<!-- filepath: resources/views/components/raffle-card.blade.php -->
<div class="card hover-scale mb-4">
    <div class="relative overflow-hidden raffle-image-container">
        <!-- Badge para rifas próximas a finalizar -->
        @if ($raffle->ends_soon)
            <div
                class="absolute top-0 right-0 bg-danger text-white px-3 py-1 m-2 rounded-full font-bold z-10 animate-pulse">
                ¡Termina pronto!
            </div>
        @endif

        <!-- Imágenes con efecto carrusel en hover -->
        <div class="raffle-images">
            <img src="{{ $raffle->main_image }}" class="w-full h-64 object-cover main-image" alt="{{ $raffle->title }}">

            <div class="hover-images hidden">
                @foreach ($raffle->images->take(3) as $image)
                    <img src="{{ $image->url }}" class="w-full h-64 object-cover" alt="{{ $raffle->title }}">
                @endforeach
            </div>
        </div>

        <!-- Categoría con icono personalizado -->
        <div class="absolute bottom-0 left-0 bg-primary bg-opacity-80 text-white px-3 py-1 m-2 rounded-full">
            <i class="fas {{ getCategoryIcon($raffle->category) }} mr-1"></i>
            {{ $raffle->category->name }}
        </div>
    </div>

    <div class="p-4">
        <h3 class="text-xl font-bold mb-2">{{ $raffle->title }}</h3>

        <div class="flex justify-between items-center mb-3">
            <!-- Precio llamativo -->
            <div class="price-tag">
                <span class="text-sm">Boleto desde</span>
                <span class="text-2xl font-bold text-primary">{{ formatMoney($raffle->ticket_price) }}</span>
            </div>

            <!-- Progreso de ventas -->
            <div class="text-sm text-gray-600">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-secondary h-2.5 rounded-full" style="width: {{ $raffle->sold_percentage }}%"></div>
                </div>
                <span class="text-xs">{{ $raffle->sold_percentage }}% Vendido</span>
            </div>
        </div>

        <!-- Fecha del sorteo con contador regresivo -->
        <div class="flex justify-between items-center mb-3">
            <div>
                <i class="far fa-calendar-alt mr-1"></i>
                <span>{{ formatDate($raffle->draw_date) }}</span>
            </div>
            <div class="countdown" data-countdown="{{ $raffle->draw_date->timestamp }}"></div>
        </div>

        <a href="{{ route('raffles.show', $raffle) }}"
            class="block w-full py-2 px-4 bg-primary text-white text-center font-bold rounded-lg hover:bg-opacity-90 transition">
            Ver Detalles
        </a>
    </div>
</div>

<script>
    // Efecto de carrusel en hover
    document.addEventListener('DOMContentLoaded', function() {
        const raffleCards = document.querySelectorAll('.raffle-image-container');

        raffleCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                const mainImage = this.querySelector('.main-image');
                const hoverImages = this.querySelector('.hover-images');

                if (hoverImages.children.length > 0) {
                    mainImage.style.display = 'none';
                    hoverImages.classList.remove('hidden');

                    // Iniciar carrusel automático
                    let currentIndex = 0;
                    const images = hoverImages.children;

                    images[0].style.display = 'block';

                    setInterval(() => {
                        images[currentIndex].style.display = 'none';
                        currentIndex = (currentIndex + 1) % images.length;
                        images[currentIndex].style.display = 'block';
                    }, 1000);
                }
            });

            card.addEventListener('mouseleave', function() {
                const mainImage = this.querySelector('.main-image');
                const hoverImages = this.querySelector('.hover-images');

                mainImage.style.display = 'block';
                hoverImages.classList.add('hidden');
            });
        });
    });
</script>
