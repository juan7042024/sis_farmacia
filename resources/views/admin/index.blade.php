@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Dashboard</h3>
            <p class="text-muted">Resumen ejecutivo de los módulos activos en el sistema.</p>
        </div>
    </div>
</div>

<section class="section">
    {{-- ========================================== --}}
    {{-- TARJETAS SUPERIORES (MÉTRICAS)           --}}
    {{-- ========================================== --}}
    <div class="row">
        {{-- Usuarios --}}
        <div class="col-12 col-md-6 col-xl-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-3 bg-primary text-white d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Usuarios</h6>
                        <h2 class="mb-1 fw-bold">{{ $stats['usuarios'] }}</h2>
                        <small class="text-muted">Total de usuarios registrados</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Roles --}}
        <div class="col-12 col-md-6 col-xl-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-3 bg-info text-white d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Roles</h6>
                        <h2 class="mb-1 fw-bold">{{ $stats['roles'] }}</h2>
                        <small class="text-muted">Niveles de acceso disponibles</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sucursales --}}
        <div class="col-12 col-md-6 col-xl-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-3 bg-success text-white d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Sucursales</h6>
                        <h2 class="mb-1 fw-bold">{{ $stats['sucursales'] }}</h2>
                        <small class="text-muted">Puntos de venta activos</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Empleados --}}
        <div class="col-12 col-md-6 col-xl-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-3 bg-danger text-white d-flex justify-content-center align-items-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Empleados</h6>
                        <h2 class="mb-1 fw-bold">{{ $stats['empleados'] }}</h2>
                        <small class="text-muted">Total de empleados registrados</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TARJETAS INFERIORES (DETALLES)           --}}
    {{-- ========================================== --}}
    <div class="row">
        
        {{-- Tarjeta: Estado del sistema --}}
        <div class="col-12 col-lg-5 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h4 class="card-title fw-bold text-dark">Estado del sistema</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <strong class="me-2">Configuración:</strong>
                        <span class="badge {{ $stats['configuracion'] ? 'bg-success' : 'bg-danger' }}">
                            {{ $stats['configuracion'] ? 'Cargada' : 'No cargada' }}
                        </span>
                    </div>

                    @if ($config)
                        <div class="mb-3">
                            <strong>Nombre:</strong> <span class="text-muted">{{ $config->nombre }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong> <span class="text-muted">{{ $config->email }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Divisa:</strong> <span class="text-muted">{{ $config->divisa }}</span>
                        </div>
                    @else
                        <div class="alert alert-warning py-2 mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i> No hay configuración registrada aún.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tarjeta: Actividad de empleados --}}
        <div class="col-12 col-lg-7 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h4 class="card-title fw-bold text-dark">Actividad de empleados</h4>
                </div>
                <div class="card-body">
                    
                    <div class="row mb-4">
                        <div class="col-6">
                            <p class="text-muted mb-1">Activos</p>
                            <h3 class="text-primary fw-bold mb-0">{{ $stats['empleado_activos'] }}</h3>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-1">Inactivos</p>
                            <h3 class="text-secondary fw-bold mb-0">{{ $stats['empleado_inactivos'] }}</h3>
                        </div>
                    </div>

                    {{-- Cálculo del porcentaje de empleados activos --}}
                    @php
                        $totalEmpleados = $stats['empleados'];
                        $porcentajeActivos = $totalEmpleados > 0 ? round(($stats['empleado_activos'] / $totalEmpleados) * 100) : 0;
                    @endphp

                    {{-- Barra de progreso --}}
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" 
                             style="width: {{ $porcentajeActivos }}%;" 
                             aria-valuenow="{{ $porcentajeActivos }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">{{ $porcentajeActivos }}% de empleados están activos</small>

                </div>
            </div>
        </div>

    </div>
</section>
@endsection