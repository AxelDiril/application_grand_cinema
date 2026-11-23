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
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreign(['screening_id'], 'fk_reservations_screenings')->references(['screening_id'])->on('screenings')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['seat_id'], 'fk_reservations_seats')->references(['seat_id'])->on('seats')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['id'], 'fk_reservations_users')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign('fk_reservations_screenings');
            $table->dropForeign('fk_reservations_seats');
            $table->dropForeign('fk_reservations_users');
        });
    }
};
