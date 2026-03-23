<?php

defined( 'ABSPATH' ) || die();

namespace WPBE\Optimization\Section;

/**
 * Class MetaTags
 *
 * Optimize the WordPress Meta Tags.
 *
 * @package WPBE\Optimization\Section
 */
class MetaTags {
	public function __construct() {
		$options = (array) get_option( 'wpbe_meta_tags', [] );

		// General Meta Tags (individual)
		if ( ! empty( $options['wp_generator'] ) ) {
			remove_action( 'wp_head', 'wp_generator' );
		}

		if ( ! empty( $options['oembed_links'] ) ) {
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		}

		if ( ! empty( $options['shortlink'] ) ) {
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		}

		if ( ! empty( $options['wlw_manifest'] ) ) {
			remove_action( 'wp_head', 'wlwmanifest_link' );
		}

		if ( ! empty( $options['adjacent_posts'] ) ) {
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		}

		// REST API Links (grouped — all REST link output)
		if ( ! empty( $options['rest_links'] ) ) {
			remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
			remove_action( 'template_redirect', 'rest_output_link_header', 11 );
			remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
		}

		// Feed Links (individual)
		if ( ! empty( $options['rsd_link'] ) ) {
			remove_action( 'wp_head', 'rsd_link' );
		}

		if ( ! empty( $options['feed_links'] ) ) {
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
		}

		// Emojis (grouped — scripts, styles, filters, TinyMCE)
		if ( ! empty( $options['emojis'] ) ) {
			$this->remove_emojis();
		}

		// Pingbacks & Trackbacks (grouped — tightly coupled)
		if ( ! empty( $options['pingbacks'] ) ) {
			$this->disable_pingbacks();
		}
	}

	/**
	 * Get available meta tag options, grouped by category.
	 *
	 * @return array<string, array<string, string>> Group label => [ option key => label ].
	 */
	public static function get_options(): array {
		return [
			'head_cleanup' => [
				'label'   => esc_html__( 'Head Cleanup', 'wpbe' ),
				'options' => [
					'wp_generator'   => esc_html__( 'WordPress Generator Tag', 'wpbe' ),
					'oembed_links'   => esc_html__( 'oEmbed Discovery Links', 'wpbe' ),
					'shortlink'      => esc_html__( 'Shortlink Tag', 'wpbe' ),
					'wlw_manifest'   => esc_html__( 'Windows Live Writer Manifest', 'wpbe' ),
					'adjacent_posts' => esc_html__( 'Adjacent Posts (prev/next) Links', 'wpbe' ),
				],
			],
			'rest_api'     => [
				'label'   => esc_html__( 'REST API', 'wpbe' ),
				'options' => [
					'rest_links' => esc_html__( 'REST API Links (head, header, RSD)', 'wpbe' ),
				],
			],
			'feeds'        => [
				'label'   => esc_html__( 'Feeds', 'wpbe' ),
				'options' => [
					'rsd_link'   => esc_html__( 'RSD (Really Simple Discovery) Link', 'wpbe' ),
					'feed_links' => esc_html__( 'RSS Feed Links', 'wpbe' ),
				],
			],
			'emojis'       => [
				'label'   => esc_html__( 'Emojis', 'wpbe' ),
				'options' => [
					'emojis' => esc_html__( 'Emoji Scripts, Styles and TinyMCE Plugin', 'wpbe' ),
				],
			],
			'pingbacks'    => [
				'label'   => esc_html__( 'Pingbacks & Trackbacks', 'wpbe' ),
				'options' => [
					'pingbacks' => esc_html__( 'Disable Pingbacks, Trackbacks and X-Pingback Header', 'wpbe' ),
				],
			],
		];
	}

	/**
	 * Get a flat array of all valid option keys.
	 *
	 * @return array<string>
	 */
	public static function get_option_keys(): array {
		$keys = [];
		foreach ( self::get_options() as $group ) {
			$keys = array_merge( $keys, array_keys( $group['options'] ) );
		}
		return $keys;
	}

	/**
	 * Remove all emoji scripts, styles, and filters.
	 *
	 * @return void
	 */
	private function remove_emojis(): void {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', [ $this, 'disable_emojis_tinymce' ] );
		add_filter( 'wp_resource_hints', [ $this, 'remove_emoji_dns_prefetch' ], 10, 2 );
	}

	/**
	 * Disable pingbacks, trackbacks, self-pings, and X-Pingback header.
	 *
	 * @return void
	 */
	private function disable_pingbacks(): void {
		add_filter( 'wp_headers', [ $this, 'modify_wp_headers_x_pingback' ] );
		add_action( 'pre_ping', [ $this, 'modify_pre_ping' ], 10, 3 );
		add_action( 'init', [ $this, 'remove_trackback_support' ] );
		add_filter(
			'xmlrpc_methods',
			function ( array $methods ): array {
				unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );
				return $methods;
			}
		);
	}

	/**
	 * Disable the emoji plugin in TinyMCE.
	 *
	 * @param array $plugins Array of TinyMCE plugins.
	 * @return array Modified array of plugins.
	 */
	public function disable_emojis_tinymce( array $plugins ): array {
		return array_diff( $plugins, [ 'wpemoji' ] );
	}

	/**
	 * Remove emoji CDN from DNS prefetch hints.
	 *
	 * @param array  $urls          URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed for.
	 * @return array Modified URLs.
	 */
	public function remove_emoji_dns_prefetch( array $urls, string $relation_type ): array {
		if ( $relation_type !== 'dns-prefetch' ) {
			return $urls;
		}

		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

		return array_filter(
			$urls,
			function ( $url ) use ( $emoji_svg_url ) {
				$url_string = is_array( $url ) ? ( $url['href'] ?? '' ) : (string) $url;
				return ! str_contains( $url_string, parse_url( $emoji_svg_url, PHP_URL_HOST ) );
			}
		);
	}

	/**
	 * Remove trackback support from all post types.
	 *
	 * @return void
	 */
	public function remove_trackback_support(): void {
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'trackbacks' ) ) {
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	/**
	 * Remove self-pings.
	 *
	 * @param array $post_links The list of links to be pinged.
	 * @param array $pung       An array of URLs already pinged for the given post.
	 * @param int   $post_ID    The ID of the post being pinged.
	 */
	public function modify_pre_ping( &$post_links, &$pung, int $post_ID ): void {
		foreach ( $post_links as $key => $link ) {
			if ( 0 === strpos( $link, home_url() ) ) {
				unset( $post_links[ $key ] );
			}
		}
	}

	/**
	 * Remove X-Pingback header.
	 *
	 * @param array $headers An array of headers to be sent with the HTTP response.
	 * @return array The modified array of headers.
	 */
	public function modify_wp_headers_x_pingback( array $headers ): array {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
}
