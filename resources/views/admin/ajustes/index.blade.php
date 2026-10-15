@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <h3>Ajustes del sistema</h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Configuracion general</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.ajustes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Columna izquierda: Campos del formulario --}}
                    <div class="col-lg-8 col-12">
                        <div class="row">
                            {{-- Nombre --}}
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                                        <input id="nombre" type="text" class="form-control"
                                               value="{{ old('nombre', $configuracion->nombre ?? '') }}" name="nombre"
                                               placeholder="Escriba aquí..." required>
                                    </div>
                                    @error('nombre')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email">Email</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input id="email" type="email" class="form-control"
                                               value="{{ old('email', $configuracion->email ?? '') }}" name="email"
                                               placeholder="correo@dominio.com" required>
                                    </div>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Teléfono --}}
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input id="telefono" type="text" class="form-control"
                                               value="{{ old('telefono', $configuracion->telefono ?? '') }}" name="telefono"
                                               placeholder="Escriba aquí..." required>
                                    </div>
                                    @error('telefono')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- DIVISA (Campo clave que solicitas) --}}
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="divisa">Divisa</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                                        <select id="divisa" class="form-select" name="divisa" required>
                                            <option value="">Seleccione una divisa</option>
                                            @foreach ($divisas ?? [] as $codigo => $divisa)
                                                <option value="{{ $codigo }}"
                                                    {{ old('divisa', $configuracion->divisa ?? '') == $codigo ? 'selected' : '' }}>
                                                    {{ $codigo }} - {{ $divisa['name'] ?? $codigo }}
                                                    @if (!empty($divisa['symbol']))
                                                        ({{ $divisa['symbol'] }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('divisa')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Dirección --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="direccion">Dirección</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input id="direccion" type="text" class="form-control"
                                               value="{{ old('direccion', $configuracion->direccion ?? '') }}" name="direccion"
                                               placeholder="Escriba aquí..." required>
                                    </div>
                                    @error('direccion')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Descripción --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea id="descripcion" class="form-control" name="descripcion" rows="3"
                                              placeholder="Descripción opcional">{{ old('descripcion', $configuracion->descripcion ?? '') }}</textarea>
                                    @error('descripcion')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Sitio web --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="web">Sitio web</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-globe"></i></span>
                                        <input id="web" type="url" class="form-control"
                                               value="{{ old('web', $configuracion->web ?? '') }}" name="web"
                                               placeholder="https://tu-sitio.com">
                                    </div>
                                    @error('web')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Columna derecha: Carga de Logo --}}
                    <div class="col-lg-4 col-12">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                                                    <!--condicionar si el ajuste existe o tiene logo para no poner el asterisco de obligatorio-->
                                            <label for="logo" class="form-label">Imagen de Login @if( !isset($configuracion) || !isset($configuracion->logo) ) (*) @endif</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                                {{-- @error('descripcion') is-invalid @enderror esto si hay error en la validcion, laravel mandara el error de inmediato --}}
                                                <!--onchange="mostrarImagen(event)" para mostrar la imagen seleccionada-->
                                                <input type="file" name="logo" id="logo" onchange="mostrarImagen(event)" class="form-control" accept="image/*" @if( !isset($configuracion) || !isset($configuracion->logo) ) required @endif>
                                                @error('logo')
                                                    {{-- Error de validacion del campo por el name="pagina_web" si algo pasa --}}
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong></div>
                                                @enderror
                                            </div>
                                        </div>
                                        <center><!--Cargar la imagen actual si existe-->
                                            @if($configuracion && $configuracion->logo)
                                                <img src="{{ asset('storage/' . $configuracion->logo) }}" id="preview1" style="max-width: 300px; margin-top: 15px;" alt="Logo actual">

                                            @else
                                                <img src="" id="preview1" style="max-width: 300px; margin-top: 15px;" alt=""> <!-- imagen de previsualizacion del logo-->
                                            @endif
                                        </center>
                                        <!--Script para mostrar la imagen seleccionada en el input file-->
                                        <script>
                                            const mostrarImagen = e => document.getElementById('preview1').src = URL.createObjectURL(e.target.files[0]);
                                        </script>
                                    </div>
                                </div>
                        </div>
                </div>

                {{-- Botón de guardar --}}
                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar ajustes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
