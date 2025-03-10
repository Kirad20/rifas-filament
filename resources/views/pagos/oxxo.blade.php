@extends('layouts.app')

@section('title', 'Pago en OXXO')

@section('content')
    <div class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6 bg-amber-500 text-white">
                        <h1 class="text-xl font-bold">Pago en OXXO</h1>
                    </div>

                    <div class="p-6">
                        <div
                            class="flex items-center justify-center p-6 mb-6 border border-amber-200 bg-amber-50 rounded-lg">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-amber-600 mb-2">Referencia de pago</h2>
                                <div class="text-3xl font-mono bg-white border-2 border-amber-500 py-3 px-6 rounded">
                                    {{ $referencia }}
                                </div>
                                <p class="mt-2 text-gray-600">Monto a pagar:
                                    <strong>${{ number_format($total, 2) }}</strong></p>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold mb-4">Instrucciones de pago</h3>
                        <ol class="list-decimal list-outside pl-5 space-y-2 text-gray-700">
                            <li>Acude a la tienda OXXO más cercana.</li>
                            <li>Indica en caja que quieres realizar un pago de OXXOPay.</li>
                            <li>Dicta al cajero la referencia de 12 dígitos que aparece en esta página.</li>
                            <li>Realiza el pago correspondiente en efectivo.</li>
                            <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. Conserva este
                                comprobante.</li>
                            <li>Tu compra se procesará automáticamente en un máximo de 2 horas y recibirás un correo
                                electrónico de confirmación.</li>
                        </ol>

                        <div class="mt-6 text-sm text-gray-600">
                            <p><strong>Importante:</strong> El número de boletos seleccionados se reservará durante 24
                                horas. Si no se recibe el pago dentro de ese período, los boletos volverán a estar
                                disponibles.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 border-t">
                    <a href="{{ route('home') }}"
                        class="inline-block bg-amber-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-amber-600 transition">
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
