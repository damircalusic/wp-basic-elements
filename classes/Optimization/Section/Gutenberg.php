<?php

namespace WPBE\Optimization\Section;

defined( 'ABSPATH' ) || die();

/**
 * Class Gutenberg
 *
 * Optimize the WordPress Gutenberg editor.
 *
 * @package WPBE\Optimization\Section
 */
class Gutenberg {
	public function __construct() {
		$options = (array) get_option( 'wpbe_gutenberg', [] );

		if ( ! empty( array_filter( $options ) ) ) {
			add_action( 'enqueue_block_editor_assets', [ $this, 'disable_editor_features' ], 100 );
		}
	}

	/**
	 * Get available Gutenberg features that can be disabled.
	 *
	 * @return array<string, string> Feature key => Label.
	 */
	public static function get_disableable_features(): array {
		return [
			'welcomeGuide'    => esc_html__( 'Welcome Guide', 'wpbe' ),
			'fullscreenMode'  => esc_html__( 'Fullscreen Mode', 'wpbe' ),
			'focusMode'       => esc_html__( 'Focus Mode (Spotlight)', 'wpbe' ),
			'distractionFree' => esc_html__( 'Distraction Free Mode', 'wpbe' ),
			'fixedToolbar'    => esc_html__( 'Top Toolbar', 'wpbe' ),
		];
	}

	/**
	 * Disable selected Gutenberg editor features via inline script.
	 *
	 * @return void
	 */
	public function disable_editor_features(): void {
		$options  = (array) get_option( 'wpbe_gutenberg', [] );
		$features = [];

		foreach ( self::get_disableable_features() as $key => $label ) {
			if ( ! empty( $options[ $key ] ) ) {
				$features[] = $key;
			}
		}

		if ( empty( $features ) ) {
			return;
		}

		$features_json = wp_json_encode( $features, JSON_HEX_TAG | JSON_HEX_AMP );
		$script        = "
			window.addEventListener( 'load', function() {
				var features = {$features_json};
				var editPost = wp.data.select( 'core/edit-post' );

				if ( ! editPost ) {
					return;
				}

				features.forEach( function( feature ) {
					if ( editPost.isFeatureActive( feature ) ) {
						wp.data.dispatch( 'core/edit-post' ).toggleFeature( feature );
					}
				});
			} );
		";

		wp_add_inline_script( 'wp-blocks', $script );
	}
}
