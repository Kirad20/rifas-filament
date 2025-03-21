<!-- Botón flotante para herramientas avanzadas -->
<button class="tools-floating-button" id="btnHerramientasAvanzadas">
    <i class="fas fa-magic"></i>
</button>

<!-- Backdrop para el panel -->
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
                        <input type="number" id="rangoInicio" class="form-control" placeholder="Desde" min="1" max="{{ $rifa->total_boletos ?? 100 }}">
                        <input type="number" id="rangoFin" class="form-control" placeholder="Hasta" min="1" max="{{ $rifa->total_boletos ?? 100 }}">
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
