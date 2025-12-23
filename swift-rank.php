<?php
/**
 * Plugin Name: Swift Rank
 * Plugin URI: https://toolpress.net/swift-rank/
 * Description: Add Schema.org structured data to your WordPress site. Supports Organization and LocalBusiness schema with Knowledge Graph integration.
 * Version: 1.0.4
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Author: ToolPress
 * Author URI: https://toolpress.net
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: swift-rank
 */

if (!defined('ABSPATH')) {
	exit;
}

// Plugin constants.
define('SWIFT_RANK_VERSION', '1.0.4');
define('SWIFT_RANK_PLUGIN_FILE', __FILE__);
define('SWIFT_RANK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SWIFT_RANK_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SWIFT_RANK_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SWIFT_RANK_PATH', plugin_dir_path(__FILE__)); // Alias for compatibility
define('SWIFT_RANK_URL', plugin_dir_url(__FILE__)); // Alias for compatibility

/**
 * Main Plugin Class
 */

class Swift_Rank
{
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
	public static function get_instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct()
	{
		$this->load_dependencies();

		add_action('plugins_loaded', array($this, 'load_textdomain'));

		// Initialize CPT Registration globally.
		Swift_Rank_CPT_Registration::get_instance();

		// Initialize REST API handler.
		Swift_Rank_REST_API::get_instance();

		// Initialize admin bar validator.
		Swift_Rank_Admin_Bar_Validator::get_instance();

		// Initialize CPT Admin Components and Post Metabox only in admin.
		if (is_admin()) {
			Swift_Rank_CPT_Metabox::get_instance();
			Swift_Rank_CPT_Columns::get_instance();
			Swift_Rank_CPT_Filters::get_instance();

			Swift_Rank_Post_Metabox::get_instance();
			Swift_Rank_User_Profile::get_instance();

			// Core Admin Components
			Swift_Rank_Admin_Menu::get_instance();
			Swift_Rank_Admin_Assets::get_instance();
			Swift_Rank_Admin_Notice::get_instance();
			Swift_Rank_Plugin_Listing::get_instance();
		}

		// Initialize blocks.
		Swift_Rank_Blocks::get_instance();

		// Initialize frontend output.
		Schema_Output_Handler::get_instance();

		// Activation hook.
		register_activation_hook(SWIFT_RANK_PLUGIN_FILE, array($this, 'activate'));
	}

	/**
	 * Load required files.
	 */
	private function load_dependencies()
	{
		// Load helper classes first
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/output/types/interface-schema-builder.php';
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/utils/class-schema-variable-replacer.php';
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/class-schema-type-helper.php';
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/utils/class-schema-reference-manager.php';

		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/class-swift-rank-conditions.php';

		// Load Schema Types Config (centralized Pro types definition)
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/class-schema-types-config.php';

		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/output/class-schema-output-handler.php';

		// Load Schema Reference Resolver
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/output/resolvers/class-schema-reference-resolver.php';

		// Register schema types from base plugin
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/schema-types-registration.php';

		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/class-swift-rank-blocks.php';



		// Load admin tab files.
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/rest-api/class-rest-api.php';
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/class-admin-bar-validator.php';

		// Wizard REST API - must be loaded globally for REST endpoints
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/rest-api/class-wizard-api.php';

		// Load template loader globally matching templates
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/class-schema-template-loader.php';

		// Load CPT Registration globally.
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/cpt/class-cpt-registration.php';

		// Load admin-only files.
		if (is_admin()) {

			// Core Admin Components
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/class-admin-menu.php';
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/class-admin-assets.php';
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/class-admin-notice.php';
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/class-plugin-listing.php';

			// Setup Wizard
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/class-setup-wizard.php';
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/class-wizard-notice.php';

			// CPT Admin components
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/cpt/class-cpt-metabox.php';
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/cpt/class-cpt-columns.php';
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/cpt/class-cpt-filters.php';

			// Post Metabox
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/metabox/class-swift-rank-post-metabox.php';

			// User Profile
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/class-user-profile-schema.php';
		}
	}

	/**
	 * Load plugin textdomain.
	 */
	public function load_textdomain()
	{
		load_plugin_textdomain('swift-rank', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	/**
	 * Plugin activation
	 */
	public function activate()
	{
		// Set default options.
		$default_options = array(
			// General
			'code_placement' => 'head',
			'minify_schema' => true,
			'default_image' => array('url' => '', 'id' => 0, 'width' => 0, 'height' => 0),

			// Knowledge Graph settings.
			'knowledge_graph_enabled' => true,
			'knowledge_graph_type' => 'Organization',
			'organization_fields' => array(
				'organizationType' => 'Organization',
				'name' => '{site_name}',
				'url' => '{site_url}',
				'logo' => '{site_logo}',
				'description' => '{site_description}',
			),
			'person_fields' => array(
				'name' => '{site_name}',
				'url' => '{site_url}',
				'image' => '{site_logo}',
				'description' => '{site_description}',
			),
			'localbusiness_fields' => array(
				'businessType' => 'LocalBusiness',
				'name' => '{site_name}',
				'url' => '{site_url}',
				'image' => '{site_logo}',
				'description' => '{site_description}',
			),

			// Auto Schema
			'auto_schema_post_enabled' => true,
			'auto_schema_post_type' => 'Article',
			'auto_schema_page_enabled' => true,
			'auto_schema_search_enabled' => true,
			'auto_schema_woocommerce_enabled' => true,

			'breadcrumb_enabled' => true,
			'breadcrumb_separator' => '»',
			'breadcrumb_home_text' => 'Home',
			'breadcrumb_show_home' => true,

			// Pro Features (Defaults off)
			'sitelinks_searchbox' => false,
		);

		if (!get_option('swift_rank_settings')) {
			add_option('swift_rank_settings', $default_options);
		}

		// Set wizard redirect transient for new installations
		$wizard_state = get_option('swift_rank_wizard_state');
		if (!$wizard_state || empty($wizard_state['is_complete'])) {
			set_transient('swift_rank_activation_redirect', true, 30);
		}

		// Register the custom post type.
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/admin/cpt/class-cpt-registration.php';
		Swift_Rank_CPT_Registration::get_instance()->register_post_type();

		// Flush rewrite rules.
		flush_rewrite_rules();
	}
}

/**
 * Initialize the plugin
 */
function swift_rank_init()
{
	return Swift_Rank::get_instance();
}

// Start the plugin.
swift_rank_init();
