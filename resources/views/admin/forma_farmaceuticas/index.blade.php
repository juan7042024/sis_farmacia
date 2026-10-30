@extends('layouts.admin')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Formas farmacéuticas</h3>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFormaFarmaceuticaModal">
        <i class="bi bi-plus-circle"></i> Nueva forma farmacéutica
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-8"> {{-- Se mantiene el ancho de 8 columnas --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Listado de formas farmacéuticas</h4>
                </div>
                <div class="card-body">
                    {{-- Seccion de buscador --}}
                    <form method="GET" action="{{ route('admin.forma_farmaceuticas.index') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="search" class="form-label mb-1">Buscar forma farmacéutica</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                        value="{{ $search ?? '' }}" placeholder="Escribe el nombre de la forma farmacéutica">
                                </div>
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                                <a href="{{ route('admin.forma_farmaceuticas.index') }}" class="btn btn-light-secondary w-100">Limpiar</a>
                            </div>
                        </div>
                    </form>
                    {{-- fin seccion de buscador --}}

                    {{-- mensaje de resultados de busqueda --}}
                    @if (!empty($search))
                        <div class="alert alert-success py-2 mb-3" role="alert">
                            Se encontraron {{ $formaFarmaceuticas->total() }} resultado(s) para "{{ $search }}".
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
                                @forelse ($formaFarmaceuticas as $formaFarmaceutica)
                                    <tr>
                                        <td>{{ $formaFarmaceuticas->firstItem() + $loop->index }}</td>
                                        <td>{{ $formaFarmaceutica->nombre }}</td>
                                        <td>
                                            {{-- Botón Editar --}}
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editFormaFarmaceuticaModal-{{ $formaFarmaceutica->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Botón Eliminar --}}
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFormaFarmaceuticaModal-{{ $formaFarmaceutica->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ========================================== --}}
                                    {{-- MODAL EDITAR FORMA FARMACÉUTICA --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="editFormaFarmaceuticaModal-{{ $formaFarmaceutica->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" style="color:white">Editar forma farmacéutica</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.forma_farmaceuticas.update', $formaFarmaceutica->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nombre-{{ $formaFarmaceutica->id }}">Nombre (*)</label>
                                                            <div class="input-group">
                                                                {{-- Icono de píldora/cápsula --}}
                                                                <span class="input-group-text"><i class="bi bi-capsule"></i></span>
                                                                <input type="text" name="nombre" id="nombre-{{ $formaFarmaceutica->id }}" class="form-control" 
                                                                    value="{{ session('open_modal') === 'editFormaFarmaceuticaModal-' . $formaFarmaceutica->id ? old('nombre', $formaFarmaceutica->nombre) : $formaFarmaceutica->nombre }}">
                                                            </div>
                                                            @if (session('open_modal') === 'editFormaFarmaceuticaModal-' . $formaFarmaceutica->id)
                                                                @error('nombre')
                                                                    <div class="mt-1">
                                                                        <small class="text-danger">{{ $message }}</small>
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
                                    {{-- MODAL ELIMINAR FORMA FARMACÉUTICA --}}
                                    {{-- ========================================== --}}
                                    <div class="modal fade" id="deleteFormaFarmaceuticaModal-{{ $formaFarmaceutica->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" style="color:white">Eliminar forma farmacéutica</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.forma_farmaceuticas.destroy', $formaFarmaceutica->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>¿Estás seguro de eliminar la forma farmacéutica <strong>{{ $formaFarmaceutica->nombre }}</strong>?</p>
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
                                            <p class="text-muted mb-0">No hay formas farmacéuticas registradas.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- PAGINACIÓN --}}
                    @if ($formaFarmaceuticas->count() > 0)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4">
                            <small class="text-muted mb-2 mb-md-0">
                                Mostrando {{ $formaFarmaceuticas->firstItem() }} a {{ $formaFarmaceuticas->lastItem() }} de {{ $formaFarmaceuticas->total() }} registros
                            </small>
                            <div>
                                {{ $formaFarmaceuticas->links('vendor.pagination.bootstrap-5-no-summary') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================== --}}
{{-- MODAL CREAR FORMA FARMACÉUTICA            --}}
{{-- ========================================== --}}
<div class="modal fade" id="createFormaFarmaceuticaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" style="color:white">Crear forma farmacéutica</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.forma_farmaceuticas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre-create">Nombre (*)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-capsule"></i></span>
                            <input type="text" name="nombre" id="nombre-create" class="form-control" value="{{ old('nombre') }}" placeholder="Nombre de la forma farmacéutica">
                        </div>
                        @if (session('open_modal') === 'createFormaFarmaceuticaModal')
                            @error('nombre')
                                <div class="mt-1">
                                    <small class="text-danger">{{ $message }}</small>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Abrir modal de crear si hay errores
        @if(session('open_modal') === 'createFormaFarmaceuticaModal')
            var createModalElement = document.getElementById('createFormaFarmaceuticaModal');
            if (createModalElement) {
                bootstrap.Modal.getOrCreateInstance(createModalElement).show();
            }
        @endif

        // Abrir modal de editar si hay errores
        @if(session('open_modal') && str_contains(session('open_modal'), 'editFormaFarmaceuticaModal-'))
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