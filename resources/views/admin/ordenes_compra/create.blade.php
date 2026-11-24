@extends('layouts.admin')

@section('content')
{{-- uso de cstf tokan para el envio de valores de ajax al backend --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>📝 Orden de Compra - Carrito Temporal</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Orden de Compra</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <!-- FORMULARIO DE CARRITO -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">🛒 Agregar Productos a la Orden</h4>
                    </div>
                    <div class="card-body">
                        <form id="formCarrito" method="POST" action="">
                            @csrf
                            <div class="row">
                                <!-- SUCURSAL -->
                                <div class="col-md-6 mb-3">
                                    <label for="sucursal_id" class="form-label">Sucursal <span
                                            class="text-danger">*</span></label>
                                    <select id="sucursal_id" name="sucursal_id"
                                        class="form-select form-select-lg @error('sucursal_id') is-invalid @enderror"
                                        required>
                                        {{-- formulario para requerir de la sucursal --}}
                                        <option value="">-- Selecciona una Sucursal --</option>
                                        @foreach ($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}"
                                                {{ old('sucursal_id') == $sucursal->id ? 'selected' : '' }}>
                                                {{ $sucursal->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sucursal_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- PROVEEDOR -->
                                <div class="col-md-6 mb-3">
                                    <label for="proveedor_id" class="form-label">Proveedor <span
                                            class="text-danger">*</span></label>
                                    <select id="proveedor_id" name="proveedor_id"
                                        class="form-select form-select-lg @error('proveedor_id') is-invalid @enderror"
                                        required>
                                        <option value="">-- Selecciona un Proveedor --</option>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}"
                                                {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                                {{ $proveedor->nombre }} - {{ $proveedor->empresa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('proveedor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <!-- BÚSQUEDA DE PRODUCTOS -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Seleccionar Producto <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="buscarProducto" class="form-control form-control-lg"
                                            placeholder="Busca por código, nombre, laboratorio, categoría, forma, presentación...">
                                        <button class="btn btn-outline-secondary" type="button" id="btnBuscar">
                                            <i class="bi bi-search"></i> Buscar
                                        </button>
                                    </div>
                                </div>

                                <!-- TABLA DE PRODUCTOS -->
                                <div class="col-12 mb-3">
                                    <div class="table-responsive" style="min-height: auto;">
                                        <table id="tablaProductos" class="table table-hover table-sm mb-0">
                                            <thead class="table-light" style="position: sticky; top: 0;">
                                                <tr>
                                                    <th style="width: 5%;">#</th>
                                                    <th style="width: 10%;">Código</th>
                                                    <th style="width: 10%;">Código Barra</th>
                                                    <th style="width: 12%;">Nombre Comercial</th>
                                                    <th style="width: 12%;">Nombre Genérico</th>
                                                    <th style="width: 10%;">Concentración</th>
                                                    <th style="width: 10%;">Laboratorio</th>
                                                    <th style="width: 10%;">Categoría</th>
                                                    <th style="width: 10%;">Forma Farmacéutica</th>
                                                    <th style="width: 8%;">Presentación</th>
                                                    <th style="width: 7%;">Acción Terapéutica</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productosBody">
                                                {{-- foreach para el listado de los productos con JS y blade de php --}}
                                                @foreach ($productos as $producto)
                                                    <tr class="producto-row" data-id="{{ $producto->id }}"
                                                        data-codigo="{{ $producto->codigo_producto }}"
                                                        data-codigo-barra="{{ $producto->codigo_barra ?? '' }}"
                                                        data-nombre-comercial="{{ $producto->nombre_comercial }}"
                                                        data-nombre-generico="{{ $producto->nombre_generico }}"
                                                        data-laboratorio="{{ $producto->laboratorio ? $producto->laboratorio->nombre : 'N/A' }}"
                                                        data-categoria="{{ $producto->categoria ? $producto->categoria->nombre : 'N/A' }}"
                                                        data-forma-farmaceutica="{{ $producto->formaFarmaceutica ? $producto->formaFarmaceutica->nombre : 'N/A' }}"
                                                        data-presentacion="{{ $producto->presentacion ? $producto->presentacion->nombre : 'N/A' }}"
                                                        data-concentracion="{{ $producto->concentracion ?? '' }}"
                                                        data-unidad-medida="{{ $producto->unidad_medida ?? '' }}"
                                                        data-accion-terapeutica="{{ $producto->accion_terapeutica ?? '' }}">
                                                        <td><small class="text-muted">{{ $loop->iteration }}</small></td>
                                                        <td><small
                                                                class="badge bg-info">{{ $producto->codigo_producto }}</small>
                                                        </td>
                                                        <td><small>{{ $producto->codigo_barra ?? '-' }}</small></td>
                                                        <td><small><strong>{{ $producto->nombre_comercial }}</strong></small>
                                                        </td>
                                                        <td><small
                                                                class="text-muted">{{ $producto->nombre_generico }}</small>
                                                        </td>
                                                        <td><small>{{ $producto->concentracion ?? '-' }}{{ $producto->unidad_medida ? ' ' . $producto->unidad_medida : '' }}</small>
                                                        </td>
                                                        <td><small>{{ $producto->laboratorio ? $producto->laboratorio->nombre : '-' }}</small>
                                                        </td>
                                                        <td><small>{{ $producto->categoria ? $producto->categoria->nombre : '-' }}</small>
                                                        </td>
                                                        <td><small>{{ $producto->formaFarmaceutica ? $producto->formaFarmaceutica->nombre : '-' }}</small>
                                                        </td>
                                                        <td><small>{{ $producto->presentacion ? $producto->presentacion->nombre : '-' }}</small>
                                                        </td>
                                                        <td><small>{{ $producto->accion_terapeutica ?? '-' }}</small></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- CONTROLES DE PAGINACIÓN -->
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">Mostrando <span id="infoProductos">0</span>
                                            productos</small>
                                        <nav aria-label="Paginación de productos">
                                            <ul class="pagination pagination-sm mb-0" id="paginacionProductos"></ul>
                                        </nav>
                                    </div>
                                </div>

                                <!-- CAMPOS OCULTOS que se enviaran al backend hacia la Base de datos en tiempo real con el ajax -->
                                <input type="hidden" id="producto_id" name="producto_id" value="">
                                <input type="hidden" id="cantidad" value="1">
                                <input type="hidden" id="precio_compra" value="0">
                                <input type="hidden" id="precio_venta" value="0">
                                <input type="hidden" id="porcentaje_ganancia" value="0">
                                <input type="hidden" id="observaciones" value="">
                            </div>

                            <!-- ESTADO OCULTO -->
                            <input type="hidden" id="estado" name="estado" value="activo">

                            <hr>

                            <!-- TABLA DE CARRITO -->
                            <div class="table-responsive">
                                <table id="tablaCarrito" class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">P. Compra</th>
                                            <th class="text-center">P. Venta</th>
                                            <th class="text-center">% Ganancia</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="carritoBody">
                                        <tr id="filaVacia" class="text-center">
                                            <td colspan="8" class="py-4 text-muted">
                                                <i class="bi bi-inbox"></i> Carrito vacío. Agrega productos para comenzar.
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light" id="tablaFooter" style="display: none;">
                                        <tr style="border-top: 2px solid #dee2e6;">
                                            <td colspan="1"></td>
                                            <td><strong>Totales:</strong></td>
                                            <td class="text-center"><strong><span id="totalCantidad">0</span></strong>
                                            </td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-end"><strong>{{ $ajuste->divisa ?? 'Bs.' }} <span id="totalCompra">0.00</span></strong>
                                            </td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- BOTONES FINALES -->
                            <div class="row mt-4">
                                <div class="col-12 d-flex gap-2 justify-content-end">
                                    <button type="button" class="btn btn-secondary btn-lg" onclick="limpiarCarrito()">
                                        <i class="bi bi-trash"></i> Limpiar
                                    </button>
                                    <button type="submit" id="btnConfirmar" class="btn btn-primary btn-lg" disabled>
                                        <i class="bi bi-check-circle"></i> Confirmar Orden
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Estilos de la tabla de ordenes --}}
@push('styles')
    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .form-control-lg,
        .form-select-lg {
            font-size: 1rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        #porcentaje_ganancia {
            font-weight: bold;
            color: #28a745;
        }

        .text-warning-custom {
            color: #ff9800;
        }

        .text-success-custom {
            color: #28a745;
        }

        /* Estilos para tabla de productos */
        #tablaProductos {
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        #tablaProductos thead {
            background-color: #e9ecef;
        }

        #tablaProductos thead th {
            padding: 0.35rem 0.5rem;
            font-weight: 600;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        #tablaProductos tbody td {
            padding: 0.25rem 0.5rem;
            vertical-align: middle;
        }

        #tablaProductos tbody tr {
            height: auto;
        }

        .producto-row {
            cursor: pointer;
            transition: background-color 0.2s ease;
            user-select: none;
        }

        #tablaProductos .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.4rem;
        }

        .producto-row:hover {
            background-color: #e8f4f8 !important;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .producto-row.table-active {
            background-color: #cfe2ff !important;
            font-weight: 500;
        }

        .producto-row td small {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.8rem;
            line-height: 1.2;
        }

        #buscarProducto {
            border-radius: 0.375rem 0 0 0.375rem;
        }

        #btnBuscar {
            border-radius: 0 0.375rem 0.375rem 0;
        }

        /* Estilos para inputs de carrito */
        .cantidad-input {
            transition: background-color 0.2s ease, border-color 0.2s ease;
            text-align: right !important;
        }

        .cantidad-input:focus {
            background-color: #fff3cd !important;
            border-color: #ffc107 !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // ===== VARIABLES GLOBALES =====
        let carrito = [];
        const MONEDA = "{{ $ajuste->divisa ?? 'Bs.' }}"; // toma la moneda del valor en php
        let contadorLinea = 0;
        let paginaActual = 1;
        let itemsPorPagina = 10;
        // arreglo de la tabla para el guardado de los productos
        let productosOriginales = [];
        let productosVisibles = [];
        let updateItemTimers = {};
        // agregacion de nuevos productos mediante instanciacion
        let addingProductIds = new Set();

        // ===== INICIALIZACIÓN =====
        document.addEventListener('DOMContentLoaded', function() {
            // Guardar productos originales
            productosOriginales = Array.from(document.querySelectorAll('.producto-row'));
            productosVisibles = [...productosOriginales];

            // Inicializar paginación
            inicializarPaginacion();
            mostrarPagina(1);

            // Event listeners para búsqueda de productos
            document.getElementById('buscarProducto').addEventListener('input', filtrarProductos);
            document.getElementById('btnBuscar').addEventListener('click', function() {
                filtrarProductos();
            });

            // Un solo listener para evitar duplicados por re-render/paginación
            document.getElementById('productosBody').addEventListener('click', function(e) {
                const fila = e.target.closest('.producto-row');
                if (!fila || fila.style.display === 'none') return;

                const productoId = fila.getAttribute('data-id');
                seleccionarProducto(productoId, fila);
            });

            // Carrito vacío al iniciar (fuente de datos: BD solo)
            carrito = [];
            renderizarCarrito();
        });

        // ===== PAGINACIÓN =====
        function inicializarPaginacion() {
            const totalPaginas = Math.ceil(productosVisibles.length / itemsPorPagina);
            const paginacionContainer = document.getElementById('paginacionProductos');
            paginacionContainer.innerHTML = '';

            // Botón anterior
            const btnAnterior = document.createElement('li');
            btnAnterior.className = 'page-item';
            btnAnterior.innerHTML = '<a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>';
            btnAnterior.addEventListener('click', function(e) {
                e.preventDefault();
                if (paginaActual > 1) {
                    mostrarPagina(paginaActual - 1);
                }
            });
            paginacionContainer.appendChild(btnAnterior);

            //calculo de Números de página
            for (let i = 1; i <= totalPaginas; i++) {
                const btnPagina = document.createElement('li');
                btnPagina.className = 'page-item' + (i === paginaActual ? ' active' : '');
                btnPagina.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                btnPagina.addEventListener('click', function(e) {
                    e.preventDefault();
                    mostrarPagina(i);
                });
                paginacionContainer.appendChild(btnPagina);
            }

            // Botón siguiente
            const btnSiguiente = document.createElement('li');
            btnSiguiente.className = 'page-item';
            btnSiguiente.innerHTML = '<a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>';
            btnSiguiente.addEventListener('click', function(e) {
                e.preventDefault();
                if (paginaActual < totalPaginas) {
                    mostrarPagina(paginaActual + 1);
                }
            });
            paginacionContainer.appendChild(btnSiguiente);
        }

        function mostrarPagina(numeroPagina) {
            paginaActual = numeroPagina;
            const inicio = (numeroPagina - 1) * itemsPorPagina;
            const fin = inicio + itemsPorPagina;

            // Ocultar todas las filas
            productosOriginales.forEach(fila => {
                fila.style.display = 'none';
            });

            // Mostrar filas de la página actual
            productosVisibles.slice(inicio, fin).forEach(fila => {
                fila.style.display = '';
            });

            // Actualizar controles de paginación
            inicializarPaginacion();

            // Actualizar información
            const totalVisibles = productosVisibles.length;
            const mostrandoDesde = inicio + 1;
            const mostrandoHasta = Math.min(fin, totalVisibles);
            document.getElementById('infoProductos').textContent =
                `${mostrandoDesde}-${mostrandoHasta} de ${totalVisibles}`;
        }

        // ===== CALCULAR % GANANCIA =====
        function calcularGanancia() {
            const precioCompra = parseFloat(document.getElementById('precio_compra').value) || 0;
            const precioVenta = parseFloat(document.getElementById('precio_venta').value) || 0;

            let ganancia = 0;
            if (precioCompra > 0) {
                ganancia = ((precioVenta - precioCompra) / precioCompra) * 100;
            }

            document.getElementById('porcentaje_ganancia').value = ganancia.toFixed(2);
        }

        // ===== FILTRAR PRODUCTOS =====
        function filtrarProductos() {
            const busqueda = document.getElementById('buscarProducto').value.toLowerCase();

            if (busqueda === '') {
                productosVisibles = [...productosOriginales];
            } else {
                productosVisibles = productosOriginales.filter(fila => {
                    const codigo = fila.getAttribute('data-codigo').toLowerCase();
                    const codigoBarraText = fila.getAttribute('data-codigo-barra').toLowerCase();
                    const nombreComercial = fila.getAttribute('data-nombre-comercial').toLowerCase();
                    const nombreGenerico = fila.getAttribute('data-nombre-generico').toLowerCase();
                    const laboratorio = fila.getAttribute('data-laboratorio').toLowerCase();
                    const categoria = fila.getAttribute('data-categoria').toLowerCase();
                    const formaFarmaceutica = fila.getAttribute('data-forma-farmaceutica').toLowerCase();
                    const presentacion = fila.getAttribute('data-presentacion').toLowerCase();
                    const concentracion = fila.getAttribute('data-concentracion').toLowerCase();
                    const unidadMedida = fila.getAttribute('data-unidad-medida').toLowerCase();
                    const accionTerapeutica = fila.getAttribute('data-accion-terapeutica').toLowerCase();

                    return codigo.includes(busqueda) || codigoBarraText.includes(busqueda) ||
                        nombreComercial.includes(busqueda) || nombreGenerico.includes(busqueda) ||
                        laboratorio.includes(busqueda) || categoria.includes(busqueda) ||
                        formaFarmaceutica.includes(busqueda) || presentacion.includes(busqueda) ||
                        concentracion.includes(busqueda) || unidadMedida.includes(busqueda) ||
                        accionTerapeutica.includes(busqueda);
                });
            }

            paginaActual = 1;
            mostrarPagina(1);
        }

        // ===== SELECCIONAR PRODUCTO =====
        function enfocarCantidadItem(itemId) {
            const filaItem = document.querySelector(`tr[data-item-id="${itemId}"]`);
            if (!filaItem) return;

            filaItem.classList.add('table-active');
            setTimeout(() => filaItem.classList.remove('table-active'), 800);

            const cantidadInput = filaItem.querySelector('.cantidad-input');
            if (cantidadInput) {
                cantidadInput.focus();
                cantidadInput.select();
            }
        }

        function seleccionarProducto(productoId, fila) {
            const nombreComercial = fila.getAttribute('data-nombre-comercial');
            const nombreGenerico = fila.getAttribute('data-nombre-generico');
            const codigo = fila.getAttribute('data-codigo');
            const sucursalId = document.getElementById('sucursal_id').value;

            // Evita registrar el mismo producto varias veces por doble clic/latencia
            if (addingProductIds.has(productoId)) {
                return;
            }

            // Validar que sucursal esté seleccionada
            if (!sucursalId) {
                Swal.fire('Atención', 'Por favor selecciona una sucursal primero', 'warning');
                return;
            }

            // Si ya existe en carrito, no crear otra fila; enfocar para editar cantidad
            const itemExistente = carrito.find(item =>
                String(item.producto_id) === String(productoId) &&
                String(item.sucursal_id) === String(sucursalId)
            );

            if (itemExistente) {
                enfocarCantidadItem(itemExistente.id);
                return;
            }

            addingProductIds.add(productoId);

            // Marcar fila como seleccionada visualmente
            document.querySelectorAll('.producto-row').forEach(f => f.classList.remove('table-active'));
            fila.classList.add('table-active');

            // Crear nombre completo del producto
            const nombreProducto = `${nombreComercial} (${nombreGenerico}) - Código: ${codigo}`;

            // Guardar en la base de datos
            fetch('{{ route('admin.ordenes_compra.addItems') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        sucursal_id: sucursalId,
                        producto_id: productoId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Crear objeto del carrito con el id retornado de la BD
                        const item = {
                            id: data.item_id,
                            sucursal_id: sucursalId,
                            proveedor_id: document.getElementById('proveedor_id').value,
                            producto_id: productoId,
                            producto_nombre: nombreProducto,
                            cantidad: 0,
                            precio_compra: 0,
                            precio_venta: 0,
                            porcentaje_ganancia: 0,
                            observaciones: '',
                            subtotal: 0
                        };

                        // Agregar al carrito local
                        carrito.push(item);
                        renderizarCarrito(true);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo agregar el producto', 'error');
                })
                .finally(() => {
                    addingProductIds.delete(productoId);
                });
        }

        // ===== CALCULAR GANANCIA DE UN ITEM =====
        function calcularGananciaItem(precio_compra, precio_venta) {
            if (precio_compra <= 0) return 0;
            return ((precio_venta - precio_compra) / precio_compra) * 100;
        }

        function calcularPrecioVentaDesdeGanancia(precio_compra, porcentaje_ganancia) {
            if (precio_compra <= 0) return 0;
            return precio_compra * (1 + (porcentaje_ganancia / 100));
        }

        // ===== AGREGAR AL CARRITO (No se usa, pero se mantiene por compatibilidad) =====
        function agregarAlCarrito() {
            return false;
        }

        function enfocarBuscadorProducto() {
            const buscarProductoInput = document.getElementById('buscarProducto');
            if (buscarProductoInput) {
                setTimeout(() => {
                    buscarProductoInput.focus();
                    buscarProductoInput.select();
                }, 0);
            }
        }

        // ===== RENDERIZAR CARRITO =====
        function renderizarCarrito(autoFocusCantidad = false) {
            const carritoBody = document.getElementById('carritoBody');
            const filaVacia = document.getElementById('filaVacia');
            const btnConfirmar = document.getElementById('btnConfirmar');
            const tablaFooter = document.getElementById('tablaFooter');

            if (carrito.length === 0) {
                carritoBody.innerHTML =
                    '<tr id="filaVacia" class="text-center"><td colspan="8" class="py-4 text-muted"><i class="bi bi-inbox"></i> Carrito vacío. Agrega productos para comenzar.</td></tr>';
                btnConfirmar.disabled = true;
                tablaFooter.style.display = 'none';
                return;
            }

            carritoBody.innerHTML = '';
            tablaFooter.style.display = '';
            carrito.forEach((item, index) => {
                const ganancia = calcularGananciaItem(item.precio_compra, item.precio_venta);
                const subtotal = item.cantidad * item.precio_compra;
                const clasGanancia = ganancia >= 30 ? 'text-success-custom' : 'text-warning-custom';

                const fila = document.createElement('tr');
                fila.setAttribute('data-item-id', item.id);
                fila.innerHTML = `
                <td><small class="text-muted">${index + 1}</small></td>
                <td><small>${item.producto_nombre}</small></td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm cantidad-input" value="${item.cantidad === 0 ? '' : item.cantidad}" min="0" style="width: 100px; margin: 0 auto; display: block;">
                </td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm precio-compra-input" value="${item.precio_compra > 0 ? item.precio_compra.toFixed(2) : ''}" placeholder="0.00" min="0" step="0.01" style="width: 90px; margin: 0 auto; display: block; text-align: center;">
                </td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm precio-venta-input" value="${item.precio_venta > 0 ? item.precio_venta.toFixed(2) : ''}" placeholder="0.00" min="0" step="0.01" style="width: 90px; margin: 0 auto; display: block; text-align: center;">
                </td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm ganancia-input ${clasGanancia}" value="${ganancia > 0 ? ganancia.toFixed(2) : ''}" placeholder="0.00" min="0" step="0.01" style="width: 100px; margin: 0 auto; display: block; text-align: center;">
                </td>
                <td class="text-end"><strong class="subtotal-valor">${MONEDA} ${subtotal.toFixed(2)}</strong></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="eliminarDelCarrito(${item.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;

                // Agregar event listeners a los inputs
                const cantidadInput = fila.querySelector('.cantidad-input');
                const precioCompraInput = fila.querySelector('.precio-compra-input');
                const precioVentaInput = fila.querySelector('.precio-venta-input');
                const gananciaInput = fila.querySelector('.ganancia-input');

                cantidadInput.addEventListener('input', function() {
                    actualizarItemCarrito(item.id, {
                        cantidad: parseInt(this.value) || 0
                    });
                });

                cantidadInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || (e.key === 'Tab' && !e.shiftKey)) {
                        e.preventDefault();
                        precioCompraInput.focus();
                    }
                });

                precioCompraInput.addEventListener('input', function() {
                    actualizarItemCarrito(item.id, {
                        precio_compra: parseFloat(this.value) || 0
                    });
                });

                precioCompraInput.addEventListener('focus', function() {
                    this.select();
                });

                precioCompraInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || (e.key === 'Tab' && !e.shiftKey)) {
                        e.preventDefault();
                        precioVentaInput.focus();
                    } else if (e.key === 'Tab' && e.shiftKey) {
                        e.preventDefault();
                        cantidadInput.focus();
                    }
                });

                precioVentaInput.addEventListener('input', function() {
                    actualizarItemCarrito(item.id, {
                        precio_venta: parseFloat(this.value) || 0
                    });
                });

                precioVentaInput.addEventListener('focus', function() {
                    this.select();
                });

                precioVentaInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || (e.key === 'Tab' && !e.shiftKey)) {
                        e.preventDefault();
                        gananciaInput.focus();
                    } else if (e.key === 'Tab' && e.shiftKey) {
                        e.preventDefault();
                        precioCompraInput.focus();
                    }
                });

                gananciaInput.addEventListener('input', function() {
                    actualizarItemCarrito(item.id, {
                        porcentaje_ganancia: parseFloat(this.value) || 0
                    });
                });

                gananciaInput.addEventListener('focus', function() {
                    this.select();
                });

                gananciaInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || (e.key === 'Tab' && !e.shiftKey)) {
                        e.preventDefault();
                        e.stopPropagation();
                        enfocarBuscadorProducto();
                    } else if (e.key === 'Tab' && e.shiftKey) {
                        e.preventDefault();
                        precioVentaInput.focus();
                    }
                });

                carritoBody.appendChild(fila);
            });

            // Hacer foco en cantidad solo cuando se agrega un nuevo producto
            if (autoFocusCantidad) {
                const ultimoInput = carritoBody.querySelector('tr:last-child .cantidad-input');
                if (ultimoInput) {
                    setTimeout(() => ultimoInput.focus(), 100);
                }
            }

            btnConfirmar.disabled = false;
            actualizarResumen();
        }

        function actualizarVistaFila(itemId) {
            const item = carrito.find(i => i.id === itemId);
            if (!item) return;

            const fila = document.querySelector(`tr[data-item-id="${itemId}"]`);
            if (!fila) return;

            const ganancia = calcularGananciaItem(item.precio_compra, item.precio_venta);
            const subtotal = item.cantidad * item.precio_compra;
            const clasGanancia = ganancia >= 30 ? 'text-success-custom' : 'text-warning-custom';

            item.porcentaje_ganancia = ganancia;
            item.subtotal = subtotal;

            const precioVentaInput = fila.querySelector('.precio-venta-input');
            const gananciaInput = fila.querySelector('.ganancia-input');
            const subtotalEl = fila.querySelector('.subtotal-valor');

            if (precioVentaInput && document.activeElement !== precioVentaInput) {
                precioVentaInput.value = item.precio_venta > 0 ? item.precio_venta.toFixed(2) : '';
            }

            if (gananciaInput) {
                if (document.activeElement !== gananciaInput) {
                    gananciaInput.value = ganancia > 0 ? ganancia.toFixed(2) : '';
                }
                gananciaInput.classList.remove('text-success-custom', 'text-warning-custom');
                gananciaInput.classList.add(clasGanancia);
            }

            if (subtotalEl) {
                subtotalEl.textContent = `${MONEDA} ${subtotal.toFixed(2)}`;
            }
        }

        // ===== ACTUALIZAR ITEM DEL CARRITO =====
        function actualizarItemCarrito(itemId, cambios) {
            const item = carrito.find(i => i.id === itemId);
            if (!item) return;

            // Actualizar propiedades
            Object.assign(item, cambios);

            const cambioGanancia = Object.prototype.hasOwnProperty.call(cambios, 'porcentaje_ganancia');
            const cambioPrecioVenta = Object.prototype.hasOwnProperty.call(cambios, 'precio_venta');
            const cambioPrecioCompra = Object.prototype.hasOwnProperty.call(cambios, 'precio_compra');

            // Si se edita manualmente el % de ganancia, recalcular P. Venta
            if (cambioGanancia) {
                item.precio_venta = calcularPrecioVentaDesdeGanancia(item.precio_compra, item.porcentaje_ganancia);
            }

            // Si cambia P. Compra y no se está editando P. Venta, mantener el % actual y recalcular P. Venta
            if (cambioPrecioCompra && !cambioPrecioVenta && !cambioGanancia) {
                item.precio_venta = calcularPrecioVentaDesdeGanancia(item.precio_compra, item.porcentaje_ganancia);
            }

            // Recalcular ganancia y subtotal
            item.porcentaje_ganancia = calcularGananciaItem(item.precio_compra, item.precio_venta);
            item.subtotal = item.cantidad * item.precio_compra;

            // Refrescar solo la fila afectada para no interrumpir la escritura
            actualizarVistaFila(itemId);
            actualizarResumen();

            // Debounce para evitar una petición por cada tecla
            if (updateItemTimers[itemId]) {
                clearTimeout(updateItemTimers[itemId]);
            }

            updateItemTimers[itemId] = setTimeout(() => {
                fetch(`{{ route('admin.ordenes_compra.updateItem', ['itemId' => 'PLACEHOLDER']) }}`.replace(
                        'PLACEHOLDER',
                        itemId), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            cantidad: item.cantidad,
                            precio_compra_unidad: item.precio_compra,
                            precio_venta_unidad: item.precio_venta,
                            porcentaje_ganancia_unidad: item.porcentaje_ganancia
                        })
                    })
                    .then(response => response.json())
                    .catch(error => console.error('Error al actualizar:', error));

                delete updateItemTimers[itemId];
            }, 350);
        }

        // ===== ACTUALIZAR RESUMEN =====
        function actualizarResumen() {
            let totalCantidad = 0;
            let totalCompra = 0;

            carrito.forEach(item => {
                totalCantidad += (item.cantidad || 0);
                totalCompra += (item.subtotal || 0);
            });

            document.getElementById('totalCantidad').textContent = totalCantidad;
            document.getElementById('totalCompra').textContent = totalCompra.toFixed(2);
        }

        // ===== ELIMINAR DEL CARRITO =====
        function eliminarDelCarrito(id) {
            Swal.fire({
                title: '¿Eliminar?',
                text: '¿Estás seguro de que deseas eliminar este producto del carrito?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = `{{ route('admin.ordenes_compra.removeItem', ['itemId' => 'PLACEHOLDER']) }}`
                        .replace(
                            'PLACEHOLDER', id);

                    // Eliminar de la base de datos
                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (updateItemTimers[id]) {
                                    clearTimeout(updateItemTimers[id]);
                                    delete updateItemTimers[id];
                                }
                                carrito = carrito.filter(item => item.id !== id);
                                renderizarCarrito();
                                Swal.fire('Eliminado', 'Producto eliminado del carrito', 'success');
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'No se pudo eliminar el producto: ' + error.message, 'error');
                        });
                }
            });
        }

        // ===== LIMPIAR FORMULARIO =====
        function limpiarFormulario() {
            document.getElementById('producto_id').value = '';
            document.getElementById('cantidad').value = '1';
            document.getElementById('precio_compra').value = '0.00';
            document.getElementById('precio_venta').value = '0.00';
            document.getElementById('porcentaje_ganancia').value = '0.00';
            document.getElementById('observaciones').value = '';
            document.getElementById('producto_id').focus();
        }

        // ===== LIMPIAR CARRITO =====
        function limpiarCarrito() {
            Swal.fire({
                title: '¿Limpiar carrito?',
                text: 'Esto eliminará todos los productos del carrito',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (carrito.length === 0) {
                        Swal.fire('Info', 'El carrito ya está vacío', 'info');
                        return;
                    }

                    const itemIds = carrito.map(item => item.id).filter(id => !!id);

                    Object.keys(updateItemTimers).forEach(key => clearTimeout(updateItemTimers[key]));
                    updateItemTimers = {};

                    fetch('{{ route('admin.ordenes_compra.clearItems') }}', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                item_ids: itemIds
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                Swal.fire('Error', data.message || 'No se pudo limpiar el carrito', 'error');
                                return;
                            }

                            carrito = [];
                            contadorLinea = 0;
                            renderizarCarrito();
                            limpiarFormulario();
                            Swal.fire('Limpiado', 'Carrito vacío', 'success');
                        })
                        .catch(error => {
                            console.error('Error al limpiar carrito:', error);
                            Swal.fire('Error', 'No se pudo limpiar el carrito en base de datos', 'error');
                        });
                }
            });
        }

        // ===== ENVIAR FORMULARIO =====
        document.getElementById('formCarrito').addEventListener('submit', function(e) {
            e.preventDefault();

            if (carrito.length === 0) {
                Swal.fire('Error', 'El carrito está vacío. Agrega productos.', 'error');
                return;
            }

            const sucursal_id = document.getElementById('sucursal_id').value;
            const proveedor_id = document.getElementById('proveedor_id').value;

            // Crear formulario oculto con datos del carrito
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('sucursal_id', sucursal_id);
            formData.append('proveedor_id', proveedor_id);
            formData.append('carrito', JSON.stringify(carrito));

            // Enviar por AJAX
            fetch('{{ route('admin.ordenes_compra.store') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Éxito', 'Orden de compra confirmada', 'success').then(() => {
                            carrito = [];
                            contadorLinea = 0;
                            window.location.href = '{{ route('admin.ordenes_compra.index') }}';
                        });
                    } else {
                        Swal.fire('Error', data.message || 'Error al guardar la orden', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error en la petición', 'error');
                });
        });
    </script>
@endpush
