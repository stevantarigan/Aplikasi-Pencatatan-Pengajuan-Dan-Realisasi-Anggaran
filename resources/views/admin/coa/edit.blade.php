@extends('layouts.app')

@section('title', 'Edit COA')

@section('icon', 'pencil-square')

@section('content')
    <div class="edit-coa-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Edit COA</h2>
                <p class="page-subtitle">Ubah informasi Chart of Account (Kode Rekening)</p>
            </div>
            <a href="{{ route('admin.daftarCOA') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <form action="{{ route('admin.coa.update', $coa->id) }}" method="POST" id="editCoaForm">
                @csrf
                @method('PUT')

                <div class="form-body">
                    <!-- Informasi COA -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-file-earmark-text"></i>
                            Informasi COA
                        </h3>
                        <div class="section-divider"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Kode COA <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="bi bi-hash input-icon"></i>
                                        <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                            name="kode" value="{{ old('kode', $coa->kode) }}"
                                            placeholder="Contoh: 1.1.01" required>
                                    </div>
                                    @error('kode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i>
                                        Kode harus unik dan mengikuti format penomoran COA
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Tipe Rekening <span class="text-danger">*</span>
                                    </label>
                                    <div class="tipe-selector">
                                        <div class="tipe-option">
                                            <input type="radio" name="tipe" id="tipeUtama" value="utama"
                                                {{ old('tipe', $coa->tipe) == 'utama' ? 'checked' : '' }} required>
                                            <label for="tipeUtama" class="tipe-card tipe-utama">
                                                <i class="bi bi-star-fill"></i>
                                                <div class="tipe-info">
                                                    <span class="tipe-name">Utama</span>
                                                    <span class="tipe-desc">Rekening induk / header</span>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="tipe-option">
                                            <input type="radio" name="tipe" id="tipeTurunan" value="turunan"
                                                {{ old('tipe', $coa->tipe) == 'turunan' ? 'checked' : '' }}>
                                            <label for="tipeTurunan" class="tipe-card tipe-turunan">
                                                <i class="bi bi-diagram-3"></i>
                                                <div class="tipe-info">
                                                    <span class="tipe-name">Turunan</span>
                                                    <span class="tipe-desc">Rekening anak / detail</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @error('tipe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        Nama Rekening <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="bi bi-file-text input-icon"></i>
                                        <input type="text"
                                            class="form-control @error('nama_rekening') is-invalid @enderror"
                                            name="nama_rekening" value="{{ old('nama_rekening', $coa->nama_rekening) }}"
                                            placeholder="Masukkan nama rekening" required>
                                    </div>
                                    @error('nama_rekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-info-circle"></i>
                            Informasi Tambahan
                        </h3>
                        <div class="section-divider"></div>

                        <div class="info-grid">
                            <div class="info-item">
                                <i class="bi bi-calendar-plus"></i>
                                <div>
                                    <span class="info-label">Dibuat pada</span>
                                    <span class="info-value">{{ $coa->created_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-calendar-check"></i>
                                <div>
                                    <span class="info-label">Terakhir diupdate</span>
                                    <span class="info-value">{{ $coa->updated_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-hash"></i>
                                <div>
                                    <span class="info-label">ID COA</span>
                                    <span class="info-value">#{{ $coa->id }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="info-box">
                            <i class="bi bi-info-circle-fill"></i>
                            <div class="info-content">
                                <strong>Panduan Pengisian:</strong>
                                <ul>
                                    <li><strong>Kode COA:</strong> Gunakan format titik sebagai pemisah (contoh: 1.1.01,
                                        1.2.05, 2.1.10)</li>
                                    <li><strong>Tipe Utama:</strong> Untuk rekening induk yang menjadi kategori (tidak bisa
                                        diinput transaksi langsung)</li>
                                    <li><strong>Tipe Turunan:</strong> Untuk rekening detail yang bisa digunakan dalam
                                        transaksi</li>
                                    <li><strong>Nama Rekening:</strong> Gunakan nama yang jelas dan mudah dipahami</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="action-left">
                        <small class="text-muted">
                            <span class="text-danger">*</span> Field wajib diisi
                        </small>
                    </div>
                    <div class="action-right">
                        <a href="{{ route('admin.daftarCOA') }}" class="btn-cancel">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="bi bi-check-lg"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .edit-coa-container {
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

        /* Form Card */
        .form-card {
            background: #f8fafc;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .form-body {
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2.5rem;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #1e293b;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .section-title i {
            color: #3b82f6;
            font-size: 1.4rem;
        }

        .section-divider {
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            width: 80px;
            margin-bottom: 1.5rem;
            border-radius: 2px;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .text-danger {
            color: #ef4444;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: #94a3b8;
            font-size: 1rem;
            z-index: 1;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .text-muted {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }

        .text-muted i {
            font-size: 0.85rem;
        }

        /* Tipe Selector */
        .tipe-selector {
            display: flex;
            gap: 1rem;
        }

        .tipe-option {
            position: relative;
            flex: 1;
        }

        .tipe-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .tipe-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tipe-card i {
            font-size: 1.8rem;
        }

        .tipe-info {
            flex: 1;
        }

        .tipe-name {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .tipe-desc {
            display: block;
            font-size: 0.8rem;
            color: #64748b;
        }

        .tipe-option input[type="radio"]:checked+.tipe-card {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.1);
        }

        /* Tipe Colors */
        .tipe-card.tipe-utama i {
            color: #10b981;
        }

        .tipe-card.tipe-turunan i {
            color: #f59e0b;
        }

        .tipe-option input[type="radio"]:checked+.tipe-card.tipe-utama {
            background: #d1fae5;
            border-color: #10b981;
        }

        .tipe-option input[type="radio"]:checked+.tipe-card.tipe-turunan {
            background: #fed7aa;
            border-color: #f59e0b;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
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
            font-size: 1.5rem;
            color: #3b82f6;
        }

        .info-label {
            display: block;
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .info-value {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Info Box */
        .info-box {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            background: #e0f2fe;
            border-radius: 12px;
            border: 1px solid #bae6fd;
        }

        .info-box i {
            color: #3b82f6;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .info-content {
            color: #1e293b;
            font-size: 0.9rem;
            flex: 1;
        }

        .info-content strong {
            display: block;
            margin-bottom: 0.5rem;
            color: #0369a1;
            font-size: 1rem;
        }

        .info-content ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .info-content li {
            margin-bottom: 0.25rem;
            color: #475569;
        }

        .info-content li strong {
            display: inline;
            color: #0369a1;
            font-size: 0.9rem;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: white;
            border-top: 1px solid #e2e8f0;
        }

        .action-right {
            display: flex;
            gap: 1rem;
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #f1f5f9;
            color: #64748b;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
            color: #475569;
            transform: translateY(-2px);
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .edit-coa-container {
                padding: 1.5rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .btn-back {
                width: 100%;
                justify-content: center;
            }

            .form-body {
                padding: 1.5rem;
            }

            .tipe-selector {
                flex-direction: column;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
                gap: 1rem;
            }

            .action-left {
                width: 100%;
                text-align: center;
            }

            .action-right {
                width: 100%;
            }

            .btn-cancel,
            .btn-submit {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .action-right {
                flex-direction: column;
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            // Prevent double submit
            $('#editCoaForm').on('submit', function() {
                $('#submitBtn').prop('disabled', true);
                $('#submitBtn').html('<i class="bi bi-hourglass-split"></i> Menyimpan...');
            });

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

            // Validasi format kode COA (opsional)
            $('#kode').on('keyup', function() {
                var kode = $(this).val();
                // Validasi sederhana: harus mengandung angka dan titik
                if (kode && !/^[\d.]+$/.test(kode)) {
                    $(this).addClass('is-invalid');
                    if ($(this).siblings('.invalid-feedback').length === 0) {
                        $(this).after(
                            '<div class="invalid-feedback">Kode hanya boleh berisi angka dan titik</div>'
                            );
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                }
            });
        });
    </script>
@endsection
