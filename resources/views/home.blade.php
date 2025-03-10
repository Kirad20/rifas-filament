@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <!-- Hero Banner con overlay y diseño mejorado -->
    <div class="relative bg-gradient-to-r from-amber-600 to-amber-500 text-white overflow-hidden">
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
                        class="text-yellow-300">SorteosMX</span>!</h1>
                <p class="text-xl md:text-2xl mb-10 text-white/90">La plataforma de rifas en línea con premios increíbles,
                    resultados transparentes y la mejor experiencia para nuestros usuarios.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('rifas') }}"
                        class="bg-white text-amber-600 px-8 py-4 rounded-lg font-bold hover:bg-yellow-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Ver rifas disponibles
                    </a>
                    <a href="#como-funciona"
                        class="bg-amber-700 bg-opacity-60 backdrop-blur-sm text-white px-8 py-4 rounded-lg font-bold hover:bg-amber-800 transition border border-white/20">
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

    <!-- Sección de cómo funciona con iconos -->
    <div id="como-funciona" class="bg-gray-50 py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">¿Cómo Funciona?</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Participar en nuestras rifas es muy sencillo. Sigue estos pasos y
                    prepárate para ganar increíbles premios.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="bg-white rounded-xl shadow-md p-6 text-center relative transition-all hover:shadow-lg">
                    <div
                        class="absolute -top-5 left-1/2 transform -translate-x-1/2 bg-amber-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shadow-md">
                        1</div>
                    <div class="h-20 flex items-center justify-center my-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-amber-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Explora</h3>
                    <p class="text-gray-600">Navega por nuestro catálogo de rifas disponibles y encuentra la que más te
                        guste.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center relative transition-all hover:shadow-lg">
                    <div
                        class="absolute -top-5 left-1/2 transform -translate-x-1/2 bg-amber-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shadow-md">
                        2</div>
                    <div class="h-20 flex items-center justify-center my-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-amber-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Compra</h3>
                    <p class="text-gray-600">Selecciona tus boletos favoritos y realiza tu pago de forma rápida y segura.
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center relative transition-all hover:shadow-lg">
                    <div
                        class="absolute -top-5 left-1/2 transform -translate-x-1/2 bg-amber-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shadow-md">
                        3</div>
                    <div class="h-20 flex items-center justify-center my-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-amber-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Espera</h3>
                    <p class="text-gray-600">Aguarda al día del sorteo y sigue la transmisión en vivo para conocer los
                        resultados.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center relative transition-all hover:shadow-lg">
                    <div
                        class="absolute -top-5 left-1/2 transform -translate-x-1/2 bg-amber-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shadow-md">
                        4</div>
                    <div class="h-20 flex items-center justify-center my-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-amber-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Gana</h3>
                    <p class="text-gray-600">Si resultas ganador, te contactaremos para entregarte tu premio.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rifas Destacadas -->
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold mb-8 text-center">Rifas Destacadas</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($rifasDestacadas as $rifa)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if ($rifa->imagen)
                        <img src="{{ asset('storage/' . $rifa->imagen) }}" alt="{{ $rifa->nombre }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Sin imagen</span>
                        </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $rifa->nombre }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($rifa->descripcion, 100) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-amber-500 font-bold">${{ number_format($rifa->precio, 2) }}</span>
                            <a href="{{ route('rifas.show', $rifa) }}"
                                class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition">Ver
                                más</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8">
                    <p class="text-gray-500">No hay rifas destacadas disponibles en este momento.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('rifas') }}" class="text-amber-500 font-medium hover:underline">Ver todas las rifas →</a>
        </div>
    </div>

    <!-- Secciones de información con diseño mejorado -->
    <div class="bg-gray-50 py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span
                    class="inline-block bg-amber-100 text-amber-800 px-4 py-1 rounded-full text-sm font-medium mb-3">INFORMACIÓN
                    IMPORTANTE</span>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Todo lo que necesitas saber</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Conoce los detalles más importantes sobre nuestros sorteos,
                    reglas y políticas para una experiencia transparente y confiable.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Reglas del Sorteo -->
                <section id="reglas" class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-6">
                        <div class="bg-amber-100 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold">Reglas del Sorteo</h2>
                    </div>

                    <div class="prose prose-amber max-w-none">
                        <p class="text-gray-600 mb-4">Todos nuestros sorteos siguen un proceso transparente y justo, regido
                            por las siguientes reglas:</p>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Los participantes deben ser mayores de edad y presentar identificación válida para reclamar
                                premios.
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Cada boleto tiene un número único que se asigna de forma aleatoria al momento de la compra.
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                El sorteo se realizará en la fecha indicada mediante transmisión en vivo en nuestras redes
                                sociales.
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Los ganadores tienen 30 días calendario para reclamar su premio a partir de la fecha del
                                sorteo.
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                SorteosMX se reserva el derecho de modificar las bases del concurso con previo aviso a los
                                participantes.
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- Aviso Legal -->
                <section id="disclaimer" class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-6">
                        <div class="bg-amber-100 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold">Aviso Legal</h2>
                    </div>

                    <div class="prose prose-amber max-w-none">
                        <p class="text-gray-600 mb-2">SorteosMX opera bajo todas las regulaciones legales aplicables para
                            sorteos y rifas en México.</p>
                        <p class="text-gray-600 mb-2">Al adquirir un boleto, el participante acepta los términos y
                            condiciones del sorteo, incluyendo el procesamiento de sus datos personales de acuerdo con
                            nuestra política de privacidad.</p>
                        <p class="text-gray-600 mb-2">Las imágenes de los premios son ilustrativas y pueden variar
                            ligeramente en características y/o color del premio final.</p>
                        <p class="text-gray-600">Los impuestos y gastos de transferencia o escrituración, en caso de
                            aplicar, correrán por cuenta del ganador.</p>

                        <div class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-100">
                            <p class="text-sm text-amber-800 italic">
                                <strong>Nota importante:</strong> SorteosMX no se hace responsable por problemas derivados
                                de información incorrecta proporcionada por el participante al momento de la compra.
                            </p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Preguntas Frecuentes con diseño de acordeón -->
            <section id="faq" class="bg-white p-8 rounded-xl shadow-md mt-8">
                <div class="flex items-center mb-6">
                    <div class="bg-amber-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold">Preguntas Frecuentes</h2>
                </div>

                <div class="space-y-4" x-data="{ selected: 0 }">
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button @click="selected !== 1 ? selected = 1 : selected = null"
                            class="flex justify-between items-center w-full px-6 py-4 text-left font-medium hover:bg-gray-50 focus:outline-none"
                            :class="{ 'bg-amber-50': selected === 1 }">
                            <span>¿Cómo puedo participar?</span>
                            <svg class="h-5 w-5 text-amber-500 transition-transform"
                                :class="{ 'rotate-180': selected === 1 }" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="selected === 1" class="px-6 py-4 text-gray-600 border-t border-gray-100 bg-gray-50">
                            <p>Para participar, simplemente navegue por nuestro catálogo de rifas activas, seleccione la que
                                le interese y proceda a comprar un boleto. Puede elegir el número de boleto o permitir que
                                nuestro sistema lo asigne aleatoriamente. Una vez realizado el pago, recibirá un correo
                                electrónico con la confirmación y los detalles de su participación.</p>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button @click="selected !== 2 ? selected = 2 : selected = null"
                            class="flex justify-between items-center w-full px-6 py-4 text-left font-medium hover:bg-gray-50 focus:outline-none"
                            :class="{ 'bg-amber-50': selected === 2 }">
                            <span>¿Cuándo se realiza el sorteo?</span>
                            <svg class="h-5 w-5 text-amber-500 transition-transform"
                                :class="{ 'rotate-180': selected === 2 }" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="selected === 2" class="px-6 py-4 text-gray-600 border-t border-gray-100 bg-gray-50">
                            <p>Los sorteos se realizan en las fechas indicadas en la descripción de cada rifa. Todas
                                nuestras transmisiones de sorteo son en vivo a través de nuestras redes sociales oficiales,
                                lo que garantiza total transparencia. Recomendamos seguirnos en redes para recibir
                                recordatorios sobre los próximos sorteos.</p>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button @click="selected !== 3 ? selected = 3 : selected = null"
                            class="flex justify-between items-center w-full px-6 py-4 text-left font-medium hover:bg-gray-50 focus:outline-none"
                            :class="{ 'bg-amber-50': selected === 3 }">
                            <span>¿Cómo sabré si gané?</span>
                            <svg class="h-5 w-5 text-amber-500 transition-transform"
                                :class="{ 'rotate-180': selected === 3 }" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="selected === 3" class="px-6 py-4 text-gray-600 border-t border-gray-100 bg-gray-50">
                            <p>Los ganadores serán notificados por correo electrónico y llamada telefónica utilizando los
                                datos proporcionados durante la compra. Adicionalmente, publicaremos los resultados en
                                nuestro sitio web y redes sociales. Por esto es importante proporcionar información de
                                contacto correcta al momento de registrarse.</p>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button @click="selected !== 4 ? selected = 4 : selected = null"
                            class="flex justify-between items-center w-full px-6 py-4 text-left font-medium hover:bg-gray-50 focus:outline-none"
                            :class="{ 'bg-amber-50': selected === 4 }">
                            <span>¿Cómo reclamar mi premio?</span>
                            <svg class="h-5 w-5 text-amber-500 transition-transform"
                                :class="{ 'rotate-180': selected === 4 }" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="selected === 4" class="px-6 py-4 text-gray-600 border-t border-gray-100 bg-gray-50">
                            <p>Una vez notificado como ganador, deberás contactar con nuestro equipo en un plazo máximo de
                                30 días calendario. Dependiendo del tipo de premio, podremos enviarlo a tu domicilio o
                                coordinar su entrega en nuestras oficinas. Para premios de alto valor, se requiere
                                identificación oficial y la firma de un acta de entrega.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Política de Devoluciones -->
            <section id="devoluciones" class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow mt-8">
                <div class="flex items-center mb-6">
                    <div class="bg-amber-100 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold">Política de Devoluciones</h2>
                </div>

                <div class="prose prose-amber max-w-none">
                    <p class="text-gray-600 mb-4">Entendemos que las circunstancias pueden cambiar, por lo que ofrecemos
                        opciones de devolución bajo las siguientes condiciones:</p>

                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Se puede solicitar devolución hasta 48 horas antes del sorteo, sin excepción.
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            La devolución se realizará al mismo método de pago utilizado en la compra original.
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            El proceso de devolución puede tardar de 5 a 10 días hábiles en ser efectivo en su cuenta.
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Para solicitar una devolución, contacte a nuestro servicio de atención al cliente vía correo
                            electrónico o teléfono.
                        </li>
                    </ul>

                    <div class="mt-6 flex items-center bg-amber-50 p-4 rounded-lg border-l-4 border-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 mr-3 flex-shrink-0"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-amber-800 text-sm">
                            <strong>Importante:</strong> No se aceptan solicitudes de devolución menos de 48 horas antes del
                            sorteo, el día del sorteo, ni después de realizado el mismo.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
