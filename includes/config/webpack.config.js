/**
 * Default configuration
 */
const path                      = require('path');
const webpack                   = require('webpack');
const dotenv                    = require('dotenv').config({ path: path.join(__dirname, '../../../', '.env') });

const MiniCssExtractPlugin      = require('mini-css-extract-plugin');
const RemoveEmptyScriptsPlugin  = require('webpack-remove-empty-scripts');
const ImageMinimizerPlugin      = require('image-minimizer-webpack-plugin');
const svgToMiniDataURI          = require('mini-svg-data-uri');
const BrowserSyncPlugin         = require('browser-sync-webpack-plugin');
const CaseSensitivePathsPlugin  = require('case-sensitive-paths-webpack-plugin');
const SpeedMeasurePlugin        = require('speed-measure-webpack-plugin');
const WebpackBar                = require('webpackbar');
const WebpackNotifierPlugin     = require('webpack-notifier');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const ShowAssetsTablePlugin     = require('webpack-show-assets-table')

const { THEME: themeName, WP_HOME, BROWSER } = process.env;
const themeFolder = `web/app/themes/${themeName}`;

// Define is it production mode or not
const isProd = process.env.NODE_ENV === 'production';

// Which service is on
const isServer = process.env.LIVE === 'server';

// Filename output for js and css files
const fileName = (ext) => `${ext}/[name].[contenthash:8].${ext}`;

// Public path for output
const publicPath = '../';

// All plugins
const plugins = [

	new MiniCssExtractPlugin({
		filename: () => fileName('css'),
	}),

	new RemoveEmptyScriptsPlugin(),

	new WebpackNotifierPlugin({
		title: process.env.NAME,
		excludeWarnings: true,
	}),

	new WebpackManifestPlugin(),

	new WebpackBar({
		name: process.env.NAME,
	}),

	new CaseSensitivePathsPlugin(),
];

if(!process.env.LIVE) {
	plugins.push(
		new ShowAssetsTablePlugin(),
	)
}

if(!isProd) {
	plugins.push(
		new webpack.SourceMapDevToolPlugin({
			filename: '[file].map',
		}),
	)
}

if(!isServer) {
	plugins.push(
		new BrowserSyncPlugin(
			{
				proxy: WP_HOME,
				files: [
					path.resolve(themeFolder, './resources/views/**/*.twig'),
					path.resolve(themeFolder, './**/*.php'),
					path.resolve(themeFolder, './style.css'),
					path.resolve('./config/**/*.php'),
					path.resolve('./framework/**/*.php'),
				],
				browser: BROWSER,
				notify: false,
				open: true,
			}
		),
	);
}

const config = {

	context: path.resolve(themeFolder),

	target: isProd ? 'browserslist' : 'web',

	devtool: isProd ? false : 'eval-cheap-source-map',

	entry: () => new Promise((resolve) => resolve(
		require('../../../aliha.config'),
	)),

	output: {
		path: path.resolve(themeFolder, 'public'),
		filename: () => fileName('js'),
		publicPath: '',
		clean: {
			dry: false, // change to true for testing.
			keep: (asset) => asset.includes('languages') || asset.includes('.gitignore'),
		},
		pathinfo: !isProd,
		hotUpdateChunkFilename: '[id].hot-update.js',
		hotUpdateMainFilename: '[runtime].hot-update.json',
	},

	stats: 'errors-only',

	devServer: {
		overlay: {
			errors: true,
			warnings: false,
		},
		headers: {
			'Access-Control-Allow-Origin': '*',
		},
		disableHostCheck: !isProd,
		host: 'localhost',
		port: 3100,
		public: 'http://localhost:3100',
		contentBase: [
			path.resolve(themeFolder, 'public'),
			path.resolve(themeFolder, 'resources'),
		],
		stats: 'errors-only',
		compress: true,
		inline: true,
		noInfo: false,
		historyApiFallback: true,
		open: false,
		writeToDisk: true,
		watchContentBase: true,
		proxy: {
			'/': {
				target: WP_HOME,
				secure: false,
				changeOrigin: true,
				autoRewrite: true,
				headers: {
					'X-ProxiedBy-Webpack': true,
				},
			},
		},
	},

	resolve: {
		alias: {
			'@': path.resolve(themeFolder), // theme root folder
			'@js': path.resolve(themeFolder, 'resources/assets/js'),
			'@img': path.resolve(themeFolder, 'resources/assets/img'),
			'@sass': path.resolve(themeFolder, 'resources/assets/sass'),
			'@icons': path.resolve(themeFolder, 'resources/assets/icons'),
			'@fonts': path.resolve(themeFolder, 'resources/assets/fonts'),
			'@resources': path.resolve(themeFolder, 'resources'),
			'modernizr$': path.resolve('.modernizrrc'),
		},
		symlinks: false,
	},

	plugins,

	optimization:
	isProd ?
		{
			runtimeChunk: 'single',
			splitChunks: {
				chunks: 'all',
				maxInitialRequests: Infinity,
				minSize: 0,
				cacheGroups: {
					vendor: {
						test: /[\\/]node_modules[\\/]/,
						name(module) {
							const packageName = module.context.match(/[\\/]node_modules[\\/](.*?)([\\/]|$)/)[1];
							return `libs/${packageName.replace('@', '')}`;
						},
					},
				},
			},
		} :
		{},

	module: {
		rules: [
			{
				test: /\.modernizrrc(\.json)?$/,
				use: [ 'modernizr-loader', 'json-loader' ],
			},
			{
				test: /\.m?js$/,
				exclude: [
					/@babel(?:\/|\\{1,2})runtime|core-js/,
					/(node_modules|bower_components)/,
				],
				loader: 'babel-loader',
			},
			{
				test: /font\.js$/i,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							url: false,
						},
					},
					{
						loader: 'webfonts-loader',
						options: {
							publicPath,
						},
					},
				],
			},
			{
				test: /\.(s?)(a|c)ss$/i,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
						options: {
							publicPath,
						},
					},
					{
						loader: 'css-loader',
					},
					{
						loader: 'postcss-loader',
						options: {
							postcssOptions: {
								plugins: isProd ? {
									'autoprefixer': {},
									'cssnano': { preset: 'default' },
								} :
									{},
							},
						},
					},
					{
						loader: 'sass-loader',
					},
				],
			},
			{
				test: /\.(png|jp(e?)g|gif|ico)$/,
				include: path.resolve(themeFolder, 'resources/assets/img'),
				type: 'asset',
				generator: {
					filename: 'img/[name].[hash:8].[ext]'
				},
	   			parser: {
		 			dataUrlCondition: {
		   				maxSize: 8 * 1024, // 8kb
		 			},
	   			},
				use: [
					{
						loader: ImageMinimizerPlugin.loader,
						options: {
							severityError: 'warning',
							minimizerOptions: {
								plugins: [
									['gifsicle', { interlaced: true, optimizationLevel: 3 }],
									['mozjpeg', { quality: 80 }],
									['pngquant', { quality: [0.6, 0.8] }],
								],
							},
						},
					},
				],
			},
			{
				test: /\.svg$/,
				include: path.resolve(themeFolder, 'resources/assets/icons'),
				type: 'asset/inline',
				generator: {
					dataUrl: content => svgToMiniDataURI(content.toString())
				},
				use: [
					{
						loader: ImageMinimizerPlugin.loader,
						options: {
							severityError: 'warning',
							minimizerOptions: {
								plugins: [
									['svgo', { plugins: [{ removeViewBox: false }] } ],
								],
							},
						},
					},
				],
			},
			{
				test: /\.(ttf|eot|woff(2?)|svg)$/,
				include: path.resolve(themeFolder, 'resources/assets/fonts'),
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name]/[name].[hash:8].[ext]'
				},
			},
		],
	},
};

/**
 * Currently there is a bug with this plugin
 *
 * When it will be fixed, just wrap config into smp.wrap()
 */
const smp = new SpeedMeasurePlugin();
module.exports = config;
