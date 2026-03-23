<?php

defined( 'ABSPATH' ) || die();

namespace WPBE;

use WPBE\Settings\Settings;
use WPBE\Optimization\Optimization;

class WPBE {
	private static ?WPBE $instance = null;

	/**
	 * Singleton instance of class
	 *
	 * @return WPBE
	 */
	public static function instance(): WPBE {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'classes' ] );
	}

	/**
	 * Load classes
	 *
	 * @return void
	 */
	public function classes(): void {
		new Settings();
		new Optimization();
	}
}
