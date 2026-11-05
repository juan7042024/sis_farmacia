<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\FormaFarmaceutica;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        // consulta con el uso de ORM para listar las presentaciones y paginarlo junto a las relaciones para traer los nombre de los registros relacionados
        $productos = productos::with(['categoria', 'laboratorio', 'formaFarmaceutica', 'presentacion'])
        ->when($search !== '', function ($query) use ($search){
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
    public function show(string $id)
    {
        //buscar el id del producto y traer las relacion que tienen los modelos para acceder a los valores mediante la relacion
        $producto = productos::with(['categoria', 'laboratorio', 'formaFarmaceutica', 'presentacion'])->findOrFail($id);

        // pasar a la vista el id y la vista
        return view('admin.productos.show', [
            'producto' => $producto
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //llamar los valores para ponerlo en los inputs con las relaciones trayendo los nombres que tiene guardado
        $producto = productos::with(['categoria', 'laboratorio', 'formaFarmaceutica', 'presentacion'])->findOrFail($id);
        //return response()->json($productos);
        // traer las llaves foraneas al formulario para poder crear el producto solo traer el id y nombre ordenado por el nombre
        $categorias = Categorias::orderBy('nombre')->get(['id', 'nombre']);
        $laboratorios = Laboratorio::orderBy('nombre')->get(['id', 'nombre']);
        $formaFarmaceuticas = FormaFarmaceutica::orderBy('nombre')->get(['id', 'nombre']);
        $presentaciones = Presentacion::orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.productos.edit', [
            'producto' => $producto, // pasar los valores para llenar los valores de los campos con las relaciones
            'categorias' => $categorias,
            'laboratorios' => $laboratorios,
            'formaFarmaceuticas' => $formaFarmaceuticas,
            'presentaciones' => $presentaciones
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Buscar el producto a editar
        $producto = productos::findOrFail($id);

        // 2. Validar la información (Omitiendo el ID actual en las reglas unique)
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'laboratorio_id' => 'nullable|exists:laboratorios,id',
            'forma_farmaceutica_id' => 'nullable|exists:forma_farmaceuticas,id',
            'presentacion_id' => 'nullable|exists:presentacions,id',
            // Se agrega ",$id" al final para ignorar el registro actual
            'codigo_producto' => 'required|string|max:50|unique:productos,codigo_producto,' . $id,
            'codigo_barra' => 'nullable|string|max:50|unique:productos,codigo_barra,' . $id,
            'nombre_comercial' => 'required|string|max:255',
            'nombre_generico' => 'required|string|max:255',
            'concentracion' => 'nullable|string|max:100',
            'accion_terapeutica' => 'nullable|string|max:255',
            'unidad_medida' => 'nullable|string|max:50',
            'usa_receta' => 'boolean',
            'imagen' => 'nullable|image|max:2048', // Máximo 2MB
        ]);

        // 3. Asignar los nuevos valores
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
        
        // Si el checkbox viene marcado guarda 1 (true), si no, guarda 0 (false)
        $producto->usa_receta = $request->input('usa_receta', false);

        // 4. Guardado de la nueva imagen (si se seleccionó una)
        if ($request->hasFile('imagen')) {
            
            // Eliminar la imagen anterior si existe en el disco
            if ($producto->imagen && file_exists(public_path('storage/' . $producto->imagen))) {
                unlink(public_path('storage/' . $producto->imagen));
            }

            // Recuperar la nueva imagen y su nombre
            $imagen = $request->file('imagen');
            $extension = $imagen->getClientOriginalExtension();
            
            // Generar un nombre aleatorio/encriptado
            $nombreArchivo = bin2hex(random_bytes(32)) . '.' . $extension;
            
            // Crear directorio si no existe
            $directorio = public_path('storage/productos');
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755, true);
            }
            
            // Mover la nueva imagen
            $imagen->move($directorio, $nombreArchivo);
            
            // Actualizar la ruta en la base de datos
            $producto->imagen = 'productos/' . $nombreArchivo;
        }
        // Nota: No hace falta un "else" aquí. Si no se subió imagen, el campo $producto->imagen conserva su valor anterior automáticamente.

        // 5. Guardar los cambios en la base de datos
        $producto->update();

        // 6. Redirigir con mensaje de éxito
        return to_route('admin.productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // buscar el id del producto
        $producto = productos::findOrFail($id);

        // valida si la imagen esta en el disco para que lo elimine una vez del registro de los productos
        if(!empty($producto->imagen) && Storage::disk('public')->exists($producto->imagen)){
            Storage::disk('public')->delete($producto->imagen); // borra la imagen dentro del folder
        }
        // borrar el registro
        $producto->delete();
        // devolver la ruta
        return to_route('admin.productos.index')->with('success', 'Producto eliminado exitosamente');
    }
}
