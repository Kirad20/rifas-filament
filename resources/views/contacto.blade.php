@extends('layouts.app')

@section('title', 'Contacto')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold mb-8 text-center">Contacta con Nosotros</h1>

        <div class="max-w-3xl mx-auto">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-8">
                <p class="mb-8">Completa el formulario a continuación y nos pondremos en contacto contigo lo antes posible.
                </p>

                <form action="{{ route('contacto.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="nombre" class="block text-gray-700 font-medium mb-2">Nombre</label>
                        <input type="text" name="nombre" id="nombre"
                            class="w-full px-4 py-2 border rounded-lg @error('nombre') border-red-500 @enderror"
                            value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Correo electrónico</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-2 border rounded-lg @error('email') border-red-500 @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="asunto" class="block text-gray-700 font-medium mb-2">Asunto</label>
                        <input type="text" name="asunto" id="asunto"
                            class="w-full px-4 py-2 border rounded-lg @error('asunto') border-red-500 @enderror"
                            value="{{ old('asunto') }}" required>
                        @error('asunto')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="mensaje" class="block text-gray-700 font-medium mb-2">Mensaje</label>
                        <textarea name="mensaje" id="mensaje" rows="5"
                            class="w-full px-4 py-2 border rounded-lg @error('mensaje') border-red-500 @enderror" required>{{ old('mensaje') }}</textarea>
                        @error('mensaje')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="bg-amber-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-amber-600 transition">
                        Enviar mensaje
                    </button>
                </form>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium mb-4">Información de contacto</h3>
                    <p class="text-gray-600">
                        Email: info@sorteosmx.com<br>
                        Teléfono: +52 123 456 7890<br>
                        Dirección: Av. Principal #123, CDMX
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium mb-4">Horario de atención</h3>
                    <p class="text-gray-600">
                        Lunes a Viernes: 9:00 AM - 6:00 PM<br>
                        Sábado: 10:00 AM - 2:00 PM<br>
                        Domingo: Cerrado
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
