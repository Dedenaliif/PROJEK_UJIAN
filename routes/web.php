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
    JurusanController,
    SertifikatController,
    SesiController,
    UjianStartController
};
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/



Route::get('/', function () {
    return redirect('/login');
})->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.authenticate');

Route::fallback(function () {

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
        Route::resource('siswa', SiswaController::class)->only(['index', 'store']);
        Route::resource('kelas', KelasController::class);
        Route::resource('jurusan', JurusanController::class);
        Route::resource('sesi', SesiController::class);

        Route::post('/import-siswa', [UserController::class, 'importCsv'])->name('siswa.import');
        Route::get('/user/template-csv', [UserController::class, 'downloadTemplate'])->name('siswa.template');
        Route::get('/user/download', [UserController::class, 'downloadUserCsv'])->name('user.download');
        Route::get('/siswa/export-csv', [SiswaController::class, 'exportCsv'])->name('siswa.exportCsv');
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
        Route::get('/ujianexportnilai', [BuatUjianController::class, 'exportSemuaNilai'])->name('ujian.exportSemuaNilai');
        Route::prefix('soal')->group(function () {
            Route::get('/{ujian}', [BuatSoalController::class, 'create'])->name('soal.create');
            Route::post('/{ujian}', [BuatSoalController::class, 'store'])->name('soal.store');
            Route::get('/{ujian}/edit/{id}', [BuatSoalController::class, 'edit'])->name('soal.edit');
            Route::put('/{id}', [BuatSoalController::class, 'update'])->name('soal.update');
            Route::delete('/{ujian}/{id}', [BuatSoalController::class, 'destroy'])->name('soal.destroy');
        });
        Route::post('/ujian/markupnilai', [BuatUjianController::class, 'simpanMarkup'])->name('ujian.markupnilai.simpan');
        Route::post('/ujian/sesi/simpan', [BuatUjianController::class, 'simpanSesi'])->name('ujian.sesi.simpan');
        Route::get('/markupnilai', [BuatUjianController::class, 'markupnilai'])->name('markup.nilai');
        Route::get('/ujian/exportDataMarkup', [BuatUjianController::class, 'exportDataMarkup'])->name('ujian.exportDataMarkup');
        Route::get('/sertifikat/downloadsemua', [SertifikatController::class, 'downloadSemuaSertifikat'])->name('downloadsemua');
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
        Route::get('/ujian/{ujianId}/sertifikat', [HalamanUjianController::class, 'cetakSertifikat'])
            ->name('ujian.sertifikat')
            ->middleware('auth'); // Pastikan siswa wajib login
        // ujian
        Route::post('/ujian/{ujian}/start', [HalamanUjianController::class, 'start'])->name('ujianstart.start');
        Route::get('/ujian/{ujian}', [HalamanUjianController::class, 'show'])->name('ujianstart.show');
        Route::post('/ujian/{ujian}/save', [HalamanUjianController::class, 'save'])->name('ujianstart.save');
        Route::post('/ujian/{ujian}/selesai', [HalamanUjianController::class, 'selesai'])->name('ujianstart.selesai');
        Route::get('/sertifikat/{id}/download', [SertifikatController::class, 'layoutDummy']);
        // hasil ujian
        Route::get('/ujian/{ujian}/hasil', [HalamanUjianController::class, 'hasil'])->name('ujian.hasil');

        // check status ujian
        Route::get('/cek-status-ujian', [BuatUjianController::class, 'checkStatus'])->name('ujian.cekStatus');

        // latihan Ujian
        Route::post('/ujian/{id}/latihan-start', [UjianStartController::class, 'latihanStart'])->name('ujian.latihan.start');
        Route::get('/ujian/{id}/latihan', [UjianStartController::class, 'latihanShow'])->name('ujian.latihan.show');
        Route::post('/ujian/{id}/latihan-save', [UjianStartController::class, 'latihanSave'])->name('ujian.latihan.save');
        Route::post('/ujian/{id}/latihan-selesai', [UjianStartController::class, 'latihanSelesai'])->name('ujian.latihan.selesai');
        Route::post('/latihan-selesai/{id}', [UjianStartController::class, 'latihanSelesai'])->name('ujianstart.latihanSelesai');
        Route::get('/ujian/{id}/latihan-hasil', [UjianStartController::class, 'hasilLatihan'])->name('ujian.latihan.hasil');
        Route::get('/latihan-check', [BuatUjianController::class, 'checkLatihan'])->name('latihan.check');
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
