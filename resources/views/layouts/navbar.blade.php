<!-- Sidebar Navigation - COMPACT VERSION -->
<aside class="sidebar-nav">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-wrapper {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            id="sidebarLogo">
            <div class="brand-icon">
                <i class="bi bi-gitlab"></i>
            </div>
            <div class="brand-text">SIAGA</div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="nav-menu">
        <!-- Home -->
        <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"
            data-page="home">
            <div class="nav-icon">
                <i class="bi bi-house-door"></i>
            </div>
            <div class="nav-text">Home</div>
            <div class="active-indicator"></div>
        </a>
        <!-- Harga Makanan Pasien -->
        <a class="nav-item {{ request()->routeIs('admin.makanan.*') ? 'active' : '' }}"
            href="{{ route('admin.makanan.index') }}" data-page="makanan">
            <div class="nav-icon">
                <i class="bi bi-cup-hot"></i>
            </div>
            <div class="nav-text">Harga Makanan</div>
        </a>
        <!-- Daftar Pasien -->
        <a class="nav-item {{ request()->routeIs('admin.pasien.*') ? 'active' : '' }}"
            href="{{ route('admin.pasien.index') }}" data-page="pasien">
            <div class="nav-icon">
                <i class="bi bi-clipboard-heart"></i>
            </div>
            <div class="nav-text">Daftar Pasien</div>
        </a>

        {{-- Daftar User - Hanya tampil untuk Admin dan Kepala Gizi --}}
        @if (in_array(Auth::user()->role, ['admin', 'kepala_gizi']))
            <a class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"
                data-page="users">
                <div class="nav-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="nav-text">Daftar User</div>
            </a>
        @endif

        <!-- Daftar COA -->
        <a class="nav-item {{ request()->routeIs('admin.daftarCOA') || request()->routeIs('admin.coa.*') ? 'active' : '' }}"
            href="{{ route('admin.daftarCOA') }}" data-page="coa">
            <div class="nav-icon">
                <i class="bi bi-credit-card"></i>
            </div>
            <div class="nav-text">Daftar COA</div>
        </a>

        <!-- Rencana Anggaran -->
        <a class="nav-item {{ request()->routeIs('admin.rencana.*') ? 'active' : '' }}"
            href="{{ route('admin.rencana.index') }}" data-page="rencana">
            <div class="nav-icon">
                <i class="bi bi-list-check"></i>
            </div>
            <div class="nav-text">Rencana Anggaran</div>
            <div class="nav-badge warning" id="rencanaBadge">
                @php
                    try {
                        $rencanaDiajukan = \App\Models\RencanaAnggaran::where('status', 'diajukan')->count();
                        echo $rencanaDiajukan;
                    } catch (\Exception $e) {
                        echo '0';
                    }
                @endphp
            </div>
        </a>

        <!-- Daftar Anggaran -->
        <a class="nav-item {{ request()->routeIs('admin.anggaran.*') ? 'active' : '' }}"
            href="{{ route('admin.anggaran.index') }}">
            <div class="nav-icon">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="nav-text">Daftar Anggaran</div>
            <div class="nav-badge success" id="anggaranBadge">
                @php
                    try {
                        $anggaranAktif = \App\Models\Anggaran::where('status', 'aktif')->count();
                        echo $anggaranAktif;
                    } catch (\Exception $e) {
                        echo '0';
                    }
                @endphp
            </div>
        </a>

        <!-- Data Pengajuan -->
        <a class="nav-item {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}"
            href="{{ route('admin.pengajuan.index') }}">
            <div class="nav-icon">
                <i class="bi bi-send"></i>
            </div>
            <div class="nav-text">Data Pengajuan</div>
            <div class="nav-badge urgent" id="pengajuanBadge">
                @php
                    try {
                        $pengajuanPending = \App\Models\PengajuanAnggaran::where('status', 'diajukan')->count();
                        echo $pengajuanPending;
                    } catch (\Exception $e) {
                        echo '0';
                    }
                @endphp
            </div>
        </a>

        <!-- Data Realisasi -->
        <a class="nav-item {{ request()->routeIs('admin.realisasi.*') ? 'active' : '' }}"
            href="{{ route('admin.realisasi.index') }}">
            <div class="nav-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="nav-text">Data Realisasi</div>
            <div class="nav-badge info" id="realisasiBadge">
                @php
                    try {
                        $totalAnggaran = \App\Models\Anggaran::sum('total_anggaran');
                        $totalRealisasi = \App\Models\PengajuanAnggaran::where('status', 'direalisasi')->sum(
                            'jumlah_realisasi',
                        );
                        $persentase = $totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100) : 0;
                        echo $persentase . '%';
                    } catch (\Exception $e) {
                        echo '0%';
                    }
                @endphp
            </div>
        </a>

        <!-- ===== DROPDOWN LAPORAN ===== -->
        <div class="dropdown-container">
            <div class="dropdown-header" onclick="toggleDropdown(this)">
                <div class="nav-icon">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <div class="nav-text">Laporan</div>
                <div class="dropdown-arrow">
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>

            <div class="dropdown-content" style="display: none;">
                <a class="dropdown-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}"
                    href="{{ route('admin.laporan.index') }}" onclick="handleSubmenuClick(this, 'Dashboard Laporan');">
                    <i class="bi bi-pie-chart"></i>
                    Dashboard Laporan
                </a>
                <a class="dropdown-link {{ request()->routeIs('admin.laporan.rencana') ? 'active' : '' }}"
                    href="{{ route('admin.laporan.rencana') }}"
                    onclick="handleSubmenuClick(this, 'Laporan Rencana Anggaran');">
                    <i class="bi bi-file-text"></i>
                    Laporan Rencana Anggaran
                </a>
                <a class="dropdown-link {{ request()->routeIs('admin.laporan.perubahan') ? 'active' : '' }}"
                    href="{{ route('admin.laporan.perubahan') }}"
                    onclick="handleSubmenuClick(this, 'Laporan Perubahan Anggaran');">
                    <i class="bi bi-arrow-repeat"></i>
                    Laporan Perubahan Anggaran
                </a>
                <a class="dropdown-link {{ request()->routeIs('admin.laporan.lpj') && !request()->has('view') ? 'active' : '' }}"
                    href="{{ route('admin.laporan.lpj') }}" onclick="handleSubmenuClick(this, 'LPJ Anggaran');">
                    <i class="bi bi-file-earmark-text"></i>
                    LPJ Anggaran ⭐
                </a>
            </div>
        </div>
    </div>
</aside>

<style>
    /* ========== SIDEBAR STYLES - COMPACT VERSION ========== */
    .sidebar-nav {
        width: 240px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-right: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 2px 0 12px rgba(0, 0, 0, 0.05);
        padding: 1rem 0.75rem;
        display: flex;
        flex-direction: column;
        z-index: 1050;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-nav.active {
        transform: translateX(0);
    }

    @media (max-width: 992px) {
        .sidebar-nav {
            transform: translateX(-100%);
            box-shadow: none;
            width: 260px;
        }

        .sidebar-nav.active {
            transform: translateX(0);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }
    }

    /* Sidebar Brand - COMPACT */
    .sidebar-brand {
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 1rem;
    }

    .brand-wrapper {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .brand-wrapper:hover {
        transform: translateY(-1px);
    }

    .brand-icon {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        box-shadow: 0 3px 10px rgba(109, 93, 252, 0.25);
        transition: all 0.2s ease;
    }

    .brand-wrapper:hover .brand-icon {
        transform: rotate(-3deg) scale(1.02);
    }

    .brand-text {
        font-size: 1.2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Navigation Menu */
    .nav-menu {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .nav-menu::-webkit-scrollbar {
        width: 3px;
    }

    .nav-menu::-webkit-scrollbar-track {
        background: transparent;
    }

    .nav-menu::-webkit-scrollbar-thumb {
        background: rgba(109, 93, 252, 0.2);
        border-radius: 3px;
    }

    /* Navigation Items - COMPACT */
    .nav-item {
        position: relative;
        display: flex;
        align-items: center;
        padding: 0.55rem 0.85rem;
        border-radius: 10px;
        color: #64748b;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 0.1rem;
        overflow: hidden;
    }

    .nav-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: linear-gradient(180deg, #6d5dfc, #22d3ee);
        border-radius: 0 3px 3px 0;
        transition: height 0.2s ease;
    }

    .nav-item:hover {
        background: rgba(109, 93, 252, 0.08);
        color: #6d5dfc;
        transform: translateX(3px);
    }

    .nav-item:hover::before {
        height: 55%;
    }

    .nav-item.active {
        background: rgba(109, 93, 252, 0.12);
        color: #6d5dfc;
        font-weight: 600;
    }

    .nav-item.active::before {
        height: 65%;
    }

    .nav-icon {
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.7rem;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .nav-item:hover .nav-icon,
    .nav-item.active .nav-icon {
        transform: scale(1.03);
        color: #6d5dfc;
    }

    .nav-text {
        flex: 1;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .nav-item:hover .nav-text {
        transform: translateX(1px);
    }

    /* Active Indicator */
    .active-indicator {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #6d5dfc;
        box-shadow: 0 0 0 2px rgba(109, 93, 252, 0.25);
        opacity: 0;
        transform: scale(0);
        transition: all 0.2s ease;
    }

    .nav-item.active .active-indicator {
        opacity: 1;
        transform: scale(1);
    }

    /* Badges - COMPACT */
    .nav-badge {
        font-size: 0.6rem;
        padding: 0.15rem 0.4rem;
        border-radius: 16px;
        font-weight: 600;
        min-width: 20px;
        text-align: center;
        transition: all 0.2s ease;
        margin-left: 0.4rem;
        color: white;
    }

    .nav-item:hover .nav-badge {
        transform: scale(1.02);
    }

    .nav-badge.urgent {
        background: linear-gradient(135deg, #ef4444, #f87171);
        animation: pulse 2s infinite;
    }

    .nav-badge.warning {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }

    .nav-badge.success {
        background: linear-gradient(135deg, #10b981, #34d399);
    }

    .nav-badge.info {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.02);
        }
    }

    /* ========== DROPDOWN LAPORAN - COMPACT ========== */
    .dropdown-container {
        width: 100%;
        margin-bottom: 0.1rem;
        border-radius: 10px;
        overflow: hidden;
    }

    .dropdown-header {
        display: flex;
        align-items: center;
        padding: 0.55rem 0.85rem;
        color: #64748b;
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .dropdown-header:hover {
        background: rgba(109, 93, 252, 0.08);
        color: #6d5dfc;
        transform: translateX(3px);
    }

    .dropdown-header:hover .nav-icon {
        transform: scale(1.03);
        color: #6d5dfc;
    }

    .dropdown-arrow {
        margin-left: auto;
        transition: transform 0.2s ease;
    }

    .dropdown-arrow i {
        font-size: 0.8rem;
    }

    .dropdown-container.open .dropdown-arrow {
        transform: rotate(180deg);
    }

    .dropdown-content {
        display: none;
        padding: 0.3rem 0 0.3rem 1.8rem;
        background: transparent;
        flex-direction: column;
    }

    .dropdown-link {
        display: flex;
        align-items: center;
        padding: 0.4rem 0.7rem;
        color: #64748b;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        font-size: 0.8rem;
        margin-bottom: 0.1rem;
    }

    .dropdown-link i {
        margin-right: 0.6rem;
        color: #6d5dfc;
        font-size: 0.8rem;
        width: 18px;
    }

    .dropdown-link:hover {
        background: rgba(109, 93, 252, 0.08);
        color: #6d5dfc;
        transform: translateX(5px);
    }

    .dropdown-link.active {
        background: rgba(109, 93, 252, 0.12);
        color: #6d5dfc;
        font-weight: 500;
    }

    /* Animation */
    .nav-item {
        animation: slideIn 0.4s ease-out forwards;
        opacity: 0;
        transform: translateX(-15px);
    }

    @keyframes slideIn {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .nav-item:nth-child(1) {
        animation-delay: 0.05s;
    }

    .nav-item:nth-child(2) {
        animation-delay: 0.08s;
    }

    .nav-item:nth-child(3) {
        animation-delay: 0.11s;
    }

    .nav-item:nth-child(4) {
        animation-delay: 0.14s;
    }

    .nav-item:nth-child(5) {
        animation-delay: 0.17s;
    }

    .nav-item:nth-child(6) {
        animation-delay: 0.20s;
    }

    .nav-item:nth-child(7) {
        animation-delay: 0.23s;
    }

    .nav-item:nth-child(8) {
        animation-delay: 0.26s;
    }

    .nav-item:nth-child(9) {
        animation-delay: 0.29s;
    }

    .dropdown-container {
        animation-delay: 0.32s;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar-nav {
            width: 260px;
            padding: 0.8rem 0.65rem;
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            font-size: 1.1rem;
        }

        .brand-text {
            font-size: 1.05rem;
        }

        .nav-item {
            padding: 0.45rem 0.7rem;
        }

        .nav-text {
            font-size: 0.8rem;
        }

        .dropdown-header {
            padding: 0.45rem 0.7rem;
        }

        .dropdown-link {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
        }
    }
</style>

<script>
    function toggleDropdown(element) {
        var container = element.parentElement;
        var content = container.querySelector('.dropdown-content');
        var arrow = element.querySelector('.dropdown-arrow i');

        container.classList.toggle('open');

        if (content.style.display === 'none' || content.style.display === '') {
            content.style.display = 'flex';
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        } else {
            content.style.display = 'none';
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
    }

    function handleSubmenuClick(element, title) {
        document.querySelectorAll('.dropdown-link').forEach(function(link) {
            link.classList.remove('active');
        });
        element.classList.add('active');

        var currentPageSpan = document.querySelector('#currentPage span');
        if (currentPageSpan) {
            currentPageSpan.textContent = title;
        }
        document.title = 'SIAGA | ' + title;

        return true;
    }
</script>
