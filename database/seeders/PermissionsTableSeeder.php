<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder; // Add this line to import the Seeder class

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'akses_admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses_permission', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses_user', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses_accounting', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses_finance', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses_logistic', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses_report', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses_migrasi', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses_master', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
