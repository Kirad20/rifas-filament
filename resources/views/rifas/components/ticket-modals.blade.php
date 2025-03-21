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
<div class="modal fade" id="modalTelefono" tabindex="-1" aria-labelledby="modalTelefonoLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTelefonoLabel">Ingresa tu teléfono de contacto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-4">Necesitamos tu número de teléfono para asociar tu selección de boletos y guardarlo para tus futuras compras.</p>
                <form id="formTelefono">
                    <div class="mb-3">
                        <label for="inputTelefono" class="form-label">Número de teléfono</label>
                        <input type="tel" class="form-control" id="inputTelefono" placeholder="Ej: 5512345678"
                               pattern="[0-9]{10}" maxlength="10" required>
                        <div class="form-text">Formato: 10 dígitos sin espacios ni caracteres especiales</div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Guardar teléfono</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Esconder modales inmediatamente para evitar que se muestren como texto plano
document.addEventListener('DOMContentLoaded', function() {
    // Ocultar modales mediante CSS para evitar FOUC (Flash of Unstyled Content)
    var styleSheet = document.createElement("style");
    styleSheet.textContent = ".modal { display: none; }";
    document.head.appendChild(styleSheet);

    // Inicializar los modales cuando Bootstrap esté disponible
    function inicializarModales() {
        try {
            // 1. Modal de selección rápida
            var modalSeleccionRapida = document.getElementById('modalSeleccionRapida');
            if (modalSeleccionRapida) {
                window.modalSeleccionRapidaInstance = new bootstrap.Modal(modalSeleccionRapida);
            }

            // 2. Modal de teléfono
            var modalTelefono = document.getElementById('modalTelefono');
            if (modalTelefono) {
                window.modalTelefonoInstance = new bootstrap.Modal(modalTelefono, {
                    backdrop: 'static',
                    keyboard: false
                });

                // Configurar el formulario de teléfono
                var formTelefono = document.getElementById('formTelefono');
                if (formTelefono) {
                    formTelefono.addEventListener('submit', function(e) {
                        e.preventDefault();
                        var telefono = document.getElementById('inputTelefono').value.trim();

                        if (!/^\d{10}$/.test(telefono)) {
                            alert('Por favor ingresa un número de teléfono válido de 10 dígitos.');
                            return;
                        }

                        // Guardar en localStorage
                        localStorage.setItem('telefono_cliente', telefono);

                        // Cerrar modal
                        window.modalTelefonoInstance.hide();

                        // Notificar a la aplicación
                        if (window.appManager) {
                            window.appManager.telefonoGuardado = telefono;

                            if (window.appManager.procesandoCompra) {
                                window.appManager.procesarCompraTelefono(telefono);
                                window.appManager.procesandoCompra = false;
                            }
                        }
                    });
                }

                // Mostrar modal si es necesario
                var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
                var telefonoGuardado = localStorage.getItem('telefono_cliente');

                if (!isLoggedIn && !telefonoGuardado) {
                    setTimeout(function() {
                        window.modalTelefonoInstance.show();
                    }, 1000);
                }
            }

            console.log('Modales inicializados correctamente');
        } catch (error) {
            console.error('Error al inicializar modales:', error);
        }
    }

    // Esperar a que Bootstrap esté disponible
    var waitForBootstrap = setInterval(function() {
        if (typeof bootstrap !== 'undefined') {
            clearInterval(waitForBootstrap);
            inicializarModales();
        }
    }, 100);
});
</script>
