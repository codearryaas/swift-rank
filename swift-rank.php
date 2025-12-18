<?php
/**
 * Plugin Name: Swift Rank
 * Plugin URI: https://racase.com.np/plugins/swift-rank/
 * Description: Add Schema.org structured data to your WordPress site. Supports Organization and LocalBusiness schema with Knowledge Graph integration.
 * Version: 1.0.3
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Author: Rakesh Lawaju
 * Author URI: https://racase.com.np
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: swift-rank
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
define( 'SWIFT_RANK_VERSION', '1.0.3' );
define( 'SWIFT_RANK_PLUGIN_FILE', __FILE__ );
define( 'SWIFT_RANK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SWIFT_RANK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SWIFT_RANK_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main Plugin Class
 */

class Swift_Rank {
	/**
	 * Instance of this class.
	 *
	 * @var Swift_Rank
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Swift_Rank
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->load_dependencies();
		$this->init_hooks();
	}

	/**
	 * Load required files.
	 */
	private function load_dependencies() {
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/class-swift-rank-output.php';
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/class-swift-rank-admin.php';
	}

	/**
	 * Initialize WordPress hooks.
	 */
	private function init_hooks() {

		// Initialize admin settings page.
		if ( is_admin() ) {
			Swift_Rank_Admin::get_instance();
		}

		// Initialize frontend output.
		Swift_Rank_Output::get_instance();

		// Activation and deactivation hooks.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
	}

	/**
	 * Plugin activation
	 */
	public function activate() {
		// Set default options.
		$default_options = array(
			'organization_schema' => false,
			'organization_type'   => 'Organization',
			'organization_name'   => get_bloginfo( 'name' ),
		);

		if ( ! get_option( 'swift_rank_settings' ) ) {
			add_option( 'swift_rank_settings', $default_options );
		}
	}
}

/**
 * Initialize the plugin
 */
function swift_rank_init() {
	return Swift_Rank::get_instance();
}

// Start the plugin.
swift_rank_init();
