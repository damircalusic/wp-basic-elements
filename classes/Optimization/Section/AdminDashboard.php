<?php

namespace WPBE\Optimization\Section;

defined( 'ABSPATH' ) || die();

/**
 * Class AdminDashboard
 *
 * Optimize the WordPress admin dashboard.
 *
 * @package WPBE\Optimization\Section
 */
class AdminDashboard {
	public function __construct() {
		// Add Actions
		add_action( 'wp_dashboard_setup', [ $this, 'remove_dashboard_widgets' ], 999 );
	}

	/**
	 * Get removable dashboard widgets.
	 *
	 * @return array<string, array{label: string, context: string}>
	 */
	public static function get_removable_widgets(): array {
		return [
			// WordPress
			'welcome_panel'                        => [
				'label'   => esc_html__( 'Welcome Panel', 'wpbe' ),
				'context' => 'normal',
			],
			'dashboard_right_now'                  => [
				'label'   => esc_html__( 'At a Glance', 'wpbe' ),
				'context' => 'normal',
			],
			'dashboard_activity'                   => [
				'label'   => esc_html__( 'Activity', 'wpbe' ),
				'context' => 'normal',
			],
			'dashboard_site_health'                => [
				'label'   => esc_html__( 'Site Health Status', 'wpbe' ),
				'context' => 'normal',
			],
			'dashboard_quick_press'                => [
				'label'   => esc_html__( 'Quick Draft', 'wpbe' ),
				'context' => 'side',
			],
			'dashboard_primary'                    => [
				'label'   => esc_html__( 'WordPress Events and News', 'wpbe' ),
				'context' => 'side',
			],
			// WooCommerce
			'wc_admin_dashboard_setup'             => [
				'label'   => esc_html__( 'WooCommerce Setup', 'wpbe' ),
				'context' => 'normal',
			],
			'woocommerce_dashboard_recent_reviews' => [
				'label'   => esc_html__( 'WooCommerce Recent Reviews', 'wpbe' ),
				'context' => 'normal',
			],
			'woocommerce_dashboard_status'         => [
				'label'   => esc_html__( 'WooCommerce Status', 'wpbe' ),
				'context' => 'normal',
			],
			'woocommerce_dashboard_sales'          => [
				'label'   => esc_html__( 'WooCommerce Sales', 'wpbe' ),
				'context' => 'normal',
			],
			'woocommerce_dashboard_recent_orders'  => [
				'label'   => esc_html__( 'WooCommerce Recent Orders', 'wpbe' ),
				'context' => 'normal',
			],
			// Yoast SEO
			'wpseo-dashboard-overview'             => [
				'label'   => esc_html__( 'Yoast SEO Overview', 'wpbe' ),
				'context' => 'normal',
			],
			// Easy WP SMTP
			'easy_wp_smtp_reports_widget_lite'     => [
				'label'   => esc_html__( 'Easy WP SMTP Reports', 'wpbe' ),
				'context' => 'normal',
			],
		];
	}

	/**
	 * Remove dashboard widgets based on settings.
	 *
	 * @return void
	 */
	public function remove_dashboard_widgets(): void {
		$options = (array) get_option( 'wpbe_admin_dashboard', [] );

		foreach ( self::get_removable_widgets() as $widget_id => $widget ) {
			if ( empty( $options[ $widget_id ] ) ) {
				continue;
			}

			if ( $widget_id === 'welcome_panel' ) {
				remove_action( 'welcome_panel', 'wp_welcome_panel' );
			} else {
				remove_meta_box( $widget_id, 'dashboard', $widget['context'] );
			}
		}
	}
}
