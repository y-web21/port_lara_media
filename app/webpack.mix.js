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
  .postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
  ])
  .copyDirectory('resources/js/components/**', 'public/js')
  .browserSync({
    // proxy: "http://localhost:50080", // Docker ホストから npx mix watch する場合
    proxy: "web",  // コンテナ内から npx mix watch する場合はコンテナ名を指定する
    port: 3000,
    ui: { port: 3001 },
    files: ['./resources/**/*', './public/**/*', './app/**/*'],
    open: true,
    reloadOnRestart: true,
  });
