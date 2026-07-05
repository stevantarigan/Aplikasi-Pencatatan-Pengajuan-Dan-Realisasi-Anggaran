{{-- resources/views/admin/daftarAnggaran.blade.php --}}

@extends('layouts.app')

@section('title', 'Daftar Anggaran')
@section('icon', 'calculator')

@section('content')
    <div class="anggaran-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Daftar Anggaran</h2>
                <p class="page-subtitle">Anggaran yang otomatis dibuat dari rencana yang disetujui</p>
            </div>
            <div class="header-actions">
                <div class="btn-group-year">
                    <a href="{{ route('admin.anggaran.index', request()->except('tahun')) }}"
                        class="btn-year {{ !request('tahun') ? 'active' : '' }}">
                        Semua Tahun
                    </a>
                    @foreach ($tahunList as $tahun)
                        <a href="{{ route('admin.anggaran.index', ['tahun' => $tahun] + request()->except('tahun')) }}"
                            class="btn-year {{ request('tahun') == $tahun ? 'active' : '' }}">
                            {{ $tahun }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Info Alert jika belum ada anggaran -->
        @if ($anggaran->isEmpty() && !request('tahun') && !request('status') && !request('search'))
            <div class="alert-info mb-3">
                <i class="bi bi-info-circle me-2"></i>
                Belum ada anggaran. Anggaran akan otomatis muncul ketika ada rencana anggaran yang disetujui.
                <a href="{{ route('admin.rencana.index') }}" class="alert-link">Lihat Rencana Anggaran</a>
            </div>
        @endif

        <!-- Info Filter Aktif -->
        @if (request('tahun') || request('status') || request('search'))
            <div class="filter-active">
                <i class="bi bi-funnel-fill"></i>
                Filter aktif:
                @if (request('tahun'))
                    Tahun {{ request('tahun') }}
                @endif
                @if (request('status'))
                    @if (request('status') == 'aktif')
                        Status Aktif
                    @elseif(request('status') == 'ditutup')
                        Status Ditutup
                    @endif
                @endif
                @if (request('search'))
                    Pencarian "{{ request('search') }}"
                @endif
                <a href="{{ route('admin.anggaran.index') }}" class="clear-filter">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        @endif

        <!-- Statistik -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-calculator"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Anggaran</span>
                    <span class="stat-value">{{ $totalAnggaran }}</span>
                    <small class="text-muted">termasuk ditutup</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-calendar"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Tahun Anggaran</span>
                    <span class="stat-value">{{ $totalTahun }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Nominal (Aktif)</span>
                    <span class="stat-value">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="bi bi-piggy-bank"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Sisa Anggaran (Aktif)</span>
                    <span class="stat-value">
                        Rp {{ number_format($totalSisa, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="filter-section">
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="searchAnggaran" placeholder="Cari kode rekening, nama rencana, tahun..."
                    value="{{ request('search') }}">
            </div>
            <div class="filter-wrapper">
                <select class="filter-select" id="tahunFilter" name="tahun">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
                <select class="filter-select" id="statusFilter" name="status">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="ditutup" {{ request('status') == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                </select>
                <button class="btn-refresh"
                    onclick="window.location.href='{{ route('admin.anggaran.index', request()->except(['status', 'search', 'page'])) }}'"
                    title="Reset Filter">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <!-- Table dengan Scroll Horizontal -->
        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="min-width:50px">No</th>
                            <th style="min-width:80px">Tahun</th>
                            <th style="min-width:120px">Kode Rekening</th>
                            <th style="min-width:180px">Nama Rencana</th>
                            <th style="min-width:140px">Total Anggaran</th>
                            <th style="min-width:150px">Sisa Anggaran</th>
                            <th style="min-width:90px">Status</th>
                            <th style="min-width:90px">Dibuat</th>
                            <th style="min-width:200px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggaran as $index => $item)
                            <tr>
                                <td>{{ $anggaran->firstItem() + $index }}</td>
                                <td><span class="badge-tahun"><i class="bi bi-calendar3"></i> {{ $item->tahun }}</span>
                                </td>
                                <td>
                                    @if ($item->rencanaAnggaran && $item->rencanaAnggaran->kodeRekening)
                                        <div class="kode-rekening-info">
                                            <span
                                                class="rekening-code">{{ $item->rencanaAnggaran->kodeRekening->kode }}</span>
                                            <small class="text-muted d-block">
                                                {{ $item->rencanaAnggaran->kodeRekening->nama_rekening }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->rencanaAnggaran)
                                        <div class="rencana-info">
                                            <span class="nama-rencana">{{ $item->rencanaAnggaran->nama_rencana }}</span>
                                            <small class="text-muted d-block">
                                                <i class="bi bi-person"></i>
                                                {{ $item->rencanaAnggaran->pengaju->nama ?? 'N/A' }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="nominal">Rp {{ number_format($item->total_anggaran, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $sisa = $item->sisa_anggaran;
                                        $sisaClass = 'text-success';
                                        $sisaIcon = 'bi-check-circle';
                                        if ($sisa <= 0) {
                                            $sisaClass = 'text-danger';
                                            $sisaIcon = 'bi-exclamation-circle';
                                        } elseif ($sisa < $item->total_anggaran * 0.2) {
                                            $sisaClass = 'text-warning';
                                            $sisaIcon = 'bi-exclamation-triangle';
                                        }
                                        $persentase =
                                            $item->total_anggaran > 0
                                                ? round(
                                                    (($item->total_anggaran - $sisa) / $item->total_anggaran) * 100,
                                                    1,
                                                )
                                                : 0;
                                    @endphp
                                    <div class="sisa-anggaran">
                                        <span class="fw-bold {{ $sisaClass }}">
                                            <i class="bi {{ $sisaIcon }}"></i>
                                            Rp {{ number_format($sisa, 0, ',', '.') }}
                                        </span>
                                        <small class="text-muted d-block">Terpakai {{ $persentase }}%</small>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->status == 'aktif')
                                        <span class="status-badge status-aktif">Aktif</span>
                                    @else
                                        <span class="status-badge status-ditutup">Ditutup</span>
                                    @endif
                                </td>
                                <td><span class="tanggal-buat">{{ $item->created_at->format('d M Y') }}</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.anggaran.show', $item->id) }}" class="btn-icon btn-view"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if (auth()->user()->role == 'admin')
                                            <a href="{{ route('admin.anggaran.edit', $item->id) }}"
                                                class="btn-icon btn-edit" title="Edit Anggaran">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif

                                        @if (auth()->user()->role == 'admin')
                                            @if ($item->pengajuan->count() == 0)
                                                <button class="btn-icon btn-delete"
                                                    onclick="deleteAnggaran({{ $item->id }})" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button class="btn-icon btn-delete disabled" disabled
                                                    title="Tidak dapat dihapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        @endif

                                        @if ($item->status == 'aktif' && $item->sisa_anggaran > 0)
                                            <a href="{{ route('admin.pengajuan.create', ['anggaran_id' => $item->id]) }}"
                                                class="btn-icon btn-success" title="Buat Pengajuan">
                                                <i class="bi bi-send"></i>
                                            </a>
                                        @elseif ($item->status == 'ditutup')
                                            <button class="btn-icon btn-secondary disabled" disabled
                                                title="Anggaran ditutup">
                                                <i class="bi bi-send-slash"></i>
                                            </button>
                                        @elseif ($item->sisa_anggaran <= 0)
                                            <button class="btn-icon btn-secondary disabled" disabled
                                                title="Sisa anggaran habis">
                                                <i class="bi bi-send-slash"></i>
                                            </button>
                                        @endif

                                        @if (auth()->user()->role == 'admin')
                                            @if ($item->status == 'aktif')
                                                <button class="btn-icon btn-warning"
                                                    onclick="ubahStatus({{ $item->id }}, 'ditutup')"
                                                    title="Tutup Anggaran">
                                                    <i class="bi bi-lock"></i>
                                                </button>
                                            @else
                                                <button class="btn-icon btn-info"
                                                    onclick="ubahStatus({{ $item->id }}, 'aktif')"
                                                    title="Buka Anggaran">
                                                    <i class="bi bi-unlock"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">
                                    <div class="empty-state">
                                        <i class="bi bi-file-earmark-x"></i>
                                        <p class="text-muted">
                                            @if (request('tahun') || request('status') || request('search'))
                                                Tidak ada data dengan filter yang dipilih
                                            @else
                                                Belum ada data anggaran
                                            @endif
                                        </p>
                                        @if (request('tahun') || request('status') || request('search'))
                                            <a href="{{ route('admin.anggaran.index') }}" class="btn-empty">
                                                <i class="bi bi-x-circle"></i> Reset Filter
                                            </a>
                                        @else
                                            <a href="{{ route('admin.rencana.index') }}" class="btn-empty">
                                                <i class="bi bi-list-check"></i> Lihat Rencana
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

        <!-- Pagination -->
        @if ($anggaran->hasPages())
            <div class="pagination-wrapper">
                <div class="custom-pagination">
                    @if ($anggaran->onFirstPage())
                        <span class="page-prev disabled"><i class="bi bi-chevron-left"></i></span>
                    @else
                        <a href="{{ $anggaran->previousPageUrl() }}" class="page-prev"><i
                                class="bi bi-chevron-left"></i></a>
                    @endif

                    @php
                        $current = $anggaran->currentPage();
                        $last = $anggaran->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                        if ($start > 1) {
                            echo '<a href="' . $anggaran->url(1) . '" class="page-number">1</a>';
                            if ($start > 2) {
                                echo '<span class="page-dots">...</span>';
                            }
                        }
                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $current) {
                                echo '<span class="page-number active">' . $i . '</span>';
                            } else {
                                echo '<a href="' . $anggaran->url($i) . '" class="page-number">' . $i . '</a>';
                            }
                        }
                        if ($end < $last) {
                            if ($end < $last - 1) {
                                echo '<span class="page-dots">...</span>';
                            }
                            echo '<a href="' . $anggaran->url($last) . '" class="page-number">' . $last . '</a>';
                        }
                    @endphp

                    @if ($anggaran->hasMorePages())
                        <a href="{{ $anggaran->nextPageUrl() }}" class="page-next"><i
                                class="bi bi-chevron-right"></i></a>
                    @else
                        <span class="page-next disabled"><i class="bi bi-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif

        <div class="table-footer">
            <div class="footer-info">
                <small>
                    <i class="bi bi-info-circle"></i>
                    @if ($anggaran->total() > 0)
                        Data {{ $anggaran->firstItem() }}-{{ $anggaran->lastItem() }} dari {{ $anggaran->total() }}
                    @else
                        Tidak ada data
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

        .anggaran-container {
            background: white;
            border-radius: 12px;
            padding: 0.85rem;
            box-shadow: 0 2px 8px rgba(109, 93, 252, 0.08);
        }

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

        .btn-year.active {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
        }

        .alert-info {
            background: #e0f2fe;
            border: 1px solid #7dd3fc;
            border-radius: 8px;
            color: #0369a1;
            padding: 0.5rem 0.8rem;
            font-size: 0.7rem;
            margin-bottom: 0.75rem;
        }

        .alert-link {
            color: #0369a1;
            font-weight: 600;
            text-decoration: underline;
        }

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
            font-size: 0.7rem;
            flex-wrap: wrap;
        }

        .clear-filter {
            color: #0369a1;
            text-decoration: none;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .stat-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #6d5dfc;
            box-shadow: 0 5px 12px rgba(109, 93, 252, 0.08);
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
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
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        }

        .stat-label {
            display: block;
            color: #64748b;
            font-size: 0.65rem;
            margin-bottom: 0.15rem;
        }

        .stat-value {
            display: block;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
        }

        .stat-content small {
            font-size: 0.55rem;
        }

        .filter-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.8rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .search-wrapper {
            flex: 1;
            min-width: 220px;
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 0.7rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .search-wrapper input {
            width: 100%;
            padding: 0.45rem 0.8rem 0.45rem 2.2rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.7rem;
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
            padding: 0.45rem 1.5rem 0.45rem 0.7rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.7rem;
            min-width: 100px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
        }

        .btn-refresh {
            width: 32px;
            height: 32px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-refresh:hover {
            border-color: #6d5dfc;
            color: #6d5dfc;
            transform: rotate(180deg);
        }

        /* Table wrapper - scroll horizontal */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            margin-bottom: 0.8rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: white;
        }

        .table-responsive {
            width: 100%;
            overflow-x: visible;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        .table th {
            background: #f8fafc;
            padding: 0.55rem 0.7rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.7rem;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .table td {
            padding: 0.5rem 0.7rem;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            font-size: 0.75rem;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

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

        .badge-tahun {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            background: #e0f2fe;
            color: #0284c7;
            white-space: nowrap;
        }

        .rekening-code {
            font-weight: 600;
            background: #f1f5f9;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
            font-size: 0.65rem;
        }

        .nama-rencana {
            font-weight: 500;
            font-size: 0.7rem;
        }

        .nominal {
            font-weight: 600;
            color: #059669;
            font-size: 0.7rem;
            white-space: nowrap;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.6rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-aktif {
            background: #d1fae5;
            color: #059669;
        }

        .status-ditutup {
            background: #f1f5f9;
            color: #64748b;
        }

        .sisa-anggaran {
            line-height: 1.3;
        }

        .sisa-anggaran .text-success {
            color: #10b981 !important;
        }

        .sisa-anggaran .text-warning {
            color: #f59e0b !important;
        }

        .sisa-anggaran .text-danger {
            color: #ef4444 !important;
        }

        .sisa-anggaran small {
            font-size: 0.55rem;
        }

        .tanggal-buat {
            font-size: 0.65rem;
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
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
            color: white;
            font-size: 0.7rem;
            text-decoration: none;
            transition: all 0.2s ease;
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

        .btn-success {
            background: #10b981;
        }

        .btn-secondary {
            background: #64748b;
        }

        .btn-warning {
            background: #f59e0b;
        }

        .btn-info {
            background: #0ea5e9;
        }

        .btn-icon:hover:not(:disabled) {
            transform: translateY(-1px);
            filter: brightness(110%);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-icon.disabled,
        .btn-icon:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .empty-state {
            padding: 1.2rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 2rem;
            color: #cbd5e1;
        }

        .empty-state p {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .btn-empty {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.8rem;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.65rem;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 0.6rem;
            margin-bottom: 0.4rem;
        }

        .custom-pagination {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-prev,
        .page-next,
        .page-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            padding: 0 0.5rem;
            border-radius: 6px;
            background: white;
            color: #475569;
            text-decoration: none;
            border: 1px solid #e2e8f0;
            font-weight: 500;
            font-size: 0.65rem;
            cursor: pointer;
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
            cursor: not-allowed;
        }

        .page-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            color: #94a3b8;
            font-size: 0.65rem;
        }

        .table-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 0.3rem;
        }

        .footer-info small {
            font-size: 0.55rem;
            display: flex;
            align-items: center;
            gap: 0.2rem;
            color: #64748b;
        }

        .fw-bold {
            font-weight: 600;
        }

        .text-muted {
            color: #64748b !important;
        }

        .d-block {
            display: block;
        }

        @media (max-width: 768px) {
            .anggaran-container {
                padding: 0.6rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .stat-card {
                padding: 0.5rem;
            }

            .stat-icon {
                width: 34px;
                height: 34px;
                font-size: 1rem;
            }

            .stat-value {
                font-size: 0.9rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
            }

            .btn-group-year {
                width: 100%;
                justify-content: center;
            }

            .filter-section {
                flex-direction: column;
            }

            .search-wrapper {
                min-width: 100%;
            }

            .filter-wrapper {
                width: 100%;
                flex-direction: column;
            }

            .filter-select {
                width: 100%;
            }

            .btn-refresh {
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-icon {
                width: 100%;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let searchTimer;
            $('#searchAnggaran').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    filterTable();
                }, 500);
            });
            $('#tahunFilter, #statusFilter').on('change', function() {
                filterTable();
            });

            function filterTable() {
                var search = $('#searchAnggaran').val();
                var tahun = new URLSearchParams(window.location.search).get('tahun') || '';
                var status = $('#statusFilter').val();
                window.location.href = '{{ route('admin.anggaran.index') }}?search=' + encodeURIComponent(search) +
                    '&tahun=' + encodeURIComponent(tahun) + '&status=' + encodeURIComponent(status);
            }
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}'
                });
            @endif
            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: '{{ session('warning') }}',
                    timer: 3000
                });
            @endif
        });

        function deleteAnggaran(id) {
            Swal.fire({
                title: 'Hapus Anggaran',
                text: 'Yakin ingin menghapus anggaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/anggaran/' + id,
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
                                    })
                                    .then(() => window.location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = xhr.responseJSON?.message || 'Terjadi kesalahan';
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: message
                            });
                        }
                    });
                }
            });
        }

        function ubahStatus(id, status) {
            let confirmText = status == 'aktif' ? 'Yakin ingin membuka kembali anggaran ini?' :
                'Yakin ingin menutup anggaran ini?';
            Swal.fire({
                title: status == 'aktif' ? 'Buka Anggaran' : 'Tutup Anggaran',
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: status == 'aktif' ? '#10b981' : '#f59e0b',
                confirmButtonText: status == 'aktif' ? 'Ya, Buka' : 'Ya, Tutup',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/anggaran/' + id + '/status',
                        type: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    })
                                    .then(() => window.location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = xhr.responseJSON?.message || 'Terjadi kesalahan';
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: message
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
