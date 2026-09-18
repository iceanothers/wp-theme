// 🧠 Точковий dev-watcher.
//
// `vite build --watch` перезбирає й перезаписує ВСІ output-файли на кожну
// зміну (навіть незмінені) — через це PhpStorm auto-upload заливає весь dist/
// при правці одного файлу. Цей скрипт замість цього білдить кожен цикл лише
// ті entry, чиї джерела реально змінились.
const path = require('path');
const chokidar = require('chokidar');
const { build } = require('vite');
const { root, entry, scssEntryNames, jsEntryNames } = require('../build/entries');

const pending = new Set();
let timer = null;
let building = false;

function scheduleBuild(names) {
    names.forEach((n) => entry[n] && pending.add(n));
    clearTimeout(timer);
    timer = setTimeout(runBuild, 150);
}

async function runBuild() {
    if (building) {
        // йде білд — повторимо коли він завершиться
        timer = setTimeout(runBuild, 150);
        return;
    }
    const names = [...pending];
    pending.clear();
    if (!names.length) return;

    building = true;
    const input = {};
    names.forEach((n) => { input[n] = entry[n]; });

    console.log('🔨 rebuild:', names.join(', '));
    try {
        await build({
            root,
            configFile: false,
            logLevel: 'warn',
            build: {
                outDir: 'dist',
                emptyOutDir: false,
                sourcemap: true,
                target: 'es2015',
                cssMinify: true,
                rollupOptions: {
                    input,
                    output: {
                        entryFileNames: '[name].js',
                        assetFileNames: '[name][extname]'
                    }
                }
            },
            css: { devSourcemap: true }
        });
        console.log('✅ done:', names.join(', '));
    } catch (err) {
        console.error('❌ build failed:', err.message || err);
    } finally {
        building = false;
    }
}

// 🔍 Визначаємо, які entry зачіпає змінений файл
function affectedEntries(relPath) {
    if (relPath === 'style/style.scss' || relPath.startsWith('style/core/')) {
        // спільні партіали — впливають на всі scss-entry
        return ['styles', ...scssEntryNames];
    }
    if (relPath.startsWith('style/templates/')) {
        const name = path.basename(relPath, '.scss') + '_style';
        return [name];
    }
    if (relPath === 'js/logic.js') {
        return ['main'];
    }
    if (relPath.startsWith('js/parts/')) {
        if (path.basename(relPath).startsWith('vendor')) return [];
        const name = path.basename(relPath, '.js') + '_script';
        return [name];
    }
    return [];
}

console.log('👀 watching style/**/*.scss, js/logic.js, js/parts/**/*.js');

// перша повна збірка
scheduleBuild(['styles', 'main', ...scssEntryNames, ...jsEntryNames]);

chokidar
    .watch(['style/**/*.scss', 'js/logic.js', 'js/parts/**/*.js'], {
        cwd: root,
        ignoreInitial: true
    })
    .on('all', (event, relPath) => {
        const rel = relPath.split(path.sep).join('/');
        const names = affectedEntries(rel);
        if (names.length) {
            scheduleBuild(names);
        }
    });
