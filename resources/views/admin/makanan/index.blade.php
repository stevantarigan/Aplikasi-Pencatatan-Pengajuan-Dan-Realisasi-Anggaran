{{-- resources/views/admin/makanan/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Pengaturan Harga Makanan')
@section('icon', 'cup-hot')

@section('content')
    <div class="harga-makanan-container">
        <!-- Header Sederhana - DIPERKECIL -->
        <div class="header-section">
            <div class="header-icon">
                <i class="bi bi-cup-hot-fill"></i>
            </div>
            <div class="header-text">
                <h1>Pengaturan Harga Makanan</h1>
                <p>Atur harga makanan per pasien untuk perhitungan rekomendasi anggaran</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Utama - RADIUS DIKECILKAN -->
        <div class="main-card">
            <!-- Harga Saat Ini (Display Only) -->
            <div class="current-price-section">
                <div class="price-label">
                    <i class="bi bi-tag"></i>
                    Harga Saat Ini
                </div>
                <div class="price-value">{{ $makanan->harga_formatted }}</div>
                <div class="price-note">
                    <i class="bi bi-info-circle"></i>
                    {{ $makanan->nama }}
                </div>
            </div>

            <!-- Form Edit Harga (Hanya Harga yang Bisa Diedit) -->
            <div class="edit-section">
                <div class="edit-header">
                    <i class="bi bi-pencil-square"></i>
                    <h3>Ubah Harga Makanan</h3>
                </div>

                <form action="{{ route('admin.makanan.update') }}" method="POST" id="formHarga">
                    @csrf

                    <!-- Nama Makanan (Readonly) -->
                    <div class="form-group">
                        <label for="nama">
                            <i class="bi bi-cup"></i>
                            Nama Makanan
                        </label>
                        <input type="text" class="form-control readonly" id="nama" value="{{ $makanan->nama }}"
                            readonly disabled>
                        <small class="text-muted">Nama makanan tidak dapat diubah</small>
                    </div>

                    <!-- Input Harga (Fokus Utama) -->
                    <div class="form-group">
                        <label for="harga">
                            <i class="bi bi-cash"></i>
                            Harga Baru (Rp)
                        </label>
                        <div class="input-wrapper">
                            <input type="text" class="harga-input" id="harga" name="harga"
                                value="{{ old('harga', $makanan->harga) }}" placeholder="Masukkan harga baru"
                                autocomplete="off">
                            <span class="input-suffix">Rp</span>
                        </div>
                        <div class="input-hint">
                            <i class="bi bi-info-circle"></i>
                            Minimal Rp 1.000, maksimal Rp 1.000.000
                        </div>
                    </div>

                    <!-- Keterangan (Opsional) -->
                    <div class="form-group">
                        <label for="keterangan">
                            <i class="bi bi-chat-text"></i>
                            Keterangan (Opsional)
                        </label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2"
                            placeholder="Contoh: Kenaikan harga bahan baku">{{ old('keterangan', $makanan->keterangan) }}</textarea>
                    </div>

                    <!-- Ringkasan Perubahan -->
                    <div class="summary-box">
                        <div class="summary-row">
                            <span>Harga Sebelumnya:</span>
                            <strong class="text-muted">{{ $makanan->harga_formatted }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Harga Baru:</span>
                            <strong class="text-warning" id="hargaBaruDisplay">Rp 0</strong>
                        </div>
                    </div>

                    <!-- Tombol Aksi - DIPERKECIL -->
                    <div class="action-buttons">
                        <button type="button" class="btn-reset" id="resetBtn">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn-simpan">
                            <i class="bi bi-check-lg"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Dampak (Ringkas) -->
            <div class="info-footer">
                <div class="info-item">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                    <span>Perubahan langsung berlaku (real-time)</span>
                </div>
                <div class="info-item">
                    <i class="bi bi-journal-plus text-success"></i>
                    <span>Mempengaruhi rekomendasi di Tambah Rencana</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Container Utama - DIPERKECIL */
        .harga-makanan-container {
            max-width: 620px;
            /* 700px -> 620px */
            margin: 0 auto;
            padding: 1rem;
            /* 1.5rem -> 1rem */
        }

        /* Header Section - DIPERKECIL */
        .header-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 1.4rem;
            /* 1.5rem 2rem -> 1rem 1.4rem */
            border-radius: 14px;
            /* 20px -> 14px */
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .header-icon {
            width: 50px;
            /* 60px -> 50px */
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-icon i {
            font-size: 1.4rem;
            /* 2rem -> 1.4rem */
        }

        .header-text h1 {
            font-size: 1.4rem;
            /* 1.8rem -> 1.4rem */
            font-weight: 700;
            margin: 0 0 0.25rem 0;
        }

        .header-text p {
            font-size: 0.85rem;
            /* 0.95rem -> 0.85rem */
            margin: 0;
            opacity: 0.9;
        }

        /* Alert */
        .alert-success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            color: #065f46;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }

        .alert-error ul {
            margin: 0.25rem 0 0 1.25rem;
        }

        /* Main Card - RADIUS DIKECILKAN */
        .main-card {
            background: white;
            border-radius: 16px;
            /* 24px -> 16px */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #f0f0f0;
        }

        /* Current Price Section */
        .current-price-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.5rem;
            /* 2rem -> 1.5rem */
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .price-label {
            color: #64748b;
            font-size: 0.9rem;
            /* 1rem -> 0.9rem */
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .price-label i {
            color: #f59e0b;
        }

        .price-value {
            font-size: 2.2rem;
            /* 3rem -> 2.2rem */
            font-weight: 800;
            color: #f59e0b;
            line-height: 1.2;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(245, 158, 11, 0.1);
        }

        .price-note {
            color: #94a3b8;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        /* Edit Section - PADDING DIKECILKAN */
        .edit-section {
            padding: 1.3rem;
            /* 2rem -> 1.3rem */
        }

        .edit-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .edit-header i {
            color: #f59e0b;
            font-size: 1.1rem;
        }

        .edit-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-group label i {
            margin-right: 0.5rem;
            color: #f59e0b;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 1rem;
            /* 0.75rem 1rem -> 0.6rem 1rem */
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            /* 12px -> 10px */
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .form-control.readonly {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        /* INPUT HARGA - DIPERKECIL */
        .harga-input {
            width: 100%;
            padding: 0.8rem 3.3rem 0.8rem 1rem;
            /* 1rem 4rem 1rem 1.5rem -> 0.8rem 3.3rem 0.8rem 1rem */
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            /* 16px -> 12px */
            font-size: 1rem;
            /* 1.2rem -> 1rem */
            font-weight: 600;
            transition: all 0.3s ease;
            background: white;
        }

        .harga-input:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .input-suffix {
            position: absolute;
            right: 0.75rem;
            color: #64748b;
            font-weight: 700;
            font-size: 0.9rem;
            background: #f1f5f9;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
        }

        .input-hint {
            margin-top: 0.5rem;
            color: #94a3b8;
            font-size: 0.75rem;
            /* 0.85rem -> 0.75rem */
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .text-muted {
            color: #64748b;
            font-size: 0.8rem;
        }

        /* Summary Box */
        .summary-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.4rem 0;
            font-size: 0.9rem;
        }

        .summary-row:first-child {
            border-bottom: 1px dashed #e2e8f0;
            margin-bottom: 0.4rem;
        }

        .summary-row span {
            color: #475569;
        }

        .text-warning {
            color: #f59e0b;
            font-size: 1rem;
            /* 1.2rem -> 1rem */
        }

        /* Action Buttons - DIPERKECIL */
        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .btn-reset {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem;
            /* 1rem -> 0.8rem */
            background: #f1f5f9;
            color: #64748b;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            /* 1rem -> 0.9rem */
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: #e2e8f0;
            color: #475569;
            transform: translateY(-1px);
        }

        .btn-simpan {
            flex: 2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem;
            /* 1rem -> 0.8rem */
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            /* 1rem -> 0.9rem */
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-simpan:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        /* Info Footer */
        .info-footer {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-around;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
            font-size: 0.8rem;
            /* 0.9rem -> 0.8rem */
        }

        .info-item i {
            font-size: 0.9rem;
        }

        .text-warning {
            color: #f59e0b;
        }

        .text-success {
            color: #10b981;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .harga-makanan-container {
                padding: 0.75rem;
            }

            .header-section {
                flex-direction: column;
                text-align: center;
                padding: 1rem;
            }

            .header-text h1 {
                font-size: 1.2rem;
            }

            .header-text p {
                font-size: 0.8rem;
            }

            .price-value {
                font-size: 1.8rem;
            }

            .info-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .edit-section {
                padding: 1rem;
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            // Format Rupiah untuk tampilan
            function formatRupiah(angka) {
                if (!angka) return '0';
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Update display harga baru
            function updateHargaBaru() {
                let harga = $('#harga').val().replace(/[^0-9]/g, '');
                harga = harga ? parseInt(harga) : 0;

                if (harga > 0) {
                    $('#hargaBaruDisplay').text('Rp ' + formatRupiah(harga));
                } else {
                    $('#hargaBaruDisplay').text('Rp 0');
                }
            }

            // Event: saat mengetik di input harga
            $('#harga').on('input', function() {
                let value = this.value.replace(/[^0-9]/g, '');

                if (value !== '') {
                    // Batasi maksimal 7 digit (1.000.000)
                    if (value.length > 7) {
                        value = value.slice(0, 7);
                    }
                    this.value = value;
                }

                updateHargaBaru();
            });

            // Initial update
            updateHargaBaru();

            // Tombol Reset
            $('#resetBtn').on('click', function(e) {
                e.preventDefault();
                $('#harga').val('{{ $makanan->harga }}');
                $('#keterangan').val('{{ addslashes($makanan->keterangan) }}');
                updateHargaBaru();

                Swal.fire({
                    icon: 'success',
                    title: 'Reset Berhasil',
                    text: 'Form dikembalikan ke nilai awal',
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            });

            // Submit form dengan validasi dan konfirmasi
            $('#formHarga').on('submit', function(e) {
                e.preventDefault();

                let harga = $('#harga').val().replace(/[^0-9]/g, '');
                let hargaInt = parseInt(harga);

                // Validasi
                if (!harga || hargaInt < 1000) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Harga Tidak Valid',
                        text: 'Harga minimal Rp 1.000',
                        confirmButtonColor: '#f59e0b'
                    });
                    return;
                }

                if (hargaInt > 1000000) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Harga Terlalu Besar',
                        text: 'Harga maksimal Rp 1.000.000',
                        confirmButtonColor: '#f59e0b'
                    });
                    return;
                }

                // Konfirmasi
                Swal.fire({
                    title: 'Konfirmasi Perubahan',
                    html: `Apakah Anda yakin ingin mengubah harga makanan menjadi <strong>Rp ${formatRupiah(harga)}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set value ke angka tanpa format
                        $('#harga').val(hargaInt);

                        // Tampilkan loading
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        this.submit();
                    }
                });
            });

            // Enter key pada input
            $('#harga').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#formHarga').submit();
                }
            });
        });
    </script>
@endsection
