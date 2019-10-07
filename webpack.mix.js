const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js(['resources/js/app.js',
    'resources/js/Active/Sketchpad.js',
], 'public/js/bundle/index.bundle.js')
    .sass('resources/sass/app.scss', 'public/css/bundle/index.bundle.css')
    .combine('resources/js/infoCheck.js', 'public/js/bundle/checkinfo.bundle.js');