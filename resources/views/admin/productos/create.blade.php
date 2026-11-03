@extends('layouts.admin')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Productos</h3>
    <a href="{{ route('admin.productos.index') }}" class="btn btn-light-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Crear producto</h4>
                </div>
                <div class="card-body">
                    {{-- Alerta general de errores --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3" role="alert">
                            <strong>Se encontraron errores en el formulario.</strong>
                        </div>
                    @endif

                    {{-- multipart/form-data para que soporte la subida de archivos --}}
                    <form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            {{-- Categoría --}}
                            <div class="col-12 col-md-4">
                                <label for="categoria_id" class="form-label">Categoría (*)</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-tags-fill"></i>
                                    </span>
                                    <select name="categoria_id" id="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror" required>
                                        <option value="">Selecciona una categoría</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('categoria_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Laboratorio --}}
                            <div class="col-12 col-md-4">
                                <label for="laboratorio_id" class="form-label">Laboratorio</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-flask"></i>
                                    </span>
                                    <select name="laboratorio_id" id="laboratorio_id" class="form-select @error('laboratorio_id') is-invalid @enderror">
                                        <option value="">Selecciona un laboratorio</option>
                                        @foreach ($laboratorios as $laboratorio)
                                            <option value="{{ $laboratorio->id }}" {{ old('laboratorio_id') == $laboratorio->id ? 'selected' : '' }}>
                                                {{ $laboratorio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('laboratorio_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Forma Farmacéutica --}}
                            <div class="col-12 col-md-4">
                                <label for="forma_farmaceutica_id" class="form-label">Forma farmacéutica</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-capsule"></i>
                                    </span>
                                    <select name="forma_farmaceutica_id" id="forma_farmaceutica_id" class="form-select @error('forma_farmaceutica_id') is-invalid @enderror">
                                        <option value="">Selecciona una forma</option>
                                        @foreach ($formaFarmaceuticas as $forma)
                                            <option value="{{ $forma->id }}" {{ old('forma_farmaceutica_id') == $forma->id ? 'selected' : '' }}>
                                                {{ $forma->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('forma_farmaceutica_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Presentación --}}
                            <div class="col-12 col-md-4">
                                <label for="presentacion_id" class="form-label">Presentación</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-box"></i>
                                    </span>
                                    <select name="presentacion_id" id="presentacion_id" class="form-select @error('presentacion_id') is-invalid @enderror">
                                        <option value="">Selecciona una presentación</option>
                                        @foreach ($presentaciones as $presentacion)
                                            <option value="{{ $presentacion->id }}" {{ old('presentacion_id') == $presentacion->id ? 'selected' : '' }}>
                                                {{ $presentacion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('presentacion_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Código de producto --}}
                            <div class="col-12 col-md-4">
                                <label for="codigo_producto" class="form-label">Código de producto (*)</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-upc-scan"></i>
                                    </span>
                                    <input type="text" name="codigo_producto" id="codigo_producto" class="form-control @error('codigo_producto') is-invalid @enderror" value="{{ old('codigo_producto') }}" placeholder="Ej: PRO-01" required>
                                </div>
                                @error('codigo_producto')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Código de barra --}}
                            <div class="col-12 col-md-4">
                                <label for="codigo_barra" class="form-label">Código de barra</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-upc"></i>
                                    </span>
                                    <input type="text" name="codigo_barra" id="codigo_barra" class="form-control @error('codigo_barra') is-invalid @enderror" value="{{ old('codigo_barra') }}" placeholder="Ej: 777878454">
                                </div>
                                @error('codigo_barra')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Nombre comercial --}}
                            <div class="col-12 col-md-6">
                                <label for="nombre_comercial" class="form-label">Nombre comercial (*)</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-briefcase"></i>
                                    </span>
                                    <input type="text" name="nombre_comercial" id="nombre_comercial" class="form-control @error('nombre_comercial') is-invalid @enderror" value="{{ old('nombre_comercial') }}" placeholder="Nombre comercial del producto" required>
                                </div>
                                @error('nombre_comercial')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Nombre genérico --}}
                            <div class="col-12 col-md-6">
                                <label for="nombre_generico" class="form-label">Nombre genérico (*)</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-capsule"></i>
                                    </span>
                                    <input type="text" name="nombre_generico" id="nombre_generico" class="form-control @error('nombre_generico') is-invalid @enderror" value="{{ old('nombre_generico') }}" placeholder="Nombre genérico del producto" required>
                                </div>
                                @error('nombre_generico')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Concentración --}}
                            <div class="col-12 col-md-4">
                                <label for="concentracion" class="form-label">Concentración</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-eyedropper"></i>
                                    </span>
                                    <input type="text" name="concentracion" id="concentracion" class="form-control @error('concentracion') is-invalid @enderror" value="{{ old('concentracion') }}" placeholder="Ej: 100 MG">
                                </div>
                                @error('concentracion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Unidad de medida --}}
                            <div class="col-12 col-md-4">
                                <label for="unidad_medida" class="form-label">Unidad de medida</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-rulers"></i>
                                    </span>
                                    <select name="unidad_medida" id="unidad_medida" class="form-select @error('unidad_medida') is-invalid @enderror">
                                        <option value="">Selecciona una unidad</option>
                                        <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>kg - kilogramo</option>
                                        <option value="g" {{ old('unidad_medida') == 'g' ? 'selected' : '' }}>g - gramo</option>
                                        <option value="mg" {{ old('unidad_medida') == 'mg' ? 'selected' : '' }}>mg - miligramo</option>
                                        <option value="mcg" {{ old('unidad_medida') == 'mcg' ? 'selected' : '' }}>mcg - microgramo</option>
                                        <option value="l" {{ old('unidad_medida') == 'l' ? 'selected' : '' }}>l - litro</option>
                                        <option value="ml" {{ old('unidad_medida') == 'ml' ? 'selected' : '' }}>ml - mililitro</option>
                                        <option value="mmol" {{ old('unidad_medida') == 'mmol' ? 'selected' : '' }}>mmol - milimol</option>
                                        <option value="mEq" {{ old('unidad_medida') == 'mEq' ? 'selected' : '' }}>mEq - miliequivalente</option>
                                        <option value="UI" {{ old('unidad_medida') == 'UI' ? 'selected' : '' }}>UI - unidad internacional</option>
                                        <option value="%" {{ old('unidad_medida') == '%' ? 'selected' : '' }}>% - porcentaje</option>
                                    </select>
                                </div>
                                @error('unidad_medida')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Imagen --}}
                            <div class="col-12 col-md-4">
                                <label for="imagen" class="form-label">Imagen</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-image"></i>
                                    </span>
                                    <input type="file" name="imagen" id="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
                                </div>
                                @error('imagen')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Acción terapéutica --}}
                            <div class="col-12 col-md-6">
                                <label for="accion_terapeutica" class="form-label">Acción terapéutica</label>
                                <div class="input-group">
                                    <span class="input-group-text d-flex justify-content-center align-items-center" style="width: 46px;">
                                        <i class="bi bi-heart-pulse"></i>
                                    </span>
                                    <input type="text" name="accion_terapeutica" id="accion_terapeutica" class="form-control @error('accion_terapeutica') is-invalid @enderror" value="{{ old('accion_terapeutica') }}" placeholder="Ej: Analgésico, antiinflamatorio, antibiótico">
                                </div>
                                @error('accion_terapeutica')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Previsualización de Imagen --}}
                            <div class="col-12 col-md-6">
                                <div class="border rounded d-flex justify-content-center align-items-center" style="height: 100%; min-height: 120px; background-color: #e9ecef;">
                                    {{-- Contenedor de la imagen (Imagen 5) --}}
                                    <img id="image-preview" src="" alt="Sin previsualización" class="img-fluid rounded" style="max-height: 120px; display: none;">
                                    <span id="preview-text" class="text-muted">Sin previsualización</span>
                                </div>
                            </div>

                            {{-- Requiere receta --}}
                            <div class="col-12 mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="usa_receta" id="usa_receta" value="1" {{ old('usa_receta') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="usa_receta">
                                        ¿Requiere receta?
                                    </label>
                                </div>
                                @error('usa_receta')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Botones --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.productos.index') }}" class="btn btn-light-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function previewImage(event) {
        var reader = new FileReader();
        var output = document.getElementById('image-preview');
        var text = document.getElementById('preview-text');

        reader.onload = function(){
            output.src = reader.result;
            output.style.display = 'block';
            text.style.display = 'none';
        };
        
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
        } else {
            output.src = '';
            output.style.display = 'none';
            text.style.display = 'block';
        }
    }
</script>
@endpush
@endsection