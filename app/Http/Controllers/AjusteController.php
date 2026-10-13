<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
// asi creamos el modelo "php artisan make:model ajuste -mcr" donde "-m", creamos la migracion, "c" el controlador y "r" los metodos del controlador
class AjusteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        
        //traer la divisas que tiene salida de arreglo
        $divisas = $this->getDivisas();
        $configuracion = Ajuste::query()->first();

        return view('admin.ajustes.index',[
            'divisas' => $divisas,
            'configuracion' => $configuracion
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar que envie los valores al JSON
        //return response()->json($request);

        // cargar los valores de las divisas que nos devuelve
         $divisas = $this->getDivisas();

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'divisa' => ['required', 'string', 'size:3'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'web' => ['nullable', 'url', 'max:255'],
        ]);

        if (!array_key_exists($validated['divisa'], $divisas)) {
            return back()
                ->withErrors(['divisa' => 'La divisa seleccionada no es válida.'])
                ->withInput();
        }

        // si tiene el registro, se pone los registros existentes, si no, crea uno nuevo
        $ajuste = Ajuste::query()->first() ?? new Ajuste();
        $ajuste->nombre = $request->nombre;
        $ajuste->descripcion = $request->descripcion;
        $ajuste->direccion = $request->direccion;
        $ajuste->telefono = $request->telefono;
        $ajuste->email = $request->email;
        $ajuste->divisa = $request->divisa;
        $ajuste->web = $request->web;

        //  // recuperar la imagen y su nombre
        // $logo = $request->file('logo');
        // // obtener el nombre para poder encriptarlo
        // $extension = $logo->getClientOriginalExtension();

        // // Generar un nombre aleatorio/encriptado para el archivo manteniendo su extension en el archivo
        // $nombreArchivo = bin2hex(random_bytes(32)) . '.' . $extension;
        
        // // Crear directorio si no existe (php puro)
        // $directorio = public_path('storage/ajustes');
        // if (!file_exists($directorio)) {
        //     mkdir($directorio, 0755, true);
        // }
        // // Mover la imagen (php puro) usando el request de avatar
        // $logo->move($directorio, $nombreArchivo);
        // // guardar la direccion de texto en la base de datos
        // $ajuste->logo = 'ajustes/' . $nombreArchivo;


        //-----------------------------------

        if($request->file('logo')) // si existe el avatar, que se actualiza
        {
            // sustituimos la imagen anterior para poner la nueva imagen si actualizan en dado caso
            Storage::disk('public')->delete($ajuste->logo);
             // recuperar la imagen y su nombre
            $imagen = $request->file('logo');
            // obtener el nombre para poder encriptarlo
            $extension = $imagen->getClientOriginalExtension();
    
            // Generar un nombre aleatorio/encriptado para el archivo manteniendo su extension en el archivo
            $nombreArchivo = bin2hex(random_bytes(32)) . '.' . $extension;
            
            // Crear directorio si no existe (php puro)
            $directorio = public_path('storage/ajustes');
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755, true);
            }
            // Mover la imagen (php puro) usando el request de avatar
            $imagen->move($directorio, $nombreArchivo);
            // guardar la direccion de texto en la base de datos
            $ajuste->logo = 'ajustes/' . $nombreArchivo;
        }

        //-----------------------------------
        // guardar el ajuste
        $ajuste->update();

        return redirect()->route('admin.ajustes.index')->with('success', 'Ajustes guardados correctamente.');
    } 

    // metodo de las divisas que tipo arreglo
    private function getDivisas(): array
    {
        // cargar las divisas 
        $divisas = []; // cargar las divisas en un arreglo
        $divisasPath = public_path('divisas.json');

        // si esta el archivo
        if(File::exists($divisasPath)){
            //descodificar el json
            $decoded = json_decode(File::get($divisasPath), true);
            if(is_array($decoded)){
                $divisas = $decoded;
                ksort($divisas);
            }
        }
        // devolverl el valor del arreglo
        return $divisas;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    

    /**
     * Display the specified resource.
     */
    public function show(Ajuste $ajuste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ajuste $ajuste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ajuste $ajuste)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ajuste $ajuste)
    {
        //
    }
}
