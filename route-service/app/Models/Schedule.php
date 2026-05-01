<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'route_id',
        'departure_time',
        'arrival_time',
        'available_seats',
        'status',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
