<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// asi se creo "php artisan make:model productos -mcr"
class productos extends Model
{
    use HasFactory; // para que el factory llene de valores
    //asignacion masiva de los campos de nuevos registros a la BD
     protected $fillable = [
        'categoria_id',
        'laboratorio_id',
        'forma_farmaceutica_id',
        'presentacion_id',
        'codigo_producto',
        'codigo_barra',
        'nombre_comercial',
        'nombre_generico',
        'concentracion',
        'accion_terapeutica',
        'unidad_medida',
        'usa_receta',
        'imagen',
    ];

    // relacion destino de llave foranea(belongsTo), donde muchos productos pertenecen a 1 categoria
    public function categoria(){
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }

    // relacion destino de llave foranea(belongsTo), donde muchos productos pertenecen a 1 laboratorio
    public function laboratorio(){
        return $this->belongsTo(Laboratorio::class, 'laboratorio_id');
    }

    // relacion destino de llave foranea(belongsTo), donde muchos productos pertenecen a 1 formaFarmaceutica
    public function formaFarmaceutica(){
        return $this->belongsTo(FormaFarmaceutica::class, 'forma_farmaceutica_id');
    }

    // relacion destino de llave foranea(belongsTo), donde muchos productos pertenecen a 1 presentacion
    public function presentacion(){
        return $this->belongsTo(Presentacion::class, 'presentacion_id');
    }
}
