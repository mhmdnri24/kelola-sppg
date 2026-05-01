<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('users')->delete();

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'user_type' => 'admin',
            ]
        );
        $admin->assignRole('admin');

        // Create dapur user
        $dapur = User::firstOrCreate(
            ['email' => 'dapur@example.com'],
            [
                'name' => 'Dapur User',
                'password' => bcrypt('password'),
                'user_type' => 'dapur',
            ]
        );
        $dapur->assignRole('dapur');

        // Create supplier user
        $supplier = User::firstOrCreate(
            ['email' => 'supplier@example.com'],
            [
                'name' => 'Supplier User',
                'password' => bcrypt('password'),
                'user_type' => 'supplier',
            ]
        );
        $supplier->assignRole('supplier');
    }
}
