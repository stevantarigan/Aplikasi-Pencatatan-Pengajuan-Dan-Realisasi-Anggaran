{{-- resources/views/admin/rencana/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Tambah Rencana Anggaran')
@section('icon', 'journal-plus')

@section('content')
    <div class="create-rencana-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Tambah Rencana Anggaran</h2>
                <p class="page-subtitle">Buat rencana anggaran baru untuk kebutuhan operasional</p>
            </div>
            <a href="{{ route('admin.rencana.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Info Harga Makanan -->
        <div class="info-harga-card">
            <div class="info-harga-icon">
                <i class="bi bi-cup-hot-fill"></i>
            </div>
            <div class="info-harga-content">
                <span>Harga makanan per pasien saat ini:</span>
                <strong id="hargaMakananDisplay">Rp 50.000</strong>
                <a href="{{ route('admin.makanan.index') }}" class="btn-ubah-harga">
                    <i class="bi bi-pencil"></i>
                    Ubah
                </a>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="form-card">
            <form action="{{ route('admin.rencana.store') }}" method="POST" id="rencanaForm">
                @csrf

                <div class="form-body">
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
                                                    {{ old('kode_rekening_id') == $rekening->id ? 'selected' : '' }}>
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
                                        Tahun Rencana <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="bi bi-calendar-year input-icon"></i>
                                        <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                            id="tahun" name="tahun" value="{{ old('tahun', date('Y') + 1) }}"
                                            min="2000" max="2099" placeholder="Contoh: {{ date('Y') + 1 }}"
                                            required>
                                        <span class="input-suffix" id="checkingYear" style="display: none;">
                                            <i class="bi bi-hourglass-split"></i>
                                        </span>
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
                                            id="nama_rencana" name="nama_rencana" value="{{ old('nama_rencana') }}"
                                            placeholder="Contoh: Rencana Pembangunan Gedung" required>
                                    </div>
                                    @error('nama_rencana')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- REKOMENDASI ANGGARAN BERDASARKAN DATA HISTORIS -->
                    <div class="form-section rekomendasi-section" id="rekomendasiSection" style="display: none;">
                        <h3 class="section-title">
                            <i class="bi bi-cpu"></i>
                            Rekomendasi Anggaran
                        </h3>
                        <div class="section-divider"></div>

                        <div class="rekomendasi-card">
                            <div class="rekomendasi-header">
                                <div class="rekomendasi-icon">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                                <div class="rekomendasi-title">
                                    <h4>Rekomendasi Berdasarkan Data Historis</h4>
                                    <p>Menggunakan data pasien tahun <span id="tahunDataHistoris"></span> untuk rencana
                                        tahun <span id="tahunRencana"></span></p>
                                </div>
                                <div class="rekomendasi-badge">
                                    <span class="badge-rekomendasi">Historis</span>
                                </div>
                            </div>

                            <div class="rekomendasi-body">
                                <!-- Loading -->
                                <div id="rekomendasiLoading" style="display: none;">
                                    <div class="loading-spinner"></div>
                                    <p>Menganalisis data historis...</p>
                                </div>

                                <!-- Data Tidak Ada -->
                                <div id="rekomendasiNoData" style="display: none;">
                                    <div class="no-data-icon">
                                        <i class="bi bi-database-x"></i>
                                    </div>
                                    <h5>Tidak Ada Data Historis</h5>
                                    <p>Belum ada data pasien untuk tahun <span id="noDataTahun"></span></p>
                                    <p class="text-muted small">Data historis diperlukan untuk memberikan rekomendasi</p>
                                    <small class="text-muted">Anda tetap dapat mengisi anggaran secara manual</small>
                                </div>

                                <!-- Data Ada -->
                                <div id="rekomendasiData" style="display: none;">
                                    <div class="rekomendasi-stats">
                                        <div class="stat-item">
                                            <div class="stat-label">
                                                <i class="bi bi-people-fill"></i>
                                                Total Pasien <span id="tahunTotalPasien"></span>
                                            </div>
                                            <div class="stat-value" id="totalPasien">0</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-label">
                                                <i class="bi bi-calendar-week"></i>
                                                Rata-rata/Bulan
                                            </div>
                                            <div class="stat-value" id="rataRataPerBulan">0</div>
                                        </div>
                                    </div>

                                    <div class="rekomendasi-kalkulasi">
                                        <div class="kalkulasi-item">
                                            <span>Total pasien tahun <span id="kalkulasiTahunHistoris"></span></span>
                                            <span class="kalkulasi-value" id="totalPasienValue">0</span>
                                        </div>
                                        <div class="kalkulasi-item">
                                            <span>Dibagi 12 bulan</span>
                                            <span class="kalkulasi-value">÷ 12</span>
                                        </div>
                                        <div class="kalkulasi-item hasil">
                                            <span>Rata-rata pasien per bulan</span>
                                            <span class="kalkulasi-value" id="rataRataValue">0</span>
                                        </div>
                                        <div class="kalkulasi-item">
                                            <span>Dikalikan harga makanan</span>
                                            <span class="kalkulasi-value" id="hargaMakananKalkulasi">× Rp 50.000</span>
                                        </div>
                                        <div class="kalkulasi-item hasil">
                                            <span>Anggaran per bulan</span>
                                            <span class="kalkulasi-value" id="anggaranPerBulan">Rp 0</span>
                                        </div>
                                        <div class="kalkulasi-item">
                                            <span>Dikalikan 12 bulan</span>
                                            <span class="kalkulasi-value">× 12</span>
                                        </div>
                                        <div class="kalkulasi-divider"></div>
                                        <div class="kalkulasi-item total">
                                            <span>Rekomendasi Anggaran untuk Tahun <span
                                                    id="tahunRekomendasi"></span></span>
                                            <span class="kalkulasi-value" id="anggaranPerTahun">Rp 0</span>
                                        </div>
                                    </div>

                                    <div class="rekomendasi-action">
                                        <button type="button" class="btn-use-rekomendasi" id="useRekomendasi">
                                            <i class="bi bi-check2-circle"></i>
                                            Gunakan Rekomendasi Ini
                                        </button>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i>
                                            Berdasarkan data historis tahun sebelumnya
                                        </small>
                                    </div>
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
                                            value="{{ old('total_anggaran') }}"
                                            placeholder="Contoh: 1000000 atau 1.000.000" required>
                                        <span class="input-suffix">Rp</span>
                                    </div>
                                    @error('total_anggaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i>
                                        Masukkan angka, otomatis akan muncul titik sebagai pemisah ribuan. Contoh: 1.000.000
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
                                    rows="4" placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
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
                                    <li><strong>Tahun Rencana:</strong> Masukkan tahun anggaran yang direncanakan</li>
                                    <li><strong>Nama Rencana:</strong> Gunakan nama yang jelas dan spesifik</li>
                                    <li><strong>Total Anggaran:</strong> Masukkan nominal dalam Rupiah, otomatis akan
                                        diformat dengan titik</li>
                                    <li><strong>Rekomendasi:</strong> Berdasarkan data historis tahun sebelumnya dengan
                                        harga makanan saat ini</li>
                                    <li><strong>Harga Makanan:</strong> Dapat diubah di menu "Harga Makanan"</li>
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
                        <button type="reset" class="btn-cancel" id="resetBtn">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="bi bi-check-lg"></i>
                            Simpan Rencana
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .create-rencana-container {
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
            margin-bottom: 1.5rem;
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

        /* Info Harga Makanan */
        .info-harga-card {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
            box-shadow: 0 5px 15px rgba(245, 158, 11, 0.2);
        }

        .info-harga-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-harga-icon i {
            font-size: 1.2rem;
        }

        .info-harga-content {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .info-harga-content span {
            font-size: 0.95rem;
        }

        .info-harga-content strong {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .btn-ubah-harga {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.4rem 1rem;
            background: white;
            color: #f59e0b;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-left: auto;
        }

        .btn-ubah-harga:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
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

        /* REKOMENDASI SECTION */
        .rekomendasi-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            color: white;
        }

        .rekomendasi-section .section-title {
            color: white;
            margin-bottom: 0.5rem;
        }

        .rekomendasi-section .section-title i {
            color: white;
        }

        .rekomendasi-section .section-divider {
            background: linear-gradient(90deg, white, rgba(255, 255, 255, 0.5));
        }

        .rekomendasi-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .rekomendasi-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f6f9fc 0%, #e6f0f5 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        .rekomendasi-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rekomendasi-icon i {
            font-size: 24px;
            color: white;
        }

        .rekomendasi-title {
            flex: 1;
        }

        .rekomendasi-title h4 {
            color: #2d3748;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 0.25rem 0;
        }

        .rekomendasi-title p {
            color: #718096;
            font-size: 0.9rem;
            margin: 0;
        }

        .rekomendasi-badge {
            background: rgba(102, 126, 234, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 30px;
            border: 1px solid #667eea;
        }

        .badge-rekomendasi {
            color: #667eea;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .rekomendasi-body {
            padding: 2rem;
        }

        /* Loading */
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #rekomendasiLoading {
            text-align: center;
            color: #718096;
        }

        /* No Data */
        #rekomendasiNoData {
            text-align: center;
            padding: 2rem;
        }

        .no-data-icon {
            width: 80px;
            height: 80px;
            background: #f7fafc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .no-data-icon i {
            font-size: 40px;
            color: #a0aec0;
        }

        #rekomendasiNoData h5 {
            color: #2d3748;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        #rekomendasiNoData p {
            color: #718096;
            margin-bottom: 0.5rem;
        }

        /* Stats */
        .rekomendasi-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            background: #f7fafc;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
        }

        .stat-label {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-label i {
            margin-right: 0.5rem;
        }

        .stat-value {
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 700;
        }

        /* Kalkulasi */
        .rekomendasi-kalkulasi {
            background: #f7fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .kalkulasi-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            color: #4a5568;
        }

        .kalkulasi-item.hasil {
            border-bottom: 2px dashed #cbd5e0;
            margin-bottom: 0.5rem;
            padding-bottom: 1rem;
            font-weight: 600;
            color: #2d3748;
        }

        .kalkulasi-item.total {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2d3748;
        }

        .kalkulasi-value {
            font-weight: 600;
        }

        .kalkulasi-item.hasil .kalkulasi-value {
            color: #667eea;
        }

        .kalkulasi-item.total .kalkulasi-value {
            color: #667eea;
            font-size: 1.4rem;
        }

        .kalkulasi-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #cbd5e0, transparent);
            margin: 1rem 0;
        }

        /* Action Button */
        .rekomendasi-action {
            text-align: center;
        }

        .btn-use-rekomendasi {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .btn-use-rekomendasi:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-use-rekomendasi i {
            font-size: 1.2rem;
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
            .create-rencana-container {
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

            .info-harga-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-ubah-harga {
                margin-left: 0;
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

            .rekomendasi-stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .action-right {
                flex-direction: column;
            }

            .rekomendasi-header {
                flex-direction: column;
                text-align: center;
            }

            .rekomendasi-title {
                text-align: center;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // =============================================
            // AMBIL HARGA MAKANAN DARI SERVER
            // =============================================
            let hargaMakanan = 50000; // Default

            $.ajax({
                url: '{{ route('admin.makanan.get-harga') }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        hargaMakanan = response.harga;
                        $('#hargaMakananDisplay').text('Rp ' + formatRupiah(hargaMakanan));
                        $('#hargaMakananKalkulasi').html('× Rp ' + formatRupiah(hargaMakanan));
                    }
                }
            });

            // =============================================
            // FORMAT RUPIAH DENGAN TITIK - LANGSUNG SAAT MENGETIK
            // =============================================
            function formatRupiah(angka) {
                if (!angka) return '0';
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

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

            // Format jika ada old value dari server (saat validasi gagal)
            @if (old('total_anggaran'))
                let oldValue = '{{ old('total_anggaran') }}';
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
            // CEK REKOMENDASI SAAT TAHUN BERGANTI (DATA HISTORIS)
            // =============================================
            let rekomendasiTimer;

            $('#tahun').on('input', function() {
                let tahunRencana = $(this).val();

                // Hanya cek jika tahun sudah 4 digit
                if (tahunRencana.length === 4) {
                    // Clear timer sebelumnya
                    clearTimeout(rekomendasiTimer);

                    // Tampilkan loading di input
                    $('#checkingYear').show();

                    // Timer untuk menghindari terlalu banyak request
                    rekomendasiTimer = setTimeout(function() {
                        cekRekomendasi(tahunRencana);
                    }, 500);
                } else {
                    // Sembunyikan section rekomendasi
                    $('#rekomendasiSection').slideUp();
                }
            });

            // Cek rekomendasi untuk tahun default (tahun depan)
            let tahunDefault = $('#tahun').val();
            if (tahunDefault && tahunDefault.length === 4) {
                cekRekomendasi(tahunDefault);
            }

            function cekRekomendasi(tahunRencana) {
                // Gunakan data historis (tahun sebelumnya)
                let tahunData = parseInt(tahunRencana) - 1;

                $.ajax({
                    url: '{{ route('admin.rencana.rekomendasi') }}',
                    type: 'GET',
                    data: {
                        tahun: tahunData
                    },
                    beforeSend: function() {
                        $('#rekomendasiSection').slideDown();
                        $('#rekomendasiLoading').show();
                        $('#rekomendasiNoData').hide();
                        $('#rekomendasiData').hide();
                    },
                    success: function(response) {
                        $('#checkingYear').hide();

                        if (response.success) {
                            if (response.data.ada_data) {
                                // Update teks tahun
                                $('#tahunDataHistoris').text(tahunData);
                                $('#tahunRencana').text(tahunRencana);
                                $('#tahunTotalPasien').text(tahunData);
                                $('#kalkulasiTahunHistoris').text(tahunData);
                                $('#tahunRekomendasi').text(tahunRencana);
                                $('#noDataTahun').text(tahunData);

                                // Update statistik
                                $('#totalPasien').text(response.data.total_pasien_formatted +
                                ' pasien');
                                $('#rataRataPerBulan').text(response.data
                                    .rata_rata_per_bulan_formatted + ' pasien');

                                // Update kalkulasi
                                $('#totalPasienValue').text(response.data.total_pasien_formatted +
                                    ' pasien');
                                $('#rataRataValue').text(response.data.rata_rata_per_bulan_formatted +
                                    ' pasien');

                                // Hitung ulang anggaran dengan harga makanan terbaru
                                let anggaranPerBulan = response.data.rata_rata_per_bulan * hargaMakanan;
                                let anggaranPerTahun = anggaranPerBulan * 12;

                                $('#anggaranPerBulan').text('Rp ' + formatRupiah(Math.round(
                                    anggaranPerBulan)));
                                $('#anggaranPerTahun').text('Rp ' + formatRupiah(Math.round(
                                    anggaranPerTahun)));

                                // Tampilkan data
                                $('#rekomendasiLoading').hide();
                                $('#rekomendasiData').show();
                            } else {
                                $('#noDataTahun').text(tahunData);
                                $('#rekomendasiLoading').hide();
                                $('#rekomendasiNoData').show();
                            }
                        }
                    },
                    error: function() {
                        $('#checkingYear').hide();
                        $('#rekomendasiLoading').hide();

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengambil data rekomendasi'
                        });
                    }
                });
            }

            // =============================================
            // GUNAKAN REKOMENDASI
            // =============================================
            $('#useRekomendasi').on('click', function() {
                let anggaranPerTahun = $('#anggaranPerTahun').text();

                // Ambil angka saja dari format Rupiah
                let numbers = anggaranPerTahun.replace(/[^0-9]/g, '');

                if (numbers) {
                    // Format angka dengan titik (untuk tampilan di input)
                    let formatted = '';
                    for (let i = 0; i < numbers.length; i++) {
                        if (i > 0 && (numbers.length - i) % 3 === 0) {
                            formatted += '.';
                        }
                        formatted += numbers[i];
                    }

                    $('#total_anggaran').val(formatted);

                    // Trigger input event untuk formatting
                    $('#total_anggaran').trigger('input');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Anggaran rekomendasi telah digunakan',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });

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
            // RESET BUTTON DENGAN KONFIRMASI
            // =============================================
            $('#resetBtn').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Reset Form',
                    text: 'Apakah Anda yakin ingin mereset semua data yang sudah diisi?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Reset',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#rencanaForm')[0].reset();
                        $('#rekomendasiSection').slideUp();

                        // Cek ulang untuk tahun default
                        let tahun = $('#tahun').val();
                        if (tahun && tahun.length === 4) {
                            cekRekomendasi(tahun);
                        }
                    }
                });
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
