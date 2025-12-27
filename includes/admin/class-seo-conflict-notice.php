<?php
/**
 * SEO Plugin Conflict Notice
 *
 * @package Swift_Rank
 */

namespace Swift_Rank\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class SEO_Conflict_Notice
{
    /**
     * Initialize the notice
     */
    public function __construct()
    {
        add_action('admin_notices', array($this, 'show_conflict_notices'));
        add_action('wp_ajax_swift_rank_dismiss_seo_notice', array($this, 'dismiss_notice'));
    }

    /**
     * Check if a specific SEO plugin is active
     */
    private function is_plugin_active($plugin)
    {
        switch ($plugin) {
            case 'yoast':
                return defined('WPSEO_VERSION');
            case 'aioseo':
                return defined('AIOSEO_VERSION');
            case 'rankmath':
                return defined('RANK_MATH_VERSION');
            default:
                return false;
        }
    }

    /**
     * Check if schema is disabled for a plugin
     */
    private function is_schema_disabled($plugin)
    {
        $option_name = 'disable_' . $plugin . '_schema';
        return get_option('swift_rank_' . $option_name, false);
    }

    /**
     * Show SEO conflict notices
     */
    public function show_conflict_notices()
    {
        // Don't show on Swift Rank settings page (already shown there)
        if (isset($_GET['page']) && $_GET['page'] === 'swift-rank') {
            return;
        }

        $plugins = array(
            'yoast' => array(
                'name' => 'Yoast SEO',
                'dismissed_key' => 'swift_rank_yoast_notice_dismissed'
            ),
            'aioseo' => array(
                'name' => 'All in One SEO',
                'dismissed_key' => 'swift_rank_aioseo_notice_dismissed'
            ),
            'rankmath' => array(
                'name' => 'Rank Math',
                'dismissed_key' => 'swift_rank_rankmath_notice_dismissed'
            )
        );

        foreach ($plugins as $plugin_key => $plugin_data) {
            // Check if plugin is active and schema is not disabled
            if ($this->is_plugin_active($plugin_key) && !$this->is_schema_disabled($plugin_key)) {
                // Check if user dismissed notice
                if (get_transient($plugin_data['dismissed_key'])) {
                    continue;
                }

                $this->render_notice($plugin_key, $plugin_data['name'], $plugin_data['dismissed_key']);
            }
        }
    }

    /**
     * Render individual notice
     */
    private function render_notice($plugin_key, $plugin_name, $dismissed_key)
    {
        $settings_url = admin_url('admin.php?page=swift-rank-settings#general');
        ?>
        <div class="notice notice-warning is-dismissible swift-rank-seo-conflict-notice"
            data-plugin="<?php echo esc_attr($plugin_key); ?>" data-dismiss-key="<?php echo esc_attr($dismissed_key); ?>">
            <p>
                <strong><?php _e('Swift Rank - Schema Conflict Detected', 'swift-rank'); ?></strong>
            </p>
            <p>
                <?php
                printf(
                    __('%s is active and may be outputting schema markup. This could cause duplicate schema on your site.', 'swift-rank'),
                    '<strong>' . esc_html($plugin_name) . '</strong>'
                );
                ?>
            </p>
            <p>
                <a href="<?php echo esc_url($settings_url); ?>" class="button button-primary">
                    <?php printf(__('Disable %s Schema', 'swift-rank'), $plugin_name); ?>
                </a>
                <a href="#" class="button button-link swift-rank-seo-dismiss">
                    <?php _e('Dismiss', 'swift-rank'); ?>
                </a>
            </p>
        </div>
        <?php
    }

    /**
     * Dismiss notice temporarily
     */
    public function dismiss_notice()
    {
        $dismiss_key = isset($_POST['dismiss_key']) ? sanitize_text_field($_POST['dismiss_key']) : '';

        if (empty($dismiss_key)) {
            wp_send_json_error();
        }

        // Dismiss for 30 days
        set_transient($dismiss_key, true, 30 * DAY_IN_SECONDS);
        wp_send_json_success();
    }
}

// Initialize with proper namespace
new \Swift_Rank\Admin\SEO_Conflict_Notice();
