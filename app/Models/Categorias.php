<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// asi lo creamos el modelo controlador y migracion, php artisan make:model Categorias -mcr
class Categorias extends Model
{
    // uso de factory para llenar valores de ejemplo a categorias
    use HasFactory;
    //asignacion masiva de que campos llenaremos en los registros de la base de datos
    protected $fillable = [
        'nombre'
    ];

}
