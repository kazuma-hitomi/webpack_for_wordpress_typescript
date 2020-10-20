const path = require('path')
const autoprefixer = require('autoprefixer')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin')

const outputPath = path.resolve(__dirname, './')

module.exports = {
  entry: {
    main: './src/js/main.ts',
    top: './src/js/top.ts',
  },
  output: {
    path: outputPath,
    filename: './assets/js/[name].js',
  },
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.ts$/,
        loader: 'eslint-loader',
        exclude: /node_modules/,
      },
      {
        test: /\.ts$/,
        use: ['babel-loader', 'ts-loader'],
        exclude: /node_modules/,
      },
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              ident: 'postcss',
              plugins: [
                autoprefixer({
                  grid: true,
                }),
              ],
            },
          },
          'sass-loader',
        ],
      },
      {
        test: /\.(jpg|png|gif|svg)$/,
        use: {
          loader: 'file-loader',
          options: {
            name: '[name].[ext]',
            outputPath: './assets/images/',
            publicPath: (outputPath) => `../../assets/images/${outputPath}`,
          },
        },
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: './assets/css/[name].css',
    }),
  ],
  optimization: {
    minimizer: [new OptimizeCSSAssetsPlugin()],
  },
}
