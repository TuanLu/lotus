const merge = require('webpack-merge');
const common = require('./webpack.common.js');
var webpack = require('webpack');

module.exports = merge(common, {
  entry: [
    './client/chart.jsx'
  ],
  devtool: 'inline-source-map',
  mode: 'development',
  output: {
    path: __dirname,
    filename: './public/ishopdesign.min.js',
    //filename: '[name].[hash:8].js',
		//chunkFilename: '[name].[bundle].js',
		//path: path.resolve(__dirname, 'dist')
  },
});