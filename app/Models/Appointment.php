<?php

namespace App\Models;

use App\Services\StayCalendar;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'type', 'availability_id', 'start_date', 'end_date',
        'client_name', 'client_email', 'client_phone',
        'dog_name', 'service', 'message', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function availability(): BelongsTo
    {
        return $this->belongsTo(Availability::class);
    }

    // Séjour de un ou plusieurs jours (par opposition à un créneau horaire)
    public function isStay(): bool
    {
        return $this->type === 'stay';
    }

    public function startsOn(): CarbonInterface
    {
        return $this->isStay() ? $this->start_date : $this->availability->date;
    }

    public function endsOn(): CarbonInterface
    {
        return $this->isStay() ? $this->end_date : $this->availability->date;
    }

    // Nombre de jours du séjour, arrivée et départ compris
    public function days(): int
    {
        return $this->isStay()
            ? StayCalendar::daysBetween($this->start_date, $this->end_date)
            : 1;
    }

    // Texte lisible : « Du samedi 14 novembre au samedi 21 novembre 2026 (8 jours) »
    public function whenLabel(): string
    {
        if ($this->isStay()) {
            if ($this->start_date->isSameDay($this->end_date)) {
                return ucfirst($this->start_date->translatedFormat('l j F Y')).' (1 jour)';
            }

            return 'Du '.$this->start_date->translatedFormat('l j F')
                .' au '.$this->end_date->translatedFormat('l j F Y')
                .' ('.$this->days().' jours)';
        }

        $slot = $this->availability;

        return ucfirst($slot->date->translatedFormat('l j F Y'))
            .', de '.substr($slot->start_time, 0, 5)
            .' à '.substr($slot->end_time, 0, 5);
    }
}
