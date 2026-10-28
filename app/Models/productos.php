<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// asi se creo "php artisan make:model productos -mcr"
class productos extends Model
{
    //asignacion masiva de los campos de nuevos registros a la BD

    protected $fillable = [

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
