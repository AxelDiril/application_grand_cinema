<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = DB::table('rooms')->get();

        foreach ($rooms as $room) {
            for ($i = 1; $i <= 100; $i++) {
                DB::table('seats')->updateOrInsert(
                    [
                        'room_id' => $room->room_id,
                        'seat_label' => (string) $i,
                    ],
                    []
                );
            }
        }
    }
}
