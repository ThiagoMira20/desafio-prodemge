<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'logradouro',
        'cep',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'user_id',
        'complemento'
    ];
}
