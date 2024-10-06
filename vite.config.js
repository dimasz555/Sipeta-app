import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // App-specific CSS
                'resources/js/app.js',   // App-specific JS
                'public/assets/vendor/bootstrap/css/bootstrap.min.css', // External vendor CSS
                'public/assets/css/style.css', // Main template CSS
                'public/assets/js/main.js',  // Main template JS
            ],
            refresh: true,
        }),
    ],
});
