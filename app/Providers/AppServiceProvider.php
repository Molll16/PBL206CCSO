<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Agen; // <-- Pastikan namespace model Agen kamu sudah benar di sini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Biarkan kosong seperti bawaannya
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Menyuntikkan data $list_agen otomatis ke file header di setiap halaman customer
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('list_agen', Agen::where('user_id', Auth::id())->get());
            }
        });
    }
}