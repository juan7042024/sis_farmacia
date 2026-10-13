<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// asi creamos el modelo "php artisan make:model ajuste -mcr" donde "-m", creamos la migracion, "c" el controlador y "r" los metodos del controlador
class Ajuste extends Model
{
    //

    // declarar los campos para la asignacion masiva
    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'telefono',
        'email',
        'divisa',
        'logo',
        'web'
    ];
}
