import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// Config del curso: la misma de Laravel mas la seccion "server", para que los
// estilos carguen igual en el contenedor local y en GitHub Codespaces.
// Sin esto, el navegador busca al servidor de Vite en una direccion que no
// puede alcanzar desde fuera del contenedor y la pagina se ve SIN estilos.

const enCodespaces = !!process.env.CODESPACE_NAME;
const dominio = process.env.GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN ?? 'app.github.dev';
const hostPublico = `${process.env.CODESPACE_NAME}-5173.${dominio}`;

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        // En Docker sobre Windows, los eventos de "archivo cambiado" no cruzan
        // del disco de Windows al contenedor: sin esto, guardas y el navegador
        // no se actualiza solo (HMR muerto, refresh manual si funciona).
        // El sondeo revisa los archivos cada 300 ms; costo minimo, cura total.
        watch: { usePolling: true, interval: 300 },
        ...(enCodespaces
            ? {
                  // En Codespaces el navegador entra por el dominio publico del puerto 5173
                  // (recuerda ponerlo en "Public" en la pestana PORTS)
                  hmr: { protocol: 'wss', host: hostPublico, clientPort: 443 },
                  origin: `https://${hostPublico}`,
                  // Vite 6 endurecio su CORS (solo acepta localhost de fabrica).
                  // En Codespaces la pagina vive en el dominio -8000 y los assets
                  // en el -5173: sin esto el navegador bloquea @vite/client y
                  // app.js (estilos si cargan, HMR y JS no).
                  cors: { origin: [/\.app\.github\.dev$/, /^https?:\/\/localhost(?::\d+)?$/] },
              }
            : {
                  // Contenedor local o Laragon: el navegador llega por localhost
                  hmr: { host: 'localhost' },
              }),
    },
});
