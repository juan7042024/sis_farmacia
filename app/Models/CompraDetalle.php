<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// asi se creo "php artisan make:model CompraDetalle -mcrf"
class CompraDetalle extends Model
{
    /** @use HasFactory<\Database\Factories\CompraDetalleFactory> */
    use HasFactory;

    protected $table = 'compra_detalles';

    protected $fillable = [
        'compra_id',
        'producto_id',
        'lote_id',
        'precio_compra_unidad',
        'precio_venta_unidad',
        'porcentaje_ganancia_unidad',
        'cantidad',
    ];

    protected $casts = [
        'precio_compra_unidad' => 'decimal:2',
        'precio_venta_unidad' => 'decimal:2',
        'porcentaje_ganancia_unidad' => 'decimal:2',
        'cantidad' => 'integer',
    ];

    // Relaciones de llave foranea destino (belongsTo que muchas compras_detalle pertenecen a a compra, producto y lote)
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function producto()
    {
        return $this->belongsTo(productos::class, 'producto_id');
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }
}
