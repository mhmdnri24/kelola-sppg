<?php

namespace Database\Seeders;

use App\Models\Anggaran;
use App\Models\BankAccount;
use App\Models\Dapur;
use App\Models\Supplier;
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
        DB::table('katalogs')->delete();
        DB::table('bank_accounts')->delete();
        DB::table('suppliers')->delete();

        $supplier = [
            'name' => 'PT. Jaya Abadi',
            'contact_person' => 'Ani Pratiwi',
            'phone' => '081234567891',
            'email' => 'ani@jayaabadi.com',
            'address' => 'Jl. Sudirman No. 456, Bandung',
            'supplier_type' => 'individual',
            'partner_supplier_id' => null,
            'logo' => null,
        ];

        $supplier = Supplier::create($supplier);

        $payload = [
            'bank_name' => 'Bank Central Asia (BCA)',
            'account_number' => '1234567890',
            'account_name' => 'PT. Sumber Makmur',
            'supplier_id' => $supplier->id,
        ];

        BankAccount::create($payload);

        DB::table('anggarans')->delete();
        //

        $expire_date = explode(' ', explode('T', now()->addDays(7)->toDateTimeString())[0])[0];

        $active_date = explode(' ', now())[0];

        $dapur = Dapur::firstOrCreate([
            'name' => 'Dapur test',
            'alamat' => 'Jl. Dapur No. 1, Jakarta',
            'no_telp' => '081234567890',
            'email' => 'dapur@example.com',
        ]);

        Anggaran::create([
            'dapur_id' => $dapur->id,
            'location' => 'Jakarta',
            'kategori' => 'UMUM',
            'nama_anggaran' => 'Anggaran Makanan Bulan Januari',
            'pm_pb' => 10,
            'pm_pk' => 5,
            'pagu_pb' => 1000000.00,
            'pagu_pk' => 500000.00,
            'hpp_pb' => 900000.00,
            'hpp_pk' => 450000.00,
            'active_date' => $active_date,
            'expire_date' => $expire_date,
            'status' => 'active',
        ]);


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
                'dapur_id' => $dapur->id,
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
                'supplier_id' => $supplier->id,
            ]
        );
        $supplier->assignRole('supplier');
    }
}
