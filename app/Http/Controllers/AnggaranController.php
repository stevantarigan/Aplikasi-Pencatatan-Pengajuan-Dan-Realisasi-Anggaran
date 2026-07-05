<?php
// app/Http/Controllers/AnggaranController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggaran;
use App\Models\RencanaAnggaran;
use App\Models\PengajuanAnggaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnggaranController extends Controller
{
    /**
     * Display a listing of the anggaran.
     * Semua role bisa melihat (Admin, Kepala Gizi, Staff)
     */
    public function index(Request $request)
    {
        // CEK DAN GENERATE ANGGARAN DARI RENCANA YANG SUDAH DISETUJUI TAPI BELUM ADA ANGGARANNYA
        $this->generateAnggaranDariRencanaLama();

        $query = Anggaran::with(['rencanaAnggaran.kodeRekening', 'rencanaAnggaran.pengaju'])
            ->orderBy('tahun', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tahun', 'like', "%{$search}%")
                    ->orWhereHas('rencanaAnggaran', function ($subq) use ($search) {
                        $subq->where('nama_rencana', 'like', "%{$search}%")
                            ->orWhereHas('kodeRekening', function ($subq2) use ($search) {
                                $subq2->where('kode', 'like', "%{$search}%")
                                    ->orWhere('nama_rekening', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔴 CLONE QUERY UNTUK STATISTIK (agar tidak mengganggu query asli)
        $statQuery = clone $query;

        // 🔴 PERUBAHAN: Statistik hanya menghitung anggaran dengan status AKTIF
        $totalAnggaran = $statQuery->count();
        $totalTahun = $statQuery->select('tahun')->distinct()->count();

        // Hanya hitung nominal dari anggaran AKTIF
        $totalNominal = $statQuery->where('status', 'aktif')->sum('total_anggaran');
        $totalSisa = $statQuery->where('status', 'aktif')->sum('sisa_anggaran');

        // Data untuk filter tahun (semua tahun yang ada di database)
        $tahunList = Anggaran::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Pagination (menggunakan query asli)
        $anggaran = $query->paginate(10);
        $anggaran->appends($request->query());

        return view('admin.daftarAnggaran', compact(
            'anggaran',
            'tahunList',
            'totalAnggaran',
            'totalTahun',
            'totalNominal',
            'totalSisa'
        ));
    }

    /**
     * Method untuk generate anggaran dari rencana yang sudah disetujui tapi belum ada anggarannya
     */
    private function generateAnggaranDariRencanaLama()
    {
        // Ambil semua rencana dengan status disetujui yang BELUM punya anggaran
        $rencanaDisetujui = RencanaAnggaran::where('status', 'disetujui')
            ->whereDoesntHave('anggaran')
            ->get();

        $count = 0;

        foreach ($rencanaDisetujui as $rencana) {
            // Buat anggaran
            Anggaran::create([
                'rencana_anggaran_id' => $rencana->id,
                'tahun' => $rencana->tahun,
                'total_anggaran' => $rencana->total_anggaran,
                'sisa_anggaran' => $rencana->total_anggaran, // Awalnya sama dengan total
                'status' => 'aktif',
            ]);
            $count++;
        }

        if ($count > 0) {
            \Log::info("Berhasil menggenerate {$count} anggaran dari rencana lama yang disetujui");
        }
    }

    /**
     * Show the form for creating a new anggaran.
     * Hanya Admin yang bisa create
     */
    public function create()
    {
        // 🔴 CEK ROLE: Hanya admin yang bisa create
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Hanya admin yang dapat membuat anggaran.');
        }

        $rencanaDisetujui = RencanaAnggaran::with('kodeRekening')
            ->where('status', 'disetujui')
            ->whereDoesntHave('anggaran')
            ->orderBy('tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($rencanaDisetujui->isEmpty()) {
            return redirect()->route('admin.anggaran.index')
                ->with('warning', 'Tidak ada rencana anggaran disetujui yang bisa dibuat anggaran.');
        }

        return view('admin.anggaran.create', compact('rencanaDisetujui'));
    }

    /**
     * Store a newly created anggaran in storage.
     * Hanya Admin yang bisa store
     */
    public function store(Request $request)
    {
        // 🔴 CEK ROLE: Hanya admin yang bisa store
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Hanya admin yang dapat membuat anggaran.');
        }

        // Bersihkan format Rupiah sebelum validasi
        if ($request->has('total_anggaran')) {
            $cleanTotal = $this->cleanRupiah($request->total_anggaran);
            $request->merge(['total_anggaran' => $cleanTotal]);
        }

        $request->validate([
            'rencana_anggaran_id' => 'required|exists:rencana_anggaran,id',
            'tahun' => 'required|integer|min:2000|max:2099',
            'total_anggaran' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,ditutup',
        ]);

        // Cek apakah rencana anggaran sudah memiliki anggaran
        $exists = Anggaran::where('rencana_anggaran_id', $request->rencana_anggaran_id)->exists();
        if ($exists) {
            return back()->withInput()
                ->with('error', 'Rencana anggaran ini sudah memiliki data anggaran.');
        }

        // Cek status rencana anggaran
        $rencana = RencanaAnggaran::find($request->rencana_anggaran_id);
        if ($rencana->status !== 'disetujui') {
            return back()->withInput()
                ->with('error', 'Rencana anggaran harus berstatus disetujui.');
        }

        DB::beginTransaction();
        try {
            $anggaran = Anggaran::create([
                'rencana_anggaran_id' => $request->rencana_anggaran_id,
                'tahun' => $request->tahun,
                'total_anggaran' => $request->total_anggaran,
                'sisa_anggaran' => $request->total_anggaran, // Awalnya sama dengan total
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()->route('admin.anggaran.index', ['tahun' => $request->tahun])
                ->with('success', 'Anggaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified anggaran.
     * Semua role bisa melihat detail
     */
    public function show(Anggaran $anggaran)
    {
        // Update sisa anggaran sebelum ditampilkan
        $anggaran->updateSisaAnggaran();

        $anggaran->load([
            'rencanaAnggaran.kodeRekening',
            'rencanaAnggaran.pengaju',
            'rencanaAnggaran.penyetuju',
            'pengajuan' => function ($q) {
                $q->with(['pengaju', 'penyetuju'])
                    ->orderBy('tanggal_pengajuan', 'desc');
            }
        ]);

        return view('admin.anggaran.show', compact('anggaran'));
    }

    /**
     * Show the form for editing the specified anggaran.
     * Hanya Admin yang bisa edit
     */
    public function edit(Anggaran $anggaran)
    {
        // 🔴 CEK ROLE: Hanya admin yang bisa edit
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Hanya admin yang dapat mengedit anggaran.');
        }

        // Update sisa anggaran sebelum ditampilkan
        $anggaran->updateSisaAnggaran();

        // Cek apakah sudah ada pengajuan/realisasi (hanya untuk informasi)
        $hasTransactions = $anggaran->pengajuan()->exists();

        return view('admin.anggaran.edit', compact('anggaran', 'hasTransactions'));
    }

    /**
     * Update the specified anggaran in storage.
     * Hanya Admin yang bisa update
     */
    public function update(Request $request, Anggaran $anggaran)
    {
        // 🔴 CEK ROLE: Hanya admin yang bisa update
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Hanya admin yang dapat mengupdate anggaran.');
        }

        // Bersihkan format Rupiah sebelum validasi
        if ($request->has('total_anggaran')) {
            $cleanTotal = $this->cleanRupiah($request->total_anggaran);
            $request->merge(['total_anggaran' => $cleanTotal]);
        }

        $request->validate([
            'tahun' => 'required|integer|min:2000|max:2099',
            'total_anggaran' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,ditutup',
        ]);

        DB::beginTransaction();
        try {
            $oldTotal = $anggaran->total_anggaran;
            $newTotal = $request->total_anggaran;
            $oldStatus = $anggaran->status;
            $newStatus = $request->status;

            // Hitung total realisasi yang sudah ada
            $totalRealisasi = $anggaran->pengajuan()
                ->where('status', 'direalisasi')
                ->sum('jumlah_realisasi');

            $data = [
                'tahun' => $request->tahun,
                'total_anggaran' => $newTotal,
                'status' => $newStatus,
            ];

            // 🔴 PERBAIKAN SISA ANGGARAN:
            // Jika total berubah, hitung ulang sisa berdasarkan realisasi yang sudah ada
            if ($oldTotal != $newTotal) {
                // Sisa baru = total baru - total realisasi
                $data['sisa_anggaran'] = $newTotal - $totalRealisasi;

                // Validasi: sisa anggaran tidak boleh negatif jika masih ada realisasi
                if ($data['sisa_anggaran'] < 0) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', 'Total anggaran tidak boleh kurang dari total realisasi yang sudah ada (Rp ' . number_format($totalRealisasi, 0, ',', '.') . ').');
                }
            }

            // 🔴 ANGGARAN DITUTUP TETAP BISA DIEDIT
            $anggaran->update($data);

            // Update sisa anggaran sekali lagi untuk memastikan
            $anggaran->updateSisaAnggaran();

            DB::commit();

            $message = 'Anggaran berhasil diperbarui.';
            if ($oldStatus != $newStatus) {
                $statusLabel = $newStatus == 'aktif' ? 'Aktif' : 'Ditutup';
                $message = 'Anggaran berhasil diperbarui dan status diubah menjadi ' . $statusLabel . '.';
            }

            return redirect()->route('admin.anggaran.index', ['tahun' => $request->tahun])
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = $e->getMessage();

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $errorMessage);
        }
    }

    /**
     * Remove the specified anggaran from storage.
     * Hanya Admin yang bisa hapus
     */
    public function destroy(Anggaran $anggaran)
    {
        // 🔴 CEK ROLE: Hanya admin yang bisa hapus
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya admin yang dapat menghapus anggaran.'
            ], 403);
        }

        // Cek apakah sudah ada pengajuan
        if ($anggaran->pengajuan()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Anggaran tidak dapat dihapus karena sudah memiliki data pengajuan.'
            ], 422);
        }

        try {
            $anggaran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Anggaran berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status anggaran
     * Hanya Admin yang bisa update status
     */
    public function updateStatus(Request $request, Anggaran $anggaran)
    {
        // 🔴 CEK ROLE: Hanya admin yang bisa update status
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya admin yang dapat mengubah status anggaran.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:aktif,ditutup',
        ]);

        $anggaran->update(['status' => $request->status]);

        $statusLabel = $request->status == 'aktif' ? 'Aktif' : 'Ditutup';

        return response()->json([
            'success' => true,
            'message' => 'Status anggaran berhasil diperbarui menjadi ' . $statusLabel,
            'status' => $anggaran->status
        ]);
    }

    /**
     * Get detail anggaran for AJAX
     * Semua role bisa akses
     */
    public function getDetail(Anggaran $anggaran)
    {
        // Update sisa anggaran sebelum ditampilkan
        $anggaran->updateSisaAnggaran();

        $anggaran->load([
            'rencanaAnggaran.kodeRekening',
            'rencanaAnggaran.pengaju',
            'pengajuan' => function ($q) {
                $q->latest()->limit(5);
            }
        ]);

        // Tentukan class untuk sisa anggaran
        $sisa = $anggaran->sisa_anggaran;
        $sisaClass = 'text-success';

        if ($sisa <= 0) {
            $sisaClass = 'text-danger';
        } elseif ($sisa < ($anggaran->total_anggaran * 0.2)) {
            $sisaClass = 'text-warning';
        }

        // Tentukan badge status
        $statusBadge = $anggaran->status == 'aktif'
            ? '<span class="badge-status bg-success">Aktif</span>'
            : '<span class="badge-status bg-secondary">Ditutup</span>';

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $anggaran->id,
                'tahun' => $anggaran->tahun,
                'total_anggaran' => 'Rp ' . number_format($anggaran->total_anggaran, 0, ',', '.'),
                'sisa_anggaran' => 'Rp ' . number_format($sisa, 0, ',', '.'),
                'sisa_class' => $sisaClass,
                'status' => $anggaran->status,
                'status_badge' => $statusBadge,
                'rencana' => [
                    'nama' => $anggaran->rencanaAnggaran->nama_rencana ?? '-',
                    'kode_rekening' => $anggaran->rencanaAnggaran->kodeRekening->kode ?? '-',
                    'nama_rekening' => $anggaran->rencanaAnggaran->kodeRekening->nama_rekening ?? '-',
                ],
                'created_at' => $anggaran->created_at->format('d M Y H:i'),
                'updated_at' => $anggaran->updated_at->format('d M Y H:i'),
            ]
        ]);
    }

    /**
     * Membersihkan format Rupiah
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