<?php
/**
 * Admin Assets Handler
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Swift_Rank_Admin_Assets class
 *
 * Handles enqueuing of admin scripts and styles.
 */
class Swift_Rank_Admin_Assets
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
     * @return Swift_Rank_Admin_Assets
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
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_settings_assets'));
    }

    /**
     * Enqueue admin assets
     *
     * @param string $hook Current admin page hook.
     */
    public function enqueue_admin_assets($hook)
    {
        // Only load on Swift Rank admin pages.
        if (false === strpos($hook, 'swift-rank')) {
            return;
        }

        // Enqueue WordPress media library.
        wp_enqueue_media();

        wp_enqueue_style(
            'swift-rank-admin',
            SWIFT_RANK_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            SWIFT_RANK_VERSION
        );

        wp_enqueue_script(
            'swift-rank-admin',
            SWIFT_RANK_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            SWIFT_RANK_VERSION,
            true
        );

        // Localize script with data.
        wp_localize_script(
            'swift-rank-admin',
            'tpSchemaAdmin',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('swift_rank_admin_nonce'),
            )
        );
    }

    /**
     * Enqueue settings assets for React page
     */
    public function enqueue_settings_assets($hook)
    {
        // Check if it's our settings page (now a submenu of swift-rank)
        if ('swift-rank_page_swift-rank-settings' !== $hook && 'admin_page_swift-rank-settings' !== $hook) {
            // Also check strictly for the slug if the hook name varies
            if (!isset($_GET['page']) || 'swift-rank-settings' !== sanitize_text_field(wp_unslash($_GET['page']))) {
                return;
            }
        }

        wp_enqueue_media();

        $asset_file = include(SWIFT_RANK_PLUGIN_DIR . 'build/admin-settings/index.asset.php');

        // Get variable groups from variable replacer
        require_once SWIFT_RANK_PLUGIN_DIR . 'includes/utils/class-schema-variable-replacer.php';
        $replacer_class = apply_filters('swift_rank_variable_replacer_class', 'Schema_Variable_Replacer');
        if (class_exists($replacer_class)) {
            $variable_replacer = new $replacer_class();
        } else {
            $variable_replacer = new Schema_Variable_Replacer();
        }
        $variable_groups = $variable_replacer->get_variable_groups();

        // Get schema types for FieldsBuilder
        require_once SWIFT_RANK_PLUGIN_DIR . 'includes/schema-types-registration.php';
        $schema_types = apply_filters('swift_rank_register_types', array());

        // Localize script with settings data
        $settings_data = array(
            'isProActive' => defined('SWIFT_RANK_PRO_VERSION'),
            'isWooCommerceActive' => class_exists('WooCommerce'),
            'upgradeUrl' => apply_filters('swift_rank_upgrade_url', 'https://toolpress.net/swift-rank/pricing'),
            'version' => defined('SWIFT_RANK_VERSION') ? SWIFT_RANK_VERSION : 'N/A',
            'proVersion' => defined('SWIFT_RANK_PRO_VERSION') ? SWIFT_RANK_PRO_VERSION : null,
            'wpVersion' => get_bloginfo('version'),
            'phpVersion' => phpversion(),
            'activeTheme' => wp_get_theme()->get('Name'),
            'variableGroups' => $variable_groups,
            'schemaTypes' => array_values($schema_types),
        );

        wp_enqueue_script(
            'swift-rank-settings',
            SWIFT_RANK_PLUGIN_URL . 'build/admin-settings/index.js',
            $asset_file['dependencies'],
            $asset_file['version'],
            true
        );

        wp_localize_script('swift-rank-settings', 'swiftRankSettings', $settings_data);

        wp_enqueue_style(
            'swift-rank-settings',
            SWIFT_RANK_PLUGIN_URL . 'build/admin-settings/style-index.css',
            array('wp-components'),
            $asset_file['version']
        );
    }
}
