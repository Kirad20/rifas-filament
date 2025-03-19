@extends('layouts.app')

@section('title', 'Seleccionar boletos - ' . $rifa->nombre)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rifas.index') }}">Rifas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rifas.show', $rifa->id) }}">{{ $rifa->nombre }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Seleccionar boletos</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-md-6">
            <img src="{{ $rifa->getFirstMediaUrl('portada') ?: asset('img/placeholder.jpg') }}"
                alt="{{ $rifa->nombre }}" class="img-fluid rounded mb-3">
            <h1>{{ $rifa->nombre }}</h1>
            <div class="rifa-precio mb-2">${{ number_format($rifa->precio, 2) }} MXN por boleto</div>
            <div class="rifa-progreso mb-2">
                <div class="rifa-progreso-barra" style="width: {{ $rifa->porcentaje_vendido }}%"></div>
            </div>
            <p class="rifa-vendidos mb-3">{{ $rifa->boletos_vendidos }}/{{ $rifa->boletos_totales }} boletos vendidos</p>
            <p><i class="far fa-calendar-alt me-2"></i>Fecha del sorteo: {{ \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('d/m/Y H:i') }}</p>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title h4">Resumen de compra</h2>
                    <div id="resumen-container">
                        <p class="mb-1">Boletos seleccionados: <span id="contador-boletos">0</span></p>
                        <p class="mb-3">Subtotal: <strong>$<span id="subtotal">0.00</span> MXN</strong></p>
                        <div id="boletos-seleccionados-container" class="mb-3">
                            <p>No has seleccionado boletos</p>
                        </div>
                        <button id="btn-comprar" class="btn btn-primario w-100" disabled>
                            Proceder a comprar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-light">
            <ul class="nav nav-tabs card-header-tabs" id="metodos-seleccion" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="individual-tab" data-bs-toggle="tab" data-bs-target="#individual" type="button" role="tab" aria-controls="individual" aria-selected="true">
                        Selección individual
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rango-tab" data-bs-toggle="tab" data-bs-target="#rango" type="button" role="tab" aria-controls="rango" aria-selected="false">
                        Selección por rango
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="aleatorio-tab" data-bs-toggle="tab" data-bs-target="#aleatorio" type="button" role="tab" aria-controls="aleatorio" aria-selected="false">
                        Selección aleatoria
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content">
            <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                <h3 class="h5 mb-3">Selección individual de boletos</h3>
                <p class="text-muted mb-4">Haz clic en los números que deseas para agregarlos a tu selección.</p>

                <div class="mb-4">
                    <div class="input-group input-group-special">
                        <input type="number" id="buscar-boleto" class="form-control" placeholder="Buscar número específico"
                            min="1" max="{{ $rifa->boletos_totales }}">
                        <button class="btn btn-secundario" id="btn-buscar-boleto">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                        <div class="input-help w-100">
                            Ingresa el número exacto que estás buscando y presiona buscar para localizarlo rápidamente
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <!-- Leyenda mejorada -->
                    <div class="boletos-leyenda">
                        <div class="leyenda-item">
                            <span class="leyenda-color leyenda-disponible"></span>
                            <span>Disponible</span>
                        </div>
                        <div class="leyenda-item">
                            <span class="leyenda-color leyenda-no-disponible"></span>
                            <span>No disponible</span>
                        </div>
                        <div class="leyenda-item">
                            <span class="leyenda-color leyenda-seleccionado"></span>
                            <span>Seleccionado</span>
                        </div>
                    </div>

                    <!-- Navegación mejorada -->
                    <div class="page-navigation">
                        <button id="prev-page" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-chevron-left me-1"></i> Anterior
                        </button>
                        <span class="page-status">Página <span id="current-page">1</span> de <span id="total-pages">N</span></span>
                        <button id="next-page" class="btn btn-sm btn-outline-secondary">
                            Siguiente <i class="fas fa-chevron-right ms-1"></i>
                        </button>
                    </div>

                    <div id="boletos-container" class="boletos-grid">
                        <!-- Los boletos se cargarán dinámicamente aquí -->
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando boletos...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="rango" role="tabpanel" aria-labelledby="rango-tab">
                <h3 class="h5 mb-3">Selección por rango</h3>
                <p class="text-muted mb-4">Selecciona un grupo de boletos consecutivos introduciendo el número inicial y final.</p>

                <div class="input-group-special mb-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="rango-inicio" class="form-label">Desde el número</label>
                            <input type="number" class="form-control" id="rango-inicio"
                                min="1" max="{{ $rifa->boletos_totales }}" placeholder="Ej: 1">
                            <div class="input-help">Número inicial del rango</div>
                        </div>
                        <div class="col-md-5">
                            <label for="rango-fin" class="form-label">Hasta el número</label>
                            <input type="number" class="form-control" id="rango-fin"
                                min="1" max="{{ $rifa->boletos_totales }}" placeholder="Ej: 10">
                            <div class="input-help">Número final del rango</div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label d-block">&nbsp;</label>
                            <button id="btn-seleccionar-rango" class="btn btn-secundario w-100">
                                <i class="fas fa-check-circle me-1"></i> Seleccionar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Al seleccionar un rango, se agregarán automáticamente todos los boletos disponibles dentro de ese rango.
                    Los boletos que no estén disponibles serán ignorados.
                </div>

                <div id="rango-resultado" class="result-section">
                    <!-- Aquí se mostrará el resultado de la selección por rango -->
                </div>
            </div>

            <div class="tab-pane fade" id="aleatorio" role="tabpanel" aria-labelledby="aleatorio-tab">
                <h3 class="h5 mb-3">Selección aleatoria</h3>
                <p class="text-muted mb-4">Deja que el sistema seleccione boletos aleatorios para ti. ¡Prueba tu suerte!</p>

                <div class="input-group-special mb-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="cantidad-aleatoria" class="form-label">¿Cuántos boletos aleatorios deseas?</label>
                            <input type="number" class="form-control" id="cantidad-aleatoria" min="1" max="100" value="1">
                            <div class="input-help">Puedes seleccionar hasta 100 boletos aleatorios a la vez</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label d-block">&nbsp;</label>
                            <button id="btn-seleccion-aleatoria" class="btn btn-secundario w-100">
                                <i class="fas fa-random me-1"></i> Generar números
                            </button>
                        </div>
                    </div>
                </div>

                <div id="aleatorio-resultado" class="result-section">
                    <!-- Aquí se mostrará el resultado de la selección aleatoria -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmación de compra -->
    <div class="modal fade" id="confirmarCompraModal" tabindex="-1" aria-labelledby="confirmarCompraModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarCompraModalLabel">Confirmar selección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('rifas.comprar', $rifa->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Has seleccionado <strong><span id="modal-cantidad">0</span> boletos</strong> por un total de <strong>$<span id="modal-total">0.00</span> MXN</strong></p>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Ingresa tu número de teléfono:</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono"
                                required pattern="[0-9]{10}"
                                placeholder="10 dígitos, sin espacios ni guiones">
                            <div class="form-text">Necesitamos tu número para asociar los boletos a tu compra</div>
                        </div>

                        <input type="hidden" name="boletos_seleccionados" id="input-boletos-seleccionados">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primario">Agregar al carrito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.boletos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
    gap: 8px;
    margin-bottom: 20px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: var(--border-radius);
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
}

.boleto-item {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    background-color: #fff;
    border: 1px solid var(--color-success);
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    position: relative;
    overflow: hidden;
}

.boleto-item:hover:not(.no-disponible) {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.boleto-item.disponible {
    background-color: #f0f5e6;
    color: var(--color-text-dark);
    font-weight: 500;
}

.boleto-item.disponible::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background-color: var(--color-success);
}

.boleto-item.no-disponible {
    background-color: #ffebee;
    border-color: var(--color-danger);
    color: var(--color-danger);
    cursor: not-allowed;
    position: relative;
}

.boleto-item.no-disponible::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    border-top: 1px solid var(--color-danger);
}

.boleto-item.seleccionado {
    background-color: var(--color-primary);
    border-color: var(--color-primary-dark);
    color: var(--color-text-dark);
    font-weight: bold;
    transform: scale(1.05);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
    z-index: 2;
}

.boleto-item.seleccionado::before {
    content: "✓";
    position: absolute;
    top: 2px;
    right: 3px;
    font-size: 8px;
    color: var(--color-accent);
}

.boleto-item.highlight {
    animation: highlight-pulse 1.5s ease;
}

@keyframes highlight-pulse {
    0% { box-shadow: 0 0 0 0 rgba(var(--color-primary), 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(var(--color-primary), 0); }
    100% { box-shadow: 0 0 0 0 rgba(var(--color-primary), 0); }
}

#boletos-seleccionados-container {
    max-height: 200px;
    overflow-y: auto;
    background-color: #f9f9f9;
    border-radius: var(--border-radius);
    padding: 15px;
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
}

.chip-boleto {
    display: inline-block;
    background-color: var(--color-primary-light);
    color: var(--color-accent);
    padding: 6px 10px;
    border-radius: 16px;
    margin: 0 6px 6px 0;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
}

.chip-boleto:hover {
    background-color: var(--color-primary);
}

.chip-boleto .remove-boleto {
    margin-left: 5px;
    cursor: pointer;
    color: var(--color-accent);
    font-weight: bold;
    font-size: 0.9rem;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.chip-boleto .remove-boleto:hover {
    opacity: 1;
}

.nav-tabs {
    border-bottom: 2px solid var(--color-border);
}

.nav-tabs .nav-link {
    color: var(--color-text-dark);
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    border-radius: 0;
    padding: 10px 15px;
    font-weight: 600;
    transition: all 0.3s;
}

.nav-tabs .nav-link:hover {
    color: var(--color-primary);
    border-color: transparent;
}

.nav-tabs .nav-link.active {
    color: var(--color-primary);
    font-weight: 600;
    background: none;
    border-bottom: 3px solid var(--color-primary);
}

.tab-pane {
    padding: 20px 0;
}

/* Estilos para controles de página */
.page-navigation {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: white;
    padding: 8px 12px;
    border-radius: var(--border-radius);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 15px;
}

.page-navigation .page-status {
    font-size: 0.9rem;
    color: var(--color-text-muted);
    font-weight: 500;
}

.page-navigation .btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.85rem;
}

/* Leyenda de estados */
.boletos-leyenda {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 15px;
}

.leyenda-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
}

.leyenda-color {
    width: 14px;
    height: 14px;
    border-radius: 3px;
}

.leyenda-disponible {
    background-color: #f0f5e6;
    border: 1px solid var(--color-success);
}

.leyenda-no-disponible {
    background-color: #ffebee;
    border: 1px solid var(--color-danger);
}

.leyenda-seleccionado {
    background-color: var(--color-primary);
    border: 1px solid var(--color-primary-dark);
}

/* Estilos para secciones de rango y aleatorio */
.input-group-special {
    position: relative;
    border-radius: var(--border-radius);
    background-color: white;
    padding: 20px;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
}

.input-help {
    font-size: 0.8rem;
    color: var(--color-text-muted);
    margin-top: 5px;
}

/* Mejoras al resultado de rango y aleatorio */
.result-section {
    margin: 20px 0;
    padding: 15px;
    border-radius: var(--border-radius);
    background-color: #f9f9f9;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== VARIABLES GLOBALES =====
    const BOLETOS_POR_PAGINA = 500;
    const PRECIO_BOLETO = {{ $rifa->precio }};
    const TOTAL_BOLETOS = {{ $rifa->boletos_totales }};
    let paginaActual = 1;
    let totalPaginas = Math.ceil(TOTAL_BOLETOS / BOLETOS_POR_PAGINA);
    let boletosDisponibles = [];
    let boletosSeleccionados = [];

    // ===== ELEMENTOS DOM =====
    const boletosContainer = document.getElementById('boletos-container');
    const contadorBoletos = document.getElementById('contador-boletos');
    const subtotalElement = document.getElementById('subtotal');
    const btnComprar = document.getElementById('btn-comprar');
    const boletosSeleccionadosContainer = document.getElementById('boletos-seleccionados-container');
    const currentPage = document.getElementById('current-page');
    const totalPages = document.getElementById('total-pages');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const inputBoletosSeleccionados = document.getElementById('input-boletos-seleccionados');

    const modalCantidad = document.getElementById('modal-cantidad');
    const modalTotal = document.getElementById('modal-total');

    // ===== INICIALIZACIÓN =====
    // Cargar la información de todos los boletos disponibles
    fetch('{{ route('rifas.boletos.disponibles', $rifa->id) }}')
        .then(response => response.json())
        .then(data => {
            boletosDisponibles = data;
            totalPages.textContent = totalPaginas;
            cargarBoletosEnPagina(paginaActual);
        })
        .catch(error => {
            console.error('Error cargando boletos:', error);
            boletosContainer.innerHTML = '<div class="alert alert-danger">Error al cargar los boletos. Por favor, intenta de nuevo más tarde.</div>';
        });

    // ===== EVENT LISTENERS =====
    // Navegación de páginas
    prevPageBtn.addEventListener('click', () => {
        if (paginaActual > 1) {
            paginaActual--;
            cargarBoletosEnPagina(paginaActual);
        }
    });

    nextPageBtn.addEventListener('click', () => {
        if (paginaActual < totalPaginas) {
            paginaActual++;
            cargarBoletosEnPagina(paginaActual);
        }
    });

    // Búsqueda de boleto específico
    document.getElementById('btn-buscar-boleto').addEventListener('click', buscarBoleto);
    document.getElementById('buscar-boleto').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') buscarBoleto();
    });

    // Selección por rango
    document.getElementById('btn-seleccionar-rango').addEventListener('click', seleccionarRango);

    // Selección aleatoria
    document.getElementById('btn-seleccion-aleatoria').addEventListener('click', seleccionAleatoria);

    // Proceder a compra (modificado para solicitar teléfono)
    btnComprar.addEventListener('click', () => {
        modalCantidad.textContent = boletosSeleccionados.length;
        modalTotal.textContent = (boletosSeleccionados.length * PRECIO_BOLETO).toFixed(2);
        inputBoletosSeleccionados.value = JSON.stringify(boletosSeleccionados);

        new bootstrap.Modal(document.getElementById('confirmarCompraModal')).show();
    });

    // ===== FUNCIONES =====
    // Cargar boletos en la página actual
    function cargarBoletosEnPagina(pagina) {
        const inicio = (pagina - 1) * BOLETOS_POR_PAGINA;
        const fin = Math.min(inicio + BOLETOS_POR_PAGINA, TOTAL_BOLETOS);

        boletosContainer.innerHTML = '';
        currentPage.textContent = pagina;

        for (let i = inicio; i < fin; i++) {
            const numeroBoleto = i + 1; // Los boletos comienzan en 1
            const estaBoleto = boletosDisponibles.includes(numeroBoleto);
            const estaSeleccionado = boletosSeleccionados.includes(numeroBoleto);

            const boletoElement = document.createElement('div');
            boletoElement.className = `boleto-item ${estaBoleto ? 'disponible' : 'no-disponible'} ${estaSeleccionado ? 'seleccionado' : ''}`;
            boletoElement.textContent = numeroBoleto;
            boletoElement.dataset.numero = numeroBoleto;

            if (estaBoleto && !estaSeleccionado) {
                boletoElement.addEventListener('click', () => seleccionarBoleto(numeroBoleto, boletoElement));
            } else if (estaSeleccionado) {
                boletoElement.addEventListener('click', () => deseleccionarBoleto(numeroBoleto, boletoElement));
            }

            boletosContainer.appendChild(boletoElement);
        }
    }

    // Seleccionar un boleto
    function seleccionarBoleto(numero, elemento) {
        if (!boletosSeleccionados.includes(numero) && boletosDisponibles.includes(numero)) {
            boletosSeleccionados.push(numero);
            elemento.classList.add('seleccionado');
            actualizarResumen();
        }
    }

    // Deseleccionar un boleto
    function deseleccionarBoleto(numero, elemento) {
        const index = boletosSeleccionados.indexOf(numero);
        if (index !== -1) {
            boletosSeleccionados.splice(index, 1);
            elemento.classList.remove('seleccionado');
            actualizarResumen();
        }
    }

    // Actualizar resumen
    function actualizarResumen() {
        contadorBoletos.textContent = boletosSeleccionados.length;
        subtotalElement.textContent = (boletosSeleccionados.length * PRECIO_BOLETO).toFixed(2);

        if (boletosSeleccionados.length > 0) {
            boletosSeleccionadosContainer.innerHTML = '';

            // Ordenar los boletos numéricamente
            const sortedBoletos = [...boletosSeleccionados].sort((a, b) => a - b);

            sortedBoletos.forEach(numero => {
                const chip = document.createElement('span');
                chip.className = 'chip-boleto';
                chip.innerHTML = `${numero} <span class="remove-boleto" data-numero="${numero}">&times;</span>`;
                boletosSeleccionadosContainer.appendChild(chip);
            });

            // Evento para quitar boletos del resumen
            document.querySelectorAll('.remove-boleto').forEach(btn => {
                btn.addEventListener('click', function() {
                    const numero = parseInt(this.dataset.numero);
                    deseleccionarBoleto(numero);

                    // Actualizar el elemento visual en la grilla de boletos si está visible
                    const boletoElement = document.querySelector(`.boleto-item[data-numero="${numero}"]`);
                    if (boletoElement) {
                        boletoElement.classList.remove('seleccionado');
                    }

                    actualizarResumen();
                });
            });

            btnComprar.disabled = false;
        } else {
            boletosSeleccionadosContainer.innerHTML = '<p>No has seleccionado boletos</p>';
            btnComprar.disabled = true;
        }
    }

    // Función de búsqueda de boleto
    function buscarBoleto() {
        const numeroBuscado = parseInt(document.getElementById('buscar-boleto').value);

        if (!numeroBuscado || isNaN(numeroBuscado) || numeroBuscado < 1 || numeroBuscado > TOTAL_BOLETOS) {
            alert('Por favor, introduce un número de boleto válido');
            return;
        }

        const pagina = Math.ceil(numeroBuscado / BOLETOS_POR_PAGINA);
        if (pagina !== paginaActual) {
            paginaActual = pagina;
            cargarBoletosEnPagina(paginaActual);
        }

        // Resaltar el boleto buscado
        setTimeout(() => {
            const boletoElement = document.querySelector(`.boleto-item[data-numero="${numeroBuscado}"]`);
            if (boletoElement) {
                boletoElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                boletoElement.classList.add('highlight');
                setTimeout(() => boletoElement.classList.remove('highlight'), 2000);
            }
        }, 100);
    }

    // Selección por rango
    function seleccionarRango() {
        const inicio = parseInt(document.getElementById('rango-inicio').value);
        const fin = parseInt(document.getElementById('rango-fin').value);
        const resultadoContainer = document.getElementById('rango-resultado');

        if (isNaN(inicio) || isNaN(fin) || inicio < 1 || fin > TOTAL_BOLETOS || inicio > fin) {
            resultadoContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Por favor, introduce un rango válido. El inicio debe ser menor que el fin y ambos deben estar entre 1 y ${TOTAL_BOLETOS}.
                </div>
            `;
            return;
        }

        // Encontrar qué boletos están disponibles en el rango
        const disponiblesEnRango = [];
        const noDisponiblesEnRango = [];

        for (let i = inicio; i <= fin; i++) {
            if (boletosDisponibles.includes(i)) {
                disponiblesEnRango.push(i);
            } else {
                noDisponiblesEnRango.push(i);
            }
        }

        // Agregar boletos disponibles a la selección
        disponiblesEnRango.forEach(numero => {
            if (!boletosSeleccionados.includes(numero)) {
                boletosSeleccionados.push(numero);
            }
        });

        // Actualizar resumen
        actualizarResumen();

        // Actualizar vista de boletos si está en la página actual
        if (paginaActual >= Math.ceil(inicio / BOLETOS_POR_PAGINA) &&
            paginaActual <= Math.ceil(fin / BOLETOS_POR_PAGINA)) {
            cargarBoletosEnPagina(paginaActual);
        }

        // Mostrar resultados
        resultadoContainer.innerHTML = `
            <div class="alert ${disponiblesEnRango.length > 0 ? 'alert-success' : 'alert-warning'}">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Resultado de la selección:</strong><br>
                - Boletos disponibles seleccionados: ${disponiblesEnRango.length}<br>
                ${noDisponiblesEnRango.length > 0 ? `- Boletos no disponibles en el rango: ${noDisponiblesEnRango.length} (${noDisponiblesEnRango.slice(0, 10).join(', ')}${noDisponiblesEnRango.length > 10 ? '...' : ''})` : ''}
            </div>
        `;
    }

    // Selección aleatoria
    function seleccionAleatoria() {
        const cantidad = parseInt(document.getElementById('cantidad-aleatoria').value);
        const resultadoContainer = document.getElementById('aleatorio-resultado');

        if (isNaN(cantidad) || cantidad < 1 || cantidad > 100) {
            resultadoContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Por favor, introduce una cantidad válida entre 1 y 100.
                </div>
            `;
            return;
        }

        // Filtrar boletos disponibles que no hayan sido seleccionados aún
        const disponiblesParaAleatoria = boletosDisponibles.filter(num => !boletosSeleccionados.includes(num));

        if (disponiblesParaAleatoria.length === 0) {
            resultadoContainer.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No hay boletos disponibles para selección aleatoria.
                </div>
            `;
            return;
        }

        if (cantidad > disponiblesParaAleatoria.length) {
            resultadoContainer.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Solo hay ${disponiblesParaAleatoria.length} boletos disponibles para selección aleatoria.
                </div>
            `;
            return;
        }

        // Seleccionar aleatoriamente
        const seleccionados = [];
        const copiaDisponibles = [...disponiblesParaAleatoria];

        for (let i = 0; i < cantidad; i++) {
            const randomIndex = Math.floor(Math.random() * copiaDisponibles.length);
            const numeroBoleto = copiaDisponibles.splice(randomIndex, 1)[0];
            seleccionados.push(numeroBoleto);
            boletosSeleccionados.push(numeroBoleto);
        }

        // Actualizar resumen
        actualizarResumen();

        // Si la vista actual contiene alguno de estos boletos, actualizar
        const minBoleto = Math.min(...seleccionados);
        const maxBoleto = Math.max(...seleccionados);

        if ((minBoleto >= (paginaActual - 1) * BOLETOS_POR_PAGINA + 1) &&
            (maxBoleto <= paginaActual * BOLETOS_POR_PAGINA)) {
            cargarBoletosEnPagina(paginaActual);
        }

        // Mostrar resultados
        resultadoContainer.innerHTML = `
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Boletos aleatorios seleccionados!</strong><br>
                Se han seleccionado ${seleccionados.length} boletos: ${seleccionados.sort((a, b) => a - b).slice(0, 20).join(', ')}${seleccionados.length > 20 ? '...' : ''}
            </div>
        `;
    }
});
</script>
@endpush
