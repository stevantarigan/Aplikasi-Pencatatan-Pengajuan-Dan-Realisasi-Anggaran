@extends('layouts.app')

@section('title', 'Daftar Pengajuan Anggaran')
@section('icon', 'file-earmark-text')

@section('content')
    <div class="pengajuan-container">
        <!-- Header - Dengan filter tahun -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Daftar Pengajuan Anggaran</h2>
                <p class="page-subtitle">Kelola pengajuan anggaran rumah sakit</p>
            </div>
            <div class="header-actions">
                <!-- Filter Tahun -->
                <div class="btn-group-year">
                    <a href="{{ route('admin.pengajuan.index', request()->except('tahun')) }}"
                        class="btn-year {{ !request('tahun') ? 'active' : '' }}">
                        Semua Tahun
                    </a>
                    @foreach ($tahunList as $tahun)
                        <a href="{{ route('admin.pengajuan.index', ['tahun' => $tahun] + request()->except('tahun')) }}"
                            class="btn-year {{ request('tahun') == $tahun ? 'active' : '' }}">
                            {{ $tahun }}
                        </a>
                    @endforeach
                </div>

                {{-- Tombol Tambah Pengajuan --}}
                <a href="{{ route('admin.pengajuan.create') }}" class="btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Pengajuan
                </a>
            </div>
        </div>

        <!-- Info Filter Aktif -->
        @if (request('tahun') || request('status') || request('bulan') || request('search'))
            <div class="filter-active">
                <i class="bi bi-funnel-fill"></i>
                Filter aktif:
                @if (request('tahun'))
                    Tahun {{ request('tahun') }}
                @endif
                @if (request('status'))
                    Status {{ ucfirst(request('status')) }}
                @endif
                @if (request('bulan'))
                    Bulan {{ \Carbon\Carbon::create()->month(request('bulan'))->format('F') }}
                @endif
                @if (request('search'))
                    Pencarian "{{ request('search') }}"
                @endif
                <a href="{{ route('admin.pengajuan.index') }}" class="clear-filter">
                    <i class="bi bi-x-circle"></i> Reset semua filter
                </a>
            </div>
        @endif

        <!-- Statistik - BERUBAH BERDASARKAN FILTER TAHUN -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Pengajuan</span>
                    <span class="stat-value">{{ $totalPengajuan }}</span>
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
                <div class="stat-icon bg-success">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Disetujui</span>
                    <span class="stat-value">{{ $totalDisetujui }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-danger">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Ditolak</span>
                    <span class="stat-value">{{ $totalDitolak }}</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="filter-section">
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="searchPengajuan" name="search"
                    placeholder="Cari nomor pengajuan, nama rencana, atau pengaju..." value="{{ request('search') }}">
            </div>
            <div class="filter-wrapper">
                <select class="filter-select" id="statusFilter" name="status">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="direalisasi" {{ request('status') == 'direalisasi' ? 'selected' : '' }}>Direalisasi
                    </option>
                </select>
                <select class="filter-select" id="bulanFilter" name="bulan">
                    <option value="">Semua Bulan</option>
                    @foreach (range(1, 12) as $bulan)
                        <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($bulan)->format('F') }}
                        </option>
                    @endforeach
                </select>
                <button class="btn-refresh"
                    onclick="window.location.href='{{ route('admin.pengajuan.index', request()->except(['status', 'bulan', 'search', 'page'])) }}'"
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
                            <th style="min-width:40px">No</th>
                            <th style="min-width:120px">Nomor Pengajuan</th>
                            <th style="min-width:170px">Nama Rencana</th>
                            <th style="min-width:120px">Pengaju</th>
                            <th style="min-width:90px">Bulan</th>
                            <th style="min-width:70px">Tahun</th>
                            <th style="min-width:110px">Jumlah</th>
                            <th style="min-width:90px">Status</th>
                            <th style="min-width:90px">Tgl Pengajuan</th>
                            <th style="min-width:90px">Tgl Setuju</th>
                            <th style="min-width:120px">Penyetuju</th>
                            <th style="min-width:180px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pengajuanTableBody">
                        @forelse ($pengajuan as $index => $item)
                            <tr>
                                <td>{{ $pengajuan->firstItem() + $index }}</td>

                                <td>
                                    <span class="nomor-pengajuan">{{ $item->nomor_pengajuan }}</span>
                                </td>

                                <td>
                                    @if ($item->anggaran && $item->anggaran->rencanaAnggaran)
                                        <div class="rencana-info">
                                            <span
                                                class="fw-bold">{{ $item->anggaran->rencanaAnggaran->nama_rencana }}</span>
                                            <small class="text-muted d-block">
                                                <i class="bi bi-hash"></i>
                                                {{ $item->anggaran->rencanaAnggaran->kodeRekening->kode ?? '-' }}
                                                |
                                                {{ $item->anggaran->rencanaAnggaran->kodeRekening->nama_rekening ?? '-' }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->pengaju)
                                        <div class="pengaju-info">
                                            <span
                                                class="pengaju-nama">{{ $item->pengaju->name ?? $item->pengaju->nama }}</span>
                                            <small class="pengaju-username">@ {{ $item->pengaju->username ?? '-' }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-bulan">
                                        <i class="bi bi-calendar-month"></i>
                                        {{ $item->nama_bulan }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge-tahun">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $item->anggaran->tahun ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-primary fw-bold">
                                    Rp {{ number_format($item->jumlah_diajukan, 0, ',', '.') }}
                                </td>

                                <td>
                                    @php
                                        $statusClass =
                                            [
                                                'diajukan' => 'status-diajukan',
                                                'disetujui' => 'status-disetujui',
                                                'ditolak' => 'status-ditolak',
                                                'direalisasi' => 'status-direalisasi',
                                            ][$item->status] ?? 'status-secondary';

                                        $statusIcon =
                                            [
                                                'diajukan' => 'bi-clock-history',
                                                'disetujui' => 'bi-check-circle',
                                                'ditolak' => 'bi-x-circle',
                                                'direalisasi' => 'bi-check2-all',
                                            ][$item->status] ?? 'bi-question-circle';
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="bi {{ $statusIcon }}"></i>
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}
                                </td>

                                <td>
                                    {{ $item->tanggal_persetujuan ? $item->tanggal_persetujuan->format('d/m/Y') : '-' }}
                                </td>

                                <td>
                                    @if ($item->penyetuju)
                                        <div class="penyetuju-info">
                                            <span
                                                class="penyetuju-nama">{{ $item->penyetuju->name ?? $item->penyetuju->nama }}</span>
                                            <small class="penyetuju-username">@
                                                {{ $item->penyetuju->username ?? '-' }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <!-- Tombol View untuk semua -->
                                        <a href="{{ route('admin.pengajuan.show', $item->id) }}"
                                            class="btn-icon btn-view" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Tombol Edit hanya untuk status diajukan dan user berhak -->
                                        @if ($item->status == 'diajukan')
                                            @if (Auth::user()->role == 'admin' || Auth::id() == $item->diajukan_oleh)
                                                <a href="{{ route('admin.pengajuan.edit', $item->id) }}"
                                                    class="btn-icon btn-edit" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                        @endif

                                        <!-- Tombol Approve/Reject hanya untuk admin dan status diajukan -->
                                        @if ($item->status == 'diajukan' && Auth::user()->role == 'admin')
                                            <div class="btn-group">
                                                <button class="btn-icon btn-approve"
                                                    onclick="approvePengajuan({{ $item->id }})" title="Setujui">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                <button class="btn-icon btn-reject"
                                                    onclick="rejectPengajuan({{ $item->id }})" title="Tolak">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        @endif

                                        <!-- Tombol Hapus hanya untuk status diajukan atau ditolak dan user berhak -->
                                        @if (in_array($item->status, ['diajukan', 'ditolak']))
                                            @if (Auth::user()->role == 'admin' || Auth::id() == $item->diajukan_oleh)
                                                <button class="btn-icon btn-delete"
                                                    onclick="deletePengajuan({{ $item->id }})" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-file-earmark-x"></i>
                                        <p class="mt-3 text-muted">
                                            @if (request('tahun') || request('status') || request('bulan') || request('search'))
                                                Tidak ada data pengajuan dengan filter yang dipilih
                                            @else
                                                Belum ada data pengajuan anggaran
                                            @endif
                                        </p>
                                        @if (request('tahun') || request('status') || request('bulan') || request('search'))
                                            <a href="{{ route('admin.pengajuan.index') }}" class="btn-empty">
                                                <i class="bi bi-x-circle"></i>
                                                Reset Filter
                                            </a>
                                        @else
                                            <a href="{{ route('admin.pengajuan.create') }}" class="btn-empty">
                                                <i class="bi bi-plus-lg"></i>
                                                Tambah Pengajuan Pertama
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
        @if ($pengajuan->hasPages())
            <div class="pagination-wrapper">
                <div class="custom-pagination">
                    {{-- Previous Page Link --}}
                    @if ($pengajuan->onFirstPage())
                        <span class="page-prev disabled">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $pengajuan->previousPageUrl() }}" class="page-prev">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $current = $pengajuan->currentPage();
                        $last = $pengajuan->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);

                        if ($start > 1) {
                            echo '<a href="' . $pengajuan->url(1) . '" class="page-number">1</a>';
                            if ($start > 2) {
                                echo '<span class="page-dots">...</span>';
                            }
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $current) {
                                echo '<span class="page-number active">' . $i . '</span>';
                            } else {
                                echo '<a href="' . $pengajuan->url($i) . '" class="page-number">' . $i . '</a>';
                            }
                        }

                        if ($end < $last) {
                            if ($end < $last - 1) {
                                echo '<span class="page-dots">...</span>';
                            }
                            echo '<a href="' . $pengajuan->url($last) . '" class="page-number">' . $last . '</a>';
                        }
                    @endphp

                    {{-- Next Page Link --}}
                    @if ($pengajuan->hasMorePages())
                        <a href="{{ $pengajuan->nextPageUrl() }}" class="page-next">
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
                    @if ($pengajuan->total() > 0)
                        Menampilkan {{ $pengajuan->firstItem() }} - {{ $pengajuan->lastItem() }}
                        dari total {{ $pengajuan->total() }} data
                        @if (request('tahun'))
                            untuk tahun {{ request('tahun') }}
                        @endif
                        @if (request('status'))
                            status {{ ucfirst(request('status')) }}
                        @endif
                        @if (request('bulan'))
                            bulan {{ \Carbon\Carbon::create()->month(request('bulan'))->format('F') }}
                        @endif
                    @else
                        Tidak ada data yang ditampilkan
                    @endif
                </small>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-check2-circle me-2"></i>
                        Setujui Pengajuan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="approveForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Menyetujui pengajuan akan mengurangi sisa anggaran secara otomatis.
                        </div>
                        <p>Apakah Anda yakin ingin menyetujui pengajuan ini?</p>
                        <input type="hidden" name="pengajuan_id" id="approve_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="approveSubmitBtn">Ya, Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-x-circle me-2"></i>
                        Tolak Pengajuan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menolak pengajuan ini?</p>
                        <div class="form-group mb-3">
                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="keterangan" id="rejectReason" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="pengajuan_id" id="reject_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" id="rejectSubmitBtn">Ya, Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* ============================================
                       STYLE COMPACT DIPERKECIL 2% (scale 0.98)
                       ============================================ */
        .pengajuan-container {
            background: white;
            border-radius: 10px;
            /* dari 12 */
            padding: 0.6rem;
            /* dari 0.85 */
            box-shadow: 0 2px 6px rgba(109, 93, 252, .06);
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .pengajuan-container {
            zoom: 0.92;
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

        /* Button Group Year */
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

        /* Button Primary (Tambah) */
        .btn-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
            border: none;
            padding: 0.45rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(109, 93, 252, 0.2);
            color: white;
        }

        .btn-primary i {
            font-size: 0.8rem;
        }

        /* Filter Active */
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

        .filter-active i {
            font-size: 0.8rem;
        }

        .clear-filter {
            color: #0369a1;
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.25rem;
        }

        .clear-filter:hover {
            color: #0284c7;
            text-decoration: underline;
        }

        /* Stats Grid */
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
            transition: all 0.2s ease;
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
            margin-bottom: 0.15rem;
        }

        .stat-value {
            display: block;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }

        /* Filter Section */
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
            padding: 0.45rem 1.5rem 0.45rem 0.7rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.7rem;
            min-width: 100px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #6d5dfc;
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
            transition: all 0.2s ease;
            text-decoration: none;
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
            min-width: 1200px;
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

        /* Nomor Pengajuan */
        .nomor-pengajuan {
            font-weight: 600;
            color: #1e293b;
            background: #f1f5f9;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            display: inline-block;
            font-size: 0.65rem;
        }

        /* Rencana Info */
        .rencana-info .fw-bold {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.7rem;
            display: block;
            margin-bottom: 0.15rem;
        }

        .rencana-info small {
            font-size: 0.6rem;
            color: #64748b;
        }

        .rencana-info small i {
            font-size: 0.6rem;
        }

        /* Badge Tahun & Bulan */
        .badge-tahun,
        .badge-bulan {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-tahun {
            background: #e0f2fe;
            color: #0284c7;
        }

        .badge-bulan {
            background: #f1f5f9;
            color: #475569;
        }

        .badge-tahun i,
        .badge-bulan i {
            font-size: 0.7rem;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.6rem;
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

        .status-direalisasi {
            background: #dbeafe;
            color: #2563eb;
        }

        .status-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Pengaju & Penyetuju Info */
        .pengaju-info,
        .penyetuju-info {
            display: flex;
            flex-direction: column;
        }

        .pengaju-nama,
        .penyetuju-nama {
            font-weight: 500;
            color: #1e293b;
            font-size: 0.7rem;
        }

        .pengaju-username,
        .penyetuju-username {
            font-size: 0.6rem;
            color: #64748b;
        }

        /* Text colors */
        .text-primary {
            color: #3b82f6 !important;
        }

        .fw-bold {
            font-weight: 600;
        }

        /* Action Buttons */
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

        .btn-approve {
            background: #10b981;
        }

        .btn-reject {
            background: #ef4444;
        }

        .btn-delete {
            background: #64748b;
        }

        .btn-icon:hover:not(:disabled) {
            transform: translateY(-1px);
            filter: brightness(110%);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-group {
            display: flex;
            gap: 0.2rem;
        }

        /* Empty State */
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
            transition: all 0.2s ease;
        }

        .btn-empty:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* Custom Pagination */
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
            transition: all 0.2s ease;
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
            cursor: not-allowed;
            pointer-events: none;
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

        /* Table Footer */
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

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 14px;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
            border: none;
            padding: 1rem;
        }

        .modal-header.bg-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .modal-header.bg-danger {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 1rem;
            font-size: 0.85rem;
        }

        .modal-footer {
            padding: 0.8rem 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.4rem;
            font-size: 0.8rem;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #6d5dfc;
            box-shadow: 0 0 0 2px rgba(109, 93, 252, 0.1);
        }

        .alert-info {
            background-color: #e0f2fe;
            border: 1px solid #7dd3fc;
            border-radius: 6px;
            color: #0369a1;
            padding: 0.5rem 0.8rem;
            font-size: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pengajuan-container {
                padding: 0.6rem;
                transform: scale(0.98);
                margin-right: 0;
                margin-bottom: -0.3rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
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

            .btn-primary {
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

            .page-prev,
            .page-next,
            .page-number {
                min-width: 28px;
                height: 28px;
                font-size: 0.6rem;
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let searchTimer;
            $('#searchPengajuan').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    filterTable();
                }, 500);
            });

            $('#statusFilter, #bulanFilter').on('change', function() {
                filterTable();
            });

            function filterTable() {
                var search = $('#searchPengajuan').val();
                var tahun = getUrlParameter('tahun') || '';
                var status = $('#statusFilter').val();
                var bulan = $('#bulanFilter').val();

                var url = '{{ route('admin.pengajuan.index') }}?' +
                    'search=' + encodeURIComponent(search) +
                    '&tahun=' + encodeURIComponent(tahun) +
                    '&status=' + encodeURIComponent(status) +
                    '&bulan=' + encodeURIComponent(bulan);

                window.location.href = url;
            }

            function getUrlParameter(name) {
                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                if (results == null) {
                    return null;
                }
                return decodeURIComponent(results[1]) || 0;
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        function approvePengajuan(id) {
            Swal.fire({
                title: 'Setujui Pengajuan',
                html: '<div class="alert alert-info">Menyetujui pengajuan akan mengurangi sisa anggaran secara otomatis.</div>Apakah Anda yakin ingin menyetujui pengajuan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/pengajuan') }}/' + id + '/approve',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT'
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
                                    'Terjadi kesalahan saat menyetujui pengajuan'
                            });
                        }
                    });
                }
            });
        }

        function rejectPengajuan(id) {
            Swal.fire({
                title: 'Tolak Pengajuan',
                input: 'textarea',
                inputLabel: 'Alasan Penolakan',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                inputAttributes: {
                    required: true,
                    rows: 4
                },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan penolakan harus diisi!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/pengajuan') }}/' + id + '/reject',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT',
                            keterangan: result.value
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
                                    'Terjadi kesalahan saat menolak pengajuan'
                            });
                        }
                    });
                }
            });
        }

        function deletePengajuan(id) {
            Swal.fire({
                title: 'Hapus Pengajuan',
                text: 'Apakah Anda yakin ingin menghapus pengajuan ini?',
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
                        url: '{{ url('admin/pengajuan') }}/' + id,
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
                                    'Terjadi kesalahan saat menghapus pengajuan'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
