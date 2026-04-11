<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataDiriController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SiswaController;
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
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');