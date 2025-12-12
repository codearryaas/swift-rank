<?php
/**
 * Schema Template CPT Columns Handler
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Swift_Rank_CPT_Columns class
 *
 * Handles custom columns for sr_template post type listing.
 */
class Swift_Rank_CPT_Columns
{

    /**
     * Instance of this class
     *
     * @var Swift_Rank_CPT_Columns
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return Swift_Rank_CPT_Columns
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
        add_filter('manage_sr_template_posts_columns', array($this, 'add_custom_columns'));
        add_action('manage_sr_template_posts_custom_column', array($this, 'render_custom_columns'), 10, 2);
        add_filter('manage_edit-sr_template_sortable_columns', array($this, 'add_sortable_columns'));
    }

    /**
     * Add custom columns to schema template listing
     *
     * @param array $columns Existing columns.
     * @return array Modified columns.
     */
    public function add_custom_columns($columns)
    {
        // Insert Type and Sub-Type columns after Title.
        $new_columns = array();
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ('title' === $key) {
                $new_columns['schema_type'] = __('Type', 'swift-rank');
                $new_columns['schema_subtype'] = __('Sub-Type', 'swift-rank');
            }
        }
        return $new_columns;
    }

    /**
     * Render custom column content
     *
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function render_custom_columns($column, $post_id)
    {
        if ('schema_type' === $column) {
            $schema_type = get_post_meta($post_id, '_schema_type', true);

            if ($schema_type) {
                // Get schema types to find the label and icon (without Pro suffix).
                $schema_types = Schema_Type_Helper::get_types_for_select();
                $type_label = $schema_type;
                $type_icon = '';

                foreach ($schema_types as $type) {
                    if ($type['value'] === $schema_type) {
                        // Remove " (Pro)" from label.
                        $type_label = str_replace(' (Pro)', '', $type['label']);
                        $type_icon = isset($type['icon']) ? $type['icon'] : '';
                        break;
                    }
                }

                echo '<span class="schema-type-badge">' . esc_html($type_label) . '</span>';

                // Add inline styles for badge.
                if (!wp_style_is('swift-rank-admin-columns', 'enqueued')) {
                    echo '<style>
					.schema-type-badge {
						display: inline-flex;
						align-items: center;
						gap: 6px;
						padding: 3px 8px;
						background: #f0f0f1;
						border: 1px solid #c3c4c7;
						border-radius: 3px;
						font-size: 12px;
						font-weight: 500;
						color: #2c3338;
					}
					.schema-subtype-badge {
						display: inline-block;
						padding: 2px 6px;
						background: #fff;
						border: 1px solid #dcdcde;
						border-radius: 3px;
						font-size: 11px;
						color: #646970;
					}
					</style>';
                }
            } else {
                echo '<span style="color: #646970;">—</span>';
            }
        } elseif ('schema_subtype' === $column) {
            $schema_subtype = get_post_meta($post_id, '_schema_subtype', true);

            if ($schema_subtype) {
                echo '<span class="schema-subtype-badge">' . esc_html($schema_subtype) . '</span>';
            } else {
                echo '<span style="color: #646970;">—</span>';
            }
        }
    }

    /**
     * Add sortable columns
     *
     * @param array $columns Sortable columns.
     * @return array Modified sortable columns.
     */
    public function add_sortable_columns($columns)
    {
        $columns['schema_type'] = 'schema_type';
        $columns['schema_subtype'] = 'schema_subtype';
        return $columns;
    }
}
