<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->dateTime('departure_time');  // Waktu berangkat
            $table->dateTime('arrival_time');    // Waktu tiba
            $table->integer('available_seats');  // Kursi tersedia
            $table->enum('status', ['active', 'cancelled', 'full'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
