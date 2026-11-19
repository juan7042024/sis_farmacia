@extends('layouts.admin')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Órdenes de compras</h3>
    {{-- Botón que redirige a la vista de creación --}}
    <button type="button" class="btn btn-primary" onclick="window.location='{{ route('admin.ordenes_compra.create') }}'">
        <i class="bi bi-plus-circle"></i> Nueva orden de compra
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-12"> 
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Listado de órdenes de compra</h4>
                </div>
                <div class="card-body">
                    {{-- Seccion de buscador --}}
                    <form method="GET" action="{{ route('admin.ordenes_compra.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="search" class="form-label mb-1">Buscar orden de compra</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $search ?? '' }}" placeholder="CI/NIT, nombre, telefono o email">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                <a href="{{ route('admin.ordenes_compra.index') }}" class="btn btn-light-secondary w-100">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    {{-- fin seccion de buscador --}}

                    {{-- mensaje de resultados de busqueda --}}
                    @if (!empty($search))
                        <div class="alert alert-success py-2 mb-3" role="alert">
                            Se encontraron {{ $compras->total() }} resultado(s) para "{{ $search }}".
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>Sucursal</th>
                                    <th>Proveedor</th>
                                    <th>Usuario</th>
                                    <th>Fecha de compra</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Comprobante</th>
                                    <th style="width: 150px;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($compras as $compra)
                                    <tr>
                                        <td>{{ $compras->firstItem() + $loop->index }}</td>
                                        <td>{{ $compra->sucursal->nombre ?? 'N/A' }}</td>
                                        <td>{{ $compra->proveedor->nombre ?? 'N/A' }}</td>
                                        <td>{{ $compra->usuario->name ?? 'N/A' }}</td>
                                        {{-- Formateamos la fecha si existe --}}
                                        <td>{{ $compra->fecha_compra ? \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $compra->total }}</td>
                                        <td>
                                            {{-- Badge condicional para el estado de la compra --}}
                                            @php
                                                $badgeColor = match(strtolower($compra->estado ?? '')) {
                                                    'completado', 'pagado', 'activo' => 'bg-success',
                                                    'pendiente' => 'bg-warning text-dark',
                                                    'cancelado' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeColor }}">
                                                {{ ucfirst($compra->estado ?? 'Desconocido') }}
                                            </span>
                                        </td>
                                        <td>{{ $compra->comprobante ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            {{-- Botones de Acción (Sin modales, usando redirecciones) --}}
                                            
                                            {{-- Botón Ver Detalles --}}
                                            <button type="button" class="btn btn-sm btn-info text-white" title="Ver detalles"
                                                onclick="window.location='{{ route('admin.ordenes_compra.index') }}'"> {{-- Reemplaza 'admin.ordenes_compra.index' por la ruta show cuando la tengas --}}
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            {{-- Botón Editar --}}
                                            <button type="button" class="btn btn-sm btn-success" title="Editar compra"
                                                onclick="window.location='{{ route('admin.ordenes_compra.index') }}'"> {{-- Reemplaza por la ruta edit cuando la tengas --}}
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Botón Eliminar (Puedes crear un formulario oculto o redirigir a un destroy si lo manejas por GET/JS, aquí queda como botón visual) --}}
                                            <button type="button" class="btn btn-sm btn-danger" title="Eliminar compra"
                                                onclick="alert('Funcionalidad de eliminar pendiente')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <p class="text-muted mb-0">No hay compras registradas.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- PAGINACIÓN --}}
                    @if ($compras->count() > 0)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4">
                            <small class="text-muted mb-2 mb-md-0">
                                Mostrando {{ $compras->firstItem() }} a {{ $compras->lastItem() }} de {{ $compras->total() }} registros
                            </small>
                            <div>
                                {{ $compras->links('vendor.pagination.bootstrap-5-no-summary') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection