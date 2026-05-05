<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('local')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {

        $siswa = null;

        if (Auth::check() && Auth::user()->role == 'siswa') {
            $siswa = Siswa::where('user_id', Auth::id())->first();
        }

        $view->with('siswa', $siswa);

    });
    }
}
