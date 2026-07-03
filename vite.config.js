import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/landingpage.css', 
                'resources/js/landingpage.js',   
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', 
        cors: true, // ◄ TAMBAHKAN BARIS INI (Wajib untuk mengatasi block CORS browser)
        hmr: {
            host: '100.105.123.100' 
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});