<?php

defined( 'ABSPATH' ) || die();

namespace WPBE\Optimization\Section;

/**
 * Class AdminFooter
 *
 * Optimize the WordPress admin footer.
 *
 * @package WPBE\Optimization\Section
 */
class AdminFooter {
	public function __construct() {
		$options = (array) get_option( 'wpbe_admin_footer', [] );

		if ( ! empty( $options['custom_footer_text'] ) ) {
			add_filter( 'admin_footer_text', [ $this, 'modify_admin_footer_text' ] );
		}

		if ( ! empty( $options['custom_version_text'] ) ) {
			add_filter( 'update_footer', [ $this, 'modify_update_footer' ], 11 );
		} elseif ( ! empty( $options['hide_version'] ) ) {
			add_filter( 'update_footer', '__return_empty_string', 11 );
		}
	}

	/**
	 * Get available footer options.
	 *
	 * @return array<string, array{label: string, type: string}>
	 */
	public static function get_options(): array {
		return [
			'custom_footer_text' => [
				'label' => esc_html__( 'Custom Footer Text', 'wpbe' ),
				'type'  => 'text',
			],
			'hide_version'       => [
				'label' => esc_html__( 'Hide WordPress Version', 'wpbe' ),
				'type'  => 'checkbox',
			],
		];
	}

	/**
	 * Modify admin footer text (left side).
	 *
	 * @return string
	 */
	public function modify_admin_footer_text(): string {
		$options = (array) get_option( 'wpbe_admin_footer', [] );

		return wp_kses_post( $options['custom_footer_text'] ?? '' );
	}

	/**
	 * Modify update footer text (right side).
	 *
	 * @return string
	 */
	public function modify_update_footer(): string {
		$options = (array) get_option( 'wpbe_admin_footer', [] );

		return wp_kses_post( $options['custom_version_text'] ?? '' );
	}
}
