@extends('layouts.app')

@section('title', 'Detail Realisasi Anggaran')
@section('icon', 'eye')

@section('content')
    <div class="realisasi-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Detail Realisasi Anggaran</h2>
                <p class="page-subtitle">Informasi lengkap realisasi anggaran</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.realisasi.edit', $realisasi->id) }}" class="btn-edit-page">
                    <i class="bi bi-pencil"></i>
                    Edit
                </a>
                <a href="{{ route('admin.realisasi.index') }}" class="btn-secondary">
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
                        <i class="bi bi-file-text"></i>
                    </div>
                    <h3>Informasi Realisasi</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Nomor Pengajuan</label>
                        <p class="nomor-pengajuan">{{ $realisasi->nomor_pengajuan }}</p>
                    </div>

                    <div class="info-group">
                        <label>Status</label>
                        <span class="status-badge {{ $realisasi->status_badge_class }}">
                            <i class="bi bi-{{ $realisasi->status_icon }}"></i>
                            {{ ucfirst($realisasi->status) }}
                        </span>
                    </div>

                    <div class="info-row">
                        <div class="info-group">
                            <label>Jumlah Diajukan</label>
                            <p class="value">Rp {{ number_format($realisasi->jumlah_diajukan, 0, ',', '.') }}</p>
                        </div>
                        <div class="info-group">
                            <label>Jumlah Realisasi</label>
                            <p class="value text-success fw-bold">Rp
                                {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="info-group">
                        <label>Sisa Anggaran</label>
                        @php
                            $sisa = $realisasi->sisa_anggaran;
                            $sisaClass = $sisa > 0 ? 'text-warning' : 'text-muted';
                        @endphp
                        <p class="value {{ $sisaClass }}">Rp {{ number_format($sisa, 0, ',', '.') }}</p>
                    </div>

                    <div class="info-row">
                        <div class="info-group">
                            <label>Bulan</label>
                            <p class="value"><i class="bi bi-calendar-month"></i> {{ $realisasi->nama_bulan }}</p>
                        </div>
                        <div class="info-group">
                            <label>Tahun</label>
                            <p class="value"><i class="bi bi-calendar3"></i> {{ $realisasi->anggaran->tahun ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="info-group">
                        <label>Tanggal Pengajuan</label>
                        <p class="value">
                            <i class="bi bi-clock"></i>
                            {{ $realisasi->tanggal_pengajuan ? \Carbon\Carbon::parse($realisasi->tanggal_pengajuan)->format('d F Y') : '-' }}
                        </p>
                    </div>

                    <div class="info-group">
                        <label>Tanggal Realisasi</label>
                        <p class="value">
                            <i class="bi bi-clock"></i>
                            {{ $realisasi->tanggal_realisasi ? \Carbon\Carbon::parse($realisasi->tanggal_realisasi)->format('d F Y') : '-' }}
                        </p>
                    </div>

                    @if ($realisasi->keterangan)
                        <div class="info-group">
                            <label>Keterangan Pengajuan</label>
                            <div class="keterangan-box">
                                {{ $realisasi->keterangan }}
                            </div>
                        </div>
                    @endif

                    @if ($realisasi->keterangan_realisasi)
                        <div class="info-group">
                            <label>Keterangan Realisasi</label>
                            <div class="keterangan-box">
                                {{ $realisasi->keterangan_realisasi }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Anggaran Info Card -->
            <div class="detail-card">
                <div class="card-header">
                    <div class="header-icon bg-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h3>Informasi Anggaran</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Tahun Anggaran</label>
                        <p class="value">{{ $realisasi->anggaran->tahun ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Total Anggaran</label>
                        <p class="value">Rp {{ number_format($realisasi->anggaran->total_anggaran ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="info-group">
                        <label>Total Realisasi</label>
                        <p class="value text-success">Rp
                            {{ number_format($realisasi->anggaran->total_realisasi ?? 0, 0, ',', '.') }}</p>
                    </div>

                    <div class="info-group">
                        <label>Sisa Anggaran</label>
                        @php
                            $sisaAnggaran =
                                ($realisasi->anggaran->total_anggaran ?? 0) -
                                ($realisasi->anggaran->total_realisasi ?? 0);
                        @endphp
                        <p class="value {{ $sisaAnggaran > 0 ? 'text-warning' : 'text-muted' }}">
                            Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kegiatan Info Card -->
            <div class="detail-card">
                <div class="card-header">
                    <div class="header-icon bg-info">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <h3>Informasi Kegiatan</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Nama Rencana</label>
                        <p class="value fw-bold">{{ $realisasi->anggaran->rencanaAnggaran->nama_rencana ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Kegiatan</label>
                        <p class="value">{{ $realisasi->anggaran->rencanaAnggaran->nama_kegiatan ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Kode Rekening</label>
                        <p class="kode-rekening">{{ $realisasi->anggaran->rencanaAnggaran->kodeRekening->kode ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Nama Rekening</label>
                        <p class="value">{{ $realisasi->anggaran->rencanaAnggaran->kodeRekening->nama_rekening ?? '-' }}
                        </p>
                    </div>

                    @if ($realisasi->anggaran->rencanaAnggaran && $realisasi->anggaran->rencanaAnggaran->deskripsi)
                        <div class="info-group">
                            <label>Deskripsi</label>
                            <p class="value">{{ $realisasi->anggaran->rencanaAnggaran->deskripsi }}</p>
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
                    <h3>Informasi Pengaju</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Nama Pengaju</label>
                        <p class="value">{{ $realisasi->pengaju->name ?? ($realisasi->pengaju->nama ?? '-') }}</p>
                    </div>

                    <div class="info-group">
                        <label>Username</label>
                        <p class="value">{{ $realisasi->pengaju->username ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Role</label>
                        <p class="value">{{ ucfirst($realisasi->pengaju->role ?? '-') }}</p>
                    </div>
                </div>
            </div>

            <!-- Penyetuju Info Card (if available) -->
            @if ($realisasi->penyetuju)
                <div class="detail-card">
                    <div class="card-header">
                        <div class="header-icon bg-primary">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <h3>Informasi Penyetuju</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-group">
                            <label>Nama Penyetuju</label>
                            <p class="value">{{ $realisasi->penyetuju->name ?? ($realisasi->penyetuju->nama ?? '-') }}</p>
                        </div>

                        <div class="info-group">
                            <label>Role</label>
                            <p class="value">{{ ucfirst($realisasi->penyetuju->role ?? '-') }}</p>
                        </div>

                        <div class="info-group">
                            <label>Tanggal Persetujuan</label>
                            <p class="value">
                                {{ $realisasi->tanggal_persetujuan ? \Carbon\Carbon::parse($realisasi->tanggal_persetujuan)->format('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="detail-actions">
            <button class="btn-print" onclick="window.print()">
                <i class="bi bi-printer"></i>
                Print
            </button>
            <button class="btn-delete-page" onclick="deleteRealisasi({{ $realisasi->id }})">
                <i class="bi bi-trash"></i>
                Hapus
            </button>
        </div>
    </div>

    <style>
        /* Main Container */
        .realisasi-container {
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

        .btn-edit-page:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
        }

        /* Detail Grid */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Detail Card */
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

        .header-icon.bg-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .header-icon.bg-info {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
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

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
            width: fit-content;
        }

        .bg-info {
            background: #0ea5e9;
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

        .keterangan-box {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            color: #334155;
            line-height: 1.6;
        }

        /* Text colors */
        .text-success {
            color: #10b981 !important;
        }

        .text-warning {
            color: #f59e0b !important;
        }

        .fw-bold {
            font-weight: 600;
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

        .btn-delete-page:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }

        /* Print Styles */
        @media print {

            .btn-secondary,
            .btn-edit-page,
            .btn-print,
            .btn-delete-page,
            .header-actions {
                display: none !important;
            }

            .realisasi-container {
                box-shadow: none;
                padding: 1rem;
            }

            .detail-card {
                break-inside: avoid;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .realisasi-container {
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

            .btn-print,
            .btn-delete-page {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
                    fetch('{{ url('admin/realisasi') }}/' + id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '{{ route('admin.realisasi.index') }}';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Terjadi kesalahan saat menghapus data'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus data'
                            });
                        });
                }
            });
        }
    </script>
@endsection
