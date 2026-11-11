@extends('layouts.admin')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Clientes</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClienteModal">
        <i class="bi bi-plus-circle"></i> Nuevo cliente
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-12"> 
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Listado de clientes</h4>
                </div>
                <div class="card-body">
                    {{-- Seccion de buscador --}}
                    <form method="GET" action="{{ route('admin.clientes.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="search" class="form-label mb-1">Buscar cliente</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $search ?? '' }}" placeholder="CI/NIT, nombre, telefono o email">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                <a href="{{ route('admin.clientes.index') }}" class="btn btn-light-secondary w-100">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    {{-- fin seccion de buscador --}}

                    {{-- mensaje de resultados de busqueda --}}
                    @if (!empty($search))
                        <div class="alert alert-success py-2 mb-3" role="alert">
                            Se encontraron {{ $clientes->total() }} resultado(s) para "{{ $search }}".
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>CI/NIT</th>
                                    <th>Nombres y apellidos</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th style="width: 150px;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clientes as $cliente)
                                    <tr>
                                        <td>{{ $clientes->firstItem() + $loop->index }}</td>
                                        <td>{{ $cliente->ci_nit }}</td>
                                        <td>{{ $cliente->nombres_apellidos }}</td>
                                        <td>{{ $cliente->telefono ?? 'Sin telefono' }}</td>
                                        <td>{{ $cliente->email ?? 'Sin email' }}</td>
                                        <td class="text-center">
                                            {{-- Botón Ver Detalles (Celeste) --}}
                                            <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#showClienteModal-{{ $cliente->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            {{-- Botón Editar (Verde) --}}
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editClienteModal-{{ $cliente->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Botón Eliminar (Rojo) --}}
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteClienteModal-{{ $cliente->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ========================================== --}}
                                    {{-- MODAL VER DETALLES (Celeste)              --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="showClienteModal-{{ $cliente->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title" style="color:white">Detalle del cliente</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">CI/NIT</label>
                                                            <input type="text" class="form-control" value="{{ $cliente->ci_nit }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Nombres y apellidos</label>
                                                            <input type="text" class="form-control" value="{{ $cliente->nombres_apellidos }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Teléfono</label>
                                                            <input type="text" class="form-control" value="{{ $cliente->telefono ?? 'Sin telefono' }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Email</label>
                                                            <input type="text" class="form-control" value="{{ $cliente->email ?? 'Sin email' }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ========================================== --}}
                                    {{-- MODAL EDITAR CLIENTE (Verde)              --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="editClienteModal-{{ $cliente->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" style="color:white">Editar cliente</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            {{-- CI/NIT --}}
                                                            <div class="col-md-6">
                                                                <label for="ci_nit-{{ $cliente->id }}" class="form-label">CI/NIT (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                                                    <input type="text" name="ci_nit" id="ci_nit-{{ $cliente->id }}" class="form-control" value="{{ session('open_modal') === 'editClienteModal-' . $cliente->id ? old('ci_nit', $cliente->ci_nit) : $cliente->ci_nit }}">
                                                                </div>
                                                                @if (session('open_modal') === 'editClienteModal-' . $cliente->id)
                                                                    @error('ci_nit') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Nombres --}}
                                                            <div class="col-md-6">
                                                                <label for="nombres_apellidos-{{ $cliente->id }}" class="form-label">Nombres y apellidos (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                                    <input type="text" name="nombres_apellidos" id="nombres_apellidos-{{ $cliente->id }}" class="form-control" value="{{ session('open_modal') === 'editClienteModal-' . $cliente->id ? old('nombres_apellidos', $cliente->nombres_apellidos) : $cliente->nombres_apellidos }}">
                                                                </div>
                                                                @if (session('open_modal') === 'editClienteModal-' . $cliente->id)
                                                                    @error('nombres_apellidos') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Teléfono --}}
                                                            <div class="col-md-6">
                                                                <label for="telefono-{{ $cliente->id }}" class="form-label">Teléfono</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                                    <input type="text" name="telefono" id="telefono-{{ $cliente->id }}" class="form-control" value="{{ session('open_modal') === 'editClienteModal-' . $cliente->id ? old('telefono', $cliente->telefono) : $cliente->telefono }}">
                                                                </div>
                                                                @if (session('open_modal') === 'editClienteModal-' . $cliente->id)
                                                                    @error('telefono') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Email --}}
                                                            <div class="col-md-6">
                                                                <label for="email-{{ $cliente->id }}" class="form-label">Email</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                                    <input type="email" name="email" id="email-{{ $cliente->id }}" class="form-control" value="{{ session('open_modal') === 'editClienteModal-' . $cliente->id ? old('email', $cliente->email) : $cliente->email }}">
                                                                </div>
                                                                @if (session('open_modal') === 'editClienteModal-' . $cliente->id)
                                                                    @error('email') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-success text-white">Actualizar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ========================================== --}}
                                    {{-- MODAL ELIMINAR CLIENTE (Rojo)             --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="deleteClienteModal-{{ $cliente->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" style="color:white">Eliminar cliente</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.clientes.destroy', $cliente->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>¿Estas seguro de eliminar al cliente <strong>{{ $cliente->nombres_apellidos }}</strong>?</p>
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
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-muted mb-0">No hay clientes registrados.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- PAGINACIÓN --}}
                    @if ($clientes->count() > 0)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4">
                            <small class="text-muted mb-2 mb-md-0">
                                Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }} de {{ $clientes->total() }} registros
                            </small>
                            <div>
                                {{ $clientes->links('vendor.pagination.bootstrap-5-no-summary') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================== --}}
{{-- MODAL CREAR CLIENTE (Azul)                --}}
{{-- ========================================== --}}
<div class="modal fade" id="createClienteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" style="color:white">Crear cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.clientes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- CI/NIT --}}
                        <div class="col-md-6">
                            <label for="ci_nit-create" class="form-label">CI/NIT (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" name="ci_nit" id="ci_nit-create" class="form-control" value="{{ old('ci_nit') }}" placeholder="Documento del cliente">
                            </div>
                            @if (session('open_modal') === 'createClienteModal')
                                @error('ci_nit') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Nombres --}}
                        <div class="col-md-6">
                            <label for="nombres_apellidos-create" class="form-label">Nombres y apellidos (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="nombres_apellidos" id="nombres_apellidos-create" class="form-control" value="{{ old('nombres_apellidos') }}" placeholder="Nombre completo del cliente">
                            </div>
                            @if (session('open_modal') === 'createClienteModal')
                                @error('nombres_apellidos') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Teléfono --}}
                        <div class="col-md-6">
                            <label for="telefono-create" class="form-label">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" id="telefono-create" class="form-control" value="{{ old('telefono') }}" placeholder="Telefono de contacto">
                            </div>
                            @if (session('open_modal') === 'createClienteModal')
                                @error('telefono') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email-create" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email-create" class="form-control" value="{{ old('email') }}" placeholder="correo@dominio.com">
                            </div>
                            @if (session('open_modal') === 'createClienteModal')
                                @error('email') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Abrir modal de crear si hay errores
        @if(session('open_modal') === 'createClienteModal')
            var createModalElement = document.getElementById('createClienteModal');
            if (createModalElement) {
                bootstrap.Modal.getOrCreateInstance(createModalElement).show();
            }
        @endif

        // Abrir modal de editar si hay errores
        @if(session('open_modal') && str_contains(session('open_modal'), 'editClienteModal-'))
            var editModalId = '{{ session("open_modal") }}'; 
            var editModalElement = document.getElementById(editModalId);
            if (editModalElement) {
                bootstrap.Modal.getOrCreateInstance(editModalElement).show();
            }
        @endif
    });
</script>
@endpush
@endsection