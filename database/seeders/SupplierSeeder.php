<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    DB::table('katalogs')->delete();    
    DB::table('bank_accounts')->delete();    
    DB::table('suppliers')->delete();
        
        $suppliers = [
            [
                'name' => 'PT. Sumber Makmur',
                'contact_person' => 'Budi Santoso',
                'phone' => '081234567890',
                'email' => 'budi@sumbermakmur.com',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'supplier_type' => 'business',
                'partner_supplier_id' => null,
                
                'logo' => null,
            ],
            [
                'name' => 'PT. Jaya Abadi',
                'contact_person' => 'Ani Pratiwi',
                'phone' => '081234567891',
                'email' => 'ani@jayaabadi.com',
                'address' => 'Jl. Sudirman No. 456, Bandung',
                'supplier_type' => 'individual',
                'partner_supplier_id' => null,
                
                'logo' => null,
            ]
        ];

         $bankAccounts = [
            [
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_number' => '1234567890',
                'account_name' => 'PT. Sumber Makmur',
                'supplier_id' => 1,
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '0987654321',
                'account_name' => 'PT. Jaya Abadi',
                'supplier_id' => 2,
            ]
        ];

        foreach ($suppliers as $key => $value) {
            $supplier = Supplier::create($value);
            
            $payload = $bankAccounts[$key];
            $payload['supplier_id'] = $supplier->id;

            BankAccount::create($payload);
        }


         
       
       
    }
}
