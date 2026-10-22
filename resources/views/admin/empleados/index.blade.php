@extends('layouts.admin')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Empleados</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEmpleadoModal">
        <i class="bi bi-plus-circle"></i> Nuevo empleado
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Listado de empleados</h4>
                </div>
                <div class="card-body">
                    {{-- Seccion de buscador --}}
                    <form method="GET" action="{{ route('admin.empleados.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="search" class="form-label mb-1">Buscar empleado</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $search ?? '' }}" placeholder="Nombre, documento, telefono o sucursal">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                <a href="{{ route('admin.empleados.index') }}"
                                    class="btn btn-light-secondary w-100">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    {{-- fin seccion de buscador --}}

                    {{-- mensaje de resultados de busqueda --}}
                    @if (!empty($search))
                        <div class="alert alert-success py-2 mb-3" role="alert">
                            Se encontraron {{ $empleados->total() }} resultado(s) para "{{ $search }}".
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Empleado</th>
                                    <th>Usuario</th>
                                    <th>Sucursal</th>
                                    <th>Documento</th>
                                    <th>Telefono</th>
                                    <th>Estado</th>
                                    <th style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($empleados as $empleado)
                                    <tr>
                                        <td>{{ $empleados->firstItem() + $loop->index }}</td>
                                        <td>{{ $empleado->nombres }} {{ $empleado->apellidos }}</td>
                                        <td>{{ $empleado->usuario->name ?? 'N/A' }}</td>
                                        <td>{{ $empleado->sucursal->nombre ?? 'N/A' }}</td>
                                        <td>{{ $empleado->tipo_doc }} {{ $empleado->numero_doc }}</td>
                                        <td>{{ $empleado->telefono }}</td>
                                        <td>
                                            @if ($empleado->estado === 'activo')
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editEmpleadoModal-{{ $empleado->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteEmpleadoModal-{{ $empleado->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ========================================== --}}
                                    {{-- MODAL EDITAR EMPLEADO --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="editEmpleadoModal-{{ $empleado->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg"> {{-- modal-lg para que quepan las columnas --}}
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" style="color:white">Editar empleado</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.empleados.update', $empleado->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            {{-- Nombres --}}
                                                            <div class="col-md-6 mb-3">
                                                                <label for="nombres-{{ $empleado->id }}">Nombres (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                                                    <input type="text" name="nombres" id="nombres-{{ $empleado->id }}" class="form-control" value="{{ session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('nombres', $empleado->nombres) : $empleado->nombres }}" required>
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('nombres') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Apellidos --}}
                                                            <div class="col-md-6 mb-3">
                                                                <label for="apellidos-{{ $empleado->id }}">Apellidos (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                                                    <input type="text" name="apellidos" id="apellidos-{{ $empleado->id }}" class="form-control" value="{{ session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('apellidos', $empleado->apellidos) : $empleado->apellidos }}" required>
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('apellidos') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>

                                                            {{-- Tipo Documento --}}
                                                            <div class="col-md-4 mb-3">
                                                                <label for="tipo_doc-{{ $empleado->id }}">Tipo de documento (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                                                    <select name="tipo_doc" id="tipo_doc-{{ $empleado->id }}" class="form-select" required>
                                                                        @foreach(['CI', 'DNI', 'RUC', 'PASAPORTE', 'OTRO'] as $doc)
                                                                            <option value="{{ $doc }}" {{ (session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('tipo_doc', $empleado->tipo_doc) : $empleado->tipo_doc) == $doc ? 'selected' : '' }}>{{ $doc }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('tipo_doc') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Número Documento --}}
                                                            <div class="col-md-4 mb-3">
                                                                <label for="numero_doc-{{ $empleado->id }}">Número de documento (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                                                    <input type="text" name="numero_doc" id="numero_doc-{{ $empleado->id }}" class="form-control" value="{{ session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('numero_doc', $empleado->numero_doc) : $empleado->numero_doc }}" required>
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('numero_doc') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Teléfono --}}
                                                            <div class="col-md-4 mb-3">
                                                                <label for="telefono-{{ $empleado->id }}">Teléfono</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                                    <input type="text" name="telefono" id="telefono-{{ $empleado->id }}" class="form-control" value="{{ session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('telefono', $empleado->telefono) : $empleado->telefono }}">
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('telefono') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>

                                                            {{-- Profesión --}}
                                                            <div class="col-md-4 mb-3">
                                                                <label for="profesion-{{ $empleado->id }}">Profesión</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                                                    <input type="text" name="profesion" id="profesion-{{ $empleado->id }}" class="form-control" value="{{ session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('profesion', $empleado->profesion) : $empleado->profesion }}">
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('profesion') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Fecha Nacimiento --}}
                                                            <div class="col-md-4 mb-3">
                                                                <label for="fecha_nacimiento-{{ $empleado->id }}">Fecha de nacimiento</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                                                    {{-- Formateamos la fecha a Y-m-d para el input --}}
                                                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento-{{ $empleado->id }}" class="form-control" value="{{ session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('fecha_nacimiento', $empleado->fecha_nacimiento?->format('Y-m-d')) : $empleado->fecha_nacimiento?->format('Y-m-d') }}">
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('fecha_nacimiento') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Género --}}
                                                            <div class="col-md-4 mb-3">
                                                                <label for="genero-{{ $empleado->id }}">Género</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                                                    <select name="genero" id="genero-{{ $empleado->id }}" class="form-select">
                                                                        <option value="">Seleccione...</option>
                                                                        <option value="M" {{ (session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('genero', $empleado->genero) : $empleado->genero) == 'M' ? 'selected' : '' }}>Masculino</option>
                                                                        <option value="F" {{ (session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('genero', $empleado->genero) : $empleado->genero) == 'F' ? 'selected' : '' }}>Femenino</option>
                                                                    </select>
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('genero') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>

                                                            {{-- Email --}}
                                                            <div class="col-md-6 mb-3">
                                                                <label for="email-{{ $empleado->id }}">Email (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                                    <input type="email" name="email" id="email-{{ $empleado->id }}" class="form-control" value="{{ session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('email', $empleado->usuario->email ?? '') : ($empleado->usuario->email ?? '') }}" required>
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('email') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>
                                                            {{-- Sucursal --}}
                                                            <div class="col-md-6 mb-3">
                                                                <label for="sucursal_id-{{ $empleado->id }}">Sucursal (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                                                    <select name="sucursal_id" id="sucursal_id-{{ $empleado->id }}" class="form-select" required>
                                                                        <option value="">Seleccione...</option>
                                                                        @foreach($sucursales as $sucursal)
                                                                            <option value="{{ $sucursal->id }}" {{ (session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('sucursal_id', $empleado->sucursal_id) : $empleado->sucursal_id) == $sucursal->id ? 'selected' : '' }}>{{ $sucursal->nombre }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('sucursal_id') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>

                                                            {{-- Dirección --}}
                                                            <div class="col-12 mb-3">
                                                                <label for="direccion-{{ $empleado->id }}">Dirección</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                                    <textarea name="direccion" id="direccion-{{ $empleado->id }}" class="form-control" rows="2">{{ session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('direccion', $empleado->direccion) : $empleado->direccion }}</textarea>
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('direccion') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                            </div>

                                                            {{-- Avatar --}}
                                                            <div class="col-md-6 mb-3">
                                                                <label for="avatar-{{ $empleado->id }}">Avatar</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-image"></i></span>
                                                                    <input type="file" name="avatar" id="avatar-{{ $empleado->id }}" class="form-control" accept="image/*" onchange="previewImage(event, 'preview-avatar-{{ $empleado->id }}')">
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('avatar') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                                                                @endif
                                                                <div class="text-center mt-2">
                                                                    <img id="preview-avatar-{{ $empleado->id }}" 
                                                                         src="{{ $empleado->avatar ? asset('storage/' . $empleado->avatar) : '' }}" 
                                                                         alt="Previsualización" 
                                                                         class="img-fluid rounded" 
                                                                         style="max-height: 100px; {{ $empleado->avatar ? '' : 'display: none;' }}">
                                                                </div>
                                                            </div>
                                                            {{-- Estado --}}
                                                            <div class="col-md-6 mb-3">
                                                                <label for="estado-{{ $empleado->id }}">Estado (*)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                                                    <select name="estado" id="estado-{{ $empleado->id }}" class="form-select" required>
                                                                        <option value="activo" {{ (session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('estado', $empleado->estado) : $empleado->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                                                        <option value="inactivo" {{ (session('open_modal') === 'editEmpleadoModal-' . $empleado->id ? old('estado', $empleado->estado) : $empleado->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                                    </select>
                                                                </div>
                                                                @if (session('open_modal') === 'editEmpleadoModal-' . $empleado->id)
                                                                    @error('estado') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
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
                                    {{-- MODAL ELIMINAR EMPLEADO --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="deleteEmpleadoModal-{{ $empleado->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" style="color:white">Eliminar empleado</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.empleados.destroy', $empleado->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>¿Estás seguro de eliminar al empleado <strong>{{ $empleado->nombres }} {{ $empleado->apellidos }}</strong>?</p>
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
                                        <td colspan="8" class="text-center py-4">
                                            <p class="text-muted mb-0">No hay empleados registrados.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- PAGINACIÓN --}}
                    @if ($empleados->count() > 0)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4">
                            <small class="text-muted mb-2 mb-md-0">
                                Mostrando {{ $empleados->firstItem() }} a {{ $empleados->lastItem() }} de {{ $empleados->total() }} registros
                            </small>
                            <div>
                                {{ $empleados->links('vendor.pagination.bootstrap-5-no-summary') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================== --}}
{{-- MODAL CREAR EMPLEADO --}}
{{-- ========================================== --}}
<div class="modal fade" id="createEmpleadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" style="color:white">Crear empleado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.empleados.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        {{-- Nombres --}}
                        <div class="col-md-6 mb-3">
                            <label for="nombres-create">Nombres (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input type="text" name="nombres" id="nombres-create" class="form-control" value="{{ old('nombres') }}" required>
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('nombres') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Apellidos --}}
                        <div class="col-md-6 mb-3">
                            <label for="apellidos-create">Apellidos (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input type="text" name="apellidos" id="apellidos-create" class="form-control" value="{{ old('apellidos') }}" required>
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('apellidos') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>

                        {{-- Tipo Documento --}}
                        <div class="col-md-4 mb-3">
                            <label for="tipo_doc-create">Tipo de documento (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <select name="tipo_doc" id="tipo_doc-create" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach(['CI', 'DNI', 'RUC', 'PASAPORTE', 'OTRO'] as $doc)
                                        <option value="{{ $doc }}" {{ old('tipo_doc') == $doc ? 'selected' : '' }}>{{ $doc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('tipo_doc') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Número Documento --}}
                        <div class="col-md-4 mb-3">
                            <label for="numero_doc-create">Número de documento (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" name="numero_doc" id="numero_doc-create" class="form-control" value="{{ old('numero_doc') }}" required>
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('numero_doc') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Teléfono --}}
                        <div class="col-md-4 mb-3">
                            <label for="telefono-create">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" id="telefono-create" class="form-control" value="{{ old('telefono') }}">
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('telefono') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>

                        {{-- Profesión --}}
                        <div class="col-md-4 mb-3">
                            <label for="profesion-create">Profesión</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                <input type="text" name="profesion" id="profesion-create" class="form-control" value="{{ old('profesion') }}">
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('profesion') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Fecha Nacimiento --}}
                        <div class="col-md-4 mb-3">
                            <label for="fecha_nacimiento-create">Fecha de nacimiento</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento-create" class="form-control" value="{{ old('fecha_nacimiento') }}">
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('fecha_nacimiento') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Género --}}
                        <div class="col-md-4 mb-3">
                            <label for="genero-create">Género</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                <select name="genero" id="genero-create" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <option value="M" {{ old('genero') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('genero') == 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('genero') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                            <label for="email-create">Email (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email-create" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('email') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>
                        {{-- Sucursal --}}
                        <div class="col-md-6 mb-3">
                            <label for="sucursal_id-create">Sucursal (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <select name="sucursal_id" id="sucursal_id-create" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}" {{ old('sucursal_id') == $sucursal->id ? 'selected' : '' }}>{{ $sucursal->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('sucursal_id') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>

                        {{-- Dirección --}}
                        <div class="col-12 mb-3">
                            <label for="direccion-create">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="direccion" id="direccion-create" class="form-control" rows="2">{{ old('direccion') }}</textarea>
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('direccion') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                        </div>

                        {{-- Avatar --}}
                        <div class="col-md-6 mb-3">
                            <label for="avatar-create">Avatar</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="file" name="avatar" id="avatar-create" class="form-control" accept="image/*" onchange="previewImage(event, 'preview-avatar-create')">
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('avatar') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
                            @endif
                            <div class="text-center mt-2">
                                <img id="preview-avatar-create" src="" alt="Previsualización" class="img-fluid rounded" style="max-height: 100px; display: none;">
                            </div>
                        </div>
                        {{-- Estado --}}
                        <div class="col-md-6 mb-3">
                            <label for="estado-create">Estado (*)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select name="estado" id="estado-create" class="form-select" required>
                                    <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                            @if (session('open_modal') === 'createEmpleadoModal')
                                @error('estado') <div class="mt-1"><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small></div> @enderror
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
    // Función para previsualizar la imagen en los modales
    function previewImage(event, previewId) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById(previewId);
            output.src = reader.result;
            output.style.display = 'block'; // Mostrar la imagen
        };
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Abrir modal de crear si hay errores
        @if(session('open_modal') === 'createEmpleadoModal')
            var createModalElement = document.getElementById('createEmpleadoModal');
            var createModal = bootstrap.Modal.getOrCreateInstance(createModalElement);
            createModal.show();
        @endif

        // Abrir modal de editar si hay errores
        @if(session('open_modal') && str_contains(session('open_modal'), 'editEmpleadoModal-'))
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