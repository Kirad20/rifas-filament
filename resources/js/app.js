import './bootstrap';

// Importar estilos de bibliotecas
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'aos/dist/aos.css';
import 'swiper/css/bundle';

// Importar bibliotecas JS
import AOS from 'aos';
import Swiper from 'swiper';

// Inicializar AOS
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        once: true
    });
});
