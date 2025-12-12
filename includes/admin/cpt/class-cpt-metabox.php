<?php
/**
 * Schema Template CPT Metabox Handler
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Swift_Rank_CPT_Metabox class
 *
 * Handles metaboxes for sr_template post type.
 */
class Swift_Rank_CPT_Metabox
{

    /**
     * Instance of this class
     *
     * @var Swift_Rank_CPT_Metabox
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return Swift_Rank_CPT_Metabox
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
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_sr_template', array($this, 'save_meta_data'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_metabox_assets'));
    }

    /**
     * Add metaboxes
     */
    public function add_meta_boxes()
    {
        add_meta_box(
            'schema_template_config',
            __('Schema Configuration', 'swift-rank'),
            array($this, 'render_metabox'),
            'sr_template',
            'normal',
            'high'
        );

        // Add Pro upgrade sidebar if Pro is not active.
        if (!$this->is_pro_active()) {
            add_meta_box(
                'swift_rank_pro_upgrade',
                __('Upgrade to Pro', 'swift-rank'),
                array($this, 'render_pro_upgrade_sidebar'),
                'sr_template',
                'side',
                'default'
            );
        }
    }

    /**
     * Check if Pro plugin is active.
     *
     * @return bool
     */
    private function is_pro_active()
    {
        return defined('SWIFT_RANK_PRO_VERSION');
    }

    /**
     * Render Pro upgrade sidebar metabox
     */
    public function render_pro_upgrade_sidebar()
    {
        echo '<div id="schema-pro-sidebar-root"></div>';
    }

    /**
     * Render metabox
     */
    public function render_metabox()
    {
        echo '<div id="schema-template-metabox-root"></div>';
    }

    /**
     * Enqueue metabox assets
     *
     * @param string $hook Current admin page hook.
     */
    public function enqueue_metabox_assets($hook)
    {
        // Only load on sr_template edit screen.
        global $post_type;
        if (('post.php' === $hook || 'post-new.php' === $hook) && 'sr_template' === $post_type) {
            $asset_file = SWIFT_RANK_PLUGIN_DIR . 'build/template-metabox/index.asset.php';

            if (file_exists($asset_file)) {
                $asset = require $asset_file;

                // Enqueue WordPress media.
                wp_enqueue_media();

                wp_enqueue_script(
                    'swift-rank-metabox',
                    SWIFT_RANK_PLUGIN_URL . 'build/template-metabox/index.js',
                    $asset['dependencies'],
                    $asset['version'],
                    true
                );

                wp_enqueue_style(
                    'swift-rank-metabox',
                    SWIFT_RANK_PLUGIN_URL . 'build/template-metabox/style-index.css',
                    array('wp-components'),
                    $asset['version']
                );

                // Get post meta.
                $post_id = get_the_ID();
                $schema_data = get_post_meta($post_id, '_schema_template_data', true);

                // Get schema types with Pro filter.
                $schema_types = $this->get_schema_types();

                // Get variable groups from variable replacer
                require_once SWIFT_RANK_PLUGIN_DIR . 'includes/utils/class-schema-variable-replacer.php';
                $replacer_class = apply_filters('swift_rank_variable_replacer_class', 'Schema_Variable_Replacer');
                if (class_exists($replacer_class)) {
                    $variable_replacer = new $replacer_class();
                } else {
                    $variable_replacer = new Schema_Variable_Replacer();
                }
                $variable_groups = $variable_replacer->get_variable_groups();

                wp_localize_script(
                    'swift-rank-metabox',
                    'swiftRankMetabox',
                    array(
                        'postId' => $post_id,
                        'schemaData' => $schema_data ? $schema_data : array(),
                        'schemaTypes' => $schema_types,
                        'variableGroups' => $variable_groups,
                        'nonce' => wp_create_nonce('swift_rank_metabox'),
                        'restUrl' => rest_url('swift-rank/v1/options/'),
                        'restNonce' => wp_create_nonce('wp_rest'),
                        'isProActivated' => defined('SWIFT_RANK_PRO_VERSION'),
                        'userRoles' => $this->get_user_roles(),
                    )
                );

                // Also set global config for field renderers
                wp_localize_script(
                    'swift-rank-metabox',
                    'swiftRankConfig',
                    array(
                        'isProActivated' => defined('SWIFT_RANK_PRO_VERSION'),
                    )
                );

            }
        }
    }

    /**
     * Save meta data
     *
     * @param int $post_id Post ID.
     */
    public function save_meta_data($post_id)
    {
        // Check if nonce is set.
        if (!isset($_POST['swift_rank_metabox_nonce'])) {
            return;
        }

        // Verify nonce.
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['swift_rank_metabox_nonce'])), 'swift_rank_metabox')) {
            return;
        }

        // Check autosave.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions.
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save schema data.
        if (isset($_POST['_schema_template_data'])) {
            $schema_data = json_decode(wp_unslash($_POST['_schema_template_data']), true);

            // Populate default values for missing fields.
            $schema_type = isset($schema_data['schemaType']) ? $schema_data['schemaType'] : '';
            if ($schema_type) {
                $registered_types = Schema_Type_Helper::get_schema_types();
                if (isset($registered_types[$schema_type]['fields'])) {
                    if (!isset($schema_data['fields'])) {
                        $schema_data['fields'] = array();
                    }

                    foreach ($registered_types[$schema_type]['fields'] as $field) {
                        if (isset($field['name']) && isset($field['default'])) {
                            if (!array_key_exists($field['name'], $schema_data['fields'])) {
                                $schema_data['fields'][$field['name']] = $field['default'];
                            }
                        }
                    }
                }
            }

            update_post_meta($post_id, '_schema_template_data', $schema_data);

            // Also save schema type and subtype separately for easier filtering/sorting.
            if (isset($schema_data['schemaType'])) {
                update_post_meta($post_id, '_schema_type', sanitize_text_field($schema_data['schemaType']));
            }

            // Save subtype based on schema type.
            $subtype = '';
            $schema_type = isset($schema_data['schemaType']) ? $schema_data['schemaType'] : '';

            if ($schema_type) {
                // Determine subtype field name based on schema type (e.g., Article -> articleType).
                $subtype_field_name = lcfirst($schema_type) . 'Type';

                if (isset($schema_data['fields'][$subtype_field_name]) && !empty($schema_data['fields'][$subtype_field_name])) {
                    $subtype = $schema_data['fields'][$subtype_field_name];
                } else {
                    // Try to find default value from registered types.
                    if (!isset($registered_types)) {
                        $registered_types = Schema_Type_Helper::get_schema_types();
                    }

                    if (isset($registered_types[$schema_type]['fields'])) {
                        foreach ($registered_types[$schema_type]['fields'] as $field) {
                            if ($field['name'] === $subtype_field_name && isset($field['default'])) {
                                $subtype = $field['default'];
                                break;
                            }
                        }
                    }
                }
            }
            update_post_meta($post_id, '_schema_subtype', sanitize_text_field($subtype));

            // Save conditions from the bundled data.
            if (isset($schema_data['includeConditions'])) {
                update_post_meta($post_id, '_schema_template_conditions', $schema_data['includeConditions']);
            }
        }
    }

    /**
     * Get available schema types.
     *
     * Uses Schema_Type_Helper to get types registered via filters.
     *
     * @return array
     */
    private function get_schema_types()
    {
        return Schema_Type_Helper::get_types_for_select();
    }
    /**
     * Get formatted user roles for JS
     *
     * @return array
     */
    private function get_user_roles()
    {
        $wp_roles = wp_roles();
        $roles = array();

        foreach ($wp_roles->roles as $slug => $role) {
            $roles[] = array(
                'id' => $slug,
                'name' => $role['name'],
            );
        }

        return $roles;
    }
}
