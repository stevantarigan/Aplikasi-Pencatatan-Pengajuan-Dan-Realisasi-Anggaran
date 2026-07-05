<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\RencanaAnggaran;
use App\Models\PengajuanAnggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ========== DATA STATISTIK UTAMA ==========

        // Anggaran Aktif
        $anggaranAktif = Anggaran::where('status', 'aktif')->count();

        // Total Realisasi (dari pengajuan yang sudah direalisasi)
        $totalRealisasi = PengajuanAnggaran::where('status', 'direalisasi')
            ->sum('jumlah_realisasi');

        // Total Anggaran (semua anggaran)
        $totalAnggaran = Anggaran::sum('total_anggaran');

        // Sisa Anggaran
        $sisaAnggaran = $totalAnggaran - $totalRealisasi;

        // Persentase Pemakaian Anggaran
        $persentasePemakaian = $totalAnggaran > 0
            ? round(($totalRealisasi / $totalAnggaran) * 100, 1)
            : 0;

        // ========== DETAIL ANGGARAN AKTIF ==========
        $anggaranAktifList = Anggaran::with('rencanaAnggaran')
            ->where('status', 'aktif')
            ->orderBy('tahun', 'desc')
            ->get()
            ->map(function ($anggaran) {
                // Hitung total realisasi per anggaran
                $totalRealisasiPerAnggaran = $anggaran->pengajuan()
                    ->where('status', 'direalisasi')
                    ->sum('jumlah_realisasi');

                $anggaran->total_realisasi = $totalRealisasiPerAnggaran;
                $anggaran->persentase_realisasi = $anggaran->total_anggaran > 0
                    ? round(($totalRealisasiPerAnggaran / $anggaran->total_anggaran) * 100, 1)
                    : 0;

                return $anggaran;
            });

        // ========== PENGAJUAN MENUNGGU VALIDASI ==========
        $pengajuanMenunggu = PengajuanAnggaran::with('pengaju')
            ->where('status', 'diajukan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pengajuanMenungguCount = PengajuanAnggaran::where('status', 'diajukan')->count();

        // ========== REALISASI TERBARU ==========
        $realisasiTerbaru = PengajuanAnggaran::with('pengaju')
            ->where('status', 'direalisasi')
            ->whereNotNull('tanggal_realisasi')
            ->orderBy('tanggal_realisasi', 'desc')
            ->limit(5)
            ->get();

        // ========== RENCANA ANGGARAN TERBARU ==========
        $rencanaTerbaru = RencanaAnggaran::with('pengaju')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'anggaranAktif',
            'totalRealisasi',
            'totalAnggaran',
            'sisaAnggaran',
            'persentasePemakaian',
            'anggaranAktifList',
            'pengajuanMenunggu',
            'pengajuanMenungguCount',
            'realisasiTerbaru',
            'rencanaTerbaru'
        ));
    }
}