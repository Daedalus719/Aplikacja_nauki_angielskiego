import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/scrabble-game.css',
                'resources/css/flashcards.css',
                'resources/css/sections.css',
                'resources/js/app.js',
                'resources/js/messageHandler.js',
                'resources/js/translation-game.js',
                'resources/js/scrabble-game.js',
                'resources/js/scrabble-setup.js',
                'resources/js/test.js',
                'resources/js/dictionary.js',
                'resources/js/admin.js',
                'resources/js/irregular-verbs.js',
                'resources/js/verb-task.js',
                'resources/js/flashcards.js',
                'resources/js/flashcards-test.js'
            ],
            refresh: true,
        }),
    ],
});
