@extends('layouts.app')

@section('title', 'Daftar Pasien')
@section('icon', 'people')

@section('content')
    <div class="pasien-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Data Pasien</h2>
                <p class="page-subtitle">Pengelolaan data pasien per bulan dan tahun</p>
            </div>
            <div class="header-actions">
                <!-- Tombol Filter Tahun -->
                <div class="btn-group-year">
                    <a href="{{ route('admin.pasien.index', request()->except('tahun')) }}"
                        class="btn-year {{ !request('tahun') ? 'active' : '' }}">
                        Semua Tahun
                    </a>
                    @foreach ($tahunList as $tahun)
                        <a href="{{ route('admin.pasien.index', ['tahun' => $tahun] + request()->except('tahun')) }}"
                            class="btn-year {{ request('tahun') == $tahun ? 'active' : '' }}">
                            {{ $tahun }}
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('admin.pasien.create') }}" class="btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Data Pasien
                </a>
            </div>
        </div>

        <!-- Stats Cards - BERDASARKAN FILTER -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Data</span>
                    <span class="stat-value">{{ $totalData }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Pasien</span>
                    <span class="stat-value">{{ number_format($totalPasien, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-calendar-month"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Bulan Aktif</span>
                    <span class="stat-value">{{ $bulanAktif }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Rata-rata/Bulan</span>
                    <span class="stat-value">{{ number_format($rataRata, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Info Filter Aktif -->
        @if (request('tahun') || request('bulan') || request('search'))
            <div class="filter-active">
                <i class="bi bi-funnel-fill"></i>
                Filter aktif:
                @if (request('tahun'))
                    Tahun {{ request('tahun') }}
                @endif
                @if (request('bulan'))
                    Bulan {{ request('bulan') }}
                @endif
                @if (request('search'))
                    Pencarian "{{ request('search') }}"
                @endif
                <a href="{{ route('admin.pasien.index') }}" class="clear-filter">
                    <i class="bi bi-x-circle"></i> Reset semua filter
                </a>
            </div>
        @endif

        <!-- Filter & Search -->
        <div class="filter-section">
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="searchPasien" placeholder="Cari tahun atau jumlah pasien..."
                    value="{{ request('search') }}">
            </div>
            <div class="filter-wrapper">
                <select class="filter-select" id="bulanFilter" name="bulan">
                    <option value="">Semua Bulan</option>
                    @foreach ($bulanList as $key => $bulan)
                        <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>
                            {{ $bulan }}
                        </option>
                    @endforeach
                </select>
                <button class="btn-refresh"
                    onclick="window.location.href='{{ route('admin.pasien.index', request()->except(['bulan', 'search', 'page'])) }}'"
                    title="Reset Filter">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert-custom alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-custom alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        <!-- Pasien Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Periode</th>
                        <th width="20%">Bulan</th>
                        <th width="10%">Tahun</th>
                        <th width="20%">Jumlah Pasien</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pasienTableBody">
                    @forelse($dataPasien as $index => $item)
                        <tr>
                            <td>{{ $dataPasien->firstItem() + $index }}</td>
                            <td>
                                <div class="periode-info">
                                    <span class="periode-badge">{{ $item->periode }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="bulan-text">{{ $item->nama_bulan }}</span>
                            </td>
                            <td>
                                <span class="badge-tahun">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $item->tahun }}
                                </span>
                            </td>
                            <td class="text-start">
                                <span class="nominal">
                                    <i class="bi bi-people-fill"></i>
                                    {{ number_format($item->jumlah_pasien, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.pasien.edit', $item->id) }}" class="btn-icon btn-edit"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-icon btn-delete"
                                        onclick="deletePasien({{ $item->id }})" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    <p class="mt-3 text-muted">
                                        @if (request('tahun') || request('bulan') || request('search'))
                                            Tidak ada data pasien dengan filter yang dipilih
                                        @else
                                            Belum ada data pasien
                                        @endif
                                    </p>
                                    @if (request('tahun') || request('bulan') || request('search'))
                                        <a href="{{ route('admin.pasien.index') }}" class="btn-empty">
                                            <i class="bi bi-x-circle"></i>
                                            Reset Filter
                                        </a>
                                    @else
                                        <a href="{{ route('admin.pasien.create') }}" class="btn-empty">
                                            <i class="bi bi-plus-lg"></i>
                                            Tambah Data Pasien Pertama
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 🔴 CUSTOM PAGINATION (TANPA BOOTSTRAP) -->
        @if ($dataPasien->hasPages())
            <div class="pagination-wrapper">
                <div class="custom-pagination">
                    {{-- Previous Page Link --}}
                    @if ($dataPasien->onFirstPage())
                        <span class="page-prev disabled">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $dataPasien->previousPageUrl() }}" class="page-prev">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $current = $dataPasien->currentPage();
                        $last = $dataPasien->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);

                        if ($start > 1) {
                            echo '<a href="' . $dataPasien->url(1) . '" class="page-number">1</a>';
                            if ($start > 2) {
                                echo '<span class="page-dots">...</span>';
                            }
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $current) {
                                echo '<span class="page-number active">' . $i . '</span>';
                            } else {
                                echo '<a href="' . $dataPasien->url($i) . '" class="page-number">' . $i . '</a>';
                            }
                        }

                        if ($end < $last) {
                            if ($end < $last - 1) {
                                echo '<span class="page-dots">...</span>';
                            }
                            echo '<a href="' . $dataPasien->url($last) . '" class="page-number">' . $last . '</a>';
                        }
                    @endphp

                    {{-- Next Page Link --}}
                    @if ($dataPasien->hasMorePages())
                        <a href="{{ $dataPasien->nextPageUrl() }}" class="page-next">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @else
                        <span class="page-next disabled">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Footer Info -->
        <div class="table-footer">
            <div class="footer-info">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i>
                    @if ($dataPasien->total() > 0)
                        Menampilkan {{ $dataPasien->firstItem() }} - {{ $dataPasien->lastItem() }}
                        dari total {{ $dataPasien->total() }} data
                        @if (request('tahun'))
                            untuk tahun {{ request('tahun') }}
                        @endif
                        @if (request('bulan'))
                            bulan {{ request('bulan') }}
                        @endif
                    @else
                        Tidak ada data yang ditampilkan
                    @endif
                </small>
            </div>
        </div>
    </div>

    <style>
        /* Main Container */
        .pasien-container {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(109, 93, 252, 0.1);
        }

        /* Page Header */
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
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Button Group Year */
        .btn-group-year {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            background: #f1f5f9;
            padding: 0.5rem;
            border-radius: 12px;
        }

        .btn-year {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            color: #64748b;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-year:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn-year.active {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
        }

        /* Button Primary */
        .btn-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(109, 93, 252, 0.3);
            color: white;
        }

        .btn-primary i {
            font-size: 1.1rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: #6d5dfc;
            box-shadow: 0 10px 25px rgba(109, 93, 252, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .stat-icon.bg-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        }

        .stat-icon.bg-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .stat-icon.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .stat-icon.bg-info {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            display: block;
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        /* Filter Section */
        .filter-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-wrapper {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .search-wrapper input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.8rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .search-wrapper input:focus {
            outline: none;
            border-color: #6d5dfc;
            box-shadow: 0 0 0 3px rgba(109, 93, 252, 0.1);
        }

        .filter-wrapper {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 0.75rem 2rem 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 140px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
        }

        .filter-select:focus {
            outline: none;
            border-color: #6d5dfc;
        }

        .btn-refresh {
            width: 42px;
            height: 42px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-refresh:hover {
            border-color: #6d5dfc;
            color: #6d5dfc;
            transform: rotate(180deg);
        }

        /* Filter Active */
        .filter-active {
            background: #e0f2fe;
            border: 1px solid #7dd3fc;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            margin-bottom: 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #0369a1;
            font-size: 0.9rem;
            flex-wrap: wrap;
        }

        .filter-active i {
            font-size: 1rem;
        }

        .clear-filter {
            color: #0369a1;
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .clear-filter:hover {
            color: #0284c7;
            text-decoration: underline;
        }

        /* Alert Custom */
        .alert-custom {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-close {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            background: white;
            margin-bottom: 1.5rem;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: middle;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Text alignment */
        .text-end {
            text-align: right;
        }

        /* Periode Badge */
        .periode-badge {
            font-weight: 600;
            color: #1e293b;
            background: #f1f5f9;
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            display: inline-block;
            font-size: 0.9rem;
            font-family: 'Courier New', monospace;
        }

        .bulan-text {
            font-weight: 500;
            color: #1e293b;
        }

        /* Tahun Badge */
        .badge-tahun {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: #e0f2fe;
            color: #0284c7;
        }

        .badge-tahun i {
            font-size: 0.8rem;
        }

        /* Nominal */
        .nominal {
            font-weight: 600;
            color: #059669;
            font-family: 'Courier New', monospace;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nominal i {
            color: #10b981;
            font-size: 1rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            font-size: 1rem;
            text-decoration: none;
        }

        .btn-edit {
            background: #f59e0b;
        }

        .btn-delete {
            background: #ef4444;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
            filter: brightness(110%);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
        }

        .empty-state p {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 1.5rem;
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
        }

        .btn-empty:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* Custom Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .custom-pagination {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .page-prev,
        .page-next,
        .page-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 0.75rem;
            border-radius: 10px;
            background: white;
            color: #475569;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            font-weight: 500;
            cursor: pointer;
        }

        .page-prev:hover,
        .page-next:hover,
        .page-number:hover {
            background: #f1f5f9;
            border-color: #6d5dfc;
            color: #1e293b;
        }

        .page-number.active {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
            border: none;
        }

        .page-prev.disabled,
        .page-next.disabled {
            background: #f1f5f9;
            color: #cbd5e1;
            border-color: #e2e8f0;
            cursor: not-allowed;
            pointer-events: none;
        }

        .page-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            color: #94a3b8;
        }

        /* Table Footer */
        .table-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .footer-info {
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 8px;
        }

        .footer-info small {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pasien-container {
                padding: 1.5rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
            }

            .btn-group-year {
                width: 100%;
                justify-content: center;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-section {
                flex-direction: column;
            }

            .search-wrapper {
                min-width: 100%;
            }

            .filter-wrapper {
                width: 100%;
            }

            .filter-select {
                flex: 1;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-icon {
                width: 100%;
            }

            .page-prev,
            .page-next,
            .page-number {
                min-width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }
        }

        /* Animation */
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

        .table tbody tr {
            animation: slideIn 0.3s ease;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Search with debounce
            let searchTimer;
            $('#searchPasien').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    filterTable();
                }, 500);
            });

            // Filter on change
            $('#bulanFilter').on('change', function() {
                filterTable();
            });

            function filterTable() {
                var search = $('#searchPasien').val();
                var tahun = getUrlParameter('tahun') || '';
                var bulan = $('#bulanFilter').val();

                var url = '{{ route('admin.pasien.index') }}?' +
                    'search=' + encodeURIComponent(search) +
                    '&tahun=' + encodeURIComponent(tahun) +
                    '&bulan=' + encodeURIComponent(bulan);

                window.location.href = url;
            }

            // Helper function to get URL parameter
            function getUrlParameter(name) {
                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                if (results == null) {
                    return null;
                }
                return decodeURIComponent(results[1]) || 0;
            }
        });

        // Delete Pasien dengan SweetAlert
        function deletePasien(id) {
            Swal.fire({
                title: 'Hapus Data Pasien',
                text: 'Apakah Anda yakin ingin menghapus data pasien ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form untuk DELETE request
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('admin.pasien.destroy', ':id') }}'.replace(':id', id);
                    form.style.display = 'none';

                    var csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';

                    var methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection

@push('styles')
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
