<?php

defined( 'ABSPATH' ) || die();

namespace WPBE\Optimization;

use WPBE\Optimization\Section\{AdminBar, AdminDashboard, AdminFooter, AdminUserProfile, Comments, Gutenberg, MetaTags, REST};

class Optimization {
	public function __construct() {
		new AdminBar();
		new AdminDashboard();
		new AdminFooter();
		new AdminUserProfile();
		new Comments();
		new Gutenberg();
		new MetaTags();
		new REST();
	}
}
