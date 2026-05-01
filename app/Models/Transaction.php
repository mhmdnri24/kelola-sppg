<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_transaksi',
        'dapur_id',
        'supplier_id',
        'anggaran_id',
        'tanggal_transaksi',
        'subtotal',
        'status',
        'created_by',
    ];

    public function dapur()
    {
        return $this->belongsTo(Dapur::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function anggaran(){
        return $this->belongsTo(Anggaran::class);
    }
}
