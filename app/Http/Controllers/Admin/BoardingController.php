<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardingDay;
use App\Services\StayCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class BoardingController extends Controller
{
    // Calendrier des 3 prochains mois : quels jours sont ouverts, pleins, fermés
    public function index(StayCalendar $calendar)
    {
        $start = today()->startOfMonth();
        $end = today()->addMonths(2)->endOfMonth();

        $rows = BoardingDay::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(fn ($row) => $row->date->toDateString());

        $booked = $calendar->bookedPerDay($start, $end);

        $months = [];

        for ($m = $start->copy(); $m->lte($end); $m->addMonth()) {
            $first = $m->copy()->startOfMonth();
            $cells = [];

            for ($d = $first->copy(); $d->month === $first->month; $d->addDay()) {
                $key = $d->toDateString();
                $row = $rows->get($key);
                $count = $booked[$key] ?? 0;

                $state = match (true) {
                    $d->lt(today()) => 'past',
                    ! $row || ! $row->is_open => 'closed',
                    $count >= $row->capacity => 'full',
                    $count > 0 => 'partial',
                    default => 'open',
                };

                $cells[] = [
                    'day' => $d->day,
                    'state' => $state,
                    'booked' => $count,
                    'capacity' => $row?->capacity,
                    'title' => ucfirst($d->translatedFormat('l j F')),
                ];
            }

            $months[] = [
                'label' => ucfirst($first->translatedFormat('F Y')),
                'offset' => $first->dayOfWeekIso - 1, // cases vides avant le 1er (lundi = 0)
                'cells' => $cells,
            ];
        }

        return view('admin.boarding', compact('months'));
    }

    // Ouvrir une période aux gardes (un jour ou plusieurs semaines)
    public function open(Request $request, StayCalendar $calendar)
    {
        $data = $request->validate([
            'open_from' => ['required', 'date', 'after_or_equal:today'],
            'open_to' => ['required', 'date', 'after_or_equal:open_from'],
            'capacity' => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'open_from.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
        ]);

        $from = Carbon::parse($data['open_from'])->startOfDay();
        $to = Carbon::parse($data['open_to'])->startOfDay();

        if ($calendar->daysBetween($from, $to) > 366) {
            throw ValidationException::withMessages([
                'open_to' => 'La période ne peut pas dépasser 366 jours.',
            ]);
        }

        // On ne descend pas sous le nombre de chiens déjà prévus un jour donné
        $booked = $calendar->bookedPerDay($from, $to);
        ksort($booked);

        foreach ($booked as $date => $count) {
            if ($count > (int) $data['capacity']) {
                throw ValidationException::withMessages([
                    'capacity' => 'Le '.Carbon::parse($date)->translatedFormat('l j F')
                        ." accueille déjà {$count} chien(s) : choisissez au moins {$count}.",
                ]);
            }
        }

        $rows = [];

        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            $rows[] = [
                'date' => $d->toDateString(),
                'capacity' => (int) $data['capacity'],
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Une seule requête : crée les jours manquants, met à jour les existants
        BoardingDay::upsert($rows, ['date'], ['capacity', 'is_open', 'updated_at']);

        return back()->with('success', count($rows).' jour(s) ouvert(s) aux gardes, '.$data['capacity'].' chien(s) maximum par jour.');
    }

    // Fermer une période (impossible si un séjour y est déjà prévu)
    public function close(Request $request, StayCalendar $calendar)
    {
        $data = $request->validate([
            'close_from' => ['required', 'date', 'after_or_equal:today'],
            'close_to' => ['required', 'date', 'after_or_equal:close_from'],
        ], [
            'close_from.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
        ]);

        $from = Carbon::parse($data['close_from'])->startOfDay();
        $to = Carbon::parse($data['close_to'])->startOfDay();

        $booked = $calendar->bookedPerDay($from, $to);

        if ($booked) {
            ksort($booked);

            throw ValidationException::withMessages([
                'close_from' => 'Le '.Carbon::parse(array_key_first($booked))->translatedFormat('l j F')
                    ." a déjà un séjour prévu : traitez-le d'abord dans « Demandes ».",
            ]);
        }

        $count = BoardingDay::whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->where('is_open', true)
            ->update(['is_open' => false]);

        return back()->with('success', $count > 0
            ? $count.' jour(s) fermé(s) aux gardes.'
            : 'Aucun jour ouvert dans cette période : rien à fermer.');
    }
}
