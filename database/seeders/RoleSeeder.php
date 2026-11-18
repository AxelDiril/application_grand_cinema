<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->updateOrInsert(
            ['role_code' => 'A'],
            ['role_label' => 'Administateur']
        );

        DB::table('roles')->updateOrInsert(
            ['role_code' => 'C'],
            ['role_label' => 'Agent de contrôle']
        );

        DB::table('roles')->updateOrInsert(
            ['role_code' => 'U'],
            ['role_label' => 'Utilisateur']
        );
    }
}