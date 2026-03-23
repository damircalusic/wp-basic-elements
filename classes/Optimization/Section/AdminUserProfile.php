<?php

namespace WPBE\Optimization\Section;

defined( 'ABSPATH' ) || die();

/**
 * Class AdminUserProfile
 *
 * Optimize the WordPress admin user profile.
 *
 * @package WPBE\Optimization\Section
 */
class AdminUserProfile {
	public function __construct() {
		$options = (array) get_option( 'wpbe_admin_user_profile', [] );

		$has_profile_fields = false;
		foreach ( self::get_removable_profile_fields() as $key => $field ) {
			if ( ! empty( $options[ $key ] ) ) {
				$has_profile_fields = true;
				break;
			}
		}

		if ( $has_profile_fields ) {
			add_action( 'admin_head-profile.php', [ $this, 'remove_profile_fields' ] );
			add_action( 'admin_head-user-edit.php', [ $this, 'remove_profile_fields' ] );
		}

		$has_contact_methods = false;
		foreach ( self::get_removable_contact_methods() as $key => $label ) {
			if ( ! empty( $options[ 'contact_' . $key ] ) ) {
				$has_contact_methods = true;
				break;
			}
		}

		if ( $has_contact_methods ) {
			add_filter( 'user_contactmethods', [ $this, 'modify_user_contactmethods' ], 100, 1 );
		}
	}

	/**
	 * Get removable profile fields.
	 *
	 * @return array<string, array{label: string, selector: string}>
	 */
	public static function get_removable_profile_fields(): array {
		return [
			'rich_editing'          => [
				'label'    => esc_html__( 'Rich Editing', 'wpbe' ),
				'selector' => '.user-rich-editing-wrap',
			],
			'syntax_highlighting'   => [
				'label'    => esc_html__( 'Syntax Highlighting', 'wpbe' ),
				'selector' => '.user-syntax-highlighting-wrap',
			],
			'comment_shortcuts'     => [
				'label'    => esc_html__( 'Comment Shortcuts', 'wpbe' ),
				'selector' => '.user-comment-shortcuts-wrap',
			],
			'admin_color_scheme'    => [
				'label'    => esc_html__( 'Admin Color Scheme', 'wpbe' ),
				'selector' => '.user-admin-color-wrap',
			],
			'admin_bar_front'       => [
				'label'    => esc_html__( 'Show Admin Bar on Front', 'wpbe' ),
				'selector' => '.user-admin-bar-front-wrap',
			],
			'application_passwords' => [
				'label'    => esc_html__( 'Application Passwords', 'wpbe' ),
				'selector' => '.application-passwords',
			],
			'yoast_settings'        => [
				'label'    => esc_html__( 'Yoast SEO Settings', 'wpbe' ),
				'selector' => '.yoast',
			],
		];
	}

	/**
	 * Get removable contact methods.
	 *
	 * @return array<string, string> Contact method key => Label.
	 */
	public static function get_removable_contact_methods(): array {
		return [
			'facebook'   => esc_html__( 'Facebook', 'wpbe' ),
			'instagram'  => esc_html__( 'Instagram', 'wpbe' ),
			'linkedin'   => esc_html__( 'LinkedIn', 'wpbe' ),
			'myspace'    => esc_html__( 'MySpace', 'wpbe' ),
			'pinterest'  => esc_html__( 'Pinterest', 'wpbe' ),
			'soundcloud' => esc_html__( 'SoundCloud', 'wpbe' ),
			'tumblr'     => esc_html__( 'Tumblr', 'wpbe' ),
			'twitter'    => esc_html__( 'Twitter / X', 'wpbe' ),
			'youtube'    => esc_html__( 'YouTube', 'wpbe' ),
			'wikipedia'  => esc_html__( 'Wikipedia', 'wpbe' ),
			'aim'        => esc_html__( 'AIM', 'wpbe' ),
			'yim'        => esc_html__( 'Yahoo IM', 'wpbe' ),
			'jabber'     => esc_html__( 'Jabber / Google Talk', 'wpbe' ),
		];
	}

	/**
	 * Remove selected profile fields via JavaScript.
	 *
	 * @return void
	 */
	public function remove_profile_fields(): void {
		$options   = (array) get_option( 'wpbe_admin_user_profile', [] );
		$selectors = [];

		foreach ( self::get_removable_profile_fields() as $key => $field ) {
			if ( ! empty( $options[ $key ] ) ) {
				$selectors[] = $field['selector'];
			}
		}

		if ( empty( $selectors ) ) {
			return;
		}
		?>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const selectors = <?php echo wp_json_encode( $selectors, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;

				selectors.forEach(function(selector) {
					const elements = document.querySelectorAll(selector);

					if (elements) {
						elements.forEach(element => {
							element.closest('tr')?.remove() || element.remove();
						});
					}
				});
			});
		</script>
		<?php
	}

	/**
	 * Remove extra contact methods.
	 *
	 * @param array $contactmethods Array of contact methods.
	 * @return array Modified array of contact methods.
	 */
	public function modify_user_contactmethods( array $contactmethods ): array {
		$options = (array) get_option( 'wpbe_admin_user_profile', [] );

		foreach ( self::get_removable_contact_methods() as $key => $label ) {
			if ( ! empty( $options[ 'contact_' . $key ] ) ) {
				unset( $contactmethods[ $key ] );
			}
		}

		return $contactmethods;
	}
}
