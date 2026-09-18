const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');

// 🔧 Головні ручні entry-точки
const entry = {
    styles: path.join(root, 'style/style.scss'),
    main: path.join(root, 'js/logic.js')
};

// 🧠 Автоматичні шаблонні SCSS → *_style.css
const scssDir = path.join(root, 'style/templates');
const scssEntryNames = [];
fs.readdirSync(scssDir).forEach((file) => {
    if (file.endsWith('.scss')) {
        const name = path.basename(file, '.scss') + '_style';
        entry[name] = path.join(scssDir, file);
        scssEntryNames.push(name);
    }
});

// ⚙️ Автоматичні JS з parts → *_script.js
const jsPartsDir = path.join(root, 'js/parts');
const jsEntryNames = [];
fs.readdirSync(jsPartsDir).forEach((file) => {
    if (file.endsWith('.js') && !file.startsWith('vendor')) {
        const name = path.basename(file, '.js') + '_script';
        entry[name] = path.join(jsPartsDir, file);
        jsEntryNames.push(name);
    }
});

module.exports = { root, entry, scssEntryNames, jsEntryNames };
