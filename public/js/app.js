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

    // ... el resto del código JavaScript existente ...
});
