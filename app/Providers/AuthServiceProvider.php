<?php

namespace App\Providers;

use App\Models\Lahan;
use App\Models\Plot;
use App\Models\ProgresReklamasi;
use App\Models\TargetProgresReklamasi;
use App\Models\AnggaranReklamasi;
use App\Models\DataGudang;
use App\Models\DataReklamasi;
use App\Models\BiayaReklamasi;
use App\Models\Dokumentasi;
use App\Models\ReklamasiFile;
use App\Policies\LahanPolicy;
use App\Policies\PlotPolicy;
use App\Policies\ProgresReklamasiPolicy;
use App\Policies\TargetProgresReklamasiPolicy;
use App\Policies\AnggaranReklamasiPolicy;
use App\Policies\DataGudangPolicy;
use App\Policies\DataReklamasiPolicy;
use App\Policies\BiayaReklamasiPolicy;
use App\Policies\DokumentasiPolicy;
use App\Policies\ReklamasiFilePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Lahan::class => LahanPolicy::class,
        Plot::class => PlotPolicy::class, 
        ProgresReklamasi::class => ProgresReklamasiPolicy::class,
        TargetProgresReklamasi::class => TargetProgresReklamasiPolicy::class,
        AnggaranReklamasi::class => AnggaranReklamasiPolicy::class,
        DataGudang::class => DataGudangPolicy::class,
        DataReklamasi::class => DataReklamasiPolicy::class,
        BiayaReklamasi::class => BiayaReklamasiPolicy::class,
        Dokumentasi::class => DokumentasiPolicy::class,
        ReklamasiFile::class => ReklamasiFilePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            // Admin can do anything
            if ($user->isAdmin()) {
                return true;
            }
            
            // Let policy decide for non-admins
            return null;
        });
    }
}