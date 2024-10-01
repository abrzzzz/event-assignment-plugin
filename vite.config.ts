import path from 'path';
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  server:  {
    port: 1337,
    host: '0.0.0.0',
  },
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./resources/scripts/src"),
      '@components': path.resolve(__dirname, './resources/scripts/src/components'),
      '@lib': path.resolve(__dirname, './resources/scripts/src/urils'),
    },
  },
  plugins: [react()],
  build: {
    manifest: true,
    outDir: 'resources/scripts/dist',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, './resources/scripts/src/app.tsx'),
      },
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: '[name].js',
        assetFileNames: '[name].[ext]',
      },
    },
  },
});