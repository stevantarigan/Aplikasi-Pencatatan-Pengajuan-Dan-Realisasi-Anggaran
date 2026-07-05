@extends('layouts.app')

@section('title', 'Edit Data Pasien')
@section('icon', 'pencil-square')

@section('content')
    <div class="pasien-edit-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Edit Data Pasien</h2>
                <p class="page-subtitle">Perbaharui data pasien yang sudah ada</p>
            </div>
            <!-- 🔴 PERBAIKAN: Ubah dari daftar-pasien menjadi admin.pasien.index -->
            <a href="{{ route('admin.pasien.index') }}" class="btn-secondary">
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
                    <h3>Form Edit Data Pasien</h3>
                    <p>Ubah data pasien periode {{ $dataPasien->nama_bulan }} {{ $dataPasien->tahun }}</p>
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

                <!-- 🔴 PERBAIKAN: Ubah action route dari pasien.update menjadi admin.pasien.update -->
                <form action="{{ route('admin.pasien.update', $dataPasien->id) }}" method="POST" id="formPasien">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <!-- Bulan Field -->
                        <div class="form-group full-width">
                            <label for="bulan" class="form-label">
                                Bulan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-calendar-month"></i>
                                </span>
                                <select name="bulan" id="bulan"
                                    class="form-control @error('bulan') is-invalid @enderror" required>
                                    <option value="">Pilih Bulan</option>
                                    @php
                                        $bulanList = [
                                            1 => 'Januari',
                                            2 => 'Februari',
                                            3 => 'Maret',
                                            4 => 'April',
                                            5 => 'Mei',
                                            6 => 'Juni',
                                            7 => 'Juli',
                                            8 => 'Agustus',
                                            9 => 'September',
                                            10 => 'Oktober',
                                            11 => 'November',
                                            12 => 'Desember',
                                        ];
                                    @endphp
                                    @foreach ($bulanList as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ old('bulan', $dataPasien->bulan) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('bulan')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tahun Field -->
                        <div class="form-group">
                            <label for="tahun" class="form-label">
                                Tahun <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-calendar-year"></i>
                                </span>
                                <input type="number" name="tahun" id="tahun"
                                    class="form-control @error('tahun') is-invalid @enderror"
                                    value="{{ old('tahun', $dataPasien->tahun) }}" min="2000" max="{{ date('Y') }}"
                                    placeholder="Masukkan tahun" required>
                            </div>
                            @error('tahun')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <small class="hint-text">
                                <i class="bi bi-info-circle"></i>
                                Tahun antara 2000 - {{ date('Y') }}
                            </small>
                        </div>

                        <!-- Jumlah Pasien Field -->
                        <div class="form-group">
                            <label for="jumlah_pasien" class="form-label">
                                Jumlah Pasien <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-people"></i>
                                </span>
                                <input type="number" name="jumlah_pasien" id="jumlah_pasien"
                                    class="form-control @error('jumlah_pasien') is-invalid @enderror"
                                    value="{{ old('jumlah_pasien', $dataPasien->jumlah_pasien) }}" min="0"
                                    placeholder="Masukkan jumlah pasien" required>
                            </div>
                            @error('jumlah_pasien')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <small class="hint-text">
                                <i class="bi bi-info-circle"></i>
                                Jumlah pasien tidak boleh negatif
                            </small>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn-update">
                            <i class="bi bi-check-lg"></i>
                            Update Data
                        </button>
                        <!-- 🔴 PERBAIKAN: Ubah dari daftar-pasien menjadi admin.pasien.index -->
                        <a href="{{ route('admin.pasien.index') }}" class="btn-cancel">
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
                    <li><i class="bi bi-check-circle text-warning"></i> Data yang sudah diupdate tidak dapat dikembalikan
                    </li>
                </ul>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="preview-card">
            <div class="preview-icon">
                <i class="bi bi-file-text"></i>
            </div>
            <div class="preview-content">
                <h5>Preview Data</h5>
                <div class="preview-grid">
                    <div class="preview-item">
                        <span class="preview-label">Periode</span>
                        <span class="preview-value" id="previewPeriode">{{ $dataPasien->nama_bulan }}
                            {{ $dataPasien->tahun }}</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Jumlah Pasien</span>
                        <span class="preview-value"
                            id="previewJumlah">{{ number_format($dataPasien->jumlah_pasien, 0, ',', '.') }} orang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Main Container */
        .pasien-edit-container {
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

        select.form-control {
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
            margin-bottom: 2rem;
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

        .info-content ul li i {
            font-size: 0.9rem;
        }

        /* Preview Card */
        .preview-card {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            border: 1px solid #bae6fd;
        }

        .preview-icon {
            width: 40px;
            height: 40px;
            background: #0ea5e9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .preview-content {
            flex: 1;
        }

        .preview-content h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #0369a1;
            margin-bottom: 1rem;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .preview-item {
            background: rgba(255, 255, 255, 0.5);
            padding: 0.75rem;
            border-radius: 12px;
            backdrop-filter: blur(5px);
        }

        .preview-label {
            display: block;
            font-size: 0.75rem;
            color: #0369a1;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .preview-value {
            display: block;
            font-size: 1.1rem;
            font-weight: 600;
            color: #0c4a6e;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pasien-edit-container {
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

            .form-actions {
                flex-direction: column;
            }

            .btn-update,
            .btn-cancel {
                width: 100%;
            }

            .preview-grid {
                grid-template-columns: 1fr;
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

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .form-card,
        .info-card,
        .preview-card {
            animation: slideIn 0.5s ease;
        }

        .preview-value {
            animation: pulse 2s ease-in-out infinite;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bulanSelect = document.getElementById('bulan');
            const tahunInput = document.getElementById('tahun');
            const jumlahInput = document.getElementById('jumlah_pasien');

            const previewPeriode = document.getElementById('previewPeriode');
            const previewJumlah = document.getElementById('previewJumlah');

            function updatePreview() {
                // Update periode preview
                const bulan = bulanSelect.options[bulanSelect.selectedIndex]?.text || '';
                const tahun = tahunInput.value || '{{ $dataPasien->tahun }}';
                previewPeriode.textContent = bulan + ' ' + tahun;

                // Update jumlah preview
                const jumlah = jumlahInput.value || '{{ $dataPasien->jumlah_pasien }}';
                previewJumlah.textContent = new Intl.NumberFormat('id-ID').format(jumlah) + ' orang';
            }

            bulanSelect.addEventListener('change', updatePreview);
            tahunInput.addEventListener('input', updatePreview);
            jumlahInput.addEventListener('input', updatePreview);

            // Form validation
            document.getElementById('formPasien').addEventListener('submit', function(e) {
                const jumlahPasien = document.getElementById('jumlah_pasien');
                if (jumlahPasien.value < 0) {
                    e.preventDefault();
                    alert('Jumlah pasien tidak boleh negatif!');
                    jumlahPasien.focus();
                }
            });

            // Auto format number
            jumlahInput.addEventListener('input', function(e) {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        });
    </script>
@endsection
