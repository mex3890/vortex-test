const path = require('path');
const MiniCss = require('mini-css-extract-plugin');

module.exports = {
    mode: 'development',
    devtool: 'source-map',
    entry: {
        css: path.resolve(__dirname, 'Resources', 'style', 'main.scss'),
        main: path.resolve(__dirname, 'Resources', 'js', 'main.js')
    },
    output: {
        path: path.join(__dirname, 'public'),
    },
    module: {
        rules: [
            {
                test: /.(scss|css)$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: MiniCss.loader,
                    },
                    'css-loader',
                    'postcss-loader',
                    'sass-loader'
                ]
            },
            {
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                        plugins: ['@babel/plugin-transform-runtime']
                    }
                }
            },
        ],
    },
    plugins: [
        new MiniCss({
            filename: 'main.css',
        })
    ],
    devServer: {
        static: path.join(__dirname, 'public')
    }
}
