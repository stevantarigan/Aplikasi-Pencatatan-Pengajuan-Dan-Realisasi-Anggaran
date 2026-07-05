@extends('layouts.app')

@section('title', 'Laporan LPJ')
@section('icon', 'bar-chart-steps')

@section('content')
    <div class="laporan-lpj-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Laporan LPJ</h2>
                <p class="page-subtitle">Laporan Pertanggungjawaban - Realisasi Penggunaan Anggaran per Bulan</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.laporan.index') }}" class="btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-card">
            <form action="{{ route('admin.laporan.lpj') }}" method="GET" id="filterForm">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="tahun">Tahun</label>
                        <select name="tahun" id="tahun" class="filter-select">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="kode_rekening_id">Kode Rekening</label>
                        <select name="kode_rekening_id" id="kode_rekening_id" class="filter-select">
                            <option value="">Semua Rekening</option>
                            @foreach ($kodeRekeningList as $rekening)
                                <option value="{{ $rekening->id }}"
                                    {{ request('kode_rekening_id') == $rekening->id ? 'selected' : '' }}>
                                    {{ $rekening->kode }} - {{ $rekening->nama_rekening }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="status_anggaran">Status Anggaran</label>
                        <select name="status_anggaran" id="status_anggaran" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status_anggaran') == 'aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="ditutup" {{ request('status_anggaran') == 'ditutup' ? 'selected' : '' }}>
                                Ditutup
                            </option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="search">Pencarian</label>
                        <div class="search-wrapper">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" id="search" class="filter-input"
                                placeholder="Cari nama rencana..." value="{{ request('search') }}">
                        </div>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.laporan.lpj') }}" class="btn-reset">
                        <i class="bi bi-arrow-clockwise"></i>
                        Reset
                    </a>
                    <div class="export-buttons">
                        <button type="submit" name="format" value="pdf" class="btn-pdf">
                            <i class="bi bi-file-pdf"></i>
                            Export PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistik Utama -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-files"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Rencana</span>
                    <span class="stat-value">{{ $totalRencana }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Anggaran (Final)</span>
                    <span class="stat-value">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-piggy-bank"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Realisasi</span>
                    <span class="stat-value">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="bi bi-percent"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Persentase</span>
                    <span class="stat-value">{{ $persentaseRealisasi }}%</span>
                </div>
            </div>
        </div>

        <!-- Daftar Anggaran -->
        <h3 class="section-title">DETAIL REALISASI PER ANGGARAN</h3>

        @forelse ($rencana as $index => $item)
            @php
                // Ambil total anggaran final (dari tabel Anggaran)
                $anggaranFinal = $item->anggaran->total_anggaran ?? ($item->total_anggaran ?? 0);

                $totalPengajuanAnggaran = 0;
                $totalRealisasiAnggaran = 0;
                $realisasiPerBulanAnggaran = [];

                // Ambil pengajuan unik (hindari duplikasi)
                $pengajuanList = optional($item->anggaran)->pengajuan->unique('id') ?? collect();

                foreach ($pengajuanList as $pengajuan) {
                    // Akumulasi total pengajuan (tanpa filter status)
                    $totalPengajuanAnggaran += $pengajuan->jumlah_diajukan ?? 0;

                    // Hanya hitung realisasi jika status = direalisasi
                    if ($pengajuan->status === 'direalisasi') {
                        $realisasi = $pengajuan->jumlah_realisasi ?? 0;
                        $totalRealisasiAnggaran += $realisasi;

                        $bulan = (int) $pengajuan->bulan; // pastikan integer

                        if (!isset($realisasiPerBulanAnggaran[$bulan])) {
                            $realisasiPerBulanAnggaran[$bulan] = [
                                'bulan' => $bulan,
                                'nama_bulan' => $namaBulan[$bulan] ?? 'Bulan ' . $bulan,
                                'jumlah_diajukan' => 0,
                                'jumlah_realisasi' => 0,
                                'sisa' => 0,
                            ];
                        }

                        // Akumulasi per bulan
                        $realisasiPerBulanAnggaran[$bulan]['jumlah_diajukan'] += $pengajuan->jumlah_diajukan ?? 0;
                        $realisasiPerBulanAnggaran[$bulan]['jumlah_realisasi'] += $realisasi;
                    }
                }

                // Hitung sisa per bulan (berdasarkan pengajuan - realisasi)
                foreach ($realisasiPerBulanAnggaran as $bulan => $data) {
                    $realisasiPerBulanAnggaran[$bulan]['sisa'] = $data['jumlah_diajukan'] - $data['jumlah_realisasi'];
                }

                // === PERBAIKAN: Sisa Anggaran Final (Anggaran - Realisasi) ===
                $sisaAnggaranTotal = $anggaranFinal - $totalRealisasiAnggaran;

                // Sisa berdasarkan pengajuan (hanya untuk informasi tambahan di tabel)
                $sisaPengajuan = $totalPengajuanAnggaran - $totalRealisasiAnggaran;

                // Status badge
                $statusAnggaran = optional($item->anggaran)->status ?? 'belum_ada';
                if ($statusAnggaran == 'aktif') {
                    $statusClass = 'bg-success';
                    $statusLabel = 'Aktif';
                } elseif ($statusAnggaran == 'ditutup') {
                    $statusClass = 'bg-secondary';
                    $statusLabel = 'Ditutup';
                } else {
                    $statusClass = 'bg-warning';
                    $statusLabel = 'Belum Ada';
                }
            @endphp

            <div class="anggaran-card">
                <div class="anggaran-header">
                    <div class="anggaran-title">
                        <span class="anggaran-nomor">ANGGARAN {{ $index + 1 }}</span>
                        <span class="anggaran-nama">{{ $item->nama_rencana }}</span>
                        <span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>
                </div>

                <div class="anggaran-body">
                    <!-- Informasi Anggaran -->
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Kode Rekening</span>
                            <span class="info-value">{{ optional($item->kodeRekening)->kode ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nama Rekening</span>
                            <span class="info-value">{{ optional($item->kodeRekening)->nama_rekening ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tahun</span>
                            <span class="info-value">{{ $item->tahun }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Anggaran (Final)</span>
                            <span class="info-value nilai">Rp {{ number_format($anggaranFinal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Tabel Realisasi per Bulan -->
                    <h4 class="sub-title">DETAIL REALISASI PER BULAN (Berdasarkan Pengajuan)</h4>

                    <div class="table-responsive">
                        <table class="table table-realisasi">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Jumlah Diajukan (Rp)</th>
                                    <th>Jumlah Realisasi (Rp)</th>
                                    <th>Sisa Pengajuan (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($realisasiPerBulanAnggaran))
                                    @foreach ($realisasiPerBulanAnggaran as $data)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $data['nama_bulan'] }}</td>
                                            <td class="text-right">Rp
                                                {{ number_format($data['jumlah_diajukan'], 0, ',', '.') }}</td>
                                            <td class="text-right">Rp
                                                {{ number_format($data['jumlah_realisasi'], 0, ',', '.') }}</td>
                                            <td class="text-right">Rp {{ number_format($data['sisa'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach

                                    <tr class="total-row">
                                        <td colspan="2" class="text-center fw-bold">TOTAL</td>
                                        <td class="text-right fw-bold">Rp
                                            {{ number_format($totalPengajuanAnggaran, 0, ',', '.') }}</td>
                                        <td class="text-right fw-bold">Rp
                                            {{ number_format($totalRealisasiAnggaran, 0, ',', '.') }}</td>
                                        <td class="text-right fw-bold">Rp {{ number_format($sisaPengajuan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-inbox" style="font-size: 2rem; color: #cbd5e1;"></i>
                                            <p class="mt-2 text-muted">Belum ada realisasi untuk anggaran ini</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Sisa Anggaran (Final) -->
                    <div class="sisa-anggaran">
                        <div class="sisa-item">
                            <span class="sisa-label">Total Pengajuan:</span>
                            <span class="sisa-nilai">Rp {{ number_format($totalPengajuanAnggaran, 0, ',', '.') }}</span>
                        </div>
                        <div class="sisa-item">
                            <span class="sisa-label">Total Realisasi:</span>
                            <span class="sisa-nilai">Rp {{ number_format($totalRealisasiAnggaran, 0, ',', '.') }}</span>
                        </div>
                        <div class="sisa-item">
                            <span class="sisa-label">Sisa Pengajuan:</span>
                            <span
                                class="sisa-nilai {{ $sisaPengajuan > 0 ? 'positif' : ($sisaPengajuan < 0 ? 'negatif' : '') }}">
                                Rp {{ number_format($sisaPengajuan, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="sisa-item highlight">
                            <span class="sisa-label">Sisa Anggaran Final (Anggaran - Realisasi):</span>
                            <span class="sisa-nilai {{ $sisaAnggaranTotal >= 0 ? 'positif' : 'negatif' }}">
                                Rp {{ number_format(abs($sisaAnggaranTotal), 0, ',', '.') }}
                                ({{ $sisaAnggaranTotal >= 0 ? 'Sisa' : 'Defisit' }})
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-file-earmark-x" style="font-size: 3rem; color: #cbd5e1;"></i>
                <p class="mt-3 text-muted">Tidak ada data rencana anggaran yang disetujui</p>
                <a href="{{ route('admin.laporan.lpj') }}" class="btn-empty">
                    <i class="bi bi-x-circle"></i> Reset Filter
                </a>
            </div>
        @endforelse
    </div>


    <style>
        /* ===== VARIABLES ===== */
        :root {
            --primary: #0b4f6c;
            --primary-light: #1e6f8f;
            --primary-soft: #2c7da0;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #cbd5e1;
            --border: #e2e8f0;
        }

        /* ===== MAIN CONTAINER ===== */
        .laporan-lpj-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 32px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.03);
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--dark), #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: var(--gray);
            font-size: 0.95rem;
        }

        /* ===== BUTTON STYLES ===== */
        .btn-secondary {
            background: white;
            color: #475569;
            border: 1px solid var(--border);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: var(--light);
            border-color: var(--gray-light);
            transform: translateX(-3px);
            color: var(--dark);
        }

        /* ===== FILTER CARD ===== */
        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }

        .filter-select,
        .filter-input {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: var(--warning);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-wrapper input {
            padding-left: 2.5rem;
        }

        .filter-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn-filter {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.2);
        }

        .btn-reset {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid var(--border);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-reset:hover {
            background: #e2e8f0;
            color: var(--dark);
        }

        .export-buttons {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }

        .btn-pdf {
            background: var(--danger);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-pdf:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
        }

        /* ===== STATISTICS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--warning);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }

        .stat-icon.bg-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        }

        .stat-icon.bg-warning {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
        }

        .stat-icon.bg-success {
            background: linear-gradient(135deg, var(--success), #34d399);
        }

        .stat-icon.bg-info {
            background: linear-gradient(135deg, var(--info), #38bdf8);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            display: block;
            color: var(--gray);
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            display: block;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.2;
        }

        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--warning);
        }

        /* ===== ANGGARAN CARD ===== */
        .anggaran-card {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        }

        .anggaran-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 1rem 1.5rem;
        }

        .anggaran-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .anggaran-nomor {
            font-weight: 700;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
        }

        .anggaran-nama {
            font-weight: 600;
            font-size: 1.1rem;
            flex: 1;
        }

        .anggaran-body {
            padding: 1.5rem;
        }

        /* ===== INFO GRID ===== */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: var(--light);
            border-radius: 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.8rem;
            color: var(--gray);
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 600;
            color: var(--dark);
        }

        .info-value.nilai {
            color: var(--primary);
            font-size: 1.1rem;
        }

        /* ===== SUB TITLE ===== */
        .sub-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark);
            margin: 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--warning);
        }

        /* ===== TABLE STYLES ===== */
        .table-responsive {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: white;
            margin-bottom: 1.5rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8fafc;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border);
            color: #334155;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--light);
        }

        /* Tabel Realisasi */
        .table-realisasi {
            min-width: 800px;
        }

        .table-realisasi th {
            background: var(--primary-soft);
            color: white;
            padding: 0.75rem;
            text-align: center;
        }

        .table-realisasi td {
            padding: 0.75rem;
        }

        .table-realisasi .total-row {
            background: #e2e8f0;
            font-weight: 600;
        }

        /* ===== BADGE STATUS ===== */
        .badge-status {
            display: inline-flex;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
        }

        .badge-status.bg-success {
            background: var(--success);
        }

        .badge-status.bg-secondary {
            background: var(--secondary);
        }

        .badge-status.bg-warning {
            background: var(--warning);
        }

        /* ===== SISA ANGGARAN ===== */
        .sisa-anggaran {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #f0f7fa;
            border-radius: 12px;
            border-left: 4px solid var(--primary);
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .sisa-item {
            flex: 1;
            min-width: 150px;
        }

        .sisa-item.highlight {
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .sisa-label {
            display: block;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.25rem;
        }

        .sisa-nilai {
            display: block;
            font-weight: 700;
            font-size: 1rem;
            color: var(--primary);
        }

        .sisa-nilai.positif {
            color: var(--success);
        }

        .sisa-nilai.negatif {
            color: var(--danger);
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border);
        }

        .btn-empty {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-empty:hover {
            background: #e2e8f0;
            color: var(--dark);
        }

        /* ===== UTILITY CLASSES ===== */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .fw-bold {
            font-weight: 600;
        }

        .text-muted {
            color: var(--gray) !important;
        }

        .d-block {
            display: block;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-card,
        .stat-card,
        .anggaran-card {
            animation: slideIn 0.5s ease forwards;
        }

        .anggaran-card:nth-child(odd) {
            animation-delay: 0.1s;
        }

        .anggaran-card:nth-child(even) {
            animation-delay: 0.2s;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .laporan-lpj-container {
                padding: 1rem;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
            }

            .export-buttons {
                margin-left: 0;
                width: 100%;
            }

            .btn-pdf {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .anggaran-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .sisa-anggaran {
                flex-direction: column;
                gap: 1rem;
            }

            .sisa-item {
                min-width: 100%;
            }
        }
    </style>

    <script>
        function showDetail(id) {
            console.log('Detail view untuk anggaran ' + id);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi jika diperlukan
        });
    </script>
@endsection
