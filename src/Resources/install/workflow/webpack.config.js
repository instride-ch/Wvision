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
    UIkit: 'uikit',
    'window.UIkit': 'uikit'
  })
  .addEntry('js/app', `${paths.resources}/js/main.js`)
  .addLoader({
    test: /\.js$/,
    exclude: /node_modules/,
    loader: 'eslint-loader'
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
  }, {
    resolveUrlLoader: false
  })
  .enablePostCssLoader()
  .addPlugin(new StyleLintPlugin())

  // Copy and optimize images
  .addPlugin(new CopyPlugin([{
    from: `${paths.resources}/img`,
    to: `${paths.output}/images`
  }], {
    ignore: [
      'ico/*',
      '.dummy'
    ]
  }))
  .addPlugin(new ImageminPlugin({
    disable: !Encore.isProduction(),
    test: /\.(jpe?g|png|gif|svg)$/i
  }))

  // Source maps, cache buster and build notifications
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
;

let webpackConfig = Encore.getWebpackConfig();

// Advanced webpack config
webpackConfig.watchOptions = { ignored: `${paths.vendor}/` };
webpackConfig.resolve.extensions.push('json');

module.exports = webpackConfig;