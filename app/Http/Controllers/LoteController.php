<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
// asi se creo "php artisan make:model Lote -mcr" donde -mcr es la migracion, controlador y metodos del controlador
class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //variable de busqueda de lotes por el nombre
        $search = trim((string) request('search', ''));
        // consulta con el uso de ORM para listar los lotes y paginarlo
        $lotes = Lote::when(
            $search !== '', function ($query) use ($search){
                $query->where('nombre', 'like', '%' . $search . '%');
            })
            ->orderBy('nombre')
            ->paginate(2);

        return view('admin.lotes.index', [
            'lotes' => $lotes,
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
                'nombre' => ['required', 'string', 'max:150', 'unique:lotes,nombre']
            ]);

            // 2. Si falla, reabrimos el modal
            if ($validator->fails()) {
                return redirect()
                    ->route('admin.lotes.index')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('open_modal', 'createLoteModal');
            }

            // 3. Guardar el nuevo "lote" instanciando nuevo registro para las presentacions
            $lote = new Lote();
            $lote->nombre = mb_strtoupper(trim($request->nombre)); // quitar espacios en esquinas y convertirlo a mayuscula
            $lote->save();

            return to_route('admin.lotes.index')->with('success', 'Lote creado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Validación (ignorando el ID actual en la regla unique)
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:150', 'unique:lotes,nombre,' . $id]
        ]);

        // 2. Si falla, reabrimos el modal con el ID específico
        if ($validator->fails()) {
            return redirect()
                ->route('admin.lotes.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'editLoteModal-' . $id);
        }

        // 3. Buscar el id de la Lote
        $lote = Lote::findOrFail($id);
        // actualizar y convertido a mayuscula
        $lote->nombre = mb_strtoupper(trim($request->nombre));
        $lote->update();

         return to_route('admin.lotes.index')->with('success', 'Lote actualizado correctamente.');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // 1. Buscar el ID de lote
            $lote = Lote::findOrFail($id);
            
            // 2. Eliminar el registro
            $lote->delete();

            // 3. Redirigir con mensaje de éxito
            return to_route('admin.lotes.index')
                ->with('success', 'Lote eliminada correctamente.');

                // queryException para captar errores en la base de datos
        } catch (QueryException $e) {
            // 4. Si hay un error de llave foránea (ej. la categoría tiene productos asignados)
            return redirect()
                ->route('admin.lotes.index')
                ->with('error', 'No se puede eliminar el LOTE porque está siendo utilizada en otros registros (como productos).');
        }
    }
}
