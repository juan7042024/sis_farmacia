<?php

namespace App\Http\Controllers;

use App\Models\Empleados;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class EmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // filtar el buscado recibiendo del request del search
        $search = trim((string) request('search', ''));
        //listar pero ordenado el valor por el nombre aparte de ponerle un buscador en los registros de nombre direccion y telefono
        $empleados = Empleados::with(['usuario:id,name,email', 'sucursal:id,nombre']) // cargar las relaciones que tiene empleado y solo traer los campos que vamos a usar
        ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nombres', 'like', '%' . $search . '%')
                    ->orWhere('apellidos', 'like', '%' . $search . '%')
                    ->orWhere('numero_doc', 'like', '%' . $search . '%')
                    ->orWhere('telefono', 'like', '%' . $search . '%')
                    // traer la relacion de sucursal para ser buscado en el filtro de busqueda el nombre
                    ->orWhereHas('sucursal',function ($query) use ($search){
                        $query->where('nombre', 'like', '%' . $search . '%');
                    });
                });
            })
        ->orderBy('apellidos')->paginate(2);
        // traer el nomre de la sucursal para el formulario de seleccion de sucursal, pero solo se enviaria el id de empleado de lo que se guardara
        $sucursales = Sucursal::orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.empleados.index', [
            'empleados' => $empleados,
            'search' => $search,
            'sucursales' => $sucursales
        ]);
        

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleados $empleados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleados $empleados)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleados $empleados)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleados $empleados)
    {
        //
    }
}
