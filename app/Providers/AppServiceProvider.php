<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Models\Lahan;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
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
    }
}
