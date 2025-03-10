@extends('layouts.app')

@section('title', $rifa->nombre)

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/2">
                    @if ($rifa->imagen)
                        <img src="{{ asset('storage/' . $rifa->imagen) }}" alt="{{ $rifa->nombre }}"
                            class="w-full h-80 object-cover">
                    @else
                        <div class="w-full h-80 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Sin imagen</span>
                        </div>
                    @endif
                </div>
                <div class="p-8 md:w-1/2">
                    <h1 class="text-3xl font-bold mb-4">{{ $rifa->nombre }}</h1>

                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-gray-700">Fecha del sorteo:
                                <strong>{{ \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('d/m/Y') }}</strong></span>
                        </div>
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <span class="text-gray-700">Boletos disponibles:
                                <strong>{{ $rifa->boletos_disponibles }}</strong></span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <span class="text-3xl font-bold text-amber-500">${{ number_format($rifa->precio_boleto, 2) }}</span>
                        <span class="text-gray-500 ml-2">por boleto</span>
                    </div>

                    <a href="{{ route('boletos.seleccionar', $rifa) }}"
                        class="bg-amber-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-amber-600 transition w-full md:w-auto text-center">
                        Comprar boleto
                    </a>
                </div>
            </div>

            <div class="p-8 border-t">
                <h2 class="text-2xl font-bold mb-4">Descripción</h2>
                <div class="prose max-w-none">
                    {!! $rifa->descripcion !!}
                </div>
            </div>

            <div class="p-8 border-t bg-gray-50">
                <h2 class="text-2xl font-bold mb-4">Términos y condiciones</h2>
                <div class="prose max-w-none">
                    <ul>
                        <li>El sorteo se realizará en la fecha indicada, sin posibilidad de cambios.</li>
                        <li>El ganador será notificado por correo electrónico y teléfono.</li>
                        <li>El premio deberá ser reclamado en un plazo máximo de 30 días.</li>
                        <li>No se aceptan devoluciones 48 horas antes del sorteo.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
