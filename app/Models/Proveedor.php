<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// asi se creo ""php artisan make:model Proveedor -mcrf" donde creamos la migracion, controlador, recursos de los metodos del controlador y factory"
class Proveedor extends Model
{
    /** @use HasFactory<\Database\Factories\ProveedorFactory> */
    use HasFactory;
    // asociar el nombre a la migracion(poner el nombre recomendado)
    protected $table = 'proveedors';

    //public $timestamps = false; // para desactivar el guardado y actualizado del registro

    // asignacion masiva de datos a la base de datos por el factory
    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'direccion',
        'empresa',
        'notas'
    ];
}
