<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException; // Necesario para capturar errores de BD
// asi vamos a detalle el controlador "php artisan make:model Categorias -mcr"
class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //recibir el valor de la busqueda
        $search = trim((string) request('search', ''));

        // listar las categorias pasandole el filtro de busqueda con el fin de buscar los registros
        $categorias = Categorias::when($search !== '', function($query) use ($search){
            $query->where('nombre', 'like', '%' . $search . '%');
        })
        ->orderBy('nombre')
        ->paginate(10);

        return view('admin.categorias.index', [
            'categorias' => $categorias,
            'search' => $search
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

          // 1. Validación (campo único en la tabla 'categorias')
            $validator = Validator::make($request->all(), [
                'nombre' => ['required', 'string', 'max:150', 'unique:categorias,nombre']
            ]);

            // 2. Si falla, reabrimos el modal
            if ($validator->fails()) {
                return redirect()
                    ->route('admin.categorias.index')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('open_modal', 'createCategoriaModal');
            }

            // 3. Guardar la nueva categoría instanciando nuevo registro para la categoria
            $categoria = new Categorias();
            $categoria->nombre = mb_strtoupper(trim($request->nombre)); // quitar espacios en esquinas y convertirlo a mayuscula
            $categoria->save();

            return to_route('admin.categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // 1. Validación (ignorando el ID actual en la regla unique)
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:150', 'unique:categorias,nombre,' . $id]
        ]);

        // 2. Si falla, reabrimos el modal con el ID específico
        if ($validator->fails()) {
            return redirect()
                ->route('admin.categorias.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'editCategoriaModal-' . $id);
        }

        // 3. Buscar el id de la categoria
        $categoria = Categorias::findOrFail($id);
        // actualizar la categoria y convertido a mayuscula
        $categoria->nombre = mb_strtoupper(trim($request->nombre));
        $categoria->save();

        return to_route('admin.categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            try {
            // 1. Buscar la categoría
            $categoria = Categorias::findOrFail($id);
            
            // 2. Eliminar el registro
            $categoria->delete();

            // 3. Redirigir con mensaje de éxito
            return to_route('admin.categorias.index')
                ->with('success', 'Categoría eliminada correctamente.');

                // queryException para captar errores en la base de datos
        } catch (QueryException $e) {
            // 4. Si hay un error de llave foránea (ej. la categoría tiene productos asignados)
            return redirect()
                ->route('admin.categorias.index')
                ->with('error', 'No se puede eliminar la categoría porque está siendo utilizada en otros registros (como productos).');
        }
    }
}
