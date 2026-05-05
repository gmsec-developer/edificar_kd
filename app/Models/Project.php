<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'status',
        'scn_data',
    ];

    // Relación con empresa
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}