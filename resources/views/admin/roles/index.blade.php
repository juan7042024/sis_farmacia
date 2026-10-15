@extends('layouts.admin')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Roles</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
        <i class="bi bi-plus-circle"></i> Nuevo rol
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-12 col-md-8"> {{-- Ajusta el ancho de la tarjeta según prefieras --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Listado de roles registrados</h4>
                </div>
                <div class="card-body">
                    {{-- Seccion de buscador --}}
                    <form method="GET" action="{{ route('admin.roles.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="search" class="form-label mb-1">Buscar rol</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $search ?? '' }}" placeholder="Escribe el nombre del rol">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                <a href="{{ route('admin.roles.index') }}"
                                    class="btn btn-light-secondary w-100">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    {{-- fin seccion de buscador --}}
                    {{-- mensaje de resultados de busqueda --}}
                    @if (!empty($search))
                        <div class="alert alert-success py-2 mb-3" role="alert">
                            Se encontraron {{ $roles->total() }} resultado(s) para "{{ $search }}".
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>Nombre</th>
                                    <th style="width: 220px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $rol)
                                    <tr>
                                        {{-- toma en cuenta la paginacion de paginate el orden del registro --}}
                                        <td>{{ $roles->firstItem() + $loop->index }}</td>
                                        <td>{{ $rol->name }}</td>
                                        <td>
                                            {{-- Botón Editar: Abre el modal específico para este rol --}}
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editRoleModal-{{ $rol->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Botón Eliminar: Abre el modal específico para este rol --}}
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRoleModal-{{ $rol->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ========================================== --}}
                                    {{-- MODAL EDITAR ROL (Dentro del bucle)      --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="editRoleModal-{{ $rol->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" style="color:white">Editar rol</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.roles.update', $rol->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="name-{{ $rol->id }}">Nombre del rol (*)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                                                <input type="text" name="name" id="name-{{ $rol->id }}" class="form-control" value="{{ session('open_modal') === 'editRoleModal-' . $rol->id ? old('name', $rol->name) : $rol->name }}" required>
                                                            </div>
                                                            {{-- validar que este dentro del modal para que se pueda editar --}}
                                                            @if (session('open_modal') === 'editRoleModal-' . $rol->id)
                                                                @error('name')
                                                                    <div class="mt-1">
                                                                        <small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>
                                                                    </div>
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
                                    {{-- MODAL ELIMINAR ROL (Dentro del bucle)    --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="deleteRoleModal-{{ $rol->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" style="color:white">Eliminar rol</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.roles.destroy', $rol->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>Esta seguro de eliminar el rol {{ $rol->name }}?</p>
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
                                        <td colspan="3" class="text-center py-4">
                                            <p class="text-muted mb-0">No hay roles registrados actualmente.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- INICIO DE LA PAGINACIÓN --}}
                    @if ($roles->count() > 0)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4">
                            <small class="text-muted mb-2 mb-md-0">
                                Mostrando {{ $roles->firstItem() }} a {{ $roles->lastItem() }} de {{ $roles->total() }} registros
                            </small>
                            <div>
                                {{-- para quitar textos en ingles --}}
                                {{ $roles->links('vendor.pagination.bootstrap-5-no-summary') }}
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
{{-- MODAL CREAR ROL (Fuera del bucle)        --}}
{{-- ========================================== --}}
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" style="color:white">Crear rol</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name-create">Nombre del rol (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" name="name" id="name-create" class="form-control" value="{{ old('name') }}" placeholder="Nombre del rol" required>
                        </div>
                        @if (session('open_modal') === 'createRoleModal')
                            @error('name')
                                <div class="mt-1">
                                    <small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>
                                </div>
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
    // Pero para que el modal se abra automáticamente cuando la página recargue (después de fallar la validación), 
    // necesitas agregar un pequeño script al final de tu vista index.blade.php. Bootstrap 5 no abre el modal solo con PHP, 
    // requiere JavaScript.
    document.addEventListener('DOMContentLoaded', function() {
        // Si hay un error de validación en el modal de crear
        @if(session('open_modal') === 'createRoleModal')
            var createModalElement = document.getElementById('createRoleModal');
            // getOrCreateInstance evita errores si el modal ya estaba instanciado
            var createModal = bootstrap.Modal.getOrCreateInstance(createModalElement);
            createModal.show();
        @endif

        // Si hay un error de validación en algún modal de editar
          // Modal de Editar (Buscamos el string exacto que enviamos desde el controlador)
        @if(session('open_modal') && str_contains(session('open_modal'), 'editRoleModal-'))
            // Extraemos el ID del string 'editRoleModal-5' -> 'editRoleModal-5'
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