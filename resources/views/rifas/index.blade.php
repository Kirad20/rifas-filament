@extends('layouts.app')

@section('title', 'Rifas Disponibles')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-12 text-center" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 dark:text-white mb-4">Rifas Disponibles</h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                Encuentra tu próxima oportunidad de ganar entre nuestra selección de rifas
            </p>
        </div>

        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-8" data-aos="fade-up">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Filtrar Rifas</h2>

            <form action="{{ route('rifas.filter') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Categorías -->
                <div>
                    <label for="categoria" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Categoría</label>
                    <select name="categoria" id="categoria"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Todas las categorías</option>
                        <option value="autos">Autos</option>
                        <option value="electronicos">Electrónicos</option>
                        <option value="viajes">Viajes</option>
                        <option value="inmuebles">Inmuebles</option>
                        <option value="otros">Otros</option>
                    </select>
                </div>

                <!-- Rango de precios -->
                <div>
                    <label for="precio" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Precio
                        máximo</label>
                    <div class="flex items-center">
                        <span class="text-gray-500 dark:text-gray-400 mr-2">$</span>
                        <input type="range" name="precio_max" id="precio" min="0" max="10000" step="100"
                            value="10000" class="w-full accent-amber-500" oninput="updatePriceValue(this.value)">
                    </div>
                    <div class="text-center mt-2 font-medium text-amber-600 dark:text-amber-400" id="precio_value">
                        $10,000
                    </div>
                </div>

                <!-- Ordenar por -->
                <div>
                    <label for="ordenar" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Ordenar
                        por</label>
                    <select name="ordenar" id="ordenar"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-amber-500 focus:border-amber-500">
                        <option value="fecha_cercana">Fecha de sorteo (más cercana)</option>
                        <option value="fecha_lejana">Fecha de sorteo (más lejana)</option>
                        <option value="precio_bajo">Precio (de menor a mayor)</option>
                        <option value="precio_alto">Precio (de mayor a menor)</option>
                        <option value="popularidad">Popularidad</option>
                    </select>
                </div>

                <!-- Rifas cercanas -->
                <div class="flex items-end">
                    <div class="flex items-center">
                        <input type="checkbox" name="proximas" id="proximas"
                            class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <label for="proximas" class="ml-2 text-gray-700 dark:text-gray-300 font-medium">Solo próximas a
                            sortearse</label>
                    </div>

                    <button type="submit"
                        class="ml-auto bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-6 rounded-lg transition transform hover:scale-105">
                        <i class="fas fa-search mr-2"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de rifas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($rifasActivas as $rifa)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transform transition hover:scale-105 hover:shadow-xl"
                    data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="relative">
                        @if ($rifa->hasMedia('fotos'))
                            <div class="swiper rifa-swiper-{{ $rifa->id }}">
                                <div class="swiper-wrapper">
                                    @foreach ($rifa->getMedia('fotos') as $foto)
                                        <div class="swiper-slide">
                                            <img src="{{ $foto->getUrl() }}" alt="{{ $rifa->nombre }}"
                                                class="w-full h-64 object-cover">
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Botones de navegación -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <!-- Paginación -->
                                <div class="swiper-pagination"></div>
                            </div>
                        @else
                            <img src="{{ asset('images/default-rifa.jpg') }}" alt="{{ $rifa->nombre }}"
                                class="w-full h-64 object-cover">
                        @endif

                        @if ($rifa->es_popular)
                            <div
                                class="absolute top-4 right-4 bg-red-600 text-white px-4 py-1 rounded-full text-sm font-bold shadow-md">
                                <i class="fas fa-fire mr-1"></i> Popular
                            </div>
                        @endif

                        @if ($rifa->dias_restantes <= 3)
                            <div
                                class="absolute top-4 left-4 bg-amber-500 text-white px-4 py-1 rounded-full text-sm font-bold shadow-md animate-pulse">
                                <i class="fas fa-clock mr-1"></i> ¡Últimos días!
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $rifa->nombre }}</h3>
                            <span
                                class="text-amber-600 dark:text-amber-400 text-2xl font-bold">${{ number_format($rifa->precio, 0) }}</span>
                        </div>

                        <div class="mb-4">
                            <span
                                class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">
                                <i class="fas fa-tag mr-1"></i> {{ $rifa->categoria }}
                            </span>

                            <span
                                class="inline-block bg-amber-100 dark:bg-amber-900 rounded-full px-3 py-1 text-sm font-medium text-amber-800 dark:text-amber-200">
                                <i class="fas fa-calendar mr-1"></i> {{ $rifa->fecha_sorteo->format('d/m/Y') }}
                            </span>
                        </div>

                        <p class="text-gray-600 dark:text-gray-300 mb-6 line-clamp-3">{{ $rifa->descripcion }}</p>

                        <!-- Progreso de ventas -->
                        <div class="mb-6">
                            <div class="flex justify-between text-sm font-medium mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Boletos vendidos</span>
                                <span class="text-amber-600 dark:text-amber-400">{{ $rifa->porcentaje_vendido }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="bg-gradient-to-r from-amber-400 to-amber-600 h-3 rounded-full"
                                    style="width: {{ $rifa->porcentaje_vendido }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('rifas.show', $rifa) }}"
                            class="block w-full bg-amber-500 hover:bg-amber-600 text-white text-center font-bold py-3 px-4 rounded-lg transition">
                            Ver detalles
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-12">
            {{ $rifasActivas->links() }}
        </div>
    </div>

    <script>
        function updatePriceValue(val) {
            document.getElementById('precio_value').innerText = '$' + new Intl.NumberFormat('es-MX').format(val);
        }

        // Inicializar los carruseles de Swiper cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($rifasActivas as $rifa)
                @if ($rifa->hasMedia('fotos'))
                    new Swiper('.rifa-swiper-{{ $rifa->id }}', {
                        slidesPerView: 1,
                        spaceBetween: 0,
                        loop: true,
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                    });
                @endif
            @endforeach
        });
    </script>
@endsection
