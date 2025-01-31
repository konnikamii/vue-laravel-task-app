import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import VueRouter from 'unplugin-vue-router/vite' 
import tailwindcss from '@tailwindcss/vite' 
const RUN_ON_DOCKER = process.env.RUN_ON_DOCKER?.trim() ?? "off";

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    tailwindcss(),
    VueRouter({
      /* options */
    }),
    vue()],
  server: {
    host: '0.0.0.0', 
    port: 3000,
    strictPort: true,
    watch: {
      usePolling: RUN_ON_DOCKER === 'on',
    },
    // proxy: {
    //   "/": "http://localhost:7000",
    // },  
  },
  preview: {
    port:7000,
    strictPort: true,
  }, 
})
