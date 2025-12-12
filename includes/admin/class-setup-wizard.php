<?php
/**
 * Setup Wizard Admin Page
 *
 * @package Swift_Rank
 */

namespace Swift_Rank\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Setup_Wizard
{
    /**
     * Initialize the setup wizard
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_wizard_page'));
        add_action('admin_init', array($this, 'maybe_redirect_to_wizard'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_wizard_assets'));
    }

    /**
     * Register the wizard admin page
     */
    public function register_wizard_page()
    {
        add_submenu_page(
            null, // No parent menu (hidden from menu)
            __('Swift Rank Setup', 'swift-rank'),
            __('Setup Wizard', 'swift-rank'),
            'manage_options',
            'swift-rank-setup',
            array($this, 'render_wizard_page')
        );
    }

    /**
     * Maybe redirect to wizard on activation
     */
    public function maybe_redirect_to_wizard()
    {
        // Check if we should redirect to wizard
        if (get_transient('swift_rank_activation_redirect')) {
            delete_transient('swift_rank_activation_redirect');

            // Don't redirect if activating multiple plugins
            if (isset($_GET['activate-multi'])) {
                return;
            }

            // Check if wizard is already complete
            $wizard_state = get_option('swift_rank_wizard_state', array());
            if (!empty($wizard_state['is_complete'])) {
                return;
            }

            wp_safe_redirect(admin_url('admin.php?page=swift-rank-setup'));
            exit;
        }
    }

    /**
     * Enqueue wizard assets
     */
    public function enqueue_wizard_assets($hook)
    {
        if ($hook !== 'admin_page_swift-rank-setup') {
            return;
        }

        // Enqueue wizard scripts
        $asset_file = SWIFT_RANK_PATH . 'build/setup-wizard.asset.php';

        if (file_exists($asset_file)) {
            $asset = include $asset_file;

            wp_enqueue_script(
                'swift-rank-wizard',
                SWIFT_RANK_URL . 'build/setup-wizard.js',
                $asset['dependencies'],
                $asset['version'],
                true
            );

            wp_enqueue_style(
                'swift-rank-wizard',
                SWIFT_RANK_URL . 'build/style-setup-wizard.css',
                array('wp-components'),
                $asset['version']
            );


            // Get variable groups from variable replacer
            require_once SWIFT_RANK_PATH . 'includes/utils/class-schema-variable-replacer.php';
            $replacer_class = apply_filters('swift_rank_variable_replacer_class', 'Schema_Variable_Replacer');
            if (class_exists($replacer_class)) {
                $variable_replacer = new $replacer_class();
            } else {
                $variable_replacer = new \Swift_Rank\Utils\Schema_Variable_Replacer();
            }
            $variable_groups = $variable_replacer->get_variable_groups();

            // Get schema types for FieldsBuilder
            require_once SWIFT_RANK_PATH . 'includes/schema-types-registration.php';
            $schema_types = apply_filters('swift_rank_register_types', array());

            // Localize script with settings
            wp_localize_script('swift-rank-wizard', 'swiftRankWizardSettings', array(
                'siteUrl' => get_site_url(),
                'siteName' => get_bloginfo('name'),
                'siteLogo' => $this->get_site_logo(),
                'pluginUrl' => SWIFT_RANK_URL,
                'isWooCommerceActive' => class_exists('WooCommerce'),
                'isProActive' => defined('SWIFT_RANK_PRO_VERSION'),
                'variableGroups' => $variable_groups,
                'schemaTypes' => array_values($schema_types),
            ));
        }
    }

    /**
     * Get site logo URL
     */
    private function get_site_logo()
    {
        $custom_logo_id = get_theme_mod('custom_logo');

        if ($custom_logo_id) {
            $logo = wp_get_attachment_image_url($custom_logo_id, 'full');
            if ($logo) {
                return $logo;
            }
        }

        return '';
    }

    /**
     * Render the wizard page
     */
    public function render_wizard_page()
    {
        echo '<div class="wrap">';
        echo '<div id="swift-rank-wizard-root"></div>';
        echo '</div>';

        // Hide Freemius notice bar on wizard page
        echo '<style>.fs-notice { display: none !important; }</style>';
    }
}

// Initialize with proper namespace
new \Swift_Rank\Admin\Setup_Wizard();
