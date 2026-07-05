@extends('layouts.app')

@section('title', 'Tambah Anggaran')
@section('icon', 'plus-circle')

@section('content')
    <div class="anggaran-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Tambah Anggaran</h2>
                <p class="page-subtitle">Buat anggaran baru dari rencana yang telah disetujui</p>
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
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div class="header-title">
                    <h3>Form Tambah Anggaran</h3>
                    <p>Isi data anggaran dengan benar</p>
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

                @if ($rencanaDisetujui->isEmpty())
                    <div class="alert-custom alert-warning">
                        <div class="alert-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Tidak ada rencana yang tersedia!</strong>
                            <p class="mb-0 mt-2">Semua rencana yang disetujui sudah memiliki anggaran. Silakan buat rencana
                                baru terlebih dahulu.</p>
                        </div>
                    </div>
                @else
                    <form action="{{ route('admin.anggaran.store') }}" method="POST" id="formAnggaran">
                        @csrf

                        <div class="form-grid">
                            <!-- Pilih Rencana -->
                            <div class="form-group full-width">
                                <label for="rencana_anggaran_id" class="form-label">
                                    Pilih Rencana <span class="text-danger">*</span>
                                </label>
                                <select name="rencana_anggaran_id" id="rencana_anggaran_id"
                                    class="form-control @error('rencana_anggaran_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Rencana yang Telah Disetujui --</option>
                                    @foreach ($rencanaDisetujui as $rencana)
                                        <option value="{{ $rencana->id }}" data-tahun="{{ $rencana->tahun }}"
                                            data-total="{{ $rencana->total_anggaran }}"
                                            data-kode="{{ $rencana->kodeRekening->kode ?? '' }}"
                                            data-nama="{{ $rencana->nama_rencana }}"
                                            {{ old('rencana_anggaran_id') == $rencana->id ? 'selected' : '' }}>
                                            [{{ $rencana->tahun }}] {{ $rencana->kodeRekening->kode ?? '' }} -
                                            {{ $rencana->nama_rencana }} (Rp
                                            {{ number_format($rencana->total_anggaran, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('rencana_anggaran_id')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Info Rencana Card -->
                            <div class="info-rencana-card" id="infoRencanaCard" style="display: none;">
                                <div class="info-rencana-header">
                                    <i class="bi bi-info-circle"></i>
                                    <h4>Informasi Rencana Terpilih</h4>
                                </div>
                                <div class="info-rencana-content">
                                    <div class="info-row">
                                        <span class="info-label">Kode Rekening:</span>
                                        <span class="info-value" id="infoKode">-</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Nama Rencana:</span>
                                        <span class="info-value" id="infoNama">-</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Tahun:</span>
                                        <span class="info-value" id="infoTahun">-</span>
                                    </div>
                                    <div class="info-row highlight">
                                        <span class="info-label">Total Anggaran Rencana:</span>
                                        <span class="info-value" id="infoTotal">Rp 0</span>
                                    </div>
                                </div>
                            </div>

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
                                        value="{{ old('tahun') }}" min="2000" max="2099"
                                        placeholder="Masukkan tahun" required>
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
                                        value="{{ old('total_anggaran') }}" placeholder="Masukkan total anggaran" required>
                                </div>
                                @error('total_anggaran')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                                <small class="hint-text">
                                    <i class="bi bi-info-circle"></i>
                                    Sisa anggaran awal akan sama dengan total anggaran
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
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                    </option>
                                </select>
                                @error('status')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="bi bi-check-lg"></i>
                                Simpan Anggaran
                            </button>
                            <button type="reset" class="btn-reset">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Reset
                            </button>
                            <a href="{{ route('admin.anggaran.index') }}" class="btn-cancel">
                                <i class="bi bi-x-lg"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-lightbulb"></i>
            </div>
            <div class="info-content">
                <h5>Informasi Pengisian Form</h5>
                <ul>
                    <li><i class="bi bi-check-circle text-success"></i> Tanda <span class="text-danger">*</span> (bintang
                        merah) menandakan field wajib diisi</li>
                    <li><i class="bi bi-check-circle text-success"></i> Anggaran dibuat berdasarkan rencana yang sudah
                        disetujui</li>
                    <li><i class="bi bi-check-circle text-success"></i> Sisa anggaran awal akan sama dengan total anggaran
                    </li>
                    <li><i class="bi bi-check-circle text-success"></i> Status "Aktif" berarti anggaran dapat digunakan
                        untuk pengajuan</li>
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
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
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

        .alert-warning {
            background: #fffbeb;
            border-left-color: #f59e0b;
        }

        .alert-icon {
            font-size: 1.5rem;
            color: #dc2626;
        }

        .alert-warning .alert-icon {
            color: #f59e0b;
        }

        .alert-content {
            flex: 1;
        }

        .alert-content strong {
            color: #991b1b;
        }

        .alert-warning .alert-content strong {
            color: #92400e;
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
            border-color: #6d5dfc;
            box-shadow: 0 0 0 4px rgba(109, 93, 252, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
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

        /* Info Rencana Card */
        .info-rencana-card {
            background: linear-gradient(135deg, #e0f2fe, #bae6fd);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #7dd3fc;
            grid-column: 1 / -1;
        }

        .info-rencana-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .info-rencana-header i {
            font-size: 1.2rem;
            color: #0369a1;
        }

        .info-rencana-header h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #0369a1;
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
            color: #0369a1;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #0c4a6e;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn-save {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
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
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(109, 93, 252, 0.3);
        }

        .btn-reset {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 0.875rem 2rem;
            border-radius: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-reset:hover {
            background: #e2e8f0;
            color: #1e293b;
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
        }

        .btn-cancel:hover {
            background: #fecaca;
            color: #b91c1c;
        }

        /* Info Card */
        .info-card {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            border: 1px solid #bae6fd;
        }

        .info-icon {
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

        .info-content {
            flex: 1;
        }

        .info-content h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #0369a1;
            margin-bottom: 0.5rem;
        }

        .info-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-content ul li {
            color: #075985;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

            .btn-save,
            .btn-reset,
            .btn-cancel {
                width: 100%;
                justify-content: center;
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
            const rencanaSelect = document.getElementById('rencana_anggaran_id');
            const infoCard = document.getElementById('infoRencanaCard');
            const tahunInput = document.getElementById('tahun');
            const totalInput = document.getElementById('total_anggaran');
            const form = document.getElementById('formAnggaran');

            // Info elements
            const infoKode = document.getElementById('infoKode');
            const infoNama = document.getElementById('infoNama');
            const infoTahun = document.getElementById('infoTahun');
            const infoTotal = document.getElementById('infoTotal');

            // Format Rupiah
            function formatRupiah(angka) {
                if (!angka) return '0';
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

            // Update info saat memilih rencana
            rencanaSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];

                if (this.value) {
                    const tahun = selected.dataset.tahun || '';
                    const total = selected.dataset.total || 0;
                    const kode = selected.dataset.kode || '-';
                    const nama = selected.dataset.nama || '-';

                    infoKode.textContent = kode;
                    infoNama.textContent = nama;
                    infoTahun.textContent = tahun;
                    infoTotal.textContent = 'Rp ' + formatRupiah(total);

                    infoCard.style.display = 'block';

                    // Set default values
                    tahunInput.value = tahun;
                    totalInput.value = formatRupiah(total);
                } else {
                    infoCard.style.display = 'none';
                    tahunInput.value = '';
                    totalInput.value = '';
                }
            });

            // Format rupiah on input
            totalInput.addEventListener('keyup', function() {
                formatRupiahInput(this);
            });

            // Form validation
            form.addEventListener('submit', function(e) {
                if (!rencanaSelect.value) {
                    e.preventDefault();
                    alert('Silakan pilih rencana terlebih dahulu');
                    return;
                }

                const total = parseRupiah(totalInput.value);
                if (total <= 0) {
                    e.preventDefault();
                    alert('Total anggaran harus lebih dari 0');
                    return;
                }
            });

            // Trigger change if there's old value
            if (rencanaSelect.value) {
                rencanaSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
