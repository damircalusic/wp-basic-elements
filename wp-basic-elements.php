<?php
/**
 * Plugin Name: WP Basic Elements
 * Plugin URI: https://wordpress.org/plugins/wp-basic-elements/
 * Description: Disable unnecessary features and speed up your site. Make the WP Admin simple and clean.
 * Version: 6.0.0
 * Author: Damir Calusic
 * Author URI: https://www.damircalusic.com/
 * Text Domain: wpbe
 * Domain Path: /languages/
 * Requires at least: 6.9
 * Requires PHP: 8.4
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace WPBE;

defined( 'ABSPATH' ) || die();

define( 'WPBE_VERSION', '6.0.0' );
define( 'WPBE_WEBSITE', str_ireplace( [ 'http://', 'https://', 'www.' ], '', home_url() ) );
define( 'WPBE_URL', plugin_dir_url( __FILE__ ) );
define( 'WPBE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPBE_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPBE_RELATIVE_PATH', dirname( WPBE_BASENAME ) );
define( 'WPBE_BUILD_URL', WPBE_URL . 'build/' );
define( 'WPBE_BUILD_PATH', WPBE_PATH . 'build/' );

/**
 * Autoload classes
 *
 * @param string $classname
 *
 * @return void
 */
spl_autoload_register(
	function ( $classname ) {
		$prefixes = [
			'WPBE\\'      => WPBE_PATH . 'classes/',
		];

		foreach ( $prefixes as $prefix => $base_dir ) {
			$len = strlen( $prefix );

			if ( strncmp( $prefix, $classname, $len ) !== 0 ) {
				continue;
			}

			$relative_class = substr( $classname, $len );
			$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

			if ( file_exists( $file ) ) {
				require $file;
			}
		}
	}
);

// Initialize the plugin
WPBE::instance();
