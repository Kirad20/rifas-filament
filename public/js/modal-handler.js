/**
 * Manejador especializado de modales para la aplicación de rifas
 * Este script asegura que los modales de Bootstrap funcionen correctamente
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Modal Handler inicializado');

    // Ocultar modales inmediatamente para evitar que se muestren como contenido normal
    document.querySelectorAll('.modal').forEach(modal => {
        modal.style.display = 'none';
    });

    // Esperar a que Bootstrap esté disponible
    let bootstrapCheckAttempts = 0;
    const bootstrapChecker = setInterval(() => {
        bootstrapCheckAttempts++;

        if (typeof bootstrap !== 'undefined' || bootstrapCheckAttempts > 20) {
            clearInterval(bootstrapChecker);

            if (typeof bootstrap !== 'undefined') {
                console.log('Bootstrap detectado, inicializando modales');
                initializeModals();
            } else {
                console.error('Bootstrap no disponible después de 20 intentos');
            }
        }
    }, 100); // Revisar cada 100ms
});

/**
 * Inicializa todos los modales de Bootstrap en la página
 */
function initializeModals() {
    // Restaurar visualización normal de modales
    document.querySelectorAll('.modal').forEach(modal => {
        modal.style.display = '';
    });

    // Inicializar modal de teléfono
    const phoneModal = document.getElementById('modalTelefono');
    if (phoneModal) {
        // Forzar la limpieza de cualquier estado previo
        phoneModal.classList.remove('show', 'fade', 'd-block');
        document.body.classList.remove('modal-open');

        // Remover cualquier backdrop antiguo
        const oldBackdrops = document.querySelectorAll('.modal-backdrop');
        oldBackdrops.forEach(backdrop => {
            if (backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
        });

        try {
            // Inicializar el modal y guardarlo como referencia global
            window.phoneModalInstance = new bootstrap.Modal(phoneModal, {
                backdrop: 'static',
                keyboard: false
            });

            console.log('Modal de teléfono inicializado con éxito');

            // Verificar si debemos mostrarlo automáticamente (usuario no autenticado sin teléfono guardado)
            const isLoggedIn = document.body.getAttribute('data-user-logged') === 'true';
            const savedPhone = localStorage.getItem('telefono_cliente');

            if (!isLoggedIn && !savedPhone) {
                console.log('Usuario no autenticado sin teléfono guardado, mostrando modal');
                setTimeout(() => {
                    window.phoneModalInstance.show();
                }, 1000);
            }
        } catch (error) {
            console.error('Error al inicializar modal de teléfono:', error);
        }
    }

    // Inicializar modal de selección rápida
    const quickSelectModal = document.getElementById('modalSeleccionRapida');
    if (quickSelectModal) {
        try {
            window.quickSelectModalInstance = new bootstrap.Modal(quickSelectModal);
            console.log('Modal de selección rápida inicializado con éxito');
        } catch (error) {
            console.error('Error al inicializar modal de selección rápida:', error);
        }
    }

    // Configurar el formulario de teléfono
    setupPhoneForm();
}

/**
 * Configura el formulario de teléfono para guardar en localStorage
 */
function setupPhoneForm() {
    const phoneForm = document.getElementById('formTelefono');
    if (phoneForm) {
        phoneForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const phoneInput = document.getElementById('inputTelefono');
            if (!phoneInput) return;

            const phone = phoneInput.value.trim();

            // Validar formato (10 dígitos)
            if (!/^\d{10}$/.test(phone)) {
                alert('Por favor ingresa un número de teléfono válido de 10 dígitos');
                return;
            }

            // Guardar en localStorage
            localStorage.setItem('telefono_cliente', phone);
            console.log('Teléfono guardado:', phone);

            // Cerrar el modal
            if (window.phoneModalInstance) {
                window.phoneModalInstance.hide();

                // Notificar al AppManager si existe
                if (window.appManager) {
                    window.appManager.telefonoGuardado = phone;

                    // Si estaba en proceso de compra, continuar
                    if (window.appManager.procesandoCompra) {
                        window.appManager.procesarCompraTelefono(phone);
                        window.appManager.procesandoCompra = false;
                    }
                }
            }
        });
    }
}

/**
 * Función pública para mostrar el modal de teléfono desde cualquier lugar
 */
window.showPhoneModal = function() {
    if (window.phoneModalInstance) {
        window.phoneModalInstance.show();
    } else {
        console.error('Modal de teléfono no inicializado');
    }
};

/**
 * Función pública para mostrar el modal de selección rápida desde cualquier lugar
 */
window.showQuickSelectModal = function() {
    if (window.quickSelectModalInstance) {
        window.quickSelectModalInstance.show();
    } else {
        console.error('Modal de selección rápida no inicializado');
    }
};
