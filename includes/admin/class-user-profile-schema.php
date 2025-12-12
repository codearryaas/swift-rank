<?php
/**
 * User Profile Schema Handler (Free)
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Swift_Rank_User_Profile class
 */
class Swift_Rank_User_Profile
{

    /**
     * Instance
     *
     * @var Swift_Rank_User_Profile
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Swift_Rank_User_Profile
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
    protected function __construct()
    {
        add_action('show_user_profile', array($this, 'render_fields'));
        add_action('edit_user_profile', array($this, 'render_fields'));
    }

    /**
     * Render fields
     *
     * @param WP_User $user User object.
     */
    public function render_fields($user)
    {
        if (defined('SWIFT_RANK_PRO_VERSION')) {
            return;
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        $this->enqueue_assets($user);
        $this->render_html($user);
    }

    /**
     * Enqueue assets for user profile
     *
     * @param WP_User $user User object.
     */
    protected function enqueue_assets($user)
    {
        $asset_file = SWIFT_RANK_PLUGIN_DIR . 'build/user-profile/index.asset.php';
        if (!file_exists($asset_file)) {
            return;
        }

        $assets = require $asset_file;

        wp_enqueue_script(
            'swift-rank-user-profile',
            SWIFT_RANK_PLUGIN_URL . 'build/user-profile/index.js',
            $assets['dependencies'],
            $assets['version'],
            true
        );

        wp_enqueue_style(
            'swift-rank-user-profile',
            SWIFT_RANK_PLUGIN_URL . 'build/user-profile/style-index.css',
            array(),
            $assets['version']
        );

        // Localize data
        wp_localize_script(
            'swift-rank-user-profile',
            'swiftRankPostMetabox',
            $this->get_localized_data($user)
        );

        // Localize template URLs
        wp_localize_script(
            'swift-rank-user-profile',
            'swiftRankData',
            array(
                'templatesUrl' => admin_url('edit.php?post_type=sr_template'),
                'newTemplateUrl' => admin_url('post-new.php?post_type=sr_template'),
            )
        );

        // Localize config
        wp_localize_script(
            'swift-rank-user-profile',
            'swiftRankConfig',
            array(
                'isProActivated' => defined('SWIFT_RANK_PRO_VERSION'),
            )
        );
    }

    /**
     * Get localized data for React component
     *
     * @param WP_User $user User object.
     * @return array
     */
    protected function get_localized_data($user)
    {
        return array(
            'postId' => $user->ID,
            'matchingTemplates' => array(), // Always empty in Free
            'savedOverrides' => array(), // No overrides in Free
            'schemaTypes' => Schema_Type_Helper::get_types_for_select(),
            'nonce' => wp_create_nonce('swift_rank_metabox'),
            'context' => 'user-profile',
        );
    }

    /**
     * Render HTML wrapper and React root
     *
     * @param WP_User $user User object.
     */
    protected function render_html($user)
    {
        ?>
        <h3><?php esc_html_e('Swift Rank Settings', 'swift-rank'); ?></h3>

        <div class="swift-rank-user-profile-wrapper" style="max-width: 800px; margin-top: 20px;">
            <p class="description" style="margin-bottom: 15px;">
                <?php esc_html_e('Templates matching this user\'s role are automatically applied.', 'swift-rank'); ?>
            </p>

            <div id="swift-rank-user-profile-root"></div>
        </div>
        <?php
    }
}
