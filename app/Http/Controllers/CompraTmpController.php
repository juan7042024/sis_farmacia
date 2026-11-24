<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraDetalle;
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
                'sucursal_id' => 'required|exists:sucursals,id',
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
        // logs de la actualizacion
        Log::info('=== START updateItem ===', ['itemId' => $itemId, 'user_id' => Auth::id()]);
        Log::info('Request data:', $request->all());

        // validacion de los valores que van a entrar
        $validated = $request->validate([
            'cantidad' => 'nullable|numeric|min:0',
            'precio_compra_unidad' => 'nullable|numeric|min:0',
            'precio_venta_unidad' => 'nullable|numeric|min:0',
            'porcentaje_ganancia_unidad' => 'nullable|numeric',
        ]);

        // datos validados en el log
        Log::info('Validated data:', $validated);

        // obtener el id de compra
        $item = CompraTmp::where('id', $itemId)
            ->where('usuario_id', Auth::id())
            ->first();

        // si no hay item marcar error            
        if (!$item) {
            Log::warning('Item no encontrado:', ['itemId' => $itemId, 'user_id' => Auth::id()]);
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado o no tienes permiso'
            ], 404);
        }

        // de lo contrario, se actualiza los valores que encontramos
        $item->update($validated);
        Log::info('Item actualizado:', $item->toArray());

        // devolver el valor del item actualizado
        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado'
        ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // error de validacion
            Log::error('ValidationException en updateItem:', $e->errors());

            // mostrar error de validaciones
            return response()->json([
                'success' => false,
                'message' => 'Error en validación',
                'errors' => $e->errors()
            ], 422);
        // error de actualizacion
        } catch (\Exception $e) {
            Log::error('Exception en updateItem:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            // marcar errores al actualizacion
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    //remover el item de compra
    public function removeItem($itemId)
    {
    try {
        Log::info('RemoveItem llamado con itemId: ' . $itemId . ', usuario: ' . Auth::id());

        // Verificar si el registro existe
        $existsAny = CompraTmp::where('id', $itemId)->first();

        // si no existe el item, encontrarlo
        if (!$existsAny) {
            Log::warning('Item no encontrado con id: ' . $itemId);
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en la base de datos',
                'debug' => [
                    'itemId_buscado' => $itemId,
                    'usuario_actual' => Auth::id()
                ]
            ], 404);
        }
        // buscar el id de la compraTmp
        $item = CompraTmp::where('id', $itemId)
            ->where('usuario_id', Auth::id())
            ->first();
            // si no hay item dar un mensaje de error
        if (!$item) {
            Log::warning('Item encontrado pero no pertenece al usuario. ItemId: ' . $itemId);
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar este producto',
            ], 403);
        }
        // de lo contrario borrar el registro

        $item->delete();

        // devolver un mensaje en JSON
        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado'
        ], 200);

        // si ocurre algun error en la eliminacion, aqui se mostrara
        } catch (\Exception $e) {
            Log::error('Error en removeItem: ' . $e->getMessage() . ' | ItemId: ' . $itemId);
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar producto: ' . $e->getMessage(),
                'debug' => [
                    'exception' => get_class($e),
                    'itemId' => $itemId
                ]
            ], 500);
        }
        
    }

    // METODO PARA LIMPIAR ITEMS
    public function clearItems(Request $request)
    {
        try {
            // recibe el id de los items
            $validated = $request->validate([
                'item_ids' => 'required|array|min:1',
                'item_ids.*' => 'integer|min:1',
            ]);

            // buscar el id del usuario autenticado que busque el id del usuario de la validacion
            $deleted = CompraTmp::where('usuario_id', Auth::id())
                ->whereIn('id', $validated['item_ids'])
                ->delete(); // borrar el registro

            // devolverl el carrito limpio
            return response()->json([
                'success' => true,
                'message' => 'Carrito limpiado correctamente',
                'deleted_count' => $deleted,
            ], 200);

        // validar los datos invalidos
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos para limpiar carrito',
                'errors' => $e->errors(),
            ], 422);

            // mostrar si ocurre algun error
        } catch (\Exception $e) {
            Log::error('Error en clearItems: ' . $e->getMessage());
            // mostrar el error al limpiar el carrito
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar carrito: ' . $e->getMessage(),
            ], 500);
        }
    

    /**
     * Store a newly created resource in storage.
     */
    try {
        // ===== VALIDAR DATOS =====
        $validated = $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'carrito' => 'required|json',
        ]);

        // ===== DECODIFICAR CARRITO =====
        $carrito = json_decode($request->input('carrito'), true) ?? [];

        if (empty($carrito)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío'
            ], 400);
        }

        $itemsValidos = collect($carrito)
            ->filter(function ($item) {
                return isset($item['producto_id'], $item['cantidad'])
                    && (int) $item['cantidad'] > 0;
            })
            ->values();

        if ($itemsValidos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Debe ingresar al menos un item con cantidad mayor a 0.'
            ], 422);
        }

        // ===== INICIAR TRANSACCIÓN (se modificaran 3 modelos a la vez) =====
        DB::beginTransaction();

        try {
            $usuarioId = Auth::id(); // usuario ID de la sesion
            $sucursalId = $validated['sucursal_id'];
            $proveedorId = $validated['proveedor_id'];
            $ahora = Carbon::now();
        
        $totalCompra = $itemsValidos->sum(function ($item) {
            return ((float) ($item['cantidad'] ?? 0)) * ((float) ($item['precio_compra'] ?? 0));
        });

        // ===== CREAR CABECERA DE COMPRA =====
        $compra = new Compra();
        $compra->sucursal_id = $sucursalId;
        $compra->proveedor_id = $proveedorId;
        $compra->usuario_id = $usuarioId;
        $compra->fecha_compra = $ahora->toDateString();
        $compra->total = $totalCompra;
        $compra->estado = 'pendiente';
        $compra->comprobante = null;
        $compra->nota = null;
        $compra->save();

        $compraId = $compra->id;

        // ===== CREAR DETALLES DE COMPRA =====
        foreach ($itemsValidos as $item) {
            $productoId = (int) $item['producto_id'];

            $detalle = new CompraDetalle();
            $detalle->compra_id = $compraId;
            $detalle->producto_id = $productoId;
            $detalle->lote_id = null;
            $detalle->precio_compra_unidad = (float) ($item['precio_compra'] ?? 0);
            $detalle->precio_venta_unidad = (float) ($item['precio_venta'] ?? 0);
            $detalle->porcentaje_ganancia_unidad = (float) ($item['porcentaje_ganancia'] ?? 0);
            $detalle->cantidad = (int) ($item['cantidad'] ?? 0);
            $detalle->save();
        }

        // ===== LIMPIAR TEMPORALES USADOS =====
        $idsTmp = collect($carrito)
            ->pluck('id')
            ->filter()
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        if (!empty($idsTmp)) {
            CompraTmp::where('usuario_id', $usuarioId)
            ->whereIn('id', $idsTmp)
            ->delete();
            }

            // ===== COMMIT TRANSACCIÓN =====
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Compra registrada exitosamente',
                'compra_id' => $compraId,
                'redirect' => route('admin.ordenes_compra.index')
            ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la validación',
                    'errors' => $e->errors()
                ], 422);

            } catch (\Exception $e) {
                Log::error('Error en CompraTmpController@store: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar la orden: ' . $e->getMessage()
                ], 500);
            }
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
