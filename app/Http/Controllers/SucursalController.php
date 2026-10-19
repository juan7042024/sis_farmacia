<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // filtar el buscado recibiendo del request del search
        $search = trim((string) request('search', ''));
        //listar pero ordenado el valor por el nombre aparte de ponerle un buscador en los registros de nombre direccion y telefono
        $sucursales = Sucursal::when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('direccion', 'like', '%' . $search . '%')
                    ->orWhere('telefono', 'like', '%' . $search . '%');
                });
            })
        ->orderBy('id', 'asc')->paginate(2);

        
        // devolver los valores a la vista
        return view('admin.sucursales.index', [
            'sucursales' => $sucursales,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        //
        // 1. Validaciones basadas en tu migración y modelo
        $validator = Validator::make($request->all(), [
            'nombre'    => ['required', 'string', 'max:150'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono'  => ['nullable', 'string', 'max:50'],
            // Validamos que el estado sea un valor booleano válido (0 o 1)
            'estado'    => ['required', 'boolean'],
        ]);

        // 2. Si la validación falla, redirigimos abriendo el modal
        if ($validator->fails()) {
            return redirect()
                ->route('admin.sucursales.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'createSucursalModal');
        }

        $sucursal = new Sucursal(); // instanciar nueva sucursal para crear la sucursal
        $sucursal->nombre = trim($request->nombre);
        $sucursal->direccion = trim($request->direccion);
        $sucursal->telefono = $request->filled('telefono') ? trim($request->telefono) : null;
        // El $cast a boolean en tu modelo convertirá automáticamente el "1" o "0" a true o false
        $sucursal->estado = $request->estado;
        $sucursal->save();

        // 4. Redirigir con mensaje de éxito
        return to_route('admin.sucursales.index')->with('success', 'Sucursal creada correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Validaciones
        $validator = Validator::make($request->all(), [
            'nombre'    => ['required', 'string', 'max:150', ],
                // // IMPORTANTE: El unique ignora el ID actual para no fallar si no cambian el nombre
                // 'unique:sucursals,nombre,' . $id 
            'direccion' => ['required', 'string', 'max:255'],
            'telefono'  => ['nullable', 'string', 'max:50'],
            'estado'    => ['required', 'boolean'],
        ]);

        // 2. Si la validación falla, redirigimos abriendo el modal de edición correspondiente
        if ($validator->fails()) {
            return redirect()
                ->route('admin.sucursales.index')
                ->withErrors($validator)
                ->withInput()
                // Aquí concatenamos el ID para que abra el modal específico de esta sucursal
                ->with('open_modal', 'editSucursalModal-' . $id);
        }

        // 3. Buscar la sucursal a editar
        $sucursal = Sucursal::findOrFail($id);

        // 4. Asignar los nuevos valores
        $sucursal->nombre = trim($request->nombre);
        $sucursal->direccion = trim($request->direccion);
        $sucursal->telefono = $request->filled('telefono') ? trim($request->telefono) : null;
        // El $cast a boolean en tu modelo se encarga de la conversión
        $sucursal->estado = $request->estado;
        
        // 5. actualizar los cambios
        $sucursal->update();

        // 6. Redirigir con mensaje de éxito
        return to_route('admin.sucursales.index')->with('success', 'Sucursal actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //buscar el id de la sucursal
        $sucursal = Sucursal::findOrFail($id);
        // eliminar la sucursal
        $sucursal->delete();
         // Redirigir con mensaje de éxito
        return to_route('admin.sucursales.index')->with('success', 'Sucursal Eliminado correctamente.');

    }
}
