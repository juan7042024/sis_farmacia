@extends('layouts.admin')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Proveedores</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProveedorModal">
        <i class="bi bi-plus-circle"></i> Nuevo proveedor
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-12"> {{-- Usamos col-12 para que la tabla tenga espacio como en la imagen 1 --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Listado de proveedores</h4>
                </div>
                <div class="card-body">
                    {{-- Seccion de buscador --}}
                    <form method="GET" action="{{ route('admin.proveedores.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="search" class="form-label mb-1">Buscar proveedor</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $search ?? '' }}" placeholder="Nombre, telefono, email o empresa">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                <a href="{{ route('admin.proveedores.index') }}" class="btn btn-light-secondary w-100">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    {{-- fin seccion de buscador --}}

                    {{-- mensaje de resultados de busqueda --}}
                    @if (!empty($search))
                        <div class="alert alert-success py-2 mb-3" role="alert">
                            Se encontraron {{ $proveedores->total() }} resultado(s) para "{{ $search }}".
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Empresa</th>
                                    <th>Dirección</th>
                                    <th style="width: 150px;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($proveedores as $proveedor)
                                    <tr>
                                        <td>{{ $proveedores->firstItem() + $loop->index }}</td>
                                        <td>{{ $proveedor->nombre }}</td>
                                        <td>{{ $proveedor->telefono }}</td>
                                        <td>{{ $proveedor->email ?? 'Sin email' }}</td>
                                        <td>{{ $proveedor->empresa ?? 'Sin empresa' }}</td>
                                        <td>{{ $proveedor->direccion ?? 'Sin direccion' }}</td>
                                        <td class="text-center">
                                            {{-- Botón Ver Detalles (Celeste) --}}
                                            <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#showProveedorModal-{{ $proveedor->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            {{-- Botón Editar (Verde) --}}
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editProveedorModal-{{ $proveedor->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Botón Eliminar (Rojo) --}}
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteProveedorModal-{{ $proveedor->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ========================================== --}}
                                    {{-- MODAL VER DETALLES (Celeste)              --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="showProveedorModal-{{ $proveedor->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title" style="color:white">Detalle del proveedor</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Nombre</label>
                                                            <input type="text" class="form-control" value="{{ $proveedor->nombre }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Teléfono</label>
                                                            <input type="text" class="form-control" value="{{ $proveedor->telefono }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Email</label>
                                                            <input type="text" class="form-control" value="{{ $proveedor->email ?? 'Sin email' }}" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label text-muted">Empresa</label>
                                                            <input type="text" class="form-control" value="{{ $proveedor->empresa ?? 'Sin empresa' }}" readonly>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label text-muted">Dirección</label>
                                                            <input type="text" class="form-control" value="{{ $proveedor->direccion ?? 'Sin direccion' }}" readonly>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label text-muted">Notas</label>
                                                            <input type="text" class="form-control" value="{{ $proveedor->notas ?? 'Sin notas' }}" readonly>
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
                                    {{-- MODAL EDITAR PROVEEDOR (Verde)            --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="editProveedorModal-{{ $proveedor->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" style="color:white">Editar proveedor</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.proveedores.update', $proveedor->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            {{-- Nombre --}}
                                                            <div class="col-md-6">
                                                                <label for="nombre-{{ $proveedor->id }}" class="form-label">Nombre (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                                    <input type="text" name="nombre" id="nombre-{{ $proveedor->id }}" class="form-control" value="{{ session('open_modal') === 'editProveedorModal-' . $proveedor->id ? old('nombre', $proveedor->nombre) : $proveedor->nombre }}" required>
                                                                </div>
                                                                @if (session('open_modal') === 'editProveedorModal-' . $proveedor->id)
                                                                    @error('nombre') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Teléfono --}}
                                                            <div class="col-md-6">
                                                                <label for="telefono-{{ $proveedor->id }}" class="form-label">Teléfono (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                                    <input type="text" name="telefono" id="telefono-{{ $proveedor->id }}" class="form-control" value="{{ session('open_modal') === 'editProveedorModal-' . $proveedor->id ? old('telefono', $proveedor->telefono) : $proveedor->telefono }}" required>
                                                                </div>
                                                                @if (session('open_modal') === 'editProveedorModal-' . $proveedor->id)
                                                                    @error('telefono') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Email --}}
                                                            <div class="col-md-6">
                                                                <label for="email-{{ $proveedor->id }}" class="form-label">Email</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                                    <input type="email" name="email" id="email-{{ $proveedor->id }}" class="form-control" value="{{ session('open_modal') === 'editProveedorModal-' . $proveedor->id ? old('email', $proveedor->email) : $proveedor->email }}">
                                                                </div>
                                                                @if (session('open_modal') === 'editProveedorModal-' . $proveedor->id)
                                                                    @error('email') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Empresa --}}
                                                            <div class="col-md-6">
                                                                <label for="empresa-{{ $proveedor->id }}" class="form-label">Empresa</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                                                    <input type="text" name="empresa" id="empresa-{{ $proveedor->id }}" class="form-control" value="{{ session('open_modal') === 'editProveedorModal-' . $proveedor->id ? old('empresa', $proveedor->empresa) : $proveedor->empresa }}">
                                                                </div>
                                                                @if (session('open_modal') === 'editProveedorModal-' . $proveedor->id)
                                                                    @error('empresa') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Dirección --}}
                                                            <div class="col-12">
                                                                <label for="direccion-{{ $proveedor->id }}" class="form-label">Dirección</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                                    <textarea name="direccion" id="direccion-{{ $proveedor->id }}" class="form-control" rows="2">{{ session('open_modal') === 'editProveedorModal-' . $proveedor->id ? old('direccion', $proveedor->direccion) : $proveedor->direccion }}</textarea>
                                                                </div>
                                                                @if (session('open_modal') === 'editProveedorModal-' . $proveedor->id)
                                                                    @error('direccion') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Notas --}}
                                                            <div class="col-12">
                                                                <label for="notas-{{ $proveedor->id }}" class="form-label">Notas</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-journal-text"></i></span>
                                                                    <textarea name="notas" id="notas-{{ $proveedor->id }}" class="form-control" rows="3">{{ session('open_modal') === 'editProveedorModal-' . $proveedor->id ? old('notas', $proveedor->notas) : $proveedor->notas }}</textarea>
                                                                </div>
                                                                @if (session('open_modal') === 'editProveedorModal-' . $proveedor->id)
                                                                    @error('notas') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
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
                                    {{-- MODAL ELIMINAR PROVEEDOR (Rojo)           --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="deleteProveedorModal-{{ $proveedor->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" style="color:white">Eliminar proveedor</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.proveedores.destroy', $proveedor->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>¿Estás seguro de eliminar al proveedor <strong>{{ $proveedor->nombre }}</strong>?</p>
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
                                        <td colspan="7" class="text-center py-4">
                                            <p class="text-muted mb-0">No hay proveedores registrados.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- PAGINACIÓN --}}
                    @if ($proveedores->count() > 0)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4">
                            <small class="text-muted mb-2 mb-md-0">
                                Mostrando {{ $proveedores->firstItem() }} a {{ $proveedores->lastItem() }} de {{ $proveedores->total() }} registros
                            </small>
                            <div>
                                {{ $proveedores->links('vendor.pagination.bootstrap-5-no-summary') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================== --}}
{{-- MODAL CREAR PROVEEDOR (Azul)              --}}
{{-- ========================================== --}}
<div class="modal fade" id="createProveedorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" style="color:white">Crear proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.proveedores.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Nombre --}}
                        <div class="col-md-6">
                            <label for="nombre-create" class="form-label">Nombre (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="nombre" id="nombre-create" class="form-control" value="{{ old('nombre') }}" placeholder="Nombre del proveedor" required>
                            </div>
                            @if (session('open_modal') === 'createProveedorModal')
                                @error('nombre') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Teléfono --}}
                        <div class="col-md-6">
                            <label for="telefono-create" class="form-label">Teléfono (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" id="telefono-create" class="form-control" value="{{ old('telefono') }}" placeholder="Telefono de contacto" required>
                            </div>
                            @if (session('open_modal') === 'createProveedorModal')
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
                            @if (session('open_modal') === 'createProveedorModal')
                                @error('email') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Empresa --}}
                        <div class="col-md-6">
                            <label for="empresa-create" class="form-label">Empresa</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" name="empresa" id="empresa-create" class="form-control" value="{{ old('empresa') }}" placeholder="Empresa asociada">
                            </div>
                            @if (session('open_modal') === 'createProveedorModal')
                                @error('empresa') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Dirección --}}
                        <div class="col-12">
                            <label for="direccion-create" class="form-label">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="direccion" id="direccion-create" class="form-control" rows="2" placeholder="Direccion del proveedor">{{ old('direccion') }}</textarea>
                            </div>
                            @if (session('open_modal') === 'createProveedorModal')
                                @error('direccion') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Notas --}}
                        <div class="col-12">
                            <label for="notas-create" class="form-label">Notas</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-journal-text"></i></span>
                                <textarea name="notas" id="notas-create" class="form-control" rows="3" placeholder="Notas adicionales">{{ old('notas') }}</textarea>
                            </div>
                            @if (session('open_modal') === 'createProveedorModal')
                                @error('notas') <div class="mt-1"><small class="text-danger">{{ $message }}</small></div> @enderror
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
        @if(session('open_modal') === 'createProveedorModal')
            var createModalElement = document.getElementById('createProveedorModal');
            if (createModalElement) {
                bootstrap.Modal.getOrCreateInstance(createModalElement).show();
            }
        @endif

        // Abrir modal de editar si hay errores
        @if(session('open_modal') && str_contains(session('open_modal'), 'editProveedorModal-'))
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