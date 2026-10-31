import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // app.css + app.js: Tailwind pages. bootstrap-ui.js: pages using layouts/bootstrap.blade.php.
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/bootstrap-ui.js'],
            refresh: true,
            fonts: [
                bunny('Manrope', {
                    weights: [800],
                }),
                bunny('Instrument Sans', {
                    weights: [300, 400, 500, 600, 700],
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
