<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePrice extends Model
{
    protected $fillable = [
        'company_id',
        'catalog_code',
        'catalog_name',
        'reference',
        'description_base',
        'dx',
        'dy',
        'dz',
        'model',
        'complexity_level',
        'complexity_factor',
        'labor_cost',
        'indirect_cost',
        'default_waste_percent',
        'is_active',
    ];

    protected $casts = [
        'dx' => 'decimal:2',
        'dy' => 'decimal:2',
        'dz' => 'decimal:2',
        'complexity_factor' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'indirect_cost' => 'decimal:2',
        'default_waste_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}