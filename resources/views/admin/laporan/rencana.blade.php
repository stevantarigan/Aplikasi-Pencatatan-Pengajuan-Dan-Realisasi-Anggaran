@extends('layouts.app')

@section('title', 'Laporan Rencana Anggaran')
@section('icon', 'file-text')

@section('content')
    <div class="laporan-rencana-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">
                    <i class="bi bi-file-text me-2"></i>
                    Laporan Rencana Anggaran
                </h2>
                <p class="page-subtitle">Data rencana anggaran yang telah disetujui</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.laporan.index') }}" class="btn-outline">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-card">
            <div class="filter-header">
                <i class="bi bi-funnel"></i>
                <span>Filter Data</span>
            </div>
            <form action="{{ route('admin.laporan.rencana') }}" method="GET" id="filterForm">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="tahun">
                            <i class="bi bi-calendar3"></i>
                            Tahun
                        </label>
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
                        <label for="kode_rekening_id">
                            <i class="bi bi-tag"></i>
                            Kode Rekening
                        </label>
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
                        <label for="search">
                            <i class="bi bi-search"></i>
                            Pencarian
                        </label>
                        <div class="search-wrapper">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" id="search" class="filter-input"
                                placeholder="Cari nama rencana, kode rekening..." value="{{ request('search') }}">
                        </div>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.laporan.rencana') }}" class="btn-reset">
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

        <!-- Statistik -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-files"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Rencana Disetujui</span>
                    <span class="stat-value">{{ $totalRencana }}</span>
                    <small class="stat-footer">
                        <i class="bi bi-check-circle"></i>
                        Rencana yang telah disetujui
                    </small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Anggaran</span>
                    <span class="stat-value">Rp {{ number_format($totalNominal, 0, ',', '.') }}</span>
                    <small class="stat-footer">
                        <i class="bi bi-piggy-bank"></i>
                        Nilai total anggaran disetujui
                    </small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Tahun Aktif</span>
                    <span class="stat-value">{{ request('tahun') ?: 'Semua' }}</span>
                    <small class="stat-footer">
                        <i class="bi bi-clock"></i>
                        Periode laporan
                    </small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Status</span>
                    <span class="stat-value">Disetujui</span>
                    <small class="stat-footer">
                        <i class="bi bi-shield-check"></i>
                        Rencana terverifikasi
                    </small>
                </div>
            </div>
        </div>

        <!-- Info Summary -->
        <div class="summary-card">
            <div class="summary-icon">
                <i class="bi bi-info-circle"></i>
            </div>
            <div class="summary-content">
                <strong>Informasi:</strong> Menampilkan {{ $rencana->total() }} data rencana anggaran yang telah disetujui.
                Data dapat difilter berdasarkan tahun dan kode rekening.
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Kode Rekening</th>
                        <th>Nama Rencana</th>
                        <th>Total Anggaran</th>
                        <th>Diajukan Oleh</th>
                        <th>Disetujui Oleh</th>
                        <th>Tgl Persetujuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rencana as $index => $item)
                        <tr>
                            <td class="text-center">{{ $rencana->firstItem() + $index }}</td>
                            <td>
                                <span class="badge-tahun">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $item->tahun }}
                                </span>
                            </td>
                            <td>
                                @if ($item->kodeRekening)
                                    <div class="kode-rekening-info">
                                        <span class="kode">{{ $item->kodeRekening->kode }}</span>
                                        <small class="nama">{{ $item->kodeRekening->nama_rekening }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="rencana-info">
                                    <span class="nama">{{ $item->nama_rencana }}</span>
                                    @if ($item->deskripsi)
                                        <small class="deskripsi">{{ Str::limit($item->deskripsi, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right">
                                <span class="amount">Rp {{ number_format($item->total_anggaran, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <div class="user-info">
                                    <span class="nama">{{ $item->pengaju->nama ?? 'N/A' }}</span>
                                    @if ($item->pengaju)
                                        <small class="role">{{ $item->pengaju->role ?? '' }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="user-info">
                                    <span class="nama">{{ $item->penyetuju->nama ?? 'N/A' }}</span>
                                    @if ($item->penyetuju)
                                        <small class="role">{{ $item->penyetuju->role ?? '' }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($item->tanggal_persetujuan)
                                    <div class="date-info">
                                        <span class="tanggal">{{ $item->tanggal_persetujuan->format('d M Y') }}</span>
                                        <small class="jam">{{ $item->tanggal_persetujuan->format('H:i') }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <i class="bi bi-file-earmark-x"></i>
                                <p>Tidak ada data rencana anggaran yang disetujui</p>
                                <a href="{{ route('admin.laporan.rencana') }}" class="btn-empty">
                                    <i class="bi bi-x-circle"></i> Reset Filter
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Footer -->
        <div class="table-footer">
            <div class="footer-info">
                <i class="bi bi-info-circle"></i>
                @if ($rencana->total() > 0)
                    Menampilkan {{ $rencana->firstItem() }} - {{ $rencana->lastItem() }}
                    dari total <strong>{{ $rencana->total() }}</strong> data rencana disetujui
                @else
                    Tidak ada data yang ditampilkan
                @endif
            </div>

            @if ($rencana instanceof \Illuminate\Pagination\LengthAwarePaginator && $rencana->hasPages())
                <div class="pagination-wrapper">
                    {{ $rencana->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        /* ===== VARIABLES ===== */
        :root {
            --primary: #6d5dfc;
            --primary-light: #8b7dfd;
            --primary-gradient: linear-gradient(135deg, #6d5dfc, #22d3ee);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #fbbf24);
            --success-gradient: linear-gradient(135deg, #10b981, #34d399);
            --info-gradient: linear-gradient(135deg, #0ea5e9, #38bdf8);
            --danger-gradient: linear-gradient(135deg, #ef4444, #f87171);
            --dark: #1e293b;
            --gray-dark: #334155;
            --gray: #64748b;
            --gray-light: #94a3b8;
            --gray-lighter: #cbd5e1;
            --border: #e2e8f0;
            --background: #f8fafc;
            --white: #ffffff;
        }

        /* ===== MAIN CONTAINER ===== */
        .laporan-rencana-container {
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
            color: var(--dark);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .page-title i {
            color: var(--primary);
        }

        .page-subtitle {
            color: var(--gray);
            font-size: 0.95rem;
            margin-left: 2.5rem;
        }

        .btn-outline {
            background: var(--white);
            color: var(--gray-dark);
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

        .btn-outline:hover {
            background: var(--background);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateX(-3px);
            box-shadow: 0 4px 8px rgba(109, 93, 252, 0.1);
        }

        /* ===== FILTER CARD ===== */
        .filter-card {
            background: var(--white);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
            color: var(--dark);
            font-weight: 600;
            font-size: 1rem;
        }

        .filter-header i {
            color: var(--primary);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--gray-dark);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .filter-group label i {
            color: var(--primary);
            font-size: 0.9rem;
        }

        .filter-select,
        .filter-input {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--white);
            width: 100%;
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(109, 93, 252, 0.1);
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-light);
            pointer-events: none;
        }

        .search-wrapper input {
            padding-left: 2.5rem;
        }

        .filter-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
            border-top: 1px solid var(--border);
            padding-top: 1.5rem;
        }

        .btn-filter {
            background: var(--primary-gradient);
            color: var(--white);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(109, 93, 252, 0.2);
        }

        .btn-reset {
            background: var(--background);
            color: var(--gray-dark);
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
            background: var(--white);
            border-color: var(--gray);
            color: var(--dark);
        }

        .export-buttons {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }

        .btn-pdf {
            background: var(--danger-gradient);
            color: var(--white);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
        }

        /* ===== STATISTICS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: 0 10px 20px rgba(109, 93, 252, 0.1);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.5rem;
        }

        .stat-icon.bg-primary {
            background: var(--primary-gradient);
        }

        .stat-icon.bg-warning {
            background: var(--warning-gradient);
        }

        .stat-icon.bg-info {
            background: var(--info-gradient);
        }

        .stat-icon.bg-success {
            background: var(--success-gradient);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            display: block;
            color: var(--gray);
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .stat-value {
            display: block;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .stat-footer {
            display: block;
            font-size: 0.7rem;
            color: var(--gray-light);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .stat-footer i {
            font-size: 0.7rem;
        }

        /* ===== SUMMARY CARD ===== */
        .summary-card {
            background: #e0f2fe;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-left: 4px solid #0284c7;
        }

        .summary-icon {
            width: 32px;
            height: 32px;
            background: rgba(2, 132, 199, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0284c7;
            font-size: 1.1rem;
        }

        .summary-content {
            color: #075985;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .summary-content strong {
            color: #0369a1;
        }

        /* ===== TABLE STYLES ===== */
        .table-responsive {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: var(--white);
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1400px;
        }

        .table th {
            background: var(--background);
            padding: 1rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-dark);
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--gray-dark);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: var(--background);
        }

        /* Badge Tahun */
        .badge-tahun {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: #e0f2fe;
            color: #0284c7;
            white-space: nowrap;
        }

        .badge-tahun i {
            font-size: 0.75rem;
        }

        /* Kode Rekening Info */
        .kode-rekening-info {
            line-height: 1.4;
        }

        .kode-rekening-info .kode {
            font-weight: 600;
            color: var(--dark);
            display: block;
        }

        .kode-rekening-info .nama {
            font-size: 0.8rem;
            color: var(--gray);
            display: block;
        }

        /* Rencana Info */
        .rencana-info {
            line-height: 1.4;
        }

        .rencana-info .nama {
            font-weight: 600;
            color: var(--dark);
            display: block;
        }

        .rencana-info .deskripsi {
            font-size: 0.8rem;
            color: var(--gray);
            display: block;
        }

        /* User Info */
        .user-info {
            line-height: 1.4;
        }

        .user-info .nama {
            font-weight: 500;
            color: var(--dark);
            display: block;
        }

        .user-info .role {
            font-size: 0.75rem;
            color: var(--gray);
            display: block;
        }

        /* Date Info */
        .date-info {
            line-height: 1.4;
        }

        .date-info .tanggal {
            font-weight: 500;
            color: var(--dark);
            display: block;
        }

        .date-info .jam {
            font-size: 0.75rem;
            color: var(--gray);
            display: block;
        }

        /* Amount */
        .amount {
            font-weight: 600;
            color: var(--primary);
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: 600;
        }

        .text-muted {
            color: var(--gray) !important;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem !important;
            color: var(--gray-light);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--gray-lighter);
            margin-bottom: 1rem;
            display: block;
        }

        .empty-state p {
            color: var(--gray);
            margin-bottom: 1rem;
        }

        .btn-empty {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            background: var(--background);
            color: var(--gray-dark);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
        }

        .btn-empty:hover {
            background: var(--white);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ===== PAGINATION ===== */
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-info {
            padding: 0.5rem 1rem;
            background: var(--white);
            border-radius: 8px;
            border: 1px solid var(--border);
            color: var(--gray);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-info i {
            color: var(--primary);
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .page-item .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 0.6rem;
            border-radius: 10px;
            background: var(--white);
            color: var(--gray-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .page-item.active .page-link {
            background: var(--primary-gradient);
            color: var(--white);
            border: none;
        }

        .page-item.disabled .page-link {
            background: var(--background);
            color: var(--gray-lighter);
            border-color: var(--border);
            cursor: not-allowed;
        }

        .page-item:not(.disabled):not(.active) .page-link:hover {
            background: var(--background);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
        }

        /* ===== ANIMATIONS ===== */
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

        .filter-card,
        .stat-card,
        .summary-card,
        .table-responsive,
        .table-footer {
            animation: fadeInUp 0.5s ease forwards;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .laporan-rencana-container {
                padding: 1rem;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
                align-items: stretch;
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

            .table-footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection
