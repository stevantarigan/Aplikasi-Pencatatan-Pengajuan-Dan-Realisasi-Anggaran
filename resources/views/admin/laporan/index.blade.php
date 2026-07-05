@extends('layouts.app')

@section('title', 'Laporan')
@section('icon', 'file-earmark-text')

@section('content')
    <div class="laporan-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Laporan</h2>
                <p class="page-subtitle">Menu laporan dan analisis data anggaran</p>
            </div>
        </div>

        <!-- Cards Menu -->
        <div class="menu-grid">
            <!-- Card Laporan Rencana Anggaran -->
            <div class="menu-card" onclick="window.location.href='{{ route('admin.laporan.rencana') }}'">
                <div class="menu-icon bg-primary">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="menu-content">
                    <h3>Laporan Rencana Anggaran</h3>
                    <p>Lihat dan export laporan rencana anggaran berdasarkan tahun, status, dan kode rekening</p>
                    <div class="menu-stats">
                        <span class="stat-badge"><i class="bi bi-calendar"></i> Filter Tahun</span>
                        <span class="stat-badge"><i class="bi bi-tag"></i> Filter Status</span>
                        <span class="stat-badge"><i class="bi bi-printer"></i> Export PDF/Excel</span>
                    </div>
                </div>
                <div class="menu-arrow">
                    <i class="bi bi-arrow-right-circle"></i>
                </div>
            </div>

            <!-- Card Laporan Perubahan Anggaran -->
            <div class="menu-card" onclick="window.location.href='{{ route('admin.laporan.perubahan') }}'">
                <div class="menu-icon bg-info">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="menu-content">
                    <h3>Laporan Perubahan Anggaran</h3>
                    <p>Perbandingan antara rencana awal dengan anggaran yang telah disetujui</p>
                    <div class="menu-stats">
                        <span class="stat-badge"><i class="bi bi-arrow-up"></i> Bertambah</span>
                        <span class="stat-badge"><i class="bi bi-arrow-down"></i> Berkurang</span>
                        <span class="stat-badge"><i class="bi bi-file-pdf"></i> Export PDF</span>
                    </div>
                </div>
                <div class="menu-arrow">
                    <i class="bi bi-arrow-right-circle"></i>
                </div>
            </div>

            <!-- Card Laporan LPJ -->
            <div class="menu-card featured" onclick="window.location.href='{{ route('admin.laporan.lpj') }}'">
                <div class="menu-icon bg-warning">
                    <i class="bi bi-bar-chart-steps"></i>
                </div>
                <div class="menu-content">
                    <h3>Laporan LPJ <span class="featured-badge">⭐</span></h3>
                    <p>Laporan pertanggungjawaban - perbandingan rencana vs realisasi anggaran per bulan</p>
                    <div class="menu-stats">
                        <span class="stat-badge"><i class="bi bi-pie-chart"></i> Realisasi per Bulan</span>
                        <span class="stat-badge"><i class="bi bi-table"></i> Detail Pengajuan</span>
                        <span class="stat-badge"><i class="bi bi-file-pdf"></i> Export LPJ</span>
                    </div>
                </div>
                <div class="menu-arrow">
                    <i class="bi bi-arrow-right-circle"></i>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-info-circle"></i>
            </div>
            <div class="info-content">
                <h5>Informasi Laporan</h5>
                <ul>
                    <li><i class="bi bi-check-circle text-primary"></i> <strong>Laporan Rencana Anggaran:</strong>
                        Menampilkan semua rencana anggaran yang telah disetujui dengan filter tahun, dan kode rekening</li>
                    <li><i class="bi bi-check-circle text-info"></i> <strong>Laporan Perubahan Anggaran:</strong>
                        Membandingkan nilai rencana awal dengan anggaran yang telah disetujui (bertambah/berkurang)</li>
                    <li><i class="bi bi-check-circle text-warning"></i> <strong>Laporan LPJ (⭐):</strong>
                        Laporan pertanggungjawaban dengan detail realisasi per bulan dan sisa anggaran</li>
                    <li><i class="bi bi-check-circle text-success"></i> <strong>Export:</strong> Semua laporan dapat
                        diexport ke format PDF</li>
                </ul>
            </div>
        </div>

        <!-- Quick Stats - HANYA RENCANA YANG DISETUJUI -->
        <div class="quick-stats">
            <h4>Statistik Cepat</h4>
            <div class="stats-grid">
                @php
                    // Hanya menghitung rencana yang disetujui
                    $totalRencanaDisetujui = \App\Models\RencanaAnggaran::where('status', 'disetujui')->count();
                    $totalAnggaran = \App\Models\RencanaAnggaran::where('status', 'disetujui')->sum('total_anggaran');
                    $totalRealisasi = \App\Models\PengajuanAnggaran::where('status', 'direalisasi')->sum(
                        'jumlah_realisasi',
                    );
                    $tahunAktif = \App\Models\RencanaAnggaran::where('status', 'disetujui')
                        ->select('tahun')
                        ->distinct()
                        ->orderBy('tahun', 'desc')
                        ->first();

                    // Hitung total pengajuan yang direalisasi
                    $totalPengajuanDirealisasi = \App\Models\PengajuanAnggaran::where('status', 'direalisasi')->count();

                    // Persentase realisasi
                    $persentaseRealisasi = $totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100, 1) : 0;

                    // Hitung perubahan anggaran
                    $totalAnggaranDisetujui = \App\Models\Anggaran::whereHas('rencanaAnggaran', function ($q) {
                        $q->where('status', 'disetujui');
                    })->sum('total_anggaran');

                    $selisihAnggaran = $totalAnggaranDisetujui - $totalAnggaran;
                    $statusPerubahan = $selisihAnggaran >= 0 ? 'positif' : 'negatif';
                @endphp

                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-files"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Rencana Disetujui</span>
                        <span class="stat-value">{{ $totalRencanaDisetujui }}</span>
                        <small class="text-muted">Total rencana yang disetujui</small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Anggaran</span>
                        <span class="stat-value">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</span>
                        <small class="text-muted">Dari rencana disetujui</small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-piggy-bank"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Realisasi</span>
                        <span class="stat-value">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</span>
                        <small class="text-muted">{{ $totalPengajuanDirealisasi }} pengajuan direalisasi</small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-info">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Persentase</span>
                        <span class="stat-value">{{ $persentaseRealisasi }}%</span>
                        <small class="text-muted">Realisasi vs Anggaran</small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-secondary">
                        <i class="bi bi-calendar"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Tahun Aktif</span>
                        <span class="stat-value">{{ $tahunAktif->tahun ?? '-' }}</span>
                        <small class="text-muted">Tahun dengan rencana disetujui</small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon {{ $statusPerubahan == 'positif' ? 'bg-success' : 'bg-danger' }}">
                        <i class="bi bi-arrow-{{ $statusPerubahan == 'positif' ? 'up' : 'down' }}"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Perubahan Anggaran</span>
                        <span class="stat-value {{ $statusPerubahan == 'positif' ? 'text-success' : 'text-danger' }}">
                            {{ $selisihAnggaran >= 0 ? '+' : '' }}Rp
                            {{ number_format(abs($selisihAnggaran), 0, ',', '.') }}
                        </span>
                        <small class="text-muted">Rencana vs Disetujui</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ============================================
               GAYA COMPACT UNTUK HALAMAN LAPORAN
               ============================================ */
        .laporan-container {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(109, 93, 252, 0.08);
        }

        .page-header {
            margin-bottom: 1rem;
        }

        .page-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.1rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.7rem;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .menu-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            cursor: pointer;
        }

        .menu-card:hover {
            transform: translateY(-2px);
            border-color: #6d5dfc;
            box-shadow: 0 5px 12px rgba(109, 93, 252, 0.08);
        }

        .menu-card.featured {
            background: #fffbeb;
            border: 2px solid #fbbf24;
        }

        .menu-card.featured:hover {
            border-color: #f59e0b;
            box-shadow: 0 5px 12px rgba(245, 158, 11, 0.15);
        }

        .featured-badge {
            background: #f59e0b;
            color: white;
            padding: 0.15rem 0.4rem;
            border-radius: 20px;
            font-size: 0.65rem;
            margin-left: 0.25rem;
            font-weight: 600;
        }

        .menu-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .menu-icon.bg-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        }

        .menu-icon.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .menu-icon.bg-info {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        }

        .menu-content {
            flex: 1;
        }

        .menu-content h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.3rem;
        }

        .menu-content p {
            color: #64748b;
            font-size: 0.7rem;
            margin-bottom: 0.6rem;
            line-height: 1.4;
        }

        .menu-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
        }

        .stat-badge {
            background: #f1f5f9;
            color: #475569;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.6rem;
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            white-space: nowrap;
        }

        .menu-arrow {
            color: #cbd5e1;
            font-size: 1.2rem;
            transition: all 0.2s ease;
            margin-top: 0.2rem;
        }

        .menu-card:hover .menu-arrow {
            color: #6d5dfc;
            transform: translateX(3px);
        }

        .menu-card.featured:hover .menu-arrow {
            color: #f59e0b;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 0.7rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            font-size: 0.7rem;
        }

        .info-icon {
            width: 34px;
            height: 34px;
            background: #e0f2fe;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0284c7;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-content h5 {
            font-size: 0.8rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.3rem;
        }

        .info-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-content ul li {
            color: #475569;
            font-size: 0.7rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.3rem;
        }

        .info-content ul li i {
            font-size: 0.7rem;
            margin-top: 0.15rem;
        }

        .info-content ul li i.text-primary {
            color: #6d5dfc;
        }

        .info-content ul li i.text-warning {
            color: #f59e0b;
        }

        .info-content ul li i.text-success {
            color: #10b981;
        }

        .info-content ul li i.text-info {
            color: #0ea5e9;
        }

        .quick-stats h4 {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.6rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 0.6rem;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 0.65rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #6d5dfc;
            box-shadow: 0 5px 12px rgba(109, 93, 252, 0.08);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }

        .stat-icon.bg-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        }

        .stat-icon.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .stat-icon.bg-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .stat-icon.bg-info {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        }

        .stat-icon.bg-secondary {
            background: linear-gradient(135deg, #64748b, #94a3b8);
        }

        .stat-icon.bg-danger {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            display: block;
            color: #64748b;
            font-size: 0.65rem;
            margin-bottom: 0.1rem;
        }

        .stat-value {
            display: block;
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        .stat-content small {
            font-size: 0.6rem;
            display: block;
            margin-top: 0.15rem;
            color: #64748b;
        }

        .text-success {
            color: #10b981 !important;
        }

        .text-danger {
            color: #ef4444 !important;
        }

        .text-muted {
            color: #64748b !important;
        }

        @media (max-width: 768px) {
            .laporan-container {
                padding: 0.75rem;
            }

            .menu-grid {
                grid-template-columns: 1fr;
            }

            .menu-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .menu-arrow {
                align-self: flex-end;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animasi ringkas */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu-card,
        .info-card,
        .stat-card {
            animation: fadeInUp 0.3s ease forwards;
        }
    </style>
@endsection
