<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('layouts.admin', function ($view) {
            try {
                $unreadCount = \App\Models\Notifikasi::where('is_read', false)->count();
            } catch (\Throwable $e) {
                $unreadCount = 0;
            }
            $view->with('unreadCount', $unreadCount);
        });
    }
}
