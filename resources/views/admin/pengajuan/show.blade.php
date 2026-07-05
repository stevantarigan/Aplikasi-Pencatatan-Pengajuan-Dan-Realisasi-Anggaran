@extends('layouts.app')

@section('title', 'Detail Pengajuan Anggaran')
@section('icon', 'file-text')

@section('content')
    <div class="pengajuan-show-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Detail Pengajuan Anggaran</h2>
                <p class="page-subtitle">Informasi lengkap pengajuan anggaran</p>
            </div>
            <div class="header-actions">
                {{-- Tombol Edit - Hanya untuk status diajukan dan user berhak edit --}}
                @if ($pengajuan->status == 'diajukan' && (Auth::user()->role == 'admin' || Auth::id() == $pengajuan->diajukan_oleh))
                    <a href="{{ route('admin.pengajuan.edit', $pengajuan->id) }}" class="btn-edit-page">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </a>
                @endif

                {{-- Tombol Kembali untuk semua user --}}
                <a href="{{ route('admin.pengajuan.index') }}" class="btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="status-banner {{ $pengajuan->status }}">
            <div class="status-icon">
                <i class="bi bi-{{ $pengajuan->status_icon }}"></i>
            </div>
            <div class="status-info">
                <span class="status-label">Status Pengajuan</span>
                <span class="status-value">{{ ucfirst($pengajuan->status) }}</span>
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
                    <h3>Informasi Pengajuan</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Nomor Pengajuan</label>
                        <p class="nomor-pengajuan">{{ $pengajuan->nomor_pengajuan }}</p>
                    </div>

                    <div class="info-row">
                        <div class="info-group">
                            <label>Bulan</label>
                            <p class="value"><i class="bi bi-calendar-month"></i> {{ $pengajuan->nama_bulan }}</p>
                        </div>
                        <div class="info-group">
                            <label>Tahun</label>
                            <p class="value"><i class="bi bi-calendar3"></i> {{ $pengajuan->anggaran->tahun ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="info-group">
                        <label>Jumlah Diajukan</label>
                        <p class="value text-primary fw-bold">Rp
                            {{ number_format($pengajuan->jumlah_diajukan, 0, ',', '.') }}</p>
                    </div>

                    @if ($pengajuan->jumlah_realisasi)
                        <div class="info-group">
                            <label>Jumlah Realisasi</label>
                            <p class="value text-success fw-bold">Rp
                                {{ number_format($pengajuan->jumlah_realisasi, 0, ',', '.') }}</p>
                        </div>
                    @endif

                    <div class="info-group">
                        <label>Tanggal Pengajuan</label>
                        <p class="value"><i class="bi bi-clock"></i> {{ $pengajuan->tanggal_pengajuan->format('d F Y') }}
                        </p>
                    </div>

                    @if ($pengajuan->tanggal_persetujuan)
                        <div class="info-group">
                            <label>Tanggal Persetujuan</label>
                            <p class="value"><i class="bi bi-clock"></i>
                                {{ $pengajuan->tanggal_persetujuan->format('d F Y') }}</p>
                        </div>
                    @endif

                    @if ($pengajuan->tanggal_realisasi)
                        <div class="info-group">
                            <label>Tanggal Realisasi</label>
                            <p class="value"><i class="bi bi-clock"></i>
                                {{ $pengajuan->tanggal_realisasi->format('d F Y') }}</p>
                        </div>
                    @endif

                    @if ($pengajuan->keterangan)
                        <div class="info-group">
                            <label>Keterangan Pengajuan</label>
                            <div class="keterangan-box">
                                {{ $pengajuan->keterangan }}
                            </div>
                        </div>
                    @endif

                    @if ($pengajuan->keterangan_realisasi)
                        <div class="info-group">
                            <label>Keterangan Realisasi</label>
                            <div class="keterangan-box">
                                {{ $pengajuan->keterangan_realisasi }}
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
                    <!-- Nama Rencana (utama) -->
                    <div class="info-group">
                        <label>Nama Rencana</label>
                        <p class="value fw-bold">{{ $pengajuan->anggaran->rencanaAnggaran->nama_rencana ?? '-' }}</p>
                    </div>

                    <!-- Kode Rekening -->
                    <div class="info-group">
                        <label>Kode Rekening</label>
                        <p class="kode-rekening">{{ $pengajuan->anggaran->rencanaAnggaran->kodeRekening->kode ?? '-' }}</p>
                    </div>

                    <!-- Nama Rekening -->
                    <div class="info-group">
                        <label>Nama Rekening</label>
                        <p class="value">{{ $pengajuan->anggaran->rencanaAnggaran->kodeRekening->nama_rekening ?? '-' }}
                        </p>
                    </div>

                    <!-- Tahun Anggaran -->
                    <div class="info-group">
                        <label>Tahun Anggaran</label>
                        <p class="value">{{ $pengajuan->anggaran->tahun ?? '-' }}</p>
                    </div>

                    <!-- Total Anggaran -->
                    <div class="info-group">
                        <label>Total Anggaran</label>
                        <p class="value">Rp {{ number_format($pengajuan->anggaran->total_anggaran ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Sisa Anggaran -->
                    <div class="info-group">
                        <label>Sisa Anggaran</label>
                        @php
                            $sisa = $pengajuan->anggaran->sisa_anggaran ?? 0;
                            $sisaClass = $sisa > 0 ? 'text-success' : 'text-danger';
                        @endphp
                        <p class="value {{ $sisaClass }}">Rp {{ number_format($sisa, 0, ',', '.') }}</p>
                    </div>

                    <!-- Status Anggaran -->
                    <div class="info-group">
                        <label>Status Anggaran</label>
                        <p class="value">
                            <span class="badge-status {{ $pengajuan->anggaran->status ?? 'aktif' }}">
                                {{ ucfirst($pengajuan->anggaran->status ?? 'aktif') }}
                            </span>
                        </p>
                    </div>
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
                        <p class="value">{{ $pengajuan->pengaju->name ?? ($pengajuan->pengaju->nama ?? '-') }}</p>
                    </div>

                    <div class="info-group">
                        <label>Username</label>
                        <p class="value">{{ $pengajuan->pengaju->username ?? '-' }}</p>
                    </div>

                    <div class="info-group">
                        <label>Role</label>
                        <p class="value">{{ ucfirst($pengajuan->pengaju->role ?? 'User') }}</p>
                    </div>
                </div>
            </div>

            <!-- Penyetuju Info Card (if available) -->
            @if ($pengajuan->penyetuju)
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
                            <p class="value">{{ $pengajuan->penyetuju->name ?? ($pengajuan->penyetuju->nama ?? '-') }}</p>
                        </div>

                        <div class="info-group">
                            <label>Username</label>
                            <p class="value">{{ $pengajuan->penyetuju->username ?? '-' }}</p>
                        </div>

                        <div class="info-group">
                            <label>Role</label>
                            <p class="value">{{ ucfirst($pengajuan->penyetuju->role ?? 'Admin') }}</p>
                        </div>

                        <div class="info-group">
                            <label>Tanggal Persetujuan</label>
                            <p class="value">
                                {{ $pengajuan->tanggal_persetujuan ? $pengajuan->tanggal_persetujuan->format('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="detail-actions">
            {{-- Tombol Approve - Hanya Admin dan status diajukan --}}
            @if ($pengajuan->status == 'diajukan' && auth()->user() && auth()->user()->role == 'admin')
                <button class="btn-approve" onclick="approvePengajuan({{ $pengajuan->id }})">
                    <i class="bi bi-check-lg"></i>
                    Setujui
                </button>
                <button class="btn-reject" onclick="rejectPengajuan({{ $pengajuan->id }})">
                    <i class="bi bi-x-lg"></i>
                    Tolak
                </button>
            @endif

            {{-- Tombol Print untuk semua user --}}
            <button class="btn-print" onclick="window.print()">
                <i class="bi bi-printer"></i>
                Print
            </button>

            {{-- Tombol Hapus - Untuk status diajukan/ditolak dan user berhak hapus --}}
            @if (in_array($pengajuan->status, ['diajukan', 'ditolak']) &&
                    (Auth::user()->role == 'admin' || Auth::id() == $pengajuan->diajukan_oleh))
                <button class="btn-delete-page" onclick="deletePengajuan({{ $pengajuan->id }})">
                    <i class="bi bi-trash"></i>
                    Hapus
                </button>
            @endif
        </div>
    </div>

    <style>
        .pengajuan-show-container {
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

        /* Status Banner */
        .status-banner {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            color: white;
        }

        .status-banner.diajukan {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .status-banner.disetujui {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .status-banner.ditolak {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .status-banner.direalisasi {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        }

        .status-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .status-info {
            display: flex;
            flex-direction: column;
        }

        .status-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }

        .status-value {
            font-size: 2rem;
            font-weight: 700;
        }

        /* Badge Status */
        .badge-status {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-status.aktif {
            background: #d1fae5;
            color: #059669;
        }

        .badge-status.nonaktif {
            background: #f1f5f9;
            color: #64748b;
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

        .keterangan-box {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            color: #334155;
            line-height: 1.6;
            white-space: pre-line;
        }

        /* Text colors */
        .text-primary {
            color: #3b82f6 !important;
        }

        .text-success {
            color: #10b981 !important;
        }

        .text-danger {
            color: #ef4444 !important;
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

        .btn-approve {
            background: #10b981;
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

        .btn-approve:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-reject {
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

        .btn-reject:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
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
            background: #64748b;
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
            background: #475569;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(100, 116, 139, 0.3);
        }

        /* Print Styles */
        @media print {

            .btn-secondary,
            .btn-edit-page,
            .btn-approve,
            .btn-reject,
            .btn-print,
            .btn-delete-page,
            .header-actions {
                display: none !important;
            }

            .pengajuan-show-container {
                box-shadow: none;
                padding: 1rem;
            }

            .detail-card {
                break-inside: avoid;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pengajuan-show-container {
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

            .status-banner {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
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

            .btn-approve,
            .btn-reject,
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
        // Approve Pengajuan - Hanya Admin
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            let message = 'Gagal menyetujui pengajuan';
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

        // Reject Pengajuan - Hanya Admin
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            let message = 'Gagal menolak pengajuan';
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

        // Delete Pengajuan
        function deletePengajuan(id) {
            Swal.fire({
                title: 'Hapus Pengajuan',
                text: 'Apakah Anda yakin ingin menghapus pengajuan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
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
                                    window.location.href =
                                        '{{ route('admin.pengajuan.index') }}';
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
                            let message = 'Gagal menghapus pengajuan';
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
