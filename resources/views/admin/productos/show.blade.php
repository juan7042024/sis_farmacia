@extends('layouts.admin')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Detalle del producto</h3>
    <a href="{{ route('admin.productos.index') }}" class="btn btn-light-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    
                    {{-- ========================================== --}}
                    {{-- CABECERA: Título y Badge de Receta --}}
                    {{-- ========================================== --}}
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h3 class="mb-1 fw-bold text-dark">{{ $producto->nombre_comercial }}</h3>
                            <p class="text-muted mb-0">{{ $producto->nombre_generico }}</p>
                        </div>
                        <div>
                            {{-- Badge condicional (Imagen 1 y 2) --}}
                            <span class="badge {{ $producto->usa_receta ? 'bg-warning text-dark' : 'bg-success' }} px-3 py-2 fs-6">
                                {{ $producto->usa_receta ? 'Requiere receta' : 'Venta libre' }}
                            </span>
                        </div>
                    </div>

                    {{-- ========================================== --}}
                    {{-- SECCIÓN SUPERIOR: Imagen y Datos Principales --}}
                    {{-- ========================================== --}}
                    <div class="row g-3 mb-4">
                        {{-- Imagen del producto --}}
                        <div class="col-12 col-md-4">
                            <div class="border rounded bg-light d-flex justify-content-center align-items-center h-100"
                                style="min-height: 250px; overflow: hidden;">

                                @if ($producto->imagen && file_exists(public_path('storage/' . $producto->imagen)))
                                    <img src="{{ asset('storage/' . $producto->imagen) }}"
                                        alt="{{ $producto->nombre_comercial }}"
                                        class="img-fluid"
                                        style="max-height: 100%; object-fit: contain;">
                                @else
                                    <div class="d-flex flex-column justify-content-center align-items-center text-center text-muted">
                                        <i class="bi bi-image fs-1 mb-2"></i>
                                        <br>
                                        <span>  Sin imagen registrada</span>
                                    </div>
                                @endif

                            </div>
                        </div>

                        {{-- Datos principales --}}
                        <div class="col-12 col-md-8">
                            <div class="row g-3">
                                {{-- Código Producto --}}
                                <div class="col-12 col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Código de producto</span>
                                        <span class="fs-6 text-dark fw-medium">{{ $producto->codigo_producto ?? 'No definido' }}</span>
                                    </div>
                                </div>
                                {{-- Código Barra --}}
                                <div class="col-12 col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Código de barra</span>
                                        <span class="fs-6 text-dark fw-medium">{{ $producto->codigo_barra ?? 'No definido' }}</span>
                                    </div>
                                </div>
                                {{-- Concentración --}}
                                <div class="col-12 col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Concentración</span>
                                        <span class="fs-6 text-dark fw-medium">{{ $producto->concentracion ?? 'No definida' }}</span>
                                    </div>
                                </div>
                                {{-- Unidad de medida --}}
                                <div class="col-12 col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Unidad de medida</span>
                                        <span class="fs-6 text-dark fw-medium">{{ $producto->unidad_medida ?? 'No definida' }}</span>
                                    </div>
                                </div>
                                {{-- Acción terapéutica --}}
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Acción terapéutica</span>
                                        <span class="fs-6 text-dark fw-medium">{{ $producto->accion_terapeutica ?? 'No definida' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    {{-- ========================================== --}}
                    {{-- SECCIÓN INFERIOR: Relaciones y Fechas --}}
                    {{-- ========================================== --}}
                    <div class="row g-3 mb-4">
                        {{-- Categoría --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="border rounded p-3 h-100">
                                <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Categoría</span>
                                <span class="fs-6 text-dark fw-medium">{{ optional($producto->categoria)->nombre ?? 'No definida' }}</span>
                            </div>
                        </div>
                        {{-- Laboratorio --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="border rounded p-3 h-100">
                                <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Laboratorio</span>
                                <span class="fs-6 text-dark fw-medium">{{ optional($producto->laboratorio)->nombre ?? 'No definido' }}</span>
                            </div>
                        </div>
                        {{-- Forma Farmacéutica --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="border rounded p-3 h-100">
                                <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Forma farmacéutica</span>
                                <span class="fs-6 text-dark fw-medium">{{ optional($producto->formaFarmaceutica)->nombre ?? 'No definida' }}</span>
                            </div>
                        </div>
                        {{-- Presentación --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="border rounded p-3 h-100">
                                <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Presentación</span>
                                <span class="fs-6 text-dark fw-medium">{{ optional($producto->presentacion)->nombre ?? 'No definida' }}</span>
                            </div>
                        </div>

                        {{-- Fecha de creación --}}
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3 h-100">
                                <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Fecha y hora de creación</span>
                                {{-- Formateamos la fecha usando Carbon --}}
                                <span class="fs-6 text-dark fw-medium">{{ $producto->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                        </div>
                        {{-- Fecha de actualización --}}
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3 h-100">
                                <span class="text-muted text-uppercase d-block mb-1" style="font-size: 0.75rem; font-weight: 600;">Fecha y hora de actualización</span>
                                <span class="fs-6 text-dark fw-medium">{{ $producto->updated_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    {{-- ========================================== --}}
                    {{-- BOTONES DE ACCIÓN --}}
                    {{-- ========================================== --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.productos.index') }}" class="btn btn-light-secondary px-4">Cerrar</a>
                        <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn btn-success text-white px-4">
                            <i class="bi bi-pencil-square me-1"></i> Editar
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection