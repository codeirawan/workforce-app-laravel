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

mix.scripts([
        'resources/themes/keen-1-4-3/theme/demo1/dist/assets/plugins/global/plugins.bundle.js',
        'resources/themes/keen-1-4-3/theme/demo1/dist/assets/js/scripts.bundle.js'
    ], 'public/js/app.js')
    .styles([
        'resources/themes/keen-1-4-3/theme/demo1/dist/assets/plugins/global/plugins.bundle.css',
        'resources/themes/keen-1-4-3/theme/demo1/dist/assets/css/style.bundle.css',
        'resources/themes/keen-1-4-3/theme/demo1/dist/assets/css/skins/header/base/light.css',
        'resources/themes/keen-1-4-3/theme/demo1/dist/assets/css/skins/header/menu/light.css',
        'resources/themes/keen-1-4-3/theme/demo1/dist/assets/css/skins/brand/light.css',
        'resources/themes/keen-1-4-3/theme/demo1/dist/assets/css/skins/aside/light.css',
        'public/css/custom.css'
    ], 'public/css/app.css')
    .styles(['resources/themes/keen-1-4-3/theme/demo1/dist/assets/css/pages/login/login-v2.css'], 'public/css/auth.css')
    .js('resources/themes/keen-1-4-3/theme/demo1/dist/assets/js/pages/custom/user/login.js', 'public/js/auth.js')
    .styles(['resources/themes/keen-1-4-3/theme/demo1/dist/assets/css/pages/error/404-v3.css'], 'public/css/404.css')
    .js('resources/themes/keen-1-4-3/theme/demo1/dist/assets/js/pages/components/forms/validation/controls.js', 'public/js/form/validation.js')
    .styles(['resources/themes/keen-1-4-3/theme/demo1/dist/assets/plugins/custom/datatables/datatables.bundle.css'], 'public/css/datatable.css')
    .scripts(['resources/themes/keen-1-4-3/theme/demo1/dist/assets/plugins/custom/datatables/datatables.bundle.js'], 'public/js/datatable.js')
    .js('resources/themes/keen-1-4-3/theme/demo1/dist/assets/js/pages/components/base/tooltips.js', 'public/js/tooltip.js')
    .js('resources/js/customd-jquery-number/jquery.number.js', 'public/js/form/thousand-separator.js')
    .styles(['resources/themes/keen-1-4-3/theme/demo1/dist/assets/css/pages/wizards/wizard-v1.css'], 'public/css/form/wizard.css')
    .js('resources/js/app.js', 'public/js/leaflet-geosearch.js')
    .version();