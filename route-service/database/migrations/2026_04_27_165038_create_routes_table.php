<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('origin');        // Pelabuhan asal
            $table->string('destination');   // Pelabuhan tujuan
            $table->string('ship_name');     // Nama kapal
            $table->integer('capacity');     // Kapasitas penumpang
            $table->decimal('price', 10, 2); // Harga tiket
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
