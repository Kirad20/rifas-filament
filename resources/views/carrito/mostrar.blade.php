@extends('layouts.app')

@section('title', 'Mi Carrito - Concursos y Sorteos San Miguel')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">Mi Carrito de Compras</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($carrito->items->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
            <h3>Tu carrito está vacío</h3>
            <p>No tienes boletos en tu carrito de compras.</p>
            <a href="{{ route('rifas.index') }}" class="btn btn-primario mt-3">Ver rifas disponibles</a>
        </div>
    @else
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detalle del Carrito</h5>
            </div>
            <div class="card-body">
                @foreach($carrito->items as $item)
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $item->rifa->nombre }}</h5>
                            <span class="badge bg-primary">Precio: ${{ number_format($item->precio_unitario, 2) }} MXN/boleto</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ $item->rifa->getFirstMediaUrl('portada') ?: asset('img/placeholder.jpg') }}"
                                        alt="{{ $item->rifa->nombre }}" class="img-fluid rounded">
                                </div>
                                <div class="col-md-8">
                                    <h6>Boletos seleccionados:</h6>
                                    <div class="boletos-container mb-3">
                                        @foreach($item->boletos as $boleto)
                                            <span class="badge bg-primary position-relative me-1 mb-1">
                                                {{ $boleto->numero_boleto }}
                                                <a href="{{ route('carrito.eliminar-boleto', ['token' => $carrito->token, 'itemId' => $item->id, 'numeroBoleto' => $boleto->numero_boleto]) }}"
                                                   class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger remove-boleto"
                                                   title="Eliminar boleto">
                                                    &times;
                                                </a>
                                            </span>
                                        @endforeach
                                    </div>
                                    <p class="subtotal text-end">
                                        Subtotal: <strong>${{ number_format($item->boletos->count() * $item->precio_unitario, 2) }} MXN</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="row">
                    <div class="col-md-12">
                        <div class="total-container text-end">
                            <h4>Total a pagar: <strong>${{ number_format($carrito->getTotal(), 2) }} MXN</strong></h4>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('rifas.index') }}" class="btn btn-outline">Seguir comprando</a>
                            <a href="{{ route('carrito.finalizar.form', ['token' => $carrito->token]) }}" class="btn btn-primario">Finalizar Compra</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning mt-4">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Importante:</strong> Los boletos seleccionados se reservan temporalmente por 1 hora. Si no completas tu compra en ese tiempo, volverán a estar disponibles para otros compradores.
        </div>
    @endif
</div>

<style>
.boletos-container {
    position: relative;
}

.remove-boleto {
    cursor: pointer;
    border: 1px solid #fff;
    font-size: 0.6rem;
}

.badge {
    font-size: 0.9rem;
    padding: 0.5em 0.7em;
}
</style>
@endsection
