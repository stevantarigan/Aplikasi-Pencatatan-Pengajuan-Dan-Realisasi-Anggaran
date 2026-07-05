@extends('layouts.app')

@section('title', 'Tambah Pengajuan Anggaran')
@section('icon', 'file-earmark-plus')

@section('content')
    <div class="pengajuan-create-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Tambah Pengajuan Anggaran</h2>
                <p class="page-subtitle">Buat pengajuan anggaran baru untuk kebutuhan operasional</p>
            </div>
            <a href="{{ route('admin.pengajuan.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="alert-custom alert-danger mb-4">
                <div class="alert-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="alert-content">
                    <strong>Terjadi kesalahan!</strong>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="header-icon">
                    <i class="bi bi-file-earmark-plus"></i>
                </div>
                <div class="header-title">
                    <h3>Form Pengajuan Anggaran</h3>
                    <p>Isi data pengajuan dengan lengkap dan benar</p>
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

                <form action="{{ route('admin.pengajuan.store') }}" method="POST" id="pengajuanForm">
                    @csrf

                    <div class="form-grid">
                        <!-- Nomor Pengajuan (Readonly) -->
                        <div class="form-group">
                            <label for="nomor_pengajuan" class="form-label">
                                Nomor Pengajuan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-hash"></i>
                                </span>
                                <input type="text" class="form-control bg-light" id="nomor_pengajuan_display"
                                    value="{{ $nomor_pengajuan }}" readonly>
                                <input type="hidden" name="nomor_pengajuan" value="{{ $nomor_pengajuan }}">
                            </div>
                            <small class="hint-text">
                                <i class="bi bi-info-circle"></i>
                                Nomor pengajuan otomatis
                            </small>
                        </div>

                        <!-- Anggaran -->
                        <div class="form-group">
                            <label for="anggaran_id" class="form-label">
                                Pilih Anggaran <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-cash-stack"></i>
                                </span>
                                <select name="anggaran_id" id="anggaran_id"
                                    class="form-control @error('anggaran_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Anggaran --</option>
                                    @foreach ($anggaran as $ang)
                                        <option value="{{ $ang->id }}"
                                            data-nama-rencana="{{ $ang->rencanaAnggaran->nama_rencana ?? '' }}"
                                            data-kegiatan="{{ $ang->rencanaAnggaran->nama_kegiatan ?? '' }}"
                                            data-tahun="{{ $ang->tahun }}"
                                            data-kode-rekening="{{ $ang->rencanaAnggaran->kodeRekening->kode ?? '' }}"
                                            data-nama-rekening="{{ $ang->rencanaAnggaran->kodeRekening->nama_rekening ?? '' }}"
                                            data-sisa="{{ $ang->sisa_anggaran }}" data-total="{{ $ang->total_anggaran }}"
                                            {{ old('anggaran_id') == $ang->id ? 'selected' : '' }}>
                                            {{ $ang->rencanaAnggaran->nama_rencana ?? ($ang->rencanaAnggaran->nama_kegiatan ?? 'Anggaran #' . $ang->id) }}
                                            ({{ $ang->tahun ?? '-' }})
                                            -
                                            Rp {{ number_format($ang->total_anggaran ?? 0, 0, ',', '.') }}
                                            (Sisa: Rp {{ number_format($ang->sisa_anggaran ?? 0, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('anggaran_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Info Anggaran Card -->
                        <div class="info-anggaran-card" id="infoAnggaranCard" style="display: none;">
                            <div class="info-anggaran-header">
                                <i class="bi bi-info-circle"></i>
                                <h4>Detail Anggaran Terpilih</h4>
                            </div>
                            <div class="info-anggaran-content">
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
                                    <span class="info-value" id="infoKodeRekening">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Nama Rekening:</span>
                                    <span class="info-value" id="infoNamaRekening">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Tahun:</span>
                                    <span class="info-value" id="infoTahun">-</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Total Anggaran:</span>
                                    <span class="info-value" id="infoTotal">Rp 0</span>
                                </div>
                                <div class="info-row highlight">
                                    <span class="info-label">Sisa Anggaran:</span>
                                    <span class="info-value" id="infoSisa">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Pengajuan -->
                        <div class="form-group">
                            <label for="tanggal_pengajuan" class="form-label">
                                Tanggal Pengajuan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-calendar"></i>
                                </span>
                                <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan"
                                    class="form-control @error('tanggal_pengajuan') is-invalid @enderror"
                                    value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}" required>
                            </div>
                            @error('tanggal_pengajuan')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Bulan -->
                        <div class="form-group">
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
                                    @foreach (range(1, 12) as $bulan)
                                        <option value="{{ $bulan }}"
                                            {{ old('bulan', date('n')) == $bulan ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create()->month($bulan)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('bulan')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Jumlah Diajukan -->
                        <div class="form-group">
                            <label for="jumlah_diajukan" class="form-label">
                                Jumlah Diajukan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-cash"></i>
                                </span>
                                <input type="text" name="jumlah_diajukan" id="jumlah_diajukan"
                                    class="form-control @error('jumlah_diajukan') is-invalid @enderror"
                                    value="{{ old('jumlah_diajukan') }}" placeholder="Contoh: 1.000.000" required>
                                <span class="input-suffix">Rp</span>
                            </div>
                            @error('jumlah_diajukan')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <small class="hint-text">
                                <i class="bi bi-info-circle"></i>
                                Maksimal: Rp <span id="maxJumlah">0</span>
                            </small>
                        </div>

                        <!-- Diajukan Oleh -->
                        <div class="form-group">
                            <label for="diajukan_oleh" class="form-label">
                                Diajukan Oleh <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="bi bi-person"></i>
                                </span>
                                <select name="diajukan_oleh" id="diajukan_oleh"
                                    class="form-control @error('diajukan_oleh') is-invalid @enderror" required>
                                    <option value="">Pilih Pengaju</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('diajukan_oleh', auth()->id()) == $user->id ? 'selected' : '' }}>
                                            {{ $user->nama }} ({{ $user->role_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('diajukan_oleh')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
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
                                <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                    rows="4" placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            </div>
                            @error('keterangan')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-lg"></i>
                            Simpan Pengajuan
                        </button>
                        <button type="reset" class="btn-reset" id="resetBtn">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </button>
                        <a href="{{ route('admin.pengajuan.index') }}" class="btn-cancel">
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
                    <li><i class="bi bi-check-circle text-success"></i> Nomor pengajuan dibuat otomatis oleh sistem</li>
                    <li><i class="bi bi-check-circle text-success"></i> Pilih anggaran yang masih memiliki sisa</li>
                    <li><i class="bi bi-check-circle text-success"></i> Jumlah diajukan tidak boleh melebihi sisa anggaran
                    </li>
                    <li><i class="bi bi-check-circle text-success"></i> Setelah disimpan, pengajuan akan berstatus
                        "Diajukan"</li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        .pengajuan-create-container {
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

        .btn-back {
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

        .btn-back:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateX(-5px);
            color: #1e293b;
        }

        .btn-back i {
            font-size: 1.1rem;
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

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-section {
            margin-top: 2rem;
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

        .form-control[readonly],
        .form-control.bg-light {
            background: #f1f5f9;
            cursor: not-allowed;
        }

        select.form-control {
            padding: 0.875rem 1rem 0.875rem 2.8rem;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
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

        /* Info Anggaran Card */
        .info-anggaran-card {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #7dd3fc;
            grid-column: 1 / -1;
        }

        .info-anggaran-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .info-anggaran-header i {
            font-size: 1.2rem;
            color: #0369a1;
        }

        .info-anggaran-header h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #0369a1;
            margin: 0;
        }

        .info-anggaran-content {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-row {
            display: flex;
            flex-direction: column;
        }

        .info-row.highlight {
            background: rgba(255, 255, 255, 0.7);
            padding: 0.75rem;
            border-radius: 8px;
            grid-column: 1 / -1;
            border: 1px solid #7dd3fc;
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
            .pengajuan-create-container {
                padding: 1rem;
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

            .form-grid {
                grid-template-columns: 1fr;
            }

            .info-anggaran-content {
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            const anggaranSelect = document.getElementById('anggaran_id');
            const infoCard = document.getElementById('infoAnggaranCard');
            const jumlahInput = document.getElementById('jumlah_diajukan');
            const maxJumlahSpan = document.getElementById('maxJumlah');
            const form = document.getElementById('pengajuanForm');

            // Info elements
            const infoNamaRencana = document.getElementById('infoNamaRencana');
            const infoKegiatan = document.getElementById('infoKegiatan');
            const infoKodeRekening = document.getElementById('infoKodeRekening');
            const infoNamaRekening = document.getElementById('infoNamaRekening');
            const infoTahun = document.getElementById('infoTahun');
            const infoTotal = document.getElementById('infoTotal');
            const infoSisa = document.getElementById('infoSisa');

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

            // Format rupiah on input
            jumlahInput.addEventListener('keyup', function() {
                formatRupiahInput(this);
            });

            // Update info saat memilih anggaran
            anggaranSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];

                if (this.value) {
                    const namaRencana = selected.dataset.namaRencana || '-';
                    const kegiatan = selected.dataset.kegiatan || '-';
                    const kodeRekening = selected.dataset.kodeRekening || '-';
                    const namaRekening = selected.dataset.namaRekening || '-';
                    const tahun = selected.dataset.tahun || '-';
                    const total = selected.dataset.total || 0;
                    const sisa = selected.dataset.sisa || 0;

                    infoNamaRencana.textContent = namaRencana;
                    infoKegiatan.textContent = kegiatan;
                    infoKodeRekening.textContent = kodeRekening;
                    infoNamaRekening.textContent = namaRekening;
                    infoTahun.textContent = tahun;
                    infoTotal.textContent = 'Rp ' + formatRupiah(total);
                    infoSisa.textContent = 'Rp ' + formatRupiah(sisa);
                    maxJumlahSpan.textContent = formatRupiah(sisa);

                    infoCard.style.display = 'block';
                } else {
                    infoCard.style.display = 'none';
                    maxJumlahSpan.textContent = '0';
                }
            });

            // Validasi jumlah tidak melebihi sisa
            jumlahInput.addEventListener('keyup', function() {
                const jumlah = parseRupiah(this.value);
                const sisa = anggaranSelect.value ? parseRupiah(maxJumlahSpan.textContent) : 0;

                if (jumlah > sisa) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            // Form validation sebelum submit
            form.addEventListener('submit', function(e) {
                const jumlah = parseRupiah(jumlahInput.value);
                const sisa = anggaranSelect.value ? parseRupiah(maxJumlahSpan.textContent) : 0;

                if (!anggaranSelect.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Silakan pilih anggaran terlebih dahulu'
                    });
                    return;
                }

                if (jumlah <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Jumlah pengajuan harus lebih dari 0'
                    });
                    return;
                }

                if (jumlah > sisa) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Jumlah pengajuan tidak boleh melebihi sisa anggaran (Rp ' +
                            formatRupiah(sisa) + ')'
                    });
                    return;
                }

                // Hapus titik sebelum submit
                jumlahInput.value = jumlah;

                // Disable submit button
                const submitBtn = document.querySelector('.btn-save');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
            });

            // Reset button dengan konfirmasi
            $('#resetBtn').on('click', function(e) {
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
                    }
                });
            });

            // Trigger change if there's old value
            if (anggaranSelect.value) {
                anggaranSelect.dispatchEvent(new Event('change'));
            }

            // Format old value jika ada
            @if (old('jumlah_diajukan'))
                let oldValue = '{{ old('jumlah_diajukan') }}';
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
                    }
                }
            @endif
        });
    </script>
@endsection
