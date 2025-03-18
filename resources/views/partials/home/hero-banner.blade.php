<!-- Hero Banner con overlay y diseño mejorado -->
<div class="relative bg-gradient-to-r from-accent to-secondary text-white overflow-hidden home-partial-view" style="color: var(--text-light) !important;">
    <!-- Patrón de fondo para dar textura usando CSS en lugar de SVG -->
    <div class="absolute inset-0 opacity-10"
         style="background-image: radial-gradient(circle, currentColor 1px, transparent 1px);
                background-size: 40px 40px;">
    </div>

    <div class="container mx-auto px-4 py-24 relative z-10">
        <div class="md:w-3/5 mx-auto text-center">
            <span class="inline-block bg-white/20 px-4 py-1 rounded-full text-sm font-medium backdrop-blur-sm mb-6">La
                mejor plataforma de sorteos</span>
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">Participa y Gana con <span
                    class="text-primary">Sorteos y Concursos San Miguel</span></h1>
            <p class="text-xl md:text-2xl mb-10 text-white/90">Tu plataforma de rifas y concursos en línea más transparente, segura y confiable, donde puedes ganar increíbles premios desde tan solo $1 MXN.</p>
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

    <!-- Decoración curva inferior usando CSS en lugar de SVG -->
    <div class="absolute bottom-0 left-0 right-0 h-24 overflow-hidden"
         style="background-color: #f9fafb;
                border-radius: 50% 50% 0 0 / 100% 100% 0 0;
                transform: translateY(50%);">
    </div>
</div>
