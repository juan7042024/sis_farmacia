@extends('layouts.admin')

{{-- estilos de centrar los iconos en los modales --}}
@push('styles')
    <style>
        .modal .input-group .input-group-text {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal .input-group .input-group-text i {
            line-height: 1;
        }
    </style>
@endpush

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Usuarios</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="bi bi-plus-circle"></i> Nuevo usuario
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-12 col-md-8"> {{-- Ajusta el ancho de la tarjeta según prefieras --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Listado de usuarios registrados</h4>
                </div>
                <div class="card-body">
                    {{-- Seccion de buscador --}}
                    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="search" class="form-label mb-1">Buscar usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $search ?? '' }}" placeholder="Escribe nombre o correo">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                <a href="{{ route('admin.users.index') }}"
                                    class="btn btn-light-secondary w-100">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    {{-- fin seccion de buscador --}}

                    {{-- mensaje de resultados de busqueda --}}
                    @if (!empty($search))
                        <div class="alert alert-success py-2 mb-3" role="alert">
                            Se encontraron {{ $users->total() }} resultado(s) para "{{ $search }}".
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th style="width: 220px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        {{-- toma en cuenta la paginacion de paginate el orden del registro --}}
                                        <td>{{ $users->firstItem() + $loop->index }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        {{-- Obtenemos el primer rol del usuario, si no tiene, mostramos "Sin rol" --}}
                                        <td>{{ $user->roles->first()->name ?? 'Sin rol' }}</td>
                                        <td>
                                            {{-- Botón Editar: Abre el modal específico para este usuario --}}
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Botón Eliminar: Deshabilitado si es el usuario en sesión --}}
                                            @if ($user->id === auth()->id())
                                                {{-- Si es el usuario actual, mostramos el botón deshabilitado --}}
                                                <button type="button" class="btn btn-sm btn-danger" disabled title="No puedes eliminar tu propio usuario">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                {{-- Si es otro usuario, mostramos el botón normal que abre el modal --}}
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal-{{ $user->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- ========================================== --}}
                                    {{-- MODAL EDITAR USUARIO (Dentro del bucle)  --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" style="color:white">Editar usuario</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        
                                                        {{-- Nombre --}}
                                                        <div class="form-group mb-3">
                                                            <label for="name-{{ $user->id }}">Nombre (*)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                                <input type="text" name="name" id="name-{{ $user->id }}" class="form-control" 
                                                                    value="{{ session('open_modal') === 'editUserModal-' . $user->id ? old('name', $user->name) : $user->name }}" required>
                                                            </div>
                                                            @if (session('open_modal') === 'editUserModal-' . $user->id)
                                                                @error('name')
                                                                    <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                                                                @enderror
                                                            @endif
                                                        </div>

                                                        {{-- Email --}}
                                                        <div class="form-group mb-3">
                                                            <label for="email-{{ $user->id }}">Correo (*)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                                <input type="email" name="email" id="email-{{ $user->id }}" class="form-control" 
                                                                    value="{{ session('open_modal') === 'editUserModal-' . $user->id ? old('email', $user->email) : $user->email }}" required>
                                                            </div>
                                                            @if (session('open_modal') === 'editUserModal-' . $user->id)
                                                                @error('email')
                                                                    <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                                                                @enderror
                                                            @endif
                                                        </div>

                                                        {{-- Rol --}}
                                                        <div class="form-group mb-3">
                                                            <label for="role_id-{{ $user->id }}">Rol (*)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                                                <select name="role_id" id="role_id-{{ $user->id }}" class="form-select" required>
                                                                    <option value="">Seleccione un rol</option>
                                                                    @foreach ($roles as $role)
                                                                        <option value="{{ $role->id }}" 
                                                                            {{ (session('open_modal') === 'editUserModal-' . $user->id ? old('role_id', $user->roles->first()->id ?? '') : ($user->roles->first()->id ?? '')) == $role->id ? 'selected' : '' }}>
                                                                            {{ $role->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @if (session('open_modal') === 'editUserModal-' . $user->id)
                                                                @error('role_id')
                                                                    <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                                                                @enderror
                                                            @endif
                                                        </div>

                                                        {{-- Contraseña (Opcional en edición) --}}
                                                        <div class="form-group mb-3">
                                                            <label for="password-{{ $user->id }}">Contraseña (opcional)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                                                <input type="password" name="password" id="password-{{ $user->id }}" class="form-control" placeholder="Dejar vacío para mantener actual">
                                                            </div>
                                                            @if (session('open_modal') === 'editUserModal-' . $user->id)
                                                                @error('password')
                                                                    <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                                                                @enderror
                                                            @endif
                                                        </div>

                                                        {{-- Confirmar Contraseña --}}
                                                        <div class="form-group mb-3">
                                                            <label for="password_confirmation-{{ $user->id }}">Confirmar contraseña</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                                                <input type="password" name="password_confirmation" id="password_confirmation-{{ $user->id }}" class="form-control" placeholder="Repita la contraseña nueva">
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
                                    {{-- MODAL ELIMINAR USUARIO (Dentro del bucle) --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" style="color:white">Eliminar usuario</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>¿Está seguro de eliminar el usuario <strong>{{ $user->name }}</strong>?</p>
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
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-muted mb-0">No hay usuarios registrados actualmente.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- INICIO DE LA PAGINACIÓN --}}
                    @if ($users->count() > 0)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4">
                            <small class="text-muted mb-2 mb-md-0">
                                Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} registros
                            </small>
                            <div>
                                {{-- para quitar textos en ingles --}}
                                {{ $users->links('vendor.pagination.bootstrap-5-no-summary') }}
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
{{-- MODAL CREAR USUARIO (Fuera del bucle)     --}}
{{-- ========================================== --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" style="color:white">Crear usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    
                    {{-- Nombre --}}
                    <div class="form-group mb-3">
                        <label for="name-create">Nombre (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" id="name-create" class="form-control" value="{{ old('name') }}" placeholder="Nombre completo" required>
                        </div>
                        @if (session('open_modal') === 'createUserModal')
                            @error('name')
                                <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                            @enderror
                        @endif
                    </div>

                    {{-- Email --}}
                    <div class="form-group mb-3">
                        <label for="email-create">Correo (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email-create" class="form-control" value="{{ old('email') }}" placeholder="correo@dominio.com" required>
                        </div>
                        @if (session('open_modal') === 'createUserModal')
                            @error('email')
                                <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                            @enderror
                        @endif
                    </div>

                    {{-- Rol --}}
                    <div class="form-group mb-3">
                        <label for="role_id-create">Rol (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <select name="role_id" id="role_id-create" class="form-select" required>
                                <option value="">Seleccione un rol</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if (session('open_modal') === 'createUserModal')
                            @error('role_id')
                                <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                            @enderror
                        @endif
                    </div>

                    {{-- Contraseña --}}
                    <div class="form-group mb-3">
                        <label for="password-create">Contraseña (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" id="password-create" class="form-control" placeholder="Mínimo 8 caracteres" required>
                        </div>
                        @if (session('open_modal') === 'createUserModal')
                            @error('password')
                                <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div>
                            @enderror
                        @endif
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div class="form-group mb-3">
                        <label for="password_confirmation-create">Confirmar contraseña (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation-create" class="form-control" placeholder="Repita la contraseña" required>
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
        // Si hay un error de validación en el modal de crear
        @if(session('open_modal') === 'createUserModal')
            var createModalElement = document.getElementById('createUserModal');
            var createModal = bootstrap.Modal.getOrCreateInstance(createModalElement);
            createModal.show();
        @endif

        // Si hay un error de validación en algún modal de editar
        @if(session('open_modal') && str_contains(session('open_modal'), 'editUserModal-'))
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