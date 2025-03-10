<?php
@extends('layouts.app')

@section('title', 'Pago con tarjeta')

@section('content')
<div class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-8">Pago con tarjeta</h1>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-xl mx-auto">
            <div class="p-6 bg-amber-500 text-white">
                <h2 class="text-xl font-bold">Información de pago</h2>
                <p class="text-sm mt-1">Todos los campos son obligatorios</p>
            </div>

            <form action="{{ route('pagos.completar') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="metodo_pago" value="tarjeta">

                <div>
                    <label for="nombre_completo" class="block text-gray-700 font-medium mb-2">Nombre completo</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" required>
                </div>

                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">Correo electrónico</label>
                    <input type="email" id="email" name="email" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" required>
                </div>

                <div>
                    <label for="telefono" class="block text-gray-700 font-medium mb-2">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" required>
                </div>

                <div class="border-t border-b py-4">
                    <h3 class="font-bold mb-4">Datos de la tarjeta</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="numero_tarjeta" class="block text-gray-700 font-medium mb-2">Número de tarjeta</label>
                            <input type="text" id="numero_tarjeta" name="numero_tarjeta" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" required>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1">
                                <label for="mes_expiracion" class="block text-gray-700 font-medium mb-2">Mes</label>
                                <select id="mes_expiracion" name="mes_expiracion" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" required>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-span-1">
                                <label for="anio_expiracion" class="block text-gray-700 font-medium mb-2">Año</label>
                                <select id="anio_expiracion" name="anio_expiracion" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" required>
                                    @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-span-1">
                                <label for="cvv" class="block text-gray-700 font-medium mb-2">CVV</label>
                                <input type="text" id="cvv" name="cvv" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" maxlength="4" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-amber-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-amber-600 transition">
                        Pagar ahora
                    </button>
                    <p class="text-center text-gray-500 text-sm mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        El pago es seguro y cifrado
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
