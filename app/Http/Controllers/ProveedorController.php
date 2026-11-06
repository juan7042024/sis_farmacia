<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// asi se creo ""php artisan make:model Proveedor -mcrf" donde creamos la migracion, controlador, recursos de los metodos del controlador y factory"
class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //variable de busqueda de proveedores por el nombre
        $search = trim((string) request('search', ''));
        // consulta con el uso de ORM para listar los proveedores y paginarlo junto a las relaciones para traer los nombre de los registros relacionados
        $proveedores = Proveedor::when($search !== '', function ($query) use ($search){
                $query->where('nombre', 'like', '%' . $search . '%')
                ->orWhere('telefono', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('empresa', 'like', '%' . $search . '%');
            })
            ->orderBy('nombre')
            ->paginate(2);

        return view('admin.proveedores.index', [
            'proveedores' => $proveedores,
            'search' => $search
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // 1. Validaciones (basadas en tu imagen)
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'empresa' => ['nullable', 'string', 'max:150'],
            'notas' => ['nullable', 'string'],
        ]);

        // 2. Si falla, reabrimos el modal con los errores y los datos anteriores
        if ($validator->fails()) {
            return redirect()
                ->route('admin.proveedores.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'createProveedorModal');
        }

        // 3. Instanciar el nuevo proveedor
        $proveedor = new Proveedor();

        // 4. Asignar los valores usando trim() para quitar espacios
        $proveedor->nombre = trim($request->nombre);
        $proveedor->telefono = trim($request->telefono);
        
        // Uso de filled() para guardar NULL si el campo está vacío
        $proveedor->email = filled($request->email) ? trim($request->email) : null;
        $proveedor->direccion = filled($request->direccion) ? trim($request->direccion) : null;
        $proveedor->empresa = filled($request->empresa) ? trim($request->empresa) : null;
        $proveedor->notas = filled($request->notas) ? trim($request->notas) : null;

        // 5. Guardar en la base de datos
        $proveedor->save();

        // 6. Redirigir con mensaje de éxito (usando SweetAlert2 en el layout)
        return to_route('admin.proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Buscar el proveedor a editar
        $proveedor = Proveedor::findOrFail($id);

        // 2. Validaciones
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'empresa' => ['nullable', 'string', 'max:150'],
            'notas' => ['nullable', 'string'],
        ]);

        // 3. Si falla, reabrimos el modal específico de este proveedor
        if ($validator->fails()) {
            return redirect()
                ->route('admin.proveedores.index')
                ->withErrors($validator)
                ->withInput()
                // Aquí concatenamos el ID para abrir el modal correcto
                ->with('open_modal', 'editProveedorModal-' . $id);
        }

        // 4. Asignar los nuevos valores con trim() y filled()
        $proveedor->nombre = trim($request->nombre);
        $proveedor->telefono = trim($request->telefono);
        $proveedor->email = filled($request->email) ? trim($request->email) : null;
        $proveedor->direccion = filled($request->direccion) ? trim($request->direccion) : null;
        $proveedor->empresa = filled($request->empresa) ? trim($request->empresa) : null;
        $proveedor->notas = filled($request->notas) ? trim($request->notas) : null;

        // 5. Guardar los cambios en la base de datos
        $proveedor->update();

        // 6. Redirigir con mensaje de éxito
        return to_route('admin.proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //buscar el id del proveedor
        $proveedor = Proveedor::findOrFail($id);

        // elminar el registro directo( no tiene relaciones aun)
        $proveedor->delete();

        // 6. Redirigir con mensaje de éxito
        return to_route('admin.proveedores.index')->with('success', 'Proveedor ELIMINADO correctamente.');

    }
}
