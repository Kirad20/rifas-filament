<!-- filepath: resources/views/components/raffle-filters.blade.php -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold mb-4 border-b pb-2">Filtrar Rifas</h3>

    <form action="{{ route('raffles.index') }}" method="GET" class="space-y-4">
        <!-- Buscador con diseño mejorado -->
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar rifas..."
                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
        </div>

        <!-- Filtros por categoría con iconos -->
        <div>
            <h4 class="font-semibold mb-2">Categorías</h4>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach ($categories as $category)
                    <div class="flex items-center">
                        <input type="checkbox" name="categories[]" id="category-{{ $category->id }}"
                            value="{{ $category->id }}" class="rounded text-primary focus:ring-primary"
                            {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                        <label for="category-{{ $category->id }}" class="ml-2 flex items-center">
                            <i class="fas {{ getCategoryIcon($category) }} mr-1 text-primary"></i>
                            {{ $category->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Filtro por rango de precios -->
        <div>
            <h4 class="font-semibold mb-2">Precio del boleto</h4>
            <div class="px-2">
                <div id="price-slider" class="mb-4"></div>
                <div class="flex justify-between text-sm">
                    <span id="price-min">$<span id="price-min-value">{{ request('price_min', $minPrice) }}</span></span>
                    <span id="price-max">$<span id="price-max-value">{{ request('price_max', $maxPrice) }}</span></span>
                </div>
                <input type="hidden" name="price_min" id="input-price-min"
                    value="{{ request('price_min', $minPrice) }}">
                <input type="hidden" name="price_max" id="input-price-max"
                    value="{{ request('price_max', $maxPrice) }}">
            </div>
        </div>

        <!-- Ordenamiento -->
        <div>
            <h4 class="font-semibold mb-2">Ordenar por</h4>
            <select name="sort"
                class="w-full rounded-lg border border-gray-300 focus:ring-primary focus:border-primary">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Más recientes</option>
                <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>Terminan pronto
                </option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Precio: Menor a mayor
                </option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Precio: Mayor a
                    menor</option>
                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularidad</option>
            </select>
        </div>

        <!-- Switch para mostrar solo rifas activas -->
        <div class="flex items-center">
            <label for="active_only" class="mr-3">Solo rifas activas</label>
            <div class="relative inline-block w-12 align-middle select-none">
                <input type="checkbox" name="active_only" id="active_only" value="1"
                    {{ request('active_only') ? 'checked' : '' }}
                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer">
                <label for="active_only"
                    class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-between space-x-4">
            <button type="submit"
                class="w-full py-3 bg-primary text-white font-bold rounded-lg hover:bg-opacity-90 transition flex items-center justify-center">
                <i class="fas fa-filter mr-2"></i> Aplicar filtros
            </button>
            <a href="{{ route('raffles.index') }}"
                class="w-1/3 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition text-center">
                Limpiar
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/nouislider@14.6.3/distribute/nouislider.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@14.6.3/distribute/nouislider.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar slider de precios
        const slider = document.getElementById('price-slider');
        const minInput = document.getElementById('input-price-min');
        const maxInput = document.getElementById('input-price-max');
        const minValue = document.getElementById('price-min-value');
        const maxValue = document.getElementById('price-max-value');

        const minPrice = {{ $minPrice }};
        const maxPrice = {{ $maxPrice }};

        noUiSlider.create(slider, {
            start: [
                parseInt(minInput.value) || minPrice,
                parseInt(maxInput.value) || maxPrice
            ],
            connect: true,
            step: 10,
            range: {
                'min': minPrice,
                'max': maxPrice
            }
        });

        slider.noUiSlider.on('update', function(values, handle) {
            const value = Math.round(values[handle]);

            if (handle === 0) {
                minInput.value = value;
                minValue.innerHTML = value;
            } else {
                maxInput.value = value;
                maxValue.innerHTML = value;
            }
        });

        // Estilo para el toggle switch
        const style = document.createElement('style');
        style.textContent = `
            .toggle-checkbox:checked {
                right: 0;
                border-color: var(--color-primary);
            }
            .toggle-checkbox:checked + .toggle-label {
                background-color: var(--color-primary);
            }
            .toggle-label {
                transition: background-color 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    });
</script>
