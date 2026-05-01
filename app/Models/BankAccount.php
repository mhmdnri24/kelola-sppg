<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'bank_logo',
        'bank_name',
        'account_number',
        'account_name',
        'supplier_id'
    ];

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class);
    }
}
