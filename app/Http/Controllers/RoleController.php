<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // filtar el buscado recibiendo del request del search
        $search = trim((string) request('search', ''));
        //listar pero ordenado el valor por el nombre aparte de ponerle un buscador en los registros
        $roles = Role::when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
        ->orderBy('name')->paginate(2);

        return view('admin.roles.index', [
            'roles' => $roles,
            'search' => $search
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar que lleguen los valores a los campos
        //return response()->json($request);
         $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:100', 'unique:roles,name']
            ]);

            // validador del modal para que muestre el error dentro del modal y uso de javascript para mostrar errores dentro del modal
        if($validator->fails()){
            return redirect()
            ->route('admin.roles.index')
            ->withErrors($validator)
            ->withInput()
            ->with('open_modal', 'createRoleModal');
        }

        $rol = new Role();
        // guardar el valor en mayuscula 
        $rol->name = mb_strtoupper(trim($request->input('name')), 'UTF-8');
        $rol->guard_name = 'web';
        $rol->save();

        return to_route('admin.roles.index')->with('success', 'Rol creado correctamente');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $role = Role::findOrFail($id);

        //darle formato de mayuscula cuando se envie en el modal
        $request->merge([
            'name' => mb_strtoupper(trim((string) $request->input('name')), 'UTF-8'),
        ]);

        // validar el nombre del modal si no actualiza, que se guarde o si hay otro nombre que exista y lo quiere guardar, de error
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:roles,name,' . $role->id],
        ]);
        // mostrar el error si falla el mod
        if ($validator->fails()) {
            return redirect()
                ->route('admin.roles.index')
                ->withErrors($validator)
                ->withInput()
                // pasar el id para que muestre error de validacion en el modal de editar
                ->with('open_modal', 'editRoleModal-' . $role->id);
        }

        $role->name = $request->input('name');
        $role->save();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol actualizado correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //buscar el id
        $role = Role::findOrFail($id);
        // eliminar el rol con el id
        $role->delete();
        // devolver el mensaje de exito del rol
        return to_route('admin.roles.index')
            ->with('success', 'Rol eliminado correctamente.');

    }
}
