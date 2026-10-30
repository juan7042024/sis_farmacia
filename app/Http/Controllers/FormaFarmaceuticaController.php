<?php

namespace App\Http\Controllers;

use App\Models\FormaFarmaceutica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

// asi se creo "php artisan make:model FormaFarmaceutica -mcr"
class FormaFarmaceuticaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //variable de busqueda de formaFarmaceutica por el nombre
        $search = trim((string) request('search', ''));
        // consulta con el uso de ORM para listar los empleados y paginarlo
        $formaFarmaceuticas = FormaFarmaceutica::when(
            $search !== '', function ($query) use ($search){
                $query->where('nombre', 'like', '%' . $search . '%');
            })
            ->orderBy('nombre')
            ->paginate(2);

        return view('admin.forma_farmaceuticas.index', [
            'formaFarmaceuticas' => $formaFarmaceuticas,
            'search' => $search
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación (campo único en la tabla 'forma_farmaceuticas')
            $validator = Validator::make($request->all(), [
                'nombre' => ['required', 'string', 'max:150', 'unique:forma_farmaceuticas,nombre']
            ]);

            // 2. Si falla, reabrimos el modal
            if ($validator->fails()) {
                return redirect()
                    ->route('admin.forma_farmaceuticas.index')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('open_modal', 'createFormaFarmaceuticaModal');
            }

            // 3. Guardar el nuevo formaFarmaceutica instanciando nuevo registro para el formaFarmaceutica
            $formaFarmaceutica = new FormaFarmaceutica();
            $formaFarmaceutica->nombre = mb_strtoupper(trim($request->nombre)); // quitar espacios en esquinas y convertirlo a mayuscula
            $formaFarmaceutica->save();

            return to_route('admin.forma_farmaceuticas.index')->with('success', 'Forma farmaceutica creado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // 1. Validación (ignorando el ID actual en la regla unique)
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:150', 'unique:forma_farmaceuticas,nombre,' . $id]
        ]);

        // 2. Si falla, reabrimos el modal con el ID específico
        if ($validator->fails()) {
            return redirect()
                ->route('admin.forma_farmaceuticas.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'editFormaFarmaceuticaModal-' . $id);
        }

        // 3. Buscar el id de la formaFarmaceutica
        $formaFarmaceutica = FormaFarmaceutica::findOrFail($id);
        // actualizar la categoria y convertido a mayuscula
        $formaFarmaceutica->nombre = mb_strtoupper(trim($request->nombre));
        $formaFarmaceutica->update();

         return to_route('admin.forma_farmaceuticas.index')->with('success', 'Forma farmaceutica actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // 1. Buscar la formaFarmaceutica
            $formaFarmaceutica = FormaFarmaceutica::findOrFail($id);
            
            // 2. Eliminar el registro
            $formaFarmaceutica->delete();

            // 3. Redirigir con mensaje de éxito
            return to_route('admin.forma_farmaceuticas.index')
                ->with('success', 'FormaFarmaceutica eliminada correctamente.');

                // queryException para captar errores en la base de datos
        } catch (QueryException $e) {
            // 4. Si hay un error de llave foránea (ej. la categoría tiene productos asignados)
            return redirect()
                ->route('admin.forma_farmaceuticas.index')
                ->with('error', 'No se puede eliminar el la formaFarmaceutica porque está siendo utilizada en otros registros (como productos).');
        }
    }
}
