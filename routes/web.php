<?php

use App\Http\Controllers\BuatSoalController;
use App\Http\Controllers\BuatUjianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataDiriController;
use App\Http\Controllers\HalamanUjianController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UjianTipeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('ujian', [DashboardController::class, 'ujian'])->name('dashboard');
Route::resource('user', UserController::class);
Route::resource('kelas', KelasController::class);
Route::resource('jurusan', JurusanController::class);
Route::resource('siswa', SiswaController::class);
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/datadiri', [DataDiriController::class, 'index'])->name('datadiri.index');
Route::post('/datadiri', [DataDiriController::class, 'store'])->name('datadiri.store');
Route::get('/ujian', [BuatUjianController::class, 'index'])->name('ujian.index');
Route::get('/ujian/create', [BuatUjianController::class, 'create'])->name('ujian.create');
Route::post('/ujian', [BuatUjianController::class, 'store'])->name('ujian.store');
// Route::get('/ujian/{ujian}', [BuatSoalController::class, 'create'])->name('create.soal');
Route::post('/ujian/{ujian}/soal', [BuatSoalController::class, 'store'])
    ->name('store.soal');
Route::post('/ujianstart/{ujian}/start', [HalamanUjianController::class, 'start'])->name('ujianstart.start');
Route::get('/ujianstart/{ujian}', [HalamanUjianController::class, 'show'])->name('ujianstart.show');
Route::post('/ujianstart/{ujian}/save', [HalamanUjianController::class, 'save'])->name('ujianstart.save');
Route::post('/ujianstart/{ujian}/selesai', [HalamanUjianController::class, 'selesai'])->name('ujianstart.selesai');
Route::get('/ujian/{id}/tipe', [UjianTipeController::class, 'index'])->name('ujiantipe.index');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//CREATE SOAL UJIAN
Route::prefix('soal')->group(function () {
    Route::get('/{ujian}', [BuatSoalController::class, 'create'])->name('soal.create');
    Route::post('/{ujian}', [BuatSoalController::class, 'store'])->name('soal.store');
    Route::get('/{ujian}/edit/{id}', [BuatSoalController::class, 'edit'])->name('soal.edit');
    Route::put('/{id}', [BuatSoalController::class, 'update'])->name('soal.update');
    Route::delete('/{ujian}/{id}', [BuatSoalController::class, 'destroy'])->name('soal.destroy');
});
