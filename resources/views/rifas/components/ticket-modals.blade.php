<!-- Modal de selección rápida -->
<div class="modal fade" id="modalSeleccionRapida" tabindex="-1" aria-labelledby="modalSeleccionRapidaLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSeleccionRapidaLabel">Selección rápida</h5>
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

<!-- Modal para ingresar teléfono de contacto -->
<div class="modal fade" id="modalTelefono" tabindex="-1" aria-labelledby="modalTelefonoLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTelefonoLabel">Ingresa tu teléfono de contacto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-4">Necesitamos tu número de teléfono para asociar tu selección de boletos.</p>
                <form id="formTelefono">
                    <div class="mb-3">
                        <label for="inputTelefono" class="form-label">Número de teléfono</label>
                        <input type="tel" class="form-control" id="inputTelefono" placeholder="Ej: 5512345678"
                               pattern="[0-9]{10}" maxlength="10" required>
                        <div class="form-text">Formato: 10 dígitos sin espacios ni caracteres especiales</div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Continuar con la compra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
