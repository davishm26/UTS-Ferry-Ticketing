<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // Kode booking unik
            $table->unsignedBigInteger('passenger_id'); // ID dari PassengerService
            $table->unsignedBigInteger('schedule_id');  // ID dari RouteService
            $table->unsignedBigInteger('route_id');     // ID dari RouteService
            $table->string('passenger_name');           // Disimpan lokal (snapshot)
            $table->string('origin');                   // Disimpan lokal (snapshot)
            $table->string('destination');              // Disimpan lokal (snapshot)
            $table->dateTime('departure_time');         // Disimpan lokal (snapshot)
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['booked', 'cancelled', 'completed'])->default('booked');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
