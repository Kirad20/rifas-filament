@extends('layouts.app')

@section('title', 'Carrito de compra')

@section('content')
    <div class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-8">Carrito de Compras</h1>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Lista de boletos -->
                <div class="md:w-2/3">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6 bg-amber-500 text-white">
                            <h2 class="text-xl font-bold">Boletos seleccionados</h2>
                        </div>

                        @if (count($carrito) > 0)
                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="border-b">
                                                <th class="pb-3 font-medium">Rifa</th>
                                                <th class="pb-3 font-medium">Número</th>
                                                <th class="pb-3 font-medium">Precio</th>
                                                <th class="pb-3 font-medium">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($carrito as $index => $item)
                                                <tr class="border-b hover:bg-gray-50">
                                                    <td class="py-4">
                                                        {{ $item['rifa_nombre'] }}
                                                    </td>
                                                    <td class="py-4">
                                                        <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full">
                                                            {{ $item['numero'] }}
                                                        </span>
                                                    </td>
                                                    <td class="py-4 font-medium">
                                                        ${{ number_format($item['precio'], 2) }}
                                                    </td>
                                                    <td class="py-4">
                                                        <form action="{{ route('carrito.eliminar', $index) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700">
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
                                    <a href="{{ route('rifas') }}" class="text-amber-500 hover:underline">
                                        Continuar comprando
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <h3 class="text-lg font-medium mb-2">Tu carrito está vacío</h3>
                                <p class="text-gray-500 mb-6">Explora nuestras rifas y agrega boletos a tu carrito</p>
                                <a href="{{ route('rifas') }}"
                                    class="bg-amber-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-amber-600 transition inline-block">
                                    Ver rifas disponibles
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Resumen de compra -->
                <div class="md:w-1/3">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-4">
                        <div class="p-6 bg-gray-50 border-b">
                            <h2 class="text-xl font-bold mb-3">Resumen de compra</h2>
                            <div class="flex justify-between mb-1">
                                <span>Subtotal ({{ count($carrito) }} boletos)</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg mt-3 pt-3 border-t">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        @if (count($carrito) > 0)
                            <div class="p-6">
                                <h3 class="font-bold mb-4">Datos personales</h3>
                                <form action="{{ route('carrito.procesar-pago') }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label for="nombre" class="block text-gray-700 mb-1">Nombre completo</label>
                                            <input type="text" name="nombre" id="nombre" required
                                                class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                        </div>

                                        <div>
                                            <label for="email" class="block text-gray-700 mb-1">Correo
                                                electrónico</label>
                                            <input type="email" name="email" id="email" required
                                                class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                        </div>

                                        <div>
                                            <label for="telefono" class="block text-gray-700 mb-1">Teléfono</label>
                                            <input type="tel" name="telefono" id="telefono" required
                                                class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                                        </div>
                                    </div>

                                    <h3 class="font-bold mt-6 mb-4">Método de pago</h3>
                                    <div class="space-y-3">
                                        <label class="flex p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="metodo_pago" value="tarjeta" class="mt-1" checked>
                                            <div class="ml-3">
                                                <span class="block font-medium">Tarjeta de crédito/débito</span>
                                                <span class="text-sm text-gray-500">Visa, Mastercard, AMEX</span>
                                            </div>
                                        </label>

                                        <label class="flex p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="metodo_pago" value="paypal" class="mt-1">
                                            <div class="ml-3">
                                                <span class="block font-medium">PayPal</span>
                                                <span class="text-sm text-gray-500">Pago seguro en línea</span>
                                            </div>
                                        </label>

                                        <label class="flex p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="metodo_pago" value="oxxo" class="mt-1">
                                            <div class="ml-3">
                                                <span class="block font-medium">OXXO</span>
                                                <span class="text-sm text-gray-500">Pago en efectivo en tiendas OXXO</span>
                                            </div>
                                        </label>

                                        <label class="flex p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="metodo_pago" value="transferencia"
                                                class="mt-1">
                                            <div class="ml-3">
                                                <span class="block font-medium">Transferencia bancaria</span>
                                                <span class="text-sm text-gray-500">Depósito o transferencia</span>
                                            </div>
                                        </label>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-amber-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-amber-600 transition mt-6">
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
