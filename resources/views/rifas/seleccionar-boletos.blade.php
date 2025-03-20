@extends('layouts.app')

@section('title', 'Selección de Boletos - ' . $rifa->nombre)

@section('content')
<div class="full-width-wrapper">
    <div class="container mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rifas.index') }}">Rifas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rifas.show', $rifa->id) }}">{{ $rifa->nombre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Seleccionar Boletos</li>
            </ol>
        </nav>
    </div>

    <div class="container mb-4">
        <h1 class="modern-title">Elige tus boletos</h1>
        <p class="text-muted">Selecciona los números que deseas para la rifa "{{ $rifa->nombre }}"</p>
    </div>

    <div class="full-width-card-container">
        <div class="modern-card w-100 mb-4">
            <div class="card-body p-0">
                <div class="custom-row">
                    <!-- Panel de selección de boletos (lado izquierdo) -->
                    <div class="custom-col-9 p-4">
                        <!-- Buscador de boletos -->
                        <div class="modern-search mb-4">
                            <div class="input-group">
                                <input type="text" id="buscarBoleto" class="form-control form-control-lg border-0" placeholder="Buscar boleto por número...">
                                <button class="btn btn-light" type="button" id="btnBuscar">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Filtros y navegación -->
                        <div class="modern-filter mb-3">
                            <div class="filter-group">
                                <button type="button" class="btn btn-filter" id="mostrarTodos">Todos</button>
                                <button type="button" class="btn btn-filter" id="mostrarDisponibles">Disponibles</button>
                                <button type="button" class="btn btn-filter" id="mostrarSeleccionados">Mis seleccionados</button>
                            </div>

                            <div class="pagination-group">
                                <button class="btn btn-icon" id="paginaAnterior">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="page-indicator" id="paginaActual">Página 1</span>
                                <button class="btn btn-icon" id="paginaSiguiente">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Grid de números de boletos -->
                        <div class="modern-grid" id="boletosGrid">
                            <!-- Se llenará dinámicamente con JavaScript -->
                        </div>

                        <div class="status-indicators">
                            <div class="status-item">
                                <span class="indicator disponible"></span>
                                <span class="indicator-label">Disponible</span>
                            </div>
                            <div class="status-item">
                                <span class="indicator vendido"></span>
                                <span class="indicator-label">Vendido</span>
                            </div>
                            <div class="status-item">
                                <span class="indicator seleccionado"></span>
                                <span class="indicator-label">Seleccionado</span>
                            </div>
                        </div>
                    </div>

                    <!-- Panel de boletos seleccionados (lado derecho) -->
                    <div class="custom-col-3 selection-panel">
                        <div class="selection-header">
                            <h3>Tu selección</h3>
                            <span class="selection-counter" id="cantidadBoletos">0</span>
                        </div>

                        <div id="boletosSeleccionados" class="selection-body">
                            <div class="empty-state" id="mensajeVacio">
                                <i class="fas fa-ticket-alt"></i>
                                <p>Aún no has seleccionado boletos</p>
                            </div>
                            <ul class="tickets-list" id="listaBoletos">
                                <!-- Se llenará dinámicamente -->
                            </ul>
                        </div>

                        <div class="selection-footer">
                            <div class="price-summary">
                                <div class="price-row">
                                    <span>Precio unitario</span>
                                    <span>${{ number_format($rifa->precio_boleto, 2) }} MXN</span>
                                </div>
                                <div class="price-row total">
                                    <span>Total</span>
                                    <span id="subtotalPrecio">$0.00 MXN</span>
                                </div>
                            </div>
                            <button id="btnProcederPago" class="btn-checkout" disabled>
                                Proceder al pago <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botón flotante para herramientas avanzadas -->
<button class="tools-floating-button" id="btnHerramientasAvanzadas">
    <i class="fas fa-sliders-h"></i>
</button>

<!-- Backdrop para el panel (movido fuera del panel) -->
<div class="tools-panel-backdrop" id="herramientasBackdrop"></div>

<!-- Panel deslizable de herramientas avanzadas -->
<div class="tools-sliding-panel" id="herramientasAvanzadasPanel">
    <div class="tools-panel-header">
        <h5><i class="fas fa-sliders-h me-2"></i> Herramientas avanzadas</h5>
        <button class="btn-close" id="btnCerrarHerramientas" aria-label="Cerrar"></button>
    </div>

    <div class="tools-panel-body">
        <div class="tools-section">
            <h6><i class="fas fa-exchange-alt me-2"></i>Selección por rango</h6>
            <div class="input-group mb-1">
                <input type="number" id="rangoInicio" class="form-control" placeholder="Desde" min="1" max="{{ $rifa->total_boletos }}">
                <input type="number" id="rangoFin" class="form-control" placeholder="Hasta" min="1" max="{{ $rifa->total_boletos }}">
                <button class="btn btn-outline" type="button" id="btnSeleccionarRango">
                    <i class="fas fa-check"></i>
                </button>
            </div>
            <small class="text-muted d-block">Selecciona boletos en un rango específico</small>
        </div>

        <div class="tools-section">
            <h6><i class="fas fa-bolt me-2"></i>Selección rápida</h6>
            <div class="btn-group w-100">
                <button class="btn btn-outline flex-grow-1" id="btnSeleccionar10">10</button>
                <button class="btn btn-outline flex-grow-1" id="btnSeleccionar25">25</button>
                <button class="btn btn-outline flex-grow-1" id="btnSeleccionar50">50</button>
                <button class="btn btn-outline flex-grow-1" id="btnSeleccionar100">100</button>
            </div>
            <small class="text-muted d-block mt-1">Añade rápidamente una cantidad específica de boletos</small>
        </div>

        <div class="tools-section">
            <h6><i class="fas fa-random me-2"></i>Selección aleatoria</h6>
            <div class="input-group mb-1">
                <input type="number" id="cantidadAleatoria" class="form-control" placeholder="Cantidad" min="1" max="100" value="5">
                <button class="btn btn-outline" type="button" id="btnSeleccionarAleatorio">
                    Aleatorio
                </button>
            </div>
            <small class="text-muted d-block">Selecciona números al azar</small>
        </div>

        <div class="tools-section">
            <h6><i class="fas fa-filter me-2"></i>Terminados en</h6>
            <div class="input-group mb-1">
                <input type="number" id="terminacion" class="form-control" placeholder="Dígito" min="0" max="9" value="0">
                <button class="btn btn-outline" type="button" id="btnSeleccionarTerminacion">
                    Seleccionar
                </button>
            </div>
            <small class="text-muted d-block">Todos los boletos que terminen en este dígito</small>
        </div>

        <div class="tools-section text-center mt-4">
            <button class="btn btn-outline-danger" id="btnLimpiarSeleccion">
                <i class="fas fa-trash-alt me-1"></i> Limpiar selección
            </button>
        </div>
    </div>
</div>

<style>
    /* Estilos modernos generales */
    .full-width-wrapper {
        background-color: #f8f9fa;
        width: 100%;
        max-width: none;
        padding: 20px 0 40px;
    }

    .full-width-card-container {
        width: 95%;
        max-width: 1800px;
        margin: 0 auto;
    }

    .modern-card {
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.05);
        overflow: hidden;
        border: none;
    }

    .modern-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    /* Layout principal */
    .custom-row {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        margin: 0;
    }

    .custom-col-9 {
        width: 75%;
        border-right: 1px solid #f0f0f0;
    }

    .custom-col-3 {
        width: 25%;
    }

    /* Buscador de boletos moderno */
    .modern-search {
        background-color: #f8f9fa;
        border-radius: 12px;
        overflow: hidden;
    }

    .modern-search .form-control {
        box-shadow: none;
        padding: 15px 20px;
        font-size: 1rem;
    }

    .modern-search .btn {
        border: none;
        background: none;
        padding: 0 20px;
    }

    /* Estilos para herramientas */
    .tool-box {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        height: 100%;
        border: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }

    .tool-box:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .tool-box-header {
        padding: 12px 15px;
        font-weight: 600;
        color: #333;
        border-bottom: 1px solid #f0f0f0;
    }

    .tool-box-body {
        padding: 15px;
    }

    .btn-outline {
        border: 1px solid #e0e0e0;
        background: none;
        color: #333;
        transition: all 0.2s ease;
    }

    .btn-outline:hover {
        background-color: var(--color-primary);
        color: white;
    }

    /* Filtrado y paginación */
    .modern-filter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .filter-group, .pagination-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-filter {
        background: none;
        border: 1px solid #e0e0e0;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #666;
        transition: all 0.2s ease;
    }

    .btn-filter:hover, .btn-filter.active {
        background-color: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
    }

    .btn-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        background: none;
        color: #666;
        transition: all 0.2s ease;
    }

    .btn-icon:hover {
        background-color: #f0f0f0;
    }

    .page-indicator {
        padding: 6px 12px;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 0 5px;
        font-size: 0.85rem;
    }

    /* Grid de boletos moderno */
    .modern-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
        gap: 8px;
        min-height: 500px;
        background-color: #fff;
        border-radius: 12px;
        padding: 20px;
        width: 100%;
    }

    .boleto-item {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 45px;
        background-color: #fff;
        border: 1px solid rgba(var(--color-primary-rgb), 0.5);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.25s ease;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .boleto-item.disponible:hover {
        background-color: var(--color-primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .boleto-item.vendido {
        background-color: #f0f0f0;
        border-color: #e0e0e0;
        color: #aaa;
        cursor: not-allowed;
        position: relative;
        box-shadow: none;
    }

    .boleto-item.vendido::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        border-top: 1px solid #ccc;
    }

    .boleto-item.seleccionado {
        background-color: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
        transform: scale(1.05);
        box-shadow: 0 3px 10px rgba(var(--color-primary-rgb), 0.3);
        z-index: 1;
    }

    /* Indicadores de estado */
    .status-indicators {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .indicator {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .indicator.disponible {
        background-color: #fff;
        border: 1px solid var(--color-primary);
    }

    .indicator.vendido {
        background-color: #f0f0f0;
        border: 1px solid #e0e0e0;
        position: relative;
    }

    .indicator.vendido::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        border-top: 1px solid #ccc;
    }

    .indicator.seleccionado {
        background-color: var(--color-primary);
        border: 1px solid var(--color-primary);
    }

    .indicator-label {
        font-size: 0.85rem;
        color: #666;
    }

    /* Panel de selección */
    .selection-panel {
        display: flex;
        flex-direction: column;
        height: 100%;
        background-color: #fff;
    }

    .selection-header {
        padding: 25px 20px 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .selection-header h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
    }

    .selection-counter {
        background-color: var(--color-primary);
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .selection-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        max-height: 400px;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 200px;
        color: #aaa;
        text-align: center;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .tickets-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .tickets-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }

    .tickets-list li:hover {
        background-color: #f9f9f9;
    }

    .tickets-list li button {
        border: none;
        background: none;
        color: #dc3545;
        opacity: 0.7;
        transition: all 0.2s ease;
        padding: 5px;
    }

    .tickets-list li button:hover {
        opacity: 1;
    }

    .selection-footer {
        padding: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .price-summary {
        margin-bottom: 20px;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .price-row.total {
        font-weight: bold;
        font-size: 1.1rem;
        color: var(--color-primary);
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #f0f0f0;
    }

    .btn-checkout {
        width: 100%;
        background-color: var(--color-primary);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-checkout:hover:not(:disabled) {
        background-color: var(--color-primary-dark, #0056b3);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .btn-checkout:disabled {
        background-color: #e0e0e0;
        color: #aaa;
        cursor: not-allowed;
    }

    /* Highlight para búsqueda */
    .highlight {
        animation: pulse 1.5s ease-in-out;
        box-shadow: 0 0 0 5px rgba(var(--color-primary-rgb), 0.5);
        z-index: 1;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(var(--color-primary-rgb), 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(var(--color-primary-rgb), 0); }
        100% { box-shadow: 0 0 0 0 rgba(var(--color-primary-rgb), 0); }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .custom-col-9, .custom-col-3 {
            width: 100%;
            padding: 20px;
        }

        .custom-col-9 {
            border-right: none;
            border-bottom: 1px solid #f0f0f0;
        }

        .selection-body {
            max-height: 300px;
        }

        .modern-filter {
            flex-direction: column;
            gap: 15px;
        }

        .filter-group, .pagination-group {
            width: 100%;
            justify-content: center;
        }
    }

    /* Estilos para el botón flotante y panel deslizable - actualizados */
    .tools-floating-button {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background-color: var(--color-primary);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .tools-floating-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }

    .tools-sliding-panel {
        position: fixed;
        top: 0;
        right: -350px;
        width: 350px;
        height: 100vh;
        background-color: white;
        box-shadow: -5px 0 25px rgba(0,0,0,0.1);
        z-index: 1060; /* Valor más alto que el backdrop */
        transition: right 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .tools-sliding-panel.active {
        right: 0;
    }

    .tools-panel-header {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white; /* Asegurar fondo blanco */
    }

    .tools-panel-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .tools-panel-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: white; /* Asegurar fondo blanco */
    }

    .tools-section {
        margin-bottom: 24px;
        background-color: white; /* Asegurar fondo blanco */
    }

    .tools-section h6 {
        font-weight: 600;
        margin-bottom: 12px;
        color: #444;
    }

    /* Backdrop ahora está fuera del panel */
    .tools-panel-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0,0,0,0.5);
        z-index: 1050; /* Valor más bajo que el panel pero más alto que el resto de la página */
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .tools-panel-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    @media (max-width: 576px) {
        .tools-sliding-panel {
            width: 85%;
            right: -85%;
        }

        .tools-floating-button {
            bottom: 20px;
            right: 20px;
        }
    }

    /* Garantizar que los inputs y botones sean visibles */
    .tools-panel-body .form-control,
    .tools-panel-body .btn,
    .tools-panel-body .input-group {
        position: relative;
        z-index: 1; /* Valor positivo para asegurar que estén por encima del fondo */
    }
</style>

<!-- Incluir el archivo JavaScript externo -->
<script src="{{ asset('js/seleccion-boletos.js') }}"></script>

<!-- Inicializar el selector con un script pequeño y conciso -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const boletosApp = new BoletosSelector({
            precio: {{ $rifa->precio_boleto }},
            totalBoletos: {{ $rifa->total_boletos ?? 100 }},
            boletos: {!! json_encode($boletos->keyBy('numero')->all()) !!},
            boletosPorPagina: 100,
            rifaId: {{ $rifa->id }},
            csrfToken: '{{ csrf_token() }}',
            formAction: '{{ route('rifas.procesar-compra') }}'
        });

        // Manejadores para el panel deslizable - actualizados
        const btnHerramientas = document.getElementById('btnHerramientasAvanzadas');
        const panelHerramientas = document.getElementById('herramientasAvanzadasPanel');
        const btnCerrarHerramientas = document.getElementById('btnCerrarHerramientas');
        const backdrop = document.getElementById('herramientasBackdrop');

        // Abrir panel
        btnHerramientas.addEventListener('click', function() {
            panelHerramientas.classList.add('active');
            backdrop.classList.add('active'); // Añadir clase active al backdrop
            document.body.style.overflow = 'hidden'; // Prevenir scroll
        });

        // Cerrar panel (botón X)
        btnCerrarHerramientas.addEventListener('click', function() {
            panelHerramientas.classList.remove('active');
            backdrop.classList.remove('active'); // Quitar clase active del backdrop
            document.body.style.overflow = ''; // Restaurar scroll
        });

        // Cerrar panel (clic en backdrop)
        backdrop.addEventListener('click', function() {
            panelHerramientas.classList.remove('active');
            backdrop.classList.remove('active'); // Quitar clase active del backdrop
            document.body.style.overflow = ''; // Restaurar scroll
        });

        // También cerrar con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && panelHerramientas.classList.contains('active')) {
                panelHerramientas.classList.remove('active');
                backdrop.classList.remove('active'); // Quitar clase active del backdrop
                document.body.style.overflow = '';
            }
        });
    });
</script>
@endsection
