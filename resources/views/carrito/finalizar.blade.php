@extends('layouts.app')

@section('title', 'Finalizar Compra - Concursos y Sorteos San Miguel')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">Finalizar Compra</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Tus Datos</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('carrito.finalizar', ['token' => $carrito->token]) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre" name="nombre" value="{{ old('nombre') ?? (auth()->user()->name ?? '') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') ?? (auth()->user()->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Te enviaremos la confirmación de tu compra y detalles del sorteo a este correo.</div>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror"
                                id="telefono" name="telefono" value="{{ old('telefono') ?? $carrito->telefono }}" required readonly>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Al completar la compra, se creará una cuenta automáticamente con tus datos si no tienes una,
                            para que puedas acceder al historial de tus compras.
                        </div>

                        <button type="submit" class="btn btn-primario btn-lg w-100 mt-3">Completar Compra</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    @foreach($carrito->items as $item)
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6>{{ $item->rifa->nombre }}</h6>
                                <p class="mb-0 text-muted">{{ $item->boletos->count() }} boletos × ${{ number_format($item->precio_unitario, 2) }} MXN</p>
                                <small>Números:
                                    {{ implode(', ', $item->boletos->pluck('numero_boleto')->take(5)->toArray()) }}
                                    @if($item->boletos->count() > 5) ... @endif
                                </small>
                            </div>
                            <div class="text-end">
                                <strong>${{ number_format($item->boletos->count() * $item->precio_unitario, 2) }} MXN</strong>
                            </div>
                        </div>
                        <hr>
                    @endforeach

                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Total:</h5>
                        <h5>${{ number_format($carrito->getTotal(), 2) }} MXN</h5>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información Importante</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Una vez completada la compra, tendrás 48 horas para realizar el pago.</li>
                        <li>Recibirás instrucciones de pago por correo electrónico.</li>
                        <li>Los boletos se confirmarán cuando recibamos tu pago.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
