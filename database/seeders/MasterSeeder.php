<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed departments
        $this->call([
            DepartmentsTableSeeder::class,
            ProjectsTableSeeder::class,
            InvoiceTypeSeeder::class
        ]);
    }
}
