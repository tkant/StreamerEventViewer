let mix = require('laravel-mix');

mix.js('resources/js/main.js', 'dist/app.js');
mix.sass('resources/scss/app.scss', 'dist/');