<?php

namespace Database\Seeders;

use App\Models\Passenger;
use Illuminate\Database\Seeder;

class PassengerSeeder extends Seeder
{
    public function run(): void
    {
        Passenger::create([
            'name' => 'Muhamad Daiva',
            'email' => 'daiva@example.com',
            'phone' => '08123456789',
            'id_number' => '3201010101010001'
        ]);

        Passenger::create([
            'name' => 'Tita Ayu',
            'email' => 'tita@example.com',
            'phone' => '08987654321',
            'id_number' => '3201010101010002'
        ]);
    }
}