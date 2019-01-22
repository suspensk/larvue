const webpack = require("webpack");

module.exports = {

  // proxy API requests to Valet during development
  devServer: {
    historyApiFallback: true,
    noInfo: true,
    proxy: {
      '/api': {
        target: 'http://larvue',
        changeOrigin: true,
        secure: false,
        cookieDomainRewrite: '',
        onProxyReq: function (request, req, res) {
          request.setHeader('origin', 'http://larvue')
        }
      },
    }
  },

  // output built static files to Laravel's public dir.
  // note the "build" script in package.json needs to be modified as well.
  outputDir: '../public',

  // modify the location of the generated HTML file.
  // make sure to do this only in production.
  indexPath: process.env.NODE_ENV === 'production'
      ? '../resources/views/index.blade.php'
      : 'index.html',

  configureWebpack: {
    plugins: [
      new webpack.ProvidePlugin({
        "window.Quill": "quill/dist/quill.js",
        Quill: "quill/dist/quill.js"
      })
    ]
  }
};
