<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggaranHistory extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'date',
        'trans_type',
        'status',
        'dapur_id',
        'pagu',
        'limit',
        'module',
        'trans_id',
        'notes',
        'jumlah'
    ];

    public function anggaran()
    {
        return $this->belongsTo(Anggaran::class);
    }

    public function dapur()
    {
        return $this->belongsTo(Dapur::class);
    }
}
