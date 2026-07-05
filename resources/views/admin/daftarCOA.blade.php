{{-- resources/views/admin/daftarCOA.blade.php --}}

@extends('layouts.app')

@section('title', 'Daftar COA')
@section('icon', 'journal-text')

@section('content')
    <div class="coa-container">
        <!-- Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Daftar Chart of Account (COA)</h2>
                <p class="page-subtitle">Pengelolaan kode rekening anggaran</p>
            </div>

            {{-- Tombol Tambah COA hanya untuk Admin dan Kepala Gizi --}}
            @if (in_array(auth()->user()->role, ['admin', 'kepala_gizi']))
                <a href="{{ route('admin.coa.create') }}" class="btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah COA
                </a>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total COA</span>
                    <span class="stat-value">{{ $rekening->total() }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Rekening Utama</span>
                    <span class="stat-value">
                        {{ \App\Models\KodeRekening::where('tipe', 'utama')->count() }}
                    </span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-diagram-2"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Rekening Turunan</span>
                    <span class="stat-value">
                        {{ \App\Models\KodeRekening::where('tipe', 'turunan')->count() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="filter-section">
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="searchCoa" placeholder="Cari kode atau nama COA..."
                    value="{{ request('search') }}">
            </div>
            <div class="filter-wrapper">
                <select class="filter-select" id="tipeFilter">
                    <option value="">Semua Tipe</option>
                    <option value="utama" {{ request('tipe') == 'utama' ? 'selected' : '' }}>Utama</option>
                    <option value="turunan" {{ request('tipe') == 'turunan' ? 'selected' : '' }}>Turunan</option>
                </select>
                <button class="btn-refresh" onclick="window.location.href='{{ route('admin.daftarCOA') }}'">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <!-- COA Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode COA</th>
                        <th>Nama Rekening</th>
                        <th>Tipe</th>
                        <th>Dibuat</th>
                        <th>Terakhir Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="coaTableBody">
                    @forelse($rekening as $index => $item)
                        <tr>
                            <td>{{ $rekening->firstItem() + $index }}</td>
                            <td>
                                <div class="coa-info">
                                    <div class="coa-code">{{ $item->kode }}</div>
                                </div>
                            </td>
                            <td>{{ $item->nama_rekening }}</td>
                            <td>
                                <span class="badge-role role-{{ $item->tipe }}">
                                    <i class="bi bi-{{ $item->tipe === 'utama' ? 'diagram-3' : 'diagram-2' }}"></i>
                                    {{ ucfirst($item->tipe) }}
                                </span>
                            </td>
                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $item->updated_at->diffForHumans() }}</td>
                            <td>
                                <div class="action-buttons">
                                    {{-- Tombol View untuk semua role --}}
                                    <a href="{{ route('admin.coa.show', $item->id) }}" class="btn-icon btn-view"
                                        title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Tombol Edit untuk Admin dan Kepala Gizi --}}
                                    @if (in_array(auth()->user()->role, ['admin', 'kepala_gizi']))
                                        <a href="{{ route('admin.coa.edit', $item->id) }}" class="btn-icon btn-edit"
                                            title="Edit COA">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif

                                    {{-- Tombol Delete HANYA UNTUK ADMIN --}}
                                    @if (auth()->user()->role == 'admin')
                                        <button class="btn-icon btn-delete" onclick="deleteCoa({{ $item->id }})"
                                            title="Hapus COA">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-journal-x" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="mt-3 text-muted">Belum ada data COA</p>
                                @if (in_array(auth()->user()->role, ['admin', 'kepala_gizi']))
                                    <a href="{{ route('admin.coa.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="bi bi-plus-lg"></i>
                                        Tambah COA Pertama
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
                    Total data: {{ $rekening->total() }} COA
                </small>
            </div>
            <div class="col-md-6 text-right">
                <small class="text-muted">
                    Halaman {{ $rekening->currentPage() }} dari {{ $rekening->lastPage() }}
                </small>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $rekening->links() }}
        </div>
    </div>

    <style>
        .coa-container {
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

        /* Button Primary */
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

        /* Stats Grid */
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
        }

        .stat-icon.bg-primary {
            background: linear-gradient(135deg, #6d5dfc, #22d3ee);
        }

        .stat-icon.bg-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .stat-icon.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
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

        /* Filter Section */
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

        /* Table */
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
            min-width: 1000px;
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

        /* COA Info */
        .coa-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .coa-code {
            font-weight: 600;
            color: #1e293b;
            font-family: monospace;
            font-size: 1.1rem;
        }

        /* Role Badge (Tipe Badge) */
        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .role-utama {
            background: #d1fae5;
            color: #10b981;
        }

        .role-turunan {
            background: #fed7aa;
            color: #f59e0b;
        }

        /* Action Buttons */
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

        /* Pagination */
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

        /* Responsive */
        @media (max-width: 768px) {
            .coa-container {
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Search functionality with debounce
            let searchTimer;
            $('#searchCoa').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    performSearch();
                }, 500);
            });

            // Tipe filter
            $('#tipeFilter').on('change', function() {
                performSearch();
            });

            function performSearch() {
                var search = $('#searchCoa').val();
                var tipe = $('#tipeFilter').val();

                window.location.href = '{{ route('admin.daftarCOA') }}?search=' + encodeURIComponent(search) +
                    '&tipe=' + encodeURIComponent(tipe);
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

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}'
                });
            @endif
        });

        // Delete COA - Hanya dijalankan jika user adalah Admin (tombol delete hanya muncul untuk admin)
        function deleteCoa(id) {
            Swal.fire({
                title: 'Hapus COA',
                text: 'Apakah Anda yakin ingin menghapus COA ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/coa/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'COA berhasil dihapus',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            let message = 'Terjadi kesalahan saat menghapus';
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
        }
    </script>
@endsection
