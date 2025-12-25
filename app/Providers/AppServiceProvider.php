<?php

namespace App\Providers;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL; 
use Illuminate\Support\ServiceProvider;

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
        // 2. Tambahkan baris ini
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

         // 2. TAMBAHKAN KODE INI AGAR CSS MUNCUL DI NGROK
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
        // Atau kalau mau paksa terus (biar aman saat demo):
        URL::forceScheme('https');

    }
}
