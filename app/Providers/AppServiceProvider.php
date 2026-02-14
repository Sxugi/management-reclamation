<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator;
use App\Models\Lahan;
use App\Models\User;
use App\Models\Plot;
use App\Observers\PlotObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register model observers
        Plot::observe(PlotObserver::class);

        // Global view composer to share lahan_id based on route parameters
        View::composer('*', function ($view) {
            $lahanId = null;
            $parameters = Request::route()?->parameters() ?? [];

            foreach ($parameters as $param) {
                if ($param instanceof \App\Models\Lahan) {
                    $lahanId = $param->lahan_id;
                    break;
                }

                if ($param instanceof \App\Models\Plot && $param->lahan_id) {
                    $lahanId = $param->lahan_id;
                    break;
                }

                if (is_object($param) && property_exists($param, 'lahan_id')) {
                    $lahanId = $param->lahan_id;
                    break;
                }
            }

            $view->with('globalLahanId', $lahanId);
        });

        // Custom route model bindings
        Route::bind('user', function ($value) {
            return User::where('user_id', $value)->firstOrFail();
        });

        // Pagination default view
        Paginator::defaultView('components.main.pagination');
    }
}
