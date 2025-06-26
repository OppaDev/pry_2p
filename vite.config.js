import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Optimizaciones para reducir uso de memoria
    build: {
        rollupOptions: {
            output: {
                manualChunks: undefined, // Deshabilitar chunking manual para reducir memoria
            },
        },
        // Reducir el límite de chunks para usar menos memoria
        chunkSizeWarningLimit: 1000,
        // Optimizar la minificación
        minify: 'esbuild', // esbuild es más rápido y usa menos memoria que terser
        target: 'es2015', // Target más antiguo para reducir transformaciones
    },
    // Optimizar resolución de módulos
    resolve: {
        dedupe: ['alpinejs'], // Dedupe común dependencies
    },
    // Optimizar el servidor de desarrollo
    server: {
        hmr: {
            overlay: false, // Reducir overhead de HMR
        },
    },
});
