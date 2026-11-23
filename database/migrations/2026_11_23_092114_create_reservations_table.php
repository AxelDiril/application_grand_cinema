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
            $table->integer('reservation_id', true);
            $table->unsignedBigInteger('id')->index('fk_reservations_users');
            $table->integer('screening_id');
            $table->integer('seat_id')->nullable()->index('fk_reservations_seats');

            $table->unique(['screening_id', 'seat_id'], 'unique_screening_seat');
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
