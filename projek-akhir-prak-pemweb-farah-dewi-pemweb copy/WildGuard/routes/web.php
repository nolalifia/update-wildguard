<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\ArtikelSayaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KuisSoalController; // BARU
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanSayaController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\SpesiesController;
use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Route;

// ---------- Situs publik (tampilan WildGuard) ----------
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/lapor', [HomeController::class, 'kirimLaporan'])->name('lapor.kirim');

// BARU: data titik untuk peta publik
Route::get('/peta/laporan', [HomeController::class, 'sebaran'])->name('peta.sebaran');

Route::get('/landing', [HomeController::class, 'index'])->name('landing');

// ---------- Login / daftar ----------
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::post('/keluar', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ---------- Pelapor terdaftar: kelola laporan sendiri ----------
Route::middleware('auth')->prefix('laporan-saya')->name('laporan-saya.')->group(function () {
    Route::get('/', [LaporanSayaController::class, 'index'])->name('index');
    Route::get('/{laporan}/edit', [LaporanSayaController::class, 'edit'])->name('edit');
    Route::put('/{laporan}', [LaporanSayaController::class, 'update'])->name('update');
    Route::delete('/{laporan}', [LaporanSayaController::class, 'destroy'])->name('destroy');
});

// ---------- Pelapor/penulis terdaftar: usulan artikel sendiri ----------
Route::middleware('auth')->prefix('artikel-saya')->name('artikel-saya.')->group(function () {
    Route::get('/', [ArtikelSayaController::class, 'index'])->name('index');
    Route::get('/buat', [ArtikelSayaController::class, 'create'])->name('create');
    Route::post('/', [ArtikelSayaController::class, 'store'])->name('store');
    Route::get('/{artikel}/edit', [ArtikelSayaController::class, 'edit'])->name('edit');
    Route::put('/{artikel}', [ArtikelSayaController::class, 'update'])->name('update');
    Route::delete('/{artikel}', [ArtikelSayaController::class, 'destroy'])->name('destroy');
});

// ---------- Panel admin ----------
Route::middleware(AdminOnly::class)->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('spesies', SpesiesController::class)->parameters(['spesies' => 'spesies']);
    Route::post('artikel/{artikel}/setujui', [ArtikelController::class, 'setujui'])->name('artikel.setujui');
    Route::resource('artikel', ArtikelController::class)->parameters(['artikel' => 'artikel']);
    // Laporan: admin melihat daftar, mengubah status, dan menghapus
    Route::resource('laporan', LaporanController::class)->only(['index', 'edit', 'update', 'destroy'])->parameters(['laporan' => 'laporan']);

    // BARU: kelola soal kuis
    Route::patch('kuis-soal/{soal}/toggle', [KuisSoalController::class, 'toggle'])->name('kuis-soal.toggle');
    Route::resource('kuis-soal', KuisSoalController::class)->except('show')->parameters(['kuis-soal' => 'soal']);

    // Data master: status | lokasi | kategori | jenis
    Route::prefix('master/{type}')
        ->where(['type' => 'status|lokasi|kategori|jenis', 'id' => '[0-9]+'])
        ->name('master.')
        ->controller(MasterController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
});