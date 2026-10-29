<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// asi se creo "php artisan make:model Laboratorio -mcr"
class LaboratorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //variable de busqueda de laboratorio por el nombre
        $search = trim((string) request('search', ''));
        // consulta con el uso de ORM para listar los empleados y paginarlo
        $laboratorios = Laboratorio::when(
            $search !== '', function ($query) use ($search){
                $query->where('nombre', 'like', '%' . $search . '%');
            })
            ->orderBy('nombre')
            ->paginate(2);

        return view('admin.laboratorios.index', [
            'laboratorios' => $laboratorios,
            'search' => $search
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validación (campo único en la tabla 'laboratorios')
            $validator = Validator::make($request->all(), [
                'nombre' => ['required', 'string', 'max:150', 'unique:laboratorios,nombre']
            ]);

            // 2. Si falla, reabrimos el modal
            if ($validator->fails()) {
                return redirect()
                    ->route('admin.laboratorios.index')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('open_modal', 'createLaboratorioModal');
            }

            // 3. Guardar el nuevo laboratorio instanciando nuevo registro para el laboratorio
            $laboratorio = new Laboratorio();
            $laboratorio->nombre = mb_strtoupper(trim($request->nombre)); // quitar espacios en esquinas y convertirlo a mayuscula
            $laboratorio->save();

            return to_route('admin.laboratorios.index')->with('success', 'Laboratorio creado correctamente.');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // 1. Validación (ignorando el ID actual en la regla unique)
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:150', 'unique:laboratorios,nombre,' . $id]
        ]);

        // 2. Si falla, reabrimos el modal con el ID específico
        if ($validator->fails()) {
            return redirect()
                ->route('admin.laboratorios.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'editLaboratorioModal-' . $id);
        }

        // 3. Buscar el id del laboratorio
        $laboratorio = Laboratorio::findOrFail($id);
        // actualizar la categoria y convertido a mayuscula
        $laboratorio->nombre = mb_strtoupper(trim($request->nombre));
        $laboratorio->save();

        return to_route('admin.laboratorios.index')->with('success', 'Laboratorio actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
           try {
            // 1. Buscar la categoría
            $laboratorio = Laboratorio::findOrFail($id);
            
            // 2. Eliminar el registro
            $laboratorio->delete();

            // 3. Redirigir con mensaje de éxito
            return to_route('admin.laboratorios.index')
                ->with('success', 'Categoría eliminada correctamente.');

                // queryException para captar errores en la base de datos
        } catch (QueryException $e) {
            // 4. Si hay un error de llave foránea (ej. la categoría tiene productos asignados)
            return redirect()
                ->route('admin.laboratorios.index')
                ->with('error', 'No se puede eliminar el laboratorio porque está siendo utilizada en otros registros (como productos).');
        }
    }
}
