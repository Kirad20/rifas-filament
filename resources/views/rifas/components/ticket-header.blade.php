<div class="d-flex align-items-center justify-content-between">
    <div>
        <h1 class="modern-title">Elige tus boletos favoritos</h1>
        <p class="text-muted lead">{{ $rifa->nombre }} - <span class="price-badge">${{ number_format($rifa->precio_boleto, 2) }} MXN/boleto</span></p>
    </div>
    <div class="d-none d-md-block">
        <div class="event-info">
            <div class="event-info-item">
                <i class="far fa-calendar-alt"></i>
                <span>Fecha del sorteo: {{ \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>
</div>
