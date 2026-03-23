<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package WPBE
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || die();

$options = [
	'wpbe_admin_bar',
	'wpbe_admin_dashboard',
	'wpbe_admin_footer',
	'wpbe_admin_user_profile',
	'wpbe_comments',
	'wpbe_gutenberg',
	'wpbe_meta_tags',
	'wpbe_rest_api_whitelist',
];

foreach ( $options as $option ) {
	delete_option( $option );
}
