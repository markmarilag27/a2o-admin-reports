import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/spa/main.ts', 'resources/css/app.css'],
      refresh: ['resources/**/*.css', 'resources/**/*.ts', 'resources/**/*.vue'],
    }),
    vue(),
    tailwindcss(),
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/spa'),
    },
  },
  server: {
    host: '0.0.0.0',
    hmr: {
      host: 'localhost',
    },
    watch: {
      usePolling: true,
    },
  },
});
