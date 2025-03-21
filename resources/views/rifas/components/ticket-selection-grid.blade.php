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
        </div>

        <div class="modern-search">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="buscarBoleto" class="form-control" placeholder="Buscar boleto...">
                <button class="btn btn-search" type="button" id="btnBuscar">Buscar</button>
                <button class="btn btn-refresh" type="button" id="btnVerificarDisponibilidad" title="Verificar disponibilidad">
                    <i class="fas fa-sync-alt"></i>
                </button>
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

    <!-- Paginación superior (visible solo en escritorio) -->
    <div class="mb-3 d-none d-md-flex justify-content-end">
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

    <!-- Grid de números de boletos con loading state -->
    <div class="modern-grid-container">
        <div class="modern-grid" id="boletosGrid">
            <!-- El contenido se llenará dinámicamente -->
        </div>
    </div>

    <!-- Paginación inferior (visible solo en escritorio) -->
    <div class="d-none d-md-flex justify-content-end mt-4">
        <div class="pagination-group">
            <button class="btn btn-icon" id="paginaAnteriorInferior">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span class="page-indicator" id="paginaActualInferior">Página 1</span>
            <button class="btn btn-icon" id="paginaSiguienteInferior">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>
