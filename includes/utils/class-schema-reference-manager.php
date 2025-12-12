<?php
/**
 * Schema Reference Manager Class
 *
 * Handles generation of unique IDs for schema entities to support
 * connected graph structure.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schema_Reference_Manager class
 */
class Schema_Reference_Manager
{

    /**
     * Generate a unique ID for a schema entity
     *
     * @param string $type       Schema type (e.g., 'Article', 'Person').
     * @param int    $context_id Optional. Post ID or object ID context.
     * @param string $suffix     Optional. Suffix to ensure uniqueness if multiple of same type.
     * @return string The full URL-based ID (e.g., 'https://site.com/post/#article').
     */
    public static function get_id($type, $context_id = null, $suffix = '')
    {
        // Get base URL from context or current page
        if ($context_id) {
            $url = get_permalink($context_id);
        } else {
            // Fallback to current URL
            global $wp;
            $url = home_url(add_query_arg(array(), $wp->request));
        }

        // Ensure URL ends with a slash before adding hash if it doesn't have one
        // But standard is usually url/#hash
        $url = untrailingslashit($url);

        // Normalize type to lowercase for the hash
        $hash = strtolower($type);

        // Add suffix if provided
        if (!empty($suffix)) {
            $hash .= '-' . $suffix;
        }

        return $url . '/#' . $hash;
    }

    /**
     * Get the WebPage ID for the current context
     * 
     * @param int $context_id Optional. Post ID.
     * @return string
     */
    public static function get_webpage_id($context_id = null)
    {
        return self::get_id('webpage', $context_id);
    }

    /**
     * Get the WebSite ID
     * 
     * @return string
     */
    public static function get_website_id()
    {
        return home_url('/#website');
    }

    /**
     * Get the Organization ID
     * 
     * @return string
     */
    public static function get_organization_id()
    {
        return home_url('/#organization');
    }

    /**
     * Get the Author/Person ID
     * 
     * @param int $user_id User ID.
     * @return string
     */
    public static function get_user_id($user_id)
    {
        $author_url = get_author_posts_url($user_id);
        return untrailingslashit($author_url) . '/#person';
    }
}
