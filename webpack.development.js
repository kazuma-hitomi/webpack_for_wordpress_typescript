const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const merge = require('webpack-merge')
const common = require('./webpack.common')

module.exports = merge(common, {
  mode: 'development',
  devtool: 'eval-source-map',
  plugins: [
    new BrowserSyncPlugin({
      host: 'localhost',
      port: 3000,
      proxy: 'http://localhost:8080/',
      files: ['./*.php', './**/*.php'],
      watchOptions: {
        poll: 1000,
        ignored: ['./node_modules'],
      },
    }),
  ],
})
