let mix = require('laravel-mix');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'platform/packages/' + directory;
const dist = 'public/vendor/core/packages/' + directory;

mix
    .sass(source + '/assets/sass/style.scss', dist + '/css')
    .js(source + '/assets/js/script.js', dist + '/js')

    .copy(dist + '/css/style.css', source + '/public/css')
    .copy(dist + '/js/script.js', source + '/public/js');
