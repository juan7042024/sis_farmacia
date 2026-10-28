<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// asi se creo "php artisan make:model Presentacion -mcr"
class Presentacion extends Model
{
    //

    // relacion de llave primaria de origen (hasMany) donde 1 laboratorio tiene muchos productos
    public function productos(){
        return $this->hasMany(productos::class, 'presentacion_id');
    }
}
