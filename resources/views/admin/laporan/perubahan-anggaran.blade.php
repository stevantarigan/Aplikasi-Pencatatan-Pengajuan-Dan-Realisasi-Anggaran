{{-- resources/views/admin/laporan/perubahan-anggaran.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Perubahan Anggaran')
@section('icon', 'arrow-repeat')

@section('content')
    <div class="laporan-perubahan-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Laporan Perubahan Anggaran</h2>
                <p class="page-subtitle">Perbandingan Rencana Awal dengan Anggaran yang Disetujui</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.laporan.index') }}" class="btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-card">
            <form action="{{ route('admin.laporan.perubahan') }}" method="GET" id="filterForm">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="tahun">Tahun</label>
                        <select name="tahun" id="tahun" class="filter-select">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="kode_rekening_id">Kode Rekening</label>
                        <select name="kode_rekening_id" id="kode_rekening_id" class="filter-select">
                            <option value="">Semua Rekening</option>
                            @foreach ($kodeRekeningList as $rekening)
                                <option value="{{ $rekening->id }}"
                                    {{ request('kode_rekening_id') == $rekening->id ? 'selected' : '' }}>
                                    {{ $rekening->kode }} - {{ $rekening->nama_rekening }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="status_anggaran">Status Anggaran</label>
                        <select name="status_anggaran" id="status_anggaran" class="filter-select">
                            <option value="semua" {{ request('status_anggaran') == 'semua' ? 'selected' : '' }}>Semua
                                Status</option>
                            <option value="aktif" {{ request('status_anggaran') == 'aktif' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="ditutup" {{ request('status_anggaran') == 'ditutup' ? 'selected' : '' }}>Ditutup
                            </option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="search">Pencarian</label>
                        <div class="search-wrapper">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" id="search" class="filter-input"
                                placeholder="Cari nama rencana, kode rekening..." value="{{ request('search') }}">
                        </div>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.laporan.perubahan') }}" class="btn-reset">
                        <i class="bi bi-arrow-clockwise"></i>
                        Reset
                    </a>
                    <div class="export-buttons">
                        <button type="submit" name="format" value="pdf" class="btn-pdf">
                            <i class="bi bi-file-pdf"></i>
                            Export PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistik Perubahan -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-files"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Rencana</span>
                    <span class="stat-value">{{ $statistik['totalRencana'] }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Sebelum</span>
                    <span class="stat-value">Rp {{ number_format($statistik['totalSebelum'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-piggy-bank"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Sesudah</span>
                    <span class="stat-value">Rp {{ number_format($statistik['totalSesudah'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon {{ $statistik['totalPerubahan'] >= 0 ? 'bg-info' : 'bg-danger' }}">
                    <i class="bi bi-arrow-{{ $statistik['totalPerubahan'] >= 0 ? 'up' : 'down' }}"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Perubahan</span>
                    <span class="stat-value {{ $statistik['totalPerubahan'] >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $statistik['totalPerubahan'] >= 0 ? '+' : '' }}Rp
                        {{ number_format(abs($statistik['totalPerubahan']), 0, ',', '.') }}
                    </span>
                    <small
                        class="text-muted">{{ $statistik['persenPerubahan'] >= 0 ? '+' : '' }}{{ $statistik['persenPerubahan'] }}%</small>
                </div>
            </div>
        </div>

        <!-- Tabel Perubahan Anggaran -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">KODE REKENING</th>
                        <th rowspan="2">URAIAN</th>
                        <th colspan="8" class="text-center">RINCIAN PERHITUNGAN</th>
                        <th rowspan="2">BERTAMBAH/<br>BERKURANG (Rp.)</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="sub-header">SEBELUM</th>
                        <th colspan="4" class="sub-header">SESUDAH</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Volume</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah (Rp.)</th>
                        <th>Volume</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah (Rp.)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rencana as $item)
                        @php
                            // Nilai SEBELUM (dari tabel rencana_anggaran)
                            $sebelum = $item->total_anggaran;

                            // Nilai SESUDAH (dari tabel anggaran) - jika tidak ada, gunakan nilai sebelum
                            $sesudah = $item->anggaran->total_anggaran ?? $sebelum;

                            // Selisih/perubahan
                            $perubahan = $sesudah - $sebelum;

                            // Asumsi volume 12 bulan
                            $volume = 12;
                            $satuan = 'Bulan';

                            // Hitung harga satuan
                            $hargaSatuanSebelum = $volume > 0 ? $sebelum / $volume : 0;
                            $hargaSatuanSesudah = $volume > 0 ? $sesudah / $volume : 0;

                            // Status perubahan
                            $statusClass =
                                $perubahan > 0 ? 'text-success' : ($perubahan < 0 ? 'text-danger' : 'text-secondary');
                            $badgeClass =
                                $perubahan > 0
                                    ? 'badge-success'
                                    : ($perubahan < 0
                                        ? 'badge-danger'
                                        : 'badge-secondary');
                            $iconClass =
                                $perubahan > 0 ? 'bi-arrow-up' : ($perubahan < 0 ? 'bi-arrow-down' : 'bi-dash');

                            $persenItem = $sebelum > 0 ? ($perubahan / $sebelum) * 100 : 0;
                        @endphp
                        <tr>
                            <!-- KODE REKENING -->
                            <td>
                                <div class="kode-rekening-info">
                                    <span class="fw-bold">{{ $item->kodeRekening->kode ?? '-' }}</span>
                                    <small
                                        class="text-muted d-block">{{ Str::limit($item->kodeRekening->nama_rekening ?? '-', 30) }}</small>
                                </div>
                            </td>

                            <!-- URAIAN -->
                            <td>
                                <span class="fw-bold">{{ $item->nama_rencana }}</span>
                                <small class="text-muted d-block">Tahun: {{ $item->tahun }}</small>
                                @if ($item->deskripsi)
                                    <small class="text-muted d-block">{{ Str::limit($item->deskripsi, 30) }}</small>
                                @endif
                            </td>

                            <!-- RINCIAN PERHITUNGAN - SEBELUM -->
                            <td class="text-center">{{ number_format($volume, 0) }}</td>
                            <td class="text-center">{{ $satuan }}</td>
                            <td class="text-right">Rp {{ number_format($hargaSatuanSebelum, 0, ',', '.') }}</td>
                            <td class="text-right fw-bold">Rp {{ number_format($sebelum, 0, ',', '.') }}</td>

                            <!-- RINCIAN PERHITUNGAN - SESUDAH -->
                            <td class="text-center">{{ number_format($volume, 0) }}</td>
                            <td class="text-center">{{ $satuan }}</td>
                            <td class="text-right">Rp {{ number_format($hargaSatuanSesudah, 0, ',', '.') }}</td>
                            <td class="text-right fw-bold">Rp {{ number_format($sesudah, 0, ',', '.') }}</td>

                            <!-- BERTAMBAH/BERKURANG -->
                            <td class="text-right">
                                <div class="perubahan-info">
                                    <span class="badge {{ $badgeClass }}">
                                        <i class="bi {{ $iconClass }}"></i>
                                        {{ $perubahan >= 0 ? '+' : '' }}Rp
                                        {{ number_format(abs($perubahan), 0, ',', '.') }}
                                    </span>
                                    <div class="progress-mini mt-1">
                                        <div class="progress-bar-mini {{ $perubahan >= 0 ? 'bg-success' : 'bg-danger' }}"
                                            style="width: {{ min(100, abs($persenItem)) }}%"></div>
                                    </div>
                                    <small
                                        class="{{ $statusClass }}">{{ $perubahan >= 0 ? '+' : '' }}{{ number_format($persenItem, 1) }}%</small>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class="bi bi-file-earmark-x" style="font-size:3rem; color:#cbd5e1;"></i>
                                <p class="mt-3 text-muted">Tidak ada data perubahan anggaran</p>
                                <a href="{{ route('admin.laporan.perubahan') }}" class="btn-empty">
                                    <i class="bi bi-x-circle"></i> Reset Filter
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-footer">
                        <td colspan="3" class="text-right fw-bold">TOTAL</td>
                        <td></td>
                        <td></td>
                        <td class="text-right fw-bold">Rp {{ number_format($statistik['totalSebelum'], 0, ',', '.') }}
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right fw-bold">Rp {{ number_format($statistik['totalSesudah'], 0, ',', '.') }}
                        </td>
                        <td class="text-right">
                            <span
                                class="badge {{ $statistik['totalPerubahan'] >= 0 ? 'badge-success' : 'badge-danger' }}">
                                {{ $statistik['totalPerubahan'] >= 0 ? '+' : '' }}Rp
                                {{ number_format(abs($statistik['totalPerubahan']), 0, ',', '.') }}
                            </span>
                            <div class="progress-mini mt-1">
                                <div class="progress-bar-mini {{ $statistik['totalPerubahan'] >= 0 ? 'bg-success' : 'bg-danger' }}"
                                    style="width: {{ min(100, abs($statistik['persenPerubahan'])) }}%"></div>
                            </div>
                            <small>{{ $statistik['persenPerubahan'] >= 0 ? '+' : '' }}{{ number_format($statistik['persenPerubahan'], 1) }}%</small>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer Info -->
        <div class="table-footer">
            <div class="footer-info">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i>
                    Menampilkan {{ count($rencana) }} data rencana anggaran
                </small>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="keterangan-box">
            <h6><i class="bi bi-info-circle"></i> Keterangan:</h6>
            <ul>
                <li>Nilai SEBELUM diambil dari total_anggaran tabel rencana_anggaran (Rencana Awal)</li>
                <li>Nilai SESUDAH diambil dari total_anggaran tabel anggaran (Anggaran yang Disetujui)</li>
                <li>Volume 12 Bulan adalah asumsi (dapat disesuaikan dengan kebutuhan)</li>
                <li>Bertambah/Berkurang = Nilai Sesudah - Nilai Sebelum</li>
            </ul>
        </div>
    </div>

    <style>
        .laporan-perubahan-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 32px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.03);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
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
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateX(-3px);
            color: #1e293b;
        }

        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }

        .filter-select,
        .filter-input {
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: #6d5dfc;
            box-shadow: 0 0 0 3px rgba(109, 93, 252, 0.1);
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-wrapper input {
            padding-left: 2.5rem;
        }

        .filter-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn-filter {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(109, 93, 252, 0.2);
        }

        .btn-reset {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-reset:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .export-buttons {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }

        .btn-pdf {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-pdf:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: #6d5dfc;
            box-shadow: 0 10px 20px rgba(109, 93, 252, 0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }

        .stat-icon.bg-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        }

        .stat-icon.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .stat-icon.bg-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .stat-icon.bg-info {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        }

        .stat-icon.bg-danger {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            display: block;
            color: #64748b;
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            display: block;
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        .stat-content small {
            font-size: 0.7rem;
            display: block;
            margin-top: 0.25rem;
            color: #64748b;
        }

        .text-success {
            color: #10b981;
        }

        .text-danger {
            color: #ef4444;
        }

        .text-secondary {
            color: #64748b;
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            background: white;
            margin-bottom: 1rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1600px;
        }

        .table th {
            background: #f8fafc;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.85rem;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
            vertical-align: middle;
        }

        .table .sub-header {
            background: #e2e8f0;
            color: #1e293b;
            text-align: center;
        }

        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: middle;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .table-footer {
            background: #e2e8f0;
            font-weight: 600;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .kode-rekening-info,
        .user-info,
        .date-info {
            line-height: 1.4;
        }

        .fw-bold {
            font-weight: 600;
        }

        .text-muted {
            color: #64748b !important;
        }

        .d-block {
            display: block;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
        }

        .badge-success {
            background: #10b981;
        }

        .badge-danger {
            background: #ef4444;
        }

        .badge-secondary {
            background: #64748b;
        }

        .progress-mini {
            width: 80px;
            height: 4px;
            border-radius: 2px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .progress-bar-mini {
            height: 100%;
            transition: width 0.3s ease;
        }

        .bg-success {
            background: #10b981;
        }

        .bg-danger {
            background: #ef4444;
        }

        .perubahan-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .footer-info {
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 8px;
        }

        .footer-info small {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .keterangan-box {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        .keterangan-box h6 {
            margin-bottom: 0.5rem;
            color: #1e293b;
            font-weight: 600;
        }

        .keterangan-box ul {
            margin: 0;
            padding-left: 1.5rem;
            color: #475569;
        }

        .keterangan-box li {
            margin-bottom: 0.25rem;
        }

        .btn-empty {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-empty:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        @media (max-width: 768px) {
            .laporan-perubahan-container {
                padding: 1rem;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
            }

            .export-buttons {
                margin-left: 0;
                width: 100%;
            }

            .btn-pdf {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-card,
        .stat-card,
        .table-responsive,
        .keterangan-box {
            animation: slideIn 0.5s ease forwards;
        }
    </style>
@endsection
