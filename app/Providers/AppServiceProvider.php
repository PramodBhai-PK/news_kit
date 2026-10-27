<?php

namespace App\Providers;

use App\Models\State;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');

        View::composer(['layouts.portal', 'portal.*'], function ($view) {
            $portalStates = collect();
            try {
                if (Schema::hasTable('states')) {
                    $portalStates = State::where('is_active', true)
                        ->with(['cities' => fn($q) => $q->where('is_active', true)->orderBy('name')])
                        ->orderBy('order')
                        ->get();
                }
            } catch (\Throwable $e) {
                // Failsafe during setup/migrations
            }

            $view->with([
                'portalStates' => $portalStates,
                'userCitySlug' => session('user_city_slug'),
                'userCityName' => session('user_city_name'),
            ]);
        });
    }
}
