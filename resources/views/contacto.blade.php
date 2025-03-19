@extends('layouts.app')

@section('title', 'Contacto - Concursos y Sorteos San Miguel')

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-5">Contáctanos</h1>

        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('contacto.enviar') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono (opcional)</label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" name="telefono" value="{{ old('telefono') }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mensaje" class="form-label">Mensaje</label>
                                <textarea class="form-control @error('mensaje') is-invalid @enderror"
                                    id="mensaje" name="mensaje" rows="5" required>{{ old('mensaje') }}</textarea>
                                @error('mensaje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primario">Enviar mensaje</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-5">
                    <h3>Información de contacto</h3>
                    <p><i class="fas fa-envelope me-2"></i> info@sorteosmx.com</p>
                    <p><i class="fas fa-phone me-2"></i> +52 123 456 7890</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Av. Principal #123, CDMX</p>
                </div>
            </div>
        </div>
    </div>
@endsection
