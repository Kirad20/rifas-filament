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
            <!-- Carousel de imágenes -->
            <div class="rifa-carousel">
                <div class="carousel-main mb-3">
                    <img src="{{ $rifa->getFirstMediaUrl('portada') ?: asset('img/placeholder.jpg') }}"
                        alt="{{ $rifa->nombre }}" class="img-fluid rounded active-img" id="featured-img">
                </div>

                @if($rifa->getMedia('fotos')->count() > 0)
                <div class="carousel-thumbs">
                    <div class="thumb-container">
                        <!-- Imagen de portada también como miniatura -->
                        <div class="thumb-item active">
                            <img src="{{ $rifa->getFirstMediaUrl('portada') ?: asset('img/placeholder.jpg') }}"
                                alt="{{ $rifa->nombre }}" class="img-thumbnail"
                                onclick="changeImage('{{ $rifa->getFirstMediaUrl('portada') ?: asset('img/placeholder.jpg') }}', this)">
                        </div>

                        <!-- Imágenes adicionales de la colección 'fotos' -->
                        @foreach($rifa->getMedia('fotos') as $media)
                        <div class="thumb-item">
                            <img src="{{ $media->getUrl() }}"
                                alt="{{ $rifa->nombre }}" class="img-thumbnail"
                                onclick="changeImage('{{ $media->getUrl() }}', this)">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
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
                <a href="{{ route('rifas.seleccionar-boletos', $rifa->id) }}" class="btn btn-primario btn-lg">Comprar boletos</a>
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

    .carousel-main {
        width: 100%;
        overflow: hidden;
    }

    .carousel-main img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        transition: all 0.3s;
    }

    .carousel-thumbs {
        width: 100%;
        overflow-x: auto;
        padding: 10px 0;
    }

    .thumb-container {
        display: flex;
        gap: 10px;
    }

    .thumb-item {
        flex: 0 0 auto;
        width: 80px;
        height: 80px;
        cursor: pointer;
        opacity: 0.7;
        transition: all 0.3s;
    }

    .thumb-item.active {
        opacity: 1;
        border: 2px solid var(--color-primary);
    }

    .thumb-item:hover {
        opacity: 1;
    }

    .thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<script>
    function changeImage(src, element) {
        // Cambiar la imagen principal
        document.getElementById('featured-img').src = src;

        // Actualizar la clase activa en las miniaturas
        const thumbs = document.querySelectorAll('.thumb-item');
        thumbs.forEach(thumb => thumb.classList.remove('active'));
        element.parentElement.classList.add('active');
    }
</script>
@endsection
