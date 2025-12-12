<?php
/**
 * Admin Notice Class
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Swift_Rank_Admin_Notice class
 * 
 * Handles admin notices for the plugin.
 */
class Swift_Rank_Admin_Notice
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
	 * @return Swift_Rank_Admin_Notice
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
		// Only add notices if Pro is not active.
		if (!defined('SWIFT_RANK_PRO_VERSION')) {
			add_action('admin_notices', array($this, 'render_pro_upgrade_notice'));
			add_action('wp_ajax_swift_rank_dismiss_pro_notice', array($this, 'dismiss_pro_upgrade_notice'));
		}
	}

	/**
	 * Render Pro upgrade admin notice
	 */
	public function render_pro_upgrade_notice()
	{
		// Don't show if Pro is active.
		if (defined('SWIFT_RANK_PRO_VERSION')) {
			return;
		}

		// Don't show if dismissed.
		$dismissed = get_option('swift_rank_pro_notice_dismissed', false);
		if ($dismissed) {
			// Check if 30 days have passed since dismissal.
			$dismissed_time = get_option('swift_rank_pro_notice_dismissed_time', 0);
			if (time() - $dismissed_time < 30 * DAY_IN_SECONDS) {
				return;
			}
		}

		// Only show on Swift Rank pages or dashboard.
		$screen = get_current_screen();
		$show_on_screens = array('dashboard', 'toplevel_page_swift-rank', 'swift-rank_page_swift-rank', 'edit-sr_template', 'sr_template');
		if (!$screen || !in_array($screen->id, $show_on_screens, true)) {
			return;
		}

		$upgrade_url = apply_filters('swift_rank_upgrade_url', 'https://toolpress.net/swift-rank/pricing');
		?>
		<div class="notice notice-info is-dismissible swift-rank-pro-notice"
			style="border-left-color: #d99c00; padding: 12px 12px 12px 16px;">
			<div style="display: flex; align-items: center; gap: 16px;padding: 0.5rem;">
				<div
					style="flex-shrink: 0; width: 40px; height: 40px; background: linear-gradient(135deg, #f0b849 0%, #d99c00 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
					<span class="dashicons dashicons-star-filled"
						style="color: #fff; font-size: 20px; width: 20px; height: 20px;"></span>
				</div>
				<div style="flex: 1;">
					<p style="margin: 0 0 4px; font-size: 14px; font-weight: 600; color: #1d2327;">
						<?php esc_html_e('Upgrade to Swift Rank Pro', 'swift-rank'); ?>
					</p>
					<p style="margin: 0; color: #50575e; font-size: 13px;">
						<?php esc_html_e('Unlock Pro schema types (Product, Recipe, Podcast), advanced condition groups, breadcrumb schema, and more.', 'swift-rank'); ?>
					</p>
				</div>
				<a href="<?php echo esc_url($upgrade_url); ?>" target="_blank" rel="noopener noreferrer"
					style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #f0b849 0%, #d99c00 100%); color: #1e1e1e; border-radius: 4px; font-size: 13px; font-weight: 600; text-decoration: none;">
					<?php esc_html_e('Upgrade Now', 'swift-rank'); ?>
					<span class="dashicons dashicons-external" style="font-size: 14px; width: 14px; height: 14px;"></span>
				</a>
			</div>
		</div>
		<script>
			jQuery(document).ready(function ($) {
				$('.swift-rank-pro-notice').on('click', '.notice-dismiss', function () {
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'swift_rank_dismiss_pro_notice',
							nonce: '<?php echo esc_js(wp_create_nonce('swift_rank_dismiss_pro_notice')); ?>'
						}
					});
				});
			});
		</script>
		<?php
	}

	/**
	 * Dismiss Pro upgrade notice via AJAX
	 */
	public function dismiss_pro_upgrade_notice()
	{
		check_ajax_referer('swift_rank_dismiss_pro_notice', 'nonce');

		if (!current_user_can('manage_options')) {
			wp_die(-1);
		}

		update_option('swift_rank_pro_notice_dismissed', true);
		update_option('swift_rank_pro_notice_dismissed_time', time());

		wp_die(1);
	}
}
