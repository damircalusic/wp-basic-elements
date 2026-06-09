<?php

namespace WPBE\Optimization\Section;

defined( 'ABSPATH' ) || die();

/**
 * Class REST
 *
 * Optimize the WordPress REST API.
 *
 * @package WPBE\Optimization\Section
 */
class REST {
	public function __construct() {
		// Add Filters
		add_filter( 'rest_authentication_errors', [ $this, 'modify_rest_authentication_errors' ] );
	}

	/**
	 * Disable WordPress REST API access for non-logged-in users and whitelist specific routes.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Error|null|bool $access The current access level.
	 * @return \WP_Error|null|bool The updated access level.
	 */
	public function modify_rest_authentication_errors( $access ) {
		// If already an error, pass it through
		if ( is_wp_error( $access ) ) {
			return $access;
		}

		// Allow logged-in users
		if ( is_user_logged_in() ) {
			return $access;
		}

		// Get the REST route from the request
		$route = $this->get_current_rest_route();

		// Check if route is whitelisted
		if ( $this->is_route_whitelisted( $route ) ) {
			return $access;
		}

		// Block access for non-logged-in users
		$message = apply_filters( 'disable_wp_rest_api_error', esc_html__( 'REST API restricted to authenticated users.', 'wpbe' ) );
		return new \WP_Error( 'rest_login_required', $message, array( 'status' => rest_authorization_required_code() ) );
	}

	/**
	 * Get default REST API whitelist with descriptions
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of endpoint paths and their descriptions.
	 */
	public static function get_default_whitelist(): array {
		return [
			// WooCommerce Core
			'/wp-json/wc/'               => esc_html__( 'WooCommerce REST API (cart, checkout, products)', 'wpbe' ),
			'/wp-json/wc-analytics/'     => esc_html__( 'WooCommerce Analytics', 'wpbe' ),
			'/wp-json/wc-admin/'         => esc_html__( 'WooCommerce Admin', 'wpbe' ),
			'/wp-json/wc/store/'         => esc_html__( 'WooCommerce Blocks (cart, checkout blocks)', 'wpbe' ),
			'/wp-json/wc/v3/'            => esc_html__( 'WooCommerce REST API v3', 'wpbe' ),
			'/wp-json/wc-blocks/'        => esc_html__( 'WooCommerce Blocks API', 'wpbe' ),

			// PayPal (Payment Plugins for PayPal WooCommerce)
			'/wp-json/wc-ppcp/'          => esc_html__( 'PayPal checkout (cart, order, webhooks)', 'wpbe' ),
			'/wp-json/paypal/'           => esc_html__( 'PayPal endpoints', 'wpbe' ),

			// WordPress Core
			'/wp-json/wp/v2/media'       => esc_html__( 'WordPress Media uploads', 'wpbe' ),
			'/wp-json/wp/v2/search'      => esc_html__( 'WordPress Search', 'wpbe' ),
			'/wp-json/wp/v2/types'       => esc_html__( 'WordPress Post types', 'wpbe' ),
			'/wp-json/wp/v2/taxonomies'  => esc_html__( 'WordPress Taxonomies', 'wpbe' ),
			'/wp-json/oembed/'           => esc_html__( 'oEmbed (embedded content)', 'wpbe' ),
		];
	}

	/**
	 * Check if a REST API route is whitelisted.
	 *
	 * @since 1.0.0
	 *
	 * @param string $route The route to check.
	 * @return bool True if the route is whitelisted, false otherwise.
	 */
	private function is_route_whitelisted( string $route ): bool {
		// Get default whitelist paths
		$default_whitelist = array_keys( self::get_default_whitelist() );

		// Get custom whitelist from options
		$custom_whitelist = get_option( 'wpbe_rest_api_whitelist', '' );

		if ( ! empty( $custom_whitelist ) ) {
			$custom_namespaces = array_map( 'trim', explode( ',', $custom_whitelist ) );
			$default_whitelist = array_merge( $default_whitelist, $custom_namespaces );
		}

		// Check if current route matches any whitelisted pattern
		foreach ( $default_whitelist as $pattern ) {
			if ( strpos( $route, $pattern ) !== false ) {
				return true;
			}
		}

		// Always return false if no matches found
		return false;
	}

	/**
	 * Get the current REST route
	 *
	 * @return string
	 */
	private function get_current_rest_route(): string {
		// Prefer the route WordPress is actually dispatching. This is set during
		// serve_request() for every REST request: pretty permalinks, plain
		// ?rest_route= requests, and proxied dispatches such as the PayPal
		// "wc-ajax" frontend request (?wc-ajax=...&path=/wc-ppcp/v1/...), where
		// the real route lives in a query var rather than the URI path.
		if ( ! empty( $GLOBALS['wp']->query_vars['rest_route'] ) ) {
			return '/wp-json/' . ltrim( (string) $GLOBALS['wp']->query_vars['rest_route'], '/' );
		}

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return '';
		}

		$uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		// Strip query string to prevent whitelist bypass via query parameters.
		$path = parse_url( $uri, PHP_URL_PATH );

		return is_string( $path ) ? $path : '';
	}
}
