<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\BoardingDay;
use App\Services\AppointmentMailer;

class AppointmentController extends Controller
{
    public function index()
    {
        $all = Appointment::with('availability')->get();
        $today = today()->toDateString();

        // 1. Demandes qui attendent une réponse (les plus anciennes d'abord)
        $pending = $all->where('status', 'en_attente')
            ->sortBy('created_at')
            ->values();

        // 2. Rendez-vous et séjours confirmés, pas encore terminés
        $upcoming = $all->where('status', 'confirme')
            ->filter(fn ($a) => $a->endsOn()->toDateString() >= $today)
            ->sortBy(fn ($a) => $a->startsOn()->toDateString().' '.($a->isStay() ? '00:00:00' : $a->availability->start_time))
            ->values();

        // 3. Historique : refusés, annulés, et confirmés déjà terminés
        $history = $all
            ->filter(fn ($a) => in_array($a->status, ['refuse', 'annule'], true)
                || ($a->status === 'confirme' && $a->endsOn()->toDateString() < $today))
            ->sortByDesc('updated_at')
            ->take(50)
            ->values();

        $openSlots = Availability::bookable()->count();
        $openDays = BoardingDay::where('is_open', true)->whereDate('date', '>=', today())->count();

        return view('admin.appointments', compact('pending', 'upcoming', 'history', 'openSlots', 'openDays'));
    }

    public function confirm(Appointment $appointment, AppointmentMailer $mailer)
    {
        if ($appointment->status !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $appointment->update(['status' => 'confirme']);

        return $this->result($appointment, $mailer->toClient($appointment, 'confirmed'), 'Rendez-vous confirmé.');
    }

    public function refuse(Appointment $appointment, AppointmentMailer $mailer)
    {
        if ($appointment->status !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $appointment->update(['status' => 'refuse']);

        return $this->result($appointment, $mailer->toClient($appointment, 'refused'), 'Demande refusée : les dates sont de nouveau disponibles.');
    }

    public function cancel(Appointment $appointment, AppointmentMailer $mailer)
    {
        if ($appointment->status !== 'confirme') {
            return back()->with('error', 'Seul un rendez-vous confirmé peut être annulé.');
        }

        $appointment->update(['status' => 'annule']);

        return $this->result($appointment, $mailer->toClient($appointment, 'cancelled'), 'Rendez-vous annulé : les dates sont de nouveau disponibles.');
    }

    // Message d'après action : dit clairement si le client a reçu son email ou non
    private function result(Appointment $appointment, bool $mailSent, string $message)
    {
        if ($mailSent) {
            return back()->with('success', $message.' Un email a été envoyé à '.$appointment->client_email.'.');
        }

        return back()->with('error', $message." Mais l'email n'a pas pu être envoyé : prévenez le client par téléphone au ".$appointment->client_phone.'.');
    }
}
