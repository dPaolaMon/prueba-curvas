import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
	// Descomentar esto y volver a construir con la base en producción
	// base: '/sistema/build/',
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/kiosko-inicio.js',
            ],
            refresh: true,
        }),
    ],
});
