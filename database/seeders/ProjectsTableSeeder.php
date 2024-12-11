<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            ['code' => '000H', 'location' => 'Balikpapan', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '001H', 'location' => 'Jakarta', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '017C', 'location' => 'Malinau', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '021C', 'location' => 'Bogor', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '022C', 'location' => 'Melak', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '023C', 'location' => 'Melak', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'APS', 'location' => 'Kariangau', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('projects')->insert($projects);
    }
}
