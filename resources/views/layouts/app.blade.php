<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Rifas Online') - SorteosMX</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Barra de navegación -->
        <nav class="bg-amber-500 text-white shadow-md">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="font-bold text-xl">
                            SorteosMX
                        </a>
                    </div>

                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('home') }}" class="hover:text-amber-200 transition">
                            Inicio
                        </a>
                        <a href="{{ route('rifas') }}" class="hover:text-amber-200 transition">
                            Rifas
                        </a>
                        <a href="{{ route('contacto') }}" class="hover:text-amber-200 transition">
                            Contacto
                        </a>
                    </div>

                    <div class="md:hidden">
                        <!-- Menú móvil -->
                        <button id="mobile-menu-button" class="text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Menú móvil desplegable -->
                <div id="mobile-menu" class="hidden md:hidden pb-4">
                    <a href="{{ route('home') }}" class="block py-2 hover:text-amber-200">
                        Inicio
                    </a>
                    <a href="{{ route('rifas') }}" class="block py-2 hover:text-amber-200">
                        Rifas
                    </a>
                    <a href="{{ route('contacto') }}" class="block py-2 hover:text-amber-200">
                        Contacto
                    </a>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow-inner py-8 mt-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-medium mb-4">SorteosMX</h3>
                        <p class="text-gray-600">
                            La mejor plataforma para participar en rifas y sorteos en México.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium mb-4">Enlaces Útiles</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li><a href="#" class="hover:text-amber-500">Términos y Condiciones</a></li>
                            <li><a href="#" class="hover:text-amber-500">Política de Privacidad</a></li>
                            <li><a href="#" class="hover:text-amber-500">Preguntas Frecuentes</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium mb-4">Contacto</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li>Email: info@sorteosmx.com</li>
                            <li>Teléfono: +52 123 456 7890</li>
                            <li>Dirección: Av. Principal #123, CDMX</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-8 pt-8 text-center text-gray-500">
                    <p>&copy; {{ date('Y') }} SorteosMX. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
