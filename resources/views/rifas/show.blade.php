@extends('layouts.app')

@section('title', $rifa->nombre . ' - Concursos y Sorteos San Miguel')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rifas.index') }}">Rifas</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $rifa->nombre }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <img src="{{ $rifa->getFirstMediaUrl('imagen') ?: asset('img/placeholder.jpg') }}"
                alt="{{ $rifa->nombre }}" class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h1>{{ $rifa->nombre }}</h1>

            <div class="mb-4">
                <div class="badge bg-primary mb-2">Precio por boleto: ${{ number_format($rifa->precio, 2) }} MXN</div>

                @if($rifa->estado === 'activa')
                    <div class="badge bg-success">Activa</div>
                @elseif($rifa->estado === 'finalizada')
                    <div class="badge bg-secondary">Finalizada</div>
                @elseif($rifa->estado === 'cancelada')
                    <div class="badge bg-danger">Cancelada</div>
                @endif
            </div>

            <div class="rifa-progreso mb-3" style="height: 10px;">
                <div class="rifa-progreso-barra" style="width: {{ ($rifa->total_boletos > 0) ? (($rifa->boletos_vendidos / $rifa->total_boletos) * 100) : 0 }}%"></div>
            </div>
            <p class="mb-4">
                <strong>Avance de venta:</strong> {{ $rifa->boletos_vendidos }} de {{ $rifa->total_boletos }} boletos vendidos
                ({{ $rifa->total_boletos > 0 ? number_format(($rifa->boletos_vendidos / $rifa->total_boletos) * 100, 1) : 0 }}%)
            </p>

            <p class="mb-4">
                <i class="far fa-calendar-alt me-2"></i>
                <strong>Fecha del sorteo:</strong> {{ \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('d/m/Y H:i') }}
            </p>

            <div class="mb-4">
                <h5>Descripción</h5>
                <p>{{ $rifa->descripcion }}</p>
            </div>

            @if($rifa->estado === 'activa')
                <a href="#seleccionar-boletos" class="btn btn-primario btn-lg">Comprar boletos</a>
            @elseif($rifa->estado === 'finalizada')
                <div class="alert alert-secondary">
                    Esta rifa ya ha finalizado. Consulta los resultados en nuestra sección de ganadores.
                </div>
            @elseif($rifa->estado === 'cancelada')
                <div class="alert alert-danger">
                    Esta rifa ha sido cancelada.
                </div>
            @endif
        </div>
    </div>

    @if($rifa->estado === 'activa')
        <div id="seleccionar-boletos" class="my-5">
            <h3 class="mb-4">Selecciona tus boletos</h3>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Disponibles: {{ $boletosDisponibles }} boletos
            </div>

            <div class="row gx-2 gy-2 mt-4">
                <!-- Aquí iría la lógica para mostrar los boletos disponibles -->
                <!-- Esto es un ejemplo visual, necesitarías implementar la lógica real -->
                @for($i = 1; $i <= min(100, $rifa->total_boletos); $i++)
                    <div class="col-1">
                        <div class="boleto-item {{ random_int(1, 3) == 1 ? 'vendido' : '' }}">
                            {{ $i }}
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    @endif
</div>

<style>
    .boleto-item {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        background-color: #fff;
        border: 1px solid var(--color-primary);
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .boleto-item:not(.vendido):hover {
        background-color: var(--color-primary);
        color: white;
    }

    .boleto-item.vendido {
        background-color: #e5e7eb;
        border-color: #d1d5db;
        color: #9ca3af;
        cursor: not-allowed;
        position: relative;
    }

    .boleto-item.vendido::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        border-top: 1px solid #9ca3af;
    }
</style>
@endsection
