<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'superadmin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'accounting', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'finance', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'logistic', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insert($roles);

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

        // Get superadmin role ID
        $superadminRole = DB::table('roles')->where('name', 'superadmin')->first()->id;
        $logisticRole = DB::table('roles')->where('name', 'logistic')->first()->id;
        $accountingRole = DB::table('roles')->where('name', 'accounting')->first()->id;

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

        // Assign permissions to roles
        $rolePermissions = [
            ['role_id' => $superadminRole, 'permission_id' => $aksesAdmin],
            ['role_id' => $superadminRole, 'permission_id' => $aksesPermission],
            ['role_id' => $superadminRole, 'permission_id' => $aksesUser],
            ['role_id' => $superadminRole, 'permission_id' => $aksesAccounting],
            ['role_id' => $superadminRole, 'permission_id' => $aksesFinance],
            ['role_id' => $superadminRole, 'permission_id' => $aksesMaster],
            ['role_id' => $superadminRole, 'permission_id' => $aksesLogistic],
            ['role_id' => $superadminRole, 'permission_id' => $aksesReport],
            ['role_id' => $superadminRole, 'permission_id' => $aksesMigrasi],
            ['role_id' => $logisticRole, 'permission_id' => $aksesLogistic],
            ['role_id' => $logisticRole, 'permission_id' => $aksesReport],
            ['role_id' => $accountingRole, 'permission_id' => $aksesAccounting],
            ['role_id' => $accountingRole, 'permission_id' => $aksesReport],
        ];

        DB::table('role_has_permissions')->insert($rolePermissions);
    }
}
