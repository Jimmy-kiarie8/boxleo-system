const mix = require('laravel-mix');

/* 
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
// mix.js("resources/js/app.js", "public/js")
//     .postCss("resources/css/app.css", "public/css/tailwind.css", [
//         require("tailwindcss"),
//     ]);

mix.js('resources/js/app.js', 'public/js')
    // .postCss('resources/css/app.css', 'public/css/tailwind.css', [
    //     // require('postcss-import'),
    //     require('tailwindcss'),
    // ])
    // .styles([
    //     'public/landing/css/all.css',
    //     'public/css/custom.css',
    //     'public/landing/css/swiper.css'
    // ],'public/css/home.css')
    .styles([
        'public/css/custom.css',
        'public/css/home2.css',
    ],'public/css/landingpage.css')
    .sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/admin.js', 'public/js/admin.js')
    .js('resources/js/shopify.js', 'public/js/shopify.js')
    .js('resources/js/warehouse.js', 'public/js/warehouse.js')
    .js('resources/js/marketplace.js', 'public/js/marketplace.js')
    .webpackConfig(require('./webpack.config'));

    mix.combine([
        'public/landing/js/swiper.js',
        'public/landing/js/jquery.appear.js',
        'public/landing/js/wow.min.js',
        'public/landing/js/countUp.min.js',
        'public/landing/js/imagesloaded.pkgd.min.js',
        'public/landing/js/jquery.parallax-scroll.js',
        'public/landing/js/jquery.magnific-popup.min.js',
        'public/landing/js/app.js',
        'public/landing/js/header.js'
    ], 'public/js/landingpage.js');

mix.disableNotifications();
