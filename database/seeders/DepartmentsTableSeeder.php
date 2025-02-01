<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Start of Selection
        $departments = [
            ['department_name' => 'Management / BOD', 'project' => '000H', 'akronim' => 'BOD', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Internal Audit & System', 'project' => '000H', 'akronim' => 'IAS', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Corporate Secretary', 'project' => '001H', 'akronim' => 'CORSEC', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'APS - Arka Project Support', 'project' => 'APS', 'akronim' => 'APS', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Relationship & Coordination', 'project' => '000H', 'akronim' => 'RNC', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Design & Construction', 'project' => '000H', 'akronim' => 'DNC', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Finance', 'project' => '001H', 'akronim' => 'FIN', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Human Capital & Support', 'project' => '000H', 'akronim' => 'HCS', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Logistic', 'project' => '000H', 'akronim' => 'LOG', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Warehouse 017C', 'project' => '017C', 'akronim' => 'WH017', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Warehouse 021C', 'project' => '021C', 'akronim' => 'WH021', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Warehouse 022C', 'project' => '022C', 'akronim' => 'WH022', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Warehouse 023C', 'project' => '023C', 'akronim' => 'WH023', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Accounting', 'project' => '000H', 'akronim' => 'ACC', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Plant', 'project' => '000H', 'akronim' => 'PLANT', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Procurement', 'project' => '000H', 'akronim' => 'PROC', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Operation & Production', 'project' => '000H', 'akronim' => 'OPR', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Safety', 'project' => '000H', 'akronim' => 'SHE', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Information Technology', 'project' => '000H', 'akronim' => 'IT', 'created_at' => now(), 'updated_at' => now()],
            ['department_name' => 'Research & Development', 'project' => '000H', 'akronim' => 'RND', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('departments')->insert($departments);

        DB::table('departments')->where('akronim', 'ACC')->update(['transit_code' => '000H-ACC-TRN']);
        DB::table('departments')->where('akronim', 'LOG')->update(['transit_code' => '000H-LOG-TRN']);
        DB::table('departments')->where('akronim', 'FIN')->update(['transit_code' => '001H-FIN-TRN']);
        DB::table('departments')->where('akronim', 'ACC')->update(['location_code' => '000H-ACC']);
        DB::table('departments')->where('akronim', 'LOG')->update(['location_code' => '000H-LOG']);
        DB::table('departments')->where('akronim', 'FIN')->update(['location_code' => '001H-FIN']);
    }
}
