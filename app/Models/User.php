<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\BelongsToCompany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

	protected $fillable = [
  	  'name',
   	 'email',
   	 'password',
   	 'is_active',
    	'avatar',
      ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }
}