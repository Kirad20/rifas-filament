// Menu móvil
document.addEventListener('DOMContentLoaded', function() {
    // Toggle para el menú móvil
    const menuToggle = document.querySelector('.menu-movil');
    const navegacion = document.querySelector('.navegacion');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navegacion.classList.toggle('activo');
        });
    }

    // Manejo de las preguntas frecuentes (acordeón)
    const faqPreguntas = document.querySelectorAll('.faq-pregunta');

    faqPreguntas.forEach(pregunta => {
        pregunta.addEventListener('click', () => {
            const respuesta = pregunta.nextElementSibling;

            // Cierra todas las otras respuestas
            document.querySelectorAll('.faq-respuesta').forEach(item => {
                if (item !== respuesta) {
                    item.classList.remove('activo');
                }
            });

            // Toggle para esta respuesta
            respuesta.classList.toggle('activo');

            // Cambiar icono
            const icono = pregunta.querySelector('i');
            if (icono) {
                if (respuesta.classList.contains('activo')) {
                    icono.classList.remove('fa-chevron-down');
                    icono.classList.add('fa-chevron-up');
                } else {
                    icono.classList.remove('fa-chevron-up');
                    icono.classList.add('fa-chevron-down');
                }
            }
        });
    });

    // Contador animado para estadísticas
    const estadisticaValores = document.querySelectorAll('.estadistica-valor');

    const animarContadores = () => {
        estadisticaValores.forEach(valor => {
            const objetivo = parseInt(valor.getAttribute('data-valor'));
            const duracion = 2000; // 2 segundos
            let valorActual = 0;
            const incremento = objetivo / (duracion / 16);

            const contador = setInterval(() => {
                valorActual += incremento;
                if (valorActual >= objetivo) {
                    valorActual = objetivo;
                    clearInterval(contador);
                }

                // Si tiene un signo + o % al final
                const signo = valor.getAttribute('data-signo') || '';
                valor.textContent = Math.floor(valorActual) + signo;

                if (valorActual >= objetivo) {
                    clearInterval(contador);
                }
            }, 16);
        });
    };

    // IntersectionObserver para activar la animación cuando sea visible
    if ('IntersectionObserver' in window) {
        const estadisticasSeccion = document.querySelector('.estadisticas');
        if (estadisticasSeccion) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animarContadores();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            observer.observe(estadisticasSeccion);
        }
    } else {
        // Fallback para navegadores que no soporten IntersectionObserver
        animarContadores();
    }
});
