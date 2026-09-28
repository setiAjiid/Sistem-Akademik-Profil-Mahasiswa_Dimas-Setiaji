import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Font diunduh saat build lalu disajikan dari server sendiri (bukan CDN).
            // Dimuat di layout lewat directive @fonts.
            fonts: [
                bunny('Plus Jakarta Sans', {
                    weights: [400, 600, 700, 800],
                }),
                bunny('JetBrains Mono', {
                    weights: [400, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
