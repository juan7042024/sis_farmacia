<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// asi se creo "php artisan make:model FormaFarmaceutica -mcr"
class FormaFarmaceutica extends Model
{
    use HasFactory; // uso de factory para los seeders y el factory

    protected $fillable = [
        'nombre'
    ];

    // getter y setter del nombre para mayuscula en la generacion de los registros ficticios de prueba
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = mb_strtoupper(trim((string) $value));
    }

    public function getNombreAttribute($value)
    {
        return mb_strtoupper((string) $value);
    }

    // relacion de llave primaria de origen (hasMany) donde 1 formaFarmaceutica tiene muchos productos
    public function productos(){
        return $this->hasMany(productos::class, 'forma_farmaceutica_id');
    }
}
