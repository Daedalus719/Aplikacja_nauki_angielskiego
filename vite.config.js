import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/messageHandler.js',
                'resources/js/test.js',
                'resources/js/dictionary.js',
                'resources/js/admin.js',
                'resources/js/irregular-verbs.js',
                'resources/js/verb-task.js',
                'resources/js/sentence-rules.js',
                'resources/js/sentence-rules-create.js',
            ],
            refresh: true,
        }),
    ],
});
