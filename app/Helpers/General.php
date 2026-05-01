<?php
namespace App\Helpers;

use App\Models\Anggaran;

class General{

     public function getActiveAnggaranByDapur()
    {
        $dapur_id = auth()->user()->dapur_id;
        $anggaran = Anggaran::where('dapur_id', $dapur_id)
            ->active()
            ->whereDate('active_date', '<=', now())
            ->whereDate('expire_date', '>=', now())
            ->first();

        return ($anggaran);
    }

}