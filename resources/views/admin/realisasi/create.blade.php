@extends('layouts.app')

@section('title', 'Tambah Realisasi Anggaran')
@section('icon', 'plus-circle')

@section('content')
    <div class="realisasi-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Tambah Realisasi Anggaran</h2>
                <p class="page-subtitle">Formulir penambahan realisasi dari pengajuan yang telah disetujui</p>
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
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div class="header-title">
                    <h3>Form Realisasi Anggaran</h3>
                    <p>Isi data realisasi dengan lengkap dan benar</p>
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

                <form action="{{ route('admin.realisasi.store') }}" method="POST" id="formRealisasi">
                    @csrf

                    <div class="form-grid">
                        <!-- Pilih Pengajuan -->
                        <div class="form-group full-width">
                            <label for="pengajuan_id" class="form-label">
                                Pilih Pengajuan <span class="text-danger">*</span>
                            </label>
                            <select name="pengajuan_id" id="pengajuan_id"
                                class="form-control @error('pengajuan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Pengajuan yang Telah Disetujui --</option>
                                @forelse($pengajuan as $item)
                                    <option value="{{ $item->id }}" data-nomor="{{ $item->nomor_pengajuan }}"
                                        data-nama-rencana="{{ $item->anggaran->rencanaAnggaran->nama_rencana ?? '' }}"
                                        data-kegiatan="{{ $item->anggaran->rencanaAnggaran->nama_kegiatan ?? '' }}"
                                        data-kode="{{ $item->anggaran->rencanaAnggaran->kodeRekening->kode ?? '' }}"
                                        data-nama-rekening="{{ $item->anggaran->rencanaAnggaran->kodeRekening->nama_rekening ?? '' }}"
                                        data-jumlah="{{ $item->jumlah_diajukan }}" data-bulan="{{ $item->nama_bulan }}"
                                        data-tahun="{{ $item->anggaran->tahun ?? '' }}"
                                        {{ old('pengajuan_id') == $item->id ? 'selected' : '' }}>
                                        [{{ $item->nomor_pengajuan }}]
                                        {{ $item->anggaran->rencanaAnggaran->nama_rencana ?? ($item->anggaran->rencanaAnggaran->nama_kegiatan ?? 'Kegiatan') }}
                                        - {{ $item->nama_bulan }} {{ $item->anggaran->tahun ?? '' }} -
                                        Rp {{ number_format($item->jumlah_diajukan, 0, ',', '.') }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada pengajuan yang siap direalisasi</option>
                                @endforelse
                            </select>
                            @error('pengajuan_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            @if ($pengajuan->isEmpty())
                                <small class="hint-text text-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Tidak ada pengajuan dengan status "Disetujui" yang siap direalisasi.
                                </small>
                            @endif
                        </div>

                        <!-- Info Pengajuan Card -->
                        <div class="info-pengajuan-card" id="infoPengajuanCard" style="display: none;">
                            <div class="info-pengajuan-header">
                                <i class="bi bi-info-circle"></i>
                                <h4>Informasi Pengajuan Terpilih</h4>
                            </div>
                            <div class="info-pengajuan-content">
                                <div class="info-row">
                                    <span class="info-label">Nomor Pengajuan:</span>
                                    <span class="info-value" id="infoNomor">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Nama Rencana:</span>
                                    <span class="info-value" id="infoNamaRencana">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Kegiatan:</span>
                                    <span class="info-value" id="infoKegiatan">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Kode Rekening:</span>
                                    <span class="info-value" id="infoKode">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Nama Rekening:</span>
                                    <span class="info-value" id="infoNamaRekening">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Periode:</span>
                                    <span class="info-value" id="infoPeriode">-</span>
                                </div>
                                <div class="info-row highlight">
                                    <span class="info-label">Jumlah Diajukan:</span>
                                    <span class="info-value" id="infoJumlah">Rp 0</span>
                                </div>
                            </div>
                        </div>

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
                                    value="{{ old('jumlah_realisasi') }}" placeholder="Masukkan jumlah realisasi" required>
                                <span class="input-suffix">Rp</span>
                            </div>
                            @error('jumlah_realisasi')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <small class="hint-text">
                                <i class="bi bi-info-circle"></i>
                                Maksimal: Rp <span id="maxJumlah">0</span>
                            </small>
                            <small class="hint-text text-danger" id="warningMelebihi" style="display: none;">
                                <i class="bi bi-exclamation-triangle"></i>
                                Jumlah realisasi melebihi yang diajukan!
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
                                    value="{{ old('tanggal_realisasi', date('Y-m-d')) }}" required>
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
                            <div class="input-wrapper">
                                <i class="bi bi-pencil input-icon"></i>
                                <textarea name="keterangan_realisasi" id="keterangan_realisasi"
                                    class="form-control @error('keterangan_realisasi') is-invalid @enderror" rows="4"
                                    placeholder="Masukkan keterangan realisasi (opsional)">{{ old('keterangan_realisasi') }}</textarea>
                            </div>
                            @error('keterangan_realisasi')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn-save" id="submitBtn">
                            <i class="bi bi-check-lg"></i>
                            Simpan Realisasi
                        </button>
                        <button type="reset" class="btn-reset" id="resetBtn">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
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
                <i class="bi bi-lightbulb"></i>
            </div>
            <div class="info-content">
                <h5>Informasi Pengisian Form</h5>
                <ul>
                    <li><i class="bi bi-check-circle text-success"></i> Tanda <span class="text-danger">*</span> (bintang
                        merah) menandakan field wajib diisi</li>
                    <li><i class="bi bi-check-circle text-success"></i> Pilih pengajuan yang sudah disetujui untuk
                        direalisasi</li>
                    <li><i class="bi bi-check-circle text-success"></i> <strong>Jumlah realisasi tidak boleh melebihi
                            jumlah yang diajukan</strong></li>
                    <li><i class="bi bi-check-circle text-success"></i> Data realisasi akan mengubah status pengajuan
                        menjadi "Direalisasi"</li>
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

        .input-suffix {
            position: absolute;
            right: 1rem;
            color: #64748b;
            font-weight: 600;
            font-size: 0.9rem;
            background: #f1f5f9;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            z-index: 10;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 3rem 0.875rem 2.8rem;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        select.form-control {
            padding: 0.875rem 1rem 0.875rem 2.8rem;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #6d5dfc;
            box-shadow: 0 0 0 4px rgba(109, 93, 252, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
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

        .hint-text.text-danger {
            color: #ef4444;
        }

        /* Info Pengajuan Card */
        .info-pengajuan-card {
            background: linear-gradient(135deg, #e0f2fe, #bae6fd);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #7dd3fc;
            grid-column: 1 / -1;
        }

        .info-pengajuan-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .info-pengajuan-header i {
            font-size: 1.2rem;
            color: #0369a1;
        }

        .info-pengajuan-header h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #0369a1;
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

        .btn-save:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(109, 93, 252, 0.3);
        }

        .btn-save:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pengajuanSelect = document.getElementById('pengajuan_id');
            const infoCard = document.getElementById('infoPengajuanCard');
            const jumlahInput = document.getElementById('jumlah_realisasi');
            const maxJumlahSpan = document.getElementById('maxJumlah');
            const warningMelebihi = document.getElementById('warningMelebihi');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('formRealisasi');
            const resetBtn = document.getElementById('resetBtn');

            // Info elements
            const infoNomor = document.getElementById('infoNomor');
            const infoNamaRencana = document.getElementById('infoNamaRencana');
            const infoKegiatan = document.getElementById('infoKegiatan');
            const infoKode = document.getElementById('infoKode');
            const infoNamaRekening = document.getElementById('infoNamaRekening');
            const infoPeriode = document.getElementById('infoPeriode');
            const infoJumlah = document.getElementById('infoJumlah');

            // Format Rupiah
            function formatRupiah(angka) {
                if (!angka) return '0';
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

            // Validasi jumlah realisasi
            function validateJumlah() {
                const jumlah = parseRupiah(jumlahInput.value);
                const max = pengajuanSelect.value ? parseRupiah(maxJumlahSpan.textContent) : 0;

                if (jumlah > max) {
                    jumlahInput.classList.add('is-invalid');
                    warningMelebihi.style.display = 'block';
                    submitBtn.disabled = true;
                    return false;
                } else {
                    jumlahInput.classList.remove('is-invalid');
                    warningMelebihi.style.display = 'none';
                    submitBtn.disabled = false;
                    return true;
                }
            }

            // Update info saat memilih pengajuan
            pengajuanSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];

                if (this.value) {
                    const nomor = selected.dataset.nomor || '-';
                    const namaRencana = selected.dataset.namaRencana || '-';
                    const kegiatan = selected.dataset.kegiatan || '-';
                    const kode = selected.dataset.kode || '-';
                    const namaRekening = selected.dataset.namaRekening || '-';
                    const bulan = selected.dataset.bulan || '-';
                    const tahun = selected.dataset.tahun || '-';
                    const jumlah = selected.dataset.jumlah || 0;

                    infoNomor.textContent = nomor;
                    infoNamaRencana.textContent = namaRencana;
                    infoKegiatan.textContent = kegiatan;
                    infoKode.textContent = kode;
                    infoNamaRekening.textContent = namaRekening;
                    infoPeriode.textContent = bulan + ' ' + tahun;
                    infoJumlah.textContent = 'Rp ' + formatRupiah(jumlah);
                    maxJumlahSpan.textContent = formatRupiah(jumlah);

                    infoCard.style.display = 'block';

                    // Validasi ulang setelah memilih pengajuan
                    validateJumlah();
                } else {
                    infoCard.style.display = 'none';
                    maxJumlahSpan.textContent = '0';
                    jumlahInput.classList.remove('is-invalid');
                    warningMelebihi.style.display = 'none';
                    submitBtn.disabled = true;
                }
            });

            // Format rupiah on input dan validasi
            jumlahInput.addEventListener('keyup', function() {
                formatRupiahInput(this);
                validateJumlah();
            });

            // Form validation sebelum submit
            form.addEventListener('submit', function(e) {
                const jumlah = parseRupiah(jumlahInput.value);
                const max = pengajuanSelect.value ? parseRupiah(maxJumlahSpan.textContent) : 0;

                if (!pengajuanSelect.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Silakan pilih pengajuan terlebih dahulu'
                    });
                    return;
                }

                if (jumlah <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Jumlah realisasi harus lebih dari 0'
                    });
                    return;
                }

                if (jumlah > max) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Jumlah realisasi tidak boleh melebihi Rp ' + formatRupiah(max)
                    });
                    return;
                }

                // Hapus titik sebelum submit
                jumlahInput.value = jumlah;

                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
            });

            // Reset button dengan konfirmasi
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Reset Form',
                    text: 'Apakah Anda yakin ingin mereset semua data yang sudah diisi?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6d5dfc',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Reset',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.reset();
                        infoCard.style.display = 'none';
                        maxJumlahSpan.textContent = '0';
                        jumlahInput.classList.remove('is-invalid');
                        warningMelebihi.style.display = 'none';
                        submitBtn.disabled = true;
                    }
                });
            });

            // Trigger change if there's old value
            if (pengajuanSelect.value) {
                pengajuanSelect.dispatchEvent(new Event('change'));

                // Format old value jika ada
                @if (old('jumlah_realisasi'))
                    let oldValue = '{{ old('jumlah_realisasi') }}';
                    if (oldValue && !isNaN(oldValue)) {
                        let numbers = oldValue.replace(/[^0-9]/g, '');
                        if (numbers) {
                            let formatted = '';
                            for (let i = 0; i < numbers.length; i++) {
                                if (i > 0 && (numbers.length - i) % 3 === 0) {
                                    formatted += '.';
                                }
                                formatted += numbers[i];
                            }
                            jumlahInput.value = formatted;
                            validateJumlah();
                        }
                    }
                @endif
            } else {
                submitBtn.disabled = true;
            }
        });
    </script>
@endsection
