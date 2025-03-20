/**
 * Módulo de selección de boletos para rifas
 */
class BoletosSelector {
    constructor(options) {
        // Configuración y datos
        this.precio = options.precio;
        this.totalBoletos = options.totalBoletos;
        this.boletos = options.boletos;
        this.boletosPorPagina = options.boletosPorPagina || 100;
        this.rifaId = options.rifaId;
        this.csrfToken = options.csrfToken;
        this.formAction = options.formAction;

        // Estado
        this.boletosSeleccionados = [];
        this.paginaActual = 1;
        this.mostrarSoloDisponibles = false;

        // Arrays de boletos clasificados
        this.boletosNoDisponibles = [];
        this.numerosDisponibles = [];

        // Inicializar
        this.init();
    }

    init() {
        // Clasificar boletos
        this.clasificarBoletos();

        // Configurar elementos DOM
        this.configurarDOM();

        // Configurar event listeners
        this.configurarEventListeners();

        // Mostrar información de depuración
        this.mostrarDebugInfo();

        // Cargar boletos iniciales
        this.cargarTodosLosBoletos();
    }

    clasificarBoletos() {
        Object.values(this.boletos).forEach(boleto => {
            if (boleto.estado !== 'disponible') {
                this.boletosNoDisponibles.push(boleto.numero);
            } else {
                this.numerosDisponibles.push(boleto.numero);
            }
        });
    }

    configurarDOM() {
        // Elementos principales
        this.gridBoletos = document.getElementById('boletosGrid');
        this.listaBoletos = document.getElementById('listaBoletos');
        this.cantidadBoletos = document.getElementById('cantidadBoletos');
        this.subtotalPrecio = document.getElementById('subtotalPrecio');
        this.btnProcederPago = document.getElementById('btnProcederPago');
        this.mensajeVacio = document.getElementById('mensajeVacio');
        this.btnBuscar = document.getElementById('btnBuscar');
        this.inputBuscar = document.getElementById('buscarBoleto');

        // Botones de filtro
        this.btnMostrarTodos = document.getElementById('mostrarTodos');
        this.btnMostrarDisponibles = document.getElementById('mostrarDisponibles');
        this.btnMostrarSeleccionados = document.getElementById('mostrarSeleccionados');

        // Paginación
        this.btnPaginaAnterior = document.getElementById('paginaAnterior');
        this.btnPaginaSiguiente = document.getElementById('paginaSiguiente');
        this.paginadorActual = document.getElementById('paginaActual');

        // Selección avanzada - actualizar para usar los selectores de los modales
        this.btnSeleccionarRango = document.getElementById('btnSeleccionarRango');
        this.rangoInicio = document.getElementById('rangoInicio');
        this.rangoFin = document.getElementById('rangoFin');
        this.btnSeleccionar10 = document.getElementById('btnSeleccionar10');
        this.btnSeleccionar25 = document.getElementById('btnSeleccionar25');
        this.btnSeleccionar50 = document.getElementById('btnSeleccionar50');
        this.btnSeleccionar100 = document.getElementById('btnSeleccionar100');
        this.btnSeleccionarAleatorio = document.getElementById('btnSeleccionarAleatorio');
        this.cantidadAleatoria = document.getElementById('cantidadAleatoria');
        this.btnSeleccionarTerminacion = document.getElementById('btnSeleccionarTerminacion');
        this.terminacion = document.getElementById('terminacion');
        this.btnLimpiarSeleccion = document.getElementById('btnLimpiarSeleccion');

        // Configuración inicial
        this.btnMostrarTodos.classList.add('active');
    }

    configurarEventListeners() {
        // Búsqueda
        this.btnBuscar.addEventListener('click', () => this.buscarBoleto());
        this.inputBuscar.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.buscarBoleto();
        });

        // Filtros
        this.btnMostrarTodos.addEventListener('click', () => {
            this.paginaActual = 1;
            this.mostrarSoloDisponibles = false;
            this.btnMostrarTodos.classList.add('active');
            this.btnMostrarDisponibles.classList.remove('active');
            this.cargarBoletos();
        });

        this.btnMostrarDisponibles.addEventListener('click', () => {
            this.paginaActual = 1;
            this.mostrarSoloDisponibles = true;
            this.btnMostrarDisponibles.classList.add('active');
            this.btnMostrarTodos.classList.remove('active');
            this.cargarBoletos();
        });

        this.btnMostrarSeleccionados.addEventListener('click', () => this.mostrarSeleccionados());

        // Paginación
        this.btnPaginaAnterior.addEventListener('click', () => {
            if (this.paginaActual > 1) {
                this.paginaActual--;
                this.cargarBoletos();
            }
        });

        this.btnPaginaSiguiente.addEventListener('click', () => {
            const totalPaginas = Math.ceil(this.totalBoletos / this.boletosPorPagina);
            if (this.paginaActual < totalPaginas) {
                this.paginaActual++;
                this.cargarBoletos();
            }
        });

        // Selección avanzada
        this.btnSeleccionarRango.addEventListener('click', () => {
            const inicio = parseInt(this.rangoInicio.value);
            const fin = parseInt(this.rangoFin.value);

            if (isNaN(inicio) || isNaN(fin) || inicio < 1 ||
                fin > this.totalBoletos || inicio > fin) {
                alert(`Por favor ingrese un rango válido entre 1 y ${this.totalBoletos}`);
                return;
            }

            this.seleccionarPorRango(inicio, fin);
        });

        // Botones de selección rápida
        this.btnSeleccionar10.addEventListener('click', () => this.seleccionarRapida(10));
        this.btnSeleccionar25.addEventListener('click', () => this.seleccionarRapida(25));
        this.btnSeleccionar50.addEventListener('click', () => this.seleccionarRapida(50));
        this.btnSeleccionar100.addEventListener('click', () => this.seleccionarRapida(100));

        // Selección aleatoria
        this.btnSeleccionarAleatorio.addEventListener('click', () => {
            const cantidad = parseInt(this.cantidadAleatoria.value);

            if (isNaN(cantidad) || cantidad < 1 || cantidad > 100) {
                alert('Por favor ingrese una cantidad válida entre 1 y 100');
                return;
            }

            this.seleccionarAleatorio(cantidad);
        });

        // Selección por terminación
        this.btnSeleccionarTerminacion.addEventListener('click', () => {
            const terminacion = parseInt(this.terminacion.value);

            if (isNaN(terminacion) || terminacion < 0 || terminacion > 9) {
                alert('Por favor ingrese un dígito válido (0-9)');
                return;
            }

            this.seleccionarPorTerminacion(terminacion);
        });

        // Limpiar selección
        this.btnLimpiarSeleccion.addEventListener('click', () => this.limpiarSeleccion());

        // Proceder al pago
        this.btnProcederPago.addEventListener('click', () => this.procederAlPago());
    }

    mostrarDebugInfo() {
        console.log("Información de boletos:");
        console.log("- Total boletos de la rifa: ", this.totalBoletos);
        console.log("- Boletos no disponibles: ", this.boletosNoDisponibles.length);
        console.log("- Boletos disponibles: ", this.numerosDisponibles.length);

        // Agregar mensaje de depuración visible
        const debugInfo = document.createElement('div');
        debugInfo.className = 'alert alert-info mb-3 small';
        debugInfo.innerHTML = `<strong>Información de boletos:</strong><br>
            Total boletos de la rifa: ${this.totalBoletos}<br>
            Boletos no disponibles: ${this.boletosNoDisponibles.length}<br>
            Boletos disponibles: ${this.numerosDisponibles.length}`;

        this.gridBoletos.parentNode.insertBefore(debugInfo, this.gridBoletos);
    }

    cargarTodosLosBoletos() {
        console.log("Forzando carga de todos los boletos...");
        this.gridBoletos.innerHTML = '<div class="alert alert-secondary w-100 text-center">Cargando boletos...</div>';

        setTimeout(() => {
            this.cargarBoletos();
        }, 100);
    }

    cargarBoletos() {
        console.log("Iniciando carga de boletos...");
        this.gridBoletos.innerHTML = '';
        this.paginadorActual.textContent = `Página ${this.paginaActual}`;

        const inicio = (this.paginaActual - 1) * this.boletosPorPagina + 1;
        const fin = Math.min(this.paginaActual * this.boletosPorPagina, this.totalBoletos);

        console.log(`Cargando boletos desde ${inicio} hasta ${fin}`);

        if (this.totalBoletos <= 0) {
            this.gridBoletos.innerHTML = '<div class="alert alert-warning w-100 text-center">No hay boletos definidos para esta rifa</div>';
            return;
        }

        let contadorBoletos = 0;
        let contadorDisponibles = 0;
        let contadorNoDisponibles = 0;
        const fragment = document.createDocumentFragment();

        for (let i = inicio; i <= fin; i++) {
            const boleto = this.boletos[i] || { numero: i, estado: 'disponible' };
            const estaDisponible = boleto.estado === 'disponible';

            if (this.mostrarSoloDisponibles && !estaDisponible) {
                continue;
            }

            contadorBoletos++;
            if (estaDisponible) {
                contadorDisponibles++;
            } else {
                contadorNoDisponibles++;
            }

            const estaSeleccionado = this.boletosSeleccionados.includes(i);
            const boletoDom = document.createElement('div');
            boletoDom.className = `boleto-item ${estaDisponible ? 'disponible' : 'vendido'} ${estaSeleccionado ? 'seleccionado' : ''}`;
            boletoDom.textContent = i;
            boletoDom.dataset.numero = i;

            if (estaDisponible) {
                boletoDom.addEventListener('click', () => {
                    this.toggleSeleccionBoleto(i);
                });
            }

            fragment.appendChild(boletoDom);
        }

        // Agregar todos los boletos al grid de una vez
        this.gridBoletos.appendChild(fragment);

        console.log(`Se generaron ${contadorBoletos} boletos (${contadorDisponibles} disponibles, ${contadorNoDisponibles} no disponibles)`);

        // Verificar si se generaron boletos
        if (this.gridBoletos.children.length === 0) {
            let mensaje = 'No se encontraron boletos en este rango';
            if (this.mostrarSoloDisponibles) {
                mensaje = 'No hay boletos disponibles en este rango';
            }
            this.gridBoletos.innerHTML = `<div class="alert alert-info w-100 text-center">${mensaje}</div>`;
        }

        // Actualizar contador total/disponibles
        const infoContador = document.createElement('div');
        infoContador.className = 'mt-3 text-end';
        infoContador.innerHTML = `<small>Mostrando ${contadorBoletos} boletos. Total disponibles: ${this.numerosDisponibles.length}/${this.totalBoletos}</small>`;

        // Eliminar contador anterior si existe
        const contadorAnterior = this.gridBoletos.parentNode.querySelector('.mt-3.text-end');
        if (contadorAnterior) {
            contadorAnterior.remove();
        }

        this.gridBoletos.parentNode.appendChild(infoContador);
    }

    toggleSeleccionBoleto(numero) {
        const index = this.boletosSeleccionados.indexOf(numero);
        const boletoDom = document.querySelector(`.boleto-item[data-numero="${numero}"]`);

        if (index === -1) {
            // Agregar selección
            this.boletosSeleccionados.push(numero);
            if (boletoDom) boletoDom.classList.add('seleccionado');
        } else {
            // Quitar selección
            this.boletosSeleccionados.splice(index, 1);
            if (boletoDom) boletoDom.classList.remove('seleccionado');
        }

        this.actualizarSubtotal();
    }

    actualizarSubtotal() {
        // Actualizar la lista de boletos seleccionados
        this.listaBoletos.innerHTML = '';

        if (this.boletosSeleccionados.length === 0) {
            this.mensajeVacio.style.display = 'block';
            this.btnProcederPago.disabled = true;
        } else {
            this.mensajeVacio.style.display = 'none';
            this.btnProcederPago.disabled = false;

            // Ordenar los boletos numéricamente
            this.boletosSeleccionados.sort((a, b) => a - b);

            this.boletosSeleccionados.forEach(numero => {
                const item = document.createElement('li');
                item.className = 'list-group-item d-flex justify-content-between align-items-center';

                const numSpan = document.createElement('span');
                numSpan.textContent = `Boleto #${numero}`;

                const btnEliminar = document.createElement('button');
                btnEliminar.className = 'btn btn-sm text-danger';
                btnEliminar.innerHTML = '<i class="fas fa-times"></i>';
                btnEliminar.addEventListener('click', () => {
                    this.toggleSeleccionBoleto(numero);
                });

                item.appendChild(numSpan);
                item.appendChild(btnEliminar);
                this.listaBoletos.appendChild(item);
            });
        }

        // Actualizar cantidad y subtotal
        this.cantidadBoletos.textContent = this.boletosSeleccionados.length;
        const subtotal = this.boletosSeleccionados.length * this.precio;
        this.subtotalPrecio.textContent = `$${subtotal.toFixed(2)} MXN`;

        // Actualizar el contador de boletos en el encabezado
        const counterElement = document.querySelector('.selection-counter');
        if (counterElement) {
            counterElement.textContent = this.boletosSeleccionados.length;
        }
    }

    buscarBoleto() {
        const numeroBuscado = parseInt(this.inputBuscar.value);

        if (!isNaN(numeroBuscado) && numeroBuscado >= 1 && numeroBuscado <= this.totalBoletos) {
            // Calcular la página donde está el boleto
            this.paginaActual = Math.ceil(numeroBuscado / this.boletosPorPagina);
            this.cargarBoletos();

            // Resaltar el boleto buscado temporalmente
            setTimeout(() => {
                const boletoBuscado = document.querySelector(`.boleto-item[data-numero="${numeroBuscado}"]`);
                if (boletoBuscado) {
                    boletoBuscado.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    boletoBuscado.classList.add('highlight');
                    setTimeout(() => {
                        boletoBuscado.classList.remove('highlight');
                    }, 2000);
                }
            }, 100);
        } else {
            alert('Por favor ingresa un número de boleto válido entre 1 y ' + this.totalBoletos);
        }
    }

    mostrarSeleccionados() {
        if (this.boletosSeleccionados.length === 0) {
            alert('No has seleccionado ningún boleto aún');
            return;
        }

        this.gridBoletos.innerHTML = '';

        this.boletosSeleccionados.forEach(numero => {
            const boletoDom = document.createElement('div');
            boletoDom.className = 'boleto-item disponible seleccionado';
            boletoDom.textContent = numero;
            boletoDom.dataset.numero = numero;

            boletoDom.addEventListener('click', () => {
                this.toggleSeleccionBoleto(numero);
            });

            this.gridBoletos.appendChild(boletoDom);
        });
    }

    procederAlPago() {
        if (this.boletosSeleccionados.length > 0) {
            // Verificar que todos los boletos seleccionados estén disponibles
            const boletosNoValidos = this.boletosSeleccionados.filter(numero => {
                const boleto = this.boletos[numero] || { estado: 'disponible' };
                return boleto.estado !== 'disponible';
            });

            if (boletosNoValidos.length > 0) {
                alert(`Los siguientes boletos ya no están disponibles: ${boletosNoValidos.join(', ')}`);
                return;
            }

            // Enviar formulario
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.formAction;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = this.csrfToken;

            const rifaInput = document.createElement('input');
            rifaInput.type = 'hidden';
            rifaInput.name = 'rifa_id';
            rifaInput.value = this.rifaId;

            const boletosInput = document.createElement('input');
            boletosInput.type = 'hidden';
            boletosInput.name = 'boletos';
            boletosInput.value = JSON.stringify(this.boletosSeleccionados);

            form.appendChild(csrfToken);
            form.appendChild(rifaInput);
            form.appendChild(boletosInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Funciones de selección avanzada que AGREGAN a la selección existente
    seleccionarPorRango(inicio, fin) {
        let boletosAgregados = 0;
        for (let i = inicio; i <= fin; i++) {
            const boleto = this.boletos[i] || { numero: i, estado: 'disponible' };
            if (boleto.estado === 'disponible' && !this.boletosSeleccionados.includes(i)) {
                this.boletosSeleccionados.push(i);
                boletosAgregados++;
            }
        }

        // Recargar los boletos para mostrar la selección
        this.cargarBoletos();
        this.actualizarSubtotal();

        alert(`Se han agregado ${boletosAgregados} boletos disponibles a tu selección.`);
    }

    seleccionarRapida(cantidad) {
        // Generar lista de boletos disponibles
        let disponibles = [];
        for (let i = 1; i <= this.totalBoletos; i++) {
            const boleto = this.boletos[i] || { numero: i, estado: 'disponible' };
            if (boleto.estado === 'disponible' && !this.boletosSeleccionados.includes(i)) {
                disponibles.push(i);
            }
        }

        // Verificar si hay suficientes boletos
        if (disponibles.length < cantidad) {
            alert(`Solo hay ${disponibles.length} boletos disponibles para seleccionar.`);
            cantidad = disponibles.length;
        }

        // Seleccionar boletos consecutivos desde el inicio
        for (let i = 0; i < cantidad && i < disponibles.length; i++) {
            this.boletosSeleccionados.push(disponibles[i]);
        }

        // Recargar para mostrar selección
        this.cargarBoletos();
        this.actualizarSubtotal();

        alert(`Se han agregado ${cantidad} boletos a tu selección.`);
    }

    seleccionarAleatorio(cantidad) {
        // Generar lista de boletos disponibles
        let disponibles = [];
        for (let i = 1; i <= this.totalBoletos; i++) {
            const boleto = this.boletos[i] || { numero: i, estado: 'disponible' };
            if (boleto.estado === 'disponible' && !this.boletosSeleccionados.includes(i)) {
                disponibles.push(i);
            }
        }

        // Verificar si hay suficientes boletos
        if (disponibles.length < cantidad) {
            alert(`Solo hay ${disponibles.length} boletos disponibles para seleccionar aleatoriamente.`);
            cantidad = disponibles.length;
        }

        // Mezclar aleatoriamente y seleccionar
        disponibles = disponibles.sort(() => Math.random() - 0.5);
        for (let i = 0; i < cantidad && i < disponibles.length; i++) {
            this.boletosSeleccionados.push(disponibles[i]);
        }

        // Recargar para mostrar selección
        this.cargarBoletos();
        this.actualizarSubtotal();

        alert(`Se han agregado ${cantidad} boletos aleatorios a tu selección.`);
    }

    seleccionarPorTerminacion(digito) {
        // Generar lista de boletos que terminan en el dígito especificado
        let boletosTerminacion = [];
        for (let i = 1; i <= this.totalBoletos; i++) {
            if (i % 10 === digito) {
                const boleto = this.boletos[i] || { numero: i, estado: 'disponible' };
                if (boleto.estado === 'disponible' && !this.boletosSeleccionados.includes(i)) {
                    boletosTerminacion.push(i);
                }
            }
        }

        // Verificar si hay boletos
        if (boletosTerminacion.length === 0) {
            alert(`No hay boletos disponibles terminados en ${digito}.`);
            return;
        }

        // Agregar los boletos
        let boletosAgregados = 0;
        for (let numero of boletosTerminacion) {
            if (!this.boletosSeleccionados.includes(numero)) {
                this.boletosSeleccionados.push(numero);
                boletosAgregados++;
            }
        }

        // Recargar para mostrar selección
        this.cargarBoletos();
        this.actualizarSubtotal();

        alert(`Se han agregado ${boletosAgregados} boletos terminados en ${digito}.`);
    }

    limpiarSeleccion() {
        if (this.boletosSeleccionados.length === 0) {
            alert('No hay boletos seleccionados para limpiar.');
            return;
        }

        if (confirm('¿Estás seguro de que deseas eliminar todos los boletos seleccionados?')) {
            this.boletosSeleccionados = [];
            this.cargarBoletos();
            this.actualizarSubtotal();
            alert('Se ha limpiado tu selección de boletos.');
        }
    }
}

// Exportar para uso en el documento
window.BoletosSelector = BoletosSelector;
