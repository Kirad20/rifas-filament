<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Rifas Online') - SorteosMX</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Estilos adicionales -->
    <style>
        :root {
            --primary-color: #f59e0b;
            --primary-dark: #d97706;
            --primary-light: #fcd34d;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        h1,
        h2,
        h3,
        .logo-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/img/hero-background.jpg');
            background-size: cover;
            background-position: center;
        }

        .theme-toggle {
            cursor: pointer;
        }

        .dark {
            color-scheme: dark;
        }
    </style>
</head>

<body class="font-sans antialiased transition-colors duration-300">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <!-- Header con Video de Fondo (opcional) -->
        @if (Route::currentRouteName() === 'home')
            <div class="relative overflow-hidden h-[50vh]">
                <video autoplay muted loop class="absolute w-full h-full object-cover">
                    <source src="{{ asset('videos/rifas-promo.mp4') }}" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="text-center" data-aos="fade-up">
                        <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-4 drop-shadow-lg">
                            ¡GANA EN GRANDE!
                        </h1>
                        <p class="text-xl md:text-2xl text-white mb-8 max-w-3xl mx-auto">
                            Las mejores rifas con los premios más increíbles de todo México
                        </p>
                        <a href="{{ route('rifas') }}"
                            class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-full text-lg transition transform hover:scale-105">
                            Ver Rifas Activas
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Barra de navegación -->
        <nav class="bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg sticky top-0 z-50">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="logo-text font-bold text-2xl flex items-center">
                            <i class="fas fa-ticket-alt mr-2 rotate-45"></i>
                            <span class="tracking-wider">SorteosMX</span>
                        </a>
                    </div>

                    <div class="hidden md:flex space-x-8">
                        <a href="{{ route('home') }}" class="hover:text-amber-100 transition text-lg font-medium">
                            <i class="fas fa-home mr-1"></i> Inicio
                        </a>
                        <a href="{{ route('rifas') }}" class="hover:text-amber-100 transition text-lg font-medium">
                            <i class="fas fa-ticket-alt mr-1"></i> Rifas
                        </a>
                        <a href="{{ route('contacto') }}" class="hover:text-amber-100 transition text-lg font-medium">
                            <i class="fas fa-envelope mr-1"></i> Contacto
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button class="theme-toggle text-white">
                            <i class="fas fa-moon dark:hidden"></i>
                            <i class="fas fa-sun hidden dark:block"></i>
                        </button>

                        <div class="md:hidden">
                            <!-- Menú móvil -->
                            <button id="mobile-menu-button" class="text-white p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Menú móvil desplegable -->
                <div id="mobile-menu" class="hidden md:hidden pb-4">
                    <a href="{{ route('home') }}" class="block py-3 text-lg hover:text-amber-100">
                        <i class="fas fa-home mr-2"></i> Inicio
                    </a>
                    <a href="{{ route('rifas') }}" class="block py-3 text-lg hover:text-amber-100">
                        <i class="fas fa-ticket-alt mr-2"></i> Rifas
                    </a>
                    <a href="{{ route('contacto') }}" class="block py-3 text-lg hover:text-amber-100">
                        <i class="fas fa-envelope mr-2"></i> Contacto
                    </a>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="dark:text-gray-100">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 shadow-inner py-12 mt-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-2xl font-bold mb-6 text-amber-600 dark:text-amber-400">SorteosMX</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">
                            La mejor plataforma para participar en rifas y sorteos en México.
                        </p>
                        <div class="mt-6 flex space-x-4">
                            <a href="#" class="text-amber-500 hover:text-amber-600 text-2xl">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="text-amber-500 hover:text-amber-600 text-2xl">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-amber-500 hover:text-amber-600 text-2xl">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-2xl font-bold mb-6 text-amber-600 dark:text-amber-400">Enlaces Útiles</h3>
                        <ul class="space-y-3 text-gray-600 dark:text-gray-300 text-lg">
                            <li><a href="#" class="hover:text-amber-500 flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-amber-500"></i> Términos y Condiciones</a>
                            </li>
                            <li><a href="#" class="hover:text-amber-500 flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-amber-500"></i> Política de
                                    Privacidad</a></li>
                            <li><a href="#" class="hover:text-amber-500 flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-amber-500"></i> Preguntas Frecuentes</a>
                            </li>
                            <li><a href="#" class="hover:text-amber-500 flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-amber-500"></i> Ganadores Anteriores</a>
                            </li>
                        </ul>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="300">
                        <h3 class="text-2xl font-bold mb-6 text-amber-600 dark:text-amber-400">Contacto</h3>
                        <ul class="space-y-3 text-gray-600 dark:text-gray-300 text-lg">
                            <li class="flex items-center"><i class="fas fa-envelope mr-3 text-amber-500"></i>
                                info@sorteosmx.com</li>
                            <li class="flex items-center"><i class="fas fa-phone-alt mr-3 text-amber-500"></i> +52 123
                                456 7890</li>
                            <li class="flex items-center"><i class="fas fa-map-marker-alt mr-3 text-amber-500"></i>
                                Av. Principal #123, CDMX</li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t border-gray-200 dark:border-gray-700 mt-12 pt-8 text-center text-gray-500 dark:text-gray-400">
                    <p class="text-lg">&copy; {{ date('Y') }} SorteosMX. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Inicializar AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // Toggle del menú móvil
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Modo oscuro/claro
        document.querySelector('.theme-toggle').addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        });

        // Verificar preferencia de tema
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</body>

</html>
