<section class="rifas-destacadas">
    <div class="container">
        <h2>Rifas Destacadas</h2>

        @if(isset($rifas) && count($rifas) > 0)
            <div class="rifas-grid">
                @foreach($rifas as $rifa)
                    <div class="rifa-card">
                        <img src="{{ $rifa->getFirstMediaUrl('imagen') ?: asset('img/placeholder.jpg') }}" alt="{{ $rifa->nombre }}" class="rifa-img">
                        <div class="rifa-info">
                            <h3 class="rifa-titulo">{{ $rifa->nombre }}</h3>
                            <div class="rifa-precio">${{ number_format($rifa->precio, 2) }} MXN por boleto</div>
                            <div class="rifa-estado">
                                <div class="rifa-progreso">
                                    <div class="rifa-progreso-barra" style="width: {{ ($rifa->total_boletos > 0) ? (($rifa->boletos_vendidos / $rifa->total_boletos) * 100) : 0 }}%"></div>
                                </div>
                                <span class="rifa-vendidos">{{ $rifa->boletos_vendidos }}/{{ $rifa->total_boletos }} boletos vendidos</span>
                            </div>
                            <div class="rifa-fecha">
                                <i class="far fa-calendar-alt"></i>
                                <span>Fecha del sorteo: {{ \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('d/m/Y') }}</span>
                            </div>
                            <a href="{{ route('rifas.show', $rifa->id) }}" class="btn btn-primario">Ver detalles</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rifas-mensaje">
                <p>No hay rifas destacadas disponibles en este momento.</p>
            </div>
        @endif

        <div class="ver-mas">
            <a href="{{ route('rifas.index') }}" class="btn btn-secundario">Ver todas las rifas →</a>
        </div>
    </div>
</section>
