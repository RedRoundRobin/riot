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

mix.js(
    [
        "resources/js/app.js",
        "resources/js/sb-admin-2.js",
        "resources/js/datatables-config.js",
        "resources/js/extra.js",
    ],
    "public/js"
)
    .sourceMaps()
    .sass("resources/sass/app.scss", "public/css")
    .styles(
        [
            "resources/css/datatables.css",
            "resources/css/fontawesome.css",
            "resources/css/sb-admin-2.css",
            "resources/css/theme-edit.css",
        ],
        "public/css/theme.css"
    )
    .copyDirectory("resources/images", "public/images")
    .copyDirectory("resources/webfonts", "public/webfonts");
