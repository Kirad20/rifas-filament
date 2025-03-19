@extends('layouts.app')

@section('title', 'Selección de Boletos - ' . $rifa->nombre)

@section('content')
<div class="full-width-wrapper">
    <div class="container mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rifas.index') }}">Rifas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rifas.show', $rifa->id) }}">{{ $rifa->nombre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Seleccionar Boletos</li>
            </ol>
        </nav>
    </div>

    <div class="container mb-4">
        <h1>Selecciona tus boletos</h1>
    </div>

    <div class="full-width-card-container">
        <div class="card w-100 mb-4">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $rifa->nombre }}</h5>
                    <div class="badge bg-primary">${{ number_format($rifa->precio_boleto, 2) }} MXN por boleto</div>
                </div>
            </div>
            <div class="card-body">
                <div class="custom-row">
                    <!-- Panel de selección de boletos (lado izquierdo) -->
                    <div class="custom-col-9">
                        <!-- Buscador de boletos -->
                        <div class="mb-4">
                            <div class="input-group">
                                <input type="text" id="buscarBoleto" class="form-control" placeholder="Buscar boleto por número...">
                                <button class="btn btn-outline-secondary" type="button" id="btnBuscar">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                            <div class="form-text">Ingresa un número específico de boleto para encontrarlo rápidamente</div>
                        </div>

                        <!-- Filtros y paginación -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="mostrarTodos">Todos</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="mostrarDisponibles">Disponibles</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="mostrarSeleccionados">Mis seleccionados</button>
                            </div>

                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-secondary btn-sm" id="paginaAnterior">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="btn btn-outline-secondary btn-sm disabled" id="paginaActual">Página 1</span>
                                <button class="btn btn-outline-secondary btn-sm" id="paginaSiguiente">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Grid de números de boletos -->
                        <div class="boletos-grid" id="boletosGrid">
                            <!-- Se llenará dinámicamente con JavaScript -->
                        </div>

                        <div class="mt-3 text-center">
                            <p><span class="boleto-ejemplo disponible"></span> Disponible
                            <span class="ms-3 boleto-ejemplo vendido"></span> Vendido
                            <span class="ms-3 boleto-ejemplo seleccionado"></span> Seleccionado</p>
                        </div>
                    </div>

                    <!-- Panel de boletos seleccionados (lado derecho) -->
                    <div class="custom-col-3">
                        <div id="subtotalPanel">
                            <h5 class="mb-3">Boletos seleccionados</h5>

                            <div id="boletosSeleccionados" class="mb-3">
                                <p class="text-muted text-center" id="mensajeVacio">No has seleccionado ningún boleto</p>
                                <!-- Lista de boletos seleccionados -->
                                <ul class="list-group list-group-flush rounded-0" id="listaBoletos">
                                    <!-- Se llenará dinámicamente -->
                                </ul>
                            </div>

                            <div class="subtotal-info mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Precio por boleto:</span>
                                    <strong>${{ number_format($rifa->precio_boleto, 2) }} MXN</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Cantidad de boletos:</span>
                                    <strong id="cantidadBoletos">0</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Subtotal:</span>
                                    <strong id="subtotalPrecio">$0.00 MXN</strong>
                                </div>

                                <button id="btnProcederPago" class="btn btn-primario w-100" disabled>
                                    Proceder al pago
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Solución al problema del ancho limitado */
    .full-width-wrapper {
        width: 100%;
        max-width: none;
        padding: 20px 0;
    }

    .full-width-card-container {
        width: 95%;
        margin: 0 auto;
    }

    /* Layout personalizado para reemplazar row/col con problemas */
    .custom-row {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        margin: 0;
    }

    .custom-col-9 {
        width: 75%;
        padding-right: 15px;
    }

    .custom-col-3 {
        width: 25%;
        padding-left: 15px;
        border-left: 1px solid #dee2e6;
    }

    /* Grid de boletos responsivo y ampliado */
    .boletos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
        gap: 8px;
        min-height: 500px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 5px;
        width: 100%;
    }

    /* Ajustes responsivos */
    @media (max-width: 992px) {
        .custom-col-9, .custom-col-3 {
            width: 100%;
            padding: 0;
        }

        .custom-col-3 {
            border-left: none;
            border-top: 1px solid #dee2e6;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
        }

        .full-width-card-container {
            width: 90%;
        }
    }

    /* Ajustes para utilizar el ancho completo de la pantalla */
    .container-fluid {
        width: 100%;
        max-width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    @media (min-width: 1600px) {
        .container-fluid {
            padding-right: 30px;
            padding-left: 30px;
        }
    }

    /* Grid de boletos responsivo y ampliado */
    .boletos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
        gap: 8px;
        min-height: 500px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 5px;
        width: 100%;
    }

    /* Asegurar que la tarjeta utiliza todo el ancho disponible */
    .card {
        width: 100%;
    }

    /* Resto de estilos específicos de boletos */
    .boleto-item {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        background-color: #fff;
        border: 1px solid var(--color-primary);
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: bold;
    }

    .boleto-item.disponible:hover {
        background-color: var(--color-primary);
        color: white;
    }

    .boleto-item.vendido {
        background-color: #e5e7eb;
        border-color: #d1d5db;
        color: #9ca3af;
        cursor: not-allowed;
        position: relative;
    }

    .boleto-item.vendido::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        border-top: 1px solid #9ca3af;
    }

    .boleto-item.seleccionado {
        background-color: var(--color-primary);
        color: white;
    }

    .boleto-ejemplo {
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 5px;
        vertical-align: middle;
        border-radius: 3px;
    }

    .boleto-ejemplo.disponible {
        background-color: #fff;
        border: 1px solid var(--color-primary);
    }

    .boleto-ejemplo.vendido {
        background-color: #e5e7eb;
        border: 1px solid #d1d5db;
        position: relative;
    }

    .boleto-ejemplo.vendido::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        border-top: 1px solid #9ca3af;
    }

    .boleto-ejemplo.seleccionado {
        background-color: var(--color-primary);
        border: 1px solid var(--color-primary);
    }

    #listaBoletos {
        max-height: 300px;
        overflow-y: auto;
    }

    #listaBoletos .list-group-item {
        padding: 0.5rem;
        border-left: 0;
        border-right: 0;
        border-radius: 0;
    }

    /* Añadir estilo para botones de filtro activos */
    .btn-outline-secondary.active {
        background-color: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
    }

    /* Estilos responsivos para modo móvil */
    @media (max-width: 992px) {
        .col-lg-9, .col-lg-3 {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
        }

        .col-lg-3 {
            border-left: none !important;
            border-top: 1px solid #dee2e6;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables globales
        const precio = {{ $rifa->precio_boleto }};
        const totalBoletos = {{ $rifa->total_boletos ?? 100 }}; // Valor predeterminado si no está definido

        // Obtener los datos de los boletos desde PHP
        const boletos = {!! json_encode($boletos->keyBy('numero')->all()) !!};

        // Crear array de boletos no disponibles (vendidos o reservados)
        const boletosNoDisponibles = [];
        const numerosDisponibles = [];

        // Procesar la colección de boletos
        Object.values(boletos).forEach(boleto => {
            if (boleto.estado !== 'disponible') {
                boletosNoDisponibles.push(boleto.numero);
            } else {
                numerosDisponibles.push(boleto.numero);
            }
        });

        let boletosSeleccionados = [];
        let paginaActual = 1;
        const boletosPorPagina = 100;
        let mostrarSoloDisponibles = false;

        // Elementos DOM
        const gridBoletos = document.getElementById('boletosGrid');
        const listaBoletos = document.getElementById('listaBoletos');
        const cantidadBoletos = document.getElementById('cantidadBoletos');
        const subtotalPrecio = document.getElementById('subtotalPrecio');
        const btnProcederPago = document.getElementById('btnProcederPago');
        const mensajeVacio = document.getElementById('mensajeVacio');
        const btnBuscar = document.getElementById('btnBuscar');
        const inputBuscar = document.getElementById('buscarBoleto');
        const btnMostrarTodos = document.getElementById('mostrarTodos');
        const btnMostrarDisponibles = document.getElementById('mostrarDisponibles');

        console.log("Información de boletos:");
        console.log("- Total boletos de la rifa: ", totalBoletos);
        console.log("- Boletos no disponibles: ", boletosNoDisponibles.length);
        console.log("- Boletos disponibles: ", numerosDisponibles.length);

        // Agregar mensaje de depuración visible (remover en producción)
        const debugInfo = document.createElement('div');
        debugInfo.className = 'alert alert-info mb-3 small';
        debugInfo.innerHTML = `<strong>Información de boletos:</strong><br>
            Total boletos de la rifa: ${totalBoletos}<br>
            Boletos no disponibles: ${boletosNoDisponibles.length}<br>
            Boletos disponibles: ${numerosDisponibles.length}`;
        gridBoletos.parentNode.insertBefore(debugInfo, gridBoletos);

        // Asegurarse de que el grid tenga dimensiones visibles
        gridBoletos.style.minHeight = '200px';

        // Botones de filtrado - añadir clase activa
        btnMostrarTodos.classList.add('active');

        // Cargar boletos iniciales - Forzar la creación de todos los boletos
        cargarTodosLosBoletos();

        // Event listeners
        btnBuscar.addEventListener('click', buscarBoleto);
        inputBuscar.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                buscarBoleto();
            }
        });

        document.getElementById('mostrarTodos').addEventListener('click', function() {
            paginaActual = 1;
            mostrarSoloDisponibles = false;
            btnMostrarTodos.classList.add('active');
            btnMostrarDisponibles.classList.remove('active');
            cargarBoletos();
        });

        document.getElementById('mostrarDisponibles').addEventListener('click', function() {
            paginaActual = 1;
            mostrarSoloDisponibles = true;
            btnMostrarDisponibles.classList.add('active');
            btnMostrarTodos.classList.remove('active');
            cargarBoletos();
        });

        document.getElementById('mostrarSeleccionados').addEventListener('click', mostrarSeleccionados);

        document.getElementById('paginaAnterior').addEventListener('click', function() {
            if (paginaActual > 1) {
                paginaActual--;
                cargarBoletos();
            }
        });

        document.getElementById('paginaSiguiente').addEventListener('click', function() {
            const totalPaginas = Math.ceil(totalBoletos / boletosPorPagina);
            if (paginaActual < totalPaginas) {
                paginaActual++;
                cargarBoletos();
            }
        });

        // Función para cargar todos los boletos iniciales
        function cargarTodosLosBoletos() {
            console.log("Forzando carga de todos los boletos...");

            // Mostrar un mensaje de carga
            gridBoletos.innerHTML = '<div class="alert alert-secondary w-100 text-center">Cargando boletos...</div>';

            // Usar setTimeout para no bloquear la interfaz
            setTimeout(() => {
                cargarBoletos();
            }, 100);
        }

        // Funciones
        function cargarBoletos() {
            console.log("Iniciando carga de boletos...");
            gridBoletos.innerHTML = '';
            document.getElementById('paginaActual').textContent = `Página ${paginaActual}`;

            const inicio = (paginaActual - 1) * boletosPorPagina + 1;
            const fin = Math.min(paginaActual * boletosPorPagina, totalBoletos);

            console.log(`Cargando boletos desde ${inicio} hasta ${fin}`);

            // Verificar que hay boletos para mostrar
            if (totalBoletos <= 0) {
                gridBoletos.innerHTML = '<div class="alert alert-warning w-100 text-center">No hay boletos definidos para esta rifa</div>';
                return;
            }

            let contadorBoletos = 0;
            let contadorDisponibles = 0;
            let contadorNoDisponibles = 0;

            // Crear un fragmento para mejorar rendimiento
            const fragment = document.createDocumentFragment();

            for (let i = inicio; i <= fin; i++) {
                const boleto = boletos[i] || { numero: i, estado: 'disponible' };
                const estaDisponible = boleto.estado === 'disponible';

                // Si estamos mostrando solo disponibles y no está disponible, saltamos este boleto
                if (mostrarSoloDisponibles && !estaDisponible) {
                    continue;
                }

                contadorBoletos++;
                if (estaDisponible) {
                    contadorDisponibles++;
                } else {
                    contadorNoDisponibles++;
                }

                const estaSeleccionado = boletosSeleccionados.includes(i);

                const boletoDom = document.createElement('div');
                boletoDom.className = `boleto-item ${estaDisponible ? 'disponible' : 'vendido'} ${estaSeleccionado ? 'seleccionado' : ''}`;
                boletoDom.textContent = i;
                boletoDom.dataset.numero = i;

                if (estaDisponible) {
                    boletoDom.addEventListener('click', function() {
                        toggleSeleccionBoleto(i);
                    });
                }

                fragment.appendChild(boletoDom);
            }

            // Agregar todos los boletos al grid de una vez
            gridBoletos.appendChild(fragment);

            console.log(`Se generaron ${contadorBoletos} boletos (${contadorDisponibles} disponibles, ${contadorNoDisponibles} no disponibles)`);

            // Verificar si se generaron boletos
            if (gridBoletos.children.length === 0) {
                let mensaje = 'No se encontraron boletos en este rango';
                if (mostrarSoloDisponibles) {
                    mensaje = 'No hay boletos disponibles en este rango';
                }
                gridBoletos.innerHTML = `<div class="alert alert-info w-100 text-center">${mensaje}</div>`;
            }

            // Actualizar contador total/disponibles
            const infoContador = document.createElement('div');
            infoContador.className = 'mt-3 text-end';
            infoContador.innerHTML = `<small>Mostrando ${contadorBoletos} boletos. Total disponibles: ${numerosDisponibles.length}/${totalBoletos}</small>`;

            // Eliminar contador anterior si existe
            const contadorAnterior = gridBoletos.parentNode.querySelector('.mt-3.text-end');
            if (contadorAnterior) {
                contadorAnterior.remove();
            }

            gridBoletos.parentNode.appendChild(infoContador);
        }

        function toggleSeleccionBoleto(numero) {
            const index = boletosSeleccionados.indexOf(numero);
            const boletoDom = document.querySelector(`.boleto-item[data-numero="${numero}"]`);

            if (index === -1) {
                // Agregar selección
                boletosSeleccionados.push(numero);
                if (boletoDom) boletoDom.classList.add('seleccionado');
            } else {
                // Quitar selección
                boletosSeleccionados.splice(index, 1);
                if (boletoDom) boletoDom.classList.remove('seleccionado');
            }

            actualizarSubtotal();
        }

        function actualizarSubtotal() {
            // Actualizar la lista de boletos seleccionados
            listaBoletos.innerHTML = '';

            if (boletosSeleccionados.length === 0) {
                mensajeVacio.style.display = 'block';
                btnProcederPago.disabled = true;
            } else {
                mensajeVacio.style.display = 'none';
                btnProcederPago.disabled = false;

                // Ordenar los boletos numéricamente
                boletosSeleccionados.sort((a, b) => a - b);

                boletosSeleccionados.forEach(numero => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center';

                    const numSpan = document.createElement('span');
                    numSpan.textContent = `Boleto #${numero}`;

                    const btnEliminar = document.createElement('button');
                    btnEliminar.className = 'btn btn-sm text-danger';
                    btnEliminar.innerHTML = '<i class="fas fa-times"></i>';
                    btnEliminar.addEventListener('click', function() {
                        toggleSeleccionBoleto(numero);
                    });

                    item.appendChild(numSpan);
                    item.appendChild(btnEliminar);
                    listaBoletos.appendChild(item);
                });
            }

            // Actualizar cantidad y subtotal
            cantidadBoletos.textContent = boletosSeleccionados.length;
            const subtotal = boletosSeleccionados.length * precio;
            subtotalPrecio.textContent = `$${subtotal.toFixed(2)} MXN`;
        }

        function buscarBoleto() {
            const numeroBuscado = parseInt(inputBuscar.value);

            if (!isNaN(numeroBuscado) && numeroBuscado >= 1 && numeroBuscado <= totalBoletos) {
                // Calcular la página donde está el boleto
                paginaActual = Math.ceil(numeroBuscado / boletosPorPagina);
                cargarBoletos();

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
                alert('Por favor ingresa un número de boleto válido entre 1 y ' + totalBoletos);
            }
        }

        function mostrarSeleccionados() {
            if (boletosSeleccionados.length === 0) {
                alert('No has seleccionado ningún boleto aún');
                return;
            }

            gridBoletos.innerHTML = '';

            boletosSeleccionados.forEach(numero => {
                const boletoDom = document.createElement('div');
                boletoDom.className = 'boleto-item disponible seleccionado';
                boletoDom.textContent = numero;
                boletoDom.dataset.numero = numero;

                boletoDom.addEventListener('click', function() {
                    toggleSeleccionBoleto(numero);
                });

                gridBoletos.appendChild(boletoDom);
            });
        }

        // Botón proceder al pago
        btnProcederPago.addEventListener('click', function() {
            if (boletosSeleccionados.length > 0) {
                // Verificar que todos los boletos seleccionados estén disponibles
                const boletosNoValidos = boletosSeleccionados.filter(numero => {
                    const boleto = boletos[numero] || { estado: 'disponible' };
                    return boleto.estado !== 'disponible';
                });

                if (boletosNoValidos.length > 0) {
                    alert(`Los siguientes boletos ya no están disponibles: ${boletosNoValidos.join(', ')}`);
                    return;
                }

                // Enviar formulario
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('rifas.procesar-compra') }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const rifaInput = document.createElement('input');
                rifaInput.type = 'hidden';
                rifaInput.name = 'rifa_id';
                rifaInput.value = {{ $rifa->id }};

                const boletosInput = document.createElement('input');
                boletosInput.type = 'hidden';
                boletosInput.name = 'boletos';
                boletosInput.value = JSON.stringify(boletosSeleccionados);

                form.appendChild(csrfToken);
                form.appendChild(rifaInput);
                form.appendChild(boletosInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    });
</script>
@endsection
