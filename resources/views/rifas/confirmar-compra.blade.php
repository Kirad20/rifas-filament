@extends('layouts.app')

@section('title', 'Confirmación de compra')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="mb-4">¡Reserva exitosa!</h1>

                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle fa-2x mb-3"></i>
                        <p class="h4">Tus boletos han sido reservados correctamente.</p>
                    </div>

                    <div class="mb-4">
                        <p>Has seleccionado <strong>{{ $venta->boletos->count() }} boletos</strong> para la rifa:</p>
                        <h3 class="my-3">{{ $venta->boletos->first()->rifa->nombre }}</h3>

                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="h5 mb-3">Boletos seleccionados:</p>
                                <p>
                                    @foreach($venta->boletos->sortBy('numero')->pluck('numero') as $numero)
                                        <span class="badge bg-primary me-1 mb-1">{{ $numero }}</span>
                                    @endforeach
                                </p>
                            </div>
                        </div>

                        <p class="mb-1">Total a pagar: <strong>${{ number_format($venta->total, 2) }} MXN</strong></p>
                    </div>

                    <div class="countdown-container mb-4">
                        <div class="alert alert-warning">
                            <p class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i> <strong>Importante:</strong> Tus boletos están reservados hasta:</p>
                            <h4 id="countdown-display" class="mb-0">
                                @php
                                    // Obtener la fecha de reserva del primer boleto o usar la fecha de expiración de la venta o calcular 48 horas desde ahora
                                    $fechaExpiracion = $venta->boletos->first()->reservado_hasta ??
                                                      ($venta->fecha_expiracion ?? Carbon\Carbon::now()->addHours(48));
                                @endphp
                                {{ $fechaExpiracion->format('d/m/Y H:i:s') }}
                            </h4>
                            <p class="mt-2">Si no realizas el pago antes de esa fecha, los boletos volverán a estar disponibles.</p>
                        </div>

                        <!-- Countdown visual -->
                        <div class="countdown-timer d-flex justify-content-center gap-3 my-3" id="countdown-timer">
                            <div class="countdown-block">
                                <div class="countdown-value" id="countdown-hours">48</div>
                                <div class="countdown-label">Horas</div>
                            </div>
                            <div class="countdown-block">
                                <div class="countdown-value" id="countdown-minutes">00</div>
                                <div class="countdown-label">Minutos</div>
                            </div>
                            <div class="countdown-block">
                                <div class="countdown-value" id="countdown-seconds">00</div>
                                <div class="countdown-label">Segundos</div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h3 class="h5 mb-0">Instrucciones de pago</h3>
                        </div>
                        <div class="card-body">
                            <p>1. Realiza tu pago a través de los siguientes métodos:</p>
                            <ul class="text-start">
                                <li>Transferencia bancaria: <strong>0123456789</strong> (Banco XYZ)</li>
                                <li>Depósito en OXXO: <strong>1234 5678 9012 3456</strong></li>
                                <li>PayPal: <strong>pagos@sorteosmx.com</strong></li>
                            </ul>
                            <p>2. Una vez realizado el pago, envía tu comprobante vía WhatsApp al número: <strong>+52 123 456 7890</strong></p>
                            <p>3. Incluye tu nombre completo y el código de referencia: <strong>{{ $venta->id }}</strong></p>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('rifas.index') }}" class="btn btn-primario">Explorar más rifas</a>
                        <a href="{{ route('home') }}" class="btn btn-outline ms-2">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.countdown-timer {
    margin: 20px 0;
}

.countdown-block {
    background-color: var(--color-primary);
    color: var(--color-accent);
    border-radius: 8px;
    width: 80px;
    height: 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.countdown-value {
    font-size: 2rem;
    font-weight: bold;
    line-height: 1;
}

.countdown-label {
    font-size: 0.8rem;
    margin-top: 5px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Establecer la fecha límite basada en el campo reservado_hasta del boleto
    const fechaExpiracion = "{{ $venta->boletos->first()->reservado_hasta ??
                               ($venta->fecha_expiracion ?? Carbon\Carbon::now()->addHours(48))->toIso8601String() }}";
    const fechaLimite = new Date(fechaExpiracion);

    // Actualizar el contador cada segundo
    const countdownTimer = setInterval(function() {
        // Obtener la fecha y hora actual
        const ahora = new Date();

        // Calcular el tiempo restante en milisegundos
        const diferencia = fechaLimite - ahora;

        // Si ya se pasó la fecha límite
        if (diferencia <= 0) {
            clearInterval(countdownTimer);
            document.getElementById('countdown-timer').innerHTML = `
                <div class="alert alert-danger w-100">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    La reserva ha expirado. Los boletos ya no están reservados.
                </div>
            `;
            return;
        }

        // Calcular horas, minutos y segundos restantes
        const horas = Math.floor(diferencia / (1000 * 60 * 60));
        const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);

        // Actualizar el display del contador
        document.getElementById('countdown-hours').textContent = String(horas).padStart(2, '0');
        document.getElementById('countdown-minutes').textContent = String(minutos).padStart(2, '0');
        document.getElementById('countdown-seconds').textContent = String(segundos).padStart(2, '0');

    }, 1000);
});
</script>
@endsection
