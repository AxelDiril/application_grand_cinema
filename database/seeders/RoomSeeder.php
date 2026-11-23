<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['room_id' => 1, 'room_name' => 'Salle Premium A'],
            ['room_id' => 2, 'room_name' => 'Salle Premium B'],
            ['room_id' => 4, 'room_name' => 'Salle Standard A'],
            ['room_id' => 5, 'room_name' => 'Salle Standard B'],
            ['room_id' => 6, 'room_name' => 'Salle Standard C'],
            ['room_id' => 7, 'room_name' => 'Salle Standard D'],
            ['room_id' => 8, 'room_name' => 'Salle VIP A'],
            ['room_id' => 9, 'room_name' => 'Salle VIP B'],
            ['room_id' => 10, 'room_name' => 'Salle Modulable A'],
            ['room_id' => 11, 'room_name' => 'Salle Modulable B'],
        ];

        foreach ($rooms as $room) {
            DB::table('rooms')->updateOrInsert(
                ['room_id' => $room['room_id']],
                [
                    'room_name' => $room['room_name'],
                ]
            );
        }
    }
}
