<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //variable de busqueda de clientes por el nombre
        $search = trim((string) request('search', ''));
        // consulta con el uso de ORM para listar los clientes y paginarlo junto a las relaciones para traer los nombre de los registros relacionados
        $clientes = Cliente::when($search !== '', function ($query) use ($search){
                $query->where('ci_nit', 'like', '%' . $search . '%')
                ->orWhere('nombres_apellidos', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('telefono', 'like', '%' . $search . '%');
            })
            ->orderBy('nombres_apellidos')
            ->paginate(2);

        return view('admin.clientes.index', [
            'clientes' => $clientes,
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
            'ci_nit' => ['required', 'string', 'max:50', 'unique:clientes,ci_nit'],
            'nombres_apellidos' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:50'],
        ]);

        // 2. Si falla, reabrimos el modal con los errores y los datos anteriores
        if ($validator->fails()) {
            return redirect()
                ->route('admin.clientes.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'createClienteModal');
        }

        // 3. Instanciar el nuevo proveedor
        $cliente = new Cliente();

        // 4. Asignar los valores usando trim() para quitar espacios
        $cliente->ci_nit = trim($request->ci_nit);
        $cliente->nombres_apellidos = trim($request->nombres_apellidos);
        
        // Uso de filled() para guardar NULL si el campo está vacío
        $cliente->email = filled($request->email) ? trim($request->email) : null;
        $cliente->telefono = filled($request->telefono) ? trim($request->telefono) : null;

        // 5. Guardar en la base de datos
        $cliente->save();

        // 6. Redirigir con mensaje de éxito (usando SweetAlert2 en el layout)
        return to_route('admin.clientes.index')->with('success', 'Clientes creado correctamente.');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        // 1. Buscar el proveedor a editar
        $cliente = Cliente::findOrFail($id);

        // 1. Validaciones (basadas en tu imagen)
        $validator = Validator::make($request->all(), [
            'ci_nit' => ['required', 'string', 'max:50', 'unique:clientes,ci_nit,' . $id],
            'nombres_apellidos' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:50'],
        ]);

        // 3. Si falla, reabrimos el modal específico de este cliente
        if ($validator->fails()) {
            return redirect()
                ->route('admin.clientes.index')
                ->withErrors($validator)
                ->withInput()
                // Aquí concatenamos el ID para abrir el modal correcto
                ->with('open_modal', 'editClienteModal-' . $id);
        }

        // 4. Asignar los valores usando trim() para quitar espacios
        $cliente->ci_nit = trim($request->ci_nit);
        $cliente->nombres_apellidos = trim($request->nombres_apellidos);
        
        // Uso de filled() para guardar NULL si el campo está vacío
        $cliente->email = filled($request->email) ? trim($request->email) : null;
        $cliente->telefono = filled($request->telefono) ? trim($request->telefono) : null;

        // 5. Actualizar en la base de datos
        $cliente->update();

        // 6. Redirigir con mensaje de éxito (usando SweetAlert2 en el layout)
        return to_route('admin.clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         //buscar el id del proveedor
        $cliente = Cliente::findOrFail($id);

        // elminar el registro directo( no tiene relaciones aun)
        $cliente->delete();

        // 6. Redirigir con mensaje de éxito
        return to_route('admin.clientes.index')->with('success', 'Cliente ELIMINADO correctamente.');
    }
}
