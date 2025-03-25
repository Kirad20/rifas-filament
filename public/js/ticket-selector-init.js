/**
 * Script de inicialización para el selector de boletos
 * Este archivo maneja la inicialización y configuración de la interfaz del selector
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar la clase principal con la configuración
    const boletosApp = new BoletosSelector(ticketSelectorConfig);
    boletosApp.init();

    // Inicializar el modal de selección rápida
    const modalSeleccionRapida = new bootstrap.Modal(document.getElementById('modalSeleccionRapida'));

    // Configurar eventos del modal de selección rápida
    configurarModalSeleccionRapida(modalSeleccionRapida, boletosApp);

    // Configurar botones de selección por terminación
    configurarBotonesTerminacion(boletosApp);

    // Configurar controles de paginación móvil
    configurarPaginacionMovil(boletosApp);

    // Configurar panel deslizable
    configurarPanelDeslizable();

    // Configurar eventos de selección avanzada
    configurarSeleccionAvanzada(boletosApp);

    // Configurar botón de selección rápida en el estado vacío
    const btnSeleccionRapida = document.getElementById('btnSeleccionRapida');
    if (btnSeleccionRapida) {
        btnSeleccionRapida.addEventListener('click', function() {
            modalSeleccionRapida.show();
        });
    }

    // Exponer el objeto para uso externo si es necesario
    window.boletosApp = boletosApp;
});

/**
 * Configura el modal de selección rápida
 * @param {bootstrap.Modal} modal - Instancia del modal de Bootstrap
 * @param {BoletosSelector} app - Instancia del selector de boletos
 */
function configurarModalSeleccionRapida(modal, app) {
    // Botón para mostrar el modal
    const btnSeleccionRapida = document.getElementById('btnSeleccionRapida');
    if (btnSeleccionRapida) {
        btnSeleccionRapida.addEventListener('click', () => modal.show());
    }

    // Botones de cantidades predefinidas
    document.querySelectorAll('.modal-quick-select').forEach(btn => {
        btn.addEventListener('click', function() {
            const cantidad = parseInt(this.dataset.cantidad);
            app.seleccionarRapida(cantidad);
            modal.hide();
        });
    });

    // Botón de cantidad personalizada
    const btnModalPersonalizado = document.getElementById('btnModalPersonalizado');
    const inputCantidad = document.getElementById('modalCantidadPersonalizada');

    if (btnModalPersonalizado && inputCantidad) {
        btnModalPersonalizado.addEventListener('click', function() {
            const cantidad = parseInt(inputCantidad.value);
            if (!isNaN(cantidad) && cantidad > 0) {
                app.seleccionarRapida(cantidad);
                modal.hide();
            }
        });
    }

    // Botón de selección aleatoria
    const btnModalAleatorio = document.getElementById('btnModalAleatorio');
    if (btnModalAleatorio && inputCantidad) {
        btnModalAleatorio.addEventListener('click', function() {
            const cantidad = parseInt(inputCantidad.value);
            if (!isNaN(cantidad) && cantidad > 0) {
                app.seleccionarAleatorio(cantidad);
                modal.hide();
            }
        });
    }
}

/**
 * Configura los botones de terminación numérica
 * @param {BoletosSelector} app - Instancia del selector de boletos
 */
function configurarBotonesTerminacion(app) {
    document.querySelectorAll('.number-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const numero = parseInt(this.dataset.number);
            app.seleccionarPorTerminacion(numero);
        });
    });
}

/**
 * Configura la paginación para dispositivos móviles
 * @param {BoletosSelector} app - Instancia del selector de boletos
 */
function configurarPaginacionMovil(app) {
    const paginaAnteriorMobile = document.getElementById('paginaAnteriorMobile');
    const paginaSiguienteMobile = document.getElementById('paginaSiguienteMobile');

    if (paginaAnteriorMobile) {
        paginaAnteriorMobile.addEventListener('click', () => {
            app.cambiarPagina('anterior');
        });
    }

    if (paginaSiguienteMobile) {
        paginaSiguienteMobile.addEventListener('click', () => {
            app.cambiarPagina('siguiente');
        });
    }
}

/**
 * Configura el panel deslizable de herramientas avanzadas
 */
function configurarPanelDeslizable() {
    const btnHerramientas = document.getElementById('btnHerramientasAvanzadas');
    const panelHerramientas = document.getElementById('herramientasAvanzadasPanel');
    const btnCerrarHerramientas = document.getElementById('btnCerrarHerramientas');
    const backdrop = document.getElementById('herramientasBackdrop');

    // Validar que los elementos existan
    if (!btnHerramientas || !panelHerramientas || !btnCerrarHerramientas || !backdrop) {
        console.error('Elementos del panel deslizable no encontrados');
        return;
    }

    // Abrir panel
    btnHerramientas.addEventListener('click', () => {
        panelHerramientas.classList.add('active');
        backdrop.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevenir scroll
    });

    // Cerrar panel (botón X)
    btnCerrarHerramientas.addEventListener('click', () => cerrarPanel());

    // Cerrar panel (clic en backdrop)
    backdrop.addEventListener('click', () => cerrarPanel());

    // También cerrar con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && panelHerramientas.classList.contains('active')) {
            cerrarPanel();
        }
    });

    /**
     * Cierra el panel deslizable
     */
    function cerrarPanel() {
        panelHerramientas.classList.remove('active');
        backdrop.classList.remove('active');
        document.body.style.overflow = ''; // Restaurar scroll
    }
}

/**
 * Configura los eventos de selección avanzada desde el panel
 * @param {BoletosSelector} app - Instancia del selector de boletos
 */
function configurarSeleccionAvanzada(app) {
    // Selección por rango
    const btnSeleccionarRango = document.getElementById('btnSeleccionarRango');
    const rangoInicio = document.getElementById('rangoInicio');
    const rangoFin = document.getElementById('rangoFin');

    if (btnSeleccionarRango && rangoInicio && rangoFin) {
        btnSeleccionarRango.addEventListener('click', () => {
            const inicio = parseInt(rangoInicio.value);
            const fin = parseInt(rangoFin.value);

            if (isNaN(inicio) || isNaN(fin) || inicio < 1 ||
                fin > app.totalBoletos || inicio > fin) {
                alert(`Por favor ingrese un rango válido entre 1 y ${app.totalBoletos}`);
                return;
            }

            app.seleccionarPorRango(inicio, fin);
        });
    }

    // Botones de selección rápida
    const btnSeleccionar10 = document.getElementById('btnSeleccionar10');
    const btnSeleccionar25 = document.getElementById('btnSeleccionar25');
    const btnSeleccionar50 = document.getElementById('btnSeleccionar50');
    const btnSeleccionar100 = document.getElementById('btnSeleccionar100');

    if (btnSeleccionar10) btnSeleccionar10.addEventListener('click', () => app.seleccionarRapida(10));
    if (btnSeleccionar25) btnSeleccionar25.addEventListener('click', () => app.seleccionarRapida(25));
    if (btnSeleccionar50) btnSeleccionar50.addEventListener('click', () => app.seleccionarRapida(50));
    if (btnSeleccionar100) btnSeleccionar100.addEventListener('click', () => app.seleccionarRapida(100));

    // Selección aleatoria
    const btnSeleccionarAleatorio = document.getElementById('btnSeleccionarAleatorio');
    const cantidadAleatoria = document.getElementById('cantidadAleatoria');

    if (btnSeleccionarAleatorio && cantidadAleatoria) {
        btnSeleccionarAleatorio.addEventListener('click', () => {
            const cantidad = parseInt(cantidadAleatoria.value);

            if (isNaN(cantidad) || cantidad < 1 || cantidad > 100) {
                alert('Por favor ingrese una cantidad válida entre 1 y 100');
                return;
            }

            app.seleccionarAleatorio(cantidad);
        });
    }

    // Limpiar selección
    const btnLimpiarSeleccion = document.getElementById('btnLimpiarSeleccion');
    if (btnLimpiarSeleccion) {
        btnLimpiarSeleccion.addEventListener('click', () => app.limpiarSeleccion());
    }
}
