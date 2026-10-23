<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\Empleados;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
// asi se creo "php artisan make:controller Admin"
class Admin extends Controller
{
    public function index(){

    // traer la primera configuracion en ajustes
    $config = Ajuste::first();

    //un arreglo asociativo para traer detalles de los modulos cuanto hay de cada seccion
    $stats = [
        'configuracion' => $config ? 1 : 0,
        'roles' => Role::count(),
        'usuarios' => User::count(),
        'sucursales' => Sucursal::count(),
        'empleados' => Empleados::count(),
        // contar los registros de estados activos
        'empleado_activos' => Empleados::where('estado', 'activo')->count(),
        'empleado_inactivos' => Empleados::where('estado', 'inactivo')->count(),
    ];

    // sacar los ultimos empleados registrados de manera descendente trayendo la relacion de la sucursal y del usuario al que pertenecen dichos empleados
    $latestEmployees = Empleados::with(['usuario:id,name,email', 'sucursal:id,nombre'])
    ->orderByDesc('id')
    ->limit(5)
    ->get();

    // devolver a la vista los valores
    return view('admin.index', [
            'config' => $config,
            'stats' => $stats,
            'latestEmployees' => $latestEmployees
        ]);
    }
}
