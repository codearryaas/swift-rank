<?php
/**
 * Schema Template Loader Class
 *
 * Handles logic for finding matching schema templates for posts.
 * This is separated from the Metabox class to allow global usage without loading admin UI code.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schema_Template_Loader class
 */
class Schema_Template_Loader
{

    /**
     * Instance of this class
     *
     * @var Schema_Template_Loader
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return Schema_Template_Loader
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get templates that match the current post
     *
     * @param int $post_id Post ID.
     * @return array
     */
    public function get_templates_for_post($post_id)
    {
        $post = get_post($post_id);
        if (!$post) {
            return array();
        }

        $post_type = $post->post_type;
        $templates = get_posts(
            array(
                'post_type' => 'sr_template',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            )
        );

        $matching = array();
        foreach ($templates as $template) {
            $schema_data = get_post_meta($template->ID, '_schema_template_data', true);
            if (empty($schema_data) || empty($schema_data['schemaType'])) {
                continue;
            }

            // Check if template should be applied.
            if ($this->matches_post($schema_data, $post_id, $post_type)) {
                $matching[] = array(
                    'id' => $template->ID,
                    'title' => $template->post_title,
                    'schemaType' => isset($schema_data['schemaType']) ? $schema_data['schemaType'] : '',
                    'fields' => isset($schema_data['fields']) ? $schema_data['fields'] : array(),
                );
            }
        }

        return $matching;
    }

    /**
     * Check if a template matches the current post
     *
     * @param array  $schema_data Template schema data.
     * @param int    $post_id     Post ID.
     * @param string $post_type   Post type.
     * @return bool
     */
    public function matches_post($schema_data, $post_id, $post_type)
    {
        $include_conditions = isset($schema_data['includeConditions']) ? $schema_data['includeConditions'] : array();

        // Debugging
        // error_log("Checking Template: " . print_r($schema_data['schemaType'], true) . " for Post $post_id ($post_type)");
        // error_log("Conditions: " . print_r($include_conditions, true));

        // If no conditions are set, don't display.
        if (empty($include_conditions)) {
            return false;
        }

        // Check if at least one group has rules.
        if (!Swift_Rank_Conditions::has_rules($include_conditions)) {
            return false;
        }

        // Check if conditions match current post.
        return Swift_Rank_Conditions::matches_conditions($include_conditions, $post_id, $post_type);
    }
}
