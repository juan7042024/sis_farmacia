@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Productos</h3>
        {{-- Botón que redirige a la vista de creación --}}
        <button type="button" class="btn btn-primary" onclick="window.location='{{ route('admin.productos.create') }}'">
            <i class="bi bi-plus-circle"></i> Nuevo producto
        </button>
    </div>
</div>

<section class="section">
    <div class="row">
        {{-- Usamos col-12 para que la tabla ocupe el ancho completo como en la imagen 1 --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Listado de productos</h4>
                </div>
                <div class="card-body">
                    {{-- Seccion de buscador --}}
                    <form method="GET" action="{{ route('admin.productos.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="search" class="form-label mb-1">Buscar producto</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $search ?? '' }}" placeholder="Escribe el nombre del producto">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                {{-- Corregido: el botón limpiar debe apuntar a productos, no a presentaciones --}}
                                <a href="{{ route('admin.productos.index') }}" class="btn btn-light-secondary w-100">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    {{-- fin seccion de buscador --}}

                    {{-- mensaje de resultados de busqueda --}}
                    @if (!empty($search))
                        <div class="alert alert-info py-2 mb-3" role="alert">
                            {{-- Corregido: usar $productos en lugar de $presentaciones --}}
                            Se encontraron {{ $productos->total() }} resultado(s) para "{{ $search }}".
                        </div>
                    @endif

                                        <div class="table-responsive">
                        <table class="table table-striped mb-0 text-nowrap"> {{-- text-nowrap evita que el texto se rompa en varias líneas --}}
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Categoría</th>
                                    <th>Laboratorio</th>
                                    <th>Forma <br> farmacéutica</th>
                                    <th>Presentación</th>
                                    <th>Código <br> producto</th>
                                    <th>Código barra</th>
                                    <th>Nombre <br> comercial</th>
                                    <th>Nombre <br> genérico</th>
                                    <th>Concentración</th>
                                    <th class="text-center">Receta</th>
                                    <th style="width: 120px;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($productos as $producto)
                                    <tr>
                                        <td>{{ $productos->firstItem() + $loop->index }}</td>
                                        
                                        {{-- Relaciones --}}
                                        <td>{{ optional($producto->categoria)->nombre ?? 'No definida' }}</td>
                                        <td>{{ optional($producto->laboratorio)->nombre ?? 'No definido' }}</td>
                                        <td>{{ optional($producto->formaFarmaceutica)->nombre ?? 'No definida' }}</td>
                                        <td>{{ optional($producto->presentacion)->nombre ?? 'No definida' }}</td>
                                        
                                        {{-- Códigos y Nombres --}}
                                        <td>{{ $producto->codigo_producto }}</td>
                                        <td>{{ $producto->codigo_barra ?? 'No definido' }}</td>
                                        <td>{{ $producto->nombre_comercial }}</td>
                                        <td>{{ $producto->nombre_generico }}</td>
                                        
                                        {{-- Concentración (unimos la cantidad con la unidad de medida) --}}
                                        <td>
                                            {{ $producto->concentracion }}
                                            {{-- Si unidad_medida es un string directo en tu modelo, lo imprimimos --}}
                                            {{ $producto->unidad_medida }} 
                                        </td>

                                        {{-- Receta (Badge condicional) --}}
                                        <td class="text-center">
                                            <span class="badge {{ $producto->usa_receta ? 'bg-warning text-dark' : 'bg-success' }}">
                                                {{ $producto->usa_receta ? 'Sí' : 'No' }}
                                            </span>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="text-center">
                                            {{-- Botón Ver detalle --}}
                                            <button type="button" class="btn btn-sm btn-primary" title="Ver detalle"
                                                onclick="window.location='{{ route('admin.productos.show', $producto->id) }}'">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            
                                            {{-- Botón Editar --}}
                                            <button type="button" class="btn btn-sm btn-success" title="Editar"
                                                onclick="window.location='{{ route('admin.productos.edit', $producto->id) }}'">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Botón Eliminar --}}
                                            <button type="button" class="btn btn-sm btn-danger" title="Eliminar" data-bs-toggle="modal" 
                                                data-bs-target="#deleteProductoModal-{{ $producto->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ========================================== --}}
                                    {{-- MODAL ELIMINAR PRODUCTO                   --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="deleteProductoModal-{{ $producto->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" style="color:white">Eliminar producto</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>¿Estás seguro de eliminar el producto <strong>{{ $producto->nombre_comercial }}</strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        {{-- Se actualizó el colspan a 12 para abarcar todas las columnas nuevas --}}
                                        <td colspan="12" class="text-center py-4">
                                            <p class="text-muted mb-0">No hay productos registrados.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- PAGINACIÓN --}}
                    @if ($productos->count() > 0)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4">
                            <small class="text-muted mb-2 mb-md-0">
                                Mostrando {{ $productos->firstItem() }} a {{ $productos->lastItem() }} de {{ $productos->total() }} registros
                            </small>
                            <div>
                                {{ $productos->links('vendor.pagination.bootstrap-5-no-summary') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection