<?php
/**
 * Schema Template CPT Registration
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Swift_Rank_CPT_Registration class
 *
 * Handles the sr_template custom post type registration.
 */
class Swift_Rank_CPT_Registration
{

    /**
     * Instance of this class
     *
     * @var Swift_Rank_CPT_Registration
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return Swift_Rank_CPT_Registration
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
        add_action('init', array($this, 'register_post_type'));
    }

    /**
     * Register sr_template custom post type
     */
    public function register_post_type()
    {
        $labels = array(
            'name' => __('Schema Templates', 'swift-rank'),
            'singular_name' => __('Schema Template', 'swift-rank'),
            'menu_name' => __('Swift Rank', 'swift-rank'),
            'add_new' => __('Add New', 'swift-rank'),
            'add_new_item' => __('Add Schema Template', 'swift-rank'),
            'edit_item' => __('Edit Schema Template', 'swift-rank'),
            'new_item' => __('New Schema Template', 'swift-rank'),
            'view_item' => __('View Schema Template', 'swift-rank'),
            'search_items' => __('Search', 'swift-rank'),
            'not_found' => __('No schema templates found', 'swift-rank'),
            'not_found_in_trash' => __('No schema templates found in trash', 'swift-rank'),
            'all_items' => __('Schema Templates', 'swift-rank'),
            'archives' => __('Schema Template Archives', 'swift-rank'),
            'insert_into_item' => __('Insert into schema template', 'swift-rank'),
            'uploaded_to_this_item' => __('Uploaded to this schema template', 'swift-rank'),
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'swift-rank',
            'query_var' => false,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => null,
            'supports' => array('title'),
            'show_in_rest' => true,
            'rest_base' => 'schema-templates',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );

        register_post_type('sr_template', $args);
    }
}
