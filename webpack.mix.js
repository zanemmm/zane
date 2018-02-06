let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .scripts([
        'public/js/zepto.min.js',
        'public/js/app.js'
    ], 'public/js/main.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .options({
        postCss: [
            require('postcss-css-variables')()
        ]
    })
    .styles([
        'public/css/normalize.css',
        'public/css/app.css'
    ],'public/css/main.css')
    .browserSync('127.0.0.1:8000');
