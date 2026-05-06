<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialPrice extends Model
{
    protected $fillable = [
        'company_id',
        'material_code',
        'material_name',
        'color_code',
        'color_name',
        'unit_type',
        'unit_cost',
        'is_active',
    ];
}
