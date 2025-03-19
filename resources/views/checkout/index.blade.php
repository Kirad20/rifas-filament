@extends('layouts.app')

@section('title', 'Finalizar compra')

@section('content')
<div>
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rifas.index') }}">Rifas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rifas.show', $rifa->id) }}">{{ $rifa->nombre }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Finalizar compra</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-4">Finalizar compra</h1>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Resumen de la compra</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Rifa: {{ $rifa->nombre }}</h6>
                        <p class="mb-2">Precio por boleto: ${{ number_format($rifa->precio, 2) }} MXN</p>
                        <p>Fecha del sorteo: {{ \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('d/m/Y H:i') }}</p>
                    </div>

                    <h6>Boletos seleccionados:</h6>
                    <div class="row mb-4">
                        @foreach($boletos as $boleto)
                            <div class="col-2 mb-2">
                                <div class="boleto-checkout">{{ $boleto }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Cantidad de boletos:</span>
                        <strong>{{ count($boletos) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total:</span>
                        <strong>${{ number_format($total, 2) }} MXN</strong>
                    </div>
                </div>
            </div>

            <!-- Aquí puedes agregar el formulario para datos del comprador -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Datos del comprador</h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm" method="POST" action="{{ route('checkout.procesar') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card subtotal-panel">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Método de pago</h5>
                </div>
                <div class="card-body">
                    <!-- Aquí puedes integrar los métodos de pago -->
                    <div class="mb-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metodoPago" id="tarjetaCredito" value="tarjeta" checked>
                            <label class="form-check-label" for="tarjetaCredito">
                                Tarjeta de crédito/débito
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metodoPago" id="paypal" value="paypal">
                            <label class="form-check-label" for="paypal">
                                PayPal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodoPago" id="transferencia" value="transferencia">
                            <label class="form-check-label" for="transferencia">
                                Transferencia bancaria
                            </label>
                        </div>
                    </div>

                    <div class="subtotal-info mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>${{ number_format($total, 2) }} MXN</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>IVA (16%):</span>
                            <strong>${{ number_format($total * 0.16, 2) }} MXN</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Total a pagar:</span>
                            <strong>${{ number_format($total * 1.16, 2) }} MXN</strong>
                        </div>
                    </div>

                    <button type="submit" form="checkoutForm" class="btn btn-primario w-100">
                        Completar compra
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .boleto-checkout {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        background-color: var(--color-primary);
        color: white;
        border-radius: 5px;
        font-weight: bold;
    }

    .subtotal-panel {
        position: sticky;
        top: 20px;
    }
</style>
@endsection
