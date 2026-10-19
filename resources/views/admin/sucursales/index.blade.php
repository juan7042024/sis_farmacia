@extends('layouts.admin')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Sucursales</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSucursalModal">
        <i class="bi bi-plus-circle"></i> Nueva sucursal
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-12 col-md-12"> {{-- Ajusté el ancho de la tarjeta para que se vea más amplia --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Listado de sucursales registradas</h4>
                </div>
                <div class="card-body">
                    {{-- Seccion de buscador --}}
                    <form method="GET" action="{{ route('admin.sucursales.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="search" class="form-label mb-1">Buscar sucursal</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $search ?? '' }}" placeholder="Escribe nombre, direccion o telefono">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                <a href="{{ route('admin.sucursales.index') }}"
                                    class="btn btn-light-secondary w-100">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    {{-- fin seccion de buscador --}}

                    {{-- mensaje de resultados de busqueda --}}
                    @if (!empty($search))
                        <div class="alert alert-success py-2 mb-3" role="alert">
                            Se encontraron {{ $sucursales->total() }} resultado(s) para "{{ $search }}".
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>Nombre</th>
                                    <th>Direccion</th>
                                    <th>Telefono</th>
                                    <th style="width: 100px;">Estado</th>
                                    <th style="width: 220px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sucursales as $sucursal)
                                    <tr>
                                        {{-- toma en cuenta la paginacion de paginate el orden del registro --}}
                                        <td>{{ $sucursales->firstItem() + $loop->index }}</td>
                                        <td>{{ $sucursal->nombre }}</td>
                                        <td>{{ $sucursal->direccion }}</td>
                                        <td>{{ $sucursal->telefono }}</td>
                                        <td>
                                            {{-- Badge condicional para el estado --}}
                                            @if ($sucursal->estado == 1 || $sucursal->estado == 'activa' || $sucursal->estado === true)
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-danger">Inactiva</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Botón Editar: Abre el modal específico para esta sucursal --}}
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editSucursalModal-{{ $sucursal->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Botón Eliminar: Abre el modal específico para esta sucursal --}}
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSucursalModal-{{ $sucursal->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ========================================== --}}
                                    {{-- MODAL EDITAR SUCURSAL (Dentro del bucle) --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="editSucursalModal-{{ $sucursal->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" style="color:white">Editar sucursal</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.sucursales.update', $sucursal->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        
                                                        {{-- Nombre --}}
                                                        <div class="form-group mb-3">
                                                            <label for="nombre-{{ $sucursal->id }}">Nombre (*)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                                                <input type="text" name="nombre" id="nombre-{{ $sucursal->id }}" class="form-control" 
                                                                    value="{{ session('open_modal') === 'editSucursalModal-' . $sucursal->id ? old('nombre', $sucursal->nombre) : $sucursal->nombre }}" required>
                                                            </div>
                                                            @if (session('open_modal') === 'editSucursalModal-' . $sucursal->id)
                                                                @error('nombre')
                                                                    <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                                                                @enderror
                                                            @endif
                                                        </div>

                                                        {{-- Dirección --}}
                                                        <div class="form-group mb-3">
                                                            <label for="direccion-{{ $sucursal->id }}">Direccion (*)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                                <textarea name="direccion" id="direccion-{{ $sucursal->id }}" class="form-control" rows="2" required>{{ session('open_modal') === 'editSucursalModal-' . $sucursal->id ? old('direccion', $sucursal->direccion) : $sucursal->direccion }}</textarea>
                                                            </div>
                                                            @if (session('open_modal') === 'editSucursalModal-' . $sucursal->id)
                                                                @error('direccion')
                                                                    <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                                                                @enderror
                                                            @endif
                                                        </div>

                                                        {{-- Teléfono --}}
                                                        <div class="form-group mb-3">
                                                            <label for="telefono-{{ $sucursal->id }}">Telefono</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                                <input type="text" name="telefono" id="telefono-{{ $sucursal->id }}" class="form-control" 
                                                                    value="{{ session('open_modal') === 'editSucursalModal-' . $sucursal->id ? old('telefono', $sucursal->telefono) : $sucursal->telefono }}" placeholder="Telefono de contacto">
                                                            </div>
                                                            @if (session('open_modal') === 'editSucursalModal-' . $sucursal->id)
                                                                @error('telefono')
                                                                    <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                                                                @enderror
                                                            @endif
                                                        </div>

                                                        {{-- Estado --}}
                                                        <div class="form-group mb-3">
                                                            <label for="estado-{{ $sucursal->id }}">Estado (*)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                                                <select name="estado" id="estado-{{ $sucursal->id }}" class="form-select" required>
                                                                    <option value="1" {{ (session('open_modal') === 'editSucursalModal-' . $sucursal->id ? old('estado', $sucursal->estado) : $sucursal->estado) == 1 ? 'selected' : '' }}>Activa</option>
                                                                    <option value="0" {{ (session('open_modal') === 'editSucursalModal-' . $sucursal->id ? old('estado', $sucursal->estado) : $sucursal->estado) == 0 ? 'selected' : '' }}>Inactiva</option>
                                                                </select>
                                                            </div>
                                                            @if (session('open_modal') === 'editSucursalModal-' . $sucursal->id)
                                                                @error('estado')
                                                                    <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                                                                @enderror
                                                            @endif
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
                                    {{-- MODAL ELIMINAR SUCURSAL (Dentro del bucle) --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="deleteSucursalModal-{{ $sucursal->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" style="color:white">Eliminar sucursal</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.sucursales.destroy', $sucursal->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>¿Está seguro de eliminar la sucursal <strong>{{ $sucursal->nombre }}</strong>?</p>
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
                                            <p class="text-muted mb-0">No hay sucursales registradas.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- INICIO DE LA PAGINACIÓN --}}
                    @if ($sucursales->count() > 0)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4">
                            <small class="text-muted mb-2 mb-md-0">
                                Mostrando {{ $sucursales->firstItem() }} a {{ $sucursales->lastItem() }} de {{ $sucursales->total() }} registros
                            </small>
                            <div>
                                {{-- para quitar textos en ingles --}}
                                {{ $sucursales->links('vendor.pagination.bootstrap-5-no-summary') }}
                            </div>
                        </div>
                    @endif
                    {{-- FIN DE LA PAGINACIÓN --}}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================== --}}
{{-- MODAL CREAR SUCURSAL (Fuera del bucle)    --}}
{{-- ========================================== --}}
<div class="modal fade" id="createSucursalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" style="color:white">Crear sucursal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.sucursales.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    
                    {{-- Nombre --}}
                    <div class="form-group mb-3">
                        <label for="nombre-create">Nombre (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                            <input type="text" name="nombre" id="nombre-create" class="form-control" value="{{ old('nombre') }}" placeholder="Nombre de la sucursal" required>
                        </div>
                        @if (session('open_modal') === 'createSucursalModal')
                            @error('nombre')
                                <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                            @enderror
                        @endif
                    </div>

                    {{-- Dirección --}}
                    <div class="form-group mb-3">
                        <label for="direccion-create">Direccion (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <textarea name="direccion" id="direccion-create" class="form-control" rows="2" placeholder="Direccion de la sucursal" required>{{ old('direccion') }}</textarea>
                        </div>
                        @if (session('open_modal') === 'createSucursalModal')
                            @error('direccion')
                                <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                            @enderror
                        @endif
                    </div>

                    {{-- Teléfono --}}
                    <div class="form-group mb-3">
                        <label for="telefono-create">Telefono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="telefono" id="telefono-create" class="form-control" value="{{ old('telefono') }}" placeholder="Telefono de contacto">
                        </div>
                        @if (session('open_modal') === 'createSucursalModal')
                            @error('telefono')
                                <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                            @enderror
                        @endif
                    </div>

                    {{-- Estado --}}
                    <div class="form-group mb-3">
                        <label for="estado-create">Estado (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                            <select name="estado" id="estado-create" class="form-select" required>
                                {{-- Asumiendo que guardas 1 para Activa y 0 para Inactiva --}}
                                <option value="1" {{ old('estado') == 1 ? 'selected' : '' }}>Activa</option>
                                <option value="0" {{ old('estado') == 0 ? 'selected' : '' }}>Inactiva</option>
                            </select>
                        </div>
                        @if (session('open_modal') === 'createSucursalModal')
                            @error('estado')
                                <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                            @enderror
                        @endif
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
        // Si hay un error de validación en el modal de crear
        @if(session('open_modal') === 'createSucursalModal')
            var createModalElement = document.getElementById('createSucursalModal');
            var createModal = bootstrap.Modal.getOrCreateInstance(createModalElement);
            createModal.show();
        @endif

        // Si hay un error de validación en algún modal de editar
        @if(session('open_modal') && str_contains(session('open_modal'), 'editSucursalModal-'))
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