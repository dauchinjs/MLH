const mix = require('laravel-mix');
const path = require('path');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .babelConfig({
        plugins: ['@babel/plugin-syntax-dynamic-import'],
        presets: [
            [
                '@babel/preset-env',
                {
                    modules: false,
                    useBuiltIns: 'usage',
                    corejs: 3,
                    targets: '> 0.25%, not dead',
                },
            ],
        ],
    })
    .webpackConfig({
        resolve: {
            alias: {
                vue: 'vue/dist/vue.esm-bundler.js',
                ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue'),
            },
        },
    })
    .extract();

mix.postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
]);

if (mix.inProduction()) {
    mix.version();
}
