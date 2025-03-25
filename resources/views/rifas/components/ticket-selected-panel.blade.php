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
