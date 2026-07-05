{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Sistem Informasi Anggaran')

@section('content')
    <style>
        /* ==================== VARIABLES - COLORFUL PALETTE ==================== */
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --success: #10b981;
            --success-dark: #059669;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-dark: #d97706;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-dark: #2563eb;
            --info-light: #dbeafe;
            --purple: #8b5cf6;
            --purple-dark: #7c3aed;
            --purple-light: #ede9fe;
            --pink: #ec489a;
            --pink-light: #fce7f3;
            --orange: #f97316;
            --orange-light: #ffedd5;
            --cyan: #06b6d4;
            --cyan-light: #cffafe;
            --indigo: #6366f1;
            --indigo-light: #e0e7ff;
            --dark: #0f172a;
            --gray: #64748b;
            --gray-light: #f8fafc;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.05);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.05);
            --radius-lg: 1rem;
            --radius-xl: 1.5rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dashboard-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
            min-height: 100vh;
        }

        /* ==================== WELCOME CARD ==================== */
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            border-radius: 1.5rem;
            box-shadow: 0 20px 35px -10px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .role-badge {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .live-clock {
            font-family: monospace;
            font-size: 1.5rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* ==================== STATS GRID ==================== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 1.25rem;
            padding: 1.25rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover::after {
            transform: scaleX(1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .stat-card.primary::after {
            background: linear-gradient(90deg, var(--primary), var(--purple));
        }

        .stat-card.success::after {
            background: linear-gradient(90deg, var(--success), #34d399);
        }

        .stat-card.info::after {
            background: linear-gradient(90deg, var(--info), #60a5fa);
        }

        .stat-card.warning::after {
            background: linear-gradient(90deg, var(--warning), #fbbf24);
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--gray);
            font-weight: 500;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-card.primary .stat-icon {
            background: linear-gradient(135deg, var(--primary), var(--purple));
            color: white;
        }

        .stat-card.success .stat-icon {
            background: linear-gradient(135deg, var(--success), #34d399);
            color: white;
        }

        .stat-card.info .stat-icon {
            background: linear-gradient(135deg, var(--info), #60a5fa);
            color: white;
        }

        .stat-card.warning .stat-icon {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
            color: white;
        }

        .stat-trend {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-trend.primary {
            background: var(--primary-light);
            color: var(--primary);
        }

        .stat-trend.success {
            background: var(--success-light);
            color: var(--success);
        }

        .stat-trend.info {
            background: var(--info-light);
            color: var(--info);
        }

        .stat-trend.warning {
            background: var(--warning-light);
            color: var(--warning);
        }

        /* ==================== PROGRESS CARD ==================== */
        .progress-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 1.25rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .progress-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .progress-percentage {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, #a78bfa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .progress-bar-modern {
            height: 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 0.8s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .progress-fill.success {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .progress-fill.warning {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }

        .progress-fill.danger {
            background: linear-gradient(90deg, #ef4444, #f87171);
        }

        /* ==================== ALERTS ==================== */
        .alert-modern {
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 5px solid;
            backdrop-filter: blur(10px);
        }

        .alert-warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-left-color: var(--warning);
            color: #92400e;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left-color: var(--danger);
            color: #991b1b;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.01);
            }
        }

        /* ==================== CARDS ==================== */
        .card-modern {
            background: white;
            border-radius: 1.25rem;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: var(--transition);
            border: 1px solid var(--border);
        }

        .card-modern:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
            border-color: transparent;
        }

        .card-header-modern {
            padding: 1rem 1.25rem;
            border-bottom: 2px solid var(--border);
            background: white;
        }

        .card-header-modern h5 {
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ==================== TABLES ==================== */
        .table-modern {
            width: 100%;
        }

        .table-modern thead th {
            padding: 0.75rem 1rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray);
            background: #f8fafc;
            border-bottom: 2px solid var(--border);
        }

        .table-modern tbody td {
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .table-modern tbody tr:hover {
            background: linear-gradient(90deg, var(--primary-light), transparent);
        }

        /* ==================== BADGES ==================== */
        .badge-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.3rem 0.75rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .badge-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .badge-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .badge-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        .badge-purple {
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            color: #5b21b6;
        }

        .badge-cyan {
            background: linear-gradient(135deg, #cffafe, #a5f3fc);
            color: #155e75;
        }

        .badge-pink {
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
            color: #9d174d;
        }

        .badge-orange {
            background: linear-gradient(135deg, #ffedd5, #fed7aa);
            color: #9a3412;
        }

        /* ==================== BUTTONS ==================== */
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--gray);
            transition: var(--transition);
        }

        .btn-outline:hover {
            background: linear-gradient(135deg, var(--primary), var(--purple));
            border-color: transparent;
            color: white;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--purple));
            border: none;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            transition: var(--transition);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
            color: white;
        }

        /* ==================== PROGRESS SMALL ==================== */
        .progress-sm {
            width: 70px;
            height: 6px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-sm-fill {
            height: 100%;
            border-radius: 10px;
        }

        .progress-sm-fill.bg-success {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .progress-sm-fill.bg-warning {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }

        .progress-sm-fill.bg-danger {
            background: linear-gradient(90deg, #ef4444, #f87171);
        }

        /* ==================== EMPTY STATE ==================== */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .live-clock {
                font-size: 1.1rem;
                margin-top: 1rem;
            }

            .stat-value {
                font-size: 1.4rem;
            }

            .progress-percentage {
                font-size: 2rem;
            }
        }
    </style>

    <div class="dashboard-container">
        <div class="container-fluid px-3 px-md-4 py-4">
            <!-- Welcome Card -->
            <div class="welcome-card text-white p-4 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="role-badge">
                                <i class="bi bi-shield-check me-1"></i>
                                {{ ucfirst(Auth::user()->role ?? 'User') }}
                            </span>
                            <span class="role-badge">
                                <i class="bi bi-star-fill me-1"></i>
                                Premium Access
                            </span>
                        </div>
                        <h2 class="mb-1 fw-bold">
                            Selamat Datang, {{ Auth::user()->nama ?? (Auth::user()->username ?? 'Pengguna') }}! 👋
                        </h2>
                        <p class="mb-0 opacity-75">
                            <i class="bi bi-calendar3 me-2"></i>
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </p>
                    </div>
                    <div class="live-clock mt-2 mt-md-0">
                        <i class="bi bi-clock me-2"></i>
                        <span id="liveClock">--:--:--</span>
                    </div>
                </div>
            </div>

            <!-- Alert -->
            @if ($persentasePemakaian >= 70 && $persentasePemakaian < 90)
                <div class="alert-modern alert-warning d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div>
                        <strong class="d-block mb-1">⚠️ Perhatian!</strong>
                        Anggaran telah terpakai <strong class="text-warning">{{ $persentasePemakaian }}%</strong>.
                        Sisa anggaran <strong>Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</strong>.
                        Mohon lebih bijak dalam pengajuan anggaran.
                    </div>
                </div>
            @elseif($persentasePemakaian >= 90)
                <div class="alert-modern alert-danger d-flex align-items-start">
                    <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
                    <div>
                        <strong class="d-block mb-1">🚨 Peringatan Kritis!</strong>
                        Anggaran telah terpakai <strong class="text-danger">{{ $persentasePemakaian }}%</strong>.
                        Sisa anggaran hanya <strong>Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</strong>.
                        Segera evaluasi penggunaan anggaran!
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="stat-trend primary">
                            <i class="bi bi-arrow-up-short"></i> Aktif
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($anggaranAktif) }}</div>
                    <div class="stat-label">Anggaran Aktif</div>
                </div>

                <div class="stat-card success">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div class="stat-trend success">
                            <i class="bi bi-graph-up"></i> +{{ $persentasePemakaian }}%
                        </div>
                    </div>
                    <div class="stat-value">Rp {{ number_format($totalRealisasi / 1000000, 1) }}jt</div>
                    <div class="stat-label">Total Realisasi</div>
                </div>

                <div class="stat-card info">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div class="stat-trend info">
                            <i class="bi bi-database"></i> Keseluruhan
                        </div>
                    </div>
                    <div class="stat-value">Rp {{ number_format($totalAnggaran / 1000000, 1) }}jt</div>
                    <div class="stat-label">Total Anggaran</div>
                </div>

                <div class="stat-card warning">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon">
                            <i class="bi bi-piggy-bank"></i>
                        </div>
                        <div class="stat-trend warning">
                            <i class="bi bi-arrow-down-short"></i> Sisa {{ 100 - $persentasePemakaian }}%
                        </div>
                    </div>
                    <div class="stat-value">Rp {{ number_format($sisaAnggaran / 1000000, 1) }}jt</div>
                    <div class="stat-label">Sisa Anggaran</div>
                </div>
            </div>

            <!-- Progress Overview -->
            <div class="progress-card">
                <div class="row align-items-center">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <div class="d-flex align-items-center gap-3">
                            <div class="progress-percentage">{{ $persentasePemakaian }}%</div>
                            <div>
                                <div class="fw-semibold text-white">Penggunaan Anggaran</div>
                                <div class="small text-white-50">dari total Rp
                                    {{ number_format($totalAnggaran, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="progress-bar-modern">
                            @php
                                $progressClass =
                                    $persentasePemakaian < 70
                                        ? 'success'
                                        : ($persentasePemakaian < 90
                                            ? 'warning'
                                            : 'danger');
                            @endphp
                            <div class="progress-fill {{ $progressClass }}" style="width: {{ $persentasePemakaian }}%">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small text-white-50">
                            <span><i class="bi bi-check-circle-fill text-success me-1"></i> Terealisasi</span>
                            <span><i class="bi bi-piggy-bank me-1"></i> Sisa {{ 100 - $persentasePemakaian }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Anggaran Aktif -->
            <div class="card-modern">
                <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5>
                        <i class="bi bi-cash-stack text-primary"></i>
                        Detail Anggaran Aktif
                        <span class="badge-modern badge-purple ms-2">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Real-time
                        </span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rencana Anggaran</th>
                                <th>Tahun</th>
                                <th>Total</th>
                                <th>Realisasi</th>
                                <th>Sisa</th>
                                <th>Progress</th>
                                <th>Aksi</th>
                        </thead>
                        <tbody>
                            @forelse($anggaranAktifList as $index => $item)
                                @php
                                    $persen = $item->persentase_realisasi;
                                    $badgeClass = $persen < 70 ? 'success' : ($persen < 90 ? 'warning' : 'danger');
                                    $textClass =
                                        $persen >= 90
                                            ? 'text-danger'
                                            : ($persen >= 70
                                                ? 'text-warning'
                                                : 'text-success');
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->rencanaAnggaran->nama_rencana ?? '-' }}</td>
                                    <td><span class="badge-modern badge-cyan">{{ $item->tahun }}</span></td>
                                    <td>Rp {{ number_format($item->total_anggaran, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->total_realisasi, 0, ',', '.') }}</td>
                                    <td class="{{ $persen >= 90 ? 'text-danger fw-semibold' : '' }}">Rp
                                        {{ number_format($item->sisa_anggaran, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress-sm">
                                                <div class="progress-sm-fill bg-{{ $badgeClass }}"
                                                    style="width: {{ $persen }}%"></div>
                                            </div>
                                            <span
                                                class="small fw-semibold {{ $textClass }}">{{ $persen }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.anggaran.show', $item->id) }}" class="btn-outline">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <div>Tidak ada data anggaran aktif</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Two Column: Pengajuan & Realisasi -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card-modern h-100">
                        <div class="card-header-modern">
                            <h5>
                                <i class="bi bi-clock-history text-warning"></i>
                                Pengajuan Menunggu Validasi
                                @if ($pengajuanMenungguCount > 0)
                                    <span class="badge-modern badge-warning ms-2">
                                        <i class="bi bi-hourglass-split me-1"></i> {{ $pengajuanMenungguCount }} Menunggu
                                    </span>
                                @endif
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>No. Pengajuan</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Pengaju</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pengajuanMenunggu as $pengajuan)
                                        <tr>
                                            <td class="fw-semibold">{{ $pengajuan->nomor_pengajuan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->locale('id')->isoFormat('D MMM YYYY') }}
                                            </td>
                                            <td class="text-primary fw-semibold">Rp
                                                {{ number_format($pengajuan->jumlah_diajukan, 0, ',', '.') }}</td>
                                            <td>{{ $pengajuan->pengaju->nama ?? ($pengajuan->pengaju->username ?? '-') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.pengajuan.show', $pengajuan->id) }}"
                                                    class="btn-outline">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <div>Tidak ada pengajuan</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card-modern h-100">
                        <div class="card-header-modern">
                            <h5>
                                <i class="bi bi-check2-circle text-success"></i>
                                Realisasi Terbaru
                                <span class="badge-modern badge-success ms-2">
                                    <i class="bi bi-check2-all me-1"></i> Success
                                </span>
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>No. Pengajuan</th>
                                        <th>Tanggal Realisasi</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($realisasiTerbaru as $realisasi)
                                        <tr>
                                            <td class="fw-semibold">{{ $realisasi->nomor_pengajuan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($realisasi->tanggal_realisasi)->locale('id')->isoFormat('D MMM YYYY') }}
                                            </td>
                                            <td class="text-success fw-semibold">Rp
                                                {{ number_format($realisasi->jumlah_realisasi ?? $realisasi->jumlah_diajukan, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span class="badge-modern badge-info">
                                                    <i class="bi bi-check2-all me-1"></i> Direalisasi
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.pengajuan.show', $realisasi->id) }}"
                                                    class="btn-outline">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <div>Belum ada realisasi</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rencana Anggaran Terbaru -->
            <div class="card-modern mt-4">
                <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5>
                        <i class="bi bi-file-text text-info"></i>
                        Rencana Anggaran Terbaru
                        <span class="badge-modern badge-pink ms-2">
                            <i class="bi bi-star me-1"></i> Terbaru
                        </span>
                    </h5>
                    <a href="{{ route('admin.rencana.index') }}" class="btn-gradient">
                        <i class="bi bi-arrow-right"></i> Lihat Semua
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Rencana</th>
                                <th>Tahun</th>
                                <th>Total Anggaran</th>
                                <th>Status</th>
                                <th>Pengaju</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rencanaTerbaru as $index => $rencana)
                                <tr>
                                    <td class="fw-semibold">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $rencana->nama_rencana }}</td>
                                    <td><span class="badge-modern badge-cyan">{{ $rencana->tahun }}</span></td>
                                    <td>Rp {{ number_format($rencana->total_anggaran, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($rencana->status == 'Tervalidasi')
                                            <span class="badge-modern badge-success"><i
                                                    class="bi bi-check-circle me-1"></i> Tervalidasi</span>
                                        @elseif($rencana->status == 'Menunggu Validasi')
                                            <span class="badge-modern badge-warning"><i
                                                    class="bi bi-hourglass-split me-1"></i> Menunggu</span>
                                        @elseif($rencana->status == 'Ditolak')
                                            <span class="badge-modern badge-danger"><i class="bi bi-x-circle me-1"></i>
                                                Ditolak</span>
                                        @else
                                            <span
                                                class="badge-modern bg-light text-secondary">{{ $rencana->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $rencana->pengaju->nama ?? ($rencana->pengaju->username ?? '-') }}</td>
                                    <td>
                                        <a href="{{ route('admin.rencana.show', $rencana->id) }}" class="btn-outline">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <div>Belum ada rencana anggaran</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const clockElement = document.getElementById('liveClock');
                if (clockElement) {
                    clockElement.innerHTML = `${hours}:${minutes}:${seconds}`;
                }
            }
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
@endsection
