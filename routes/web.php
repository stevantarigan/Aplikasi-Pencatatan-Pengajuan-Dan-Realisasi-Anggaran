<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KodeRekeningController;
use App\Http\Controllers\PengajuanAnggaranController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\RealisasiAnggaranController;
use App\Http\Controllers\RencanaAnggaranController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MakananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ROOT → LOGIN
Route::get('/', function () {
    return redirect()->route('login');
});

// LOGIN
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.auth');

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== DASHBOARD ROUTE (PROTECTED) =====
// Route dashboard utama dengan controller
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route tambahan untuk dashboard dengan filter tahun (AJAX)
Route::middleware('auth')->get('/dashboard/filter/{year}', [DashboardController::class, 'filterByYear'])->name('dashboard.filter');

// ===== ROUTES DENGAN MIDDLEWARE AUTH =====
Route::middleware(['auth'])->group(function () {

    // ===== USER MANAGEMENT =====
    Route::resource('users', UserController::class);
    Route::put('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');

    // ===== ADMIN PREFIX =====
    Route::prefix('admin')->name('admin.')->group(function () {

        // ===== COA (CHART OF ACCOUNT) =====
        Route::prefix('coa')->name('coa.')->group(function () {
            Route::get('/', [KodeRekeningController::class, 'index'])->name('index');
            Route::get('/create', [KodeRekeningController::class, 'create'])->name('create');
            Route::post('/', [KodeRekeningController::class, 'store'])->name('store');
            Route::get('/{coa}', [KodeRekeningController::class, 'show'])->name('show');
            Route::get('/{coa}/edit', [KodeRekeningController::class, 'edit'])->name('edit');
            Route::put('/{coa}', [KodeRekeningController::class, 'update'])->name('update');
            Route::delete('/{coa}', [KodeRekeningController::class, 'destroy'])->name('destroy');

            // API route untuk select2
            Route::get('/data/select2', [KodeRekeningController::class, 'getData'])->name('data');
        });

        // ===== RENCANA ANGGARAN =====
        Route::prefix('rencana')->name('rencana.')->group(function () {
            // CRUD
            Route::get('/', [RencanaAnggaranController::class, 'index'])->name('index');
            Route::get('/create', [RencanaAnggaranController::class, 'create'])->name('create');
            Route::post('/', [RencanaAnggaranController::class, 'store'])->name('store');
            Route::get('/{rencana}', [RencanaAnggaranController::class, 'show'])->name('show');
            Route::get('/{rencana}/edit', [RencanaAnggaranController::class, 'edit'])->name('edit');
            Route::put('/{rencana}', [RencanaAnggaranController::class, 'update'])->name('update');
            Route::delete('/{rencana}', [RencanaAnggaranController::class, 'destroy'])->name('destroy');

            // ===== REKOMENDASI =====
            // Route untuk mendapatkan rekomendasi anggaran berdasarkan tahun (di halaman create)
            Route::get('/rekomendasi/data', [RencanaAnggaranController::class, 'getRekomendasi'])->name('rekomendasi');

            // Route untuk mendapatkan rekomendasi keputusan untuk sebuah rencana (di halaman index/show)
            Route::get('/rekomendasi/keputusan/{id}', [RencanaAnggaranController::class, 'getRekomendasiKeputusan'])->name('rekomendasi.keputusan');

            // ===== APPROVAL =====
            Route::post('/{rencana}/approve', [RencanaAnggaranController::class, 'approve'])->name('approve');
            Route::post('/{rencana}/reject', [RencanaAnggaranController::class, 'reject'])->name('reject');
            Route::post('/{rencana}/revision', [RencanaAnggaranController::class, 'requestRevision'])->name('revision');
        });

        // ===== MAKANAN (HARGA MAKANAN) =====
        Route::prefix('makanan')->name('makanan.')->group(function () {
            Route::get('/', [MakananController::class, 'index'])->name('index');
            Route::post('/update', [MakananController::class, 'update'])->name('update');
            Route::get('/get-harga', [MakananController::class, 'getHarga'])->name('get-harga');
        });

        // ===== ANGGARAN =====
        Route::prefix('anggaran')->name('anggaran.')->group(function () {
            Route::get('/', [AnggaranController::class, 'index'])->name('index');
            Route::get('/create', [AnggaranController::class, 'create'])->name('create');
            Route::post('/', [AnggaranController::class, 'store'])->name('store');
            Route::get('/{anggaran}', [AnggaranController::class, 'show'])->name('show');
            Route::get('/{anggaran}/edit', [AnggaranController::class, 'edit'])->name('edit');
            Route::put('/{anggaran}', [AnggaranController::class, 'update'])->name('update');
            Route::delete('/{anggaran}', [AnggaranController::class, 'destroy'])->name('destroy');
            Route::put('/{anggaran}/status', [AnggaranController::class, 'updateStatus'])->name('status');
            Route::get('/{anggaran}/detail', [AnggaranController::class, 'getDetail'])->name('detail');
        });

        // ===== PENGAJUAN ANGGARAN =====
        Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
            Route::get('/', [PengajuanAnggaranController::class, 'index'])->name('index');
            Route::get('/create', [PengajuanAnggaranController::class, 'create'])->name('create');
            Route::post('/', [PengajuanAnggaranController::class, 'store'])->name('store');
            Route::get('/{pengajuan}', [PengajuanAnggaranController::class, 'show'])->name('show');
            Route::get('/{pengajuan}/edit', [PengajuanAnggaranController::class, 'edit'])->name('edit');
            Route::put('/{pengajuan}', [PengajuanAnggaranController::class, 'update'])->name('update');
            Route::delete('/{pengajuan}', [PengajuanAnggaranController::class, 'destroy'])->name('destroy');
            Route::put('/{pengajuan}/approve', [PengajuanAnggaranController::class, 'approve'])->name('approve');
            Route::put('/{pengajuan}/reject', [PengajuanAnggaranController::class, 'reject'])->name('reject');
        });

        // ===== REALISASI ANGGARAN =====
        Route::prefix('realisasi')->name('realisasi.')->group(function () {
            Route::get('/', [RealisasiAnggaranController::class, 'index'])->name('index');
            Route::get('/create', [RealisasiAnggaranController::class, 'create'])->name('create');
            Route::post('/', [RealisasiAnggaranController::class, 'store'])->name('store');
            Route::get('/{realisasi}', [RealisasiAnggaranController::class, 'show'])->name('show');
            Route::get('/{realisasi}/edit', [RealisasiAnggaranController::class, 'edit'])->name('edit');
            Route::put('/{realisasi}', [RealisasiAnggaranController::class, 'update'])->name('update');
            Route::delete('/{realisasi}', [RealisasiAnggaranController::class, 'destroy'])->name('destroy');
            Route::get('/data/list', [RealisasiAnggaranController::class, 'getData'])->name('data');
        });

        // ===== PASIEN =====
        Route::prefix('pasien')->name('pasien.')->group(function () {
            Route::get('/', [PasienController::class, 'daftarPasien'])->name('index');
            Route::get('/create', [PasienController::class, 'create'])->name('create');
            Route::post('/', [PasienController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PasienController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PasienController::class, 'update'])->name('update');
            Route::delete('/{id}', [PasienController::class, 'destroy'])->name('destroy');
        });

        // ===== LAPORAN =====
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/rencana', [LaporanController::class, 'rencanaAnggaran'])->name('rencana');
            Route::get('/perubahan', [LaporanController::class, 'perubahanAnggaran'])->name('perubahan');
            Route::get('/lpj', [LaporanController::class, 'lpj'])->name('lpj');
        });

        // ===== BACKWARD COMPATIBILITY (UNTUK ROUTE LAMA YANG MASIH DIPAKAI) =====
        // Route ini untuk menjaga kompatibilitas dengan kode yang masih menggunakan nama route lama
        Route::get('/daftarCOA', [KodeRekeningController::class, 'index'])->name('daftarCOA');
        Route::get('/rencana-anggaran', [RencanaAnggaranController::class, 'index'])->name('rencana.index.old');
        Route::get('/rencana-anggaran/create', [RencanaAnggaranController::class, 'create'])->name('rencana.create.old');
        Route::post('/rencana-anggaran', [RencanaAnggaranController::class, 'store'])->name('rencana.store.old');
        Route::get('/daftar-pasien', [PasienController::class, 'daftarPasien'])->name('pasien.index.old');
    });
});