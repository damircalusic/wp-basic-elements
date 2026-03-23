<?php

defined( 'ABSPATH' ) || die();

namespace WPBE\Settings;

use WPBE\Optimization\Section\AdminBar;
use WPBE\Optimization\Section\AdminDashboard;
use WPBE\Optimization\Section\AdminUserProfile;
use WPBE\Optimization\Section\Comments;
use WPBE\Optimization\Section\Gutenberg;
use WPBE\Optimization\Section\MetaTags;
use WPBE\Optimization\Section\REST;

/**
 * Class Settings
 *
 * Manage WordPress settings.
 *
 * @package WPBE\Settings
 */
class Settings {
	/**
	 * Settings page slug
	 */
	private const PAGE_SLUG = 'wpbe-settings';

	/**
	 * Option group
	 */
	private const OPTION_GROUP = 'wpbe_settings_group';

	/**
	 * Donate link PayPal
	 */
	private const DONATE_PAYPAL = 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AJABLMWDF4RR8&source=url';

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add Actions
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_settings_assets' ] );

		// Add Filters
		add_filter( 'plugin_action_links_' . WPBE_BASENAME, [ $this, 'add_plugin_links' ] );
	}

	/**
	 * Add links to the plugin action row.
	 *
	 * @param array $links Existing plugin action links.
	 * @return array Modified links.
	 */
	public function add_plugin_links( array $links ): array {
		$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=' . self::PAGE_SLUG ) ) . '">' . esc_html__( 'Settings', 'wpbe' ) . '</a>';
		$paypal_link   = '<a href="' . esc_url( self::DONATE_PAYPAL ) . '" target="_blank">' . esc_html__( 'Donate (PayPal)', 'wpbe' ) . '</a>';

		array_unshift( $links, $settings_link, $paypal_link );

		return $links;
	}

	/**
	 * Add settings page to WordPress admin menu
	 *
	 * @return void
	 */
	public function add_settings_page(): void {
		add_options_page(
			esc_html__( 'WPBE Settings', 'wpbe' ),
			esc_html__( 'WPBE Elements', 'wpbe' ),
			'manage_options',
			self::PAGE_SLUG,
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Register settings
	 *
	 * @return void
	 */
	public function register_settings(): void {
		$this->register_admin_bar_settings();
		$this->register_admin_dashboard_settings();
		$this->register_admin_footer_settings();
		$this->register_admin_user_profile_settings();
		$this->register_comments_settings();
		$this->register_gutenberg_settings();
		$this->register_meta_tags_settings();
		$this->register_rest_api_settings();
	}

	/**
	 * Register Admin Bar settings
	 *
	 * @return void
	 */
	private function register_admin_bar_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'wpbe_admin_bar',
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize_admin_bar' ],
				'default'           => [],
			]
		);

		add_settings_section(
			'wpbe_admin_bar_section',
			esc_html__( 'Admin Bar', 'wpbe' ),
			[ $this, 'render_admin_bar_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			'wpbe_admin_bar',
			esc_html__( 'Remove Admin Bar Items', 'wpbe' ),
			[ $this, 'render_admin_bar_field' ],
			self::PAGE_SLUG,
			'wpbe_admin_bar_section'
		);
	}

	/**
	 * Register Admin Dashboard settings
	 *
	 * @return void
	 */
	private function register_admin_dashboard_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'wpbe_admin_dashboard',
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize_admin_dashboard' ],
				'default'           => [],
			]
		);

		add_settings_section(
			'wpbe_admin_dashboard_section',
			esc_html__( 'Admin Dashboard', 'wpbe' ),
			[ $this, 'render_admin_dashboard_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			'wpbe_admin_dashboard',
			esc_html__( 'Remove Dashboard Widgets', 'wpbe' ),
			[ $this, 'render_admin_dashboard_field' ],
			self::PAGE_SLUG,
			'wpbe_admin_dashboard_section'
		);
	}

	/**
	 * Register Admin Footer settings
	 *
	 * @return void
	 */
	private function register_admin_footer_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'wpbe_admin_footer',
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize_admin_footer' ],
				'default'           => [],
			]
		);

		add_settings_section(
			'wpbe_admin_footer_section',
			esc_html__( 'Admin Footer', 'wpbe' ),
			[ $this, 'render_admin_footer_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			'wpbe_admin_footer',
			esc_html__( 'Footer Options', 'wpbe' ),
			[ $this, 'render_admin_footer_field' ],
			self::PAGE_SLUG,
			'wpbe_admin_footer_section'
		);
	}

	/**
	 * Register Admin User Profile settings
	 *
	 * @return void
	 */
	private function register_admin_user_profile_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'wpbe_admin_user_profile',
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize_admin_user_profile' ],
				'default'           => [],
			]
		);

		add_settings_section(
			'wpbe_admin_user_profile_section',
			esc_html__( 'Admin User Profile', 'wpbe' ),
			[ $this, 'render_admin_user_profile_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			'wpbe_admin_user_profile',
			esc_html__( 'Profile Options', 'wpbe' ),
			[ $this, 'render_admin_user_profile_field' ],
			self::PAGE_SLUG,
			'wpbe_admin_user_profile_section'
		);
	}

	/**
	 * Register Comments settings
	 *
	 * @return void
	 */
	private function register_comments_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'wpbe_comments',
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize_comments' ],
				'default'           => [],
			]
		);

		add_settings_section(
			'wpbe_comments_section',
			esc_html__( 'Comments', 'wpbe' ),
			[ $this, 'render_comments_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			'wpbe_comments',
			esc_html__( 'Comment Options', 'wpbe' ),
			[ $this, 'render_comments_field' ],
			self::PAGE_SLUG,
			'wpbe_comments_section'
		);
	}

	/**
	 * Register Gutenberg settings
	 *
	 * @return void
	 */
	private function register_gutenberg_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'wpbe_gutenberg',
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize_gutenberg' ],
				'default'           => [],
			]
		);

		add_settings_section(
			'wpbe_gutenberg_section',
			esc_html__( 'Gutenberg Editor', 'wpbe' ),
			[ $this, 'render_gutenberg_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			'wpbe_gutenberg',
			esc_html__( 'Disable Editor Features', 'wpbe' ),
			[ $this, 'render_gutenberg_field' ],
			self::PAGE_SLUG,
			'wpbe_gutenberg_section'
		);
	}

	/**
	 * Register Meta Tags settings
	 *
	 * @return void
	 */
	private function register_meta_tags_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'wpbe_meta_tags',
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize_meta_tags' ],
				'default'           => [],
			]
		);

		add_settings_section(
			'wpbe_meta_tags_section',
			esc_html__( 'Meta Tags & Head Cleanup', 'wpbe' ),
			[ $this, 'render_meta_tags_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			'wpbe_meta_tags',
			esc_html__( 'Cleanup Options', 'wpbe' ),
			[ $this, 'render_meta_tags_field' ],
			self::PAGE_SLUG,
			'wpbe_meta_tags_section'
		);
	}

	/**
	 * Register REST API settings
	 *
	 * @return void
	 */
	private function register_rest_api_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'wpbe_rest_api_whitelist',
			[
				'type'              => 'string',
				'sanitize_callback' => [ $this, 'sanitize_whitelist' ],
				'default'           => '',
			]
		);

		add_settings_section(
			'wpbe_rest_api_section',
			esc_html__( 'REST API Settings', 'wpbe' ),
			[ $this, 'render_rest_api_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			'wpbe_rest_api_whitelist',
			esc_html__( 'Custom REST API Whitelist', 'wpbe' ),
			[ $this, 'render_whitelist_field' ],
			self::PAGE_SLUG,
			'wpbe_rest_api_section'
		);
	}

	/**
	 * Sanitize Admin Bar options
	 *
	 * @param mixed $input Input value.
	 * @return array Sanitized value.
	 */
	public function sanitize_admin_bar( mixed $input ): array {
		if ( ! is_array( $input ) ) {
			return [];
		}

		$valid_keys = array_keys( AdminBar::get_removable_items() );
		$sanitized  = [];

		foreach ( $valid_keys as $key ) {
			$sanitized[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
		}

		return $sanitized;
	}

	/**
	 * Render Admin Bar section description
	 *
	 * @return void
	 */
	public function render_admin_bar_section(): void {
		echo '<p>' . esc_html__( 'Select which items to remove from the WordPress admin bar.', 'wpbe' ) . '</p>';
	}

	/**
	 * Render Admin Bar checkboxes
	 *
	 * @return void
	 */
	public function render_admin_bar_field(): void {
		$options = (array) get_option( 'wpbe_admin_bar', [] );

		echo '<fieldset>';
		foreach ( AdminBar::get_removable_items() as $menu_id => $label ) {
			$checked = ! empty( $options[ $menu_id ] ) ? 'checked' : '';
			printf(
				'<label><input type="checkbox" name="wpbe_admin_bar[%s]" value="1" %s /> %s</label>',
				esc_attr( $menu_id ),
				esc_attr( $checked ),
				esc_html( $label )
			);
		}
		echo '</fieldset>';
	}

	/**
	 * Sanitize Meta Tags options
	 *
	 * @param mixed $input Input value.
	 * @return array Sanitized value.
	 */
	public function sanitize_meta_tags( mixed $input ): array {
		if ( ! is_array( $input ) ) {
			return [];
		}

		$valid_keys = MetaTags::get_option_keys();
		$sanitized  = [];

		foreach ( $valid_keys as $key ) {
			$sanitized[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
		}

		return $sanitized;
	}

	/**
	 * Render Meta Tags section description
	 *
	 * @return void
	 */
	public function render_meta_tags_section(): void {
		echo '<p>' . esc_html__( 'Remove unnecessary tags and scripts from the site head.', 'wpbe' ) . '</p>';
	}

	/**
	 * Render Meta Tags checkboxes
	 *
	 * @return void
	 */
	public function render_meta_tags_field(): void {
		$options = (array) get_option( 'wpbe_meta_tags', [] );

		echo '<fieldset>';
		foreach ( MetaTags::get_options() as $group ) {
			echo '<strong>' . esc_html( $group['label'] ) . '</strong>';
			foreach ( $group['options'] as $key => $label ) {
				$checked = ! empty( $options[ $key ] ) ? 'checked' : '';
				printf(
					'<label><input type="checkbox" name="wpbe_meta_tags[%s]" value="1" %s /> %s</label>',
					esc_attr( $key ),
					esc_attr( $checked ),
					esc_html( $label )
				);
			}
		}
		echo '</fieldset>';
	}

	/**
	 * Sanitize Admin Footer options
	 *
	 * @param mixed $input Input value.
	 * @return array Sanitized value.
	 */
	public function sanitize_admin_footer( mixed $input ): array {
		if ( ! is_array( $input ) ) {
			return [];
		}

		return [
			'custom_footer_text'  => wp_kses_post( trim( $input['custom_footer_text'] ?? '' ) ),
			'custom_version_text' => wp_kses_post( trim( $input['custom_version_text'] ?? '' ) ),
			'hide_version'        => ! empty( $input['hide_version'] ) ? 1 : 0,
		];
	}

	/**
	 * Render Admin Footer section description
	 *
	 * @return void
	 */
	public function render_admin_footer_section(): void {
		echo '<p>' . esc_html__( 'Customize the WordPress admin footer area.', 'wpbe' ) . '</p>';
	}

	/**
	 * Render Admin Footer fields
	 *
	 * @return void
	 */
	public function render_admin_footer_field(): void {
		$options = (array) get_option( 'wpbe_admin_footer', [] );

		// Left side — custom footer text
		$footer_text = $options['custom_footer_text'] ?? '';
		printf(
			'<label class="wpbe-field-label" for="wpbe_admin_footer_text">%s</label>',
			esc_html__( 'Left Footer Text', 'wpbe' )
		);
		printf(
			'<input type="text" id="wpbe_admin_footer_text" name="wpbe_admin_footer[custom_footer_text]" value="%s" placeholder="%s" />',
			esc_attr( $footer_text ),
			esc_attr__( '&copy; 2026 My Company. All rights reserved.', 'wpbe' )
		);
		echo '<p class="description">' . esc_html__( 'Replaces the default "Thank you for creating with WordPress" text. HTML allowed.', 'wpbe' ) . '</p>';

		// Right side — custom version text
		$version_text = $options['custom_version_text'] ?? '';
		printf(
			'<label class="wpbe-field-label" for="wpbe_admin_footer_version">%s</label>',
			esc_html__( 'Right Footer Text', 'wpbe' )
		);
		printf(
			'<input type="text" id="wpbe_admin_footer_version" name="wpbe_admin_footer[custom_version_text]" value="%s" placeholder="%s" />',
			esc_attr( $version_text ),
			esc_attr__( 'v1.0.0', 'wpbe' )
		);
		echo '<p class="description">' . esc_html__( 'Replaces the WordPress version text on the right side. HTML allowed.', 'wpbe' ) . '</p>';

		// Hide version toggle
		$checked = ! empty( $options['hide_version'] ) ? 'checked' : '';
		printf(
			'<label><input type="checkbox" name="wpbe_admin_footer[hide_version]" value="1" %s /> %s</label>',
			esc_attr( $checked ),
			esc_html__( 'Hide Right Footer Text Entirely', 'wpbe' )
		);
	}

	/**
	 * Sanitize Admin User Profile options
	 *
	 * @param mixed $input Input value.
	 * @return array Sanitized value.
	 */
	public function sanitize_admin_user_profile( mixed $input ): array {
		if ( ! is_array( $input ) ) {
			return [];
		}

		$valid_keys = array_keys( AdminUserProfile::get_removable_profile_fields() );

		foreach ( AdminUserProfile::get_removable_contact_methods() as $key => $label ) {
			$valid_keys[] = 'contact_' . $key;
		}

		$sanitized = [];

		foreach ( $valid_keys as $key ) {
			$sanitized[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
		}

		return $sanitized;
	}

	/**
	 * Render Admin User Profile section description
	 *
	 * @return void
	 */
	public function render_admin_user_profile_section(): void {
		echo '<p>' . esc_html__( 'Hide unnecessary fields from the user profile page.', 'wpbe' ) . '</p>';
	}

	/**
	 * Render Admin User Profile checkboxes
	 *
	 * @return void
	 */
	public function render_admin_user_profile_field(): void {
		$options = (array) get_option( 'wpbe_admin_user_profile', [] );

		echo '<fieldset>';
		echo '<strong>' . esc_html__( 'Hide Profile Fields', 'wpbe' ) . '</strong>';
		foreach ( AdminUserProfile::get_removable_profile_fields() as $key => $field ) {
			$checked = ! empty( $options[ $key ] ) ? 'checked' : '';
			printf(
				'<label><input type="checkbox" name="wpbe_admin_user_profile[%s]" value="1" %s /> %s</label>',
				esc_attr( $key ),
				esc_attr( $checked ),
				esc_html( $field['label'] )
			);
		}

		echo '<strong>' . esc_html__( 'Remove Contact Methods', 'wpbe' ) . '</strong>';
		foreach ( AdminUserProfile::get_removable_contact_methods() as $key => $label ) {
			$checked = ! empty( $options[ 'contact_' . $key ] ) ? 'checked' : '';
			printf(
				'<label><input type="checkbox" name="wpbe_admin_user_profile[contact_%s]" value="1" %s /> %s</label>',
				esc_attr( $key ),
				esc_attr( $checked ),
				esc_html( $label )
			);
		}
		echo '</fieldset>';
	}

	/**
	 * Sanitize Comments options
	 *
	 * @param mixed $input Input value.
	 * @return array Sanitized value.
	 */
	public function sanitize_comments( mixed $input ): array {
		if ( ! is_array( $input ) ) {
			return [];
		}

		$valid_keys = array_keys( Comments::get_options() );
		$sanitized  = [];

		foreach ( $valid_keys as $key ) {
			$sanitized[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
		}

		return $sanitized;
	}

	/**
	 * Render Comments section description
	 *
	 * @return void
	 */
	public function render_comments_section(): void {
		echo '<p>' . esc_html__( 'Configure WordPress comment functionality.', 'wpbe' ) . '</p>';
	}

	/**
	 * Render Comments checkboxes
	 *
	 * @return void
	 */
	public function render_comments_field(): void {
		$options = (array) get_option( 'wpbe_comments', [] );

		echo '<fieldset>';
		foreach ( Comments::get_options() as $key => $label ) {
			$checked = ! empty( $options[ $key ] ) ? 'checked' : '';
			printf(
				'<label><input type="checkbox" name="wpbe_comments[%s]" value="1" %s /> %s</label>',
				esc_attr( $key ),
				esc_attr( $checked ),
				esc_html( $label )
			);
		}
		echo '</fieldset>';
		echo '<p class="description">' . esc_html__( 'Removes the comments menu, closes comments on all posts, hides existing comments, and removes comment support from all post types.', 'wpbe' ) . '</p>';
	}

	/**
	 * Sanitize Gutenberg options
	 *
	 * @param mixed $input Input value.
	 * @return array Sanitized value.
	 */
	public function sanitize_gutenberg( mixed $input ): array {
		if ( ! is_array( $input ) ) {
			return [];
		}

		$valid_keys = array_keys( Gutenberg::get_disableable_features() );
		$sanitized  = [];

		foreach ( $valid_keys as $key ) {
			$sanitized[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
		}

		return $sanitized;
	}

	/**
	 * Render Gutenberg section description
	 *
	 * @return void
	 */
	public function render_gutenberg_section(): void {
		echo '<p>' . esc_html__( 'Disable default Gutenberg editor features for all users.', 'wpbe' ) . '</p>';
	}

	/**
	 * Render Gutenberg checkboxes
	 *
	 * @return void
	 */
	public function render_gutenberg_field(): void {
		$options = (array) get_option( 'wpbe_gutenberg', [] );

		echo '<fieldset>';
		foreach ( Gutenberg::get_disableable_features() as $key => $label ) {
			$checked = ! empty( $options[ $key ] ) ? 'checked' : '';
			printf(
				'<label><input type="checkbox" name="wpbe_gutenberg[%s]" value="1" %s /> %s</label>',
				esc_attr( $key ),
				esc_attr( $checked ),
				esc_html( $label )
			);
		}
		echo '</fieldset>';
		echo '<p class="description">' . esc_html__( 'Checked features will be turned off when the editor loads.', 'wpbe' ) . '</p>';
	}

	/**
	 * Sanitize Admin Dashboard options
	 *
	 * @param mixed $input Input value.
	 * @return array Sanitized value.
	 */
	public function sanitize_admin_dashboard( mixed $input ): array {
		if ( ! is_array( $input ) ) {
			return [];
		}

		$valid_keys = array_keys( AdminDashboard::get_removable_widgets() );
		$sanitized  = [];

		foreach ( $valid_keys as $key ) {
			$sanitized[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
		}

		return $sanitized;
	}

	/**
	 * Render Admin Dashboard section description
	 *
	 * @return void
	 */
	public function render_admin_dashboard_section(): void {
		echo '<p>' . esc_html__( 'Select which widgets to remove from the WordPress dashboard.', 'wpbe' ) . '</p>';
	}

	/**
	 * Render Admin Dashboard checkboxes
	 *
	 * @return void
	 */
	public function render_admin_dashboard_field(): void {
		$options = (array) get_option( 'wpbe_admin_dashboard', [] );

		echo '<fieldset>';
		foreach ( AdminDashboard::get_removable_widgets() as $widget_id => $widget ) {
			$checked = ! empty( $options[ $widget_id ] ) ? 'checked' : '';
			printf(
				'<label><input type="checkbox" name="wpbe_admin_dashboard[%s]" value="1" %s /> %s</label>',
				esc_attr( $widget_id ),
				esc_attr( $checked ),
				esc_html( $widget['label'] )
			);
		}
		echo '</fieldset>';
	}

	/**
	 * Sanitize whitelist input
	 *
	 * @param string $input Input value.
	 * @return string Sanitized value.
	 */
	public function sanitize_whitelist( string $input ): string {
		// Remove extra whitespace and sanitize
		$input = trim( $input );
		return sanitize_textarea_field( $input );
	}

	/**
	 * Render REST API section description
	 *
	 * @return void
	 */
	public function render_rest_api_section(): void {
		echo '<p>' . esc_html__( 'Configure REST API access restrictions for non-logged-in users.', 'wpbe' ) . '</p>';
	}

	/**
	 * Render whitelist field
	 *
	 * @return void
	 */
	public function render_whitelist_field(): void {
		$value             = get_option( 'wpbe_rest_api_whitelist', '' );
		$default_whitelist = $this->get_default_whitelist();
		?>
		<textarea
			name="wpbe_rest_api_whitelist"
			rows="6"
			cols="96"
			class="large-text code"
			placeholder="<?php echo esc_attr( '/wp-json/custom-endpoint/, /wp-json/another-endpoint/' ); ?>"
		><?php echo esc_textarea( $value ); ?></textarea>

		<p class="description">
			<?php esc_html_e( 'Add custom REST API endpoints to whitelist (comma-separated). These will be accessible to non-logged-in users.', 'wpbe' ); ?>
		</p>

		<p class="description">
			<strong><?php esc_html_e( 'Example:', 'wpbe' ); ?></strong>
			<code><?php echo esc_html( '/wp-json/custom-plugin/, /wp-json/my-api/' ); ?></code>
		</p>

		<details>
			<summary>
				<?php esc_html_e( 'View Default Whitelisted Endpoints', 'wpbe' ); ?>
			</summary>
			<div>
				<p><em><?php esc_html_e( 'These endpoints are whitelisted by default and do not need to be added manually:', 'wpbe' ); ?></em></p>
				<ul style="list-style: disc; margin-left: 16px; font-family: monospace;">
					<?php foreach ( $default_whitelist as $endpoint => $description ) : ?>
						<li><strong><?php echo esc_html( $endpoint ); ?></strong> — <?php echo esc_html( $description ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</details>
		<?php
	}

	/**
	 * Get default whitelist for display
	 *
	 * @return array
	 */
	private function get_default_whitelist(): array {
		return REST::get_default_whitelist();
	}

	/**
	 * Enqueue settings page assets.
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 * @return void
	 */
	public function enqueue_settings_assets( string $hook_suffix ): void {
		if ( 'settings_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}

		$css_asset_file = WPBE_BUILD_PATH . 'css/admin.asset.php';
		$css_version    = file_exists( $css_asset_file )
			? ( include $css_asset_file )['version'] ?? WPBE_VERSION
			: WPBE_VERSION;

		wp_enqueue_style(
			'wpbe-admin',
			WPBE_BUILD_URL . 'css/admin.css',
			[],
			$css_version
		);

		$js_asset_file = WPBE_BUILD_PATH . 'js/admin.asset.php';
		$js_asset      = file_exists( $js_asset_file ) ? include $js_asset_file : [];
		$js_version    = $js_asset['version'] ?? WPBE_VERSION;
		$js_deps       = $js_asset['dependencies'] ?? [];

		wp_enqueue_script(
			'wpbe-admin',
			WPBE_BUILD_URL . 'js/admin.js',
			$js_deps,
			$js_version,
			true
		);
	}

	/**
	 * Render settings page
	 *
	 * @return void
	 */
	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $wp_settings_sections, $wp_settings_fields;

		settings_errors( 'wpbe_messages' );
		?>
		<div class="wpbe-settings">
			<div class="wpbe-header">
				<h1><?php esc_html_e( 'WP Basic Elements', 'wpbe' ); ?></h1>
				<span class="wpbe-version"><?php echo esc_html( WPBE_VERSION ); ?></span>
			</div>

			<form method="post" action="options.php">
				<?php settings_fields( self::OPTION_GROUP ); ?>

				<?php if ( isset( $wp_settings_sections[ self::PAGE_SLUG ] ) ) : ?>
					<div class="wpbe-layout">
						<nav class="wpbe-nav" role="tablist">
							<?php $first = true; ?>
							<?php foreach ( $wp_settings_sections[ self::PAGE_SLUG ] as $section ) : ?>
								<button
									type="button"
									role="tab"
									class="wpbe-nav-item<?php echo $first ? ' is-active' : ''; ?>"
									aria-selected="<?php echo $first ? 'true' : 'false'; ?>"
									data-tab="<?php echo esc_attr( $section['id'] ); ?>"
								><?php echo esc_html( $section['title'] ); ?></button>
								<?php $first = false; ?>
							<?php endforeach; ?>
						</nav>

						<div class="wpbe-content">
							<?php $first = true; ?>
							<?php foreach ( $wp_settings_sections[ self::PAGE_SLUG ] as $section ) : ?>
								<div class="wpbe-panel<?php echo $first ? ' is-active' : ''; ?>" data-panel="<?php echo esc_attr( $section['id'] ); ?>" role="tabpanel">
									<div class="wpbe-card">
										<div class="wpbe-card-header">
											<div class="wpbe-card-title-row">
												<h2><?php echo esc_html( $section['title'] ); ?></h2>
												<?php if ( ! in_array( $section['id'], [ 'wpbe_admin_footer_section', 'wpbe_rest_api_section' ], true ) ) : ?>
													<button type="button" class="wpbe-toggle-all" aria-label="<?php esc_attr_e( 'Toggle all', 'wpbe' ); ?>">
														<?php esc_html_e( 'Toggle All', 'wpbe' ); ?>
													</button>
												<?php endif; ?>
											</div>
											<?php
											if ( $section['callback'] ) {
												call_user_func( $section['callback'], $section );
											}
											?>
										</div>
										<div class="wpbe-card-body">
											<?php if ( isset( $wp_settings_fields[ self::PAGE_SLUG ][ $section['id'] ] ) ) : ?>
												<table class="form-table" role="presentation">
													<?php do_settings_fields( self::PAGE_SLUG, $section['id'] ); ?>
												</table>
											<?php endif; ?>
										</div>
									</div>

									<div class="wpbe-submit">
										<?php submit_button( esc_html__( 'Save Settings', 'wpbe' ), 'primary', 'submit', false ); ?>
										<a href="<?php echo esc_url( self::DONATE_PAYPAL ); ?>" target="_blank" class="wpbe-donate-link"><?php esc_html_e( 'Buy me a coffee', 'wpbe' ); ?> &hearts;</a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}
}
