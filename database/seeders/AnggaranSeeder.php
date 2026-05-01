<?php

namespace Database\Seeders;

use App\Models\Anggaran;
use App\Models\Dapur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('anggarans')->delete();
        //

        $expire_date = explode(' ',explode('T',now()->addDays(7)->toDateTimeString())[0])[0];

        $active_date = explode(' ',now())[0];

        $dapur = Dapur::firstOrCreate([
            'name' => 'supplier',
            'alamat' => 'Jl. Supplier No. 1, Jakarta',
            'no_telp' => '081234567890',
            'email' => 'supplier@example.com',
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
    }
}
