{{-- resources/views/admin/rencanaAnggaran.blade.php --}}
{{-- Halaman Rencana Anggaran dengan semua fungsi kecuali Revisi --}}

@extends('layouts.app')

@section('title', 'Rencana Anggaran')
@section('icon', 'list-check')

@section('content')
    <div class="rencana-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Rencana Anggaran</h2>
                <p class="page-subtitle">Pengelolaan rencana anggaran per kode rekening dan tahun</p>
            </div>
            <div class="header-actions">
                <!-- Filter Tahun -->
                <div class="btn-group-year">
                    <a href="{{ route('admin.rencana.index', request()->except('tahun')) }}"
                        class="btn-year {{ !request('tahun') ? 'active' : '' }}">
                        Semua Tahun
                    </a>
                    @foreach ($tahunList as $tahun)
                        <a href="{{ route('admin.rencana.index', ['tahun' => $tahun] + request()->except('tahun')) }}"
                            class="btn-year {{ request('tahun') == $tahun ? 'active' : '' }}">
                            {{ $tahun }}
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('admin.rencana.create') }}" class="btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Rencana
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-list-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Rencana</span>
                    <span class="stat-value">{{ $totalRencana }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Disetujui</span>
                    <span class="stat-value">{{ $totalDisetujui }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Diajukan</span>
                    <span class="stat-value">{{ $totalDiajukan }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Anggaran</span>
                    <span class="stat-value">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Info Filter Aktif -->
        @if (request('tahun') || request('status') || request('search'))
            <div class="filter-active">
                <i class="bi bi-funnel-fill"></i>
                Filter aktif:
                @if (request('tahun'))
                    Tahun {{ request('tahun') }}
                @endif
                @if (request('status'))
                    Status {{ ucfirst(request('status')) }}
                @endif
                @if (request('search'))
                    Pencarian "{{ request('search') }}"
                @endif
                <a href="{{ route('admin.rencana.index') }}" class="clear-filter">
                    <i class="bi bi-x-circle"></i> Reset semua filter
                </a>
            </div>
        @endif

        <!-- Filter & Search -->
        <div class="filter-section">
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="searchRencana" placeholder="Cari kode rekening, nama rencana, atau tahun..."
                    value="{{ request('search') }}">
            </div>
            <div class="filter-wrapper">
                <select class="filter-select" id="statusFilter" name="status">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                </select>
                <button class="btn-refresh"
                    onclick="window.location.href='{{ route('admin.rencana.index', request()->except(['status', 'search', 'page'])) }}'"
                    title="Reset Filter">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <!-- Rencana Table -->
        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Rekening</th>
                            <th>Nama Rekening</th>
                            <th>Nama Rencana</th>
                            <th>Tahun</th>
                            <th>Total Anggaran</th>
                            <th>Status</th>
                            <th>Rekomendasi Sistem</th>
                            <th>Pengaju</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rencanaTableBody">
                        @forelse($rencana as $index => $item)
                            <tr>
                                <td>{{ $rencana->firstItem() + $index }}</td>
                                <td>
                                    <div class="rekening-info">
                                        <span class="rekening-code">{{ $item->kodeRekening->kode ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->kodeRekening->nama_rekening ?? '-' }}</td>
                                <td>
                                    <span class="nama-rencana">{{ $item->nama_rencana }}</span>
                                    @if ($item->keterangan)
                                        <i class="bi bi-info-circle text-muted ms-1" data-bs-toggle="tooltip"
                                            title="{{ $item->keterangan }}"></i>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-tahun">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $item->tahun }}
                                    </span>
                                </td>
                                <td>
                                    <span class="nominal">
                                        Rp {{ number_format($item->total_anggaran, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass =
                                            [
                                                'diajukan' => 'status-diajukan',
                                                'disetujui' => 'status-disetujui',
                                                'ditolak' => 'status-ditolak',
                                                'revisi' => 'status-revisi',
                                            ][$item->status] ?? 'status-secondary';

                                        $statusIcon =
                                            [
                                                'diajukan' => 'bi-clock-history',
                                                'disetujui' => 'bi-check-circle',
                                                'ditolak' => 'bi-x-circle',
                                                'revisi' => 'bi-pencil-square',
                                            ][$item->status] ?? 'bi-question-circle';
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="bi {{ $statusIcon }}"></i>
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{-- Kolom Rekomendasi Sistem --}}
                                    @if (isset($item->rekomendasi_keputusan))
                                        @php
                                            $rekomendasi = $item->rekomendasi_keputusan;
                                            $rekomendasiClass =
                                                [
                                                    'diterima' => 'rekomendasi-success',
                                                    'masih_layak' => 'rekomendasi-warning',
                                                    'disarankan_tolak' => 'rekomendasi-danger',
                                                    'tidak_ada_data' => 'rekomendasi-secondary',
                                                ][$rekomendasi['status']] ?? 'rekomendasi-secondary';

                                            $rekomendasiIcon =
                                                [
                                                    'diterima' => 'bi-check-circle-fill',
                                                    'masih_layak' => 'bi-exclamation-triangle-fill',
                                                    'disarankan_tolak' => 'bi-x-circle-fill',
                                                    'tidak_ada_data' => 'bi-question-circle-fill',
                                                ][$rekomendasi['status']] ?? 'bi-question-circle-fill';
                                        @endphp

                                        <div class="rekomendasi-container">
                                            <div class="rekomendasi-badge {{ $rekomendasiClass }}" data-bs-toggle="tooltip"
                                                data-bs-placement="left" title="{{ $rekomendasi['teks'] }}">
                                                <i class="bi {{ $rekomendasiIcon }}"></i>
                                                <span>
                                                    @if ($rekomendasi['status'] == 'diterima')
                                                        Layak Diterima
                                                    @elseif ($rekomendasi['status'] == 'masih_layak')
                                                        Masih Layak (+10%)
                                                    @elseif ($rekomendasi['status'] == 'disarankan_tolak')
                                                        Disarankan Tolak
                                                    @else
                                                        Tidak Ada Data
                                                    @endif
                                                </span>
                                            </div>

                                            {{-- Tooltip Kustom --}}
                                            <div class="rekomendasi-tooltip {{ $rekomendasiClass }}">
                                                <i class="bi {{ $rekomendasiIcon }}"></i>
                                                <span>{{ $rekomendasi['teks'] }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="pengaju-info">
                                        <span class="pengaju-nama">{{ $item->pengaju->nama ?? '-' }}</span>
                                        <small class="pengaju-username">@ {{ $item->pengaju->username ?? '-' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- Tombol View (untuk semua) --}}
                                        <button class="btn-icon btn-view" onclick="viewRencana({{ $item->id }})"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        @if (in_array($item->status, ['diajukan', 'revisi']))
                                            @if (Auth::user()->role == 'admin' || Auth::id() == $item->diajukan_oleh)
                                                {{-- Tombol Edit --}}
                                                <button class="btn-icon btn-edit"
                                                    onclick="editRencana({{ $item->id }})" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                {{-- Tombol Hapus --}}
                                                <button class="btn-icon btn-delete"
                                                    onclick="deleteRencana({{ $item->id }})" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        @endif

                                        @if ($item->status == 'diajukan' && Auth::user()->role == 'admin')
                                            <div class="btn-group">
                                                {{-- Tombol Setujui --}}
                                                <button class="btn-icon btn-approve"
                                                    onclick="approveRencana({{ $item->id }})" title="Setujui">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>

                                                {{-- Tombol Tolak --}}
                                                <button class="btn-icon btn-reject"
                                                    onclick="rejectRencana({{ $item->id }})" title="Tolak">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-3">
                                    <div class="empty-state">
                                        <i class="bi bi-file-earmark-x"></i>
                                        <p class="mt-2 text-muted">
                                            @if (request('tahun') || request('status') || request('search'))
                                                Tidak ada data rencana anggaran dengan filter yang dipilih
                                            @else
                                                Belum ada data rencana anggaran
                                            @endif
                                        </p>
                                        @if (request('tahun') || request('status') || request('search'))
                                            <a href="{{ route('admin.rencana.index') }}" class="btn-empty">
                                                <i class="bi bi-x-circle"></i>
                                                Reset Filter
                                            </a>
                                        @else
                                            <a href="{{ route('admin.rencana.create') }}" class="btn-empty">
                                                <i class="bi bi-plus-lg"></i>
                                                Tambah Rencana Pertama
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Custom Pagination -->
        @if ($rencana->hasPages())
            <div class="pagination-wrapper">
                <div class="custom-pagination">
                    {{-- Previous Page Link --}}
                    @if ($rencana->onFirstPage())
                        <span class="page-prev disabled">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $rencana->previousPageUrl() }}" class="page-prev">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $current = $rencana->currentPage();
                        $last = $rencana->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);

                        if ($start > 1) {
                            echo '<a href="' . $rencana->url(1) . '" class="page-number">1</a>';
                            if ($start > 2) {
                                echo '<span class="page-dots">...</span>';
                            }
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $current) {
                                echo '<span class="page-number active">' . $i . '</span>';
                            } else {
                                echo '<a href="' . $rencana->url($i) . '" class="page-number">' . $i . '</a>';
                            }
                        }

                        if ($end < $last) {
                            if ($end < $last - 1) {
                                echo '<span class="page-dots">...</span>';
                            }
                            echo '<a href="' . $rencana->url($last) . '" class="page-number">' . $last . '</a>';
                        }
                    @endphp

                    {{-- Next Page Link --}}
                    @if ($rencana->hasMorePages())
                        <a href="{{ $rencana->nextPageUrl() }}" class="page-next">
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
                    @if ($rencana->total() > 0)
                        Menampilkan {{ $rencana->firstItem() }} - {{ $rencana->lastItem() }}
                        dari total {{ $rencana->total() }} data
                        @if (request('tahun'))
                            untuk tahun {{ request('tahun') }}
                        @endif
                        @if (request('status'))
                            status {{ ucfirst(request('status')) }}
                        @endif
                    @else
                        Tidak ada data yang ditampilkan
                    @endif
                </small>
            </div>
        </div>
    </div>

    <style>
        /* ============================================
               STYLE COMPACT - SEPERTI ZOOM 75-78% DI CHROME
               HANYA MODIFIKASI STYLE, FUNGSI TETAP UTUH
               ============================================ */

        /* Main Container - DIPERKECIL */
        .rencana-container {
            background: white;
            border-radius: 12px;
            padding: 0.85rem;
            box-shadow: 0 2px 8px rgba(109, 93, 252, 0.08);
        }

        /* Page Header - DIPERKECIL */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
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

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Button Group Year - DIPERKECIL */
        .btn-group-year {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
            background: #f1f5f9;
            padding: 0.3rem;
            border-radius: 8px;
        }

        .btn-year {
            padding: 0.3rem 0.7rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.7rem;
            text-decoration: none;
            color: #64748b;
            transition: all 0.2s ease;
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

        /* Button Primary - DIPERKECIL */
        .btn-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
            border: none;
            padding: 0.45rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(109, 93, 252, 0.25);
            color: white;
        }

        .btn-primary i {
            font-size: 0.85rem;
        }

        /* Stats Grid - DIPERKECIL */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.8rem;
            margin-bottom: 1.2rem;
        }

        .stat-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #6d5dfc;
            box-shadow: 0 5px 12px rgba(109, 93, 252, 0.08);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
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
            font-size: 0.7rem;
            margin-bottom: 0.15rem;
        }

        .stat-value {
            display: block;
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        /* Filter Active - DIPERKECIL */
        .filter-active {
            background: #e0f2fe;
            border: 1px solid #7dd3fc;
            border-radius: 16px;
            padding: 0.3rem 0.8rem;
            margin-bottom: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #0369a1;
            font-size: 0.75rem;
            flex-wrap: wrap;
        }

        .filter-active i {
            font-size: 0.85rem;
        }

        .clear-filter {
            color: #0369a1;
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.3rem;
        }

        .clear-filter:hover {
            color: #0284c7;
            text-decoration: underline;
        }

        /* Filter Section - DIPERKECIL */
        .filter-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .search-wrapper {
            flex: 1;
            min-width: 240px;
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .search-wrapper input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.3rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .search-wrapper input:focus {
            outline: none;
            border-color: #6d5dfc;
            box-shadow: 0 0 0 2px rgba(109, 93, 252, 0.1);
        }

        .filter-wrapper {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 0.5rem 1.8rem 0.5rem 0.8rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.8rem;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 120px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
        }

        .filter-select:focus {
            outline: none;
            border-color: #6d5dfc;
        }

        .btn-refresh {
            width: 34px;
            height: 34px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-refresh:hover {
            border-color: #6d5dfc;
            color: #6d5dfc;
            transform: rotate(180deg);
        }

        /* TABLE WRAPPER - SCROLL HORIZONTAL */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            margin-bottom: 1rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: white;
        }

        .table-responsive {
            width: 100%;
            overflow-x: visible;
        }

        /* Table - DIPERKECIL */
        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .table th {
            background: #f8fafc;
            padding: 0.6rem 0.9rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .table td {
            padding: 0.6rem 0.9rem;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            font-size: 0.8rem;
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

        /* Scrollbar */
        .table-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .table-wrapper::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        /* Rekening Info - DIPERKECIL */
        .rekening-code {
            font-weight: 600;
            color: #1e293b;
            font-family: 'Courier New', monospace;
            background: #f1f5f9;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            display: inline-block;
            font-size: 0.75rem;
        }

        .nama-rencana {
            font-weight: 500;
            color: #1e293b;
            font-size: 0.8rem;
        }

        /* Tahun Badge - DIPERKECIL */
        .badge-tahun {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.6rem;
            border-radius: 16px;
            font-size: 0.7rem;
            font-weight: 600;
            background: #e0f2fe;
            color: #0284c7;
            white-space: nowrap;
        }

        .badge-tahun i {
            font-size: 0.7rem;
        }

        /* Nominal - DIPERKECIL */
        .nominal {
            font-weight: 600;
            color: #059669;
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
        }

        /* Status Badge - DIPERKECIL */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.8rem;
            border-radius: 16px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-diajukan {
            background: #fef3c7;
            color: #d97706;
        }

        .status-disetujui {
            background: #d1fae5;
            color: #059669;
        }

        .status-ditolak {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-revisi {
            background: #dbeafe;
            color: #2563eb;
        }

        .status-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Rekomendasi Styles - TETAP UTUH, HANYA UKURAN DIKECILKAN */
        .rekomendasi-container {
            position: relative;
        }

        .rekomendasi-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.7rem;
            border-radius: 16px;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: help;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .rekomendasi-badge i {
            font-size: 0.75rem;
        }

        .rekomendasi-badge.rekomendasi-success {
            background: #d1fae5;
            color: #059669;
        }

        .rekomendasi-badge.rekomendasi-warning {
            background: #fef3c7;
            color: #d97706;
        }

        .rekomendasi-badge.rekomendasi-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .rekomendasi-badge.rekomendasi-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Tooltip Kustom - TETAP UTUH */
        .rekomendasi-tooltip {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            background: white;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            font-size: 0.75rem;
            margin-top: 0.3rem;
            border-left: 3px solid;
            white-space: normal;
            min-width: 220px;
        }

        .rekomendasi-tooltip.rekomendasi-success {
            border-left-color: #059669;
        }

        .rekomendasi-tooltip.rekomendasi-warning {
            border-left-color: #d97706;
        }

        .rekomendasi-tooltip.rekomendasi-danger {
            border-left-color: #dc2626;
        }

        .rekomendasi-tooltip.rekomendasi-secondary {
            border-left-color: #64748b;
        }

        .rekomendasi-container:hover .rekomendasi-tooltip {
            display: block;
        }

        /* Pengaju Info - DIPERKECIL */
        .pengaju-info {
            display: flex;
            flex-direction: column;
        }

        .pengaju-nama {
            font-weight: 500;
            color: #1e293b;
            font-size: 0.75rem;
        }

        .pengaju-username {
            font-size: 0.65rem;
            color: #64748b;
        }

        /* Action Buttons - DIPERKECIL */
        .action-buttons {
            display: flex;
            gap: 0.3rem;
            flex-wrap: wrap;
        }

        .btn-group {
            display: flex;
            gap: 0.2rem;
        }

        .btn-icon {
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: white;
            font-size: 0.8rem;
        }

        .btn-view {
            background: #3b82f6;
        }

        .btn-edit {
            background: #f59e0b;
        }

        .btn-delete {
            background: #ef4444;
        }

        .btn-approve {
            background: #10b981;
        }

        .btn-reject {
            background: #ef4444;
        }

        .btn-icon:hover {
            transform: translateY(-1px);
            filter: brightness(110%);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        /* Empty State - DIPERKECIL */
        .empty-state {
            padding: 2rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
        }

        .empty-state p {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .btn-empty {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-empty:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* Custom Pagination - DIPERKECIL */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            margin-bottom: 0.7rem;
        }

        .custom-pagination {
            display: flex;
            gap: 0.3rem;
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
            min-width: 32px;
            height: 32px;
            padding: 0 0.6rem;
            border-radius: 6px;
            background: white;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            font-weight: 500;
            font-size: 0.75rem;
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
            min-width: 32px;
            height: 32px;
            color: #94a3b8;
            font-size: 0.75rem;
        }

        /* Table Footer - DIPERKECIL */
        .table-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 0.5rem;
        }

        .footer-info {
            padding: 0.4rem 0.8rem;
            background: #f8fafc;
            border-radius: 6px;
        }

        .footer-info small {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.7rem;
        }

        /* Tooltip */
        .bi-info-circle {
            cursor: help;
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

        /* Responsive - DIPERBAIKI */
        @media (max-width: 768px) {
            .rencana-container {
                padding: 0.75rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
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
                gap: 0.6rem;
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

            .btn-group {
                flex-wrap: wrap;
            }

            .btn-icon {
                width: 100%;
            }

            .page-prev,
            .page-next,
            .page-number {
                min-width: 30px;
                height: 30px;
                font-size: 0.7rem;
            }

            .rekomendasi-tooltip {
                display: none !important;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Search with debounce
            let searchTimer;
            $('#searchRencana').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    filterTable();
                }, 500);
            });

            // Filter on change
            $('#statusFilter').on('change', function() {
                filterTable();
            });

            function filterTable() {
                var search = $('#searchRencana').val();
                var tahun = getUrlParameter('tahun') || '';
                var status = $('#statusFilter').val();

                var url = '{{ route('admin.rencana.index') }}?' +
                    'search=' + encodeURIComponent(search) +
                    '&tahun=' + encodeURIComponent(tahun) +
                    '&status=' + encodeURIComponent(status);

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

        // View Rencana
        function viewRencana(id) {
            window.location.href = '/admin/rencana/' + id;
        }

        // Edit Rencana
        function editRencana(id) {
            window.location.href = '/admin/rencana/' + id + '/edit';
        }

        // Delete Rencana
        function deleteRencana(id) {
            Swal.fire({
                title: 'Hapus Rencana',
                text: 'Apakah Anda yakin ingin menghapus rencana anggaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/rencana/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat menghapus data'
                            });
                        }
                    });
                }
            });
        }

        // Approve Rencana
        function approveRencana(id) {
            // Cek rekomendasi terlebih dahulu
            $.ajax({
                url: '/admin/rencana/rekomendasi/keputusan/' + id,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let rekomendasi = response.data;
                        let message = '';

                        if (rekomendasi.status === 'disarankan_tolak') {
                            message = 'PERHATIAN: ' + rekomendasi.teks +
                                '\n\nApakah Anda tetap ingin menyetujui?';
                        } else if (rekomendasi.status === 'masih_layak') {
                            message = 'INFORMASI: ' + rekomendasi.teks + '\n\nTetap setujui?';
                        } else {
                            message = rekomendasi.teks + '\n\nSetujui rencana ini?';
                        }

                        Swal.fire({
                            title: 'Setujui Rencana',
                            text: message,
                            icon: rekomendasi.status === 'disarankan_tolak' ? 'warning' : 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Setujui',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                prosesApprove(id);
                            }
                        });
                    } else {
                        prosesApprove(id);
                    }
                },
                error: function() {
                    prosesApprove(id);
                }
            });
        }

        function prosesApprove(id) {
            $.ajax({
                url: '/admin/rencana/' + id + '/approve',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
        }

        // FUNGSI REVISI TELAH DIHAPUS

        // Reject Rencana
        function rejectRencana(id) {
            Swal.fire({
                title: 'Tolak Rencana',
                input: 'textarea',
                inputLabel: 'Alasan Penolakan',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                inputAttributes: {
                    'required': true,
                    'rows': 4
                },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Tolak',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan penolakan harus diisi!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/rencana/' + id + '/reject',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            alasan_penolakan: result.value
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
