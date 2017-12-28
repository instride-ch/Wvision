const path            = require('path');
const Encore          = require('@symfony/webpack-encore');
const CopyPlugin      = require('copy-webpack-plugin');
const ImageminPlugin  = require('imagemin-webpack-plugin').default;
const StyleLintPlugin = require('stylelint-webpack-plugin');

const paths = {
  output: path.resolve(__dirname, './web/build'),
  public: '/build',
  resources: path.resolve(__dirname, './assets'),
  vendor: path.resolve(__dirname, './node_modules')
};

Encore
// Set output and public paths
  .setOutputPath(paths.output)
  .setPublicPath(paths.public)

  // Clean output before build
  .cleanupOutputBeforeBuild()

  // Javascript
  .autoProvideVariables({
    UIkit: 'uikit/dist/js/uikit-core',
    'window.UIkit': 'uikit/dist/js/uikit-core'
  })
  .addEntry('js/app', `${paths.resources}/js/main.js`)
  .addLoader({
    test: /\.js$/,
    exclude: /node_modules/,
    loader: 'eslint-loader'
  })
  .configureBabel(function (babelConfig) {
    babelConfig.presets.push(['env', {
      targets: {
        browsers: [
          'last 2 versions',
          'ios >= 9.1',
          'Safari >= 9.1',
          'not ie <= 10'
        ]
      },
      useBuiltIns: true
    }]);
  })

  // CSS
  .addStyleEntry('css/global', `${paths.resources}/scss/global.scss`)
  .addStyleEntry('css/email', `${paths.resources}/scss/email.scss`)
  .addStyleEntry('css/editmode', `${paths.resources}/scss/editmode.scss`)
  .enableSassLoader(function (options) {
    options.includePaths = [
      `${paths.vendor}/uikit/src/scss`,
      `${paths.vendor}/foundation-emails/scss`
    ]
  }, { resolveUrlLoader: false })
  .enablePostCssLoader()
  .addPlugin(new StyleLintPlugin())

  // Copy and optimize images
  .addPlugin(new CopyPlugin([{
    from: `${paths.resources}/images`,
    to: `${paths.output}/images`
  }], {
    ignore: [
      'favicon.png',
      '.dummy'
    ]
  }))
  .addPlugin(new ImageminPlugin({
    disable: !Encore.isProduction(),
    test: /\.(jpe?g|png|gif|svg)$/i
  }))

  // Source maps and cache buster
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
;

// Advanced webpack config
let webpackConfig = Encore.getWebpackConfig();

webpackConfig.watchOptions = { ignored: `${paths.vendor}/` };
webpackConfig.resolve.extensions.push('json');

module.exports = webpackConfig;
