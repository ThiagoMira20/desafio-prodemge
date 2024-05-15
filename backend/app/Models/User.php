<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'social_name',
        'cpf',
        'father_name',
        'mother_name',
        'phone',
        'email',
    ];

    public function street()
    {
        return $this->hasOne(Street::class, 'user_id', 'id');
    }
}
