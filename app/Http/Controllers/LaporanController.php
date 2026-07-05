<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaAnggaran;
use App\Models\Anggaran;
use App\Models\PengajuanAnggaran;
use App\Models\KodeRekening;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Display a listing of the laporan.
     */
    public function index(Request $request)
    {
        $tahunList = RencanaAnggaran::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $statusList = ['disetujui', 'pending', 'ditolak', 'direvisi', 'diajukan'];

        return view('admin.laporan.index', compact('tahunList', 'statusList'));
    }

    /**
     * Laporan Rencana Anggaran - HANYA MENAMPILKAN YANG DISETUJUI
     */
    public function rencanaAnggaran(Request $request)
    {
        $request->validate([
            'tahun' => 'nullable|integer|min:2000|max:2099',
            'kode_rekening_id' => 'nullable|exists:kode_rekening,id',
            'search' => 'nullable|string|max:100',
            'format' => 'nullable|in:pdf,html'
        ]);

        // QUERY HANYA UNTUK STATUS DISETUJUI
        $query = RencanaAnggaran::with(['kodeRekening', 'pengaju', 'penyetuju'])
            ->where('status', 'disetujui')
            ->orderBy('tahun', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter kode rekening
        if ($request->filled('kode_rekening_id')) {
            $query->where('kode_rekening_id', $request->kode_rekening_id);
        }

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_rencana', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('tahun', 'like', "%{$search}%")
                    ->orWhereHas('kodeRekening', function ($subq) use ($search) {
                        $subq->where('kode', 'like', "%{$search}%")
                            ->orWhere('nama_rekening', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pengaju', function ($subq) use ($search) {
                        $subq->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('penyetuju', function ($subq) use ($search) {
                        $subq->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Statistik
        $totalRencana = $query->count();
        $totalNominal = $query->sum('total_anggaran');

        // Data untuk filter
        $tahunList = RencanaAnggaran::where('status', 'disetujui')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $kodeRekeningList = KodeRekening::orderBy('kode')->get();

        $format = $request->get('format');

        // EXPORT PDF
        if ($format == 'pdf') {
            $rencana = $query->get();
            return $this->exportRencanaPdf($rencana, $request->all());
        }

        // Tampilan biasa dengan pagination
        $rencana = $query->paginate(15)->appends($request->all());

        return view('admin.laporan.rencana', compact(
            'rencana',
            'tahunList',
            'kodeRekeningList',
            'totalRencana',
            'totalNominal'
        ));
    }

    /**
     * Laporan Perubahan Anggaran - Membandingkan Rencana Awal dengan Anggaran yang Disetujui
     */
    public function perubahanAnggaran(Request $request)
    {
        $request->validate([
            'tahun' => 'nullable|integer|min:2000|max:2099',
            'kode_rekening_id' => 'nullable|exists:kode_rekening,id',
            'status_anggaran' => 'nullable|in:aktif,ditutup,semua',
            'search' => 'nullable|string|max:100',
            'format' => 'nullable|in:pdf,html'
        ]);

        // Query rencana anggaran yang sudah disetujui
        $query = RencanaAnggaran::with([
            'kodeRekening',
            'anggaran'
        ])
            ->where('status', 'disetujui')
            ->orderBy('tahun', 'desc')
            ->orderBy('nama_rencana');

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter kode rekening
        if ($request->filled('kode_rekening_id')) {
            $query->where('kode_rekening_id', $request->kode_rekening_id);
        }

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_rencana', 'like', "%{$search}%")
                    ->orWhereHas('kodeRekening', function ($subq) use ($search) {
                        $subq->where('kode', 'like', "%{$search}%")
                            ->orWhere('nama_rekening', 'like', "%{$search}%");
                    });
            });
        }

        // Ambil data
        $rencana = $query->get();

        // Filter berdasarkan status anggaran jika diperlukan
        if ($request->filled('status_anggaran') && $request->status_anggaran != 'semua') {
            $rencana = $rencana->filter(function ($item) use ($request) {
                $statusAnggaran = $item->anggaran ? $item->anggaran->status : null;
                if ($request->status_anggaran == 'aktif') {
                    return $statusAnggaran == 'aktif';
                } elseif ($request->status_anggaran == 'ditutup') {
                    return $statusAnggaran == 'ditutup';
                }
                return true;
            });
        }

        // Hitung statistik perubahan
        $totalSebelum = 0;
        $totalSesudah = 0;
        $totalBertambah = 0;
        $totalBerkurang = 0;
        $jumlahBertambah = 0;
        $jumlahBerkurang = 0;
        $jumlahTetap = 0;

        foreach ($rencana as $item) {
            $sebelum = $item->total_anggaran;
            $sesudah = $item->anggaran->total_anggaran ?? $sebelum;
            $perubahan = $sesudah - $sebelum;

            $totalSebelum += $sebelum;
            $totalSesudah += $sesudah;

            if ($perubahan > 0) {
                $totalBertambah += $perubahan;
                $jumlahBertambah++;
            } elseif ($perubahan < 0) {
                $totalBerkurang += abs($perubahan);
                $jumlahBerkurang++;
            } else {
                $jumlahTetap++;
            }
        }

        $totalPerubahan = $totalSesudah - $totalSebelum;
        $persenPerubahan = $totalSebelum > 0 ? round(($totalPerubahan / $totalSebelum) * 100, 2) : 0;

        // Statistik untuk ditampilkan
        $statistik = [
            'totalRencana' => $rencana->count(),
            'totalSebelum' => $totalSebelum,
            'totalSesudah' => $totalSesudah,
            'totalPerubahan' => $totalPerubahan,
            'persenPerubahan' => $persenPerubahan,
            'totalBertambah' => $totalBertambah,
            'totalBerkurang' => $totalBerkurang,
            'jumlahBertambah' => $jumlahBertambah,
            'jumlahBerkurang' => $jumlahBerkurang,
            'jumlahTetap' => $jumlahTetap
        ];

        // Data untuk filter
        $tahunList = RencanaAnggaran::where('status', 'disetujui')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $kodeRekeningList = KodeRekening::orderBy('kode')->get();

        $format = $request->get('format');

        // EXPORT PDF Perubahan Anggaran
        if ($format == 'pdf') {
            return $this->exportPerubahanAnggaranPdf($rencana, $request->all(), $statistik, $kodeRekeningList);
        }

        // UNTUK TAMPILAN WEB
        return view('admin.laporan.perubahan-anggaran', compact(
            'rencana',
            'tahunList',
            'kodeRekeningList',
            'statistik'
        ));
    }

    /**
     * Laporan LPJ (Rencana vs Realisasi Anggaran) - PER BULAN
     */
    /**
     * Laporan LPJ (Rencana vs Realisasi Anggaran) - PER BULAN
     */
    public function lpj(Request $request)
    {
        $request->validate([
            'tahun' => 'nullable|integer|min:2000|max:2099',
            'kode_rekening_id' => 'nullable|exists:kode_rekening,id',
            'status_anggaran' => 'nullable|in:aktif,ditutup,belum_ada,semua',
            'search' => 'nullable|string|max:100',
            'format' => 'nullable|in:pdf,html'
        ]);

        // Query rencana anggaran yang sudah disetujui
        $query = RencanaAnggaran::with([
            'kodeRekening',
            'anggaran.pengajuan' => function ($q) {
                $q->with(['pengaju', 'penyetuju'])
                    ->orderBy('bulan')
                    ->orderBy('tanggal_pengajuan');
            }
        ])
            ->where('status', 'disetujui')
            ->orderBy('tahun', 'desc')
            ->orderBy('kode_rekening_id');

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter kode rekening
        if ($request->filled('kode_rekening_id')) {
            $query->where('kode_rekening_id', $request->kode_rekening_id);
        }

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_rencana', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('kodeRekening', function ($subq) use ($search) {
                        $subq->where('kode', 'like', "%{$search}%")
                            ->orWhere('nama_rekening', 'like', "%{$search}%");
                    });
            });
        }

        // Ambil data
        $rencana = $query->get();

        // Filter berdasarkan status anggaran setelah data diambil
        if ($request->filled('status_anggaran') && $request->status_anggaran != 'semua') {
            $rencana = $rencana->filter(function ($item) use ($request) {
                $statusAnggaran = $item->anggaran ? $item->anggaran->status : 'belum_ada';
                return $statusAnggaran == $request->status_anggaran;
            });
        }

        // === PERBAIKAN: Hitung total anggaran dari tabel Anggaran (final) ===
        $totalRencana = $rencana->count();
        $totalAnggaran = 0;
        $totalRealisasi = 0;
        $totalPengajuan = 0;

        // Array untuk realisasi per bulan (untuk statistik global)
        $realisasiPerBulan = array_fill(1, 12, 0);
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        foreach ($rencana as $r) {
            // Ambil total anggaran dari tabel Anggaran (jika ada), fallback ke rencana awal
            $anggaranFinal = optional($r->anggaran)->total_anggaran ?? $r->total_anggaran;
            $totalAnggaran += $anggaranFinal;

            if ($r->anggaran) {
                foreach ($r->anggaran->pengajuan->unique('id') as $p) {
                    $totalPengajuan++;
                    if ($p->status == 'direalisasi' && $p->jumlah_realisasi) {
                        $totalRealisasi += $p->jumlah_realisasi;
                        $realisasiPerBulan[$p->bulan] += $p->jumlah_realisasi;
                    }
                }
            }
        }

        $persentaseRealisasi = $totalAnggaran > 0
            ? round(($totalRealisasi / $totalAnggaran) * 100, 2)
            : 0;

        // Data untuk filter
        $tahunList = RencanaAnggaran::where('status', 'disetujui')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $kodeRekeningList = KodeRekening::orderBy('kode')->get();

        $format = $request->get('format');

        // EXPORT PDF LPJ
        if ($format == 'pdf') {
            $statistik = [
                'totalRencana' => $totalRencana,
                'totalAnggaran' => $totalAnggaran,
                'totalRealisasi' => $totalRealisasi,
                'persentaseRealisasi' => $persentaseRealisasi,
                'totalPengajuan' => $totalPengajuan,
                'realisasiPerBulan' => $realisasiPerBulan,
                'namaBulan' => $namaBulan
            ];
            return $this->exportLpjPdf($rencana, $request->all(), $statistik, $kodeRekeningList);
        }

        // UNTUK TAMPILAN WEB
        return view('admin.laporan.lpj', compact(
            'rencana',
            'tahunList',
            'kodeRekeningList',
            'totalRencana',
            'totalAnggaran',
            'totalRealisasi',
            'persentaseRealisasi',
            'totalPengajuan',
            'realisasiPerBulan',
            'namaBulan'
        ));
    }

    /**
     * Export rencana ke PDF
     */
    private function exportRencanaPdf($data, $filters)
    {
        $pdf = PDF::loadView('admin.laporan.pdf.rencana', [
            'rencana' => $data,
            'filters' => $filters
        ]);

        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        $filename = 'laporan-rencana-anggaran-' . date('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export Perubahan Anggaran ke PDF
     */
    private function exportPerubahanAnggaranPdf($data, $filters, $statistik, $kodeRekeningList)
    {
        $pdf = PDF::loadView('admin.laporan.pdf.perubahan-anggaran', [
            'rencana' => $data,
            'filters' => $filters,
            'statistik' => $statistik,
            'kodeRekeningList' => $kodeRekeningList
        ]);

        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        $filename = 'laporan-perubahan-anggaran-' . date('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export LPJ ke PDF - DENGAN REALISASI PER BULAN
     */
    private function exportLpjPdf($data, $filters, $statistik, $kodeRekeningList)
    {
        $pdf = PDF::loadView('admin.laporan.pdf.lpj', [
            'rencana' => $data,
            'filters' => $filters,
            'statistik' => $statistik,
            'kodeRekeningList' => $kodeRekeningList
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        $filename = 'laporan-lpj-' . date('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }
}