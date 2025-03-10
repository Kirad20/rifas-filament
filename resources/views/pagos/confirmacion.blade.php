@extends('layouts.app')

@section('title', 'Confirmación de compra')

@section('content')
    <div class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-8 bg-green-500 text-white text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <h1 class="text-3xl font-bold">¡Compra Confirmada!</h1>
                    <p class="mt-2">Tu compra se ha procesado correctamente.</p>
                </div>

                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold mb-4">Resumen de compra</h2>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Referencia de pago:</span>
                                <span class="font-medium">{{ $compra->referencia }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Fecha:</span>
                                <span>{{ $compra->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Método de pago:</span>
                                <span>{{ ucfirst($compra->metodo_pago) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Estado:</span>
                                <span
                                    class="px-3 py-1 {{ $compra->estado == 'pagado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-full text-sm">
                                    {{ ucfirst($compra->estado) }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="font-bold">Total pagado:</span>
                                <span class="font-bold">${{ number_format($compra->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-bold mb-4">Boletos comprados</h2>
                        <div class="overflow-x-auto bg-gray-100 p-4 rounded-lg">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b text-left">
                                        <th class="pb-2 font-medium">Rifa</th>
                                        <th class="pb-2 font-medium">Número</th>
                                        <th class="pb-2 font-medium">Fecha sorteo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($compra->detalles as $detalle)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3">{{ $detalle->boleto->rifa->nombre }}</td>
                                            <td class="py-3">
                                                <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full">
                                                    {{ $detalle->boleto->numero }}
                                                </span>
                                            </td>
                                            <td class="py-3">{{ $detalle->boleto->rifa->fecha_sorteo->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($compra->estado == 'pendiente')
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Tu compra está pendiente hasta que se confirme el pago. Los boletos serán reservados
                                        por 24 horas.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        Tu compra ha sido confirmada. Hemos enviado un correo electrónico con los detalles
                                        de tu compra.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="text-center">
                        <a href="{{ route('home') }}"
                            class="inline-block bg-amber-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-amber-600 transition">
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
