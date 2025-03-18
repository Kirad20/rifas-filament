import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
        // Removemos cssCodeSplit: false que causa el problema
        target: 'es2015',
    },
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
});
