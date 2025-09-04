const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WebpackManifestPlugin = require('webpack-manifest-plugin').WebpackManifestPlugin;
const CopyPlugin = require('copy-webpack-plugin');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');

module.exports = {
    // Вхідні файли для JavaScript
    entry: {
        main: './src/js/main.js',
        leadership: './src/js/leadership.js'
    },
    // Налаштування вихідних файлів
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'assets/js/[name].[contenthash].js',
        clean: true, // Очищувати папку dist перед кожною збіркою
        publicPath: '/', // Важливо для правильних шляхів до ресурсів
    },
    module: {
        rules: [
            // Правило для обробки SCSS файлів
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    // "Перекладач" шляхів, який виправляє url() для sass-loader
                    {
                        loader: 'resolve-url-loader',
                        options: {
                            sourceMap: true, // Потребує sourceMap від попереднього завантажувача
                        },
                    },
                    // Компілятор Sass в CSS
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true, // Важливо для resolve-url-loader
                        },
                    },
                ],
            },
            // Правило для обробки файлів шрифтів
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/i,
                type: 'asset/resource',
                generator: {
                    // Визначає, куди складати шрифти у папці dist
                    filename: 'assets/fonts/[name][ext]'
                }
            },
        ],
    },
    plugins: [
        // Плагін для винесення CSS в окремий файл
        new MiniCssExtractPlugin({
            filename: 'assets/css/[name].[contenthash].css',
        }),
        // Плагін для копіювання статичних файлів та папок
        new CopyPlugin({
            patterns: [
                { from: 'src/pages', to: 'pages' },
                { from: 'src/core', to: 'core' },
                { from: 'src/components', to: 'components' },
                { from: 'src/img', to: 'img' },
            ],
        }),
        // Плагін для створення маніфесту ресурсів
        new WebpackManifestPlugin({
            fileName: 'assets.json',
            publicPath: '',
            generate: (seed, files, entrypoints) => {
                const manifest = {};
                for (const key in entrypoints) {
                    const jsFile = entrypoints[key].find(f => f.endsWith('.js'));
                    if (jsFile) {
                        manifest[`${key}.js`] = jsFile;
                    }
                }
                const cssFiles = files.filter(f => f.name.endsWith('.css'));
                cssFiles.forEach(file => {
                    const key = file.chunk.name + '.css';
                    manifest[key] = file.path;
                });
                return manifest;
            },
        }),
    ],
    // Секція для оптимізації (активується в режимі 'production')
    optimization: {
        minimizer: [
            new ImageMinimizerPlugin({
                minimizer: {
                    implementation: ImageMinimizerPlugin.sharpMinify,
                    options: {
                        encodeOptions: {
                            jpeg: { quality: 80 },
                            png: { quality: 80 },
                        },
                    },
                },
                generator: [
                    {
                        type: "asset",
                        implementation: ImageMinimizerPlugin.sharpGenerate,
                        options: {
                            encodeOptions: { webp: { quality: 80 } },
                            filename: '[path][name].webp'
                        },
                    },
                ],
            }),
        ],
    },
};

