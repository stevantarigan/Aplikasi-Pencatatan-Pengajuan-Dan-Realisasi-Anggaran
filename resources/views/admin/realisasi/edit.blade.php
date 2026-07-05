@extends('layouts.app')

@section('title', 'Edit Realisasi Anggaran')
@section('icon', 'pencil-square')

@section('content')
    <div class="realisasi-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Edit Realisasi Anggaran</h2>
                <p class="page-subtitle">Perbaharui data realisasi yang sudah ada</p>
            </div>
            <a href="{{ route('admin.realisasi.index') }}" class="btn-secondary">
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
                    <h3>Form Edit Realisasi Anggaran</h3>
                    <p>Edit data realisasi untuk pengajuan: {{ $realisasi->nomor_pengajuan }}</p>
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

                <form action="{{ route('admin.realisasi.update', $realisasi->id) }}" method="POST" id="formRealisasi">
                    @csrf
                    @method('PUT')

                    <!-- Info Pengajuan Card (Readonly) -->
                    <div class="info-pengajuan-card">
                        <div class="info-pengajuan-header">
                            <i class="bi bi-info-circle"></i>
                            <h4>Informasi Pengajuan</h4>
                        </div>
                        <div class="info-pengajuan-content">
                            <div class="info-row">
                                <span class="info-label">Nomor Pengajuan:</span>
                                <span class="info-value">{{ $realisasi->nomor_pengajuan }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nama Rencana:</span>
                                <span
                                    class="info-value fw-bold">{{ $realisasi->anggaran->rencanaAnggaran->nama_rencana ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Kegiatan:</span>
                                <span
                                    class="info-value">{{ $realisasi->anggaran->rencanaAnggaran->nama_kegiatan ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Kode Rekening:</span>
                                <span
                                    class="info-value">{{ $realisasi->anggaran->rencanaAnggaran->kodeRekening->kode ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nama Rekening:</span>
                                <span
                                    class="info-value">{{ $realisasi->anggaran->rencanaAnggaran->kodeRekening->nama_rekening ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Periode:</span>
                                <span class="info-value">{{ $realisasi->nama_bulan }}
                                    {{ $realisasi->anggaran->tahun ?? '-' }}</span>
                            </div>
                            <div class="info-row highlight">
                                <span class="info-label">Jumlah Diajukan:</span>
                                <span class="info-value">Rp
                                    {{ number_format($realisasi->jumlah_diajukan, 0, ',', '.') }}</span>
                            </div>
                            <div class="info-row highlight">
                                <span class="info-label">Jumlah Realisasi Saat Ini:</span>
                                <span class="info-value text-success">Rp
                                    {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Sisa Anggaran:</span>
                                <span
                                    class="info-value {{ $realisasi->sisa_anggaran > 0 ? 'text-warning' : 'text-muted' }}">
                                    Rp {{ number_format($realisasi->sisa_anggaran, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <!-- Jumlah Realisasi -->
                        <div class="form-group">
                            <label for="jumlah_realisasi" class="form-label">
                                Jumlah Realisasi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-cash"></i>
                                </span>
                                <input type="text" name="jumlah_realisasi" id="jumlah_realisasi"
                                    class="form-control @error('jumlah_realisasi') is-invalid @enderror"
                                    value="{{ old('jumlah_realisasi', number_format($realisasi->jumlah_realisasi, 0, ',', '.')) }}"
                                    placeholder="Masukkan jumlah realisasi" required>
                            </div>
                            @error('jumlah_realisasi')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <small class="hint-text">
                                <i class="bi bi-info-circle"></i>
                                Maksimal: Rp {{ number_format($realisasi->jumlah_diajukan, 0, ',', '.') }}
                            </small>
                        </div>

                        <!-- Tanggal Realisasi -->
                        <div class="form-group">
                            <label for="tanggal_realisasi" class="form-label">
                                Tanggal Realisasi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-calendar"></i>
                                </span>
                                <input type="date" name="tanggal_realisasi" id="tanggal_realisasi"
                                    class="form-control @error('tanggal_realisasi') is-invalid @enderror"
                                    value="{{ old('tanggal_realisasi', $realisasi->tanggal_realisasi ? $realisasi->tanggal_realisasi->format('Y-m-d') : date('Y-m-d')) }}"
                                    required>
                            </div>
                            @error('tanggal_realisasi')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Keterangan Realisasi -->
                        <div class="form-group full-width">
                            <label for="keterangan_realisasi" class="form-label">
                                Keterangan Realisasi
                            </label>
                            <textarea name="keterangan_realisasi" id="keterangan_realisasi"
                                class="form-control @error('keterangan_realisasi') is-invalid @enderror" rows="4"
                                placeholder="Masukkan keterangan realisasi (opsional)">{{ old('keterangan_realisasi', $realisasi->keterangan_realisasi) }}</textarea>
                            @error('keterangan_realisasi')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn-update">
                            <i class="bi bi-check-lg"></i>
                            Update Realisasi
                        </button>
                        <a href="{{ route('admin.realisasi.index') }}" class="btn-cancel">
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
                    <li><i class="bi bi-check-circle text-warning"></i> Perubahan data akan langsung tersimpan di sistem
                    </li>
                    <li><i class="bi bi-check-circle text-warning"></i> Jumlah realisasi tidak boleh melebihi jumlah yang
                        diajukan</li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        /* Main Container */
        .realisasi-container {
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

        .alert-icon {
            font-size: 1.5rem;
            color: #dc2626;
        }

        .alert-content {
            flex: 1;
        }

        .alert-content strong {
            color: #991b1b;
        }

        .alert-content ul {
            padding-left: 1.5rem;
            color: #b91c1c;
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

        /* Info Pengajuan Card */
        .info-pengajuan-card {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #fde68a;
        }

        .info-pengajuan-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .info-pengajuan-header i {
            font-size: 1.2rem;
            color: #92400e;
        }

        .info-pengajuan-header h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #92400e;
            margin: 0;
        }

        .info-pengajuan-content {
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

        .text-success {
            color: #10b981 !important;
        }

        .text-warning {
            color: #f59e0b !important;
        }

        .fw-bold {
            font-weight: 700;
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

        /* Responsive */
        @media (max-width: 768px) {
            .realisasi-container {
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

            .info-pengajuan-content {
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jumlahInput = document.getElementById('jumlah_realisasi');
            const maxJumlah = {{ $realisasi->jumlah_diajukan }};
            const form = document.getElementById('formRealisasi');

            // Format Rupiah
            function formatRupiah(angka) {
                if (!angka) return '';
                let number = angka.toString().replace(/[^,\d]/g, '');
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
                return parseInt(value.replace(/\./g, '')) || 0;
            }

            function formatRupiahInput(element) {
                let value = element.value.replace(/[^,\d]/g, '');
                if (value) {
                    element.value = formatRupiah(value);
                }
            }

            // Format rupiah on input
            jumlahInput.addEventListener('keyup', function() {
                formatRupiahInput(this);
            });

            // Form validation
            form.addEventListener('submit', function(e) {
                const jumlah = parseRupiah(jumlahInput.value);

                if (jumlah <= 0) {
                    e.preventDefault();
                    alert('Jumlah realisasi harus lebih dari 0');
                    return;
                }

                if (jumlah > maxJumlah) {
                    e.preventDefault();
                    alert('Jumlah realisasi tidak boleh melebihi Rp ' + formatRupiah(maxJumlah));
                    return;
                }
            });

            // Initial format
            if (jumlahInput.value) {
                jumlahInput.value = formatRupiah(parseRupiah(jumlahInput.value));
            }
        });
    </script>
@endsection
