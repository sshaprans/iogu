const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');

module.exports = {
    entry: {
        home: [
            './src/js/home.js',
            './src/scss/home.scss',
            './src/js/precision_farming.js',
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
        // anthem: [
        //     './src/js/anthem.js',
        //     './src/scss/anthem.scss'
        // ],
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
        soil_sampling: [
            './src/js/soil_sampling.js',
            './src/scss/soil_sampling.scss'
        ],
        precision_farming: [
            './src/js/precision_farming.js',
            './src/scss/precision_farming.scss'
        ],
        ground_partner: [
            './src/js/ground_partner.js',
            './src/scss/ground_partner.scss'
        ],
        land_survey: [
            './src/js/land_survey.js',
            './src/scss/land_survey.scss'
        ],
        monitoring_objects: [
            './src/js/monitoring_objects.js',
            './src/scss/monitoring_objects.scss'
        ],
        edition: [
            './src/js/edition.js',
            './src/scss/edition.scss'
        ],
        international_activity: [
            './src/js/international_activity.js',
            './src/scss/international_activity.scss'
        ],
        government_procurement: [
            './src/js/government_procurement.js',
            './src/scss/government_procurement.scss'
        ],
        news: [
            './src/js/news.js',
            './src/scss/news.scss'
        ],
        photo_gallery: [
            './src/js/photo_gallery.js',
            './src/scss/photo_gallery.scss'
        ],
        branches: [
            './src/js/branches.js',
            './src/scss/branches.scss'
        ],
        npa: [
            './src/js/npa.js',
            './src/scss/npa.scss'
        ],
        contacts: [
            './src/js/contacts.js',
            './src/scss/contacts.scss'
        ],
        center_si: [
            './src/js/center_si.js',
            './src/scss/center_si.scss'
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
                { from: 'src/public', to: '.' },
                { from: 'src/index.php', to: 'index.php' },
                { from: 'src/pages', to: 'pages' },
                { from: 'src/core', to: 'core' },
                { from: 'src/components', to: 'components' },
                { from: 'src/img', to: 'img' },
                { from: 'src/.htaccess', to: '.htaccess', toType: 'file'},
                { from: 'src/sitemap.php', to: 'sitemap.php' },
                { from: 'src/admin', to: 'admin' },
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

