var config = require('../config');
if (!config.tasks.js) return;

var path = require('path');
var pathToUrl = require('./pathToUrl');
var webpack = require('webpack');
var webpackManifest = require('./webpackManifest');

module.exports = function (env) {
    var jsSrc = path.resolve(config.root.src, config.tasks.js.src);
    var jsDest = path.resolve(config.root.dest, config.tasks.js.dest);
    var publicPath = pathToUrl(config.tasks.js.dest, '/');

    var extensions = config.tasks.js.extensions.map(function (extension) {
        return '.' + extension;
    });

    var filenamePattern = '[name].js';

    var webpackConfig = {
        entry: {
            vendor: [
                'jquery',
                'uikit',
                'uikit-icons'
            ]
        },
        context: jsSrc,
        plugins: [],
        resolve: {
            modules: [
                'node_modules',
                jsSrc
            ],
            extensions: [''].concat(extensions)
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    loader: 'babel-loader',
                    options: {
                        'presets': [
                            'es2015',
                            'stage-1'
                        ],
                        'plugins': []
                    }
                }
            ]
        }
    };

    if (config.tasks.js.hasOwnProperty('aliases')) {
        Object.keys(config.tasks.js.aliases).forEach(function (key) {
            webpackConfig.resolve.alias[key] = config.tasks.js.aliases[key];
        });
    }

    if (env === 'development') {
        webpackConfig.devtool = 'inline-source-map';

        // Create new entries object with webpack-hot-middleware added
        for (var key in config.tasks.js.entries) {
            var entry = config.tasks.js.entries[key];
            config.tasks.js.entries[key] = ['webpack-hot-middleware/client?&reload=true'].concat(entry);
        }

        webpackConfig.plugins.push(new webpack.HotModuleReplacementPlugin());
    }

    if (env !== 'test') {
        // Karma doesn't need entry points or output settings
        webpackConfig.entry = config.tasks.js.entries;

        webpackConfig.output = {
            path: path.normalize(jsDest),
            filename: filenamePattern,
            publicPath: publicPath
        };

        if (config.tasks.js.extractSharedJs) {
            // Factor out common dependencies into a shared.js
            webpackConfig.plugins.push(
                new webpack.optimize.CommonsChunkPlugin({
                    name: 'shared',
                    filename: filenamePattern
                })
            );
        }
    }

    if (env === 'production') {
        webpackConfig.plugins.push(
            new webpack.DefinePlugin({
                'process.env': {
                    'NODE_ENV': JSON.stringify('production')
                }
            }),
            new webpack.optimize.UglifyJsPlugin(),
            new webpack.NoEmitOnErrorsPlugin()
        );
    }

    return webpackConfig;
};
