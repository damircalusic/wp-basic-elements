<?php

defined( 'ABSPATH' ) || die();

namespace WPBE\Optimization\Section;

/**
 * Class AdminBar
 *
 * Optimize the WordPress admin bar.
 *
 * @package WPBE\Optimization\Section
 */
class AdminBar {
	public function __construct() {
		// Add Actions
		add_action( 'wp_before_admin_bar_render', [ $this, 'modify_wp_before_admin_bar_render' ] );
	}

	/**
	 * Get available admin bar items that can be removed.
	 *
	 * @return array<string, string> Menu ID => Label.
	 */
	public static function get_removable_items(): array {
		return [
			'wp-logo'     => esc_html__( 'WordPress Logo', 'wpbe' ),
			'new-content' => esc_html__( 'New Content (+New)', 'wpbe' ),
			'customize'   => esc_html__( 'Customize', 'wpbe' ),
			'updates'     => esc_html__( 'Updates', 'wpbe' ),
			'comments'    => esc_html__( 'Comments', 'wpbe' ),
			'edit'        => esc_html__( 'Edit', 'wpbe' ),
			'site-editor' => esc_html__( 'Site Editor', 'wpbe' ),
			'search'      => esc_html__( 'Search', 'wpbe' ),
			'wpseo-menu'  => esc_html__( 'Yoast SEO', 'wpbe' ),
		];
	}

	/**
	 * Modify the WordPress admin bar before rendering.
	 *
	 * @return void
	 */
	public function modify_wp_before_admin_bar_render(): void {
		global $wp_admin_bar;

		$options = (array) get_option( 'wpbe_admin_bar', [] );

		foreach ( self::get_removable_items() as $menu_id => $label ) {
			if ( ! empty( $options[ $menu_id ] ) ) {
				$wp_admin_bar->remove_menu( $menu_id );
			}
		}
	}
}
