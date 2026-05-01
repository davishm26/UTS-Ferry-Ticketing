<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'booking_code',
        'passenger_id',
        'schedule_id',
        'route_id',
        'passenger_name',
        'origin',
        'destination',
        'departure_time',
        'total_price',
        'status',
    ];
}
