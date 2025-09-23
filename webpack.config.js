const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');

module.exports = {
    entry: {
        home: [
            './src/js/home.js',
            './src/scss/home.scss'
        ],
        history: [
            './src/js/history.js',
            './src/scss/history.scss'
        ],
        activity: [
            './src/js/activity.js',
            './src/scss/activity.scss'
        ],
        structure: [
            './src/js/structure.js',
            './src/scss/structure.scss'
        ],
        anthem: [
            './src/js/anthem.js',
            './src/scss/anthem.scss'
        ],
        anticorruption: [
            './src/js/anticorruption.js',
            './src/scss/anticorruption.scss'
        ],
        leadership: [
            './src/js/leadership.js',
            './src/scss/leadership.scss'
        ],
        square1: [
            './src/js/square.js',
            './src/scss/square.scss'
        ],
        square2: [
            './src/js/square.js',
            './src/scss/square.scss'
        ],
        square3: [
            './src/js/square.js',
            './src/scss/square.scss'
        ],
        square4: [
            './src/js/square.js',
            './src/scss/square.scss'
        ],
        precision_farming: [
            './src/js/precision_farming.js',
            './src/scss/precision_farming.scss'
        ],
    },
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'assets/js/[name].[contenthash].js',
        clean: true,
        publicPath: '/',
        assetModuleFilename: 'assets/resources/[name][ext][query]'
    },
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    {
                        loader: 'resolve-url-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                ],
            },
            {
                test: /\.(png|svg|jpg|jpeg|gif|woff|woff2|eot|ttf|otf)$/i,
                type: 'asset/resource',
                generator: {
                    filename: 'assets/[path][name][ext]'
                }
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'assets/css/[name].[contenthash].css',
        }),
        new CopyPlugin({
            patterns: [
                { from: 'src/index.php', to: 'index.php' },
                { from: 'src/pages', to: 'pages' },
                { from: 'src/core', to: 'core' },
                { from: 'src/components', to: 'components' },
                { from: 'src/img', to: 'img' },
                { from: 'src/.htaccess', to: '.htaccess', toType: 'file'},
            ],
        }),
        new WebpackManifestPlugin({
            fileName: 'assets.json',
            publicPath: '',
            generate: (seed, files, entrypoints) => {
                const manifest = {};
                for (const entry in entrypoints) {
                    const jsFile = entrypoints[entry].find(f => f.endsWith('.js'));
                    const cssFile = entrypoints[entry].find(f => f.endsWith('.css'));
                    if (jsFile) manifest[`${entry}.js`] = jsFile;
                    if (cssFile) manifest[`${entry}.css`] = cssFile;
                }
                return manifest;
            },
        }),
    ],
    optimization: {
        minimizer: [
            new ImageMinimizerPlugin({
                minimizer: {
                    implementation: ImageMinimizerPlugin.sharpMinify,
                    options: {
                        encodeOptions: { jpeg: { quality: 80 }, png: { quality: 80 } },
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

