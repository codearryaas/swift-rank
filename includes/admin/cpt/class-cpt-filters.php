<?php
/**
 * Schema Template CPT Filters Handler
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Swift_Rank_CPT_Filters class
 *
 * Handles filtering and sorting for sr_template post type listing.
 */
class Swift_Rank_CPT_Filters
{

    /**
     * Instance of this class
     *
     * @var Swift_Rank_CPT_Filters
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return Swift_Rank_CPT_Filters
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
        add_action('restrict_manage_posts', array($this, 'add_schema_type_filter'));
        add_action('pre_get_posts', array($this, 'filter_by_schema_type'));
    }

    /**
     * Add schema type and subtype filter dropdowns
     */
    public function add_schema_type_filter()
    {
        global $typenow;

        if ('sr_template' === $typenow) {
            // Type filter.
            $schema_types = Schema_Type_Helper::get_types_for_select();
            $current_type = isset($_GET['schema_type_filter']) ? sanitize_text_field(wp_unslash($_GET['schema_type_filter'])) : '';

            echo '<select name="schema_type_filter" id="schema_type_filter">';
            echo '<option value="">' . esc_html__('All Types', 'swift-rank') . '</option>';

            foreach ($schema_types as $type) {
                $selected = selected($current_type, $type['value'], false);
                // Remove " (Pro)" from label.
                $type_label = str_replace(' (Pro)', '', $type['label']);
                echo '<option value="' . esc_attr($type['value']) . '" ' . $selected . '>';
                echo esc_html($type_label);
                echo '</option>';
            }
            echo '</select>';

            // Subtype filter.
            $current_subtype = isset($_GET['schema_subtype_filter']) ? sanitize_text_field(wp_unslash($_GET['schema_subtype_filter'])) : '';

            echo '<select name="schema_subtype_filter" id="schema_subtype_filter">';
            echo '<option value="">' . esc_html__('All Sub-Types', 'swift-rank') . '</option>';

            // Get subtypes dynamically.
            $subtypes = Schema_Type_Helper::get_subtypes_for_filter();

            foreach ($subtypes as $value => $label) {
                $selected = selected($current_subtype, $value, false);
                echo '<option value="' . esc_attr($value) . '" ' . $selected . '>';
                echo esc_html($label);
                echo '</option>';
            }
            echo '</select>';
        }
    }

    /**
     * Filter templates by schema type and subtype
     *
     * @param WP_Query $query The query object.
     */
    public function filter_by_schema_type($query)
    {
        // Only apply on admin edit screen for sr_template post type, and only for main query.
        if (!is_admin() || !$query->is_main_query() || !isset($query->query['post_type']) || 'sr_template' !== $query->query['post_type']) {
            return;
        }

        $meta_query = array();

        // Handle filtering by schema type.
        if (isset($_GET['schema_type_filter']) && '' !== $_GET['schema_type_filter']) {
            $schema_type = sanitize_text_field(wp_unslash($_GET['schema_type_filter']));
            $meta_query[] = array(
                'key' => '_schema_type',
                'value' => $schema_type,
                'compare' => '=',
            );
        }

        // Handle filtering by schema subtype.
        if (isset($_GET['schema_subtype_filter']) && '' !== $_GET['schema_subtype_filter']) {
            $schema_subtype = sanitize_text_field(wp_unslash($_GET['schema_subtype_filter']));
            $meta_query[] = array(
                'key' => '_schema_subtype',
                'value' => $schema_subtype,
                'compare' => '=',
            );
        }

        if (!empty($meta_query)) {
            $meta_query['relation'] = 'AND';
            $query->set('meta_query', $meta_query);
        }

        // Handle sorting by schema type.
        if (isset($_GET['orderby']) && 'schema_type' === $_GET['orderby']) {
            $query->set('meta_key', '_schema_type');
            $query->set('orderby', 'meta_value');
        }

        // Handle sorting by schema subtype.
        if (isset($_GET['orderby']) && 'schema_subtype' === $_GET['orderby']) {
            $query->set('meta_key', '_schema_subtype');
            $query->set('orderby', 'meta_value');
        }
    }
}
