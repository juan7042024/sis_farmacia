<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// asi se creo "php artisan make:model Lote -mcr" donde -mcr es la migracion, controlador y metodos del controlador
class Lote extends Model
{
    //uso de los factories para la asignacion masiva
    Use HasFactory;
    // creacion del campo donde se llenara el valor
    protected $fillable = [
        'nombre'
    ];
}
