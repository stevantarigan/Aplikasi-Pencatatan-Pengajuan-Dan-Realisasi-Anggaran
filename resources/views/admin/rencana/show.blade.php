@extends('layouts.app')

@section('title', 'Detail Rencana Anggaran')
@section('icon', 'journal-text')

@section('content')
    <div class="detail-rencana-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Detail Rencana Anggaran</h2>
                <p class="page-subtitle">Informasi lengkap rencana anggaran</p>
            </div>
            <div class="header-actions">
                {{-- Tombol Approve, Reject, Revisi - Hanya Admin dan hanya jika status diajukan --}}
                @if (in_array($rencana->status, ['diajukan']) && Auth::user()->role === 'admin')
                    <div class="action-buttons-header">
                        <button class="btn-approve" onclick="approveRencana({{ $rencana->id }})">
                            <i class="bi bi-check-lg"></i>
                            Setujui
                        </button>
                        <button class="btn-revision" onclick="requestRevision({{ $rencana->id }})">
                            <i class="bi bi-pencil-square"></i>
                            Minta Revisi
                        </button>
                        <button class="btn-reject" onclick="rejectRencana({{ $rencana->id }})">
                            <i class="bi bi-x-lg"></i>
                            Tolak
                        </button>
                    </div>
                @endif

                {{-- Tombol Kembali untuk semua user --}}
                <a href="{{ route('admin.rencana.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="status-banner status-{{ $rencana->status }}">
            <div class="status-icon">
                @if ($rencana->status == 'diajukan')
                    <i class="bi bi-hourglass-split"></i>
                @elseif($rencana->status == 'disetujui')
                    <i class="bi bi-check-circle-fill"></i>
                @elseif($rencana->status == 'ditolak')
                    <i class="bi bi-x-circle-fill"></i>
                @elseif($rencana->status == 'revisi')
                    <i class="bi bi-arrow-repeat"></i>
                @endif
            </div>
            <div class="status-content">
                <span class="status-label">Status Rencana Anggaran</span>
                <span class="status-value">
                    @if ($rencana->status == 'diajukan')
                        Diajukan - Menunggu Persetujuan
                    @elseif($rencana->status == 'disetujui')
                        Disetujui - Anggaran Telah Aktif
                    @elseif($rencana->status == 'ditolak')
                        Ditolak - Pengajuan Tidak Disetujui
                    @elseif($rencana->status == 'revisi')
                        Perlu Revisi - Silakan Lakukan Perbaikan
                    @endif
                </span>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="detail-grid">
            <!-- Left Column - Rencana Card -->
            <div class="rencana-card">
                <div class="rencana-header">
                    <div class="rencana-avatar">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <h3 class="rencana-name">{{ $rencana->nama_rencana }}</h3>
                    <p class="rencana-code">{{ $rencana->kodeRekening->kode ?? '-' }} -
                        {{ $rencana->kodeRekening->nama_rekening ?? '-' }}</p>

                    <!-- Status Badge -->
                    <div class="rencana-status">
                        @php
                            $statusClass =
                                [
                                    'diajukan' => 'warning',
                                    'disetujui' => 'success',
                                    'ditolak' => 'danger',
                                    'revisi' => 'info',
                                ][$rencana->status] ?? 'secondary';

                            $statusIcon =
                                [
                                    'diajukan' => 'hourglass-split',
                                    'disetujui' => 'check-circle-fill',
                                    'ditolak' => 'x-circle-fill',
                                    'revisi' => 'arrow-repeat',
                                ][$rencana->status] ?? 'question-circle';
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            <i class="bi bi-{{ $statusIcon }}"></i>
                            {{ ucfirst($rencana->status) }}
                        </span>
                    </div>
                </div>

                <div class="rencana-body">
                    <!-- Total Anggaran -->
                    <div class="total-card">
                        <div class="total-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="total-content">
                            <span class="total-label">Total Anggaran</span>
                            <span class="total-value">Rp {{ number_format($rencana->total_anggaran, 0, ',', '.') }}</span>
                            <span class="total-year">Tahun Anggaran {{ $rencana->tahun }}</span>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="timestamps">
                        <div class="timestamp-item">
                            <i class="bi bi-calendar-plus"></i>
                            <div>
                                <span class="timestamp-label">Dibuat pada</span>
                                <span class="timestamp-value">{{ $rencana->created_at->format('d M Y') }}</span>
                                <span class="timestamp-time">{{ $rencana->created_at->format('H:i:s') }}</span>
                            </div>
                        </div>
                        <div class="timestamp-item">
                            <i class="bi bi-calendar-check"></i>
                            <div>
                                <span class="timestamp-label">Terakhir diupdate</span>
                                <span class="timestamp-value">{{ $rencana->updated_at->format('d M Y') }}</span>
                                <span class="timestamp-time">{{ $rencana->updated_at->format('H:i:s') }}</span>
                            </div>
                        </div>
                        @if ($rencana->tanggal_persetujuan)
                            <div class="timestamp-item">
                                <i class="bi bi-check-circle"></i>
                                <div>
                                    <span class="timestamp-label">Tanggal Persetujuan</span>
                                    <span
                                        class="timestamp-value">{{ $rencana->tanggal_persetujuan->format('d M Y') }}</span>
                                    <span
                                        class="timestamp-time">{{ $rencana->tanggal_persetujuan->format('H:i:s') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Details Card -->
            <div class="details-card">
                <div class="details-header">
                    <h4><i class="bi bi-info-circle"></i> Informasi Lengkap</h4>
                </div>

                <div class="details-body">
                    <!-- Informasi Rencana -->
                    <div class="detail-section">
                        <h5 class="section-title">
                            <i class="bi bi-journal-text"></i>
                            Data Rencana
                        </h5>

                        <div class="detail-list">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-hash"></i>
                                    Kode Rekening
                                </div>
                                <div class="detail-value">
                                    <span class="kode-badge">{{ $rencana->kodeRekening->kode ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-file-text"></i>
                                    Nama Rekening
                                </div>
                                <div class="detail-value">
                                    {{ $rencana->kodeRekening->nama_rekening ?? '-' }}
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-calendar-year"></i>
                                    Tahun
                                </div>
                                <div class="detail-value">
                                    <span class="tahun-badge">{{ $rencana->tahun }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-cash"></i>
                                    Total Anggaran
                                </div>
                                <div class="detail-value total-value">
                                    Rp {{ number_format($rencana->total_anggaran, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-chat-text"></i>
                                    Keterangan
                                </div>
                                <div class="detail-value keterangan-value">
                                    {{ $rencana->keterangan ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pengajuan -->
                    <div class="detail-section">
                        <h5 class="section-title">
                            <i class="bi bi-person-badge"></i>
                            Informasi Pengajuan
                        </h5>

                        <div class="info-grid">
                            <div class="info-item">
                                <i class="bi bi-person"></i>
                                <div>
                                    <span class="info-label">Diajukan oleh</span>
                                    <span class="info-value">{{ $rencana->pengaju->nama ?? '-' }}</span>
                                    <span class="info-role">{{ $rencana->pengaju->role ?? '' }}</span>
                                    <span class="info-time">{{ $rencana->created_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>

                            @if ($rencana->disetujui_oleh)
                                <div class="info-item">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <div>
                                        <span class="info-label">Diproses oleh</span>
                                        <span class="info-value">{{ $rencana->penyetuju->nama ?? '-' }}</span>
                                        <span class="info-role">{{ $rencana->penyetuju->role ?? '' }}</span>
                                        <span
                                            class="info-time">{{ $rencana->tanggal_persetujuan ? $rencana->tanggal_persetujuan->format('d M Y H:i') : '-' }}</span>
                                    </div>
                                </div>
                            @endif
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
                                <span class="metadata-label">ID Rencana</span>
                                <span class="metadata-value">#{{ $rencana->id }}</span>
                            </div>
                            <div class="metadata-item">
                                <span class="metadata-label">Dibuat</span>
                                <span class="metadata-value">{{ $rencana->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="metadata-item">
                                <span class="metadata-label">Diupdate</span>
                                <span class="metadata-value">{{ $rencana->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Anggaran Info (Jika sudah disetujui) -->
                    @if ($rencana->status == 'disetujui' && $rencana->anggaran)
                        <div class="detail-section">
                            <h5 class="section-title">
                                <i class="bi bi-pie-chart"></i>
                                Informasi Anggaran
                            </h5>

                            <div class="anggaran-info">
                                <div class="anggaran-item">
                                    <span class="anggaran-label">Total Anggaran</span>
                                    <span class="anggaran-value">Rp
                                        {{ number_format($rencana->anggaran->total_anggaran, 0, ',', '.') }}</span>
                                </div>
                                <div class="anggaran-item">
                                    <span class="anggaran-label">Sisa Anggaran</span>
                                    <span class="anggaran-value">Rp
                                        {{ number_format($rencana->anggaran->sisa_anggaran, 0, ',', '.') }}</span>
                                </div>
                                <div class="anggaran-item">
                                    <span class="anggaran-label">Status Anggaran</span>
                                    <span class="anggaran-value">
                                        <span class="badge bg-success">Aktif</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons (Mobile) -->
        @if (in_array($rencana->status, ['diajukan']) && Auth::user()->role === 'admin')
            <div class="mobile-actions">
                <button class="btn-approve" onclick="approveRencana({{ $rencana->id }})">
                    <i class="bi bi-check-lg"></i>
                    Setujui
                </button>
                <button class="btn-revision" onclick="requestRevision({{ $rencana->id }})">
                    <i class="bi bi-pencil-square"></i>
                    Revisi
                </button>
                <button class="btn-reject" onclick="rejectRencana({{ $rencana->id }})">
                    <i class="bi bi-x-lg"></i>
                    Tolak
                </button>
                <a href="{{ route('admin.rencana.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        @endif
    </div>

    <style>
        /* CSS tetap sama seperti sebelumnya */
        .detail-rencana-container {
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
            margin-bottom: 1.5rem;
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
            align-items: center;
        }

        .action-buttons-header {
            display: flex;
            gap: 0.5rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #f1f5f9;
            color: #475569;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #1e293b;
            transform: translateX(-5px);
        }

        .btn-approve,
        .btn-revision,
        .btn-reject {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-approve {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-revision {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
        }

        .btn-revision:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
        }

        .btn-reject {
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: white;
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }

        /* Status Banner */
        .status-banner {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
        }

        .status-banner.status-diajukan {
            background: #fffbeb;
            border: 1px solid #fde68a;
        }

        .status-banner.status-disetujui {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
        }

        .status-banner.status-ditolak {
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .status-banner.status-revisi {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }

        .status-icon i {
            font-size: 2.5rem;
        }

        .status-banner.status-diajukan .status-icon i {
            color: #f59e0b;
        }

        .status-banner.status-disetujui .status-icon i {
            color: #10b981;
        }

        .status-banner.status-ditolak .status-icon i {
            color: #ef4444;
        }

        .status-banner.status-revisi .status-icon i {
            color: #3b82f6;
        }

        .status-content {
            flex: 1;
        }

        .status-label {
            display: block;
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .status-value {
            display: block;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .status-banner.status-diajukan .status-value {
            color: #92400e;
        }

        .status-banner.status-disetujui .status-value {
            color: #065f46;
        }

        .status-banner.status-ditolak .status-value {
            color: #b91c1c;
        }

        .status-banner.status-revisi .status-value {
            color: #1e40af;
        }

        /* Detail Grid */
        .detail-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 1.5rem;
        }

        /* Rencana Card */
        .rencana-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            overflow: hidden;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .rencana-header {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
        }

        .rencana-avatar {
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

        .rencana-avatar i {
            font-size: 3.5rem;
        }

        .rencana-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .rencana-code {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-badge.warning {
            background: #f59e0b;
            color: white;
        }

        .status-badge.success {
            background: #10b981;
            color: white;
        }

        .status-badge.danger {
            background: #ef4444;
            color: white;
        }

        .status-badge.info {
            background: #3b82f6;
            color: white;
        }

        .rencana-body {
            padding: 1.5rem 2rem 2rem;
        }

        .total-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .total-icon i {
            font-size: 2.5rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .total-content {
            flex: 1;
        }

        .total-label {
            display: block;
            font-size: 0.8rem;
            opacity: 0.8;
            margin-bottom: 0.25rem;
        }

        .total-value {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .total-year {
            display: block;
            font-size: 0.8rem;
            opacity: 0.7;
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
            color: #667eea;
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
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-title i {
            color: #667eea;
        }

        .detail-list {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
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
            flex-shrink: 0;
        }

        .detail-label i {
            color: #667eea;
            width: 18px;
        }

        .detail-value {
            flex: 1;
            font-weight: 500;
            color: #1e293b;
        }

        .kode-badge,
        .tahun-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: monospace;
        }

        .kode-badge {
            background: #e0e7ff;
            color: #667eea;
        }

        .tahun-badge {
            background: #dbeafe;
            color: #3b82f6;
        }

        .total-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #10b981;
        }

        .keterangan-value {
            white-space: pre-line;
            line-height: 1.6;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .info-item i {
            font-size: 1.5rem;
            color: #667eea;
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
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .info-role {
            display: block;
            font-size: 0.8rem;
            color: #667eea;
            margin-bottom: 0.25rem;
        }

        .info-time {
            display: block;
            font-size: 0.75rem;
            color: #94a3b8;
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

        /* Anggaran Info */
        .anggaran-info {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
        }

        .anggaran-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .anggaran-item:last-child {
            border-bottom: none;
        }

        .anggaran-label {
            font-size: 0.9rem;
            color: #64748b;
        }

        .anggaran-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Mobile Actions */
        .mobile-actions {
            display: none;
            margin-top: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .mobile-actions .btn-approve,
        .mobile-actions .btn-revision,
        .mobile-actions .btn-reject,
        .mobile-actions .btn-back {
            flex: 1;
            justify-content: center;
            text-decoration: none;
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
            .detail-rencana-container {
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

            .action-buttons-header {
                width: 100%;
                flex-direction: column;
            }

            .btn-back,
            .btn-approve,
            .btn-revision,
            .btn-reject {
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
        }

        @media (max-width: 576px) {
            .rencana-header {
                padding: 1.5rem;
            }

            .rencana-body {
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
        function approveRencana(id) {
            Swal.fire({
                title: 'Setujui Rencana',
                text: 'Apakah Anda yakin ingin menyetujui rencana anggaran ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/rencana/' + id + '/approve',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
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

        function rejectRencana(id) {
            Swal.fire({
                title: 'Tolak Rencana',
                input: 'textarea',
                inputLabel: 'Alasan Penolakan',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                inputAttributes: {
                    'required': true
                },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Alasan penolakan harus diisi');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/rencana/' + id + '/reject',
                        type: 'POST',
                        data: {
                            alasan_penolakan: result.value,
                            _token: '{{ csrf_token() }}'
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

        function requestRevision(id) {
            Swal.fire({
                title: 'Minta Revisi',
                input: 'textarea',
                inputLabel: 'Catatan Revisi',
                inputPlaceholder: 'Masukkan catatan revisi...',
                inputAttributes: {
                    'required': true
                },
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Minta Revisi',
                cancelButtonText: 'Batal',
                preConfirm: (notes) => {
                    if (!notes) {
                        Swal.showValidationMessage('Catatan revisi harus diisi');
                    }
                    return notes;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/rencana/' + id + '/revision',
                        type: 'POST',
                        data: {
                            catatan_revisi: result.value,
                            _token: '{{ csrf_token() }}'
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
