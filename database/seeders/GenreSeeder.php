<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            ['genre_id' => 1, 'genre_label' => 'Action'],
            ['genre_id' => 2, 'genre_label' => 'Comédie'],
            ['genre_id' => 3, 'genre_label' => 'Drame'],
            ['genre_id' => 4, 'genre_label' => 'Science-fiction'],
            ['genre_id' => 5, 'genre_label' => 'Thriller'],
            ['genre_id' => 6, 'genre_label' => 'Animation'],
            ['genre_id' => 7, 'genre_label' => 'Aventure'],
            ['genre_id' => 8, 'genre_label' => 'Romance'],
            ['genre_id' => 9, 'genre_label' => 'Fantastique'],
            ['genre_id' => 10, 'genre_label' => 'Documentaire'],
        ];

        foreach ($genres as $genre) {
            DB::table('genres')->updateOrInsert(
                ['genre_id' => $genre['genre_id']],
                ['genre_label' => $genre['genre_label']]
            );
        }
    }
}
