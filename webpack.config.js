/**
 * Extend the default WordPress/Scripts webpack to make entries and output more dynamic.
 * This checks the assts/js and assets/scss folder for any .js* and .scss files and compiles those to separate files
 *
 * Latest @Wordpress/Scripts webpack config:
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/packages/scripts/config/webpack.config.js
 * @see https://github.com/WordPress/gutenberg/blob/trunk/packages/scripts/README.md
 */
const WordPressConfig = require( '@wordpress/scripts/config/webpack.config' );
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const { sync: glob } = require( 'fast-glob' ); // eslint-disable-line import/no-extraneous-dependencies
const path = require( 'path' );

/**
 * Create an object of entries for webpack
 *
 * This will create an object of all the files in the assets/js and assets/scss folders
 * The key will be the file name and the value will be the file path
 * This will allow us to compile each file to its own file
 * This will also allow us to use the same file name in different folders
 * For example, we can have assets/js/theme/another.js and assets/js/admin/another.js
 * This will compile to js/theme/another.js and js/admin/another.js
 *
 * @param {Object} paths
 */
const entryObject = ( paths ) => {
	const includeFiles = glob( paths );
	const entries = {};

	for ( const filePath of includeFiles ) {
		const assetTypePart = filePath.endsWith( '.scss' ) ? 'css' : 'js';
		const fileNamePart = path.basename( filePath ).replace( path.extname( filePath ), '' );
		let fileName = `${ assetTypePart }/${ fileNamePart }`;

		/**
		 * Exclude files that start with an underscore
		 * Any file that starts with an underscore is a partial that should be included in another file
		 *
		 * @example _menu.scss (used in header.scss)
		 * @example _productGallery.js (used in product.js)
		 */
		if ( ! fileNamePart.startsWith( '_' ) ) {
			entries[ fileName ] = filePath;
		}
	}

	return entries;
};

/**
 * Export the WordPress/Scripts webpack config with our custom entry object
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/packages/scripts/README.md#extending-the-webpack-config
 */
module.exports = {
	...WordPressConfig,
	entry: entryObject( [
		'./assets/{scss,js}/**/*.scss',
		'./assets/{scss,js}/**/*.js',
	] ),
	resolve: {
		...WordPressConfig.resolve,
		alias: {
			...WordPressConfig.resolve.alias,
			Images: path.resolve( __dirname, 'assets/images' ),
		},
	},
	optimization: {
        ...WordPressConfig.optimization,
        minimizer: [
            ...WordPressConfig.optimization.minimizer,
            new CssMinimizerPlugin(),
        ],
    },
};
