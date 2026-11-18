<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [1, 'Alice Martin', 'alice.martin@example.test', 'U', '2026-11-12 16:21:45'],
            [2, 'Lucas Bernard', 'lucas.bernard@example.test', 'U', '2026-11-12 16:21:45'],
            [3, 'Emma Petit', 'emma.petit@example.test', 'U', '2026-11-12 16:21:45'],
            [4, 'Hugo Robert', 'hugo.robert@example.test', 'U', '2026-11-12 16:21:45'],
            [5, 'Chloé Richard', 'chloe.richard@example.test', 'U', '2026-11-12 16:21:45'],
            [6, 'Nathan Durand', 'nathan.durand@example.test', 'U', '2026-11-12 16:21:45'],
            [7, 'Inès Dubois', 'ines.dubois@example.test', 'U', '2026-11-12 16:21:45'],
            [8, 'Gabriel Moreau', 'gabriel.moreau@example.test', 'U', '2026-11-12 16:21:45'],
            [9, 'Sarah Laurent', 'sarah.laurent@example.test', 'U', '2026-11-12 16:21:45'],
            [10, 'Thomas Simon', 'thomas.simon@example.test', 'U', '2026-11-12 16:21:45'],
            [11, 'Julie Michel', 'julie.michel@example.test', 'U', '2026-11-12 16:21:45'],
            [12, 'Mehdi Lefèvre', 'mehdi.lefevre@example.test', 'U', '2026-11-12 16:21:45'],
            [13, 'Camille Garcia', 'camille.garcia@example.test', 'U', '2026-11-12 16:21:45'],
            [17, 'user_test', 'user_test@example.test', 'U', null],
            [18, 'admin_test', 'admin_test@example.test', 'A', null],
            [19, 'control_test', 'control_test@example.test', 'C', null],
        ];

        $password = Hash::make('CinemaTest2026!');

        foreach ($users as [$id, $name, $email, $role, $verifiedAt]) {
            DB::table('users')->updateOrInsert(
                ['id' => $id],
                [
                    'name' => $name,
                    'email' => $email,
                    'email_verified_at' => $verifiedAt,
                    'password' => $password,
                    'remember_token' => null,
                    'role_code' => $role,
                    'comment' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
