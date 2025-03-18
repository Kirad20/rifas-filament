<!-- Preguntas Frecuentes con diseño de acordeón -->
<section id="faq" class="bg-white dark:bg-background-dark/30 p-8 rounded-xl shadow-md mt-8">
    <div class="flex items-center mb-6">
        <div class="bg-primary-light/30 p-3 rounded-full mr-4">
            <div class="text-2xl text-primary">❓</div>
        </div>
        <h2 class="text-2xl font-bold text-accent dark:text-primary">Preguntas Frecuentes</h2>
    </div>

    <div class="space-y-4" x-data="{ selected: 0 }">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <button @click="selected !== 1 ? selected = 1 : selected = null"
                class="flex justify-between items-center w-full px-6 py-4 text-left font-medium hover:bg-primary-light/10 focus:outline-none"
                :class="{ 'bg-primary-light/20 dark:bg-primary/20': selected === 1 }">
                <span class="text-text dark:text-text-light">¿Cómo puedo participar?</span>
                <span class="h-5 w-5 text-primary transition-transform inline-block"
                    :class="{ 'rotate-180': selected === 1 }">▼</span>
            </button>
            <div x-show="selected === 1" class="px-6 py-4 text-text dark:text-text-light/80 border-t border-gray-100 dark:border-gray-700 bg-background dark:bg-background-dark/50">
                <p>Para participar, simplemente navegue por nuestro catálogo de rifas activas, seleccione la que
                    le interese y proceda a comprar un boleto. Puede elegir el número de boleto o permitir que
                    nuestro sistema lo asigne aleatoriamente. Una vez realizado el pago, recibirá un correo
                    electrónico con la confirmación y los detalles de su participación.</p>
            </div>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <button @click="selected !== 2 ? selected = 2 : selected = null"
                class="flex justify-between items-center w-full px-6 py-4 text-left font-medium hover:bg-primary-light/10 focus:outline-none"
                :class="{ 'bg-primary-light/20 dark:bg-primary/20': selected === 2 }">
                <span class="text-text dark:text-text-light">¿Cuándo se realiza el sorteo?</span>
                <span class="h-5 w-5 text-primary transition-transform inline-block"
                    :class="{ 'rotate-180': selected === 2 }">▼</span>
            </button>
            <div x-show="selected === 2" class="px-6 py-4 text-text dark:text-text-light/80 border-t border-gray-100 dark:border-gray-700 bg-background dark:bg-background-dark/50">
                <p>Los sorteos se realizan en las fechas indicadas en la descripción de cada rifa. Todas
                    nuestras transmisiones de sorteo son en vivo a través de nuestras redes sociales oficiales,
                    lo que garantiza total transparencia. Recomendamos seguirnos en redes para recibir
                    recordatorios sobre los próximos sorteos.</p>
            </div>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <button @click="selected !== 3 ? selected = 3 : selected = null"
                class="flex justify-between items-center w-full px-6 py-4 text-left font-medium hover:bg-primary-light/10 focus:outline-none"
                :class="{ 'bg-primary-light/20 dark:bg-primary/20': selected === 3 }">
                <span class="text-text dark:text-text-light">¿Cómo sabré si gané?</span>
                <span class="h-5 w-5 text-primary transition-transform inline-block"
                    :class="{ 'rotate-180': selected === 3 }">▼</span>
            </button>
            <div x-show="selected === 3" class="px-6 py-4 text-text dark:text-text-light/80 border-t border-gray-100 dark:border-gray-700 bg-background dark:bg-background-dark/50">
                <p>Los ganadores serán notificados por correo electrónico y llamada telefónica utilizando los
                    datos proporcionados durante la compra. Adicionalmente, publicaremos los resultados en
                    nuestro sitio web y redes sociales. Por esto es importante proporcionar información de
                    contacto correcta al momento de registrarse.</p>
            </div>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <button @click="selected !== 4 ? selected = 4 : selected = null"
                class="flex justify-between items-center w-full px-6 py-4 text-left font-medium hover:bg-primary-light/10 focus:outline-none"
                :class="{ 'bg-primary-light/20 dark:bg-primary/20': selected === 4 }">
                <span class="text-text dark:text-text-light">¿Cómo reclamar mi premio?</span>
                <span class="h-5 w-5 text-primary transition-transform inline-block"
                    :class="{ 'rotate-180': selected === 4 }">▼</span>
            </button>
            <div x-show="selected === 4" class="px-6 py-4 text-text dark:text-text-light/80 border-t border-gray-100 dark:border-gray-700 bg-background dark:bg-background-dark/50">
                <p>Una vez notificado como ganador, deberás contactar con nuestro equipo en un plazo máximo de
                    30 días calendario. Dependiendo del tipo de premio, podremos enviarlo a tu domicilio o
                    coordinar su entrega en nuestras oficinas. Para premios de alto valor, se requiere
                    identificación oficial y la firma de un acta de entrega.</p>
            </div>
        </div>
    </div>
</section>
