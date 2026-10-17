<?php

namespace App\Providers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Les dates (vendredi, octobre…) s'affichent en français
        Carbon::setLocale('fr');

        // Le layout admin reçoit le nombre de demandes en attente
        // (pour le badge à côté de « Demandes » dans le menu)
        View::composer('layouts.admin', function ($view) {
            $view->with(
                'pendingCount',
                Appointment::where('status', 'en_attente')->count()
            );
        });
    }
}
