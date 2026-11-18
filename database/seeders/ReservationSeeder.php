<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $reservations = [
            ['reservation_id' => 1,  'seats_quantity' => 2, 'id' => 1,  'screening_id' => 1],
            ['reservation_id' => 2,  'seats_quantity' => 1, 'id' => 2,  'screening_id' => 1],
            ['reservation_id' => 3,  'seats_quantity' => 3, 'id' => 3,  'screening_id' => 2],
            ['reservation_id' => 4,  'seats_quantity' => 2, 'id' => 4,  'screening_id' => 3],
            ['reservation_id' => 5,  'seats_quantity' => 4, 'id' => 5,  'screening_id' => 4],
            ['reservation_id' => 6,  'seats_quantity' => 1, 'id' => 6,  'screening_id' => 5],
            ['reservation_id' => 7,  'seats_quantity' => 2, 'id' => 7,  'screening_id' => 6],
            ['reservation_id' => 8,  'seats_quantity' => 1, 'id' => 8,  'screening_id' => 7],
            ['reservation_id' => 9,  'seats_quantity' => 3, 'id' => 9,  'screening_id' => 9],
            ['reservation_id' => 10, 'seats_quantity' => 2, 'id' => 10, 'screening_id' => 9],
            ['reservation_id' => 11, 'seats_quantity' => 4, 'id' => 11, 'screening_id' => 10],
            ['reservation_id' => 12, 'seats_quantity' => 2, 'id' => 12, 'screening_id' => 11],
            ['reservation_id' => 13, 'seats_quantity' => 1, 'id' => 1,  'screening_id' => 12],
            ['reservation_id' => 14, 'seats_quantity' => 3, 'id' => 2,  'screening_id' => 13],
            ['reservation_id' => 15, 'seats_quantity' => 2, 'id' => 3,  'screening_id' => 15],
            ['reservation_id' => 16, 'seats_quantity' => 1, 'id' => 4,  'screening_id' => 17],
            ['reservation_id' => 17, 'seats_quantity' => 2, 'id' => 5,  'screening_id' => 18],
        ];

        foreach ($reservations as $reservation) {
            DB::table('reservations')->updateOrInsert(
                ['reservation_id' => $reservation['reservation_id']],
                [
                    'seats_quantity' => $reservation['seats_quantity'],
                    'id' => $reservation['id'],
                    'screening_id' => $reservation['screening_id'],
                ]
            );
        }
    }
}
