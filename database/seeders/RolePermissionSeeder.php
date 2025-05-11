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
            ['name' => 'akses-admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses-permission', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses-user', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses-documents', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses-deliveries', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses-master', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'akses-report', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update-sap-doc', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('permissions')->insert($permissions);

        // Get superadmin role ID
        $superadminRole = DB::table('roles')->where('name', 'superadmin')->first()->id;
        $logisticRole = DB::table('roles')->where('name', 'logistic')->first()->id;
        $accountingRole = DB::table('roles')->where('name', 'accounting')->first()->id;

        // Get permission IDs
        $aksesAdmin = DB::table('permissions')->where('name', 'akses-admin')->first()->id;
        $aksesPermission = DB::table('permissions')->where('name', 'akses-permission')->first()->id;
        $aksesUser = DB::table('permissions')->where('name', 'akses-user')->first()->id;
        $aksesDocuments = DB::table('permissions')->where('name', 'akses-documents')->first()->id;
        $aksesDeliveries = DB::table('permissions')->where('name', 'akses-deliveries')->first()->id;
        $aksesMaster = DB::table('permissions')->where('name', 'akses-master')->first()->id;
        $aksesReport = DB::table('permissions')->where('name', 'akses-report')->first()->id;
        $updateSapDoc = DB::table('permissions')->where('name', 'update-sap-doc')->first()->id;
        // Assign permissions to roles
        $rolePermissions = [
            // Superadmin gets all permissions
            ['role_id' => $superadminRole, 'permission_id' => $aksesAdmin],
            ['role_id' => $superadminRole, 'permission_id' => $aksesPermission],
            ['role_id' => $superadminRole, 'permission_id' => $aksesUser],
            ['role_id' => $superadminRole, 'permission_id' => $aksesDocuments],
            ['role_id' => $superadminRole, 'permission_id' => $aksesDeliveries],
            ['role_id' => $superadminRole, 'permission_id' => $aksesMaster],
            ['role_id' => $superadminRole, 'permission_id' => $aksesReport],
            ['role_id' => $superadminRole, 'permission_id' => $updateSapDoc],
            // Logistic role permissions
            ['role_id' => $logisticRole, 'permission_id' => $aksesDeliveries],
            ['role_id' => $logisticRole, 'permission_id' => $aksesMaster],
            ['role_id' => $logisticRole, 'permission_id' => $aksesReport],

            // Accounting role permissions
            ['role_id' => $accountingRole, 'permission_id' => $aksesDocuments],
            ['role_id' => $accountingRole, 'permission_id' => $aksesReport],
            ['role_id' => $accountingRole, 'permission_id' => $updateSapDoc],
        ];

        DB::table('role_has_permissions')->insert($rolePermissions);
    }
}
