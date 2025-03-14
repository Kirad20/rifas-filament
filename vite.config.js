import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/theme.css', // Añadir el nuevo archivo CSS
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
