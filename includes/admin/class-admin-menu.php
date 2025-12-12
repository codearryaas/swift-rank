<?php
/**
 * Admin Menu Handler
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Swift_Rank_Admin_Menu class
 *
 * Handles admin menu registration and related functionality.
 */
class Swift_Rank_Admin_Menu
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
     * @return Swift_Rank_Admin_Menu
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
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_head', array($this, 'output_menu_icon_styles'));
        add_action('admin_footer', array($this, 'upgrade_menu_new_tab_script'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu()
    {
        // Main menu item.
        add_menu_page(
            __('Swift Rank', 'swift-rank'),
            __('Swift Rank', 'swift-rank'),
            'manage_options',
            'swift-rank',
            array($this, 'render_settings_page_react'),
            SWIFT_RANK_PLUGIN_URL . 'assets/images/swift-rank-logo.png',
            100
        );

        // New React-based Settings Page.
        add_submenu_page(
            'swift-rank',
            __('Swift Rank Settings', 'swift-rank'),
            __('Settings', 'swift-rank'),
            'manage_options',
            'swift-rank-settings',
            array($this, 'render_settings_page_react')
        );

        // Upgrade to Pro submenu (only if Pro not active)
        if (!defined('SWIFT_RANK_PRO_VERSION')) {
            $upgrade_url = apply_filters('swift_rank_upgrade_url', 'https://toolpress.net/swift-rank/pricing');
            add_submenu_page(
                'swift-rank',
                __('Upgrade to Pro', 'swift-rank'),
                '<span style="color: #f0b849;"><svg width="14" height="14" viewBox="0 0 24 24" fill="#f0b849" style="vertical-align: middle; margin-right: 4px;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>' . __('Upgrade to Pro', 'swift-rank') . '</span>',
                'manage_options',
                $upgrade_url
            );
        }
    }

    /**
     * Render React settings page
     */
    public function render_settings_page_react()
    {
        echo '<div id="swift-rank-settings-root"></div>';
    }

    /**
     * Output menu icon styles
     */
    public function output_menu_icon_styles()
    {
        ?>
        <style>
            #adminmenu .toplevel_page_swift-rank .wp-menu-image img {
                max-width: 20px;
                max-height: 20px;
                opacity: 1;
            }
        </style>
        <?php
    }

    /**
     * Add JavaScript to make upgrade menu link open in new tab
     */
    public function upgrade_menu_new_tab_script()
    {
        if (!defined('SWIFT_RANK_PRO_VERSION')) {
            $upgrade_url = apply_filters('swift_rank_upgrade_url', 'https://toolpress.net/swift-rank/pricing');
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $('#adminmenu a[href="<?php echo esc_js($upgrade_url); ?>"]').attr('target', '_blank').attr('rel', 'noopener noreferrer');
                });
            </script>
            <?php
        }
    }
}
