<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dapur extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'no_telp',
        'email',
        'alamat',
        'active',
    ];

    public function anggarans(): HasMany
    {
        return $this->hasMany(Anggaran::class);
    }
}