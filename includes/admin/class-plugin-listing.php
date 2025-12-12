<?php
/**
 * Plugin Listing Page Handler
 *
 * Handles plugin action links on the plugins listing page.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Swift_Rank_Plugin_Listing class
 *
 * Manages plugin action links and meta links on the plugins page.
 */
class Swift_Rank_Plugin_Listing
{

    /**
     * Instance of this class
     *
     * @var Swift_Rank_Plugin_Listing
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return Swift_Rank_Plugin_Listing
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
        // Add action links (Settings, etc.)
        add_filter('plugin_action_links_' . SWIFT_RANK_PLUGIN_BASENAME, array($this, 'add_action_links'));

        // Add meta links (Upgrade to Pro, etc.)
        add_filter('plugin_row_meta', array($this, 'add_meta_links'), 10, 2);
    }

    /**
     * Add action links to plugin listing
     *
     * @param array $links Existing action links.
     * @return array Modified action links.
     */
    public function add_action_links($links)
    {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url(admin_url('admin.php?page=swift-rank-settings')),
            esc_html__('Settings', 'swift-rank')
        );

        // Add settings link at the beginning
        array_unshift($links, $settings_link);

        return $links;
    }

    /**
     * Add meta links to plugin listing
     *
     * @param array  $links Existing meta links.
     * @param string $file  Plugin file path.
     * @return array Modified meta links.
     */
    public function add_meta_links($links, $file)
    {
        // Only add links for this plugin
        if (SWIFT_RANK_PLUGIN_BASENAME !== $file) {
            return $links;
        }

        // Check if Pro is active
        if (!defined('SWIFT_RANK_PRO_VERSION')) {
            $upgrade_link = sprintf(
                '<a href="%s" target="_blank" style="color: #00a32a; font-weight: 600;">%s</a>',
                esc_url('https://toolpress.net/swift-rank/pricing'), // Replace with actual upgrade URL
                esc_html__('Upgrade to Pro', 'swift-rank')
            );

            $links[] = $upgrade_link;
        }

        return $links;
    }
}
