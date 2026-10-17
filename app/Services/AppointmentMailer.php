<?php

namespace App\Services;

use App\Mail\AppointmentMail;
use App\Mail\NewRequestMail;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Envoie les emails SANS jamais faire planter le site :
 * si l'envoi échoue (mauvais mot de passe SMTP, panne…), l'erreur est écrite dans
 * storage/logs/laravel.log et la méthode renvoie false, pour que l'admin soit prévenu.
 */
class AppointmentMailer
{
    // $event : received | confirmed | refused | cancelled
    public function toClient(Appointment $appointment, string $event): bool
    {
        try {
            Mail::to($appointment->client_email)->send(new AppointmentMail($appointment, $event));

            return true;
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    // Prévient le prestataire qu'une nouvelle demande est arrivée
    public function toProvider(Appointment $appointment): bool
    {
        $to = config('patte.notify_to');

        if (! $to) {
            return false;
        }

        try {
            Mail::to($to)->send(new NewRequestMail($appointment));

            return true;
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }
}
