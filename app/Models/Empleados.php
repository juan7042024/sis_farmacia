<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    // uso de factory para llenar valores de ejemplo a empleado
    use HasFactory;
    //
    protected $table = 'empleados';

    protected $fillable = [
        'usuario_id',
        'sucursal_id',
        'nombres',
        'apellidos',
        'tipo_doc',
        'numero_doc',
        'telefono',
        'direccion',
        'profesion',
        'fecha_nacimiento',
        'genero',
        'avatar',
        'estado',
    ];

    //convertir el valor al guardarse en la base de datos
    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    // relacion destino de llave foranea que pertenece a 1 usuario 
    public function usuario(){
        return $this->belongsTo(User::class, 'usuario_id');
    }
    // relacion destino de llave foranea que pertenece a 1 una sucursal
    public function sucursal(){
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
