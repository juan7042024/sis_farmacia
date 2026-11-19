<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// asi se creo ""php artisan make:model CompraTmp -mcrf"
class CompraTmp extends Model
{
    /** @use HasFactory<\Database\Factories\CompraTmpFactory> */
    use HasFactory;

    protected $table = 'compra_tmps';

    protected $fillable = [
        'usuario_id',
        'sucursal_id',
        'producto_id',
        'precio_compra_unidad',
        'precio_venta_unidad',
        'porcentaje_ganancia_unidad',
        'cantidad',
        'fecha_creacion',
        'estado'
    ];

    // darle formato a los campos que se van a guardar en la base de datos
    protected $casts = [
        'fecha_creacion' => 'datetime',
        'precio_compra_unidad' => 'decimal:2',
        'precio_venta_unidad' => 'decimal:2',
        'porcentaje_ganancia_unidad' => 'decimal:2'
    ];

    // relaciones de llave foranea destino (belongsTo) que muchas comprasTmp pertenecen a 1 usuario
    public function usuario(){
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // relaciones de llave foranea destino (belongsTo) que muchas comprasTmp pertenecen a 1 producto
    public function producto(){
        return $this->belongsTo(productos::class, 'producto_id');
    }

    // relaciones de llave foranea destino (belongsTo) que muchas comprasTmp pertenecen a 1 sucursal
    public function sucursal(){
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
