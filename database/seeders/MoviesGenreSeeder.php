<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoviesGenreSeeder extends Seeder
{
    public function run(): void
    {
        $associations = [
            [1, 3],
            [1, 4],
            [1, 7],
            [2, 2],
            [2, 8],
            [3, 3],
            [3, 5],
            [4, 1],
            [4, 5],
            [5, 6],
            [5, 7],
            [5, 9],
            [6, 3],
            [7, 10],
            [8, 2],
            [9, 4],
            [9, 5],
            [10, 6],
            [10, 7],
            [11, 3],
            [11, 8],
            [13, 5],
            [13, 9],
        ];

        foreach ($associations as [$movieId, $genreId]) {
            DB::table('movies_genres')->updateOrInsert(
                [
                    'movie_id' => $movieId,
                    'genre_id' => $genreId,
                ],
                []
            );
        }
    }
}
