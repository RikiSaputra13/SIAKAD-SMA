<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\TokenController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Siswa\SiswaController;
use App\Http\Controllers\Siswa\AbsensiController as SiswaAbsensiController;

/*
|--------------------------------------------------------------------------
| Halaman Utama → Login
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Ganti Password Langsung (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/ganti-password-langsung', [SiswaController::class, 'showDirectPasswordChangeForm'])->name('password.direct-change');
Route::post('/ganti-password-langsung', [SiswaController::class, 'directPasswordChange'])->name('password.update-direct');

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
    // Dashboard & Profil
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardAdminController::class, 'profile'])->name('profile');

    // CRUD: Guru, Siswa, Kelas, Jadwal
    Route::resource('guru', GuruController::class);
    Route::resource('siswa', AdminSiswaController::class);
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
    Route::resource('jadwal', JadwalController::class);

    // Rekap & Export Absensi → taruh dulu sebelum resource biar gak bentrok
    Route::get('absensi/rekap', [AdminAbsensiController::class, 'rekap'])->name('absensi.rekap');
    Route::get('absensi/rekap/cetak', [AdminAbsensiController::class, 'cetakRekap'])->name('absensi.rekap.cetak');
    Route::get('absensi/export-excel', [AdminAbsensiController::class, 'exportExcel'])->name('absensi.export-excel');

    // 🔥 Tambahkan Filter Absensi
    Route::get('absensi/filter', [AdminAbsensiController::class, 'filter'])->name('absensi.filter');

    // CRUD Absensi (tanpa show)
    Route::resource('absensi', AdminAbsensiController::class)->except(['show']);

    // Token Absensi
    Route::post('generate-token', [TokenController::class, 'generateToken'])->name('generate-token');

    // CRUD Pembayaran (TANPA SHOW)
    Route::resource('pembayaran', PembayaranController::class)->except(['show']);
    
    // 🔥 TAMBAHAN: Rekap & Export Pembayaran
    Route::post('pembayaran/rekap', [PembayaranController::class, 'rekap'])->name('pembayaran.rekap');
    Route::get('pembayaran/cetak-pdf', [PembayaranController::class, 'cetakPdf'])->name('pembayaran.cetak-pdf');
    Route::get('pembayaran/cetak-excel', [PembayaranController::class, 'cetakExcel'])->name('pembayaran.cetak-excel');
});

/*
|--------------------------------------------------------------------------
| SISWA AREA
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(function () {
    // Dashboard & Jadwal
    Route::get('/dashboard', [SiswaController::class, 'index'])->name('dashboard');
    Route::get('/jadwal', [SiswaController::class, 'jadwalIndex'])->name('jadwal.index');

    // Absensi
    Route::get('/absensi', [SiswaAbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [SiswaAbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/riwayat', [SiswaAbsensiController::class, 'history'])->name('absensi.history');

    // Pembayaran
    Route::get('/pembayaran', [SiswaController::class, 'pembayaranIndex'])->name('pembayaran.index');

    // Profil
    Route::get('/profile', [SiswaController::class, 'profile'])->name('profile');

    // Ubah Password
    Route::get('/ubah-password', [SiswaController::class, 'showChangePasswordForm'])->name('ubah-password.form');
    Route::post('/ubah-password', [SiswaController::class, 'changePassword'])->name('ubah-password');
});