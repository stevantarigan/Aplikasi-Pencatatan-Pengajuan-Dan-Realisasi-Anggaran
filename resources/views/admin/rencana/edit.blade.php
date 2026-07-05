@extends('layouts.app')

@section('title', 'Edit Rencana Anggaran')
@section('icon', 'pencil-square')

@section('content')
    <div class="edit-rencana-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Edit Rencana Anggaran</h2>
                <p class="page-subtitle">Perbaharui data rencana anggaran</p>
            </div>
            <a href="{{ route('admin.rencana.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if ($rencana->status != 'diajukan' && $rencana->status != 'revisi')
            <div class="alert alert-warning mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Rencana anggaran dengan status <strong>{{ ucfirst($rencana->status) }}</strong> tidak dapat diedit.
                <a href="{{ route('admin.rencana.index') }}" class="alert-link">Kembali ke daftar</a>
            </div>
        @else
            <!-- Form Card -->
            <div class="form-card">
                <form action="{{ route('admin.rencana.update', $rencana->id) }}" method="POST" id="rencanaForm">
                    @csrf
                    @method('PUT')

                    <div class="form-body">
                        <!-- Informasi Status -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-info-circle"></i>
                                Informasi Status
                            </h3>
                            <div class="section-divider"></div>

                            <div class="status-box">
                                <div class="status-item">
                                    <span class="status-label">Status Saat Ini:</span>
                                    @if ($rencana->status == 'diajukan')
                                        <span class="status-badge bg-warning">Diajukan</span>
                                    @elseif ($rencana->status == 'revisi')
                                        <span class="status-badge bg-info">Revisi</span>
                                    @elseif ($rencana->status == 'disetujui')
                                        <span class="status-badge bg-success">Disetujui</span>
                                    @elseif ($rencana->status == 'ditolak')
                                        <span class="status-badge bg-danger">Ditolak</span>
                                    @endif
                                </div>
                                @if ($rencana->status == 'revisi' && $rencana->keterangan)
                                    <div class="status-item mt-2">
                                        <span class="status-label">Catatan Revisi:</span>
                                        <div class="revision-note">
                                            {{ $rencana->keterangan }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informasi Rencana -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-file-earmark-text"></i>
                                Informasi Rencana
                            </h3>
                            <div class="section-divider"></div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Kode Rekening <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-hash input-icon"></i>
                                            <select class="form-control @error('kode_rekening_id') is-invalid @enderror"
                                                id="kode_rekening_id" name="kode_rekening_id" required>
                                                <option value="">Pilih Kode Rekening</option>
                                                @foreach ($kodeRekening as $rekening)
                                                    <option value="{{ $rekening->id }}"
                                                        {{ old('kode_rekening_id', $rencana->kode_rekening_id) == $rekening->id ? 'selected' : '' }}>
                                                        {{ $rekening->kode }} - {{ $rekening->nama_rekening }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('kode_rekening_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Tahun <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-calendar-year input-icon"></i>
                                            <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                                id="tahun" name="tahun" value="{{ old('tahun', $rencana->tahun) }}"
                                                min="2000" max="2099" placeholder="Contoh: {{ date('Y') }}"
                                                required>
                                        </div>
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i>
                                            Masukkan tahun antara 2000 - 2099
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Nama Rencana <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-file-text input-icon"></i>
                                            <input type="text"
                                                class="form-control @error('nama_rencana') is-invalid @enderror"
                                                id="nama_rencana" name="nama_rencana"
                                                value="{{ old('nama_rencana', $rencana->nama_rencana) }}"
                                                placeholder="Contoh: Rencana Pembangunan Gedung" required>
                                        </div>
                                        @error('nama_rencana')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Anggaran -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-cash-stack"></i>
                                Informasi Anggaran
                            </h3>
                            <div class="section-divider"></div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Total Anggaran <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <i class="bi bi-cash input-icon"></i>
                                            <input type="text"
                                                class="form-control @error('total_anggaran') is-invalid @enderror"
                                                id="total_anggaran" name="total_anggaran"
                                                value="{{ old('total_anggaran', $rencana->total_anggaran) }}"
                                                placeholder="Contoh: 1000000 atau 1.000.000" required>
                                            <span class="input-suffix">Rp</span>
                                        </div>
                                        @error('total_anggaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i>
                                            Masukkan angka, otomatis akan muncul titik sebagai pemisah ribuan. Contoh:
                                            1.000.000
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-chat-text"></i>
                                Keterangan
                            </h3>
                            <div class="section-divider"></div>

                            <div class="form-group">
                                <div class="input-wrapper">
                                    <i class="bi bi-pencil input-icon"></i>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                        rows="4" placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan', $rencana->keterangan) }}</textarea>
                                </div>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-info-circle"></i>
                                Informasi
                            </h3>
                            <div class="section-divider"></div>

                            <!-- Info Box -->
                            <div class="info-box">
                                <i class="bi bi-info-circle-fill"></i>
                                <div class="info-content">
                                    <strong>Panduan Pengisian:</strong>
                                    <ul>
                                        <li><strong>Kode Rekening:</strong> Pilih kode rekening yang sesuai dengan rencana
                                            anggaran</li>
                                        <li><strong>Tahun:</strong> Masukkan tahun anggaran yang direncanakan (2000-2099)
                                        </li>
                                        <li><strong>Nama Rencana:</strong> Gunakan nama yang jelas dan spesifik</li>
                                        <li><strong>Total Anggaran:</strong> Masukkan nominal dalam Rupiah, otomatis akan
                                            diformat dengan titik</li>
                                        <li><strong>Keterangan:</strong> Tambahkan informasi pendukung jika diperlukan
                                            (opsional)</li>
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
                            <a href="{{ route('admin.rencana.index') }}" class="btn-cancel">
                                <i class="bi bi-x-lg"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn-submit" id="submitBtn">
                                <i class="bi bi-check-lg"></i>
                                Update Rencana
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <style>
        .edit-rencana-container {
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

        /* Alert */
        .alert-danger {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 1rem;
            color: #b91c1c;
            display: flex;
            align-items: center;
        }

        .alert-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 1rem;
            color: #92400e;
            display: flex;
            align-items: center;
        }

        .alert-link {
            color: #92400e;
            font-weight: 600;
            text-decoration: underline;
            margin-left: 0.5rem;
        }

        /* Status Box */
        .status-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .status-label {
            font-weight: 600;
            color: #475569;
            min-width: 120px;
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
        }

        .bg-warning {
            background: #f59e0b;
        }

        .bg-info {
            background: #0ea5e9;
        }

        .bg-success {
            background: #10b981;
        }

        .bg-danger {
            background: #ef4444;
        }

        .revision-note {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            color: #475569;
            flex: 1;
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
            color: #f59e0b;
            font-size: 1.4rem;
        }

        .section-divider {
            height: 2px;
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
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

        .input-suffix {
            position: absolute;
            right: 1rem;
            color: #64748b;
            font-weight: 600;
            font-size: 0.9rem;
            background: #f1f5f9;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
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
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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

        /* Info Box */
        .info-box {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            background: #fffbeb;
            border-radius: 12px;
            border: 1px solid #fde68a;
        }

        .info-box i {
            color: #f59e0b;
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
            color: #92400e;
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
            color: #92400e;
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
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .edit-rencana-container {
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

            .status-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // =============================================
            // FORMAT RUPIAH DENGAN TITIK - LANGSUNG SAAT MENGETIK
            // =============================================
            $('#total_anggaran').on('input', function(e) {
                // Simpan posisi cursor
                let cursorPos = this.selectionStart;

                // Ambil nilai saat ini
                let currentValue = this.value;

                // Hitung jumlah titik sebelum formatting
                let previousDots = (currentValue.match(/\./g) || []).length;

                // Hapus semua karakter non-digit
                let rawValue = currentValue.replace(/[^0-9]/g, '');

                if (rawValue !== '') {
                    // Format angka dengan titik (pemisah ribuan)
                    let formatted = '';
                    for (let i = 0; i < rawValue.length; i++) {
                        // Tambahkan titik setiap 3 digit dari belakang
                        if (i > 0 && (rawValue.length - i) % 3 === 0) {
                            formatted += '.';
                        }
                        formatted += rawValue[i];
                    }

                    this.value = formatted;

                    // Sesuaikan posisi cursor
                    let newDots = (formatted.match(/\./g) || []).length;
                    let dotDiff = newDots - previousDots;
                    let newCursorPos = cursorPos + dotDiff;

                    // Pastikan cursor tidak melebihi panjang string
                    if (newCursorPos > formatted.length) {
                        newCursorPos = formatted.length;
                    }

                    this.setSelectionRange(newCursorPos, newCursorPos);
                } else {
                    this.value = '';
                }
            });

            // Handle paste dengan lebih baik
            $('#total_anggaran').on('paste', function(e) {
                e.preventDefault();

                // Ambil data dari clipboard
                let pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');

                // Ambil hanya angka
                let numbers = pastedData.replace(/[^0-9]/g, '');

                if (numbers) {
                    // Format angka
                    let formatted = '';
                    for (let i = 0; i < numbers.length; i++) {
                        if (i > 0 && (numbers.length - i) % 3 === 0) {
                            formatted += '.';
                        }
                        formatted += numbers[i];
                    }

                    this.value = formatted;
                }
            });

            // Format nilai awal dari server
            @if (old('total_anggaran', $rencana->total_anggaran))
                let oldValue = '{{ old('total_anggaran', $rencana->total_anggaran) }}';
                if (oldValue && !isNaN(oldValue)) {
                    // Jika old value berupa angka tanpa format
                    let numbers = oldValue.replace(/[^0-9]/g, '');
                    if (numbers) {
                        let formatted = '';
                        for (let i = 0; i < numbers.length; i++) {
                            if (i > 0 && (numbers.length - i) % 3 === 0) {
                                formatted += '.';
                            }
                            formatted += numbers[i];
                        }
                        $('#total_anggaran').val(formatted);
                    }
                } else if (oldValue && oldValue.includes('.')) {
                    // Jika old value sudah berformat, biarkan saja
                    $('#total_anggaran').val(oldValue);
                }
            @endif

            // =============================================
            // SEBELUM SUBMIT, HAPUS SEMUA TITIK
            // =============================================
            $('#rencanaForm').on('submit', function(e) {
                let totalAnggaran = $('#total_anggaran');
                let rawValue = totalAnggaran.val().replace(/\./g, '');

                // Validasi: pastikan hanya angka
                if (rawValue === '' || isNaN(rawValue) || parseInt(rawValue) <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Total anggaran harus diisi dengan angka yang valid dan lebih dari 0'
                    });
                    return false;
                }

                totalAnggaran.val(rawValue);

                // Disable submit button
                $('#submitBtn').prop('disabled', true);
                $('#submitBtn').html('<i class="bi bi-hourglass-split"></i> Menyimpan...');

                return true;
            });

            // =============================================
            // VALIDASI TAHUN (HARUS ANGKA)
            // =============================================
            $('#tahun').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 4) {
                    this.value = this.value.slice(0, 4);
                }
            });

            // =============================================
            // TAMPILKAN PESAN SUKSES/ERROR
            // =============================================
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
