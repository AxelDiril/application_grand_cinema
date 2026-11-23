<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $reservations = [
            ['reservation_id' => 1, 'id' => 1,  'screening_id' => 1, 'seat_id' => 140],
            ['reservation_id' => 2, 'id' => 2,  'screening_id' => 1, 'seat_id' => 138],
            ['reservation_id' => 3, 'id' => 3,  'screening_id' => 2, 'seat_id' => 18],
            ['reservation_id' => 4, 'id' => 4,  'screening_id' => 3, 'seat_id' => 157],
            ['reservation_id' => 5, 'id' => 5,  'screening_id' => 4, 'seat_id' => 39],
            ['reservation_id' => 6, 'id' => 6,  'screening_id' => 5, 'seat_id' => 84],
            ['reservation_id' => 7, 'id' => 7,  'screening_id' => 6, 'seat_id' => 59],
            ['reservation_id' => 8, 'id' => 8,  'screening_id' => 7, 'seat_id' => 108],
            ['reservation_id' => 9, 'id' => 9,  'screening_id' => 9, 'seat_id' => 134],
            ['reservation_id' => 10,'id' => 10, 'screening_id' => 9, 'seat_id' => 153],
            ['reservation_id' => 11,'id' => 11, 'screening_id' => 10, 'seat_id' => 126],
            ['reservation_id' => 12,'id' => 12, 'screening_id' => 11, 'seat_id' => 1],
            ['reservation_id' => 13,'id' => 1,  'screening_id' => 12, 'seat_id' => 162],
            ['reservation_id' => 14,'id' => 2,  'screening_id' => 13, 'seat_id' => 35],
            ['reservation_id' => 15,'id' => 3,  'screening_id' => 15, 'seat_id' => 74],
            ['reservation_id' => 16,'id' => 4,  'screening_id' => 17, 'seat_id' => 108],
            ['reservation_id' => 17,'id' => 5,  'screening_id' => 18, 'seat_id' => 11],
        ];

        foreach ($reservations as $reservation) {
            DB::table('reservations')->updateOrInsert(
                ['reservation_id' => $reservation['reservation_id']],
                [
                    'id' => $reservation['id'],
                    'screening_id' => $reservation['screening_id'],
                    'seat_id' => $reservation['seat_id']
                ]
            );
        }
    }
}
