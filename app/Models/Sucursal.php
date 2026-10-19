<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// asi se creo "php artisan make:model Sucursal -mcr"
class Sucursal extends Model
{
    //

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'estado'
    ];

    //convertir el valor al guardarse en la base de datos
    protected $casts = [
        'estado' => 'boolean',
    ];
}
