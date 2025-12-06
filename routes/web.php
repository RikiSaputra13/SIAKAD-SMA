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
use App\Http\Controllers\Guru\DashboardGuruController;
use App\Http\Controllers\Guru\UjianHarianController;
use App\Http\Controllers\Guru\UjianTengahSemesterController;
use App\Http\Controllers\Guru\UjianAkhirSemesterController;
use App\Http\Controllers\Siswa\SiswaNilaiController;

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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    
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

    Route::resource('absensi', AdminAbsensiController::class)->except(['show']);
    
   
    // HAPUS generate token dari admin karena sekarang guru yang membuat token
    // Route::post('generate-token', [TokenController::class, 'generateToken'])->name('generate-token');

});

/*
|--------------------------------------------------------------------------
| SISWA AREA
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    // Dashboard & Jadwal
    Route::get('/dashboard', [SiswaController::class, 'index'])->name('dashboard');
    Route::get('/jadwal', [SiswaController::class, 'jadwalIndex'])->name('jadwal.index');

    // Absensi
    Route::get('/absensi', [SiswaAbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [SiswaAbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/riwayat', [SiswaAbsensiController::class, 'history'])->name('absensi.history');
    Route::get('/absensi/dashboard', [AbsensiController::class, 'dashboard'])->name('siswa.absensi.dashboard');

    // Pembayaran
    Route::get('/pembayaran', [SiswaController::class, 'pembayaranIndex'])->name('pembayaran.index');

    // Profil
    Route::get('/profile', [SiswaController::class, 'profile'])->name('profile');

    // Ubah Password
    Route::get('/ubah-password', [SiswaController::class, 'showChangePasswordForm'])->name('ubah-password.form');
    Route::post('/ubah-password', [SiswaController::class, 'changePassword'])->name('ubah-password');

    // Ujian Harian
    Route::get('/ujian-harian', [App\Http\Controllers\Siswa\SiswaUjianController::class, 'index'])->name('ujian-harian.index');
    Route::get('/ujian-harian/{id}', [App\Http\Controllers\Siswa\SiswaUjianController::class, 'show'])->name('ujian-harian.show');
    Route::post('/ujian-harian/{id}/submit', [App\Http\Controllers\Siswa\SiswaUjianController::class, 'submit'])->name('ujian-harian.submit');

    // Nilai
    Route::get('/nilai', [SiswaNilaiController::class, 'index'])->name('nilai.index');
});

/*
|--------------------------------------------------------------------------
| GURU AREA
|--------------------------------------------------------------------------
*/

Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    // Dashboard & Profil
    Route::get('/dashboard', [DashboardGuruController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardGuruController::class, 'profile'])->name('profile');

    // ABSENSI ROUTES UNTUK GURU
    Route::resource('absensi', App\Http\Controllers\Guru\AbsensiController::class)->except(['show']);

    // TOKEN MANAGEMENT - FITUR BARU
    Route::post('/generate-token', [DashboardGuruController::class, 'generateToken'])->name('generate-token');
    Route::get('/get-mapel-by-kelas/{kelasId}', [DashboardGuruController::class, 'getMapelByKelas'])->name('getMapelByKelas');
    Route::get('/tokens/active', [DashboardGuruController::class, 'getActiveTokens'])->name('tokens.active');
    
    Route::delete('/token/{id}', [DashboardGuruController::class, 'deleteToken'])->name('delete-token');

    // Daftar Siswa (baca saja)
    Route::get('/siswa', [App\Http\Controllers\Guru\GuruSiswaController::class, 'index'])->name('siswa.index');

    // Absensi Siswa (baca saja)
    Route::get('/siswa/absensi', [App\Http\Controllers\Guru\GuruSiswaController::class, 'absensiSiswa'])->name('siswa.absensi');

    // Jadwal Mengajar Guru
    Route::get('/jadwal', [DashboardGuruController::class, 'jadwalGuru'])->name('jadwal.index');

    // Penilaian
    Route::get('/penilaian/list', [App\Http\Controllers\Guru\PenilaianController::class, 'index'])->name('penilaian.list');
    Route::get('/penilaian/create', [App\Http\Controllers\Guru\PenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('/penilaian', [App\Http\Controllers\Guru\PenilaianController::class, 'store'])->name('penilaian.store');
    Route::get('/penilaian/{penilaian}/edit', [App\Http\Controllers\Guru\PenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::put('/penilaian/{penilaian}', [App\Http\Controllers\Guru\PenilaianController::class, 'update'])->name('penilaian.update');
    Route::delete('/penilaian/{penilaian}', [App\Http\Controllers\Guru\PenilaianController::class, 'destroy'])->name('penilaian.destroy');
    
    // Get siswa berdasarkan kelas
    Route::get('/penilaian/get-siswa/{kelasId}', [App\Http\Controllers\Guru\PenilaianController::class, 'getSiswa'])
    ->name('penilaian.getSiswa');

    // Ujian Harian 
    Route::prefix('ujian-harian')->name('penilaian.uh.')->group(function () {
        Route::get('/', [App\Http\Controllers\Guru\UjianHarianController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Guru\UjianHarianController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Guru\UjianHarianController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Guru\UjianHarianController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Guru\UjianHarianController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Guru\UjianHarianController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Guru\UjianHarianController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/publish', [App\Http\Controllers\Guru\UjianHarianController::class, 'publish'])->name('publish');
        Route::get('/{id}/download-soal', [App\Http\Controllers\Guru\UjianHarianController::class, 'downloadSoal'])->name('download.soal');
        Route::get('/{id}/show-soal', [App\Http\Controllers\Guru\UjianHarianController::class, 'showSoal'])->name('show.soal');
        Route::get('/{id}/download-kunci', [App\Http\Controllers\Guru\UjianHarianController::class, 'downloadKunci'])->name('download.kunci');
    
        Route::get('/{id}/submissions', [UjianHarianController::class, 'showSubmissions'])->name('submissions');
        Route::get('/{ujian}/submissions/{pengumpulan}/download', [UjianHarianController::class, 'downloadJawaban'])->name('download.jawaban');
        Route::get('/{ujian}/submissions/{pengumpulan}/show', [UjianHarianController::class, 'showJawaban'])->name('show.jawaban');
        Route::post('/{ujian}/submissions/{pengumpulan}/nilai', [UjianHarianController::class, 'updateNilai'])->name('update.nilai');
    });

    // Routes untuk UTS
    Route::prefix('ujian-tengah-semester')->name('penilaian.uts.')->group(function () {
        Route::get('/', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'showSoal'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/publish', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'publish'])->name('publish');
        Route::get('/{id}/download-soal', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'downloadSoal'])->name('download.soal');
        Route::get('/{id}/download-kunci', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'downloadKunci'])->name('download.kunci');

        Route::get('/{id}/submissions', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'showSubmissions'])->name('submissions');
        Route::get('/{ujian}/submissions/{pengumpulan}/download', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'downloadJawaban'])->name('download.jawaban');
        Route::get('/{ujian}/submissions/{pengumpulan}/show', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'showJawaban'])->name('show.jawaban');
        Route::post('/{ujian}/submissions/{pengumpulan}/nilai', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'updateNilai'])->name('update.nilai');
    });

    // Routes untuk UAS
    Route::prefix('ujian-akhir-semester')->name('penilaian.uas.')->group(function () {
        Route::get('/', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'showSoal'])->name('show.soal');
        Route::get('/{id}/edit', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/publish', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'publish'])->name('publish');
        Route::get('/{id}/download-soal', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'downloadSoal'])->name('download.soal');
        Route::get('/{id}/download-kunci', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'downloadKunci'])->name('download.kunci');

        Route::get('/{id}/submissions', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'showSubmissions'])->name('submissions');
        Route::get('/{ujian}/submissions/{pengumpulan}/download', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'downloadJawaban'])->name('download.jawaban');
        Route::get('/{ujian}/submissions/{pengumpulan}/show', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'showJawaban'])->name('show.jawaban');
        Route::post('/{ujian}/submissions/{pengumpulan}/nilai', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'updateNilai'])->name('update.nilai');
    });

    // Alias untuk kemudahan
    Route::get('/uts', [App\Http\Controllers\Guru\UjianTengahSemesterController::class, 'index'])->name('penilaian.uts');
    Route::get('/uas', [App\Http\Controllers\Guru\UjianAkhirSemesterController::class, 'index'])->name('penilaian.uas');
    
    // Alias untuk kemudahan
    Route::get('/uh', [App\Http\Controllers\Guru\UjianHarianController::class, 'index'])->name('penilaian.uh');
});