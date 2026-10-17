<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingDay extends Model
{
    protected $fillable = ['date', 'capacity', 'is_open'];

    protected $casts = [
        'date' => 'date',
        'is_open' => 'boolean',
        'capacity' => 'integer',
    ];
}
