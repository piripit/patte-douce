<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\BoardingDay;
use App\Services\AppointmentMailer;
use App\Services\StayCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function create(StayCalendar $calendar)
    {
        // Créneaux horaires réservables (promenade, visite), regroupés par jour
        $slotsByDay = Availability::bookable()
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($slot) => $slot->date->toDateString());

        $services = collect(config('patte.services'));

        return view('rendez-vous', [
            'slotsByDay' => $slotsByDay,
            'stayRanges' => $calendar->openRanges(),
            'services' => $services,
            'maxStayDays' => (int) config('patte.max_stay_days', 60),
        ]);
    }

    public function store(Request $request, StayCalendar $calendar, AppointmentMailer $mailer)
    {
        // Piège anti-robots : ce champ est invisible pour un humain.
        // S'il est rempli, on fait semblant d'accepter sans rien enregistrer.
        if ($request->filled('champ_piege')) {
            return redirect()->route('rendez-vous')->with('booking_sent', true);
        }

        // Le type du service choisi (créneau horaire ou séjour) décide des champs à vérifier
        $services = collect(config('patte.services'));
        $service = $services->firstWhere('name', $request->input('service'));
        $type = $service['type'] ?? 'slot';

        $rules = [
            'service' => ['required', Rule::in($services->pluck('name')->all())],
            'dog_name' => ['required', 'string', 'max:100'],
            'client_name' => ['required', 'string', 'max:100'],
            'client_email' => ['required', 'email', 'max:150'],
            'client_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9 +().-]{6,20}$/'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];

        if ($type === 'stay') {
            $rules['start_date'] = ['required', 'date', 'after_or_equal:tomorrow'];
            $rules['end_date'] = ['required', 'date', 'after_or_equal:start_date'];
        } else {
            $rules['availability_id'] = ['required', 'exists:availabilities,id'];
        }

        $data = $request->validate($rules, [
            'start_date.after_or_equal' => "La date d'arrivée doit être à partir de demain.",
        ]);

        $start = null;
        $end = null;

        if ($type === 'stay') {
            $start = Carbon::parse($data['start_date'])->startOfDay();
            $end = Carbon::parse($data['end_date'])->startOfDay();
            $max = (int) config('patte.max_stay_days', 60);

            if ($calendar->daysBetween($start, $end) > $max) {
                throw ValidationException::withMessages([
                    'end_date' => "Un séjour ne peut pas dépasser {$max} jours en réservation en ligne. Pour une durée plus longue, contactez-moi.",
                ]);
            }
        }

        // Transaction + verrou : si deux clients envoient une demande pour les mêmes
        // dates au même instant, une seule passe, l'autre reçoit un message précis.
        $appointment = DB::transaction(function () use ($data, $type, $calendar, $start, $end) {
            if ($type === 'stay') {
                BoardingDay::whereBetween('date', [$start->toDateString(), $end->toDateString()])
                    ->orderBy('date')
                    ->lockForUpdate()
                    ->get();

                $problem = $calendar->firstProblem($start, $end);

                if ($problem) {
                    throw ValidationException::withMessages(['start_date' => $problem]);
                }

                return Appointment::create($data + ['type' => 'stay']);
            }

            Availability::whereKey($data['availability_id'])->lockForUpdate()->first();

            $stillFree = Availability::bookable()
                ->whereKey($data['availability_id'])
                ->exists();

            if (! $stillFree) {
                throw ValidationException::withMessages([
                    'availability_id' => "Ce créneau vient d'être pris ou n'est plus disponible. Choisissez-en un autre.",
                ]);
            }

            return Appointment::create($data + ['type' => 'slot']);
        });

        // Les emails partent APRÈS l'enregistrement : un échec d'envoi ne perd jamais la demande
        $mailSent = $mailer->toClient($appointment, 'received');
        $mailer->toProvider($appointment);

        return redirect()->route('rendez-vous')
            ->with('booking_sent', true)
            ->with('booking_mail', $mailSent);
    }
}
