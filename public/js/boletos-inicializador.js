/**
 * Clase para gestionar la aplicación de selección de boletos
 * Centraliza la gestión de eventos y componentes
 */
class BoletosAppManager {
    /**
     * Constructor
     * @param {BoletosSelector} boletosApp - La instancia principal del selector
     * @param {boolean} isLoggedIn - Si el usuario está autenticado
     */
    constructor(boletosApp, isLoggedIn) {
        this.boletosApp = boletosApp;
        this.isLoggedIn = isLoggedIn;
        this.telefonoGuardado = localStorage.getItem('telefono_cliente');
        this.modalTelefono = null;
        this.modalSeleccionRapida = null;
    }

    /**
     * Inicializa la aplicación
     */
    init() {
        // Inicializar componentes básicos
        this.boletosApp.clasificarBoletos();
        this.boletosApp.configurarDOM();
        this.boletosApp.configurarEventListeners();
        this.boletosApp.cargarBoletos();
        this.boletosApp.actualizarContadorDisponibles();

        // Configurar componentes adicionales
        this.configurarVerificadorDisponibilidad();
        this.configurarModales();
        this.configurarHerramientasAvanzadas();
        this.configurarSeleccionRapida();

        // Reemplazar método procederAlPago con versión mejorada
        this.mejorarProcesoPago();
    }

    /**
     * Configura el verificador de disponibilidad
     */
    configurarVerificadorDisponibilidad() {
        const btnVerificarDisponibilidad = document.getElementById('btnVerificarDisponibilidad');
        if (btnVerificarDisponibilidad) {
            btnVerificarDisponibilidad.addEventListener('click', () => {
                this.verificarDisponibilidadBoletos();
            });
        }
    }

    /**
     * Configura los modales
     */
    configurarModales() {
        // Modal de selección rápida
        const modalElement = document.getElementById('modalSeleccionRapida');
        if (modalElement) {
            modalElement.style.display = 'none';
            this.modalSeleccionRapida = new bootstrap.Modal(modalElement);
            this.configurarBotonesModalSeleccion();
        }

        // Modal de teléfono
        const modalTelefonoElement = document.getElementById('modalTelefono');
        if (modalTelefonoElement) {
            this.modalTelefono = new bootstrap.Modal(modalTelefonoElement, {
                backdrop: 'static',
                keyboard: false
            });
            this.configurarFormularioTelefono();
        }

        // Prellenar el campo de teléfono si existe en localStorage
        if (this.telefonoGuardado && document.getElementById('inputTelefono')) {
            document.getElementById('inputTelefono').value = this.telefonoGuardado;
        }
    }

    /**
     * Configura los botones del modal de selección rápida
     */
    configurarBotonesModalSeleccion() {
        // Botón para abrir modal
        const btnSeleccionRapida = document.getElementById('btnSeleccionRapida');
        if (btnSeleccionRapida) {
            btnSeleccionRapida.addEventListener('click', (e) => {
                e.preventDefault();
                this.modalSeleccionRapida.show();
            });
        }

        // Botones de cantidades predefinidas
        document.querySelectorAll('.modal-quick-select').forEach(btn => {
            btn.addEventListener('click', () => {
                const cantidad = parseInt(btn.dataset.cantidad);
                this.boletosApp.seleccionarRapida(cantidad);
                this.modalSeleccionRapida.hide();
            });
        });

        // Botón cantidad personalizada
        const btnModalPersonalizado = document.getElementById('btnModalPersonalizado');
        if (btnModalPersonalizado) {
            btnModalPersonalizado.addEventListener('click', () => {
                const cantidad = parseInt(document.getElementById('modalCantidadPersonalizada').value);
                if (!isNaN(cantidad) && cantidad > 0) {
                    this.boletosApp.seleccionarRapida(cantidad);
                    this.modalSeleccionRapida.hide();
                }
            });
        }

        // Botón selección aleatoria
        const btnModalAleatorio = document.getElementById('btnModalAleatorio');
        if (btnModalAleatorio) {
            btnModalAleatorio.addEventListener('click', () => {
                const cantidad = parseInt(document.getElementById('modalCantidadPersonalizada').value);
                if (!isNaN(cantidad) && cantidad > 0) {
                    this.boletosApp.seleccionarAleatorio(cantidad);
                    this.modalSeleccionRapida.hide();
                }
            });
        }
    }

    /**
     * Configura el formulario de teléfono
     */
    configurarFormularioTelefono() {
        const formTelefono = document.getElementById('formTelefono');
        if (formTelefono) {
            formTelefono.addEventListener('submit', (e) => {
                e.preventDefault();
                const telefono = document.getElementById('inputTelefono').value.trim();

                // Validar formato del teléfono (10 dígitos)
                if (!/^\d{10}$/.test(telefono)) {
                    alert('Por favor ingresa un número de teléfono válido de 10 dígitos.');
                    return;
                }

                // Guardar teléfono en localStorage para futuras visitas
                localStorage.setItem('telefono_cliente', telefono);
                this.telefonoGuardado = telefono;

                // Ocultar modal
                this.modalTelefono.hide();

                // Proceder con la compra incluyendo el teléfono
                this.procesarCompraTelefono(telefono);
            });
        }
    }

    /**
     * Configura las herramientas avanzadas
     */
    configurarHerramientasAvanzadas() {
        const btnHerramientas = document.getElementById('btnHerramientasAvanzadas');
        const panelHerramientas = document.getElementById('herramientasAvanzadasPanel');
        const btnCerrarHerramientas = document.getElementById('btnCerrarHerramientas');
        const backdrop = document.getElementById('herramientasBackdrop');

        if (btnHerramientas && panelHerramientas && backdrop) {
            // Abrir panel
            btnHerramientas.addEventListener('click', () => {
                panelHerramientas.classList.add('active');
                backdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            // Cerrar panel
            const cerrarPanel = () => {
                panelHerramientas.classList.remove('active');
                backdrop.classList.remove('active');
                document.body.style.overflow = '';
            };

            if (btnCerrarHerramientas) {
                btnCerrarHerramientas.addEventListener('click', cerrarPanel);
            }

            if (backdrop) {
                backdrop.addEventListener('click', cerrarPanel);
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && panelHerramientas.classList.contains('active')) {
                    cerrarPanel();
                }
            });
        }

        this.configurarBotonesHerramientas();
    }

    /**
     * Configura los botones de las herramientas avanzadas
     */
    configurarBotonesHerramientas() {
        // Botones de terminación numérica
        document.querySelectorAll('.number-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const numero = parseInt(btn.dataset.number);
                this.boletosApp.seleccionarPorTerminacion(numero);
            });
        });

        // Selección por rango
        const btnSeleccionarRango = document.getElementById('btnSeleccionarRango');
        const rangoInicio = document.getElementById('rangoInicio');
        const rangoFin = document.getElementById('rangoFin');

        if (btnSeleccionarRango && rangoInicio && rangoFin) {
            btnSeleccionarRango.addEventListener('click', () => {
                const inicio = parseInt(rangoInicio.value);
                const fin = parseInt(rangoFin.value);
                if (!isNaN(inicio) && !isNaN(fin) && inicio > 0 && fin > 0) {
                    this.boletosApp.seleccionarPorRango(inicio, fin);
                }
            });
        }

        // Botones de selección rápida
        const btnSeleccionar10 = document.getElementById('btnSeleccionar10');
        const btnSeleccionar25 = document.getElementById('btnSeleccionar25');
        const btnSeleccionar50 = document.getElementById('btnSeleccionar50');
        const btnSeleccionar100 = document.getElementById('btnSeleccionar100');

        if (btnSeleccionar10) btnSeleccionar10.addEventListener('click', () => this.boletosApp.seleccionarRapida(10));
        if (btnSeleccionar25) btnSeleccionar25.addEventListener('click', () => this.boletosApp.seleccionarRapida(25));
        if (btnSeleccionar50) btnSeleccionar50.addEventListener('click', () => this.boletosApp.seleccionarRapida(50));
        if (btnSeleccionar100) btnSeleccionar100.addEventListener('click', () => this.boletosApp.seleccionarRapida(100));

        // Selección aleatoria
        const btnSeleccionarAleatorio = document.getElementById('btnSeleccionarAleatorio');
        const cantidadAleatoria = document.getElementById('cantidadAleatoria');

        if (btnSeleccionarAleatorio && cantidadAleatoria) {
            btnSeleccionarAleatorio.addEventListener('click', () => {
                const cantidad = parseInt(cantidadAleatoria.value);
                if (!isNaN(cantidad) && cantidad > 0) {
                    this.boletosApp.seleccionarAleatorio(cantidad);
                }
            });
        }

        // Limpiar selección
        const btnLimpiarSeleccion = document.getElementById('btnLimpiarSeleccion');
        if (btnLimpiarSeleccion) {
            btnLimpiarSeleccion.addEventListener('click', () => this.boletosApp.limpiarSeleccion());
        }
    }

    /**
     * Configura la selección rápida
     */
    configurarSeleccionRapida() {
        const btnSeleccionRapida = document.getElementById('btnSeleccionRapida');
        if (btnSeleccionRapida && this.modalSeleccionRapida) {
            btnSeleccionRapida.addEventListener('click', () => {
                this.modalSeleccionRapida.show();
            });
        }
    }

    /**
     * Mejora el proceso de pago
     */
    mejorarProcesoPago() {
        // Guardar referencia al método original
        const procederAlPagoOriginal = this.boletosApp.procederAlPago;

        // Reemplazar con versión mejorada
        this.boletosApp.procederAlPago = () => {
            if (this.boletosApp.boletosSeleccionados.length === 0) return;

            // Mostrar indicador de carga
            this.boletosApp.setLoadingState(true);

            // Verificar disponibilidad antes de proceder
            fetch(`/rifas/${this.boletosApp.rifaId}/boletos-disponibles`)
                .then(response => {
                    if (!response.ok) throw new Error('Error al verificar disponibilidad');
                    return response.json();
                })
                .then(boletosDisponiblesArray => {
                    // Convertir a Set para búsquedas más eficientes
                    const boletosDisponibles = new Set(boletosDisponiblesArray);

                    // Verificar boletos seleccionados
                    const boletosNoDisponibles = this.boletosApp.boletosSeleccionados.filter(
                        numero => !boletosDisponibles.has(parseInt(numero))
                    );

                    if (boletosNoDisponibles.length > 0) {
                        // Hay boletos no disponibles
                        this.boletosApp.setLoadingState(false);

                        const mensaje = `Los siguientes boletos ya no están disponibles: ${boletosNoDisponibles.join(', ')}.\n\n¿Deseas continuar con el pago utilizando solo los boletos disponibles?`;

                        if (confirm(mensaje)) {
                            // Continuar solo con boletos disponibles
                            this.boletosApp.boletosSeleccionados = this.boletosApp.boletosSeleccionados.filter(
                                numero => boletosDisponibles.has(parseInt(numero))
                            );

                            // Actualizar la interfaz
                            this.boletosApp.actualizarSubtotal();
                            this.boletosApp.cargarBoletos();

                            // Si todavía quedan boletos seleccionados, verificamos inicio de sesión
                            if (this.boletosApp.boletosSeleccionados.length > 0) {
                                this.verificarSesionUsuario();
                            } else {
                                alert('No quedan boletos disponibles para proceder con el pago.');
                            }
                        }
                    } else {
                        // Todos los boletos están disponibles, verificamos inicio de sesión
                        this.boletosApp.setLoadingState(false);
                        this.verificarSesionUsuario();
                    }
                })
                .catch(error => {
                    console.error('Error al verificar disponibilidad:', error);
                    this.boletosApp.setLoadingState(false);

                    // Preguntar si desea continuar sin verificar
                    if (confirm('No pudimos verificar la disponibilidad actual de los boletos. ¿Deseas intentar proceder con el pago de todas formas?')) {
                        this.verificarSesionUsuario();
                    }
                });
        };

        // Guardar referencia al método original para usarlo más tarde
        this.procederAlPagoOriginal = procederAlPagoOriginal;
    }

    /**
     * Verifica si el usuario está logueado antes de proceder con el pago
     */
    verificarSesionUsuario() {
        if (this.isLoggedIn) {
            // Si está logueado, proceder normalmente
            this.procederAlPagoOriginal.call(this.boletosApp);
        } else {
            // Si hay teléfono guardado, preguntar si quiere usarlo
            if (this.telefonoGuardado && confirm(`¿Deseas usar el número ${this.telefonoGuardado} para esta compra?`)) {
                this.procesarCompraTelefono(this.telefonoGuardado);
            } else {
                // Si no está logueado o no tiene teléfono guardado, mostrar modal
                this.modalTelefono.show();
            }
        }
    }

    /**
     * Procesa la compra para un usuario no logueado incluyendo su teléfono
     * @param {string} telefono - Número de teléfono del cliente
     */
    procesarCompraTelefono(telefono) {
        // Mostrar indicador de carga
        this.boletosApp.setLoadingState(true);

        // Crear formulario para envío
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/rifas/${this.boletosApp.rifaId}/comprar-boletos`;

        // Token CSRF
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = this.boletosApp.csrfToken;

        // Boletos seleccionados
        const boletosInput = document.createElement('input');
        boletosInput.type = 'hidden';
        boletosInput.name = 'boletos_seleccionados';
        boletosInput.value = JSON.stringify(this.boletosApp.boletosSeleccionados);

        // Teléfono del cliente
        const telefonoInput = document.createElement('input');
        telefonoInput.type = 'hidden';
        telefonoInput.name = 'telefono';
        telefonoInput.value = telefono;

        // Agregar inputs al formulario
        form.appendChild(csrfInput);
        form.appendChild(boletosInput);
        form.appendChild(telefonoInput);

        // Agregar formulario al documento y enviarlo
        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Verifica la disponibilidad actual de boletos y actualiza la interfaz
     */
    verificarDisponibilidadBoletos() {
        // Mostrar indicador de carga
        this.boletosApp.setLoadingState(true);

        // Realizar petición al servidor
        fetch(`/rifas/${this.boletosApp.rifaId}/boletos-disponibles`)
            .then(response => {
                if (!response.ok) throw new Error('Error al verificar disponibilidad');
                return response.json();
            })
            .then(boletosDisponiblesArray => {
                // Convertir array a conjunto para búsquedas más rápidas
                const boletosDisponibles = new Set(boletosDisponiblesArray);

                // Verificar boletos seleccionados
                let boletosNoDisponibles = this.boletosApp.boletosSeleccionados.filter(
                    numero => !boletosDisponibles.has(parseInt(numero))
                );

                // Actualizar estado de boletos en el objeto
                for (let i = 1; i <= this.boletosApp.totalBoletos; i++) {
                    const disponible = boletosDisponibles.has(i);

                    // Crear o actualizar el objeto boleto
                    if (!this.boletosApp.boletos[i]) {
                        this.boletosApp.boletos[i] = {
                            numero: i,
                            estado: disponible ? 'disponible' : 'vendido'
                        };
                    } else {
                        this.boletosApp.boletos[i].estado = disponible ? 'disponible' : 'vendido';
                    }
                }

                // Re-clasificar boletos
                this.boletosApp.clasificarBoletos();

                // Si hay boletos seleccionados que ya no están disponibles
                if (boletosNoDisponibles.length > 0) {
                    // Eliminar boletos no disponibles de la selección
                    this.boletosApp.boletosSeleccionados = this.boletosApp.boletosSeleccionados.filter(
                        numero => boletosDisponibles.has(parseInt(numero))
                    );

                    // Mostrar alerta
                    alert(`¡Atención! Los siguientes boletos ya no están disponibles y han sido removidos de tu selección: ${boletosNoDisponibles.join(', ')}`);

                    // Actualizar subtotal
                    this.boletosApp.actualizarSubtotal();
                } else {
                    if (this.boletosApp.boletosSeleccionados.length > 0) {
                        alert('¡Verificación completada! Todos tus boletos seleccionados siguen disponibles.');
                    } else {
                        alert('¡Verificación completada! La información de disponibilidad ha sido actualizada.');
                    }
                }

                // Recargar la visualización
                this.boletosApp.cargarBoletos();
                this.boletosApp.actualizarContadorDisponibles();
            })
            .catch(error => {
                console.error('Error al verificar disponibilidad:', error);
                alert('Hubo un error al verificar la disponibilidad. Por favor, inténtalo de nuevo.');
            })
            .finally(() => {
                this.boletosApp.setLoadingState(false);
            });
    }
}

// Exportar para uso en el documento
window.BoletosAppManager = BoletosAppManager;
