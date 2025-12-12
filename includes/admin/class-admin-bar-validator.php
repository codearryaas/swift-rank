<?php
/**
 * Admin Bar Validator Class
 *
 * Adds schema validation links to the WordPress admin bar on the frontend.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Swift_Rank_Admin_Bar_Validator class
 */
class Swift_Rank_Admin_Bar_Validator
{

	/**
	 * Instance of this class
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Swift_Rank_Admin_Bar_Validator
	 */
	public static function get_instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct()
	{
		add_action('admin_bar_menu', array($this, 'add_admin_bar_validator'), 100);
		add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_admin_bar_styles'));
	}

	/**
	 * Enqueue styles for admin bar on frontend
	 */
	public function enqueue_frontend_admin_bar_styles()
	{
		// Only enqueue if admin bar is showing and user can manage options.
		if (!is_admin_bar_showing() || !current_user_can('manage_options')) {
			return;
		}

		// Enqueue dashicons for frontend admin bar.
		wp_enqueue_style('dashicons');

		// Output inline styles directly in head for admin bar.
		add_action('wp_head', array($this, 'output_admin_bar_styles'), 100);
	}

	/**
	 * Output admin bar styles
	 */
	public function output_admin_bar_styles()
	{
		?>
		<style>
			#wpadminbar #wp-admin-bar-swift-rank-validator>.ab-item {
				display: flex !important;
				align-items: center !important;
				gap: 4px;
			}

			#wpadminbar #wp-admin-bar-swift-rank-validator .dashicons {
				font-size: 16px !important;
				width: 16px !important;
				height: 16px !important;
				line-height: 1 !important;
			}

			#wpadminbar #wp-admin-bar-swift-rank-validator .ab-submenu .dashicons {
				margin-right: 6px;
			}
		</style>
		<?php
	}

	/**
	 * Add schema validator options to admin bar on frontend
	 *
	 * @param WP_Admin_Bar $wp_admin_bar The admin bar instance.
	 */
	public function add_admin_bar_validator($wp_admin_bar)
	{
		// Only show on frontend and for users who can manage options.
		if (is_admin() || !current_user_can('manage_options')) {
			return;
		}

		// Get the current page URL.
		$current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		// Add parent menu item.
		$wp_admin_bar->add_node(array(
			'id' => 'swift-rank-validator',
			'title' => '<span class="ab-icon dashicons dashicons-chart-line" style="font-size: 18px; line-height: 1.5;"></span>' . __('Test Schema', 'swift-rank'),
			'href' => '#',
			'meta' => array(
				'title' => __('Validate Schema Markup', 'swift-rank'),
			),
		));

		// Add Google Rich Results Test submenu.
		$wp_admin_bar->add_node(array(
			'id' => 'swift-rank-google-test',
			'parent' => 'swift-rank-validator',
			'title' => __('Google Rich Results Test', 'swift-rank'),
			'href' => 'https://search.google.com/test/rich-results?url=' . urlencode($current_url),
			'meta' => array(
				'target' => '_blank',
				'rel' => 'noopener',
				'title' => __('Test this page with Google Rich Results Test', 'swift-rank'),
			),
		));

		// Add Schema.org Validator submenu.
		$wp_admin_bar->add_node(array(
			'id' => 'swift-rank-schemaorg-validator',
			'parent' => 'swift-rank-validator',
			'title' => __('Schema.org Validator', 'swift-rank'),
			'href' => 'https://validator.schema.org/#url=' . urlencode($current_url),
			'meta' => array(
				'target' => '_blank',
				'rel' => 'noopener',
				'title' => __('Validate this page with Schema.org Validator', 'swift-rank'),
			),
		));
	}
}
