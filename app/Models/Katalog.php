<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'supplier_id',
        'harga',
        'stok',
        'is_terbit'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
