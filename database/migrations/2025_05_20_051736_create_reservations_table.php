<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bungalow_id');
            $table->string('last_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('person_count');
            $table->string('numero')->unique(); // numéro de réservation
            $table->timestamps();

            $table->foreign('bungalow_id')->references('id')->on('bungalows')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
