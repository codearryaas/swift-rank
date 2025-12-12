<?php
/**
 * Breadcrumb Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schema_Breadcrumb class
 *
 * Builds BreadcrumbList schema type.
 */
class Schema_Breadcrumb implements Schema_Builder_Interface
{

    /**
     * Build breadcrumb schema
     *
     * @param array $settings Plugin settings.
     * @return array Schema array (with @context).
     */
    public function build($settings)
    {
        $breadcrumb_show_home = isset($settings['breadcrumb_show_home']) ? $settings['breadcrumb_show_home'] : true;
        $breadcrumb_home_text = isset($settings['breadcrumb_home_text']) ? $settings['breadcrumb_home_text'] : __('Home', 'swift-rank');

        $items = array();
        $position = 1;

        // Add home
        if ($breadcrumb_show_home) {
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $breadcrumb_home_text,
                'item' => get_home_url(),
            );
            $position++;
        }

        // Handle different page types
        if (is_singular()) {
            // Singular posts, pages, custom post types
            $items = $this->add_singular_breadcrumbs($items, $position);
        } elseif (is_category()) {
            // Category archive
            $category = get_queried_object();
            if ($category) {
                $items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $category->name,
                    'item' => get_category_link($category->term_id),
                );
            }
        } elseif (is_tag()) {
            // Tag archive
            $tag = get_queried_object();
            if ($tag) {
                $items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $tag->name,
                    'item' => get_tag_link($tag->term_id),
                );
            }
        } elseif (is_tax()) {
            // Custom taxonomy archive
            $term = get_queried_object();
            if ($term) {
                $items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $term->name,
                    'item' => get_term_link($term),
                );
            }
        } elseif (is_author()) {
            // Author archive
            $author = get_queried_object();
            if ($author) {
                $items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $author->display_name,
                    'item' => get_author_posts_url($author->ID),
                );
            }
        } elseif (is_date()) {
            // Date archive
            $items = $this->add_date_breadcrumbs($items, $position);
        } elseif (is_search()) {
            // Search results
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => sprintf(__('Search Results for "%s"', 'swift-rank'), get_search_query()),
                'item' => get_search_link(),
            );
        } elseif (is_post_type_archive()) {
            // Custom post type archive
            $post_type = get_query_var('post_type');
            if (is_array($post_type)) {
                $post_type = reset($post_type);
            }
            $post_type_obj = get_post_type_object($post_type);
            if ($post_type_obj) {
                $items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $post_type_obj->labels->name,
                    'item' => get_post_type_archive_link($post_type),
                );
            }
        }

        // Must have at least 2 items for valid breadcrumb
        if (count($items) < 2) {
            return array();
        }

        return array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        );
    }

    /**
     * Add breadcrumbs for singular pages
     *
     * @param array $items    Existing breadcrumb items.
     * @param int   $position Current position.
     * @return array Updated breadcrumb items.
     */
    private function add_singular_breadcrumbs($items, $position)
    {
        global $post;

        if (!$post) {
            return $items;
        }

        // Add parent pages for hierarchical post types
        if (is_post_type_hierarchical(get_post_type($post))) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);

            foreach ($ancestors as $ancestor_id) {
                $ancestor = get_post($ancestor_id);
                if ($ancestor) {
                    $items[] = array(
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => get_the_title($ancestor),
                        'item' => get_permalink($ancestor),
                    );
                    $position++;
                }
            }
        }

        // Add categories for posts
        if (is_singular('post')) {
            $categories = get_the_category($post->ID);
            if (!empty($categories)) {
                // Use the first category
                $category = $categories[0];
                $items[] = array(
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $category->name,
                    'item' => get_category_link($category->term_id),
                );
                $position++;
            }
        }

        // Add current page
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title($post),
            'item' => get_permalink($post),
        );

        return $items;
    }

    /**
     * Add breadcrumbs for date archives
     *
     * @param array $items    Existing breadcrumb items.
     * @param int   $position Current position.
     * @return array Updated breadcrumb items.
     */
    private function add_date_breadcrumbs($items, $position)
    {
        $year = get_query_var('year');
        $month = get_query_var('monthnum');
        $day = get_query_var('day');

        // Add year
        if ($year) {
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $year,
                'item' => get_year_link($year),
            );
            $position++;
        }

        // Add month
        if ($month) {
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => date_i18n('F', mktime(0, 0, 0, $month, 1)),
                'item' => get_month_link($year, $month),
            );
            $position++;
        }

        // Add day
        if ($day) {
            $items[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $day,
                'item' => get_day_link($year, $month, $day),
            );
        }

        return $items;
    }

    /**
     * Get schema.org structure for BreadcrumbList type
     *
     * @return array Schema.org structure specification.
     */
    public function get_schema_structure()
    {
        return array(
            '@type' => 'BreadcrumbList',
            '@context' => 'https://schema.org',
            'label' => __('Breadcrumb', 'swift-rank'),
            'description' => __('A list of pages that lead to the current page.', 'swift-rank'),
            'url' => 'https://schema.org/BreadcrumbList',
            'icon' => 'arrow-right',
            'showInDropdown' => false, // Hide from template dropdown (managed via settings)
        );
    }

    /**
     * Get field definitions for the admin UI
     *
     * Note: Breadcrumbs are automatically generated from the page hierarchy,
     * so there are no user-editable fields for this schema type.
     *
     * @return array Empty array - no user-editable fields.
     */
    public function get_fields()
    {
        return array();
    }

}
