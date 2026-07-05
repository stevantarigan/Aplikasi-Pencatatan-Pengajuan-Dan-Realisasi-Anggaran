@extends('layouts.app')

@section('title', 'Detail Anggaran')
@section('icon', 'eye')

@section('content')
    <div class="anggaran-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Detail Anggaran</h2>
                <p class="page-subtitle">Informasi lengkap anggaran dari rencana yang disetujui</p>
            </div>
            <div class="header-actions">
                {{-- Tombol Edit - Hanya untuk Admin --}}
                @if (auth()->user()->role == 'admin')
                    <a href="{{ route('admin.anggaran.edit', $anggaran->id) }}" class="btn-edit-page"
                        {{ $anggaran->pengajuan->count() > 0 ? 'onclick="return false;" style="opacity:0.5; pointer-events:none;"' : '' }}>
                        <i class="bi bi-pencil"></i>
                        Edit
                    </a>
                @endif

                {{-- Tombol Kembali untuk semua user --}}
                <a href="{{ route('admin.anggaran.index') }}" class="btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="detail-grid">
            <!-- Main Info Card -->
            <div class="detail-card main-card">
                <div class="card-header">
                    <div class="header-icon">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <h3>Informasi Anggaran</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>ID Anggaran</label>
                        <p class="value">#{{ $anggaran->id }}</p>
                    </div>

                    <div class="info-group">
                        <label>Status</label>
                        @if ($anggaran->status == 'aktif')
                            <span class="badge-status bg-success">Aktif</span>
                        @else
                            <span class="badge-status bg-secondary">Ditutup</span>
                        @endif
                    </div>

                    <div class="info-row">
                        <div class="info-group">
                            <label>Tahun Anggaran</label>
                            <p class="value"><i class="bi bi-calendar3"></i> {{ $anggaran->tahun }}</p>
                        </div>
                        <div class="info-group">
                            <label>Total Anggaran</label>
                            <p class="value text-primary fw-bold">Rp
                                {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="info-group">
                        <label>Sisa Anggaran</label>
                        @php
                            $sisa = $anggaran->sisa_anggaran;
                            $sisaClass = 'text-success';
                            $sisaIcon = 'bi-check-circle';

                            if ($sisa <= 0) {
                                $sisaClass = 'text-danger';
                                $sisaIcon = 'bi-exclamation-circle';
                            } elseif ($sisa < $anggaran->total_anggaran * 0.2) {
                                $sisaClass = 'text-warning';
                                $sisaIcon = 'bi-exclamation-triangle';
                            }

                            $persentase =
                                $anggaran->total_anggaran > 0
                                    ? round((($anggaran->total_anggaran - $sisa) / $anggaran->total_anggaran) * 100, 1)
                                    : 0;
                        @endphp
                        <p class="value {{ $sisaClass }}">
                            <i class="bi {{ $sisaIcon }}"></i>
                            Rp {{ number_format($sisa, 0, ',', '.') }}
                            <small class="text-muted">(Terpakai {{ $persentase }}%)</small>
                        </p>
                    </div>

                    <div class="info-group">
                        <label>Dibuat Pada</label>
                        <p class="value"><i class="bi bi-clock"></i> {{ $anggaran->created_at->format('d F Y H:i') }}</p>
                    </div>

                    <div class="info-group">
                        <label>Terakhir Diupdate</label>
                        <p class="value"><i class="bi bi-clock"></i> {{ $anggaran->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Rencana Info Card -->
            <div class="detail-card">
                <div class="card-header">
                    <div class="header-icon bg-info">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <h3>Informasi Rencana</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Nama Rencana</label>
                        <p class="value fw-bold">{{ $anggaran->rencanaAnggaran->nama_rencana ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Kode Rekening</label>
                        <p class="kode-rekening">{{ $anggaran->rencanaAnggaran->kodeRekening->kode ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Nama Rekening</label>
                        <p class="value">{{ $anggaran->rencanaAnggaran->kodeRekening->nama_rekening ?? '-' }}</p>
                    </div>

                    @if ($anggaran->rencanaAnggaran && $anggaran->rencanaAnggaran->keterangan)
                        <div class="info-group">
                            <label>Keterangan Rencana</label>
                            <p class="value">{{ $anggaran->rencanaAnggaran->keterangan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pengaju Info Card -->
            <div class="detail-card">
                <div class="card-header">
                    <div class="header-icon bg-warning">
                        <i class="bi bi-person"></i>
                    </div>
                    <h3>Informasi Pengaju Rencana</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Nama Pengaju</label>
                        <p class="value">
                            {{ $anggaran->rencanaAnggaran->pengaju->name ?? ($anggaran->rencanaAnggaran->pengaju->nama ?? '-') }}
                        </p>
                    </div>

                    <div class="info-group">
                        <label>Username</label>
                        <p class="value">{{ $anggaran->rencanaAnggaran->pengaju->username ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Role</label>
                        <p class="value">{{ ucfirst($anggaran->rencanaAnggaran->pengaju->role ?? '-') }}</p>
                    </div>

                    <div class="info-group">
                        <label>Tanggal Pengajuan Rencana</label>
                        <p class="value">{{ $anggaran->rencanaAnggaran->created_at->format('d F Y') ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Penyetuju Info Card (if available) -->
            @if ($anggaran->rencanaAnggaran && $anggaran->rencanaAnggaran->penyetuju)
                <div class="detail-card">
                    <div class="card-header">
                        <div class="header-icon bg-primary">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <h3>Informasi Penyetuju Rencana</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-group">
                            <label>Nama Penyetuju</label>
                            <p class="value">
                                {{ $anggaran->rencanaAnggaran->penyetuju->name ?? ($anggaran->rencanaAnggaran->penyetuju->nama ?? '-') }}
                            </p>
                        </div>

                        <div class="info-group">
                            <label>Username</label>
                            <p class="value">{{ $anggaran->rencanaAnggaran->penyetuju->username ?? '-' }}</p>
                        </div>

                        <div class="info-group">
                            <label>Role</label>
                            <p class="value">{{ ucfirst($anggaran->rencanaAnggaran->penyetuju->role ?? '-') }}</p>
                        </div>

                        <div class="info-group">
                            <label>Tanggal Persetujuan Rencana</label>
                            <p class="value">
                                {{ $anggaran->rencanaAnggaran->tanggal_persetujuan ? \Carbon\Carbon::parse($anggaran->rencanaAnggaran->tanggal_persetujuan)->format('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Daftar Pengajuan Card -->
            <div class="detail-card full-width">
                <div class="card-header">
                    <div class="header-icon bg-success">
                        <i class="bi bi-send"></i>
                    </div>
                    <h3>Daftar Pengajuan Anggaran</h3>
                </div>
                <div class="card-body">
                    @if ($anggaran->pengajuan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Pengajuan</th>
                                        <th>Bulan</th>
                                        <th>Jumlah Diajukan</th>
                                        <th>Jumlah Realisasi</th>
                                        <th>Status</th>
                                        <th>Tgl Pengajuan</th>
                                        <th>Tgl Realisasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anggaran->pengajuan as $index => $pengajuan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <span class="nomor-pengajuan">{{ $pengajuan->nomor_pengajuan }}</span>
                                            </td>
                                            <td>{{ $pengajuan->nama_bulan }}</td>
                                            <td>Rp {{ number_format($pengajuan->jumlah_diajukan, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($pengajuan->jumlah_realisasi)
                                                    Rp {{ number_format($pengajuan->jumlah_realisasi, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge-status {{ $pengajuan->status_badge_class }}">
                                                    <i class="bi bi-{{ $pengajuan->status_icon }}"></i>
                                                    {{ ucfirst($pengajuan->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $pengajuan->tanggal_pengajuan->format('d/m/Y') }}</td>
                                            <td>{{ $pengajuan->tanggal_realisasi ? $pengajuan->tanggal_realisasi->format('d/m/Y') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">Belum ada pengajuan untuk anggaran ini.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="detail-actions">
            {{-- Tombol Buat Pengajuan - Semua user bisa buat pengajuan jika anggaran aktif dan masih ada sisa --}}
            @if ($anggaran->status == 'aktif' && $anggaran->sisa_anggaran > 0)
                <a href="{{ route('admin.pengajuan.create', ['anggaran_id' => $anggaran->id]) }}" class="btn-pengajuan">
                    <i class="bi bi-send"></i>
                    Buat Pengajuan
                </a>
            @endif

            {{-- Tombol Print - Semua user bisa print --}}
            <button class="btn-print" onclick="window.print()">
                <i class="bi bi-printer"></i>
                Print
            </button>

            {{-- Tombol Hapus - Hanya Admin, dan hanya jika belum ada pengajuan --}}
            @if (auth()->user()->role == 'admin')
                <button class="btn-delete-page" onclick="deleteAnggaran({{ $anggaran->id }})"
                    {{ $anggaran->pengajuan->count() > 0 ? 'disabled' : '' }}>
                    <i class="bi bi-trash"></i>
                    Hapus
                </button>
            @endif
        </div>
    </div>

    <style>
        .anggaran-container {
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
            gap: 1rem;
        }

        /* Button Secondary */
        .btn-secondary {
            background: white;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateX(-3px);
            color: #1e293b;
        }

        .btn-edit-page {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
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
        }

        .btn-edit-page:hover:not([disabled]) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
        }

        .btn-edit-page[disabled] {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .btn-pengajuan {
            background: linear-gradient(135deg, #10b981, #34d399);
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
        }

        .btn-pengajuan:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        /* Detail Grid */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .detail-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border-color: #6d5dfc;
        }

        .detail-card.main-card {
            grid-column: span 2;
        }

        .detail-card.full-width {
            grid-column: 1 / -1;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .header-icon.bg-info {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        }

        .header-icon.bg-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .header-icon.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .header-icon.bg-primary {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }

        .card-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-group label {
            font-size: 0.8rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-group .value {
            font-size: 1rem;
            color: #1e293b;
            margin: 0;
            line-height: 1.5;
        }

        .info-group .nomor-pengajuan {
            font-weight: 600;
            color: #1e293b;
            background: #f1f5f9;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            display: inline-block;
            font-size: 0.9rem;
        }

        .kode-rekening {
            font-family: 'Courier New', monospace;
            background: #f1f5f9;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            display: inline-block;
            font-size: 0.9rem;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
            white-space: nowrap;
        }

        .bg-success {
            background: #10b981;
        }

        .bg-warning {
            background: #f59e0b;
        }

        .bg-danger {
            background: #ef4444;
        }

        .bg-info {
            background: #0ea5e9;
        }

        .bg-secondary {
            background: #64748b;
        }

        .text-success {
            color: #10b981 !important;
        }

        .text-warning {
            color: #f59e0b !important;
        }

        .text-danger {
            color: #ef4444 !important;
        }

        .text-primary {
            color: #3b82f6 !important;
        }

        .fw-bold {
            font-weight: 600;
        }

        /* Table kecil untuk daftar pengajuan */
        .table-sm {
            font-size: 0.9rem;
            width: 100%;
        }

        .table-sm th {
            background: #f1f5f9;
            padding: 0.5rem;
            font-weight: 600;
            color: #475569;
            text-align: left;
        }

        .table-sm td {
            padding: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-sm tr:last-child td {
            border-bottom: none;
        }

        /* Detail Actions */
        .detail-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn-print {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-print:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn-delete-page {
            background: #ef4444;
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
        }

        .btn-delete-page:hover:not([disabled]) {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }

        .btn-delete-page[disabled] {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Print Styles */
        @media print {

            .btn-secondary,
            .btn-edit-page,
            .btn-pengajuan,
            .btn-print,
            .btn-delete-page,
            .header-actions {
                display: none !important;
            }

            .anggaran-container {
                box-shadow: none;
                padding: 1rem;
            }

            .detail-card {
                break-inside: avoid;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .anggaran-container {
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

            .btn-secondary,
            .btn-edit-page {
                width: 100%;
                justify-content: center;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-card.main-card {
                grid-column: span 1;
            }

            .info-row {
                grid-template-columns: 1fr;
            }

            .detail-actions {
                flex-direction: column;
            }

            .btn-pengajuan,
            .btn-print,
            .btn-delete-page {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function deleteAnggaran(id) {
            Swal.fire({
                title: 'Hapus Anggaran',
                text: 'Apakah Anda yakin ingin menghapus anggaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
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
                                }).then(() => {
                                    window.location.href =
                                        '{{ route('admin.anggaran.index') }}';
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
                            let message = 'Terjadi kesalahan saat menghapus data.';
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
