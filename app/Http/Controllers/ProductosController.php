<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\FormaFarmaceutica;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\productos;
use Illuminate\Http\Request;
// asi se creo "php artisan make:model productos -mcr"
class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //variable de busqueda de presentaciones por el nombre
        $search = trim((string) request('search', ''));
        // consulta con el uso de ORM para listar las presentaciones y paginarlo
        $productos = productos::when(
            $search !== '', function ($query) use ($search){
                $query->where('nombre_comercial', 'like', '%' . $search . '%')
                ->orWhere('nombre_generico', 'like', '%' . $search . '%')
                ->orWhere('codigo_producto', 'like', '%' . $search . '%')
                ->orWhere('codigo_barra', 'like', '%' . $search . '%');
            })
            ->orderBy('nombre_comercial')
            ->paginate(2);

        return view('admin.productos.index', [
            'productos' => $productos,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // traer las llaves foraneas al formulario para poder crear el producto solo traer el id y nombre ordenado por el nombre
        $categorias = Categorias::orderBy('nombre')->get(['id', 'nombre']);
        $laboratorios = Laboratorio::orderBy('nombre')->get(['id', 'nombre']);
        $formaFarmaceuticas = FormaFarmaceutica::orderBy('nombre')->get(['id', 'nombre']);
        $presentaciones = Presentacion::orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.productos.create', [
            'categorias' => $categorias,
            'laboratorios' => $laboratorios,
            'formaFarmaceuticas' => $formaFarmaceuticas,
            'presentaciones' => $presentaciones
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //1 validar que envie la informacion correctamente
        //return response()->json($request);

        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'laboratorio_id' => 'nullable|exists:laboratorios,id',
            'forma_farmaceutica_id' => 'nullable|exists:forma_farmaceuticas,id',
            'presentacion_id' => 'nullable|exists:presentacions,id',
            'codigo_producto' => 'required|string|max:50|unique:productos,codigo_producto',
            'codigo_barra' => 'nullable|string|max:50|unique:productos,codigo_barra',
            'nombre_comercial' => 'required|string|max:255',
            'nombre_generico' => 'required|string|max:255',
            'concentracion' => 'nullable|string|max:100',
            'accion_terapeutica' => 'nullable|string|max:255',
            'unidad_medida' => 'nullable|string|max:50',
            'usa_receta' => 'boolean',
            'imagen' => 'nullable|image|max:2048', // Máximo 2MB
        ]);

        $producto = new productos();
        $producto->categoria_id = $request->categoria_id;
        $producto->laboratorio_id = $request->laboratorio_id;
        $producto->forma_farmaceutica_id = $request->forma_farmaceutica_id;
        $producto->presentacion_id = $request->presentacion_id;
        $producto->codigo_producto = $request->codigo_producto;
        $producto->codigo_barra = $request->codigo_barra;
        $producto->nombre_comercial = $request->nombre_comercial;
        $producto->nombre_generico = $request->nombre_generico;
        $producto->concentracion = $request->concentracion;
        $producto->accion_terapeutica = $request->accion_terapeutica;
        $producto->unidad_medida = $request->unidad_medida;
        $producto->usa_receta = $request->input('usa_receta', false); // por defecto se guarda como "false"

        //guardado de la imagen la ruta y la imagen

        if ($request->hasFile('imagen')) {
            // recuperar la imagen y su nombre
            $imagen = $request->file('imagen');
            // obtener el nombre para poder encriptarlo
            $extension = $imagen->getClientOriginalExtension();
            // Generar un nombre aleatorio/encriptado para el archivo manteniendo su extension en el archivo
            $nombreArchivo = bin2hex(random_bytes(32)) . '.' . $extension;
            // Crear directorio si no existe (php puro)
            // en storage\app\public\avatars
            $directorio = public_path('storage/productos');
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755, true);
            }
            // Mover la imagen (php puro) usando el request de avatar
            $imagen->move($directorio, $nombreArchivo);
            // guardar la direccion de texto en la base de datos
            $producto->imagen = 'productos/' . $nombreArchivo;
        } else {
            $producto->imagen = null; // Si no hay imagen, guardamos null
        }

        $producto->save();

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(productos $productos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(productos $productos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, productos $productos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(productos $productos)
    {
        //
    }
}
