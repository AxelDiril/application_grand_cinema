<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $reservations = [
            ['reservation_id' => 1,  'id' => 1,  'screening_id' => 1,  'seat_label' => 40],
            ['reservation_id' => 2,  'id' => 2,  'screening_id' => 1,  'seat_label' => 38],
            ['reservation_id' => 3,  'id' => 3,  'screening_id' => 2,  'seat_label' => 18],
            ['reservation_id' => 4,  'id' => 4,  'screening_id' => 3,  'seat_label' => 57],
            ['reservation_id' => 5,  'id' => 5,  'screening_id' => 4,  'seat_label' => 39],
            ['reservation_id' => 6,  'id' => 6,  'screening_id' => 5,  'seat_label' => 84],
            ['reservation_id' => 7,  'id' => 7,  'screening_id' => 6,  'seat_label' => 59],
            ['reservation_id' => 8,  'id' => 8,  'screening_id' => 7,  'seat_label' => 8],
            ['reservation_id' => 9,  'id' => 9,  'screening_id' => 9,  'seat_label' => 34],
            ['reservation_id' => 10, 'id' => 10, 'screening_id' => 9,  'seat_label' => 53],
            ['reservation_id' => 11, 'id' => 11, 'screening_id' => 10, 'seat_label' => 26],
            ['reservation_id' => 12, 'id' => 12, 'screening_id' => 11, 'seat_label' => 1],
            ['reservation_id' => 13, 'id' => 1,  'screening_id' => 12, 'seat_label' => 62],
            ['reservation_id' => 14, 'id' => 2,  'screening_id' => 13, 'seat_label' => 35],
            ['reservation_id' => 15, 'id' => 3,  'screening_id' => 15, 'seat_label' => 74],
            ['reservation_id' => 16, 'id' => 4,  'screening_id' => 17, 'seat_label' => 8],
            ['reservation_id' => 17, 'id' => 5,  'screening_id' => 18, 'seat_label' => 11],
        ];

        foreach ($reservations as $reservation) {
            $screening = DB::table('screenings')
                ->where('screening_id', $reservation['screening_id'])
                ->first();

            if (!$screening) {
                throw new \RuntimeException(
                    "Séance {$reservation['screening_id']} introuvable."
                );
            }

            $seat = DB::table('seats')
                ->where('room_id', $screening->room_id)
                ->where('seat_label', (string) $reservation['seat_label'])
                ->first();

            if (!$seat) {
                throw new \RuntimeException(
                    "Place {$reservation['seat_label']} introuvable dans la salle {$screening->room_id}."
                );
            }

            DB::table('reservations')->updateOrInsert(
                ['reservation_id' => $reservation['reservation_id']],
                [
                    'id' => $reservation['id'],
                    'screening_id' => $reservation['screening_id'],
                    'seat_id' => $seat->seat_id,
                ]
            );
        }
    }
}
