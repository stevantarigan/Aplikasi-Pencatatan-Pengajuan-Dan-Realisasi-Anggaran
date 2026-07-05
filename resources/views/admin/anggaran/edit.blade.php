@extends('layouts.app')

@section('title', 'Edit Anggaran')
@section('icon', 'pencil-square')

@section('content')
    <div class="anggaran-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Edit Anggaran</h2>
                <p class="page-subtitle">Perbaharui data anggaran</p>
            </div>
            <a href="{{ route('admin.anggaran.index') }}" class="btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="header-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div class="header-title">
                    <h3>Form Edit Anggaran</h3>
                    <p>Edit data anggaran untuk rencana: {{ $anggaran->rencanaAnggaran->nama_rencana ?? '-' }}</p>
                </div>
            </div>

            <div class="form-card-body">
                @if ($errors->any())
                    <div class="alert-custom alert-danger">
                        <div class="alert-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Terjadi kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert-custom alert-danger">
                        <div class="alert-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Error!</strong>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert-custom alert-success">
                        <div class="alert-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Berhasil!</strong>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if ($hasTransactions)
                    <div class="alert-custom alert-info">
                        <div class="alert-icon">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Informasi!</strong>
                            <p class="mb-0">Anggaran ini sudah memiliki transaksi pengajuan. Perubahan total anggaran akan
                                mempengaruhi sisa anggaran.</p>
                        </div>
                    </div>
                @endif

                <!-- ALERT KHUSUS UNTUK ANGGARAN DITUTUP -->
                @if ($anggaran->status == 'ditutup')
                    <div class="alert-custom alert-warning">
                        <div class="alert-icon">
                            <i class="bi bi-exclamation-diamond-fill"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Perhatian! Anggaran sedang Ditutup</strong>
                            <p class="mb-0 mt-1">
                                Anggaran ini sedang dalam status ditutup. Anda tetap dapat mengedit data anggaran ini.
                                Jika ingin mengaktifkan kembali, ubah status menjadi "Aktif".
                            </p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.anggaran.update', $anggaran->id) }}" method="POST" id="formAnggaran">
                    @csrf
                    @method('PUT')

                    <!-- Info Rencana Card (Readonly) -->
                    <div class="info-rencana-card">
                        <div class="info-rencana-header">
                            <i class="bi bi-info-circle"></i>
                            <h4>Informasi Rencana Anggaran</h4>
                        </div>
                        <div class="info-rencana-content">
                            <div class="info-row">
                                <span class="info-label">Nama Rencana:</span>
                                <span class="info-value">{{ $anggaran->rencanaAnggaran->nama_rencana ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Kode Rekening:</span>
                                <span class="info-value">{{ $anggaran->rencanaAnggaran->kodeRekening->kode ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nama Rekening:</span>
                                <span
                                    class="info-value">{{ $anggaran->rencanaAnggaran->kodeRekening->nama_rekening ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Tahun Rencana:</span>
                                <span class="info-value">{{ $anggaran->rencanaAnggaran->tahun ?? '-' }}</span>
                            </div>
                            <div class="info-row highlight">
                                <span class="info-label">Total Rencana:</span>
                                <span class="info-value">Rp
                                    {{ number_format($anggaran->rencanaAnggaran->total_anggaran ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <!-- Tahun -->
                        <div class="form-group">
                            <label for="tahun" class="form-label">
                                Tahun Anggaran <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-calendar"></i>
                                </span>
                                <input type="number" name="tahun" id="tahun"
                                    class="form-control @error('tahun') is-invalid @enderror"
                                    value="{{ old('tahun', $anggaran->tahun) }}" min="2000" max="2099" required>
                            </div>
                            @error('tahun')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Total Anggaran -->
                        <div class="form-group">
                            <label for="total_anggaran" class="form-label">
                                Total Anggaran <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-cash-stack"></i>
                                </span>
                                <input type="text" name="total_anggaran" id="total_anggaran"
                                    class="form-control @error('total_anggaran') is-invalid @enderror"
                                    value="{{ old('total_anggaran', number_format($anggaran->total_anggaran, 0, ',', '.')) }}"
                                    placeholder="Masukkan total anggaran" required>
                            </div>
                            @error('total_anggaran')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <small class="hint-text">
                                <i class="bi bi-info-circle"></i>
                                Format: 1.000.000 (tanpa Rp)
                            </small>
                            @if ($hasTransactions)
                                <small class="hint-text text-info mt-1">
                                    <i class="bi bi-info-circle"></i>
                                    Total realisasi saat ini: Rp
                                    {{ number_format($anggaran->total_realisasi, 0, ',', '.') }}
                                </small>
                            @endif
                        </div>

                        <!-- Sisa Anggaran (readonly, hanya info) -->
                        <div class="form-group">
                            <label for="sisa_anggaran" class="form-label">
                                Sisa Anggaran Saat Ini
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-piggy-bank"></i>
                                </span>
                                <input type="text" id="sisa_anggaran" class="form-control bg-light"
                                    value="Rp {{ number_format($anggaran->sisa_anggaran, 0, ',', '.') }}" readonly>
                            </div>
                            @php
                                $persentase =
                                    $anggaran->total_anggaran > 0
                                        ? round(
                                            (($anggaran->total_anggaran - $anggaran->sisa_anggaran) /
                                                $anggaran->total_anggaran) *
                                                100,
                                            1,
                                        )
                                        : 0;
                                $totalRealisasi = $anggaran->total_realisasi;
                            @endphp
                            <small class="hint-text">
                                <i class="bi bi-info-circle"></i>
                                Terpakai {{ $persentase }}% (Rp {{ number_format($totalRealisasi, 0, ',', '.') }}) dari
                                total anggaran.
                                Sisa anggaran akan otomatis terupdate saat ada realisasi.
                            </small>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif"
                                    {{ old('status', $anggaran->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="ditutup"
                                    {{ old('status', $anggaran->status) == 'ditutup' ? 'selected' : '' }}>Ditutup
                                </option>
                            </select>
                            @error('status')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <small class="hint-text">
                                <i class="bi bi-info-circle"></i>
                                Status "Aktif" berarti anggaran dapat digunakan untuk pengajuan.
                                @if ($anggaran->status == 'ditutup')
                                    <span class="text-warning d-block mt-1">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        Saat ini anggaran ditutup. Ubah ke Aktif jika ingin digunakan kembali.
                                    </span>
                                @endif
                            </small>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" id="submitBtn" class="btn-update">
                            <i class="bi bi-check-lg"></i>
                            Update Anggaran
                        </button>
                        <a href="{{ route('admin.anggaran.index') }}" class="btn-cancel">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-exclamation-diamond"></i>
            </div>
            <div class="info-content">
                <h5>Informasi Edit Data</h5>
                <ul>
                    <li><i class="bi bi-check-circle text-warning"></i> Pastikan data yang diubah sudah benar</li>
                    <li><i class="bi bi-check-circle text-warning"></i> Admin dapat mengubah total anggaran meskipun sudah
                        ada pengajuan</li>
                    <li><i class="bi bi-check-circle text-warning"></i> Total anggaran tidak boleh kurang dari total
                        realisasi yang sudah ada</li>
                    <li><i class="bi bi-check-circle text-warning"></i> Sisa anggaran akan otomatis terupdate saat ada
                        realisasi</li>
                    <li><i class="bi bi-check-circle text-warning"></i> Status "Ditutup" dapat dipilih kapan saja, meskipun
                        masih ada sisa</li>
                    <li><i class="bi bi-check-circle text-success"></i> <strong>Anggaran ditutup tetap bisa diedit</strong>
                        dan diaktifkan kembali</li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        .anggaran-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 32px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.03);
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
            background: linear-gradient(135deg, #1e293b, #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.95rem;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateX(-3px);
            color: #1e293b;
        }

        .btn-secondary i {
            font-size: 1.1rem;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.6);
            backdrop-filter: blur(10px);
        }

        .form-card-header {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .header-title h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .header-title p {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .form-card-body {
            padding: 2rem;
        }

        /* Alert Custom */
        .alert-custom {
            padding: 1rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            position: relative;
            border-left: 4px solid;
        }

        .alert-danger {
            background: #fef2f2;
            border-left-color: #dc2626;
        }

        .alert-info {
            background: #e0f2fe;
            border-left-color: #0ea5e9;
        }

        .alert-warning {
            background: #fffbeb;
            border-left-color: #f59e0b;
        }

        .alert-success {
            background: #f0fdf4;
            border-left-color: #10b981;
        }

        .alert-icon {
            font-size: 1.5rem;
        }

        .alert-danger .alert-icon {
            color: #dc2626;
        }

        .alert-info .alert-icon {
            color: #0ea5e9;
        }

        .alert-warning .alert-icon {
            color: #f59e0b;
        }

        .alert-success .alert-icon {
            color: #10b981;
        }

        .alert-content {
            flex: 1;
        }

        .alert-danger .alert-content strong {
            color: #991b1b;
        }

        .alert-info .alert-content strong {
            color: #0369a1;
        }

        .alert-warning .alert-content strong {
            color: #b45309;
        }

        .alert-success .alert-content strong {
            color: #166534;
        }

        .alert-content ul {
            padding-left: 1.5rem;
            color: #b91c1c;
        }

        .alert-info .alert-content ul {
            color: #0369a1;
        }

        .alert-close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #991b1b;
            opacity: 0.7;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .text-danger {
            color: #ef4444;
        }

        /* Input Group */
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: #94a3b8;
            font-size: 1.1rem;
            z-index: 10;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.8rem;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control[readonly] {
            background: #f1f5f9;
            cursor: not-allowed;
        }

        .bg-light {
            background: #f1f5f9 !important;
        }

        select.form-control {
            padding: 0.875rem 1rem 0.875rem 1rem;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        /* Error Message */
        .error-message {
            display: block;
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            padding-left: 0.5rem;
        }

        /* Hint Text */
        .hint-text {
            display: block;
            color: #64748b;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            padding-left: 0.5rem;
        }

        .hint-text i {
            margin-right: 0.25rem;
        }

        .hint-text.text-info {
            color: #0ea5e9;
        }

        .hint-text.text-warning {
            color: #f59e0b;
        }

        /* Info Rencana Card */
        .info-rencana-card {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #fde68a;
        }

        .info-rencana-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .info-rencana-header i {
            font-size: 1.2rem;
            color: #92400e;
        }

        .info-rencana-header h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #92400e;
            margin: 0;
        }

        .info-rencana-content {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-row {
            display: flex;
            flex-direction: column;
        }

        .info-row.highlight {
            background: rgba(255, 255, 255, 0.5);
            padding: 0.75rem;
            border-radius: 8px;
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 0.8rem;
            color: #92400e;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #78350f;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn-update {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.95rem;
            flex: 1;
            justify-content: center;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
        }

        .btn-update:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-cancel {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 0.875rem 2rem;
            border-radius: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.95rem;
            flex: 1;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: #fecaca;
            color: #b91c1c;
        }

        /* Info Card */
        .info-card {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            border: 1px solid #fde68a;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: #f59e0b;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .info-content {
            flex: 1;
        }

        .info-content h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 0.5rem;
        }

        .info-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-content ul li {
            color: #b45309;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-content ul li i.bi-check-circle.text-success {
            color: #10b981 !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .anggaran-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .info-rencana-content {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-update,
            .btn-cancel {
                width: 100%;
            }
        }

        /* Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-card,
        .info-card {
            animation: slideIn 0.5s ease;
        }

        .d-block {
            display: block;
        }

        .mt-1 {
            margin-top: 0.25rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalInput = document.getElementById('total_anggaran');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('formAnggaran');
            const statusSelect = document.getElementById('status');
            const currentStatus = '{{ $anggaran->status }}';
            const hasTransactions = {{ $hasTransactions ? 'true' : 'false' }};

            // Format Rupiah
            function formatRupiah(angka) {
                if (!angka) return '';
                // Hapus karakter selain angka
                let number = angka.toString().replace(/[^,\d]/g, '');
                // Pisahkan desimal
                let split = number.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }

            function parseRupiah(value) {
                if (!value) return 0;
                // Hapus titik, ubah koma jadi titik untuk desimal
                let number = value.replace(/\./g, '').replace(',', '.');
                return parseFloat(number) || 0;
            }

            function formatRupiahInput(element) {
                let value = element.value.replace(/[^,\d]/g, '');
                if (value) {
                    element.value = formatRupiah(value);
                }
            }

            // Format rupiah on input
            totalInput.addEventListener('keyup', function() {
                formatRupiahInput(this);
            });

            // Handle form submission dengan SweetAlert
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Validasi total anggaran
                const formattedValue = totalInput.value;
                const rawValue = parseRupiah(formattedValue);

                if (rawValue <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Total anggaran harus lebih dari 0',
                        confirmButtonColor: '#f59e0b'
                    });
                    return false;
                }

                // Cek perubahan status
                const newStatus = statusSelect.value;

                if (currentStatus === 'ditutup' && newStatus === 'aktif') {
                    // Konfirmasi membuka anggaran
                    Swal.fire({
                        title: 'Buka Anggaran?',
                        text: 'Anda akan mengaktifkan kembali anggaran yang ditutup. Lanjutkan?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Buka',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm(rawValue);
                        }
                    });
                } else if (currentStatus === 'aktif' && newStatus === 'ditutup') {
                    // Konfirmasi menutup anggaran
                    Swal.fire({
                        title: 'Tutup Anggaran?',
                        text: 'Anda akan menutup anggaran ini. Anggaran yang ditutup tidak dapat digunakan untuk pengajuan baru. Lanjutkan?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f59e0b',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Tutup',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm(rawValue);
                        }
                    });
                } else {
                    // Tidak ada perubahan status atau perubahan lainnya
                    submitForm(rawValue);
                }
            });

            function submitForm(rawValue) {
                // Ubah nilai input ke angka murni sebelum submit
                totalInput.value = rawValue;

                // Tampilkan loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form
                form.submit();
            }

            // Form validation tambahan untuk total realisasi
            @if ($hasTransactions)
                const totalRealisasi = {{ $anggaran->total_realisasi ?? 0 }};

                totalInput.addEventListener('blur', function() {
                    const value = parseRupiah(this.value);
                    if (value < totalRealisasi) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            html: `Total anggaran tidak boleh kurang dari total realisasi yang sudah ada.<br>
                               Total realisasi saat ini: <strong>Rp {{ number_format($anggaran->total_realisasi ?? 0, 0, ',', '.') }}</strong>`,
                            confirmButtonColor: '#f59e0b'
                        });
                    }
                });
            @endif
        });
    </script>
@endsection
