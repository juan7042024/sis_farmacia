<?php

namespace App\Http\Controllers;

use App\Models\Empleados;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // filtar el buscado recibiendo del request del search
        $search = trim((string) request('search', ''));
        //listar pero ordenado el valor por el nombre aparte de ponerle un buscador en los registros de nombre direccion y telefono
        $empleados = Empleados::with(['usuario:id,name,email', 'sucursal:id,nombre']) // cargar las relaciones que tiene empleado y solo traer los campos que vamos a usar
        ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nombres', 'like', '%' . $search . '%')
                    ->orWhere('apellidos', 'like', '%' . $search . '%')
                    ->orWhere('numero_doc', 'like', '%' . $search . '%')
                    ->orWhere('telefono', 'like', '%' . $search . '%')
                    // traer la relacion de sucursal para ser buscado en el filtro de busqueda el nombre
                    ->orWhereHas('sucursal',function ($query) use ($search){
                        $query->where('nombre', 'like', '%' . $search . '%');
                    });
                });
            })
        ->orderBy('apellidos')->paginate(2);
        // traer el nomre de la sucursal para el formulario de seleccion de sucursal, pero solo se enviaria el id de empleado de lo que se guardara
        $sucursales = Sucursal::orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.empleados.index', [
            'empleados' => $empleados,
            'search' => $search,
            'sucursales' => $sucursales
        ]);
        

    }

    /**
     * Guardar el empleado que a su vez se crea el usuario
     */
    public function store(Request $request)
    {
        // 1. Validaciones exactas basadas en tu imagen
        $validator = Validator::make($request->all(), [
            'sucursal_id' => ['required', 'exists:sucursals,id'],
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'tipo_doc' => ['required', 'in:CI,DNI,RUC,PASAPORTE,OTRO'],
            'numero_doc' => ['required', 'string', 'max:50', 'unique:empleados,numero_doc'],
            'telefono' => ['required', 'string', 'max:50'],
            'direccion' => ['required', 'string'],
            'profesion' => ['required', 'string', 'max:150'],
            'fecha_nacimiento' => ['required', 'date'],
            'genero' => ['required', 'in:M,F'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

          // 2. Si falla la validación, reabrimos el modal con los errores
        if ($validator->fails()) {
            return redirect()
                ->route('admin.empleados.index')
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'createEmpleadoModal');
        }

         // 3. Crear el Usuario
        // NOTA: En tu imagen el password estaba en texto plano. SIEMPRE debes usar Hash::make() 
        // para que el usuario pueda loguearse en Laravel.
        $user = new User();
        $user->name = trim($request->nombres) . ' ' . trim($request->apellidos);
        $user->email = strtolower(trim($request->email)); // se guarde en minuscula el correo
        $user->password = bcrypt($request->numero_doc);
        $user->save(); // enviar a guardar el usuario

        // 4. Asignar el rol de EMPLEADO usando Spatie
        $user->assignRole('EMPLEADO');
         

        // 5 crear el empleado
        $empleado = new Empleados();
        $empleado->usuario_id = $user->id;// agarrar el id del "user"
        // uso de trim para que se guarde sin espacios de inicio a fin en la base de datos
        $empleado->sucursal_id = $request->sucursal_id; // Laravel lo maneja sin (int)
        $empleado->nombres = trim($request->nombres);
        $empleado->apellidos = trim($request->apellidos);
        $empleado->tipo_doc = $request->tipo_doc;
        $empleado->numero_doc = trim($request->numero_doc);
        // Uso de filled() para guardar NULL en lugar de "" si están vacíos
        $empleado->telefono = filled($request->telefono) ? trim($request->telefono) : null;
        $empleado->direccion = filled($request->direccion) ? trim($request->direccion) : null;
        $empleado->profesion = filled($request->profesion) ? trim($request->profesion) : null;
        $empleado->fecha_nacimiento = filled($request->fecha_nacimiento) ? $request->fecha_nacimiento : null;
        $empleado->genero = filled($request->genero) ? $request->genero : null;

        //GUARDADO DEL AVATAR IMAGEN
        // Envolver la subida de imagen en un condicional por el NULL si no quiere subir su imagen para que no de error elgetClientOriginalExtension()

    if ($request->hasFile('avatar')) {
        // recuperar la imagen y su nombre
        $avatar = $request->file('avatar');
        // obtener el nombre para poder encriptarlo
        $extension = $avatar->getClientOriginalExtension();
        // Generar un nombre aleatorio/encriptado para el archivo manteniendo su extension en el archivo
        $nombreArchivo = bin2hex(random_bytes(32)) . '.' . $extension;
        // Crear directorio si no existe (php puro)
        // en storage\app\public\avatars
        $directorio = public_path('storage/avatars');
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }
        // Mover la imagen (php puro) usando el request de avatar
        $avatar->move($directorio, $nombreArchivo);
        // guardar la direccion de texto en la base de datos
        $empleado->avatar = 'avatars/' . $nombreArchivo;
    } else {
        $empleado->avatar = null; // Si no hay imagen, guardamos null
    }
         
        $empleado->estado = $request->estado;
        $empleado->save(); // enviar a guardar el empleado

         // 7. Redirigir con éxito
    return to_route('admin.empleados.index')->with('success', 'Empleado creado correctamente.');

    }
    /**
     * Actualizar el empleado
     */
    public function update(Request $request, string $id)
    {
        // 1. Buscar el empleado y su usuario relacionado
        $empleado = Empleados::findOrFail($id);
        // buscar el usuario que esta relacionado con el empleado
        $user = User::findOrFail($empleado->usuario_id);

        // 2. Validaciones
        $validator = Validator::make($request->all(), [
            'sucursal_id' => ['required', 'exists:sucursals,id'],
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            // IMPORTANTE: Ignorar el ID actual del usuario para el email
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'tipo_doc' => ['required', 'in:CI,DNI,RUC,PASAPORTE,OTRO'],
            // IMPORTANTE: Ignorar el ID actual del empleado para el número de documento
            'numero_doc' => ['required', 'string', 'max:50', 'unique:empleados,numero_doc,' . $empleado->id],
            'telefono' => ['required', 'string', 'max:50'],
            'direccion' => ['required', 'string'],
            'profesion' => ['required', 'string', 'max:150'],
            'fecha_nacimiento' => ['required', 'date'],
            'genero' => ['required', 'in:M,F'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        // 3. Si falla la validación, reabrimos el modal de editar con los errores
        if ($validator->fails()) {
            return redirect()
                ->route('admin.empleados.index')
                ->withErrors($validator)
                ->withInput()
                // Aquí concatenamos el ID para que abra el modal específico de este empleado
                ->with('open_modal', 'editEmpleadoModal-' . $id);
        }

        // 4. Actualizar los datos del Usuario
        $user->name = trim($request->nombres) . ' ' . trim($request->apellidos);
        $user->email = strtolower(trim($request->email));
        // Nota: Normalmente en un update no se cambia la contraseña a menos que se pida explícitamente.
        // Si quieres que la contraseña cambie cada vez que cambian el numero_doc, descomenta la siguiente línea:
        $user->password = bcrypt($request->numero_doc);
        $user->save();
        // 5. Actualizar los datos del Empleado
        $empleado->sucursal_id = $request->sucursal_id;
        $empleado->nombres = trim($request->nombres);
        $empleado->apellidos = trim($request->apellidos);
        $empleado->tipo_doc = $request->tipo_doc;
        $empleado->numero_doc = trim($request->numero_doc);
        // con "filled" para poner null si esta vacio en vez de que este vacio asi -> ""
        $empleado->telefono = filled($request->telefono) ? trim($request->telefono) : null;
        $empleado->direccion = filled($request->direccion) ? trim($request->direccion) : null;
        $empleado->profesion = filled($request->profesion) ? trim($request->profesion) : null;
        $empleado->fecha_nacimiento = filled($request->fecha_nacimiento) ? $request->fecha_nacimiento : null;
        $empleado->genero = filled($request->genero) ? $request->genero : null;
        $empleado->estado = $request->estado;
        // actualizacion de la imagen
        
        if($request->file('avatar')) // si existe el avatar, que se actualiza
        {
            // CORRECCIÓN 1: Verificar que el empleado realmente tenga un avatar antes de borrarlo
            // CORRECCIÓN 2: Usar public_path porque así lo guardaste en el store
            if ($empleado->avatar && file_exists(public_path('storage/' . $empleado->avatar))) {
                unlink(public_path('storage/' . $empleado->avatar));
            }
             // recuperar la imagen y su nombre
            $avatar = $request->file('avatar');
            // obtener el nombre para poder encriptarlo
            $extension = $avatar->getClientOriginalExtension();
    
            // Generar un nombre aleatorio/encriptado para el archivo manteniendo su extension en el archivo
            $nombreArchivo = bin2hex(random_bytes(32)) . '.' . $extension;
            
            // Crear directorio si no existe (php puro)
            // en storage\app\public\avatars
            $directorio = public_path('storage/avatars');
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755, true);
            }
            // Mover la imagen (php puro) usando el request de avatar
            $avatar->move($directorio, $nombreArchivo);
            // guardar la direccion de texto en la base de datos
            $empleado->avatar = 'avatars/' . $nombreArchivo;
        }
        // 7. Guardar los cambios en el empleado
        $empleado->save();

    // 8. Redirigir con éxito
    return to_route('admin.empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     * Eliminar el empleado con el rol
     */
    public function destroy(string $id)
    {
        //
    }
}
