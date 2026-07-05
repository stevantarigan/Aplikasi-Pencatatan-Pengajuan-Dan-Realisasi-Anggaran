{{-- resources/views/admin/daftarUser.blade.php --}}

@extends('layouts.app')

@section('title', 'Daftar User')
@section('icon', 'people')

{{-- OVERRIDE BREADCRUMB --}}
@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            <i class="bi bi-house-door"></i>
            <span>Home</span>
        </a>
        <span class="breadcrumb-divider">›</span>
    </div>
    <div class="breadcrumb-item">
        <span class="breadcrumb-current">
            <i class="bi bi-people"></i>
            <span>Daftar User</span>
        </span>
    </div>
@endsection

@section('content')
    {{-- PERBAIKAN STYLE UNTUK LOGO "KE" DI HEADER --}}
    <style>
        /* ===== PERBAIKAN USER PROFILE DI HEADER ===== */
        /* Memastikan avatar "KE" tetap bagus seperti di halaman lain */

        /* Target semua kemungkinan class untuk avatar di header */
        header .user-avatar,
        .app-header .user-avatar,
        .user-profile .user-avatar,
        .main-content-wrapper .user-avatar {
            width: 52px !important;
            height: 52px !important;
            background: linear-gradient(135deg, #6d5dfc, #22d3ee) !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: white !important;
            font-weight: 700 !important;
            font-size: 1.2rem !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            line-height: 1 !important;
            text-transform: uppercase !important;
            box-shadow: 0 4px 10px rgba(109, 93, 252, 0.3) !important;
            border: 3px solid rgba(255, 255, 255, 0.9) !important;
            margin: 0 !important;
            padding: 0 !important;
            position: relative !important;
            flex-shrink: 0 !important;
            letter-spacing: normal !important;
            text-indent: 0 !important;
        }

        /* Perbaiki user info di header */
        header .user-info,
        .app-header .user-info,
        .user-profile .user-info {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-end !important;
            gap: 0 !important;
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Perbaiki nama user di header */
        header .user-name,
        .app-header .user-name {
            font-weight: 700 !important;
            color: #1e293b !important;
            font-size: 0.95rem !important;
            line-height: 1.4 !important;
            white-space: nowrap !important;
            max-width: 200px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        /* Perbaiki role user di header */
        header .user-role,
        .app-header .user-role {
            font-size: 0.75rem !important;
            color: #64748b !important;
            background: rgba(0, 0, 0, 0.03) !important;
            padding: 0.25rem 0.75rem !important;
            border-radius: 50px !important;
            display: inline-block !important;
            white-space: nowrap !important;
            max-width: 150px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        /* Perbaiki dropdown avatar */
        .user-dropdown-menu .user-avatar {
            width: 60px !important;
            height: 60px !important;
            font-size: 1.5rem !important;
        }

        /* ===== STYLE UNTUK TABEL (TIDAK MENIMPA HEADER) ===== */
        /* Class khusus untuk avatar di dalam tabel */
        .table-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6d5dfc;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        /* Class khusus untuk user info di tabel */
        .table-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Pastikan tidak ada style yang bocor ke header */
        .user-container .table-avatar {
            /* style khusus tabel */
        }

        .user-container .table-user-info {
            /* style khusus tabel */
        }

        /* Style lainnya tetap sama */
        .user-container {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(109, 93, 252, 0.1);
        }

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

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(109, 93, 252, 0.3);
            color: white;
        }

        .btn-primary i {
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: #6d5dfc;
            box-shadow: 0 10px 25px rgba(109, 93, 252, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon.bg-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        }

        .stat-icon.bg-danger {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .stat-icon.bg-info {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }

        .stat-icon.bg-purple {
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
        }

        .stat-icon.bg-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            display: block;
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }

        .filter-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-wrapper {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 1;
        }

        .search-wrapper input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .search-wrapper input:focus {
            outline: none;
            border-color: #6d5dfc;
            box-shadow: 0 0 0 3px rgba(109, 93, 252, 0.1);
        }

        .filter-wrapper {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 130px;
        }

        .filter-select:focus {
            outline: none;
            border-color: #6d5dfc;
        }

        .btn-refresh {
            width: 42px;
            height: 42px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-refresh:hover {
            border-color: #6d5dfc;
            color: #6d5dfc;
            transform: rotate(180deg);
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            background: white;
            margin-bottom: 1.5rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table td {
            padding: 1rem;
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

        .username {
            font-weight: 600;
            color: #1e293b;
        }

        .username-text {
            color: #64748b;
            font-size: 0.85rem;
        }

        .badge-you {
            display: inline-block;
            background: #e0e7ff;
            color: #6d5dfc;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-role i {
            font-size: 0.9rem;
        }

        .role-admin {
            background: #fee2e2;
            color: #ef4444;
        }

        .role-keuangan {
            background: #dbeafe;
            color: #3b82f6;
        }

        .role-kepala_gizi {
            background: #ede9fe;
            color: #8b5cf6;
        }

        .role-staff {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .status-toggle {
            cursor: pointer;
        }

        .status-toggle.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .status-switch {
            width: 44px;
            height: 24px;
            background: #e2e8f0;
            border-radius: 40px;
            position: relative;
            transition: all 0.3s ease;
        }

        .status-switch.active {
            background: #10b981;
        }

        .status-switch::after {
            content: '';
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            left: 3px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .status-switch.active::after {
            left: 23px;
        }

        .status-text {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-text.active {
            color: #10b981;
        }

        .status-text.inactive {
            color: #ef4444;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
        }

        .btn-view {
            background: #3b82f6;
        }

        .btn-edit {
            background: #f59e0b;
        }

        .btn-delete {
            background: #ef4444;
            border: none;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
            filter: brightness(110%);
            color: white;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li a,
        .pagination li span {
            display: block;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: white;
            color: #475569;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .pagination li.active span {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
            color: white;
            border: none;
        }

        .pagination li a:hover {
            background: #f1f5f9;
            border-color: #6d5dfc;
        }

        .alert {
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .user-container {
                padding: 1.5rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-section {
                flex-direction: column;
            }

            .search-wrapper {
                min-width: 100%;
            }

            .filter-wrapper {
                width: 100%;
            }

            .filter-select {
                flex: 1;
                min-width: calc(50% - 0.25rem);
            }

            .action-buttons {
                justify-content: flex-start;
            }
        }
    </style>

    <div class="user-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Daftar User</h2>
                <p class="page-subtitle">Pengelolaan data pengguna sistem</p>
            </div>

            {{-- Tombol Tambah User hanya untuk Admin dan Kepala Gizi --}}
            @if (in_array(auth()->user()->role, ['admin', 'kepala_gizi']))
                <a href="{{ route('users.create') }}" class="btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah User
                </a>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Users</span>
                    <span class="stat-value">{{ $users->total() }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-danger">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Admin</span>
                    <span class="stat-value">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="bi bi-calculator"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Keuangan</span>
                    <span class="stat-value">{{ \App\Models\User::where('role', 'keuangan')->count() }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-purple">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Kepala Gizi</span>
                    <span class="stat-value">{{ \App\Models\User::where('role', 'kepala_gizi')->count() }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Aktif</span>
                    <span class="stat-value">{{ \App\Models\User::where('status', 'active')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter & Search -->
        <div class="filter-section">
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="searchUser" placeholder="Cari nama, username atau role..."
                    value="{{ request('search') }}">
            </div>
            <div class="filter-wrapper">
                <select class="filter-select" id="roleFilter">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="keuangan" {{ request('role') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                    <option value="kepala_gizi" {{ request('role') == 'kepala_gizi' ? 'selected' : '' }}>Kepala Gizi
                    </option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>
                <select class="filter-select" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button class="btn-refresh" onclick="window.location.href='{{ route('users.index') }}'">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Terakhir Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $index => $user)
                        @php
                            // Tentukan icon berdasarkan role
                            if ($user->role == 'admin') {
                                $roleIcon = 'shield-lock';
                                $roleName = 'Admin';
                                $roleClass = 'admin';
                            } elseif ($user->role == 'keuangan') {
                                $roleIcon = 'calculator';
                                $roleName = 'Keuangan';
                                $roleClass = 'keuangan';
                            } elseif ($user->role == 'kepala_gizi') {
                                $roleIcon = 'person-workspace';
                                $roleName = 'Kepala Gizi';
                                $roleClass = 'kepala_gizi';
                            } elseif ($user->role == 'staff') {
                                $roleIcon = 'person';
                                $roleName = 'Staff';
                                $roleClass = 'staff';
                            } else {
                                $roleIcon = 'person';
                                $roleName = ucfirst($user->role);
                                $roleClass = $user->role;
                            }
                        @endphp
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="table-user-info">
                                    <div class="table-avatar">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                    <div>
                                        <span class="username">{{ $user->nama }}</span>
                                        @if ($user->id === auth()->id())
                                            <span class="badge-you">Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="username-text">@</span>{{ $user->username }}
                            </td>
                            <td>
                                <span class="badge-role role-{{ $roleClass }}">
                                    <i class="bi bi-{{ $roleIcon }}"></i>
                                    {{ $roleName }}
                                </span>
                            </td>
                            <td>
                                <div class="status-wrapper">
                                    {{-- Status toggle hanya untuk Admin --}}
                                    @if (auth()->user()->role == 'admin' && $user->id !== auth()->id())
                                        <div class="status-toggle" data-user-id="{{ $user->id }}"
                                            data-status="{{ $user->status ?? 'active' }}">
                                            <div
                                                class="status-switch {{ ($user->status ?? 'active') === 'active' ? 'active' : '' }}">
                                            </div>
                                        </div>
                                    @else
                                        <div class="status-toggle disabled">
                                            <div
                                                class="status-switch {{ ($user->status ?? 'active') === 'active' ? 'active' : '' }}">
                                            </div>
                                        </div>
                                    @endif
                                    <span class="status-text {{ $user->status ?? 'active' }}">
                                        {{ $user->status ?? 'active' }}
                                    </span>
                                </div>
                            </td>
                            <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $user->updated_at->diffForHumans() }}</td>
                            <td>
                                <div class="action-buttons">
                                    {{-- Tombol View untuk Admin dan Kepala Gizi --}}
                                    @if (in_array(auth()->user()->role, ['admin', 'kepala_gizi']))
                                        <a href="{{ route('users.show', $user->id) }}" class="btn-icon btn-view"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif

                                    {{-- Tombol Edit untuk Admin dan Kepala Gizi --}}
                                    @if (in_array(auth()->user()->role, ['admin', 'kepala_gizi']))
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn-icon btn-edit"
                                            title="Edit User">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    {{-- Tombol Delete HANYA UNTUK ADMIN dan bukan dirinya sendiri --}}
                                    @if (auth()->user()->role == 'admin' && $user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            id="delete-form-{{ $user->id }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-icon btn-delete"
                                                onclick="confirmDelete({{ $user->id }}, '{{ $user->nama }}')"
                                                title="Hapus User">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-people" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="mt-3 text-muted">Belum ada user</p>
                                @if (in_array(auth()->user()->role, ['admin', 'kepala_gizi']))
                                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="bi bi-plus-lg"></i>
                                        Tambah User Pertama
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Info -->
        <div class="row mt-3">
            <div class="col-md-6">
                <small class="text-muted">
                    Total data: {{ $users->total() }} user
                </small>
            </div>
            <div class="col-md-6 text-right">
                <small class="text-muted">
                    Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}
                </small>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $users->links() }}
        </div>
    </div>

    {{-- SCRIPT UNTUK MEMASTIKAN AVATAR HEADER TETAP BAGUS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Fungsi untuk memperbaiki avatar di header
            function fixHeaderAvatar() {
                // Terapkan style langsung dengan JavaScript sebagai backup
                $('header .user-avatar, .app-header .user-avatar, .user-profile .user-avatar').css({
                    'width': '52px',
                    'height': '52px',
                    'background': 'linear-gradient(135deg, #6d5dfc, #22d3ee)',
                    'borderRadius': '50%',
                    'display': 'flex',
                    'alignItems': 'center',
                    'justifyContent': 'center',
                    'color': 'white',
                    'fontWeight': '700',
                    'fontSize': '1.2rem',
                    'fontFamily': "'Plus Jakarta Sans', sans-serif",
                    'lineHeight': '1',
                    'textTransform': 'uppercase',
                    'boxShadow': '0 4px 10px rgba(109, 93, 252, 0.3)',
                    'border': '3px solid rgba(255, 255, 255, 0.9)',
                    'margin': '0',
                    'padding': '0',
                    'position': 'relative',
                    'flexShrink': '0'
                });
            }

            // Jalankan fungsi
            fixHeaderAvatar();

            // Jalankan juga setelah AJAX complete
            $(document).ajaxComplete(function() {
                fixHeaderAvatar();
            });

            // Search functionality
            let searchTimer;
            $('#searchUser').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    performSearch();
                }, 500);
            });

            // Role filter
            $('#roleFilter, #statusFilter').on('change', function() {
                performSearch();
            });

            function performSearch() {
                var search = $('#searchUser').val();
                var role = $('#roleFilter').val();
                var status = $('#statusFilter').val();

                window.location.href = '{{ route('users.index') }}?search=' + encodeURIComponent(search) +
                    '&role=' + encodeURIComponent(role) +
                    '&status=' + encodeURIComponent(status);
            }

            // Status toggle - hanya untuk Admin
            $('.status-toggle:not(.disabled)').on('click', function() {
                var $this = $(this);
                var userId = $this.data('user-id');
                var currentStatus = $this.data('status');
                var newStatus = currentStatus === 'active' ? 'inactive' : 'active';

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Ubah status user menjadi ${newStatus}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6d5dfc',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/users/${userId}/status`,
                            type: 'PUT',
                            data: {
                                status: newStatus,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    $this.find('.status-switch').toggleClass('active');
                                    $this.data('status', newStatus);

                                    var $statusText = $this.siblings('.status-text');
                                    $statusText.text(newStatus);
                                    $statusText.removeClass('active inactive').addClass(
                                        newStatus);

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Status user telah diubah',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            },
                            error: function(xhr) {
                                let message = 'Terjadi kesalahan';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: message
                                });
                            }
                        });
                    }
                });
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

        // Fungsi Delete dengan SweetAlert
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus User',
                html: `Apakah Anda yakin ingin menghapus user <strong>"${name}"</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-trash"></i> Ya, Hapus',
                cancelButtonText: '<i class="bi bi-x-lg"></i> Batal',
                reverseButtons: true,
                focusCancel: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        document.getElementById('delete-form-' + id).submit();
                        resolve();
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }
    </script>
@endsection
