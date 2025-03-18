@extends('layouts.app')

@section('title', 'Seleccionar Boletos - ' . $rifa->nombre)

@section('content')
    <div class="bg-background py-12">
        <div class="container mx-auto px-4">
            <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-lg overflow-hidden">
                <div class="p-8 bg-primary text-text-light">
                    <h1 class="text-3xl font-bold">Seleccionar Boletos - {{ $rifa->nombre }}</h1>
                    <p class="mt-2">Precio por boleto: ${{ number_format($rifa->precio_boleto, 2) }}</p>
                </div>

                <div class="p-6 border-b dark:border-gray-700">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold mb-2 text-accent dark:text-primary">Buscar boleto específico</h2>
                            <div class="flex gap-2">
                                <input type="number" id="buscar-numero"
                                    class="border-gray-300 dark:border-gray-700 dark:bg-background-dark/50 dark:text-text-light rounded-md focus:ring-primary focus:border-primary"
                                    placeholder="Ej. 123">
                                <button id="btn-buscar"
                                    class="bg-primary text-text-light px-4 py-2 rounded-md hover:bg-primary-dark transition btn-primary">Buscar</button>
                            </div>
                        </div>

                        <!-- Agregar filtros rápidos para boletos -->
                        <div class="flex space-x-2">
                            <button type="button" id="btn-seleccionar-10"
                                class="px-3 py-1 text-sm border border-primary text-accent dark:text-primary rounded-md hover:bg-primary-light/20 dark:hover:bg-primary/20">
                                Seleccionar 10
                            </button>
                            <button type="button" id="btn-seleccionar-20"
                                class="px-3 py-1 text-sm border border-primary text-accent dark:text-primary rounded-md hover:bg-primary-light/20 dark:hover:bg-primary/20">
                                Seleccionar 20
                            </button>
                            <button type="button" id="btn-limpiar"
                                class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-700 text-text dark:text-text-light rounded-md hover:bg-gray-50 dark:hover:bg-gray-800">
                                Limpiar
                            </button>
                        </div>

                        <div>
                            <span class="font-medium text-text dark:text-text-light">Boletos seleccionados: <span
                                    id="contador-seleccionados">0</span></span>
                            <p class="text-sm text-text dark:text-text-light/80">Total: $<span id="total-precio">0.00</span></p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('boletos.agregar-carrito') }}" method="POST" id="form-seleccion">
                    @csrf
                    <input type="hidden" name="rifa_id" value="{{ $rifa->id }}">

                    <div class="p-6 max-h-96 overflow-y-auto">
                        <div class="flex justify-between mb-4">
                            <h2 class="text-lg font-bold text-accent dark:text-primary">Boletos disponibles</h2>
                            <div>
                                <button type="button" id="btn-aleatorio" class="text-primary hover:underline">Selección
                                    aleatoria</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-2" id="contenedor-boletos">
                            @foreach ($boletosDisponibles as $boleto)
                                <div class="boleto-item" data-numero="{{ $boleto->numero }}">
                                    <input type="checkbox" name="boletos[]" value="{{ $boleto->id }}"
                                        id="boleto-{{ $boleto->id }}" class="hidden checkbox-boleto">
                                    <label for="boleto-{{ $boleto->id }}"
                                        class="block border border-gray-300 dark:border-gray-700 rounded-md py-2 px-3 text-center cursor-pointer hover:bg-primary-light/10 dark:hover:bg-primary/10 transition text-text dark:text-text-light">
                                        {{ $boleto->numero }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-6 bg-background dark:bg-background-dark/50 border-t dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <p class="text-text dark:text-text-light/80">Los boletos seleccionados se reservarán por 15 minutos</p>
                            <button type="submit"
                                class="bg-primary text-text-light px-6 py-3 rounded-md hover:bg-primary-dark transition font-medium btn-primary"
                                id="btn-agregar-carrito" disabled>
                                Agregar al carrito
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.checkbox-boleto');
            const contador = document.getElementById('contador-seleccionados');
            const totalPrecio = document.getElementById('total-precio');
            const btnAgregar = document.getElementById('btn-agregar-carrito');
            const precioBoleto = {{ $rifa->precio_boleto }};

            // Función para actualizar contadores
            function actualizarContadores() {
                const seleccionados = document.querySelectorAll('.checkbox-boleto:checked').length;
                contador.textContent = seleccionados;
                totalPrecio.textContent = (seleccionados * precioBoleto).toFixed(2);

                btnAgregar.disabled = seleccionados === 0;
            }

            // Event listener para checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const label = this.nextElementSibling;
                    if (this.checked) {
                        label.classList.add('bg-primary', 'border-primary', 'text-text-light');
                    } else {
                        label.classList.remove('bg-primary', 'border-primary', 'text-text-light');
                    }
                    actualizarContadores();
                });
            });

            // Buscar boleto específico
            document.getElementById('btn-buscar').addEventListener('click', function() {
                const numero = document.getElementById('buscar-numero').value;
                if (numero) {
                    const items = document.querySelectorAll('.boleto-item');
                    let encontrado = false;

                    items.forEach(item => {
                        item.classList.remove('destacado');
                        if (item.dataset.numero === numero) {
                            item.classList.add('destacado');
                            item.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            encontrado = true;
                        }
                    });

                    if (!encontrado) {
                        alert('Número de boleto no disponible');
                    }
                }
            });

            // Selección aleatoria
            document.getElementById('btn-aleatorio').addEventListener('click', function() {
                const disponibles = Array.from(checkboxes).filter(cb => !cb.checked);

                if (disponibles.length > 0) {
                    const aleatorio = Math.floor(Math.random() * disponibles.length);
                    disponibles[aleatorio].checked = true;
                    disponibles[aleatorio].nextElementSibling.classList.add('bg-primary', 'border-primary', 'text-text-light');
                    actualizarContadores();
                    disponibles[aleatorio].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    alert('No hay boletos disponibles');
                }
            });

            // Seleccionar múltiples boletos
            document.getElementById('btn-seleccionar-10').addEventListener('click', function() {
                seleccionarMultiples(10);
            });

            document.getElementById('btn-seleccionar-20').addEventListener('click', function() {
                seleccionarMultiples(20);
            });

            document.getElementById('btn-limpiar').addEventListener('click', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.nextElementSibling.classList.remove('bg-primary', 'border-primary', 'text-text-light');
                });
                actualizarContadores();
            });

            function seleccionarMultiples(cantidad) {
                // Desmarcar todos primero
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.nextElementSibling.classList.remove('bg-primary', 'border-primary', 'text-text-light');
                });

                const disponibles = Array.from(checkboxes);
                const seleccionar = Math.min(cantidad, disponibles.length);

                // Generar índices aleatorios únicos
                const indices = [];
                while (indices.length < seleccionar) {
                    const indice = Math.floor(Math.random() * disponibles.length);
                    if (!indices.includes(indice)) {
                        indices.push(indice);
                    }
                }

                // Marcar los boletos seleccionados
                indices.forEach(indice => {
                    disponibles[indice].checked = true;
                    disponibles[indice].nextElementSibling.classList.add('bg-primary', 'border-primary', 'text-text-light');
                });

                actualizarContadores();

                // Scroll al primer boleto seleccionado
                if (indices.length > 0) {
                    disponibles[indices[0]].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    </script>

    <style>
        .destacado label {
            animation: highlight 2s ease-in-out;
            border-color: var(--primary-color);
            background-color: var(--primary-light);
            opacity: 0.7;
        }

        @keyframes highlight {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
    </style>
@endsection
