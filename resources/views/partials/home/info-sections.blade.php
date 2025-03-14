<!-- Secciones de información con diseño mejorado -->
<div class="bg-background py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span
                class="inline-block bg-primary-light/30 text-accent px-4 py-1 rounded-full text-sm font-medium mb-3">INFORMACIÓN
                IMPORTANTE</span>
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Todo lo que necesitas saber</h2>
            <p class="text-text dark:text-text-light/80 max-w-2xl mx-auto">Conoce los detalles más importantes sobre nuestros sorteos,
                reglas y políticas para una experiencia transparente y confiable.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            @include('partials.home.info.rules')
            @include('partials.home.info.legal-notice')
        </div>

        <!-- Preguntas Frecuentes con diseño de acordeón -->
        @include('partials.home.info.faq')

        <!-- Política de Devoluciones -->
        @include('partials.home.info.refund-policy')
    </div>
</div>
