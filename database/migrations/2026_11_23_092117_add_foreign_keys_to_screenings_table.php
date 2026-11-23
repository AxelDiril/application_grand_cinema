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
        Schema::table('screenings', function (Blueprint $table) {
            $table->foreign(['movie_id'], 'fk_screenings_movies')->references(['movie_id'])->on('movies')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['room_id'], 'fk_screenings_rooms')->references(['room_id'])->on('rooms')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screenings', function (Blueprint $table) {
            $table->dropForeign('fk_screenings_movies');
            $table->dropForeign('fk_screenings_rooms');
        });
    }
};
