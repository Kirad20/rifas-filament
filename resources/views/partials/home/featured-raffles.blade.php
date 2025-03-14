<!-- Rifas Destacadas -->
<div class="container mx-auto px-4 py-16">
    <h2 class="text-3xl font-bold mb-8 text-center text-accent dark:text-primary">Rifas Destacadas</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($rifasDestacadas as $rifa)
            <div class="bg-white dark:bg-background-dark/30 rounded-lg shadow-md overflow-hidden rifa-card">
                @if ($rifa->imagen)
                    <img src="{{ asset('storage/' . $rifa->imagen) }}" alt="{{ $rifa->nombre }}"
                        class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <span class="text-gray-400 dark:text-gray-500">Sin imagen</span>
                    </div>
                @endif
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-accent dark:text-primary">{{ $rifa->nombre }}</h3>
                    <p class="text-text dark:text-text-light/80 mb-4">{{ Str::limit($rifa->descripcion, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">${{ number_format($rifa->precio, 2) }}</span>
                        <a href="{{ route('rifas.show', $rifa) }}"
                            class="bg-primary text-text-light px-4 py-2 rounded-lg hover:bg-primary-dark transition btn-primary">Ver
                            más</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-text dark:text-text-light/50">No hay rifas destacadas disponibles en este momento.</p>
            </div>
        @endforelse
    </div>

    <div class="text-center mt-8">
        <a href="{{ route('rifas') }}" class="text-primary font-medium hover:underline">Ver todas las rifas →</a>
    </div>
</div>
