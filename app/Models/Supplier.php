<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'supplier_type',
        'partner_supplier_id',        
        'logo',
        'contact_person'
    ];
 

    public function katalogs(): BelongsToMany
    {
        return $this->belongsToMany(Katalog::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }
 
}
