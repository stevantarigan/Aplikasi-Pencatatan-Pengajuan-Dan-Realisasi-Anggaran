@extends('layouts.app')

@section('title', 'Daftar Realisasi Anggaran')
@section('icon', 'check2-circle')

@section('content')
    <div class="realisasi-container">
        <!-- Header - Dengan filter tahun -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Daftar Realisasi Anggaran</h2>
                <p class="page-subtitle">Kelola realisasi anggaran dari pengajuan yang telah disetujui</p>
            </div>
            <div class="header-actions">
                <!-- Filter Tahun -->
                <div class="btn-group-year">
                    <a href="{{ route('admin.realisasi.index', request()->except('tahun')) }}"
                        class="btn-year {{ !request('tahun') ? 'active' : '' }}">
                        Semua Tahun
                    </a>
                    @foreach ($tahunList as $tahun)
                        <a href="{{ route('admin.realisasi.index', ['tahun' => $tahun] + request()->except('tahun')) }}"
                            class="btn-year {{ request('tahun') == $tahun ? 'active' : '' }}">
                            {{ $tahun }}
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('admin.realisasi.create') }}" class="btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Realisasi
                </a>
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
                <a href="{{ route('admin.realisasi.index') }}" class="clear-filter">
                    <i class="bi bi-x-circle"></i> Reset semua filter
                </a>
            </div>
        @endif

        <!-- Statistik -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Realisasi</span>
                    <span class="stat-value">{{ $totalRealisasi }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-calendar"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Bulan Tercatat</span>
                    <span class="stat-value">{{ $bulanTercatat }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Nominal</span>
                    <span class="stat-value">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Rata-rata/Bulan</span>
                    <span class="stat-value">
                        Rp {{ number_format($rataRata, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="filter-section">
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="searchRealisasi" name="search"
                    placeholder="Cari nomor pengajuan, nama rencana, atau keterangan..." value="{{ request('search') }}">
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
                <select class="filter-select" id="bulanFilter" name="bulan">
                    <option value="">Semua Bulan</option>
                    <option value="Januari" {{ request('bulan') == 'Januari' ? 'selected' : '' }}>Januari</option>
                    <option value="Februari" {{ request('bulan') == 'Februari' ? 'selected' : '' }}>Februari</option>
                    <option value="Maret" {{ request('bulan') == 'Maret' ? 'selected' : '' }}>Maret</option>
                    <option value="April" {{ request('bulan') == 'April' ? 'selected' : '' }}>April</option>
                    <option value="Mei" {{ request('bulan') == 'Mei' ? 'selected' : '' }}>Mei</option>
                    <option value="Juni" {{ request('bulan') == 'Juni' ? 'selected' : '' }}>Juni</option>
                    <option value="Juli" {{ request('bulan') == 'Juli' ? 'selected' : '' }}>Juli</option>
                    <option value="Agustus" {{ request('bulan') == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                    <option value="September" {{ request('bulan') == 'September' ? 'selected' : '' }}>September</option>
                    <option value="Oktober" {{ request('bulan') == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                    <option value="November" {{ request('bulan') == 'November' ? 'selected' : '' }}>November</option>
                    <option value="Desember" {{ request('bulan') == 'Desember' ? 'selected' : '' }}>Desember</option>
                </select>
                <button class="btn-refresh"
                    onclick="window.location.href='{{ route('admin.realisasi.index', request()->except(['tahun', 'bulan', 'search', 'page'])) }}'"
                    title="Reset Filter">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="min-width:40px">No</th>
                            <th style="min-width:120px">Nomor Pengajuan</th>
                            <th style="min-width:160px">Nama Rencana</th>
                            <th style="min-width:110px">Kode Rekening</th>
                            <th style="min-width:85px">Bulan</th>
                            <th style="min-width:60px">Tahun</th>
                            <th style="min-width:110px">Jumlah Diajukan</th>
                            <th style="min-width:110px">Jumlah Realisasi</th>
                            <th style="min-width:90px">Sisa</th>
                            <th style="min-width:95px">Tgl Realisasi</th>
                            <th style="min-width:150px">Keterangan</th>
                            <th style="min-width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="realisasiTableBody">
                        @forelse ($realisasi as $index => $item)
                            <tr>
                                <td>{{ $realisasi->firstItem() + $index }}</td>
                                <td>
                                    <span class="nomor-pengajuan">{{ $item->nomor_pengajuan }}</span>
                                </td>
                                <td>
                                    @if ($item->anggaran && $item->anggaran->rencanaAnggaran)
                                        <div class="rencana-info">
                                            <span
                                                class="fw-bold">{{ $item->anggaran->rencanaAnggaran->nama_rencana }}</span>
                                            <small class="text-muted d-block">
                                                <i class="bi bi-clipboard-heart"></i>
                                                {{ $item->anggaran->rencanaAnggaran->nama_kegiatan ?? '-' }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="kode-rekening">
                                        {{ $item->anggaran->rencanaAnggaran->kodeRekening->kode ?? '-' }}
                                    </span>
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
                                <td class="text-primary">
                                    Rp {{ number_format($item->jumlah_diajukan, 0, ',', '.') }}
                                </td>
                                <td class="text-success fw-bold">
                                    Rp {{ number_format($item->jumlah_realisasi, 0, ',', '.') }}
                                </td>
                                <td>
                                    @php
                                        $sisa = $item->sisa_anggaran;
                                        $sisaClass = $sisa > 0 ? 'text-warning' : 'text-muted';
                                    @endphp
                                    <span class="{{ $sisaClass }}">
                                        Rp {{ number_format($sisa, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="tanggal-info">
                                        <i class="bi bi-clock"></i>
                                        {{ $item->tanggal_realisasi ? $item->tanggal_realisasi->format('d/m/Y') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->keterangan_realisasi)
                                        <span class="keterangan-text" title="{{ $item->keterangan_realisasi }}">
                                            {{ Str::limit($item->keterangan_realisasi, 25) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.realisasi.show', $item->id) }}"
                                            class="btn-icon btn-view" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.realisasi.edit', $item->id) }}"
                                            class="btn-icon btn-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn-icon btn-delete"
                                            onclick="deleteRealisasi({{ $item->id }})" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-3">
                                    <div class="empty-state">
                                        <i class="bi bi-file-earmark-x"></i>
                                        <p class="text-muted">
                                            @if (request('tahun') || request('bulan') || request('search'))
                                                Tidak ada data realisasi dengan filter yang dipilih
                                            @else
                                                Data realisasi belum tersedia
                                            @endif
                                        </p>
                                        @if (request('tahun') || request('bulan') || request('search'))
                                            <a href="{{ route('admin.realisasi.index') }}" class="btn-empty">
                                                <i class="bi bi-x-circle"></i> Reset Filter
                                            </a>
                                        @else
                                            <a href="{{ route('admin.realisasi.create') }}" class="btn-empty">
                                                <i class="bi bi-plus-lg"></i> Tambah Realisasi Pertama
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
        @if ($realisasi->hasPages())
            <div class="pagination-wrapper">
                <div class="custom-pagination">
                    @if ($realisasi->onFirstPage())
                        <span class="page-prev disabled"><i class="bi bi-chevron-left"></i></span>
                    @else
                        <a href="{{ $realisasi->previousPageUrl() }}" class="page-prev"><i
                                class="bi bi-chevron-left"></i></a>
                    @endif

                    @php
                        $current = $realisasi->currentPage();
                        $last = $realisasi->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                        if ($start > 1) {
                            echo '<a href="' . $realisasi->url(1) . '" class="page-number">1</a>';
                            if ($start > 2) {
                                echo '<span class="page-dots">...</span>';
                            }
                        }
                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $current) {
                                echo '<span class="page-number active">' . $i . '</span>';
                            } else {
                                echo '<a href="' . $realisasi->url($i) . '" class="page-number">' . $i . '</a>';
                            }
                        }
                        if ($end < $last) {
                            if ($end < $last - 1) {
                                echo '<span class="page-dots">...</span>';
                            }
                            echo '<a href="' . $realisasi->url($last) . '" class="page-number">' . $last . '</a>';
                        }
                    @endphp

                    @if ($realisasi->hasMorePages())
                        <a href="{{ $realisasi->nextPageUrl() }}" class="page-next"><i
                                class="bi bi-chevron-right"></i></a>
                    @else
                        <span class="page-next disabled"><i class="bi bi-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Footer Info -->
        <div class="table-footer">
            <div class="footer-info">
                <small>
                    <i class="bi bi-info-circle"></i>
                    @if ($realisasi->total() > 0)
                        Menampilkan {{ $realisasi->firstItem() }} - {{ $realisasi->lastItem() }}
                        dari total {{ $realisasi->total() }} data
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

    <!-- Add Realisasi Modal -->
    <div class="modal fade" id="addRealisasiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Realisasi Anggaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addRealisasiForm" action="{{ route('admin.realisasi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Pilih Pengajuan <span class="text-danger">*</span></label>
                                <select class="form-select" name="pengajuan_id" id="pengajuanSelect" required>
                                    <option value="">Pilih Pengajuan yang Telah Disetujui</option>
                                    @if (isset($pengajuan) && $pengajuan->count() > 0)
                                        @foreach ($pengajuan as $p)
                                            @if ($p->status == 'disetujui' && !$p->tanggal_realisasi)
                                                <option value="{{ $p->id }}"
                                                    data-nomor="{{ $p->nomor_pengajuan }}"
                                                    data-nama-rencana="{{ $p->anggaran->rencanaAnggaran->nama_rencana ?? '' }}"
                                                    data-kegiatan="{{ $p->anggaran->rencanaAnggaran->nama_kegiatan ?? '' }}"
                                                    data-kode="{{ $p->anggaran->rencanaAnggaran->kodeRekening->kode ?? '' }}"
                                                    data-jumlah="{{ $p->jumlah_diajukan }}"
                                                    data-bulan="{{ $p->nama_bulan }}"
                                                    data-tahun="{{ $p->anggaran->tahun ?? '' }}">
                                                    [{{ $p->nomor_pengajuan }}]
                                                    {{ $p->anggaran->rencanaAnggaran->nama_rencana ?? ($p->anggaran->rencanaAnggaran->nama_kegiatan ?? '') }}
                                                    - {{ $p->nama_bulan }} {{ $p->anggaran->tahun ?? '' }} -
                                                    Rp {{ number_format($p->jumlah_diajukan, 0, ',', '.') }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if (!isset($pengajuan) || $pengajuan->count() == 0)
                                    <small class="text-danger">Tidak ada pengajuan yang siap direalisasi</small>
                                @endif
                            </div>
                        </div>

                        <!-- Info Pengajuan -->
                        <div class="alert alert-info" id="infoPengajuan" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <small><strong>Nomor Pengajuan:</strong> <span id="infoNomor"></span></small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Nama Rencana:</strong> <span id="infoNamaRencana"></span></small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Kegiatan:</strong> <span id="infoKegiatan"></span></small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Kode Rekening:</strong> <span id="infoKodeRekening"></span></small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Periode:</strong> <span id="infoPeriode"></span></small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Jumlah Diajukan:</strong> Rp <span id="infoJumlah"></span></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Realisasi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" name="jumlah_realisasi_display"
                                        id="jumlah_realisasi_display" required onkeyup="formatRupiah(this)">
                                    <input type="hidden" name="jumlah_realisasi" id="jumlah_realisasi">
                                </div>
                                <small class="text-muted">Maksimal: Rp <span id="maxJumlah">0</span></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Realisasi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_realisasi"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Realisasi</label>
                            <textarea class="form-control" name="keterangan_realisasi" rows="3"
                                placeholder="Masukkan keterangan realisasi..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Realisasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Realisasi Modal -->
    <div class="modal fade" id="viewRealisasiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-eye me-2"></i>
                        Detail Realisasi Anggaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewRealisasiContent">
                    <!-- Content akan diisi via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Realisasi Modal -->
    <div class="modal fade" id="editRealisasiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Realisasi Anggaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editRealisasiForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Realisasi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" name="edit_jumlah_realisasi_display"
                                        id="edit_jumlah_realisasi_display" required onkeyup="formatRupiah(this)">
                                    <input type="hidden" name="jumlah_realisasi" id="edit_jumlah_realisasi">
                                </div>
                                <small class="text-muted">Maksimal: Rp <span id="editMaxJumlah">0</span></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Realisasi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_realisasi"
                                    id="edit_tanggal_realisasi" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Realisasi</label>
                            <textarea class="form-control" name="keterangan_realisasi" id="edit_keterangan_realisasi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update Realisasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* ============================================
                       STYLE COMPACT - DISAMAKAN DENGAN DAFTAR ANGGARAN
                       ============================================ */
        .realisasi-container {
            background: white;
            border-radius: 10px;
            /* lebih kecil */
            padding: 0.6rem;
            /* dari 0.85rem */
            box-shadow: 0 2px 6px rgba(109, 93, 252, .06);
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
            zoom: .92;
            /* optional, kalau mau sedikit diperkecil proporsional */
        }

        .realisasi-container {
            zoom: 0.90;
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

        /* Table wrapper */
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

        .nomor-pengajuan {
            font-weight: 600;
            color: #1e293b;
            background: #f1f5f9;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            display: inline-block;
            font-size: 0.65rem;
        }

        .kode-rekening {
            font-family: 'Courier New', monospace;
            background: #f1f5f9;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-size: 0.65rem;
        }

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

        .tanggal-info {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.7rem;
        }

        .keterangan-text {
            color: #334155;
            cursor: help;
            border-bottom: 1px dashed #cbd5e1;
            font-size: 0.7rem;
        }

        .text-primary {
            color: #3b82f6 !important;
        }

        .text-success {
            color: #10b981 !important;
        }

        .text-warning {
            color: #f59e0b !important;
        }

        .text-muted {
            color: #64748b !important;
        }

        .fw-bold {
            font-weight: 600;
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
            background: #64748b;
        }

        .btn-icon:hover {
            transform: translateY(-1px);
            filter: brightness(110%);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
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
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-empty:hover {
            background: #e2e8f0;
            color: #1e293b;
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

        .d-block {
            display: block;
        }

        /* Modal styles (tidak diubah agar fungsionalitas tetap nyaman) */
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

        .modal-header.bg-info {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        }

        .modal-header.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
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

        .form-control,
        .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #6d5dfc;
            box-shadow: 0 0 0 2px rgba(109, 93, 252, 0.1);
        }

        .input-group-text {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px 0 0 6px;
            color: #475569;
            font-size: 0.8rem;
        }

        .alert-info {
            background-color: #e0f2fe;
            border: 1px solid #7dd3fc;
            border-radius: 6px;
            color: #0369a1;
            padding: 0.5rem 0.8rem;
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .realisasi-container {
                padding: 0.6rem;
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
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Search functionality with URL params
            $('#searchRealisasi, #tahunFilter, #bulanFilter').on('change keyup', function() {
                filterTable();
            });

            function filterTable() {
                var searchValue = $('#searchRealisasi').val();
                var tahunValue = $('#tahunFilter').val();
                var bulanValue = $('#bulanFilter').val();

                var url = new URL(window.location.href);
                if (searchValue) url.searchParams.set('search', searchValue);
                else url.searchParams.delete('search');

                if (tahunValue) url.searchParams.set('tahun', tahunValue);
                else url.searchParams.delete('tahun');

                if (bulanValue) url.searchParams.set('bulan', bulanValue);
                else url.searchParams.delete('bulan');

                window.location.href = url.toString();
            }

            // Helper function to get URL parameter
            function getUrlParameter(name) {
                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                if (results == null) {
                    return null;
                }
                return decodeURIComponent(results[1]) || 0;
            }

            // Ketika memilih pengajuan, tampilkan info
            $('#pengajuanSelect').on('change', function() {
                let selected = $(this).find('option:selected');
                let nomor = selected.data('nomor');
                let namaRencana = selected.data('nama-rencana');
                let kegiatan = selected.data('kegiatan');
                let kodeRekening = selected.data('kode');
                let jumlah = selected.data('jumlah');
                let bulan = selected.data('bulan');
                let tahun = selected.data('tahun');

                if (nomor) {
                    $('#infoPengajuan').show();
                    $('#infoNomor').text(nomor);
                    $('#infoNamaRencana').text(namaRencana);
                    $('#infoKegiatan').text(kegiatan);
                    $('#infoKodeRekening').text(kodeRekening);
                    $('#infoPeriode').text(bulan + ' ' + tahun);
                    $('#infoJumlah').text(formatRupiahValue(jumlah));
                    $('#maxJumlah').text(formatRupiahValue(jumlah));
                    $('#jumlah_realisasi_display').val('');
                    $('#jumlah_realisasi').val('');
                } else {
                    $('#infoPengajuan').hide();
                }
            });

            // Validasi jumlah realisasi tidak melebihi maksimal
            $('#jumlah_realisasi_display').on('keyup', function() {
                let value = $(this).val().replace(/\./g, '');
                let max = $('#pengajuanSelect').find('option:selected').data('jumlah') || 0;

                if (parseInt(value) > parseInt(max)) {
                    $(this).addClass('is-invalid');
                    Swal.fire({
                        icon: 'error',
                        title: 'Melebihi Batas',
                        text: 'Jumlah realisasi tidak boleh melebihi Rp ' + formatRupiahValue(max)
                    });
                    $(this).val(formatRupiahValue(max));
                    $('#jumlah_realisasi').val(max);
                } else {
                    $(this).removeClass('is-invalid');
                    $('#jumlah_realisasi').val(value);
                }
            });
        });

        // Format Rupiah
        function formatRupiah(element) {
            let value = element.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let remainder = split[0].length % 3;
            let rupiah = split[0].substr(0, remainder);
            let thousand = split[0].substr(remainder).match(/\d{3}/gi);

            if (thousand) {
                let separator = remainder ? '.' : '';
                rupiah += separator + thousand.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            element.value = rupiah;

            // Update hidden input
            $(element).siblings('input[type=hidden]').val(value);
        }

        function formatRupiahValue(angka) {
            if (!angka) return '';
            let value = angka.toString().replace(/[^,\d]/g, '');
            let split = value.split(',');
            let remainder = split[0].length % 3;
            let rupiah = split[0].substr(0, remainder);
            let thousand = split[0].substr(remainder).match(/\d{3}/gi);

            if (thousand) {
                let separator = remainder ? '.' : '';
                rupiah += separator + thousand.join('.');
            }

            return rupiah;
        }

        // View Realisasi
        function viewRealisasi(id) {
            $.ajax({
                url: '{{ url('admin/realisasi') }}/' + id,
                type: 'GET',
                success: function(response) {
                    // Implement view logic
                    Swal.fire({
                        title: 'Detail Realisasi',
                        html: `
                            <div style="text-align: left">
                                <p><strong>Nomor Pengajuan:</strong> ${response.nomor_pengajuan}</p>
                                <p><strong>Nama Rencana:</strong> ${response.nama_rencana}</p>
                                <p><strong>Kegiatan:</strong> ${response.nama_kegiatan}</p>
                                <p><strong>Kode Rekening:</strong> ${response.kode_rekening}</p>
                                <p><strong>Periode:</strong> ${response.bulan} ${response.tahun}</p>
                                <p><strong>Jumlah Diajukan:</strong> Rp ${formatRupiahValue(response.jumlah_diajukan)}</p>
                                <p><strong>Jumlah Realisasi:</strong> Rp ${formatRupiahValue(response.jumlah_realisasi)}</p>
                                <p><strong>Sisa:</strong> Rp ${formatRupiahValue(response.sisa)}</p>
                                <p><strong>Tanggal Realisasi:</strong> ${response.tanggal_realisasi}</p>
                                <p><strong>Keterangan:</strong> ${response.keterangan_realisasi || '-'}</p>
                                <p><strong>Pengaju:</strong> ${response.pengaju}</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonColor: '#3b82f6'
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengambil data realisasi'
                    });
                }
            });
        }

        // Edit Realisasi
        function editRealisasi(id) {
            $.ajax({
                url: '{{ url('admin/realisasi') }}/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    // Implement edit logic
                    $('#editRealisasiForm').attr('action', '{{ url('admin/realisasi') }}/' + id);
                    $('#edit_jumlah_realisasi_display').val(formatRupiahValue(response.jumlah_realisasi));
                    $('#edit_jumlah_realisasi').val(response.jumlah_realisasi);
                    $('#edit_tanggal_realisasi').val(response.tanggal_realisasi ? response.tanggal_realisasi
                        .split(' ')[0] : '');
                    $('#edit_keterangan_realisasi').val(response.keterangan_realisasi || '');
                    $('#editMaxJumlah').text(formatRupiahValue(response.jumlah_diajukan));
                    $('#editRealisasiModal').modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengambil data realisasi'
                    });
                }
            });
        }

        // Delete Realisasi
        function deleteRealisasi(id) {
            Swal.fire({
                title: 'Hapus Realisasi',
                text: 'Apakah Anda yakin ingin menghapus realisasi ini? Data akan dikembalikan ke status disetujui.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/realisasi') }}/' + id,
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
                                    text: response.message ||
                                        'Terjadi kesalahan saat menghapus data'
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = 'Terjadi kesalahan saat menghapus data';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
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
