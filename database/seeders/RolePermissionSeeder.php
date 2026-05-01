<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('roles')->delete();
        DB::table('permissions')->delete();
        DB::table('users')->delete();
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create permissions
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'update users',
            'delete users',
            'manage users',
            
            // Role permissions
            'view roles',
            'create roles',
            'update roles',
            'delete roles',
            'manage roles',
            
            // Permission permissions
            'view permissions',
            'manage permissions',
            
            // Dashboard
            'view dashboard',
            'view reports',
            
            // General
            'view settings',
            'update settings',

            // katalog
            'view katalog',
            'manage katalog',

            // keranjang
            'view keranjang',
            'manage keranjang',

            // daftar pesanan
            'view daftar pesanan',
            'manage daftar pesanan',

            'pilih item'

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $editorRole = Role::firstOrCreate(['name' => 'dapur']);
        $editorRole->syncPermissions([
            'view users',
            'view dashboard',
            'view reports',
            'view katalog',
            'manage katalog',
            'view keranjang',
            'manage keranjang',
            'view daftar pesanan',
            'manage daftar pesanan',
        ]);

        $viewerRole = Role::firstOrCreate(['name' => 'supplier']);
        $viewerRole->syncPermissions([
            'view dashboard',
            'view katalog',
        ]);
    }
}
