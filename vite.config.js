import {defineConfig} from 'vite';
import {globSync} from 'glob';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: globSync(['resources/js/*.js', 'resources/css/*.scss']).map(file => file.replace(/[\\\/]+/g, '/')),
            refresh: true,
        }),
        vue({
            base: null,
            includeAbsolute: false
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js'
        }
    }
});
