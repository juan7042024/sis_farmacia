<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// asi se creo "php artisan make:model FormaFarmaceutica -mcr"
class FormaFarmaceutica extends Model
{
    //

    // relacion de llave primaria de origen (hasMany) donde 1 formaFarmaceutica tiene muchos productos
    public function productos(){
        return $this->hasMany(productos::class, 'forma_farmaceutica_id');
    }
}
