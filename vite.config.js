const { defineConfig } = require('vite');
const { entry } = require('./build/entries');

console.log('🔍 Vite entries:', Object.keys(entry).join(', '));

module.exports = defineConfig({
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        sourcemap: true,
        // старі бібліотеки (_jquery.js, js/libs/*) вимагають ES2015-сумісного виводу
        target: 'es2015',
        cssMinify: true,
        rollupOptions: {
            input: entry,
            output: {
                entryFileNames: '[name].js',
                assetFileNames: '[name][extname]'
            }
        }
    },
    css: {
        devSourcemap: true
    }
});
