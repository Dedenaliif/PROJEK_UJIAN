<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    LoginController,
    DataDiriController,
    HalamanUjianController,
    BuatUjianController,
    BuatSoalController,
    HalamanHistoryController,
    HalamanMonitoringController,
    UserController,
    SiswaController,
    KelasController,
    JurusanController
};
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {

    if (Auth::check()) {

        $user = Auth::user();

        switch ($user->role) {

            case 'admin':
                return redirect('/admin/dashboard');

            case 'penguji':
                return redirect('/penguji/ujian');

            case 'pengawas':
                return redirect('/pengawas/monitoring');

            case 'siswa':
                return redirect('/siswa/datadiri');
        }
    }

    return app(LoginController::class)->index();

})->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.authenticate');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    | Mengelola master data
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('user', UserController::class)->except(['show']);
        Route::resource('siswa', SiswaController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('jurusan', JurusanController::class);

        Route::post('/import-siswa', [UserController::class, 'importCsv'])->name('siswa.import');
        Route::get('/user/template-csv', [UserController::class, 'downloadTemplate'])->name('siswa.template');
    });

    /*
        |--------------------------------------------------------------------------
        | PENGUJI
        |--------------------------------------------------------------------------
        | Membuat soal
        */
    Route::middleware('role:penguji')->prefix('penguji')->group(function () {

        Route::get('/ujian', [BuatUjianController::class, 'index'])->name('ujian.index');
        Route::get('/ujian/create', [BuatUjianController::class, 'create'])->name('ujian.create');
        Route::post('/ujian', [BuatUjianController::class, 'store'])->name('ujian.store');
        Route::get('/ujian/report/{ujian}', [BuatUjianController::class, 'report'])
            ->name('ujian.report');
        Route::get('/ujian/export/{ujian}', [BuatUjianController::class, 'exportCSV'])->name('ujian.export');

        Route::prefix('soal')->group(function () {
            Route::get('/{ujian}', [BuatSoalController::class, 'create'])->name('soal.create');
            Route::post('/{ujian}', [BuatSoalController::class, 'store'])->name('soal.store');
            Route::get('/{ujian}/edit/{id}', [BuatSoalController::class, 'edit'])->name('soal.edit');
            Route::put('/{id}', [BuatSoalController::class, 'update'])->name('soal.update');
            Route::delete('/{ujian}/{id}', [BuatSoalController::class, 'destroy'])->name('soal.destroy');
        });
    });

    /*
                |--------------------------------------------------------------------------
                | SISWA
                |--------------------------------------------------------------------------
                | Isi data diri + ujian
                */
    Route::middleware('role:siswa')->prefix('/siswa')->group(function () {
        Route::get('/ujianhistory/{id}/', [HalamanHistoryController::class, 'history'])->name('ujian.history');
        // data diri
        Route::get('/datadiri', [DataDiriController::class, 'index'])->name('datadiri.index');
        Route::post('/datadiri', [DataDiriController::class, 'store'])->name('datadiri.store');

        // list ujian
        Route::get('/ujian', [BuatUjianController::class, 'index'])->name('siswa.ujian');

        // ujian
        Route::post('/ujian/{ujian}/start', [HalamanUjianController::class, 'start'])->name('ujianstart.start');
        Route::get('/ujian/{ujian}', [HalamanUjianController::class, 'show'])->name('ujianstart.show');
        Route::post('/ujian/{ujian}/save', [HalamanUjianController::class, 'save'])->name('ujianstart.save');
        Route::post('/ujian/{ujian}/selesai', [HalamanUjianController::class, 'selesai'])->name('ujianstart.selesai');

        // hasil ujian
        Route::get('/ujian/{ujian}/hasil', [HalamanUjianController::class, 'hasil'])->name('ujian.hasil');

        // check status ujian
        Route::get('/cek-status-ujian', [BuatUjianController::class, 'checkStatus'])->name('ujian.cekStatus');
    });

    /*
                    |--------------------------------------------------------------------------
                    | PENGAWAS
                    |--------------------------------------------------------------------------
                    */
    Route::middleware('role:pengawas')->prefix('pengawas')->group(function () {

        Route::get('/monitoring', [HalamanMonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/ujianmonitoring/{id}', [HalamanMonitoringController::class, 'monitor'])->name('ujian.monitoring');

        Route::get('/monitoring-data/{id}', [HalamanMonitoringController::class, 'getMonitoringData'])->name('monitoring.data');
    });
});
