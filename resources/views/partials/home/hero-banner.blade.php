<!-- Hero Banner con overlay y diseño mejorado -->
<div class="relative bg-gradient-to-r from-accent to-secondary text-white overflow-hidden">
    <!-- Patrón de fondo para dar textura -->
    <div class="absolute inset-0 opacity-10">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 20 L40 20" stroke="currentColor" stroke-width="1"></path>
                    <path d="M20 0 L20 40" stroke="currentColor" stroke-width="1"></path>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#pattern)"></rect>
        </svg>
    </div>

    <div class="container mx-auto px-4 py-24 relative z-10">
        <div class="md:w-3/5 mx-auto text-center">
            <span class="inline-block bg-white/20 px-4 py-1 rounded-full text-sm font-medium backdrop-blur-sm mb-6">La
                mejor plataforma de sorteos</span>
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">¡Participa y Gana con <span
                    class="text-primary">SorteosMX</span>!</h1>
            <p class="text-xl md:text-2xl mb-10 text-white/90">La plataforma de rifas en línea con premios increíbles,
                resultados transparentes y la mejor experiencia para nuestros usuarios.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('rifas') }}"
                    class="bg-white text-accent px-8 py-4 rounded-lg font-bold hover:bg-primary-light hover:text-accent transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Ver rifas disponibles
                </a>
                <a href="#como-funciona"
                    class="bg-primary bg-opacity-60 backdrop-blur-sm text-white px-8 py-4 rounded-lg font-bold hover:bg-primary-dark transition border border-white/20">
                    Cómo funciona
                </a>
            </div>

            <!-- Contador o estadísticas -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                    <div class="font-bold text-3xl">{{ rand(50, 100) }}+</div>
                    <div class="text-sm text-white/80">Rifas completadas</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                    <div class="font-bold text-3xl">{{ rand(1000, 5000) }}+</div>
                    <div class="text-sm text-white/80">Participantes</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                    <div class="font-bold text-3xl">${{ rand(100, 999) }}K</div>
                    <div class="text-sm text-white/80">En premios</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                    <div class="font-bold text-3xl">{{ rand(95, 99) }}%</div>
                    <div class="text-sm text-white/80">Clientes satisfechos</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Decoración curva inferior -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" fill="#f9fafb">
            <path d="M0,0 C240,95 480,95 720,47.5 C960,0 1200,0 1440,47.5 L1440,100 L0,100 Z"></path>
        </svg>
    </div>
</div>
