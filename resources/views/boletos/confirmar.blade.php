@extends('layouts.app')

@section('title', 'Confirmar Boletos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-accent dark:text-primary">Confirma tus Boletos</h1>

    <!-- Resumen de selección -->
    <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold text-accent dark:text-primary mb-4">Resumen de tu selección</h2>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <div class="flex items-start mb-4">
                    <div class="bg-primary-light/20 dark:bg-primary/20 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-accent dark:text-primary-light">Rifa</h3>
                        <p class="text-text dark:text-text-light">{{ $rifa->nombre }}</p>
                    </div>
                </div>

                <div class="flex items-start mb-4">
                    <div class="bg-primary-light/20 dark:bg-primary/20 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-accent dark:text-primary-light">Fecha del Sorteo</h3>
                        <p class="text-text dark:text-text-light">{{ $rifa->fecha_sorteo->format('d/m/Y H:i') }} hrs</p>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-start mb-4">
                    <div class="bg-primary-light/20 dark:bg-primary/20 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-accent dark:text-primary-light">Precio por Boleto</h3>
                        <p class="text-text dark:text-text-light">${{ number_format($rifa->precio, 2) }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="bg-primary-light/20 dark:bg-primary/20 p-2 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-accent dark:text-primary-light">Total a Pagar</h3>
                        <p class="text-primary text-xl font-bold">${{ number_format($totalPagar, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Boletos seleccionados -->
    <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold text-accent dark:text-primary mb-4">Boletos Seleccionados</h2>

        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($boletosSeleccionados as $boleto)
                <div class="bg-primary text-text-light px-3 py-2 rounded-lg font-medium">
                    {{ str_pad($boleto->numero, 2, '0', STR_PAD_LEFT) }}
                </div>
            @endforeach
        </div>
    </div>

    <!-- Formulario de datos de comprador -->
    <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold text-accent dark:text-primary mb-4">Datos del Comprador</h2>

        <form action="{{ route('boletos.guardar') }}" method="POST" id="form-comprador">
            @csrf
            <input type="hidden" name="boletos_ids" value="{{ $boletosIds }}">
            <input type="hidden" name="rifa_id" value="{{ $rifa->id }}">

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-text dark:text-text-light mb-1">Nombre completo</label>
                        <input type="text" id="nombre" name="nombre" class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        @error('nombre')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-text dark:text-text-light mb-1">Correo electrónico</label>
                        <input type="email" id="email" name="email" class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="telefono" class="block text-sm font-medium text-text dark:text-text-light mb-1">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        @error('telefono')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="flex items-start">
                            <input type="checkbox" id="terminos" name="terminos" class="mt-1 text-primary focus:ring-primary">
                            <label for="terminos" class="ml-2 text-sm text-text dark:text-text-light">
                                Acepto los <a href="#" class="text-primary hover:underline">términos y condiciones</a> y la <a href="#" class="text-primary hover:underline">política de privacidad</a>.
                            </label>
                        </div>
                        @error('terminos')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <a href="{{ route('boletos.index', $rifa->id) }}" class="text-primary hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Regresar a selección
                </a>
                <button type="submit" class="bg-primary text-text-light px-6 py-2 rounded-lg hover:bg-primary-dark transition btn-primary">
                    Proceder al pago
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
