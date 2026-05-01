<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anggaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'dapur_id',        
        'location',
        'kategori',
        'nama_anggaran',
        'pm_pb',
        'pm_pk',
        'pagu_pb',
        'pagu_pk',
        'hpp_pb',
        'hpp_pk',
        'active_date',
        'expire_date',  
        'is_active',      
    ];

    protected function casts(): array
    {
        return [
            'pm_pb' => 'integer',
            'pm_pk' => 'integer',
            'pagu_pb' => 'decimal:2',
            'pagu_pk' => 'decimal:2',
            'hpp_pb' => 'decimal:2',
            'hpp_pk' => 'decimal:2',
            'active_date' => 'date',
            'expire_date' => 'date',            
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function dapur(): BelongsTo
    {
        return $this->belongsTo(Dapur::class);
    }

    public function calculateTotalPagu(): array
    {
        $pm = $this->pagu_pb + $this->pagu_pk;
        $limit = $this->pagu_pb + $this->pagu_pk;
        return [
            'pm' => $pm,
            'limit' => $limit
        ];
    }
}
