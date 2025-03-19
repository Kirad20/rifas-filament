@extends('layouts.app')

@section('title', 'Selección de Boletos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-accent dark:text-primary">Selecciona tus Boletos</h1>

    <!-- Información de la rifa -->
    <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="md:w-1/3">
                <img src="{{ asset('storage/' . $rifa->imagen) }}" alt="{{ $rifa->nombre }}"
                    class="w-full h-auto rounded-lg object-cover">
            </div>
            <div class="md:w-2/3">
                <h2 class="text-2xl font-bold text-accent dark:text-primary mb-2">{{ $rifa->nombre }}</h2>
                <p class="text-text dark:text-text-light/80 mb-4">{{ $rifa->descripcion }}</p>
                <div class="flex items-center mb-4">
                    <span class="text-sm bg-primary-light/20 dark:bg-primary/20 text-accent dark:text-primary-light px-3 py-1 rounded-full">
                        Precio por boleto: <span class="font-bold">${{ number_format($rifa->precio, 2) }}</span>
                    </span>
                </div>
                <div class="flex items-center">
                    <div class="bg-primary-light/10 dark:bg-primary/10 text-text dark:text-text-light p-2 rounded flex items-center mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Fecha del sorteo: <span class="font-medium ml-1">{{ $rifa->fecha_sorteo->format('d/m/Y') }}</span>
                    </div>
                    <div class="bg-primary-light/10 dark:bg-primary/10 text-text dark:text-text-light p-2 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        Boletos disponibles: <span class="font-medium ml-1">{{ $disponibles }} / {{ $total }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Selección de boletos -->
    <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-accent dark:text-primary mb-4">Selecciona tus Boletos</h2>

        <div class="mb-6">
            <p class="text-text dark:text-text-light/80 mb-3">Haz clic en los boletos que deseas comprar.</p>

            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="inline-flex items-center">
                    <span class="w-4 h-4 bg-primary-light/10 dark:bg-primary/10 border border-primary/30 rounded-sm mr-2"></span>
                    <span class="text-sm text-text dark:text-text-light">Disponible</span>
                </span>
                <span class="inline-flex items-center">
                    <span class="w-4 h-4 bg-primary border border-primary rounded-sm mr-2"></span>
                    <span class="text-sm text-text dark:text-text-light">Seleccionado</span>
                </span>
                <span class="inline-flex items-center">
                    <span class="w-4 h-4 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-sm mr-2"></span>
                    <span class="text-sm text-text dark:text-text-light">No disponible</span>
                </span>
            </div>
        </div>

        <!-- Grid de boletos -->
        <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-2 mb-6">
            @foreach($boletos as $boleto)
                @if($boleto->disponible)
                    <div class="boleto-item bg-primary-light/10 hover:bg-primary-light/30 border border-primary/30 rounded-md text-center p-2 cursor-pointer transition-all"
                        data-id="{{ $boleto->id }}" onclick="seleccionarBoleto(this)">
                        <span class="text-text dark:text-text-light font-medium">{{ str_pad($boleto->numero, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                @else
                    <div class="bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md text-center p-2 opacity-50 cursor-not-allowed">
                        <span class="text-gray-400 dark:text-gray-500 font-medium">{{ str_pad($boleto->numero, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Acciones -->
        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <div>
                <button id="btn-aleatorio" class="bg-secondary text-text-light px-4 py-2 rounded-lg hover:bg-secondary-dark transition btn-secondary">
                    Selección aleatoria
                </button>
                <button id="btn-limpiar" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition ml-2">
                    Limpiar selección
                </button>
            </div>
            <div class="flex items-center">
                <span class="mr-4 text-text dark:text-text-light">
                    Boletos seleccionados: <span id="contador-boletos" class="font-bold text-primary">0</span>
                </span>
                <button id="btn-continuar" class="bg-primary text-text-light px-6 py-2 rounded-lg hover:bg-primary-dark transition btn-primary disabled:opacity-50 disabled:cursor-not-allowed">
                    Continuar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let boletosSeleccionados = [];
    const limiteSeleccion = {{ $limite_boletos }};

    function seleccionarBoleto(elemento) {
        const boletoId = elemento.dataset.id;

        if (elemento.classList.contains('seleccionado')) {
            // Deseleccionar
            elemento.classList.remove('seleccionado', 'bg-primary');
            elemento.classList.add('bg-primary-light/10');
            boletosSeleccionados = boletosSeleccionados.filter(id => id !== boletoId);
        } else {
            // Seleccionar si no se ha alcanzado el límite
            if (boletosSeleccionados.length < limiteSeleccion) {
                elemento.classList.add('seleccionado', 'bg-primary');
                elemento.classList.remove('bg-primary-light/10');
                boletosSeleccionados.push(boletoId);
            } else {
                alert(`Solo puedes seleccionar hasta ${limiteSeleccion} boletos.`);
                return;
            }
        }

        actualizarContador();
    }

    function actualizarContador() {
        document.getElementById('contador-boletos').textContent = boletosSeleccionados.length;
        const btnContinuar = document.getElementById('btn-continuar');

        if (boletosSeleccionados.length > 0) {
            btnContinuar.disabled = false;
        } else {
            btnContinuar.disabled = true;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btnContinuar = document.getElementById('btn-continuar');
        btnContinuar.disabled = true;

        // Botón de selección aleatoria
        document.getElementById('btn-aleatorio').addEventListener('click', function() {
            // Lógica para selección aleatoria
        });

        // Botón de limpiar selección
        document.getElementById('btn-limpiar').addEventListener('click', function() {
            document.querySelectorAll('.boleto-item.seleccionado').forEach(item => {
                item.classList.remove('seleccionado', 'bg-primary');
                item.classList.add('bg-primary-light/10');
            });

            boletosSeleccionados = [];
            actualizarContador();
        });

        // Botón de continuar
        btnContinuar.addEventListener('click', function() {
            // Enviar los boletos seleccionados al servidor
            if (boletosSeleccionados.length > 0) {
                window.location.href = `/boletos/confirmar?ids=${boletosSeleccionados.join(',')}`;
            }
        });
    });
</script>
@endpush
