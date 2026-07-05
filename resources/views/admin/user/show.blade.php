@extends('layouts.app')

@section('title', 'Detail User')

@section('icon', 'person-badge')

@section('content')
<div class="detail-user-container">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Detail User</h2>
            <p class="page-subtitle">Informasi lengkap pengguna sistem informasi anggaran</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">
                <i class="bi bi-pencil-square"></i>
                Edit User
            </a>
            <a href="{{ route('users.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="detail-grid">
        <!-- Left Column - Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h3 class="profile-name">{{ $user->nama }}</h3>
                <p class="profile-username">&#64;{{ $user->username }}</p>
                
                <!-- Status Badge -->
                <div class="profile-status">
                    @if($user->status == 'active')
                        <span class="status-badge active">
                            <i class="bi bi-check-circle-fill"></i>
                            Aktif
                        </span>
                    @else
                        <span class="status-badge inactive">
                            <i class="bi bi-x-circle-fill"></i>
                            Tidak Aktif
                        </span>
                    @endif
                </div>
            </div>

            <div class="profile-body">
                <!-- Role Card -->
                <div class="role-card {{ $user->role }}">
                    <div class="role-icon">
                        @if($user->role == 'admin')
                            <i class="bi bi-shield-lock"></i>
                        @elseif($user->role == 'keuangan')
                            <i class="bi bi-calculator"></i>
                        @elseif($user->role == 'kepala_gizi')
                            <i class="bi bi-person-workspace"></i>
                        @endif
                    </div>
                    <div class="role-info">
                        <span class="role-label">Role / Hak Akses</span>
                        <span class="role-value">{{ $user->role_name }}</span>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="timestamps">
                    <div class="timestamp-item">
                        <i class="bi bi-calendar-plus"></i>
                        <div>
                            <span class="timestamp-label">Dibuat pada</span>
                            <span class="timestamp-value">{{ $user->created_at->format('d M Y') }}</span>
                            <span class="timestamp-time">{{ $user->created_at->format('H:i:s') }}</span>
                        </div>
                    </div>
                    <div class="timestamp-item">
                        <i class="bi bi-calendar-check"></i>
                        <div>
                            <span class="timestamp-label">Terakhir diupdate</span>
                            <span class="timestamp-value">{{ $user->updated_at->format('d M Y') }}</span>
                            <span class="timestamp-time">{{ $user->updated_at->format('H:i:s') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details Card -->
        <div class="details-card">
            <div class="details-header">
                <h4><i class="bi bi-info-circle"></i> Informasi Lengkap</h4>
            </div>
            
            <div class="details-body">
                <!-- Informasi Pribadi -->
                <div class="detail-section">
                    <h5 class="section-title">
                        <i class="bi bi-person-vcard"></i>
                        Data Pribadi
                    </h5>
                    
                    <div class="detail-list">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="bi bi-person"></i>
                                Nama Lengkap
                            </div>
                            <div class="detail-value">{{ $user->nama }}</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="bi bi-at"></i>
                                Username
                            </div>
                            <div class="detail-value">{{ $user->username }}</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="bi bi-envelope"></i>
                                Email
                            </div>
                            <div class="detail-value">{{ $user->email ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Hak Akses -->
                <div class="detail-section">
                    <h5 class="section-title">
                        <i class="bi bi-shield-check"></i>
                        Hak Akses
                    </h5>
                    
                    <div class="permissions-list">
                        @if($user->role == 'admin')
                            <div class="permission-item full">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Akses penuh ke semua fitur</span>
                            </div>
                            <div class="permission-item">
                                <i class="bi bi-people"></i>
                                <span>Mengelola data user</span>
                            </div>
                            <div class="permission-item">
                                <i class="bi bi-file-text"></i>
                                <span>Melihat semua laporan</span>
                            </div>
                            <div class="permission-item">
                                <i class="bi bi-gear"></i>
                                <span>Konfigurasi sistem</span>
                            </div>
                        @elseif($user->role == 'keuangan')
                            <div class="permission-item">
                                <i class="bi bi-plus-circle"></i>
                                <span>Input pengajuan anggaran</span>
                            </div>
                            <div class="permission-item">
                                <i class="bi bi-pencil"></i>
                                <span>Input realisasi anggaran</span>
                            </div>
                            <div class="permission-item">
                                <i class="bi bi-file-earmark-spreadsheet"></i>
                                <span>Melihat laporan keuangan</span>
                            </div>
                            <div class="permission-item">
                                <i class="bi bi-calculator"></i>
                                <span>Mengelola data keuangan</span>
                            </div>
                        @elseif($user->role == 'kepala_gizi')
                            <div class="permission-item">
                                <i class="bi bi-check-circle"></i>
                                <span>Menyetujui pengajuan</span>
                            </div>
                            <div class="permission-item">
                                <i class="bi bi-eye"></i>
                                <span>Monitoring anggaran</span>
                            </div>
                            <div class="permission-item">
                                <i class="bi bi-graph-up"></i>
                                <span>Melihat laporan</span>
                            </div>
                            <div class="permission-item">
                                <i class="bi bi-file-check"></i>
                                <span>Review realisasi</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Activity Stats (Optional) -->
                <div class="detail-section">
                    <h5 class="section-title">
                        <i class="bi bi-activity"></i>
                        Aktivitas
                    </h5>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">{{ $user->created_at->diffForHumans() }}</div>
                            <div class="stat-label">Bergabung</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $user->updated_at->diffForHumans() }}</div>
                            <div class="stat-label">Terakhir update</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Mobile) -->
    <div class="mobile-actions">
        <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">
            <i class="bi bi-pencil-square"></i>
            Edit
        </a>
        @if(auth()->id() != $user->id)
        <button type="button" class="btn-delete" onclick="confirmDelete()">
            <i class="bi bi-trash"></i>
            Hapus
        </button>
        @endif
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="delete-form" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
    .detail-user-container {
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

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-back, .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-back {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-back:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateX(-5px);
    }

    .btn-edit {
        background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(109, 93, 252, 0.3);
        color: white;
    }

    /* Detail Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 1.5rem;
    }

    /* Profile Card */
    .profile-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        overflow: hidden;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .profile-header {
        padding: 2rem 2rem 1.5rem;
        text-align: center;
        background: rgba(255, 255, 255, 0.1);
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        margin: 0 auto 1rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255, 255, 255, 0.5);
    }

    .profile-avatar i {
        font-size: 3.5rem;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .profile-username {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.5rem;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .status-badge.active {
        background: #10b981;
        color: white;
    }

    .status-badge.inactive {
        background: #ef4444;
        color: white;
    }

    .profile-body {
        padding: 1.5rem 2rem 2rem;
    }

    .role-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        backdrop-filter: blur(10px);
    }

    .role-card.admin .role-icon i {
        color: #fee2e2;
    }

    .role-card.keuangan .role-icon i {
        color: #dbeafe;
    }

    .role-card.kepala_gizi .role-icon i {
        color: #ede9fe;
    }

    .role-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .role-icon i {
        font-size: 1.8rem;
    }

    .role-info {
        flex: 1;
    }

    .role-label {
        display: block;
        font-size: 0.8rem;
        opacity: 0.8;
        margin-bottom: 0.25rem;
    }

    .role-value {
        display: block;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .timestamps {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1rem;
    }

    .timestamp-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .timestamp-item:last-child {
        border-bottom: none;
    }

    .timestamp-item i {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    .timestamp-label {
        display: block;
        font-size: 0.8rem;
        opacity: 0.8;
    }

    .timestamp-value {
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .timestamp-time {
        display: block;
        font-size: 0.8rem;
        opacity: 0.7;
    }

    /* Details Card */
    .details-card {
        background: #f8fafc;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .details-header {
        padding: 1.5rem 2rem;
        background: white;
        border-bottom: 1px solid #e2e8f0;
    }

    .details-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .details-header h4 i {
        color: #6d5dfc;
    }

    .details-body {
        padding: 2rem;
    }

    .detail-section {
        margin-bottom: 2rem;
    }

    .detail-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-title i {
        color: #6d5dfc;
    }

    .detail-list {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .detail-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        width: 120px;
        font-size: 0.9rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-label i {
        color: #6d5dfc;
        width: 18px;
    }

    .detail-value {
        flex: 1;
        font-weight: 500;
        color: #1e293b;
    }

    /* Permissions */
    .permissions-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .permission-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        color: #475569;
        font-size: 0.9rem;
    }

    .permission-item i {
        color: #10b981;
    }

    .permission-item.full {
        grid-column: span 2;
        background: #d1fae5;
        padding: 0.75rem;
        border-radius: 8px;
        color: #065f46;
    }

    .permission-item.full i {
        color: #059669;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .stat-item {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
    }

    .stat-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #64748b;
    }

    /* Mobile Actions */
    .mobile-actions {
        display: none;
        margin-top: 1.5rem;
        gap: 1rem;
    }

    .mobile-actions .btn-edit {
        flex: 1;
        justify-content: center;
    }

    .btn-delete {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: #fee2e2;
        color: #ef4444;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: #fecaca;
        color: #dc2626;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .permissions-list {
            grid-template-columns: 1fr;
        }

        .permission-item.full {
            grid-column: span 1;
        }
    }

    @media (max-width: 768px) {
        .detail-user-container {
            padding: 1.5rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .btn-back, .btn-edit {
            width: 100%;
            justify-content: center;
        }

        .detail-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .detail-label {
            width: auto;
        }

        .mobile-actions {
            display: flex;
        }
    }

    @media (max-width: 576px) {
        .profile-header {
            padding: 1.5rem;
        }

        .profile-body {
            padding: 1.5rem;
        }

        .details-body {
            padding: 1.5rem;
        }

        .mobile-actions {
            flex-direction: column;
        }
    }
</style>

<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Hapus User?',
            text: "User '{{ $user->nama }}' akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }

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
</script>
@endsection