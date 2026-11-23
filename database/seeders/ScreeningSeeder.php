<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScreeningSeeder extends Seeder
{
    public function run(): void
    {
        $screenings = [
            ['screening_id' => 1, 'screening_date' => '2026-11-05 14:00:00', 'room_id' => 1, 'movie_id' => 1],
            ['screening_id' => 2, 'screening_date' => '2026-11-08 18:30:00', 'room_id' => 4, 'movie_id' => 2],
            ['screening_id' => 3, 'screening_date' => '2026-11-12 20:00:00', 'room_id' => 2, 'movie_id' => 3],
            ['screening_id' => 4, 'screening_date' => '2026-11-16 16:00:00', 'room_id' => 5, 'movie_id' => 4],
            ['screening_id' => 5, 'screening_date' => '2026-11-21 14:30:00', 'room_id' => 8, 'movie_id' => 5],
            ['screening_id' => 6, 'screening_date' => '2026-11-28 20:30:00', 'room_id' => 6, 'movie_id' => 6],
            ['screening_id' => 7, 'screening_date' => '2026-12-03 17:00:00', 'room_id' => 9, 'movie_id' => 7],
            ['screening_id' => 8, 'screening_date' => '2026-12-06 19:00:00', 'room_id' => 7, 'movie_id' => 8],
            ['screening_id' => 9, 'screening_date' => '2026-12-10 20:15:00', 'room_id' => 1, 'movie_id' => 9],
            ['screening_id' => 10, 'screening_date' => '2026-12-14 11:00:00', 'room_id' => 10, 'movie_id' => 10],
            ['screening_id' => 11, 'screening_date' => '2026-12-18 18:00:00', 'room_id' => 4, 'movie_id' => 11],
            ['screening_id' => 12, 'screening_date' => '2026-12-22 21:00:00', 'room_id' => 2, 'movie_id' => 1],
            ['screening_id' => 13, 'screening_date' => '2026-12-27 15:30:00', 'room_id' => 5, 'movie_id' => 4],
            ['screening_id' => 14, 'screening_date' => '2027-01-04 14:00:00', 'room_id' => 8, 'movie_id' => 2],
            ['screening_id' => 15, 'screening_date' => '2027-01-09 20:00:00', 'room_id' => 7, 'movie_id' => 3],
            ['screening_id' => 16, 'screening_date' => '2027-01-15 17:30:00', 'room_id' => 10, 'movie_id' => 5],
            ['screening_id' => 17, 'screening_date' => '2026-11-29 16:00:00', 'room_id' => 9, 'movie_id' => 9],
            ['screening_id' => 18, 'screening_date' => '2027-01-09 20:00:00', 'room_id' => 9, 'movie_id' => 7],
            ['screening_id' => 20, 'screening_date' => '2026-11-14 19:00:00', 'room_id' => 1, 'movie_id' => 1],
        ];

        foreach ($screenings as $screening) {
            DB::table('screenings')->updateOrInsert(
                ['screening_id' => $screening['screening_id']],
                [
                    'screening_date' => $screening['screening_date'],
                    'room_id' => $screening['room_id'],
                    'movie_id' => $screening['movie_id'],
                ]
            );
        }
    }
}
