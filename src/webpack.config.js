'use strict';

const ExtractTextPlugin     = require('extract-text-webpack-plugin');
const WebpackNotifierPlugin = require('webpack-notifier');
const webpack               = require('webpack');
const fs                    = require('fs');
const path                  = require('path');
const merge                 = require('merge');

function EmitHash(options) {
  this.options = merge({}, {
    outputPath: '',
    outputFileName: '',
  }, options);
}

EmitHash.prototype.apply = function(compiler) {
  compiler.plugin('after-emit', function(compilation, callback) {
    let output_file = path.join(this.options.outputPath, this.options.outputFileName);

    fs.writeFileSync(
      output_file,
      compilation.getStats().hash
    );

    callback();

    console.log('Writing hash file: ' + output_file);
  }.bind(this));
}

let is_production = false;
process.argv.forEach(function(arg) {
  if (arg === '-p' || arg === '--production') {
    is_production = true;
  }
});

let source_path = __dirname + '/_/src/js/';
let output_path = __dirname + '/_/dist/';

let node_dir = __dirname + '/node_modules/';

let CONFIG = require(source_path + 'util/config.js');

// Get all top level files
let files = fs.readdirSync(source_path);

let entry_points = {};
files.forEach(function(file) {
  if(file[0] === '.') {
    return;
  }

  let stat = fs.statSync(source_path + file);

  if (stat.isFile()) {
    let base_name = path.basename(file, path.extname(file));
    entry_points[base_name] = source_path + file;
  }
});

let config = {
  add_vendor: function (name, path) {
    this.resolve.alias[name] = path;
    this.module.noParse.push(new RegExp(path));
    this.entry[name] = [name];
  },
  entry: entry_points,
  debug: !is_production,
  devtool: is_production ? 'none' : 'cheap-eval-source-map',
  output: {
    path: output_path,
    filename: '[name]' + '.js'
  },
  resolve: { alias: {} },
  module: {
    loaders: [
      {
        test: /\.scss$/,
        loader: ExtractTextPlugin.extract('style-loader', 'css-loader?sourceMap!sass-loader?sourceMap=map')
      },
      {
        test: /\.(jpg|png|svg|gif|eot|ttf|woff|woff2)(\?.+)?$/,
        loader: 'file-loader?name=assets/[name].[ext]'
      },
      {
        test: /\.jsx?$/,
        loader: 'babel-loader',
        query: {
          presets: ['es2015', 'react'],
          retainLines: true,
        },
      },
    ],
    noParse: []
  },
  sassLoader: {
    data: '$ENVIRONMENT: ' + (is_production === true ? 'production' : 'development') + ';',
  },
  plugins: [
    new ExtractTextPlugin('[name]' +  '.css'),
    new WebpackNotifierPlugin(),
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': (is_production === true ? 'production' : 'development'),
    }),
    new webpack.ProvidePlugin({
      '$': 'jquery',
      'jQuery': 'jquery',
      '_': 'lodash',
    }),
    new webpack.DefinePlugin({
      CONFIG: CONFIG,
    }),
    new EmitHash({
      outputPath: output_path,
      outputFileName: 'webpack_hash',
    }),
  ],
};

config.add_vendor('jquery', node_dir + 'jquery/dist/jquery' + (is_production === true ? '.min' : '') +  '.js');
config.add_vendor('lodash', node_dir + 'lodash/lodash' + (is_production === true ? '.min' : '') +  '.js');

module.exports = config;
