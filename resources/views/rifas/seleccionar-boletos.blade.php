@extends('layouts.app')

@section('title', 'Selección de Boletos - ' . $rifa->nombre)

@php
    // Definir la variable de estado de autenticación aquí para evitar problemas de sintaxis
    $authCheckStatus = Auth::check() ? 'true' : 'false';
@endphp

@section('content')
<div class="full-width-wrapper">
    <div class="container mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rifas.index') }}">Rifas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rifas.show', $rifa->id) }}">{{ $rifa->nombre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Seleccionar Boletos</li>
            </ol>
        </nav>
    </div>

    <div class="container mb-4">
        @include('rifas.components.ticket-header', ['rifa' => $rifa])
    </div>

    <!-- Main Section -->
    <div class="full-width-card-container">
        <div class="modern-card mb-4">
            <div class="card-body p-0">
                <div class="custom-row">
                    <!-- Panel de selección de boletos (lado izquierdo) -->
                    @include('rifas.components.ticket-selection-grid', ['rifa' => $rifa])

                    <!-- Panel de boletos seleccionados (lado derecho) -->
                    @include('rifas.components.ticket-selected-panel', ['rifa' => $rifa])
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Herramientas flotantes y paneles -->
@include('rifas.components.ticket-tools-panel')

<!-- Modales -->
@include('rifas.components.ticket-modals', ['rifa' => $rifa])

<!-- Incluir estilos CSS -->
<link rel="stylesheet" href="{{ asset('css/ticket-selector.css') }}">

<!-- Incluir Bootstrap JS antes de nuestros scripts personalizados -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Incluir archivos JavaScript -->
<script src="{{ asset('js/seleccion-boletos.js') }}"></script>
<script src="{{ asset('js/boletos-inicializador.js') }}"></script>

<!-- Código JavaScript adicional -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar que Bootstrap se ha cargado correctamente
        if (typeof bootstrap === 'undefined') {
            console.error('¡Bootstrap JS no está cargado! Intentando cargar de forma asíncrona...');
            // Intentar cargar Bootstrap dinámicamente si no está disponible
            const bootstrapScript = document.createElement('script');
            bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js';
            bootstrapScript.onload = inicializarAplicacion;
            document.head.appendChild(bootstrapScript);
        } else {
            // Bootstrap ya está disponible, inicializar normalmente
            inicializarAplicacion();
        }
    });

    /**
     * Inicializa la aplicación de selección de boletos
     */
    function inicializarAplicacion() {
        console.log('Inicializando aplicación...');

        // Inicializar el selector de boletos
        const boletosApp = new BoletosSelector({
            precio: {{ $rifa->precio_boleto }},
            totalBoletos: {{ $rifa->total_boletos ?? 100 }},
            boletos: {!! json_encode($boletos->keyBy('numero')->all()) !!},
            boletosPorPagina: 100,
            rifaId: {{ $rifa->id }},
            csrfToken: '{{ csrf_token() }}',
            formAction: '{{ route('rifas.procesar-compra') }}'
        });

        // Inicializar componentes
        const appManager = new BoletosAppManager(boletosApp, {{ $authCheckStatus }});
        appManager.init();

        // Exponer al objeto global window para otros scripts
        window.boletosApp = boletosApp;
        window.appManager = appManager;
    }
</script>
@endsection
