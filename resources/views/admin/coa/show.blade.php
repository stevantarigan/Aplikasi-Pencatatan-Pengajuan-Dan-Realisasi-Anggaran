@extends('layouts.app')

@section('title', 'Detail COA')

@section('icon', 'file-text')

@section('content')
    <div class="detail-coa-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Detail COA</h2>
                <p class="page-subtitle">Informasi lengkap Chart of Account (Kode Rekening)</p>
            </div>
            <div class="header-actions">
                {{-- Tombol Edit hanya untuk Admin dan Kepala Gizi --}}
                @if (in_array(auth()->user()->role, ['admin', 'kepala_gizi']))
                    <a href="{{ route('admin.coa.edit', $coa->id) }}" class="btn-edit">
                        <i class="bi bi-pencil-square"></i>
                        Edit COA
                    </a>
                @endif

                {{-- Tombol Kembali untuk semua role --}}
                <a href="{{ route('admin.daftarCOA') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="detail-grid">
            <!-- Left Column - COA Card -->
            <div class="coa-card">
                <div class="coa-header">
                    <div class="coa-avatar">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h3 class="coa-code">{{ $coa->kode }}</h3>
                    <p class="coa-name">{{ $coa->nama_rekening }}</p>

                    <!-- Type Badge -->
                    <div class="coa-type">
                        @if ($coa->tipe == 'utama')
                            <span class="type-badge utama">
                                <i class="bi bi-star-fill"></i>
                                Utama
                            </span>
                        @else
                            <span class="type-badge sub">
                                <i class="bi bi-diagram-3"></i>
                                Sub Rekening
                            </span>
                        @endif
                    </div>
                </div>

                <div class="coa-body">
                    <!-- Info Card -->
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Informasi COA</span>
                            <span class="info-value">Kode rekening ini digunakan untuk pencatatan transaksi anggaran</span>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="timestamps">
                        <div class="timestamp-item">
                            <i class="bi bi-calendar-plus"></i>
                            <div>
                                <span class="timestamp-label">Dibuat pada</span>
                                <span class="timestamp-value">{{ $coa->created_at->format('d M Y') }}</span>
                                <span class="timestamp-time">{{ $coa->created_at->format('H:i:s') }}</span>
                            </div>
                        </div>
                        <div class="timestamp-item">
                            <i class="bi bi-calendar-check"></i>
                            <div>
                                <span class="timestamp-label">Terakhir diupdate</span>
                                <span class="timestamp-value">{{ $coa->updated_at->format('d M Y') }}</span>
                                <span class="timestamp-time">{{ $coa->updated_at->format('H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Details Card -->
            <div class="details-card">
                <div class="details-header">
                    <h4><i class="bi bi-info-circle"></i> Informasi Lengkap COA</h4>
                </div>

                <div class="details-body">
                    <!-- Informasi COA -->
                    <div class="detail-section">
                        <h5 class="section-title">
                            <i class="bi bi-file-earmark-text"></i>
                            Data Rekening
                        </h5>

                        <div class="detail-list">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-hash"></i>
                                    Kode COA
                                </div>
                                <div class="detail-value">
                                    <span class="kode-badge">{{ $coa->kode }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-type"></i>
                                    Tipe Rekening
                                </div>
                                <div class="detail-value">
                                    @if ($coa->tipe == 'utama')
                                        <span class="type-indicator utama">
                                            <i class="bi bi-star-fill"></i>
                                            Utama
                                        </span>
                                    @else
                                        <span class="type-indicator sub">
                                            <i class="bi bi-diagram-3"></i>
                                            Sub Rekening
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-file-text"></i>
                                    Nama Rekening
                                </div>
                                <div class="detail-value nama-rekening">
                                    {{ $coa->nama_rekening }}
                                </div>
                            </div>

                            {{-- Keterangan (jika ada) --}}
                            @if ($coa->keterangan)
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-chat-text"></i>
                                        Keterangan
                                    </div>
                                    <div class="detail-value">
                                        {{ $coa->keterangan }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Penggunaan -->
                    <div class="detail-section">
                        <h5 class="section-title">
                            <i class="bi bi-question-circle"></i>
                            Informasi Penggunaan
                        </h5>

                        <div class="info-grid">
                            <div class="info-item">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <div>
                                    <span class="info-label">Status</span>
                                    <span class="info-value">Aktif</span>
                                </div>
                            </div>

                            <div class="info-item">
                                <i class="bi bi-file-earmark-text"></i>
                                <div>
                                    <span class="info-label">Digunakan di</span>
                                    <span class="info-value">Anggaran & Realisasi</span>
                                </div>
                            </div>

                            {{-- Total rencana anggaran - dengan pengecekan aman --}}
                            <div class="info-item">
                                <i class="bi bi-journal-plus"></i>
                                <div>
                                    <span class="info-label">Total Rencana</span>
                                    <span class="info-value">
                                        @if (isset($coa->rencanaAnggaran) && $coa->rencanaAnggaran)
                                            {{ $coa->rencanaAnggaran->count() }} rencana
                                        @else
                                            0 rencana
                                        @endif
                                    </span>
                                </div>
                            </div>

                            {{-- Total anggaran - dengan pengecekan aman --}}
                            <div class="info-item">
                                <i class="bi bi-cash-stack"></i>
                                <div>
                                    <span class="info-label">Total Anggaran</span>
                                    <span class="info-value">
                                        @if (isset($coa->anggaran) && $coa->anggaran)
                                            {{ $coa->anggaran->count() }} anggaran
                                        @else
                                            0 anggaran
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="detail-section">
                        <h5 class="section-title">
                            <i class="bi bi-clock-history"></i>
                            Metadata
                        </h5>

                        <div class="metadata-grid">
                            <div class="metadata-item">
                                <span class="metadata-label">ID COA</span>
                                <span class="metadata-value">#{{ $coa->id }}</span>
                            </div>
                            <div class="metadata-item">
                                <span class="metadata-label">Dibuat</span>
                                <span class="metadata-value">{{ $coa->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="metadata-item">
                                <span class="metadata-label">Diupdate</span>
                                <span class="metadata-value">{{ $coa->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons (Mobile) - Hanya untuk Admin dan Kepala Gizi -->
        @if (in_array(auth()->user()->role, ['admin', 'kepala_gizi']))
            <div class="mobile-actions">
                <a href="{{ route('admin.coa.edit', $coa->id) }}" class="btn-edit">
                    <i class="bi bi-pencil-square"></i>
                    Edit
                </a>
                <a href="{{ route('admin.daftarCOA') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        @else
            {{-- Untuk Staff, hanya tombol kembali di mobile --}}
            <div class="mobile-actions single">
                <a href="{{ route('admin.daftarCOA') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        @endif
    </div>

    <style>
        /* (CSS tetap sama seperti sebelumnya) */
        .detail-coa-container {
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

        .btn-back,
        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-back {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #1e293b;
            transform: translateX(-5px);
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
            color: white;
        }

        /* Detail Grid */
        .detail-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 1.5rem;
        }

        /* COA Card */
        .coa-card {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 20px;
            overflow: hidden;
            color: white;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        .coa-header {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
        }

        .coa-avatar {
            width: 100px;
            height: 100px;
            margin: 0 auto 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255, 255, 255, 0.5);
        }

        .coa-avatar i {
            font-size: 3.5rem;
        }

        .coa-code {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            font-family: monospace;
        }

        .coa-name {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .type-badge.utama {
            background: #10b981;
            color: white;
        }

        .type-badge.sub {
            background: #f59e0b;
            color: white;
        }

        .coa-body {
            padding: 1.5rem 2rem 2rem;
        }

        .info-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .info-icon i {
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            display: block;
            font-size: 0.8rem;
            opacity: 0.8;
            margin-bottom: 0.25rem;
        }

        .info-value {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            line-height: 1.4;
        }

        .timestamps {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1rem;
        }

        .timestamp-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .timestamp-item:last-child {
            border-bottom: none;
        }

        .timestamp-item i {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .timestamp-label {
            display: block;
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .timestamp-value {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .timestamp-time {
            display: block;
            font-size: 0.8rem;
            opacity: 0.7;
        }

        /* Details Card */
        .details-card {
            background: #f8fafc;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .details-header {
            padding: 1.5rem 2rem;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }

        .details-header h4 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .details-header h4 i {
            color: #3b82f6;
        }

        .details-body {
            padding: 2rem;
        }

        .detail-section {
            margin-bottom: 2rem;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-title i {
            color: #3b82f6;
        }

        .detail-list {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .detail-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            width: 120px;
            font-size: 0.9rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-label i {
            color: #3b82f6;
            width: 18px;
        }

        .detail-value {
            flex: 1;
            font-weight: 500;
            color: #1e293b;
        }

        .kode-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            background: #e0e7ff;
            color: #3b82f6;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: monospace;
        }

        .type-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .type-indicator.utama {
            background: #d1fae5;
            color: #065f46;
        }

        .type-indicator.sub {
            background: #fed7aa;
            color: #92400e;
        }

        .nama-rekening {
            font-size: 1rem;
            font-weight: 500;
            color: #1e293b;
            line-height: 1.5;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .info-item i {
            font-size: 1.2rem;
            color: #3b82f6;
        }

        .info-item i.text-success {
            color: #10b981;
        }

        .info-label {
            display: block;
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .info-value {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: #1e293b;
        }

        /* Metadata Grid */
        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .metadata-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        .metadata-label {
            display: block;
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .metadata-value {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Mobile Actions */
        .mobile-actions {
            display: none;
            margin-top: 1.5rem;
            gap: 1rem;
        }

        .mobile-actions.single {
            display: none;
        }

        .mobile-actions .btn-edit {
            flex: 1;
            justify-content: center;
        }

        .mobile-actions .btn-back {
            flex: 1;
            justify-content: center;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .info-grid,
            .metadata-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .detail-coa-container {
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

            .btn-back,
            .btn-edit {
                width: 100%;
                justify-content: center;
            }

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .detail-label {
                width: auto;
            }

            .info-grid,
            .metadata-grid {
                grid-template-columns: 1fr;
            }

            .mobile-actions {
                display: flex;
            }

            .mobile-actions.single {
                display: flex;
            }
        }

        @media (max-width: 576px) {
            .coa-header {
                padding: 1.5rem;
            }

            .coa-body {
                padding: 1.5rem;
            }

            .details-body {
                padding: 1.5rem;
            }

            .mobile-actions {
                flex-direction: column;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Show success message if any
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
        });
    </script>
@endsection
