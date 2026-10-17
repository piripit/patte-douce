<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\BoardingDay;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/**
 * Toute la logique des gardes de plusieurs jours :
 * quels jours sont ouverts, combien de chiens y sont déjà prévus, et si un séjour est possible.
 *
 * Règle : un séjour occupe chaque jour de l'arrivée au départ INCLUS.
 */
class StayCalendar
{
    // Nombre de jours entre deux dates, bornes comprises (sans effet de l'heure d'été)
    public static function daysBetween(CarbonInterface $from, CarbonInterface $to): int
    {
        $a = Carbon::parse($from->toDateString(), 'UTC');
        $b = Carbon::parse($to->toDateString(), 'UTC');

        return (int) round($a->diffInDays($b)) + 1;
    }

    /**
     * Nombre de chiens prévus par jour (demandes en attente + confirmées).
     *
     * @return array<string,int>  ex. ['2026-11-14' => 1, '2026-11-15' => 2]
     */
    public function bookedPerDay(CarbonInterface $from, CarbonInterface $to): array
    {
        $fromKey = $from->toDateString();
        $toKey = $to->toDateString();
        $counts = [];

        $stays = Appointment::where('type', 'stay')
            ->whereIn('status', ['en_attente', 'confirme'])
            ->whereDate('start_date', '<=', $toKey)
            ->whereDate('end_date', '>=', $fromKey)
            ->get();

        foreach ($stays as $stay) {
            $d = $stay->start_date->copy();

            while ($d->lte($stay->end_date)) {
                $key = $d->toDateString();

                if ($key >= $fromKey && $key <= $toKey) {
                    $counts[$key] = ($counts[$key] ?? 0) + 1;
                }

                $d->addDay();
            }
        }

        return $counts;
    }

    // Premier obstacle trouvé pour un séjour, ou null si tout est possible
    public function firstProblem(CarbonInterface $start, CarbonInterface $end): ?string
    {
        $days = BoardingDay::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(fn ($day) => $day->date->toDateString());

        $booked = $this->bookedPerDay($start, $end);

        $d = $start->copy();

        while ($d->lte($end)) {
            $key = $d->toDateString();
            $day = $days->get($key);

            if (! $day || ! $day->is_open) {
                return 'Le '.$d->translatedFormat('l j F')." n'est pas ouvert à la réservation.";
            }

            if (($booked[$key] ?? 0) >= $day->capacity) {
                return 'Le '.$d->translatedFormat('l j F').' est complet.';
            }

            $d->addDay();
        }

        return null;
    }

    /**
     * Périodes continues de jours ouverts et encore libres, à partir de demain.
     *
     * @return array<int,array{from:CarbonInterface,to:CarbonInterface,label:string}>
     */
    public function openRanges(int $months = 6): array
    {
        $from = today()->addDay();
        $to = today()->addMonths($months);

        $days = BoardingDay::where('is_open', true)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('date')
            ->get();

        $booked = $this->bookedPerDay($from, $to);

        $ranges = [];
        $last = null;

        foreach ($days as $day) {
            $key = $day->date->toDateString();

            // Jour complet : la période en cours s'arrête là
            if (($booked[$key] ?? 0) >= $day->capacity) {
                $last = null;
                continue;
            }

            $date = $day->date->copy();

            if ($last !== null && $last->copy()->addDay()->toDateString() === $key) {
                $ranges[count($ranges) - 1]['to'] = $date;
            } else {
                $ranges[] = ['from' => $date, 'to' => $date];
            }

            $last = $date;
        }

        foreach ($ranges as $i => $range) {
            $ranges[$i]['label'] = $this->rangeLabel($range['from'], $range['to']);
        }

        return $ranges;
    }

    // « du lundi 3 au vendredi 20 novembre »
    public function rangeLabel(CarbonInterface $from, CarbonInterface $to): string
    {
        $year = $to->year !== now()->year ? ' '.$to->year : '';

        if ($from->isSameDay($to)) {
            return $from->translatedFormat('l j F').$year;
        }

        if ($from->month === $to->month && $from->year === $to->year) {
            return 'du '.$from->translatedFormat('l j').' au '.$to->translatedFormat('l j F').$year;
        }

        return 'du '.$from->translatedFormat('l j F').' au '.$to->translatedFormat('l j F').$year;
    }
}
