<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaAnggaran;
use App\Models\KodeRekening;
use App\Models\Anggaran;
use App\Models\DataPasien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RencanaAnggaranController extends Controller
{
    // Helper: bersihkan format Rupiah (hilangkan titik, ganti koma)
    private function cleanRupiah($value)
    {
        if (empty($value))
            return 0;
        $clean = preg_replace('/[^0-9,-]/', '', $value);
        $clean = str_replace('.', '', $clean);
        $clean = str_replace(',', '.', $clean);
        return (float) $clean;
    }

    // Helper: validasi anggaran terhadap rekomendasi (max 110%)
    private function validateAnggaranAgainstRekomendasi($tahunRencana, $totalAnggaran)
    {
        // Data historis tahun sebelumnya
        $tahunData = $tahunRencana - 1;
        $rekomendasi = DataPasien::getRekomendasiAnggaran($tahunData);

        // Jika tidak ada data historis, validasi dilewatkan (bisa diubah sesuai kebijakan)
        if (!$rekomendasi['ada_data']) {
            return true;
        }

        $batasMaksimum = $rekomendasi['anggaran_per_tahun'] * 1.10; // 110%
        return $totalAnggaran <= $batasMaksimum;
    }

    // INDEX
    public function index(Request $request)
    {
        $query = RencanaAnggaran::with('kodeRekening', 'pengaju', 'penyetuju')
            ->orderBy('tahun', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('kodeRekening', function ($subq) use ($search) {
                    $subq->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama_rekening', 'like', "%{$search}%");
                })->orWhere('nama_rencana', 'like', "%{$search}%")
                    ->orWhere('tahun', 'like', "%{$search}%");
            });
        }

        // Clone untuk statistik
        $statQuery = clone $query;

        $totalRencana = $statQuery->count();
        $totalDisetujui = (clone $statQuery)->where('status', 'disetujui')->count();
        $totalDiajukan = (clone $statQuery)->where('status', 'diajukan')->count();
        $totalAnggaran = $statQuery->sum('total_anggaran');

        // List tahun untuk filter
        $tahunList = RencanaAnggaran::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Pagination
        $rencana = $query->paginate(10);
        $rencana->appends($request->query());

        // Tambahkan rekomendasi keputusan
        foreach ($rencana as $item) {
            $item->rekomendasi_keputusan = DataPasien::getRekomendasiKeputusan($item->tahun, $item->total_anggaran);
        }

        $kodeRekening = KodeRekening::orderBy('kode', 'asc')->get();

        return view('admin.rencanaAnggaran', compact(
            'rencana',
            'kodeRekening',
            'tahunList',
            'totalRencana',
            'totalDisetujui',
            'totalDiajukan',
            'totalAnggaran'
        ));
    }

    // CREATE form
    public function create()
    {
        $kodeRekening = KodeRekening::orderBy('kode', 'asc')->get();
        return view('admin.rencana.create', compact('kodeRekening'));
    }

    // STORE - dengan validasi 110%
    public function store(Request $request)
    {
        // Bersihkan format rupiah dari input
        $cleanTotal = $this->cleanRupiah($request->total_anggaran);
        $request->merge(['total_anggaran' => $cleanTotal]);

        // Validasi standar
        $request->validate([
            'kode_rekening_id' => 'required|exists:kode_rekening,id',
            'nama_rencana' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2099',
            'total_anggaran' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // VALIDASI REKOMENDASI 110%
        if (!$this->validateAnggaranAgainstRekomendasi($request->tahun, $request->total_anggaran)) {
            return redirect()->back()->withInput()->with(
                'error',
                'Nominal rencana anggaran melebihi batas maksimum 110% dari rekomendasi sistem.'
            );
        }

        // Simpan data
        $rencana = new RencanaAnggaran();
        $rencana->kode_rekening_id = $request->kode_rekening_id;
        $rencana->nama_rencana = $request->nama_rencana;
        $rencana->tahun = $request->tahun;
        $rencana->total_anggaran = $request->total_anggaran;
        $rencana->keterangan = $request->keterangan;
        $rencana->status = 'diajukan';
        $rencana->diajukan_oleh = Auth::id();
        $rencana->save();

        return redirect()->route('admin.rencana.index', ['tahun' => $request->tahun])
            ->with('success', 'Rencana anggaran berhasil ditambahkan.');
    }

    // SHOW detail
    public function show(RencanaAnggaran $rencana)
    {
        $rencana->load('kodeRekening', 'pengaju', 'penyetuju');
        $rencana->rekomendasi_keputusan = DataPasien::getRekomendasiKeputusan($rencana->tahun, $rencana->total_anggaran);
        return view('admin.rencana.show', compact('rencana'));
    }

    // EDIT form
    public function edit(RencanaAnggaran $rencana)
    {
        // Hak akses
        if (Auth::user()->role != 'admin' && Auth::id() != $rencana->diajukan_oleh) {
            abort(403, 'Unauthorized action. Anda tidak berhak mengedit rencana ini.');
        }

        // Status yang dapat diedit
        if (!in_array($rencana->status, ['diajukan', 'revisi'])) {
            return redirect()->route('admin.rencana.index')
                ->with('error', 'Rencana anggaran dengan status ' . $rencana->status . ' tidak dapat diedit.');
        }

        $kodeRekening = KodeRekening::orderBy('kode', 'asc')->get();
        return view('admin.rencana.edit', compact('rencana', 'kodeRekening'));
    }

    // UPDATE - dengan validasi 110%
    public function update(Request $request, RencanaAnggaran $rencana)
    {
        // Hak akses
        if (Auth::user()->role != 'admin' && Auth::id() != $rencana->diajukan_oleh) {
            abort(403, 'Unauthorized action. Anda tidak berhak mengupdate rencana ini.');
        }

        // Status yang dapat diupdate
        if (!in_array($rencana->status, ['diajukan', 'revisi'])) {
            return redirect()->route('admin.rencana.index')
                ->with('error', 'Rencana anggaran dengan status ' . $rencana->status . ' tidak dapat diubah.');
        }

        // Bersihkan total_anggaran
        $cleanTotal = $this->cleanRupiah($request->total_anggaran);
        $request->merge(['total_anggaran' => $cleanTotal]);

        // Validasi standar
        $request->validate([
            'kode_rekening_id' => 'required|exists:kode_rekening,id',
            'nama_rencana' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2099',
            'total_anggaran' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // VALIDASI REKOMENDASI 110%
        if (!$this->validateAnggaranAgainstRekomendasi($request->tahun, $request->total_anggaran)) {
            return redirect()->back()->withInput()->with(
                'error',
                'Nominal rencana anggaran melebihi batas maksimum 110% dari rekomendasi sistem.'
            );
        }

        // Update
        $rencana->kode_rekening_id = $request->kode_rekening_id;
        $rencana->nama_rencana = $request->nama_rencana;
        $rencana->tahun = $request->tahun;
        $rencana->total_anggaran = $request->total_anggaran;
        $rencana->keterangan = $request->keterangan;
        $rencana->save();

        return redirect()->route('admin.rencana.index', ['tahun' => $request->tahun])
            ->with('success', 'Rencana anggaran berhasil diperbarui.');
    }

    // DESTROY
    public function destroy(RencanaAnggaran $rencana)
    {
        // Hak akses
        if (Auth::user()->role != 'admin' && Auth::id() != $rencana->diajukan_oleh) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Anda tidak berhak menghapus rencana ini.'
            ], 403);
        }

        // Status yang dapat dihapus
        if (!in_array($rencana->status, ['diajukan', 'revisi'])) {
            return response()->json([
                'success' => false,
                'message' => 'Rencana anggaran dengan status ' . $rencana->status . ' tidak dapat dihapus.'
            ], 422);
        }

        $rencana->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rencana anggaran berhasil dihapus.'
        ]);
    }

    // APPROVE
    public function approve(RencanaAnggaran $rencana)
    {
        // Hanya admin yang boleh approve
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya Admin yang dapat menyetujui rencana.'
            ], 403);
        }

        if ($rencana->status !== 'diajukan') {
            return response()->json([
                'success' => false,
                'message' => 'Rencana anggaran tidak dapat disetujui karena statusnya ' . $rencana->status
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update status rencana
            $rencana->status = 'disetujui';
            $rencana->disetujui_oleh = Auth::id();
            $rencana->tanggal_persetujuan = now();
            $rencana->save();

            // Buat anggaran otomatis
            Anggaran::create([
                'rencana_anggaran_id' => $rencana->id,
                'tahun' => $rencana->tahun,
                'total_anggaran' => $rencana->total_anggaran,
                'sisa_anggaran' => $rencana->total_anggaran,
                'status' => 'aktif',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rencana anggaran berhasil disetujui dan anggaran otomatis dibuat.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // REJECT
    public function reject(Request $request, RencanaAnggaran $rencana)
    {
        // Hanya admin yang boleh reject
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya Admin yang dapat menolak rencana.'
            ], 403);
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        if ($rencana->status !== 'diajukan') {
            return response()->json([
                'success' => false,
                'message' => 'Rencana anggaran tidak dapat ditolak karena statusnya ' . $rencana->status
            ], 422);
        }

        $rencana->status = 'ditolak';
        $rencana->disetujui_oleh = Auth::id();
        $rencana->tanggal_persetujuan = now();
        $rencana->keterangan = ($rencana->keterangan ? $rencana->keterangan . "\n\n" : '') .
            'Alasan Penolakan: ' . $request->alasan_penolakan;
        $rencana->save();

        return response()->json([
            'success' => true,
            'message' => 'Rencana anggaran berhasil ditolak.'
        ]);
    }

    // REQUEST REVISION
    public function requestRevision(Request $request, RencanaAnggaran $rencana)
    {
        // Hanya admin yang boleh minta revisi
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya Admin yang dapat meminta revisi.'
            ], 403);
        }

        $request->validate([
            'catatan_revisi' => 'required|string|max:500',
        ]);

        if ($rencana->status !== 'diajukan') {
            return response()->json([
                'success' => false,
                'message' => 'Rencana anggaran tidak dapat direvisi karena statusnya ' . $rencana->status
            ], 422);
        }

        $rencana->status = 'revisi';
        $rencana->disetujui_oleh = Auth::id();
        $rencana->tanggal_persetujuan = now();
        $rencana->keterangan = ($rencana->keterangan ? $rencana->keterangan . "\n\n" : '') .
            'Catatan Revisi: ' . $request->catatan_revisi;
        $rencana->save();

        return response()->json([
            'success' => true,
            'message' => 'Permintaan revisi berhasil dikirim.'
        ]);
    }

    // GET REKOMENDASI (AJAX) - berdasarkan tahun (data historis tahun tersebut)
    public function getRekomendasi(Request $request)
    {
        $tahun = $request->tahun;

        if (!$tahun) {
            return response()->json([
                'success' => false,
                'message' => 'Tahun tidak boleh kosong'
            ]);
        }

        // $tahun di sini adalah tahun data historis (bukan tahun rencana)
        $rekomendasi = DataPasien::getRekomendasiAnggaran($tahun);

        // Format angka untuk tampilan
        if ($rekomendasi['ada_data']) {
            $rekomendasi['total_pasien_formatted'] = number_format($rekomendasi['total_pasien'], 0, ',', '.');
            $rekomendasi['rata_rata_per_bulan_formatted'] = number_format($rekomendasi['rata_rata_per_bulan'], 2, ',', '.');
            $rekomendasi['anggaran_per_bulan_formatted'] = 'Rp ' . number_format($rekomendasi['anggaran_per_bulan'], 0, ',', '.');
            $rekomendasi['anggaran_per_tahun_formatted'] = 'Rp ' . number_format($rekomendasi['anggaran_per_tahun'], 0, ',', '.');
            $rekomendasi['harga_makanan_formatted'] = 'Rp ' . number_format($rekomendasi['harga_makanan'], 0, ',', '.');
        }

        return response()->json([
            'success' => true,
            'data' => $rekomendasi
        ]);
    }

    // GET REKOMENDASI KEPUTUSAN (untuk satu rencana)
    public function getRekomendasiKeputusan($id)
    {
        $rencana = RencanaAnggaran::findOrFail($id);
        $rekomendasi = DataPasien::getRekomendasiKeputusan($rencana->tahun, $rencana->total_anggaran);

        return response()->json([
            'success' => true,
            'data' => $rekomendasi
        ]);
    }
}