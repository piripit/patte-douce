<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Availability extends Model
{
    protected $fillable = ['date', 'start_time', 'end_time', 'is_open'];

    protected $casts = [
        'date' => 'date',
        'is_open' => 'boolean',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // La demande « active » du créneau : en attente ou confirmée
    public function activeAppointment(): HasOne
    {
        return $this->hasOne(Appointment::class)
            ->whereIn('status', ['en_attente', 'confirme']);
    }

    // Créneaux que les clients peuvent réserver :
    // ouverts, pas encore commencés, et sans demande active
    public function scopeBookable($query)
    {
        return $query->where('is_open', true)
            ->where(function ($q) {
                $q->whereDate('date', '>', today())
                  ->orWhere(function ($q2) {
                      $q2->whereDate('date', today())
                         ->whereTime('start_time', '>', now()->format('H:i:s'));
                  });
            })
            ->whereDoesntHave('activeAppointment');
    }
}
