<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Rifas Online') - Concursos y Sorteos San Miguel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts y estilos compilados -->
    @vite(['resources/css/app.css', 'resources/css/theme.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased transition-colors duration-300">
    <div class="min-h-screen bg-background dark:bg-background-dark transition-colors duration-300">
        <!-- Header con Video de Fondo (opcional) -->
        @if (Route::currentRouteName() === 'home')
            <div class="relative overflow-hidden h-[50vh]">
                <video autoplay muted loop class="absolute w-full h-full object-cover">
                    <source src="{{ asset('videos/rifas-promo.mp4') }}" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-accent bg-opacity-70 flex items-center justify-center">
                    <div class="text-center" data-aos="fade-up">
                        <h1 class="text-5xl md:text-6xl font-extrabold text-primary mb-4 drop-shadow-lg">
                            ¡GANA EN GRANDE!
                        </h1>
                        <p class="text-xl md:text-2xl text-text-light mb-8 max-w-2xl mx-auto">
                            Participa en las mejores rifas y sorteos de México. Grandes premios te esperan.
                        </p>
                        <a href="{{ route('rifas') }}"
                            class="px-8 py-4 bg-primary text-text-light rounded-full text-xl font-bold hover:bg-primary-dark transition-colors duration-300 shadow-lg">
                            <i class="fas fa-ticket-alt mr-2 rotate-45"></i> Explorar Rifas
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Navbar -->
        <nav class="navbar sticky top-0 left-0 right-0 z-50 bg-accent shadow-lg text-text-light">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center text-2xl font-bold text-primary">
                            <i class="fas fa-ticket-alt mr-2 rotate-45"></i>
                            <span class="tracking-wider">Concursos y Sorteos San Miguel</span>
                        </a>
                    </div>

                    <div class="hidden md:flex space-x-8">
                        <a href="{{ route('home') }}" class="hover:text-primary-light transition text-lg font-medium">
                            <i class="fas fa-home mr-1"></i> Inicio
                        </a>
                        <a href="{{ route('rifas') }}" class="hover:text-primary-light transition text-lg font-medium">
                            <i class="fas fa-ticket-alt mr-1"></i> Rifas
                        </a>
                        <a href="{{ route('contacto') }}" class="hover:text-primary-light transition text-lg font-medium">
                            <i class="fas fa-envelope mr-1"></i> Contacto
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button class="theme-toggle text-primary">
                            <i class="fas fa-moon dark:hidden"></i>
                            <i class="fas fa-sun hidden dark:block"></i>
                        </button>

                        <div class="md:hidden">
                            <!-- Menú móvil -->
                            <button id="mobile-menu-button" class="text-primary p-2">
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
                    <a href="{{ route('home') }}" class="block py-3 text-lg hover:text-primary-light">
                        <i class="fas fa-home mr-2"></i> Inicio
                    </a>
                    <a href="{{ route('rifas') }}" class="block py-3 text-lg hover:text-primary-light">
                        <i class="fas fa-ticket-alt mr-2"></i> Rifas
                    </a>
                    <a href="{{ route('contacto') }}" class="block py-3 text-lg hover:text-primary-light">
                        <i class="fas fa-envelope mr-2"></i> Contacto
                    </a>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="text-text-color dark:text-text-light">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-accent shadow-inner py-12 mt-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div data-aos="fade-up" data-aos-delay="100">
                        <h3 class="text-2xl font-bold mb-6 text-primary">Concursos y Sorteos San Miguel</h3>
                        <p class="text-text-light text-lg">
                            La mejor plataforma para participar en rifas y sorteos en México.
                        </p>
                        <div class="mt-6 flex space-x-4">
                            <a href="#" class="text-primary hover:text-primary-light text-2xl">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="text-primary hover:text-primary-light text-2xl">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-primary hover:text-primary-light text-2xl">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-2xl font-bold mb-6 text-primary">Enlaces Útiles</h3>
                        <ul class="space-y-3 text-text-light text-lg">
                            <li><a href="#" class="hover:text-primary flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-primary"></i> Términos y Condiciones</a>
                            </li>
                            <li><a href="#" class="hover:text-primary flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-primary"></i> Política de
                                    Privacidad</a></li>
                            <li><a href="#" class="hover:text-primary flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-primary"></i> Preguntas Frecuentes</a>
                            </li>
                            <li><a href="#" class="hover:text-primary flex items-center"><i
                                        class="fas fa-chevron-right mr-2 text-primary"></i> Ganadores Anteriores</a>
                            </li>
                        </ul>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="300">
                        <h3 class="text-2xl font-bold mb-6 text-primary">Contacto</h3>
                        <ul class="space-y-3 text-text-light text-lg">
                            <li class="flex items-center"><i class="fas fa-envelope mr-3 text-primary"></i>
                                info@sorteosmx.com</li>
                            <li class="flex items-center"><i class="fas fa-phone-alt mr-3 text-primary"></i> +52 123
                                456 7890</li>
                            <li class="flex items-center"><i class="fas fa-map-marker-alt mr-3 text-primary"></i>
                                Av. Principal #123, CDMX</li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t border-primary-dark/30 mt-12 pt-8 text-center text-text-light/80">
                    <p class="text-lg">&copy; {{ date('Y') }} Concursos y Sorteos San Miguel. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts al final del body -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
