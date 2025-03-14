@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <!-- Hero Banner -->
    @include('partials.home.hero-banner')

    <!-- Sección de cómo funciona -->
    @include('partials.home.how-it-works')

    <!-- Rifas Destacadas -->
    @include('partials.home.featured-raffles')

    <!-- Secciones de información -->
    @include('partials.home.info-sections')
@endsection
