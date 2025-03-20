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
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h1 class="modern-title">Elige tus boletos favoritos</h1>
                <p class="text-muted lead">{{ $rifa->nombre }} - <span class="price-badge">${{ number_format($rifa->precio_boleto, 2) }} MXN/boleto</span></p>
            </div>
            <div class="d-none d-md-block">
                <div class="event-info">
                    <div class="event-info-item">
                        <i class="far fa-calendar-alt"></i>
                        <span>Fecha del sorteo: {{ \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section -->
    <div class="full-width-card-container">
        <div class="modern-card mb-4">
            <div class="card-body p-0">
                <div class="custom-row">
                    <!-- Panel de selección de boletos (lado izquierdo) -->
                    <div class="custom-col-9 p-4">
                        <!-- Header superior con selección y búsqueda -->
                        <div class="selection-header-panel mb-4">
                            <div class="ticket-selector-controls">
                                <div class="filter-group">
                                    <button type="button" class="btn btn-filter" id="mostrarTodos">
                                        <i class="fas fa-th me-1"></i> Todos
                                    </button>
                                    <button type="button" class="btn btn-filter" id="mostrarDisponibles">
                                        <i class="fas fa-ticket-alt me-1"></i> Disponibles
                                    </button>
                                    <button type="button" class="btn btn-filter" id="mostrarSeleccionados">
                                        <i class="fas fa-shopping-cart me-1"></i> Seleccionados
                                    </button>
                                </div>

                                <div class="pagination-info d-none d-md-flex align-items-center">
                                    <button class="btn btn-icon" id="paginaAnterior">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <span class="page-indicator" id="paginaActual">Página 1</span>
                                    <button class="btn btn-icon" id="paginaSiguiente">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="modern-search">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-transparent">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="buscarBoleto" class="form-control border-0" placeholder="Buscar boleto...">
                                    <button class="btn btn-search" type="button" id="btnBuscar">Buscar</button>
                                </div>
                            </div>
                        </div>

                        <!-- Indicators -->
                        <div class="ticket-indicators mb-3">
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

                            <div class="tickets-counter">
                                <span class="tickets-count" id="contadorDisponibles">0</span> boletos disponibles
                            </div>
                        </div>

                        <!-- Panel móvil de paginación (visible solo en móviles) -->
                        <div class="d-md-none mb-3">
                            <div class="pagination-group">
                                <button class="btn btn-icon" id="paginaAnteriorMobile">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="page-indicator" id="paginaActualMobile">Página 1</span>
                                <button class="btn btn-icon" id="paginaSiguienteMobile">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Grid de números de boletos con loading state -->
                        <div class="modern-grid-container">
                            <div class="modern-grid" id="boletosGrid">
                                <div class="grid-loading">
                                    <div class="spinner"></div>
                                    <p>Cargando boletos...</p>
                                </div>
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
                                <p>Selecciona los boletos que deseas comprar</p>
                                <button class="btn btn-outline-primary btn-sm mt-3" id="btnSeleccionRapida">
                                    <i class="fas fa-random me-1"></i> Selección rápida
                                </button>
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
    <i class="fas fa-magic"></i>
</button>

<!-- Backdrop para el panel (movido fuera del panel) -->
<div class="tools-panel-backdrop" id="herramientasBackdrop"></div>

<!-- Panel deslizable de herramientas avanzadas -->
<div class="tools-sliding-panel" id="herramientasAvanzadasPanel">
    <div class="tools-panel-header">
        <h5><i class="fas fa-magic me-2"></i> Selección inteligente</h5>
        <button class="btn-close" id="btnCerrarHerramientas" aria-label="Cerrar"></button>
    </div>

    <div class="tools-panel-body">
        <div class="tools-section">
            <div class="tool-card">
                <div class="tool-card-header">
                    <i class="fas fa-exchange-alt me-2"></i>Selección por rango
                </div>
                <div class="tool-card-body">
                    <p class="text-muted small mb-2">Selecciona todos los números dentro de un rango</p>
                    <div class="input-group mb-1">
                        <input type="number" id="rangoInicio" class="form-control" placeholder="Desde" min="1" max="{{ $rifa->total_boletos }}">
                        <input type="number" id="rangoFin" class="form-control" placeholder="Hasta" min="1" max="{{ $rifa->total_boletos }}">
                        <button class="btn btn-primary" type="button" id="btnSeleccionarRango">
                            Añadir
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tools-section">
            <div class="tool-card">
                <div class="tool-card-header">
                    <i class="fas fa-bolt me-2"></i>Selección rápida
                </div>
                <div class="tool-card-body">
                    <p class="text-muted small mb-2">Añade automáticamente un número específico de boletos</p>
                    <div class="btn-group w-100">
                        <button class="btn btn-outline-primary flex-grow-1" id="btnSeleccionar10">10</button>
                        <button class="btn btn-outline-primary flex-grow-1" id="btnSeleccionar25">25</button>
                        <button class="btn btn-outline-primary flex-grow-1" id="btnSeleccionar50">50</button>
                        <button class="btn btn-outline-primary flex-grow-1" id="btnSeleccionar100">100</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tools-section">
            <div class="tool-card">
                <div class="tool-card-header">
                    <i class="fas fa-random me-2"></i>Selección aleatoria
                </div>
                <div class="tool-card-body">
                    <p class="text-muted small mb-2">Selecciona automáticamente números al azar</p>
                    <div class="input-group mb-1">
                        <input type="number" id="cantidadAleatoria" class="form-control" placeholder="Cantidad" min="1" max="100" value="5">
                        <button class="btn btn-primary" type="button" id="btnSeleccionarAleatorio">
                            Aleatorio
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tools-section">
            <div class="tool-card">
                <div class="tool-card-header">
                    <i class="fas fa-filter me-2"></i>Por terminación
                </div>
                <div class="tool-card-body">
                    <p class="text-muted small mb-2">Selecciona números que terminan en un dígito específico</p>
                    <div class="number-selector">
                        @for($i = 0; $i <= 9; $i++)
                            <button class="number-btn" data-number="{{ $i }}">{{ $i }}</button>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="tools-section text-center mt-4">
            <button class="btn btn-outline-danger" id="btnLimpiarSeleccion">
                <i class="fas fa-trash-alt me-1"></i> Limpiar selección
            </button>
        </div>
    </div>
</div>

<!-- Modal de selección rápida -->
<div class="modal fade" id="modalSeleccionRapida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selección rápida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-4">¿Cuántos boletos deseas agregar rápidamente?</p>
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <button class="btn btn-lg btn-outline-primary flex-grow-1 modal-quick-select" data-cantidad="5">5</button>
                    <button class="btn btn-lg btn-outline-primary flex-grow-1 modal-quick-select" data-cantidad="10">10</button>
                    <button class="btn btn-lg btn-outline-primary flex-grow-1 modal-quick-select" data-cantidad="20">20</button>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-3">Otro:</span>
                    <div class="input-group">
                        <input type="number" class="form-control" id="modalCantidadPersonalizada" min="1" max="100" value="1">
                        <button class="btn btn-primary" id="btnModalPersonalizado">Agregar</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline-primary" id="btnModalAleatorio">Selección aleatoria</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Variables globales */
    :root {
        --color-primary-light: #e6f0ff;
        --color-primary-dark: #0056b3;
        --color-gray-100: #f8f9fa;
        --color-gray-200: #e9ecef;
        --color-gray-300: #dee2e6;
        --color-gray-400: #ced4da;
        --color-gray-600: #6c757d;
        --color-gray-800: #343a40;
        --box-shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.05);
        --box-shadow-md: 0 5px 15px rgba(0, 0, 0, 0.07);
        --box-shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
        --radius-sm: 4px;
        --radius-md: 8px;
        --radius-lg: 16px;
        --transition-fast: all 0.2s ease;
        --transition-medium: all 0.3s ease;
    }

    /* Estilos modernos generales */
    .full-width-wrapper {
        background-color: var(--color-gray-100);
        width: 100%;
        max-width: none;
        padding: 20px 0 40px;
        min-height: 95vh;
    }

    .full-width-card-container {
        width: 95%;
        max-width: 1800px;
        margin: 0 auto;
    }

    .modern-card {
        background-color: #fff;
        border-radius: var(--radius-lg);
        box-shadow: var(--box-shadow-md);
        overflow: hidden;
        border: none;
    }

    .modern-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--color-gray-800);
    }

    /* Badge de precio y fechas */
    .price-badge {
        background-color: var(--color-primary);
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .event-info {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
    }

    .event-info-item {
        background-color: white;
        padding: 8px 16px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        box-shadow: var(--box-shadow-sm);
    }

    .event-info-item i {
        color: var(--color-primary);
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
        border-right: 1px solid var(--color-gray-200);
    }

    .custom-col-3 {
        width: 25%;
    }

    /* Header del selector de boletos */
    .selection-header-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .ticket-selector-controls {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    /* Indicadores de boleto */
    .ticket-indicators {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        background-color: var(--color-gray-100);
        border-radius: var(--radius-md);
        flex-wrap: wrap;
        gap: 15px;
    }

    .tickets-counter {
        margin-left: auto;
        font-size: 0.85rem;
        color: var(--color-gray-600);
    }

    .tickets-count {
        font-weight: 700;
        color: var(--color-primary);
    }

    /* Buscador de boletos moderno */
    .modern-search {
        background-color: white;
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--box-shadow-sm);
        border: 1px solid var(--color-gray-200);
        min-width: 300px;
    }

    .modern-search .form-control {
        box-shadow: none;
        padding: 0.75rem 0.5rem;
        font-size: 0.95rem;
        background: transparent;
    }

    .modern-search .input-group-text {
        color: var(--color-gray-600);
    }

    .btn-search {
        background-color: var(--color-primary);
        color: white;
        border: none;
        padding: 0 20px;
        transition: var(--transition-fast);
    }

    .btn-search:hover {
        background-color: var(--color-primary-dark);
        color: white;
    }

    /* Filtrado y paginación */
    .filter-group, .pagination-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-filter {
        background-color: white;
        border: 1px solid var(--color-gray-300);
        padding: 6px 12px;
        border-radius: var(--radius-md);
        font-size: 0.85rem;
        color: var(--color-gray-600);
        transition: var(--transition-fast);
        box-shadow: var(--box-shadow-sm);
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
        border-radius: var(--radius-md);
        border: 1px solid var(--color-gray-300);
        background: white;
        color: var(--color-gray-600);
        transition: var(--transition-fast);
    }

    .btn-icon:hover {
        background-color: var(--color-gray-100);
    }

    .btn-icon:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .page-indicator {
        padding: 6px 12px;
        background-color: white;
        border-radius: var(--radius-md);
        margin: 0 5px;
        font-size: 0.85rem;
        box-shadow: var(--box-shadow-sm);
        border: 1px solid var(--color-gray-300);
    }

    /* Grid de boletos con loading state */
    .modern-grid-container {
        position: relative;
        min-height: 500px;
        border-radius: var(--radius-md);
        background: white;
        box-shadow: var(--box-shadow-sm);
        border: 1px solid var(--color-gray-200);
    }

    .grid-loading {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.8);
        z-index: 5;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid var(--color-gray-200);
        border-top: 4px solid var(--color-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .modern-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
        gap: 8px;
        min-height: 500px;
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
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition-medium);
        font-weight: 600;
        font-size: 1rem;
        box-shadow: var(--box-shadow-sm);
    }

    .boleto-item.disponible:hover {
        background-color: var(--color-primary-light);
        transform: translateY(-2px);
        box-shadow: var(--box-shadow-md);
        border-color: var(--color-primary);
    }

    .boleto-item.vendido {
        background-color: var(--color-gray-200);
        border-color: var(--color-gray-300);
        color: var(--color-gray-400);
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
        border-top: 1px solid var(--color-gray-400);
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
        background-color: var(--color-gray-200);
        border: 1px solid var(--color-gray-300);
        position: relative;
    }

    .indicator.vendido::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        border-top: 1px solid var(--color-gray-400);
    }

    .indicator.seleccionado {
        background-color: var(--color-primary);
        border: 1px solid var(--color-primary);
    }

    .indicator-label {
        font-size: 0.85rem;
        color: var(--color-gray-600);
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
        border-bottom: 1px solid var(--color-gray-200);
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
        box-shadow: var(--box-shadow-sm);
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
        color: var(--color-gray-400);
        text-align: center;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 15px;
        color: var(--color-gray-400);
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
        padding: 12px 15px;
        border-bottom: 1px solid var(--color-gray-200);
        transition: var(--transition-fast);
        border-left: 3px solid transparent;
    }

    .tickets-list li:hover {
        background-color: var(--color-primary-light);
        border-left-color: var(--color-primary);
    }

    .tickets-list li button {
        border: none;
        background: none;
        color: #dc3545;
        opacity: 0.7;
        transition: var(--transition-fast);
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .tickets-list li button:hover {
        opacity: 1;
        background-color: #ffeaea;
    }

    .selection-footer {
        padding: 20px;
        border-top: 1px solid var(--color-gray-200);
        background-color: var(--color-gray-100);
    }

    .price-summary {
        margin-bottom: 20px;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: var(--color-gray-600);
    }

    .price-row.total {
        font-weight: bold;
        font-size: 1.1rem;
        color: var(--color-primary);
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed var(--color-gray-300);
    }

    .btn-checkout {
        width: 100%;
        background-color: var(--color-primary);
        color: white;
        border: none;
        padding: 12px;
        border-radius: var(--radius-md);
        font-weight: 600;
        transition: var(--transition-medium);
    }

    .btn-checkout:hover:not(:disabled) {
        background-color: var(--color-primary-dark);
        box-shadow: var(--box-shadow-md);
        transform: translateY(-2px);
    }

    .btn-checkout:disabled {
        background-color: var(--color-gray-300);
        color: var(--color-gray-400);
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

    /* Estilos para el botón flotante y panel deslizable - actualizados */
    .tools-floating-button {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: var(--color-primary);
        color: white;
        border: none;
        box-shadow: var(--box-shadow-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .tools-floating-button:hover {
        transform: translateY(-3px) rotate(10deg);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
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
        background-color: var(--color-primary);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tools-panel-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .tools-panel-header .btn-close {
        color: white;
        background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
    }

    .tools-panel-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .tools-section {
        margin-bottom: 24px;
    }

    /* Tool Cards para el panel deslizable */
    .tool-card {
        background: white;
        border-radius: var(--radius-md);
        border: 1px solid var(--color-gray-200);
        box-shadow: var(--box-shadow-sm);
        overflow: hidden;
        transition: var(--transition-fast);
    }

    .tool-card:hover {
        box-shadow: var(--box-shadow-md);
    }

    .tool-card-header {
        padding: 15px;
        background: var(--color-gray-100);
        font-weight: 600;
        color: var(--color-gray-800);
        border-bottom: 1px solid var(--color-gray-200);
    }

    .tool-card-body {
        padding: 15px;
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

    /* Selector de números para terminaciones */
    .number-selector {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
    }

    .number-btn {
        width: 100%;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid var(--color-gray-300);
        border-radius: var(--radius-sm);
        font-weight: bold;
        transition: var(--transition-fast);
    }

    .number-btn:hover {
        background: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .custom-col-9, .custom-col-3 {
            width: 100%;
            padding: 20px;
        }

        .custom-col-9 {
            border-right: none;
            border-bottom: 1px solid var(--color-gray-200);
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

        .selection-header-panel {
            flex-direction: column;
            align-items: stretch;
        }

        .ticket-selector-controls {
            justify-content: space-between;
        }
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

        .modern-title {
            font-size: 1.5rem;
        }

        .filter-group {
            width: 100%;
            justify-content: space-between;
        }

        .btn-filter {
            flex: 1;
            text-align: center;
            padding: 10px 0;
        }

        .btn-filter i {
            margin-right: 0;
        }

        .btn-filter span {
            display: none;
        }
    }
</style>

<!-- Incluir el archivo JavaScript externo -->
<script src="{{ asset('js/seleccion-boletos.js') }}"></script>

<!-- Código JavaScript adicional -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el modal de selección rápida
        const modalSeleccionRapida = new bootstrap.Modal(document.getElementById('modalSeleccionRapida'));

        // Botón de selección rápida en el estado vacío
        document.getElementById('btnSeleccionRapida').addEventListener('click', function() {
            modalSeleccionRapida.show();
        });

        // Botones de selección rápida del modal
        document.querySelectorAll('.modal-quick-select').forEach(btn => {
            btn.addEventListener('click', function() {
                const cantidad = parseInt(this.dataset.cantidad);
                boletosApp.seleccionarRapida(cantidad);
                modalSeleccionRapida.hide();
            });
        });

        // Botón de cantidad personalizada en el modal
        document.getElementById('btnModalPersonalizado').addEventListener('click', function() {
            const cantidad = parseInt(document.getElementById('modalCantidadPersonalizada').value);
            if (!isNaN(cantidad) && cantidad > 0) {
                boletosApp.seleccionarRapida(cantidad);
                modalSeleccionRapida.hide();
            }
        });

        // Botón de selección aleatoria en el modal
        document.getElementById('btnModalAleatorio').addEventListener('click', function() {
            const cantidad = parseInt(document.getElementById('modalCantidadPersonalizada').value);
            if (!isNaN(cantidad) && cantidad > 0) {
                boletosApp.seleccionarAleatorio(cantidad);
                modalSeleccionRapida.hide();
            }
        });

        // Botones de terminación numérica
        document.querySelectorAll('.number-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const numero = parseInt(this.dataset.number);
                boletosApp.seleccionarPorTerminacion(numero);
            });
        });

        // Sincronizar los controles de paginación móvil con los de escritorio
        document.getElementById('paginaAnteriorMobile').addEventListener('click', function() {
            document.getElementById('paginaAnterior').click();
            document.getElementById('paginaActualMobile').textContent = document.getElementById('paginaActual').textContent;
        });

        document.getElementById('paginaSiguienteMobile').addEventListener('click', function() {
            document.getElementById('paginaSiguiente').click();
            document.getElementById('paginaActualMobile').textContent = document.getElementById('paginaActual').textContent;
        });

        // Inicializar el selector de boletos
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

        // Exponer al objeto global window para otros scripts
        window.boletosApp = boletosApp;
    });
</script>
@endsection
