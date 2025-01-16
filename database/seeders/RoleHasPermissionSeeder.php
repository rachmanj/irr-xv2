<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Get superadmin role ID
        $superadminRole = DB::table('roles')->where('name', 'superadmin')->first()->id;

        // Get permission IDs
        $aksesAdmin = DB::table('permissions')->where('name', 'akses_admin')->first()->id;
        $aksesPermission = DB::table('permissions')->where('name', 'akses_permission')->first()->id;
        $aksesUser = DB::table('permissions')->where('name', 'akses_user')->first()->id;
        $aksesAccounting = DB::table('permissions')->where('name', 'akses_accounting')->first()->id;
        $aksesFinance = DB::table('permissions')->where('name', 'akses_finance')->first()->id;
        $aksesLogistic = DB::table('permissions')->where('name', 'akses_logistic')->first()->id;
        $aksesReport = DB::table('permissions')->where('name', 'akses_report')->first()->id;
        $aksesMigrasi = DB::table('permissions')->where('name', 'akses_migrasi')->first()->id;
        $aksesMaster = DB::table('permissions')->where('name', 'akses_master')->first()->id;

        $rolePermissions = [
            ['role_id' => $superadminRole, 'permission_id' => $aksesAdmin],
            ['role_id' => $superadminRole, 'permission_id' => $aksesPermission],
            ['role_id' => $superadminRole, 'permission_id' => $aksesUser],
            ['role_id' => $superadminRole, 'permission_id' => $aksesAccounting],
            ['role_id' => $superadminRole, 'permission_id' => $aksesFinance],
            ['role_id' => $superadminRole, 'permission_id' => $aksesLogistic],
            ['role_id' => $superadminRole, 'permission_id' => $aksesReport],
            ['role_id' => $superadminRole, 'permission_id' => $aksesMigrasi],
            ['role_id' => $superadminRole, 'permission_id' => $aksesMaster],
        ];

        DB::table('role_has_permissions')->insert($rolePermissions);
    }
}
