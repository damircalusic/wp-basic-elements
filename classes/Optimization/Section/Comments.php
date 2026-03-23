<?php

defined( 'ABSPATH' ) || die();

namespace WPBE\Optimization\Section;

/**
 * Class Comments
 *
 * Optimize the WordPress comments system.
 *
 * @package WPBE\Optimization\Section
 */
class Comments {
	public function __construct() {
		$options = (array) get_option( 'wpbe_comments', [] );

		if ( ! empty( $options['disable_comments'] ) ) {
			// Add Actions
			add_action( 'admin_init', [ $this, 'remove_comment_page_menu_link' ] );
			add_action( 'widgets_init', [ $this, 'remove_comments_stylesheet' ] );

			// Add Filters
			add_filter( 'comments_open', '__return_false', 20, 2 );
			add_filter( 'comments_array', '__return_empty_array', 10, 2 );

			// Call functions
			$this->redirect_comment_pages();
			$this->remove_comment_support();
		}
	}

	/**
	 * Get available comment options.
	 *
	 * @return array<string, string> Option key => Label.
	 */
	public static function get_options(): array {
		return [
			'disable_comments' => esc_html__( 'Disable Comments Entirely', 'wpbe' ),
		];
	}

	/**
	 * Remove comment page menu link in admin.
	 *
	 * @return void
	 */
	public function remove_comment_page_menu_link(): void {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Remove comments stylesheet from WP Head.
	 *
	 * @return void
	 */
	public function remove_comments_stylesheet(): void {
		global $wp_widget_factory;

		if ( array_key_exists( 'WP_Widget_Recent_Comments', $wp_widget_factory->widgets ) ) {
			remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
		}
	}

	/**
	 * Redirect users in admin trying to access the comment page.
	 *
	 * @return void
	 */
	public function redirect_comment_pages(): void {
		global $pagenow;

		if ( 'edit-comments.php' === $pagenow ) {
			wp_safe_redirect( admin_url() );
			exit;
		}
	}

	/**
	 * Remove support for comments for all post types.
	 *
	 * @return void
	 */
	public function remove_comment_support(): void {
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
			}
		}
	}
}
