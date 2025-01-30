<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Omanof Sullivans',
            'email' => 'oman@gmail.com',
            'username' => 'superadmin',
            'password' => Hash::make('123456'),
            'project' => '000H',
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Prana Dian',
            'email' => 'prana@gmail.com',
            'username' => 'prana',
            'password' => Hash::make('123456'),
            'project' => '000H',
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Logistic',
            'email' => 'logistic@gmail.com',
            'username' => 'adminlog',
            'password' => Hash::make('123456'),
            'project' => '000H',
            'is_active' => true,
        ]);

        $this->call([
            DepartmentsTableSeeder::class,
            ProjectsTableSeeder::class,
            InvoiceTypeSeeder::class,
            AddocTypeSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // attach role to user
        $superuser = User::where('username', 'superadmin')->first();
        $superuser->assignRole('superadmin');

        $user = User::where('username', 'prana')->first();
        $user->assignRole('admin');

        $user = User::where('username', 'adminlog')->first();
        $user->assignRole('logistic');
    }
}
