/**
 * Módulo de selección de boletos para rifas
 * @author Desarrollador
 * @version 2.1
 */
class BoletosSelector {
    /**
     * Constructor de la clase
     * @param {Object} options - Configuración del selector
     */
    constructor(options) {
        // Propiedades de configuración
        this.precio = options.precio || 0;
        this.totalBoletos = options.totalBoletos || 0;
        this.boletos = options.boletos || {};
        this.boletosPorPagina = options.boletosPorPagina || 100;
        this.rifaId = options.rifaId;
        this.csrfToken = options.csrfToken || '';
        this.formAction = options.formAction || '';

        // Estado del componente
        this.boletosSeleccionados = [];
        this.paginaActual = 1;
        this.mostrarSoloDisponibles = false;
        this.isLoading = false;

        // Clasificación de boletos
        this.boletosNoDisponibles = [];
        this.numerosDisponibles = [];

        console.log('BoletosSelector inicializado con', this.totalBoletos, 'boletos totales');
    }

    /**
     * Inicializa el selector de boletos
     */
    init() {
        console.log('Iniciando selector de boletos...');
        this.clasificarBoletos();
        this.configurarDOM();
        this.configurarEventListeners();
        this.cargarBoletos(); // Cargar directamente sin spinner
        this.actualizarContadorDisponibles();
    }

    /**
     * Clasifica boletos entre disponibles y no disponibles
     */
    clasificarBoletos() {
        this.boletosNoDisponibles = [];
        this.numerosDisponibles = [];

        // Procesar boletos desde el objeto boletos
        for (let numero in this.boletos) {
            const boleto = this.boletos[numero];
            if (boleto.estado !== 'disponible') {
                this.boletosNoDisponibles.push(parseInt(numero));
            } else {
                this.numerosDisponibles.push(parseInt(numero));
            }
        }

        // Llenar números disponibles que no están en el objeto boletos
        for (let i = 1; i <= this.totalBoletos; i++) {
            if (!this.boletos[i] && !this.numerosDisponibles.includes(i)) {
                this.numerosDisponibles.push(i);
            }
        }

        console.log(`Clasificación completada: ${this.numerosDisponibles.length} disponibles, ${this.boletosNoDisponibles.length} no disponibles`);
    }

    /**
     * Configura las referencias a elementos del DOM
     */
    configurarDOM() {
        console.log('Configurando referencias DOM...');
        // Elementos principales
        this.gridBoletos = document.getElementById('boletosGrid');
        this.listaBoletos = document.getElementById('listaBoletos');
        this.cantidadBoletos = document.getElementById('cantidadBoletos');
        this.subtotalPrecio = document.getElementById('subtotalPrecio');
        this.btnProcederPago = document.getElementById('btnProcederPago');
        this.mensajeVacio = document.getElementById('mensajeVacio');
        this.btnBuscar = document.getElementById('btnBuscar');
        this.inputBuscar = document.getElementById('buscarBoleto');
        this.contadorDisponibles = document.getElementById('contadorDisponibles');

        // Botones de filtro
        this.btnMostrarTodos = document.getElementById('mostrarTodos');
        this.btnMostrarDisponibles = document.getElementById('mostrarDisponibles');
        this.btnMostrarSeleccionados = document.getElementById('mostrarSeleccionados');

        // Paginación
        this.btnPaginaAnterior = document.getElementById('paginaAnterior');
        this.btnPaginaSiguiente = document.getElementById('paginaSiguiente');
        this.paginadorActual = document.getElementById('paginaActual');
        this.paginadorActualMobile = document.getElementById('paginaActualMobile');
        this.paginadorInferior = document.getElementById('paginaActualInferior');
        this.btnPaginaAnteriorInferior = document.getElementById('paginaAnteriorInferior');
        this.btnPaginaSiguienteInferior = document.getElementById('paginaSiguienteInferior');

        // Configuración inicial de filtros
        if (this.btnMostrarTodos) this.btnMostrarTodos.classList.add('active');

        // Verificar si se encontraron todos los elementos críticos
        if (!this.gridBoletos) console.error('Elemento #boletosGrid no encontrado');
    }

    /**
     * Configura los eventos básicos del selector
     */
    configurarEventListeners() {
        // Búsqueda de boletos
        if (this.btnBuscar && this.inputBuscar) {
            this.btnBuscar.addEventListener('click', () => this.buscarBoleto());
            this.inputBuscar.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.buscarBoleto();
            });
        }

        // Filtros de visualización
        if (this.btnMostrarTodos) {
            this.btnMostrarTodos.addEventListener('click', () => this.filtrarBoletos('todos'));
        }
        if (this.btnMostrarDisponibles) {
            this.btnMostrarDisponibles.addEventListener('click', () => this.filtrarBoletos('disponibles'));
        }
        if (this.btnMostrarSeleccionados) {
            this.btnMostrarSeleccionados.addEventListener('click', () => this.filtrarBoletos('seleccionados'));
        }

        // Paginación superior
        if (this.btnPaginaAnterior && this.btnPaginaSiguiente) {
            this.btnPaginaAnterior.addEventListener('click', () => this.cambiarPagina('anterior'));
            this.btnPaginaSiguiente.addEventListener('click', () => this.cambiarPagina('siguiente'));
        }

        // Paginación inferior
        if (this.btnPaginaAnteriorInferior) {
            this.btnPaginaAnteriorInferior.addEventListener('click', () => this.cambiarPagina('anterior'));
        }
        if (this.btnPaginaSiguienteInferior) {
            this.btnPaginaSiguienteInferior.addEventListener('click', () => this.cambiarPagina('siguiente'));
        }

        // Proceder al pago
        if (this.btnProcederPago) {
            this.btnProcederPago.addEventListener('click', () => this.procederAlPago());
        }
    }

    /**
     * Cambia a la página anterior o siguiente
     * @param {string} direccion - 'anterior' o 'siguiente'
     */
    cambiarPagina(direccion) {
        const paginaInicial = this.paginaActual;
        const totalPaginas = Math.ceil(this.totalBoletos / this.boletosPorPagina);

        if (direccion === 'anterior' && this.paginaActual > 1) {
            this.paginaActual--;
        } else if (direccion === 'siguiente' && this.paginaActual < totalPaginas) {
            this.paginaActual++;
        }

        // Solo recargar si realmente cambió la página
        if (paginaInicial !== this.paginaActual) {
            this.cargarBoletos();
        }
    }

    /**
     * Filtra los boletos según el modo seleccionado
     * @param {string} modo - 'todos', 'disponibles' o 'seleccionados'
     */
    filtrarBoletos(modo) {
        this.setLoadingState(true);

        setTimeout(() => {
            if (modo === 'todos') {
                this.paginaActual = 1;
                this.mostrarSoloDisponibles = false;
                this.cargarBoletos();
            } else if (modo === 'disponibles') {
                this.paginaActual = 1;
                this.mostrarSoloDisponibles = true;
                this.cargarBoletos();
            } else if (modo === 'seleccionados') {
                this.mostrarSeleccionados();
            }

            this.actualizarBotonesActivos(modo);
            this.setLoadingState(false);
        }, 300);
    }

    /**
     * Actualiza las clases active en los botones de filtro
     * @param {string} modo - El modo activo
     */
    actualizarBotonesActivos(modo) {
        this.btnMostrarTodos.classList.remove('active');
        this.btnMostrarDisponibles.classList.remove('active');
        this.btnMostrarSeleccionados.classList.remove('active');

        if (modo === 'todos') {
            this.btnMostrarTodos.classList.add('active');
        } else if (modo === 'disponibles') {
            this.btnMostrarDisponibles.classList.add('active');
        } else if (modo === 'seleccionados') {
            this.btnMostrarSeleccionados.classList.add('active');
        }
    }

    /**
     * Actualiza el contador de boletos disponibles
     */
    actualizarContadorDisponibles() {
        if (this.contadorDisponibles) {
            this.contadorDisponibles.textContent = this.numerosDisponibles.length;
        }
    }

    /**
     * Controla el estado de carga
     * @param {boolean} loading - Si está cargando
     */
    setLoadingState(loading) {
        this.isLoading = loading;
        const loaderSelector = '.grid-loading';
        const loader = document.querySelector(loaderSelector);

        if (!loader && loading) {
            const loaderDiv = document.createElement('div');
            loaderDiv.className = 'grid-loading';
            loaderDiv.innerHTML = '<div class="spinner"></div><p>Cargando boletos...</p>';
            this.gridBoletos.parentNode.appendChild(loaderDiv);
        } else if (loader && !loading) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.parentNode && loader.parentNode.removeChild(loader);
            }, 300);
        }
    }

    /**
     * Carga los boletos en el grid según la paginación
     */
    cargarBoletos() {
        if (!this.gridBoletos) {
            console.error('No se puede cargar los boletos: gridBoletos es null');
            return;
        }

        console.log('Cargando boletos para página', this.paginaActual);
        this.gridBoletos.innerHTML = '';
        this.actualizarPaginadores();

        const inicio = (this.paginaActual - 1) * this.boletosPorPagina + 1;
        const fin = Math.min(this.paginaActual * this.boletosPorPagina, this.totalBoletos);

        if (this.totalBoletos <= 0) {
            this.mostrarMensaje('No hay boletos definidos para esta rifa', 'warning');
            return;
        }

        let contadorBoletos = 0;
        let contadorDisponibles = 0;
        const fragment = document.createDocumentFragment();

        for (let i = inicio; i <= fin; i++) {
            // Determine si el boleto existe en los datos o crear uno predeterminado
            const boleto = this.boletos[i] || { numero: i, estado: 'disponible' };
            const estaDisponible = boleto.estado === 'disponible';

            if (this.mostrarSoloDisponibles && !estaDisponible) {
                continue;
            }

            contadorBoletos++;
            if (estaDisponible) contadorDisponibles++;

            const estaSeleccionado = this.boletosSeleccionados.includes(i);
            const boletoDom = this.crearElementoBoleto(i, estaDisponible, estaSeleccionado);
            fragment.appendChild(boletoDom);
        }

        this.gridBoletos.appendChild(fragment);

        if (this.gridBoletos.children.length === 0) {
            const mensaje = this.mostrarSoloDisponibles ?
                'No hay boletos disponibles en este rango' :
                'No se encontraron boletos en este rango';
            this.mostrarMensaje(mensaje, 'info');
        }

        this.actualizarEstadoPaginacion();
        this.actualizarContadorGrid(contadorBoletos);

        console.log(`Boletos cargados: ${contadorBoletos} mostrados, ${contadorDisponibles} disponibles`);
    }

    /**
     * Actualiza todos los paginadores
     */
    actualizarPaginadores() {
        const totalPaginas = Math.ceil(this.totalBoletos / this.boletosPorPagina);
        const textoPagina = `Página ${this.paginaActual} de ${totalPaginas}`;

        // Actualizar paginador superior
        if (this.paginadorActual) this.paginadorActual.textContent = textoPagina;

        // Actualizar paginador móvil
        if (this.paginadorActualMobile) this.paginadorActualMobile.textContent = textoPagina;

        // Actualizar paginador inferior
        if (this.paginadorInferior) this.paginadorInferior.textContent = textoPagina;
    }

    /**
     * Actualiza el estado habilitado/deshabilitado de los botones de paginación
     */
    actualizarEstadoPaginacion() {
        const totalPaginas = Math.ceil(this.totalBoletos / this.boletosPorPagina);

        // Paginación superior
        this.btnPaginaAnterior.disabled = this.paginaActual <= 1;
        this.btnPaginaSiguiente.disabled = this.paginaActual >= totalPaginas;

        // Paginación inferior
        if (this.btnPaginaAnteriorInferior) {
            this.btnPaginaAnteriorInferior.disabled = this.paginaActual <= 1;
        }
        if (this.btnPaginaSiguienteInferior) {
            this.btnPaginaSiguienteInferior.disabled = this.paginaActual >= totalPaginas;
        }
    }

    /**
     * Actualiza el contador de boletos mostrados
     * @param {number} contadorBoletos - Cantidad de boletos mostrados
     */
    actualizarContadorGrid(contadorBoletos) {
        const infoHTML = `<small>Mostrando ${contadorBoletos} boletos. Total disponibles: ${this.numerosDisponibles.length}/${this.totalBoletos}</small>`;

        // Eliminar contador anterior si existe
        const contadorAnterior = this.gridBoletos.parentNode.querySelector('.mt-3.text-end');
        if (contadorAnterior) {
            contadorAnterior.remove();
        }

        // Crear nuevo contador
        const infoContador = document.createElement('div');
        infoContador.className = 'mt-3 text-end';
        infoContador.innerHTML = infoHTML;
        this.gridBoletos.parentNode.appendChild(infoContador);
    }

    /**
     * Crea un elemento DOM para un boleto
     * @param {number} numero - Número de boleto
     * @param {boolean} estaDisponible - Si está disponible
     * @param {boolean} estaSeleccionado - Si está seleccionado
     * @returns {HTMLElement}
     */
    crearElementoBoleto(numero, estaDisponible, estaSeleccionado) {
        const boletoDom = document.createElement('div');
        boletoDom.className = `boleto-item ${estaDisponible ? 'disponible' : 'vendido'} ${estaSeleccionado ? 'seleccionado' : ''}`;
        boletoDom.textContent = numero;
        boletoDom.dataset.numero = numero;

        if (estaDisponible) {
            boletoDom.addEventListener('click', () => this.toggleSeleccionBoleto(numero));
        }

        return boletoDom;
    }

    /**
     * Muestra un mensaje en el grid de boletos
     * @param {string} texto - Texto del mensaje
     * @param {string} tipo - Tipo de alerta (info, warning, etc)
     */
    mostrarMensaje(texto, tipo = 'info') {
        this.gridBoletos.innerHTML = `<div class="alert alert-${tipo} w-100 text-center">${texto}</div>`;
    }

    /**
     * Muestra los boletos seleccionados
     */
    mostrarSeleccionados() {
        if (this.boletosSeleccionados.length === 0) {
            alert('No has seleccionado ningún boleto aún');
            return;
        }

        this.gridBoletos.innerHTML = '';
        const fragment = document.createDocumentFragment();

        this.boletosSeleccionados.forEach(numero => {
            const boletoDom = document.createElement('div');
            boletoDom.className = 'boleto-item disponible seleccionado';
            boletoDom.textContent = numero;
            boletoDom.dataset.numero = numero;
            boletoDom.addEventListener('click', () => this.toggleSeleccionBoleto(numero));
            fragment.appendChild(boletoDom);
        });

        this.gridBoletos.appendChild(fragment);

        // Actualizar indicadores de paginación
        this.paginadorActual.textContent = 'Seleccionados';
        if (this.paginadorActualMobile) {
            this.paginadorActualMobile.textContent = 'Seleccionados';
        }

        // Desactivar botones de paginación
        this.btnPaginaAnterior.disabled = true;
        this.btnPaginaSiguiente.disabled = true;
    }

    /**
     * Alterna la selección de un boleto
     * @param {number} numero - Número de boleto
     */
    toggleSeleccionBoleto(numero) {
        const index = this.boletosSeleccionados.indexOf(numero);
        const boletoDom = document.querySelector(`.boleto-item[data-numero="${numero}"]`);

        if (index === -1) {
            // Agregar selección
            this.boletosSeleccionados.push(numero);
            if (boletoDom) {
                boletoDom.classList.add('seleccionado');
                this.animarElemento(boletoDom, 'seleccionar');
            }
        } else {
            // Quitar selección
            this.boletosSeleccionados.splice(index, 1);
            if (boletoDom) {
                this.animarElemento(boletoDom, 'deseleccionar', () => {
                    boletoDom.classList.remove('seleccionado');
                });
            }
        }

        this.actualizarSubtotal();
    }

    /**
     * Anima un elemento con efectos CSS
     * @param {HTMLElement} elemento - Elemento a animar
     * @param {string} tipo - Tipo de animación
     * @param {Function} callback - Función a ejecutar al terminar
     */
    animarElemento(elemento, tipo, callback) {
        if (tipo === 'seleccionar') {
            elemento.animate([
                { transform: 'scale(1)' },
                { transform: 'scale(1.2)' },
                { transform: 'scale(1.05)' }
            ], {
                duration: 300,
                easing: 'ease-out'
            });
        } else if (tipo === 'deseleccionar') {
            elemento.animate([
                { transform: 'scale(1.05)' },
                { transform: 'scale(0.9)' },
                { transform: 'scale(1)' }
            ], {
                duration: 300,
                easing: 'ease-out'
            }).onfinish = callback;
        }
    }

    /**
     * Actualiza el subtotal y la lista de boletos seleccionados
     */
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
            this.boletosSeleccionados.forEach(numero => this.agregarBoletoALista(numero));
        }

        // Actualizar cantidad y subtotal
        const cantidadBoletos = this.boletosSeleccionados.length;
        const subtotal = cantidadBoletos * this.precio;

        this.cantidadBoletos.textContent = cantidadBoletos;
        this.subtotalPrecio.textContent = `$${subtotal.toFixed(2)} MXN`;
    }

    /**
     * Agrega un boleto a la lista de seleccionados
     * @param {number} numero - Número del boleto
     */
    agregarBoletoALista(numero) {
        const item = document.createElement('li');

        const numSpan = document.createElement('span');
        numSpan.textContent = `Boleto #${numero}`;

        const btnEliminar = document.createElement('button');
        btnEliminar.className = 'btn btn-sm text-danger';
        btnEliminar.innerHTML = '<i class="fas fa-times"></i>';
        btnEliminar.addEventListener('click', () => this.toggleSeleccionBoleto(numero));

        item.appendChild(numSpan);
        item.appendChild(btnEliminar);
        this.listaBoletos.appendChild(item);
    }

    /**
     * Busca y resalta un boleto específico
     */
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
                    setTimeout(() => boletoBuscado.classList.remove('highlight'), 2000);
                }
            }, 100);
        } else {
            alert(`Por favor ingresa un número de boleto válido entre 1 y ${this.totalBoletos}`);
        }
    }

    /**
     * Procede al pago con los boletos seleccionados
     */
    procederAlPago() {
        if (this.boletosSeleccionados.length === 0) return;

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

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = this.csrfToken;

        const rifaInput = document.createElement('input');
        rifaInput.type = 'hidden';
        rifaInput.name = 'rifa_id';
        rifaInput.value = this.rifaId;

        const boletosInput = document.createElement('input');
        boletosInput.type = 'hidden';
        boletosInput.name = 'boletos';
        boletosInput.value = JSON.stringify(this.boletosSeleccionados);

        form.appendChild(csrfInput);
        form.appendChild(rifaInput);
        form.appendChild(boletosInput);
        document.body.appendChild(form);
        form.submit();
    }

    // ===== Métodos para selección avanzada =====

    /**
     * Selecciona boletos por rango
     * @param {number} inicio - Número inicial
     * @param {number} fin - Número final
     */
    seleccionarPorRango(inicio, fin) {
        let boletosAgregados = 0;

        for (let i = inicio; i <= fin; i++) {
            const boleto = this.boletos[i] || { numero: i, estado: 'disponible' };
            if (boleto.estado === 'disponible' && !this.boletosSeleccionados.includes(i)) {
                this.boletosSeleccionados.push(i);
                boletosAgregados++;
            }
        }

        if (boletosAgregados > 0) {
            this.cargarBoletos();
            this.actualizarSubtotal();
            alert(`Se han agregado ${boletosAgregados} boletos disponibles a tu selección.`);
        } else {
            alert('No se encontraron boletos disponibles en el rango especificado.');
        }
    }

    /**
     * Selecciona una cantidad específica de boletos disponibles
     * @param {number} cantidad - Cantidad de boletos a seleccionar
     */
    seleccionarRapida(cantidad) {
        // Obtener boletos disponibles que no estén ya seleccionados
        const disponibles = this.obtenerBoletosDisponibles();

        // Verificar si hay suficientes boletos
        if (disponibles.length === 0) {
            alert('No hay boletos disponibles para seleccionar.');
            return;
        }

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

    /**
     * Selecciona boletos aleatorios
     * @param {number} cantidad - Cantidad de boletos a seleccionar
     */
    seleccionarAleatorio(cantidad) {
        // Obtener boletos disponibles que no estén ya seleccionados
        let disponibles = this.obtenerBoletosDisponibles();

        // Verificar si hay suficientes boletos
        if (disponibles.length === 0) {
            alert('No hay boletos disponibles para seleccionar aleatoriamente.');
            return;
        }

        if (disponibles.length < cantidad) {
            alert(`Solo hay ${disponibles.length} boletos disponibles para seleccionar aleatoriamente.`);
            cantidad = disponibles.length;
        }

        // Mezclar aleatoriamente y seleccionar
        disponibles = disponibles.sort(() => Math.random() - 0.5);
        for (let i = 0; i < cantidad; i++) {
            this.boletosSeleccionados.push(disponibles[i]);
        }

        // Recargar para mostrar selección
        this.cargarBoletos();
        this.actualizarSubtotal();
        alert(`Se han agregado ${cantidad} boletos aleatorios a tu selección.`);
    }

    /**
     * Selecciona boletos por terminación
     * @param {number} digito - Dígito de terminación (0-9)
     */
    seleccionarPorTerminacion(digito) {
        if (digito < 0 || digito > 9) {
            alert('Por favor ingrese un dígito válido del 0 al 9');
            return;
        }

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
        boletosTerminacion.forEach(numero => this.boletosSeleccionados.push(numero));

        // Recargar para mostrar selección
        this.cargarBoletos();
        this.actualizarSubtotal();
        alert(`Se han agregado ${boletosTerminacion.length} boletos terminados en ${digito}.`);
    }

    /**
     * Limpia todos los boletos seleccionados
     */
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

    /**
     * Obtiene lista de boletos disponibles no seleccionados
     * @returns {number[]} - Array con números de boletos
     */
    obtenerBoletosDisponibles() {
        const disponibles = [];

        for (let i = 1; i <= this.totalBoletos; i++) {
            const boleto = this.boletos[i] || { numero: i, estado: 'disponible' };
            if (boleto.estado === 'disponible' && !this.boletosSeleccionados.includes(i)) {
                disponibles.push(i);
            }
        }

        return disponibles;
    }
}

// Exportar para uso en el documento
window.BoletosSelector = BoletosSelector;
