const path = require( 'path' );

module.exports = {
    entry:   {
        app:    './src/ts/',
        styles: './src/ts/core/styles.ts'
    },
    resolve: {
        extensions: [ '.ts', '.tsx', '.js' ],
    },
    module:  {
        rules: [
            {
                test:   /\.tsx?$/,
                loader: 'babel-loader',
            },
            {
                enforce: 'pre',
                test:    /\.js$/,
                loader:  'source-map-loader',
            },
            {
                test: /\.(s*)css$/,
                use:  [ 'style-loader', 'css-loader', 'sass-loader' ]
            },
            {
                test: /\.svg/,
                use:  {
                    loader:  'svg-url-loader',
                    options: {}
                }
            },
            {
                test: /\.(png|jpg|gif)$/i,
                use:  [
                    {
                        loader:  'url-loader',
                        options: {
                            limit: 8192,
                        }
                    }
                ]
            },
            {
                test:    /\.(woff|woff2|eot|ttf)$/,
                loader:  'url-loader',
                options: {
                    limit: 100000
                }
            }
        ]
    },
    output:    {
        filename:      'wpk-[name].js',
        chunkFilename: 'wpk-chunk-[name].js',
        path:          path.join( __dirname, '/public/js' ),
    },
    mode:      process.env.NODE_ENV,
    externals: {
        jquery: 'jQuery'
    }
};
