@extends('layouts.app')

@section('title', 'Carrito de compra')

@section('content')
    <div class="bg-background dark:bg-background-dark py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-8 text-accent dark:text-primary">Carrito de Compras</h1>

            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Lista de boletos -->
                <div class="md:w-2/3">
                    <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6 bg-primary text-text-light">
                            <h2 class="text-xl font-bold">Boletos seleccionados</h2>
                        </div>

                        @if (count($carrito) > 0)
                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="border-b dark:border-gray-700">
                                                <th class="pb-3 font-medium text-text dark:text-text-light">Rifa</th>
                                                <th class="pb-3 font-medium text-text dark:text-text-light">Número</th>
                                                <th class="pb-3 font-medium text-text dark:text-text-light">Precio</th>
                                                <th class="pb-3 font-medium text-text dark:text-text-light">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($carrito as $index => $item)
                                                <tr class="border-b dark:border-gray-700 hover:bg-background dark:hover:bg-background-dark/50">
                                                    <td class="py-4 text-text dark:text-text-light">
                                                        {{ $item['rifa_nombre'] }}
                                                    </td>
                                                    <td class="py-4">
                                                        <span class="px-3 py-1 bg-primary-light/20 dark:bg-primary/20 text-accent dark:text-primary rounded-full">
                                                            {{ $item['numero'] }}
                                                        </span>
                                                    </td>
                                                    <td class="py-4 font-medium text-text dark:text-text-light">
                                                        ${{ number_format($item['precio'], 2) }}
                                                    </td>
                                                    <td class="py-4">
                                                        <form action="{{ route('carrito.eliminar', $index) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-6 text-right">
                                    <a href="{{ route('rifas') }}" class="text-primary hover:underline">
                                        Continuar comprando
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-600 mx-auto mb-4"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <h3 class="text-lg font-medium mb-2 text-accent dark:text-primary">Tu carrito está vacío</h3>
                                <p class="text-text dark:text-text-light/80 mb-6">Explora nuestras rifas y agrega boletos a tu carrito</p>
                                <a href="{{ route('rifas') }}"
                                    class="bg-primary text-text-light px-6 py-3 rounded-lg font-medium hover:bg-primary-dark transition btn-primary inline-block">
                                    Ver rifas disponibles
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Resumen de compra -->
                <div class="md:w-1/3">
                    <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-lg overflow-hidden sticky top-4">
                        <div class="p-6 bg-background dark:bg-background-dark/50 border-b dark:border-gray-700">
                            <h2 class="text-xl font-bold mb-3 text-accent dark:text-primary">Resumen de compra</h2>
                            <div class="flex justify-between mb-1 text-text dark:text-text-light">
                                <span>Subtotal ({{ count($carrito) }} boletos)</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg mt-3 pt-3 border-t dark:border-gray-700 text-accent dark:text-primary">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        @if (count($carrito) > 0)
                            <div class="p-6">
                                <h3 class="font-bold mb-4 text-accent dark:text-primary">Datos personales</h3>
                                <form action="{{ route('carrito.procesar-pago') }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label for="nombre" class="block text-text dark:text-text-light mb-1">Nombre completo</label>
                                            <input type="text" name="nombre" id="nombre" required
                                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-background-dark/50 dark:text-text-light rounded-md focus:ring-primary focus:border-primary">
                                        </div>

                                        <div>
                                            <label for="email" class="block text-text dark:text-text-light mb-1">Correo
                                                electrónico</label>
                                            <input type="email" name="email" id="email" required
                                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-background-dark/50 dark:text-text-light rounded-md focus:ring-primary focus:border-primary">
                                        </div>

                                        <div>
                                            <label for="telefono" class="block text-text dark:text-text-light mb-1">Teléfono</label>
                                            <input type="tel" name="telefono" id="telefono" required
                                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-background-dark/50 dark:text-text-light rounded-md focus:ring-primary focus:border-primary">
                                        </div>
                                    </div>

                                    <h3 class="font-bold mt-6 mb-4 text-accent dark:text-primary">Método de pago</h3>
                                    <div class="space-y-3">
                                        <label class="flex p-3 border dark:border-gray-700 rounded-lg cursor-pointer hover:bg-background dark:hover:bg-background-dark/50">
                                            <input type="radio" name="metodo_pago" value="tarjeta" class="mt-1 text-primary focus:ring-primary" checked>
                                            <div class="ml-3">
                                                <span class="block font-medium text-text dark:text-text-light">Tarjeta de crédito/débito</span>
                                                <span class="text-sm text-text dark:text-text-light/80">Visa, Mastercard, AMEX</span>
                                            </div>
                                        </label>

                                        <label class="flex p-3 border dark:border-gray-700 rounded-lg cursor-pointer hover:bg-background dark:hover:bg-background-dark/50">
                                            <input type="radio" name="metodo_pago" value="paypal" class="mt-1 text-primary focus:ring-primary">
                                            <div class="ml-3">
                                                <span class="block font-medium text-text dark:text-text-light">PayPal</span>
                                                <span class="text-sm text-text dark:text-text-light/80">Pago seguro en línea</span>
                                            </div>
                                        </label>

                                        <label class="flex p-3 border dark:border-gray-700 rounded-lg cursor-pointer hover:bg-background dark:hover:bg-background-dark/50">
                                            <input type="radio" name="metodo_pago" value="oxxo" class="mt-1 text-primary focus:ring-primary">
                                            <div class="ml-3">
                                                <span class="block font-medium text-text dark:text-text-light">OXXO</span>
                                                <span class="text-sm text-text dark:text-text-light/80">Pago en efectivo en tiendas OXXO</span>
                                            </div>
                                        </label>

                                        <label class="flex p-3 border dark:border-gray-700 rounded-lg cursor-pointer hover:bg-background dark:hover:bg-background-dark/50">
                                            <input type="radio" name="metodo_pago" value="transferencia"
                                                class="mt-1 text-primary focus:ring-primary">
                                            <div class="ml-3">
                                                <span class="block font-medium text-text dark:text-text-light">Transferencia bancaria</span>
                                                <span class="text-sm text-text dark:text-text-light/80">Depósito o transferencia</span>
                                            </div>
                                        </label>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-primary text-text-light px-6 py-3 rounded-lg font-medium hover:bg-primary-dark transition btn-primary mt-6">
                                        Continuar con el pago
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
