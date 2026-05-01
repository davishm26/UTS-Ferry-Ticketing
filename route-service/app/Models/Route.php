<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'origin',
        'destination',
        'ship_name',
        'capacity',
        'price',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
