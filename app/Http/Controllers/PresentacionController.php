<?php

namespace App\Http\Controllers;

use App\Models\Presentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
// asi se creo "php artisan make:model Presentacion -mcr"
class PresentacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //variable de busqueda de presentaciones por el nombre
        $search = trim((string) request('search', ''));
        // consulta con el uso de ORM para listar las presentaciones y paginarlo
        $presentaciones = Presentacion::when(
            $search !== '', function ($query) use ($search){
                $query->where('nombre', 'like', '%' . $search . '%');
            })
            ->orderBy('nombre')
            ->paginate(2);

        return view('admin.presentaciones.index', [
            'presentaciones' => $presentaciones,
            'search' => $search
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación (campo único en la tabla 'presentacions' de la migracion)
            $validator = Validator::make($request->all(), [
                'nombre' => ['required', 'string', 'max:150', 'unique:presentacions,nombre']
            ]);

            // 2. Si falla, reabrimos el modal
            if ($validator->fails()) {
                return redirect()
                    ->route('admin.presentaciones.index')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('open_modal', 'createPresentacionModal');
            }

            // 3. Guardar el nuevo presentacion instanciando nuevo registro para las presentacions
            $presentacion = new Presentacion();
            $presentacion->nombre = mb_strtoupper(trim($request->nombre)); // quitar espacios en esquinas y convertirlo a mayuscula
            $presentacion->save();

            return to_route('admin.presentaciones.index')->with('success', 'Presentacion creado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Validación (ignorando el ID actual en la regla unique)
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:150', 'unique:presentacions,nombre,' . $id]
        ]);

        // 2. Si falla, reabrimos el modal con el ID específico
        if ($validator->fails()) {
            return redirect()
                ->route('admin.presentaciones.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'editPresentacionModal-' . $id);
        }

        // 3. Buscar el id de la formaFarmaceutica
        $presentacion = Presentacion::findOrFail($id);
        // actualizar la categoria y convertido a mayuscula
        $presentacion->nombre = mb_strtoupper(trim($request->nombre));
        $presentacion->update();

         return to_route('admin.presentaciones.index')->with('success', 'Presentacion actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // 1. Buscar la presentacion
            $presentacion = Presentacion::findOrFail($id);
            
            // 2. Eliminar el registro
            $presentacion->delete();

            // 3. Redirigir con mensaje de éxito
            return to_route('admin.presentaciones.index')
                ->with('success', 'Presentacion eliminada correctamente.');

                // queryException para captar errores en la base de datos
        } catch (QueryException $e) {
            // 4. Si hay un error de llave foránea (ej. la categoría tiene productos asignados)
            return redirect()
                ->route('admin.presentaciones.index')
                ->with('error', 'No se puede eliminar la Presentacion porque está siendo utilizada en otros registros (como productos).');
        }
    }
}
