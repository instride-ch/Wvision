let path            = require('path');
let Encore          = require('@symfony/webpack-encore');
let CopyPlugin      = require('copy-webpack-plugin');
let ImageminPlugin  = require('imagemin-webpack-plugin').default;
let StyleLintPlugin = require('stylelint-webpack-plugin');

let paths = {
  output: path.resolve(__dirname, './web/build'),
  public: '/build',
  resources: path.resolve(__dirname, './assets'),
  vendor: path.resolve(__dirname, './node_modules')
};

Encore
// set output and public paths
  .setOutputPath(paths.output)
  .setPublicPath(paths.public)

  // clean output before build
  .cleanupOutputBeforeBuild()

  // javascript
  .autoProvidejQuery()
  .autoProvideVariables({
    UIkit: 'uikit',
    'window.UIkit': 'uikit'
  })
  .createSharedEntry('js/vendor', [
    'jquery',
    'uikit'
  ])
  .addEntry('js/app', paths.resources + '/js/main.js')
  .addLoader({
    test: /\.js$/,
    exclude: /node_modules/,
    loader: 'eslint-loader'
  })

  // css
  .addStyleEntry('css/global', paths.resources + '/scss/global.scss')
  .addStyleEntry('css/email', paths.resources + '/scss/email.scss')
  .addStyleEntry('css/editmode', paths.resources + '/scss/editmode.scss')
  .enableSassLoader(function(sassOptions) {
    sassOptions.includePaths = [
      paths.vendor + '/uikit/src/scss',
      paths.vendor + '/breakpoint-sass/stylesheets',
      paths.vendor + '/foundation-emails/scss'
    ]
  }, {
    resolve_url_loader: false
  })
  .enablePostCssLoader()
  .addPlugin(new StyleLintPlugin())

  // copy and optimize images
  .addPlugin(new CopyPlugin([{
    from: paths.resources + '/img',
    to: paths.output + '/images'
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

  // source maps and cache buster
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
;

let webpackConfig = Encore.getWebpackConfig();

// advanced webpack config
webpackConfig.watchOptions = { ignored: paths.vendor + '/' };
webpackConfig.resolve.extensions.push('json');

module.exports = webpackConfig;
