const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const extractSass = new ExtractTextPlugin({
  filename: '[name].css',
  disable: process.env.NODE_ENV === 'development',
});

module.exports = {
  entry: {
    index: './client/src/index.js',
    single: './client/src/single.js',
    customizerPreview: './client/src/customizer-preview.js',
  },
  output: {
    filename: '[name].bundle.js',
    path: path.resolve(__dirname, 'client/build'),
  },
  resolve: {
    extensions: ['.js', '.jsx'],
  },
  module: {
    loaders: [
      {
        test: path.join(__dirname, '*.js'),
        loader: 'babel-loader',
      },
    ],
    rules: [{
      test: /\.scss$/,
      use: extractSass.extract({
        use: [
          {
            loader: 'css-loader',
          },
          {
            loader: 'sass-loader',
          },
        ],
        // use style-loader in development
        fallback: 'style-loader',
      }),
    }],
  },
  plugins: [
    extractSass,
  ],
};
