<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanAnggaran;
use App\Models\Anggaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanAnggaranController extends Controller
{
    // Helper: bersihkan format Rupiah
    private function cleanRupiah($value)
    {
        if (empty($value))
            return 0;
        $clean = preg_replace('/[^0-9,-]/', '', $value);
        $clean = str_replace('.', '', $clean);
        $clean = str_replace(',', '.', $clean);
        return (float) $clean;
    }

    // INDEX
    public function index(Request $request)
    {
        $query = PengajuanAnggaran::with(['anggaran.rencanaAnggaran.kodeRekening', 'pengaju', 'penyetuju'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('pengaju', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('anggaran.rencanaAnggaran', function ($r) use ($search) {
                        $r->where('nama_rencana', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('tahun')) {
            $query->whereHas('anggaran', function ($q) use ($request) {
                $q->where('tahun', $request->tahun);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        $statQuery = clone $query;
        $totalPengajuan = $statQuery->count();
        $totalDiajukan = (clone $statQuery)->where('status', 'diajukan')->count();
        $totalDisetujui = (clone $statQuery)->where('status', 'disetujui')->count();
        $totalDitolak = (clone $statQuery)->where('status', 'ditolak')->count();
        $totalDirealisasi = (clone $statQuery)->where('status', 'direalisasi')->count();

        $tahunList = Anggaran::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $pengajuan = $query->paginate(10)->appends($request->query());

        return view('admin.daftarPengajuan', compact(
            'pengajuan',
            'tahunList',
            'totalPengajuan',
            'totalDiajukan',
            'totalDisetujui',
            'totalDitolak',
            'totalDirealisasi'
        ));
    }

    // CREATE form
    public function create()
    {
        $anggaran = Anggaran::with('rencanaAnggaran.kodeRekening')
            ->where('status', 'aktif')
            ->where('sisa_anggaran', '>', 0)
            ->get();
        $users = User::all();

        $lastPengajuan = PengajuanAnggaran::orderBy('id', 'desc')->first();
        $lastNumber = $lastPengajuan ? (int) substr($lastPengajuan->nomor_pengajuan, -3) + 1 : 1;
        $nomor_pengajuan = 'PA-' . date('Y') . '-' . str_pad($lastNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.pengajuan.create', compact('anggaran', 'users', 'nomor_pengajuan'));
    }

    // STORE - dengan validasi sisa anggaran
    public function store(Request $request)
    {
        // Bersihkan format rupiah
        $cleanJumlah = $this->cleanRupiah($request->jumlah_diajukan);
        $request->merge(['jumlah_diajukan' => $cleanJumlah]);

        $request->validate([
            'nomor_pengajuan' => 'required|unique:pengajuan_anggaran',
            'anggaran_id' => 'required|exists:anggaran,id',
            'tanggal_pengajuan' => 'required|date',
            'bulan' => 'required|integer|between:1,12',
            'jumlah_diajukan' => 'required|numeric|min:0',
            'diajukan_oleh' => 'required|exists:users,id',
            'keterangan' => 'nullable|string',
        ]);

        $anggaran = Anggaran::find($request->anggaran_id);

        // VALIDASI SISA ANGGARAN
        if ($anggaran->sisa_anggaran < $request->jumlah_diajukan) {
            return redirect()->back()->withInput()->with(
                'error',
                'Nominal pengajuan melebihi sisa pagu anggaran yang tersedia. Sisa: Rp ' .
                number_format($anggaran->sisa_anggaran, 0, ',', '.')
            );
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['status'] = 'diajukan';
            PengajuanAnggaran::create($data);
            DB::commit();
            return redirect()->route('admin.pengajuan.index', ['tahun' => $anggaran->tahun])
                ->with('success', 'Pengajuan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // SHOW
    public function show($id)
    {
        $pengajuan = PengajuanAnggaran::with(['anggaran.rencanaAnggaran.kodeRekening', 'pengaju', 'penyetuju'])->findOrFail($id);
        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    // EDIT form
    public function edit($id)
    {
        $pengajuan = PengajuanAnggaran::findOrFail($id);
        if ($pengajuan->status !== 'diajukan') {
            return redirect()->route('admin.pengajuan.index')->with('error', 'Hanya pengajuan diajukan yang bisa diedit.');
        }
        if (Auth::user()->role != 'admin' && Auth::id() != $pengajuan->diajukan_oleh) {
            return redirect()->route('admin.pengajuan.index')->with('error', 'Tidak berhak.');
        }
        $anggaran = Anggaran::with('rencanaAnggaran.kodeRekening')->where('status', 'aktif')->get();
        $users = User::all();
        return view('admin.pengajuan.edit', compact('pengajuan', 'anggaran', 'users'));
    }

    // UPDATE - dengan validasi sisa anggaran
    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanAnggaran::findOrFail($id);
        if ($pengajuan->status !== 'diajukan') {
            return redirect()->route('admin.pengajuan.index')->with('error', 'Hanya pengajuan diajukan yang bisa diupdate.');
        }
        if (Auth::user()->role != 'admin' && Auth::id() != $pengajuan->diajukan_oleh) {
            return redirect()->route('admin.pengajuan.index')->with('error', 'Tidak berhak.');
        }

        $cleanJumlah = $this->cleanRupiah($request->jumlah_diajukan);
        $request->merge(['jumlah_diajukan' => $cleanJumlah]);

        $request->validate([
            'anggaran_id' => 'required|exists:anggaran,id',
            'tanggal_pengajuan' => 'required|date',
            'bulan' => 'required|integer|between:1,12',
            'jumlah_diajukan' => 'required|numeric|min:0',
            'diajukan_oleh' => 'required|exists:users,id',
            'keterangan' => 'nullable|string',
        ]);

        $anggaran = Anggaran::find($request->anggaran_id);
        if ($anggaran->sisa_anggaran < $request->jumlah_diajukan) {
            return redirect()->back()->withInput()->with(
                'error',
                'Nominal pengajuan melebihi sisa pagu anggaran yang tersedia. Sisa: Rp ' .
                number_format($anggaran->sisa_anggaran, 0, ',', '.')
            );
        }

        $pengajuan->update($request->all());
        return redirect()->route('admin.pengajuan.index', ['tahun' => $anggaran->tahun])
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    // DESTROY
    public function destroy($id)
    {
        try {
            $pengajuan = PengajuanAnggaran::findOrFail($id);
            if (!in_array($pengajuan->status, ['diajukan', 'ditolak'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengajuan dengan status "Diajukan" atau "Ditolak" yang dapat dihapus.'
                ], 403);
            }
            if (Auth::user()->role != 'admin' && Auth::id() != $pengajuan->diajukan_oleh) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak berhak menghapus pengajuan ini.'
                ], 403);
            }
            $pengajuan->delete();
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    // APPROVE
    public function approve(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya Admin yang dapat menyetujui pengajuan.'
            ], 403);
        }

        try {
            DB::beginTransaction();
            $pengajuan = PengajuanAnggaran::findOrFail($id);
            if ($pengajuan->status !== 'diajukan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengajuan dengan status "Diajukan" yang dapat disetujui.'
                ], 403);
            }
            $anggaran = $pengajuan->anggaran;
            if ($anggaran->sisa_anggaran < $pengajuan->jumlah_diajukan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sisa anggaran tidak mencukupi. Sisa: Rp ' . number_format($anggaran->sisa_anggaran, 0, ',', '.')
                ], 422);
            }

            $pengajuan->update([
                'status' => 'disetujui',
                'disetujui_oleh' => Auth::id(),
                'tanggal_persetujuan' => now(),
            ]);

            $anggaran->sisa_anggaran = $anggaran->sisa_anggaran - $pengajuan->jumlah_diajukan;
            $anggaran->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil disetujui. Sisa anggaran telah diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    // REJECT
    public function reject(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya Admin yang dapat menolak pengajuan.'
            ], 403);
        }

        try {
            $request->validate([
                'keterangan' => 'required|string|max:500',
            ]);

            $pengajuan = PengajuanAnggaran::findOrFail($id);
            if ($pengajuan->status !== 'diajukan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengajuan dengan status "Diajukan" yang dapat ditolak.'
                ], 403);
            }

            $pengajuan->update([
                'status' => 'ditolak',
                'keterangan' => ($pengajuan->keterangan ? $pengajuan->keterangan . "\n\n" : '') .
                    'Alasan Penolakan: ' . $request->keterangan,
                'disetujui_oleh' => Auth::id(),
                'tanggal_persetujuan' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    // REALISASI (opsional)
    public function realisasi(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya Admin yang dapat merealisasi pengajuan.'
            ], 403);
        }

        try {
            DB::beginTransaction();
            $pengajuan = PengajuanAnggaran::findOrFail($id);
            if ($pengajuan->status !== 'disetujui') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengajuan dengan status "Disetujui" yang dapat direalisasi.'
                ], 403);
            }
            if ($pengajuan->tanggal_realisasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan ini sudah direalisasi.'
                ], 422);
            }

            $request->validate([
                'jumlah_realisasi' => 'required|numeric|min:0|max:' . $pengajuan->jumlah_diajukan,
                'tanggal_realisasi' => 'required|date',
                'keterangan_realisasi' => 'nullable|string',
            ]);

            $pengajuan->update([
                'status' => 'direalisasi',
                'jumlah_realisasi' => $request->jumlah_realisasi,
                'tanggal_realisasi' => $request->tanggal_realisasi,
                'keterangan_realisasi' => $request->keterangan_realisasi,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil direalisasi.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal merealisasi pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }
}