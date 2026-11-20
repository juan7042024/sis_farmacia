<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraTmp;
use App\Models\productos;
use App\Models\Proveedor;
use App\Models\Sucursal;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// asi se creo ""php artisan make:model CompraTmp -mcrf"
class CompraTmpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // traer las compras ya paginadas
        $compras = Compra::paginate(10);
        //
        return view('admin.ordenes_compra.index', [
            'compras'=> $compras
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sucursales = Sucursal::all();
        $proveedores = Proveedor::all();
        // traer las relaciones de este modelo
        $productos = productos::with(['categoria', 'laboratorio', 'formaFarmaceutica', 'presentacion'])->get();

        return view('admin.ordenes_compra.create', [
            'sucursales' => $sucursales,
            'proveedores' => $proveedores,
            'productos' => $productos
        ]);

    }

    // metodo para agregar items en las compras
    public function addItems(Request $request){
        try{
            Log::info('=== START addItems ===');
            Log::info('Usuario Autenticado', ['user_id' => Auth::id(), 'user' => Auth::user()]);
            // mostrar la respuesta que recibio el request
            Log::info('Request data', $request->all());
    
            // validar que sean requerido estos campos de llave foranea
            $validated = $request->validate([
                'sucursal_id' => 'required|exists:sucursal,id',
                'producto_id' => 'required|exists:productos,id',
            ]);
    
            Log::info('Validated data:', $validated);
            // hacemos el guardado del registro en el modelo con las llaves foraneas validadas
            $item = CompraTmp::create([
                'usuario_id' => Auth::id(),
                'sucursal_id' => $validated['sucursal_id'],
                'producto_id' => $validated['producto_id'],
                'precio_compra_unidad' => 0,
                'precio_venta_unidad' => 0,
                'porcentaje_ganancia_unidad' => 0,
                'cantidad' => 0,
                'fecha_creacion' => Carbon::now(),
                'estado' => 'activo',
            ]);

            Log::info('Item creado:', ['id' => $item->id, 'item' => $item->toArray()]);

            //devolver una respuesta JSON para que ajax lo pueda interpretar y usar Javascript
            return response()->json([
                'success' => true,
                'item_id' => $item->id,
                'message' => 'Producto agregado'
            ], 201);
        }catch (\Illuminate\Validation\ValidationException $e) {
            // validar que no haya errores
            Log::error('ValidationException en addItems:', $e->errors());

            // mandar mensajes para el uso de ajax
            return response()->json([
                'success' => false,
                'message' => 'Error en validación',
                'errors' => $e->errors()
            ], 422);

            // error al momento de agregar items
        } catch (\Exception $e) {
            Log::error('Exception en addItems:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            // dando error al agregar producto para ajax
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    // metodo para actualizar los items 
    public function updateItem(Request $request, $itemId)
    {
    try {
        Log::info('=== START updateItem ===', ['itemId' => $itemId, 'user_id' => Auth::id()]);
        Log::info('Request data:', $request->all());

        $validated = $request->validate([
            'cantidad' => 'nullable|numeric|min:0',
            'precio_compra_unidad' => 'nullable|numeric|min:0',
            'precio_venta_unidad' => 'nullable|numeric|min:0',
            'porcentaje_ganancia_unidad' => 'nullable|numeric',
        ]);

        Log::info('Validated data:', $validated);

        $item = CompraTmp::where('id', $itemId)
            ->where('usuario_id', Auth::id())
            ->first();

        if (!$item) {
            Log::warning('Item no encontrado:', ['itemId' => $itemId, 'user_id' => Auth::id()]);
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado o no tienes permiso'
            ], 404);
        }

        $item->update($validated);
        Log::info('Item actualizado:', $item->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado'
        ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('ValidationException en updateItem:', $e->errors());

            return response()->json([
                'success' => false,
                'message' => 'Error en validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Exception en updateItem:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CompraTmp $compraTmp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompraTmp $compraTmp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompraTmp $compraTmp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompraTmp $compraTmp)
    {
        //
    }
}
