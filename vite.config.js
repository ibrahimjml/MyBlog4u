import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 
              'resources/js/app.js',
              'resources/js/prism.js',
              'resources/js/tinymce.js',
              'resources/js/medium-zoom.js',
              'resources/css/custom/auth-animate.css'
              ],
      refresh: true,
        }),
      ],
      server: {
            host: '0.0.0.0',
            port: 5173,
            hmr: {
                host: 'localhost',
                clientPort: 5173,
            },
        },
});
