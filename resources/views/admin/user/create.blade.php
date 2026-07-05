@extends('layouts.app')

@section('title', 'Tambah User')

@section('icon', 'person-plus')

@section('content')
    <div class="create-user-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Tambah User Baru</h2>
                <p class="page-subtitle">Buat pengguna baru untuk sistem informasi anggaran</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <form action="{{ route('users.store') }}" method="POST" id="createUserForm">
                @csrf

                <div class="form-body">
                    <!-- Informasi Akun -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-person-circle"></i>
                            Informasi Akun
                        </h3>
                        <div class="section-divider"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="bi bi-person input-icon"></i>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap"
                                            required>
                                    </div>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Username <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="bi bi-at input-icon"></i>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            name="username" value="{{ old('username') }}" placeholder="Masukkan username"
                                            required>
                                    </div>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Username harus unik dan hanya berisi huruf, angka, dan
                                        underscore</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keamanan -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-shield-lock"></i>
                            Keamanan
                        </h3>
                        <div class="section-divider"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="bi bi-lock input-icon"></i>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" id="password" placeholder="Minimal 6 karakter" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="password-strength" id="passwordStrength">
                                        <div class="strength-bar"></div>
                                    </div>
                                    <small class="text-muted">Minimal 6 karakter. Gunakan kombinasi huruf dan angka untuk
                                        keamanan yang lebih baik</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="bi bi-lock-fill input-icon"></i>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Ulangi password" required>
                                        <button type="button" class="password-toggle"
                                            onclick="togglePassword('password_confirmation')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div id="passwordMatch" class="password-match"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role & Hak Akses -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-person-badge"></i>
                            Role & Hak Akses
                        </h3>
                        <div class="section-divider"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Role <span class="text-danger">*</span>
                                    </label>
                                    <div class="role-selector">
                                        <!-- Admin -->
                                        <div class="role-option">
                                            <input type="radio" name="role" id="roleAdmin" value="admin"
                                                {{ old('role') == 'admin' ? 'checked' : '' }} required>
                                            <label for="roleAdmin" class="role-card role-admin">
                                                <i class="bi bi-shield-lock"></i>
                                                <div class="role-info">
                                                    <span class="role-name">Admin</span>
                                                    <span class="role-desc">Akses penuh ke semua fitur</span>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Keuangan -->
                                        <div class="role-option">
                                            <input type="radio" name="role" id="roleKeuangan" value="keuangan"
                                                {{ old('role') == 'keuangan' ? 'checked' : '' }}>
                                            <label for="roleKeuangan" class="role-card role-keuangan">
                                                <i class="bi bi-calculator"></i>
                                                <div class="role-info">
                                                    <span class="role-name">Keuangan</span>
                                                    <span class="role-desc">Mengelola data keuangan dan realisasi</span>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Kepala Gizi -->
                                        <div class="role-option">
                                            <input type="radio" name="role" id="roleKepalaGizi" value="kepala_gizi"
                                                {{ old('role') == 'kepala_gizi' ? 'checked' : '' }}>
                                            <label for="roleKepalaGizi" class="role-card role-kepala_gizi">
                                                <i class="bi bi-person-workspace"></i>
                                                <div class="role-info">
                                                    <span class="role-name">Kepala Gizi</span>
                                                    <span class="role-desc">Approval dan monitoring</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status Awal</label>
                                    <div class="status-info">
                                        <div class="status-badge active">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Active
                                        </div>
                                        <p class="status-note">
                                            <i class="bi bi-info-circle"></i>
                                            User baru akan aktif secara default. Status dapat diubah nanti.
                                        </p>
                                    </div>
                                </div>

                                <!-- Informasi Hak Akses -->
                                <div class="info-box">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <div class="info-content">
                                        <strong>Informasi Hak Akses:</strong>
                                        <ul>
                                            <li><strong>Admin:</strong> Mengelola user, melihat semua data, akses penuh</li>
                                            <li><strong>Keuangan:</strong> Input pengajuan, realisasi, dan melihat laporan
                                                keuangan</li>
                                            <li><strong>Kepala Gizi:</strong> Menyetujui pengajuan, monitoring, dan melihat
                                                laporan</li>
                                        </ul>
                                    </div>
                                </div>
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
                        <a href="{{ route('users.index') }}" class="btn-cancel">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="bi bi-check-lg"></i>
                            Simpan User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .create-user-container {
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
            color: #6d5dfc;
            font-size: 1.4rem;
        }

        .section-divider {
            height: 2px;
            background: linear-gradient(90deg, #6d5dfc, #22d3ee);
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
            border-color: #6d5dfc;
            box-shadow: 0 0 0 3px rgba(109, 93, 252, 0.1);
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

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 1rem;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
            z-index: 1;
        }

        .password-toggle:hover {
            color: #6d5dfc;
        }

        /* Password Strength */
        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
        }

        .strength-bar.weak {
            width: 33.33%;
            background: #ef4444;
        }

        .strength-bar.medium {
            width: 66.66%;
            background: #f59e0b;
        }

        .strength-bar.strong {
            width: 100%;
            background: #10b981;
        }

        /* Password Match */
        .password-match {
            margin-top: 0.25rem;
            font-size: 0.85rem;
        }

        .password-match.match {
            color: #10b981;
        }

        .password-match.not-match {
            color: #ef4444;
        }

        /* Role Selector */
        .role-selector {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .role-card {
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

        .role-card i {
            font-size: 1.8rem;
        }

        .role-info {
            flex: 1;
        }

        .role-name {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .role-desc {
            display: block;
            font-size: 0.85rem;
            color: #64748b;
        }

        .role-option input[type="radio"]:checked+.role-card {
            border-color: #6d5dfc;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(109, 93, 252, 0.1);
        }

        /* Role Colors */
        .role-card.role-admin i {
            color: #ef4444;
        }

        .role-card.role-keuangan i {
            color: #3b82f6;
        }

        .role-card.role-kepala_gizi i {
            color: #8b5cf6;
        }

        .role-option input[type="radio"]:checked+.role-card.role-admin {
            background: #fee2e2;
        }

        .role-option input[type="radio"]:checked+.role-card.role-keuangan {
            background: #dbeafe;
        }

        .role-option input[type="radio"]:checked+.role-card.role-kepala_gizi {
            background: #ede9fe;
        }

        /* Status Info */
        .status-info {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #d1fae5;
            color: #10b981;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .status-badge i {
            font-size: 1rem;
        }

        .status-note {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.85rem;
            margin: 0;
        }

        .status-note i {
            color: #3b82f6;
        }

        /* Info Box */
        .info-box {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: #e0e7ff;
            border-radius: 12px;
            margin-top: 1.5rem;
        }

        .info-box i {
            color: #6d5dfc;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .info-content {
            color: #1e293b;
            font-size: 0.9rem;
        }

        .info-content strong {
            display: block;
            margin-bottom: 0.5rem;
            color: #1e293b;
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
            color: #6d5dfc;
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
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(109, 93, 252, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .create-user-container {
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
    </style>

    <script>
        $(document).ready(function() {
            // Password strength checker
            $('#password').on('keyup', function() {
                var password = $(this).val();
                var strengthBar = $('#passwordStrength .strength-bar');

                // Remove all classes
                strengthBar.removeClass('weak medium strong');

                if (password.length === 0) {
                    strengthBar.css('width', '0');
                    return;
                }

                // Check strength
                var strength = 0;

                // Length check
                if (password.length >= 6) strength += 1;
                if (password.length >= 8) strength += 1;

                // Character variety
                if (password.match(/[a-z]/)) strength += 1;
                if (password.match(/[A-Z]/)) strength += 1;
                if (password.match(/[0-9]/)) strength += 1;
                if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

                // Determine strength class
                if (strength <= 2) {
                    strengthBar.addClass('weak');
                    strengthBar.css('width', '33.33%');
                } else if (strength <= 4) {
                    strengthBar.addClass('medium');
                    strengthBar.css('width', '66.66%');
                } else {
                    strengthBar.addClass('strong');
                    strengthBar.css('width', '100%');
                }
            });

            // Password match checker
            $('#password, #password_confirmation').on('keyup', function() {
                var password = $('#password').val();
                var confirm = $('#password_confirmation').val();
                var matchDiv = $('#passwordMatch');

                if (confirm.length === 0) {
                    matchDiv.removeClass('match not-match').empty();
                    return;
                }

                if (password === confirm) {
                    matchDiv.removeClass('not-match').addClass('match').html(
                        '<i class="bi bi-check-circle-fill"></i> Password cocok');
                } else {
                    matchDiv.removeClass('match').addClass('not-match').html(
                        '<i class="bi bi-exclamation-circle-fill"></i> Password tidak cocok');
                }
            });

            // Form validation
            $('#createUserForm').on('submit', function(e) {
                var password = $('#password').val();
                var confirm = $('#password_confirmation').val();

                if (password !== confirm) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok',
                        text: 'Password dan konfirmasi password harus sama!',
                    });
                }
            });

            // Prevent double submit
            $('#createUserForm').on('submit', function() {
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
        });

        // Toggle password visibility
        function togglePassword(inputId) {
            var input = document.getElementById(inputId);
            var icon = event.currentTarget.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
@endsection
