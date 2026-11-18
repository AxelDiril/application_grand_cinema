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
        Schema::table('movies_genres', function (Blueprint $table) {
        $table->foreign('genre_id', 'fk_mg_genres')
            ->references('genre_id')
            ->on('genres')
            ->onUpdate('restrict')
            ->onDelete('cascade');

        $table->foreign('movie_id', 'fk_mg_movies')
            ->references('movie_id')
            ->on('movies')
            ->onUpdate('restrict')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies_genres', function (Blueprint $table) {
            $table->dropForeign('fk_mg_genres_cascade');
            $table->dropForeign('fk_mg_genres_movies');
        });
    }
};
