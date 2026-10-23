<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// asi se creo "php artisan make:model Sucursal -mcr"
class Sucursal extends Model
{
    //declaracion del factory para llenar de registro al modelo para las pruebas
    use HasFactory;
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

    // relacion de 1 sucursal puede tener a muchos empleados
    public function empleados(){
        return $this->hasMany(Empleados::class, 'sucursal_id');
    } 
}
