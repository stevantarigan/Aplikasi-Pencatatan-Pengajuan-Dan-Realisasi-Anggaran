<?php
// app/Http/Controllers/RealisasiAnggaranController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanAnggaran;
use App\Models\Anggaran;

class RealisasiAnggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PengajuanAnggaran::with([
            'anggaran.rencanaAnggaran.kodeRekening',
            'pengaju',
            'penyetuju'
        ])
            ->where('status', 'direalisasi')
            ->orderBy('tanggal_realisasi', 'desc');

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('anggaran.rencanaAnggaran', function ($q2) use ($search) {
                    $q2->where('nama_rencana', 'like', "%{$search}%")
                        ->orWhere('nama_kegiatan', 'like', "%{$search}%");
                })
                    ->orWhere('bulan', 'like', "%{$search}%")
                    ->orWhere('keterangan_realisasi', 'like', "%{$search}%")
                    ->orWhereHas('anggaran', function ($q2) use ($search) {
                        $q2->where('tahun', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereHas('anggaran', function ($q) use ($request) {
                $q->where('tahun', $request->tahun);
            });
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $bulanMap = [
                'Januari' => 1,
                'Februari' => 2,
                'Maret' => 3,
                'April' => 4,
                'Mei' => 5,
                'Juni' => 6,
                'Juli' => 7,
                'Agustus' => 8,
                'September' => 9,
                'Oktober' => 10,
                'November' => 11,
                'Desember' => 12
            ];
            $bulanAngka = $bulanMap[$request->bulan] ?? null;
            if ($bulanAngka) {
                $query->where('bulan', $bulanAngka);
            }
        }

        // 🔴 CLONE QUERY UNTUK STATISTIK (agar tidak mengganggu query asli)
        $statQuery = clone $query;

        // Statistik berdasarkan filter
        $totalRealisasi = $statQuery->count();
        $totalNominal = $statQuery->sum('jumlah_realisasi');
        $bulanTercatat = $statQuery->select('bulan')->distinct()->count();
        $rataRata = $totalRealisasi > 0 ? $totalNominal / $totalRealisasi : 0;

        // Ambil daftar tahun untuk filter (semua tahun dari anggaran)
        $tahunList = Anggaran::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Data dengan paginasi
        $realisasi = $query->paginate(10);
        $realisasi->appends($request->query());

        // Ambil data pengajuan untuk form tambah realisasi (yang statusnya disetujui)
        $pengajuan = PengajuanAnggaran::with([
            'anggaran.rencanaAnggaran.kodeRekening',
            'pengaju'
        ])
            ->where('status', 'disetujui')
            ->orderBy('tanggal_persetujuan', 'desc')
            ->get();

        return view('admin.daftarRealisasi', compact(
            'realisasi',
            'totalRealisasi',
            'totalNominal',
            'bulanTercatat',
            'rataRata',
            'tahunList',
            'pengajuan'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data pengajuan yang sudah disetujui untuk direalisasi
        $pengajuan = PengajuanAnggaran::with([
            'anggaran.rencanaAnggaran.kodeRekening',
            'pengaju'
        ])
            ->where('status', 'disetujui')
            ->whereNull('tanggal_realisasi')
            ->orderBy('tanggal_persetujuan', 'desc')
            ->get();

        return view('admin.realisasi.create', compact('pengajuan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Bersihkan format Rupiah dari input jumlah_realisasi
        $jumlahRealisasi = $this->cleanRupiah($request->jumlah_realisasi);

        $request->merge(['jumlah_realisasi' => $jumlahRealisasi]);

        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuan_anggaran,id',
            'jumlah_realisasi' => 'required|numeric|min:0',
            'tanggal_realisasi' => 'required|date',
            'keterangan_realisasi' => 'nullable|string',
        ]);

        $pengajuan = PengajuanAnggaran::findOrFail($request->pengajuan_id);

        // Validasi status harus disetujui
        if ($pengajuan->status !== 'disetujui') {
            return back()
                ->withInput()
                ->withErrors(['pengajuan_id' => 'Hanya pengajuan dengan status "Disetujui" yang dapat direalisasi.']);
        }

        // Validasi sudah pernah direalisasi
        if ($pengajuan->tanggal_realisasi) {
            return back()
                ->withInput()
                ->withErrors(['pengajuan_id' => 'Pengajuan ini sudah pernah direalisasi.']);
        }

        // Validasi jumlah realisasi tidak melebihi yang diajukan
        if ($request->jumlah_realisasi > $pengajuan->jumlah_diajukan) {
            return back()
                ->withInput()
                ->withErrors(['jumlah_realisasi' => 'Jumlah realisasi tidak boleh melebihi jumlah yang diajukan (Rp ' . number_format($pengajuan->jumlah_diajukan, 0, ',', '.') . ')']);
        }

        // Update data pengajuan menjadi realisasi
        $pengajuan->update([
            'jumlah_realisasi' => $request->jumlah_realisasi,
            'tanggal_realisasi' => $request->tanggal_realisasi,
            'keterangan_realisasi' => $request->keterangan_realisasi,
            'status' => 'direalisasi',
        ]);

        return redirect()->route('admin.realisasi.index', ['tahun' => $pengajuan->anggaran->tahun])
            ->with('success', 'Realisasi anggaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $realisasi = PengajuanAnggaran::with([
            'anggaran.rencanaAnggaran.kodeRekening',
            'pengaju',
            'penyetuju'
        ])
            ->where('status', 'direalisasi')
            ->findOrFail($id);

        return view('admin.realisasi.show', compact('realisasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $realisasi = PengajuanAnggaran::with([
            'anggaran.rencanaAnggaran.kodeRekening',
            'pengaju'
        ])
            ->where('status', 'direalisasi')
            ->findOrFail($id);

        return view('admin.realisasi.edit', compact('realisasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $realisasi = PengajuanAnggaran::where('status', 'direalisasi')->findOrFail($id);

        // Bersihkan format Rupiah dari input jumlah_realisasi
        $jumlahRealisasi = $this->cleanRupiah($request->jumlah_realisasi);

        $request->merge(['jumlah_realisasi' => $jumlahRealisasi]);

        $request->validate([
            'jumlah_realisasi' => 'required|numeric|min:0',
            'tanggal_realisasi' => 'required|date',
            'keterangan_realisasi' => 'nullable|string',
        ]);

        // Validasi jumlah realisasi tidak melebihi yang diajukan
        if ($request->jumlah_realisasi > $realisasi->jumlah_diajukan) {
            return back()
                ->withInput()
                ->withErrors(['jumlah_realisasi' => 'Jumlah realisasi tidak boleh melebihi jumlah yang diajukan (Rp ' . number_format($realisasi->jumlah_diajukan, 0, ',', '.') . ')']);
        }

        $realisasi->update([
            'jumlah_realisasi' => $request->jumlah_realisasi,
            'tanggal_realisasi' => $request->tanggal_realisasi,
            'keterangan_realisasi' => $request->keterangan_realisasi,
        ]);

        return redirect()->route('admin.realisasi.index', ['tahun' => $realisasi->anggaran->tahun])
            ->with('success', 'Realisasi anggaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $realisasi = PengajuanAnggaran::where('status', 'direalisasi')->findOrFail($id);

            // Kembalikan status menjadi disetujui
            $realisasi->update([
                'status' => 'disetujui',
                'jumlah_realisasi' => null,
                'tanggal_realisasi' => null,
                'keterangan_realisasi' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Realisasi anggaran berhasil dihapus. Data dikembalikan ke status Disetujui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus realisasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean Rupiah format to number
     */
    private function cleanRupiah($value)
    {
        if (empty($value)) {
            return 0;
        }

        // Hapus "Rp" jika ada, hapus titik, dan ganti koma dengan titik
        $clean = preg_replace('/[^0-9,-]/', '', $value);
        $clean = str_replace('.', '', $clean);
        $clean = str_replace(',', '.', $clean);

        return floatval($clean);
    }
}