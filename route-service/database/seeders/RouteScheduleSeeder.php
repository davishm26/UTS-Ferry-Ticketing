<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class RouteScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Rute 1
        $route1 = Route::create([
            'origin' => 'Pelabuhan Merak',
            'destination' => 'Pelabuhan Bakauheni',
            'ship_name' => 'KM Nusantara Jaya',
            'capacity' => 200,
            'price' => 75000
        ]);

        // Tambah Jadwal untuk Rute 1
        Schedule::create([
            'route_id' => $route1->id,
            'departure_time' => now()->addDays(1)->setTime(8, 0, 0),
            'arrival_time' => now()->addDays(1)->setTime(10, 0, 0),
            'available_seats' => 200,
            'status' => 'active'
        ]);

        // Buat Rute 2
        $route2 = Route::create([
            'origin' => 'Pelabuhan Ketapang',
            'destination' => 'Pelabuhan Gilimanuk',
            'ship_name' => 'KM Dharma Ferry',
            'capacity' => 150,
            'price' => 50000
        ]);

        // Tambah Jadwal untuk Rute 2
        Schedule::create([
            'route_id' => $route2->id,
            'departure_time' => now()->addDays(1)->setTime(14, 0, 0),
            'arrival_time' => now()->addDays(1)->setTime(15, 30, 0),
            'available_seats' => 150,
            'status' => 'active'
        ]);
    }
}