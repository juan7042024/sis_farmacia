<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
// asi se creo "php artisan make:controller UserController -r"
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // filtar el buscado recibiendo del request del search
        $search = trim((string) request('search', ''));
        //listar pero ordenado el valor por el nombre aparte de ponerle un buscador en los registros de nombre y de correo
        $users = User::when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
        ->orderBy('name')->paginate(2);

        // traer los roles para listarlo en el formulario en la creacion
        $roles = Role::orderBy('name')->get(['id', 'name']);
        // devolver los valores a la vista
        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //1 validar de que se envie al request
        //return response()->json($request);

         // 1. Validaciones (basadas en la imagen 4)
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // 2. Si falla la validación, redirigimos con errores y abrimos el modal de crear
        if ($validator->fails()) {
            return redirect()
                ->route('admin.users.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'createUserModal');
        }

        // 3. Crear el usuario

        $user = new User();
        $user->name = trim($request->name); // con trim enviar los valores sin espacios al principio y final del texto
        $user->email = trim($request->email);
        $user->password = bcrypt($request->password); // ¡NUNCA guardes la contraseña en texto plano!
        $user->save();

        // $user = User::create([
        //     'name' => trim($request->name),
        //     'email' => trim($request->email),
        //     'password' => bcrypt($request->password), // ¡NUNCA guardes la contraseña en texto plano!
        // ]);

        // 4. Asignar el rol usando Spatie
        // Esto insertará automáticamente en la tabla 'model_has_roles'
        // colocando el role_id, model_type (App\Models\User) y model_id (el ID del usuario creado)
        // 4. Asignar el rol usando Spatie (CORREGIDO)
        // Buscamos el rol por su ID y luego lo asignamos
        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role);

        // 5. Redirigir con mensaje de éxito
        return to_route('admin.users.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // 1. Validaciones para el modal con "Validator"
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            // La regla 'unique' ignora el ID actual para permitir guardar sin cambiar el correo
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email,' . $id],
            // 'nullable' permite que la contraseña vaya vacía. 'confirmed' valida el campo password_confirmation
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // 2. Si falla la validación, redirigimos y abrimos el modal de editar correspondiente
        if ($validator->fails()) {
            return redirect()
                ->route('admin.users.index')
                ->withErrors($validator)
                ->withInput()
                // Aquí está la clave: enviamos el ID exacto del modal a abrir
                ->with('open_modal', 'editUserModal-' . $id); 
        }

        // 3. Buscar el usuario
        $user = User::findOrFail($id);

        // 4. Preparar los datos básicos
        // $data = [
        //     'name' => trim($request->name),
        //     'email' => trim($request->email),
        // ];
        $user->name = trim($request->name); // con trim enviar los valores sin espacios al principio y final del texto
        $user->email = trim($request->email);
       

        // 5. Solo actualizar la contraseña si el usuario escribió una nueva
        if ($request->filled('password')) {
             $user->password = bcrypt($request->password); // ¡NUNCA guardes la contraseña en texto plano!
            //$data['password'] = bcrypt($request->password);
        }

        // 6. Actualizar el usuario en la base de datos
        $user->update();

        // 7. Actualizar el rol usando Spatie
        // Usamos syncRoles en lugar de assignRole para reemplazar el rol anterior.
        // Si el usuario tenía el rol A y le ponemos el rol B, con syncRoles se queda solo con B.
        $role = Role::findOrFail($request->role_id);
        $user->syncRoles([$role]);

        // 8. Redirigir con mensaje de éxito
        return to_route('admin.users.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $user = User::query()->findOrFail($id);

         // si esta en la session y quiere borrarse a si mismo, no lo dejara
        if ((int) $user->id === (int) Auth::id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
        }
}
