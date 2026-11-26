<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    /** @use HasFactory<\Database\Factories\CompraFactory> */
    use HasFactory;

    protected $fillable = [
        'sucursal_id',
        'proveedor_id',
        'usuario_id',
        'fecha_compra',
        'total',
        'estado',
        'comprobante',
        'nota'
    ];

    // darle formato al guardar los valores
    protected $casts = [
        'fecha_compra' => 'datetime',
        'total' => 'decimal:2' // 2 decimas
    ];

    // relacion destino de las llaves foranesas (BelongsTo) de muchos pertenece al 1 registro 
    public function sucursal(){
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'usuario_id');
    }

}
