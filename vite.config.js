import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/header.js', 'resources/js/search.js', 'resources/js/post.js','resources/js/user.js','resources/js/admin.js', 'resources/js/faq.js', 'resources/js/home.js'],
            refresh: true,
        }),
    ],
});
