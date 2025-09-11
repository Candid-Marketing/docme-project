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

 mix.js('resources/js/app.js', 'public/js')
    .css('resources/css/login.css', 'public/css')
    .css('resources/css/register.css', 'public/css')
    .css('resources/css/side_menu.css', 'public/css')
    .css('resources/css/admin/side_menu.css', 'public/css/admin')
    .css('resources/css/superadmin/side_menu.css', 'public/css/superadmin')
    .css('resources/css/superadmin/file.css', 'public/css/superadmin')
    .css('resources/css/user/side_menu.css', 'public/css/user')
    .css('resources/css/landing.css', 'public/css')
    .css('resources/css/stripe.css', 'public/css')
    .js('resources/js/superadmin/index.js', 'public/js/superadmin')
    .js('resources/js/superadmin/sidemenu.js', 'public/js/superadmin')
    .js('resources/js/admin/sidemenu.js', 'public/js/admin')
    .js('resources/js/user/sidemenu.js', 'public/js/user')
    .js('resources/js/login.js', 'public/js')
    .js('resources/js/landing.js', 'public/js')
    .copy('resources/imgs', 'public/imgs')
    .copy('resources/img', 'public/img')
    .postCss('resources/css/app.css', 'public/css');
