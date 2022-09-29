const { glob } = require('glob')
const path = require('path')
const PugPlugin = require('pug-plugin')

const assetPath = 'storage/app/assets/'

function asset(_path) {
    return path.join(assetPath, _path)
}

module.exports = {
  // Set to production to minify JS files.
  mode: 'development',

  // Handle all .pug files into the pages folder
  // so all this pages will be an entry points.
  entry: glob.sync('./resources/views/pages/*.pug').reduce((obj, el) => {
    obj[path.parse(el).name] = el

    return obj
  }, {}),

  // Set output options.
  output: {
    path: __dirname,
    publicPath: '/',
    filename: asset('js/[name].compiled.js'),
  },

  plugins: [
    // Enable processing of Pug files defined in webpack entry.
    new PugPlugin({
      extractCss: {
        filename: asset('css/[name].compiled.css'),
        },
      filename: '/views/[name].clbr.html',
    }),
  ],

  // Needs to use require typescript files inside pug.
  resolve: {
    extensions: ['.ts', '.tsx', '.js'],
    alias: {
      '@': path.join(__dirname, 'resources/'),
    },
  },

  module: {
    rules: [
      {
        test: /\.(png|jpg|jpeg|svg|webp|ico)/,
        type: 'asset/resource',
        generator: {
          filename: asset('img/[name][ext]'),
        },
      },
      {
        test: /\.(woff2|woff|ttf|otf|svg|eot)/,
        type: 'asset/resource',
        generator: {
          filename: asset('fonts/[name][ext]'),
        },
      },
      {
        test: /\.(pug|html)$/,
        loader: PugPlugin.loader,
        options: {
          method: 'render',
          embedFilters: {
            escape: true,
            markdown: {
                highlight: {
                verbose: true,
                use: 'prismjs',
              },
            },
          },
        },
      },
      {
        test: /\.(css|sass|scss)$/,
        use: ['css-loader', 'postcss-loader', 'sass-loader'],
      },
      {
        test: /\.tsx?$/,
        use: 'ts-loader',
        exclude: /node_modules/,
      },
    ],
  },
}
