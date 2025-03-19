@extends('layouts.app')

@section('title', 'Mis Boletos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-accent dark:text-primary">Mis Boletos</h1>

    @if($compras->count() > 0)
        @foreach($compras as $compra)
            <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-accent dark:text-primary">{{ $compra->rifa->nombre }}</h2>
                        <p class="text-text dark:text-text-light/80 text-sm">
                            Compra realizada el {{ $compra->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="mt-3 md:mt-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($compra->estado == 'pagado') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                            @elseif($compra->estado == 'pendiente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                            @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                            {{ ucfirst($compra->estado) }}
                        </span>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-accent dark:text-primary-light mb-3">Detalles de la Rifa</h3>

                        <div class="space-y-2 text-text dark:text-text-light/80">
                            <div class="flex justify-between">
                                <span>Fecha del sorteo:</span>
                                <span class="font-medium">{{ $compra->rifa->fecha_sorteo->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Precio por boleto:</span>
                                <span class="font-medium">${{ number_format($compra->rifa->precio, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Cantidad de boletos:</span>
                                <span class="font-medium">{{ $compra->boletos->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total pagado:</span>
                                <span class="font-medium text-primary">${{ number_format($compra->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-accent dark:text-primary-light mb-3">Tus Boletos</h3>

                        <div class="flex flex-wrap gap-2">
                            @foreach($compra->boletos as $boleto)
                                <div class="bg-primary-light/20 dark:bg-primary/20 text-accent dark:text-primary px-3 py-2 rounded-lg font-medium">
                                    {{ str_pad($boleto->numero, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($compra->rifa->ganador)
                    <div class="mt-6 p-4 bg-primary-light/10 dark:bg-primary/10 rounded-lg border border-primary/20">
                        <h3 class="font-semibold text-accent dark:text-primary">Resultado del sorteo</h3>
                        <p class="text-text dark:text-text-light mt-1">
                            El boleto ganador fue: <span class="font-bold text-primary">{{ str_pad($compra->rifa->boleto_ganador, 2, '0', STR_PAD_LEFT) }}</span>
                            - Ganador: {{ $compra->rifa->ganador }}
                        </p>
                    </div>
                @elseif($compra->estado == 'pendiente')
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('pagos.completar', $compra->id) }}" class="bg-primary text-text-light px-4 py-2 rounded-lg hover:bg-primary-dark transition btn-primary">
                            Completar pago
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-md p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-primary/50 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
            <h2 class="text-2xl font-bold text-accent dark:text-primary mb-2">No tienes boletos</h2>
            <p class="text-text dark:text-text-light/80 mb-6">Aún no has comprado boletos para ninguna rifa.</p>
            <a href="{{ route('rifas') }}" class="bg-primary text-text-light px-6 py-2 rounded-lg hover:bg-primary-dark transition btn-primary">
                Ver rifas disponibles
            </a>
        </div>
    @endif
</div>
@endsection
