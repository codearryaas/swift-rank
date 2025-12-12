<?php
/**
 * Schema Variable Replacer Class
 *
 * Handles replacement of template variables in schema JSON.
 * Can be extended by Pro plugin to add additional variables.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schema_Variable_Replacer class
 *
 * Handles both registration and replacement of template variables.
 */
class Schema_Variable_Replacer
{

    /**
     * Registered variable groups
     *
     * @var array
     */
    protected $variable_groups = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->register_variable_groups();
    }

    /**
     * Register variable groups
     *
     * Override this method in child classes to add more variables.
     */
    protected function register_variable_groups()
    {
        // Site variables
        $this->register_group('site', array(
            'label' => __('Site', 'swift-rank'),
            'icon' => 'globe',
            'variables' => array(
                array(
                    'value' => '{site_name}',
                    'label' => __('Site Name', 'swift-rank'),
                    'description' => __('Your website name from Settings', 'swift-rank'),
                ),
                array(
                    'value' => '{site_url}',
                    'label' => __('Site URL', 'swift-rank'),
                    'description' => __('Your website homepage URL', 'swift-rank'),
                ),
                array(
                    'value' => '{site_description}',
                    'label' => __('Site Tagline', 'swift-rank'),
                    'description' => __('Site tagline from Settings', 'swift-rank'),
                ),
                array(
                    'value' => '{site_logo}',
                    'label' => __('Site Logo', 'swift-rank'),
                    'description' => __('Custom logo URL if set', 'swift-rank'),
                ),
                array(
                    'value' => '{current_url}',
                    'label' => __('Current URL', 'swift-rank'),
                    'description' => __('The current page URL', 'swift-rank'),
                ),
            ),
        ));

        // Post/Content variables
        $this->register_group('post', array(
            'label' => __('Content', 'swift-rank'),
            'icon' => 'file-text',
            'variables' => array(
                array(
                    'value' => '{post_title}',
                    'label' => __('Title', 'swift-rank'),
                    'description' => __('The post/page title', 'swift-rank'),
                ),
                array(
                    'value' => '{post_excerpt}',
                    'label' => __('Excerpt', 'swift-rank'),
                    'description' => __('Post excerpt or auto-generated summary', 'swift-rank'),
                ),
                array(
                    'value' => '{post_content}',
                    'label' => __('Content', 'swift-rank'),
                    'description' => __('Full post content (stripped of HTML)', 'swift-rank'),
                ),
                array(
                    'value' => '{post_url}',
                    'label' => __('Permalink', 'swift-rank'),
                    'description' => __('The permanent URL of this content', 'swift-rank'),
                ),
                array(
                    'value' => '{featured_image}',
                    'label' => __('Featured Image', 'swift-rank'),
                    'description' => __('Featured image URL', 'swift-rank'),
                ),
                array(
                    'value' => '{post_date}',
                    'label' => __('Publish Date', 'swift-rank'),
                    'description' => __('Date when content was published', 'swift-rank'),
                ),
                array(
                    'value' => '{post_modified}',
                    'label' => __('Modified Date', 'swift-rank'),
                    'description' => __('Date when content was last updated', 'swift-rank'),
                ),
            ),
        ));

        // Author variables
        $this->register_group('author', array(
            'label' => __('Author', 'swift-rank'),
            'icon' => 'users',
            'variables' => array(
                array(
                    'value' => '{author_name}',
                    'label' => __('Author Name', 'swift-rank'),
                    'description' => __('Display name of the post author', 'swift-rank'),
                ),
                array(
                    'value' => '{author_url}',
                    'label' => __('Author URL', 'swift-rank'),
                    'description' => __('Author archive page URL', 'swift-rank'),
                ),
                array(
                    'value' => '{author_bio}',
                    'label' => __('Author Bio', 'swift-rank'),
                    'description' => __('Author biographical info', 'swift-rank'),
                ),
                array(
                    'value' => '{author_email}',
                    'label' => __('Author Email', 'swift-rank'),
                    'description' => __('Author email address', 'swift-rank'),
                ),
                array(
                    'value' => '{author_avatar}',
                    'label' => __('Author Avatar', 'swift-rank'),
                    'description' => __('Author profile image URL', 'swift-rank'),
                ),
            ),
        ));

        // WooCommerce variables (if WooCommerce is active)
        if (class_exists('WooCommerce')) {
            $this->register_group('woocommerce', array(
                'label' => __('WooCommerce', 'swift-rank'),
                'icon' => 'cart',
                'variables' => array(
                    array(
                        'value' => '{woo_product_title}',
                        'label' => __('Product Title', 'swift-rank'),
                        'description' => __('Product title', 'swift-rank'),
                    ),
                    array(
                        'value' => '{woo_product_price}',
                        'label' => __('Product Price', 'swift-rank'),
                        'description' => __('Regular product price', 'swift-rank'),
                    ),
                    array(
                        'value' => '{woo_product_sale_price}',
                        'label' => __('Sale Price', 'swift-rank'),
                        'description' => __('Product sale price (if on sale)', 'swift-rank'),
                    ),
                    array(
                        'value' => '{woo_product_currency}',
                        'label' => __('Currency', 'swift-rank'),
                        'description' => __('Store currency code (e.g., USD)', 'swift-rank'),
                    ),
                    array(
                        'value' => '{woo_product_sku}',
                        'label' => __('Product SKU', 'swift-rank'),
                        'description' => __('Product stock keeping unit', 'swift-rank'),
                    ),
                    array(
                        'value' => '{woo_product_stock_status}',
                        'label' => __('Stock Status', 'swift-rank'),
                        'description' => __('InStock, OutOfStock, or PreOrder', 'swift-rank'),
                    ),
                    array(
                        'value' => '{woo_product_brand}',
                        'label' => __('Product Brand', 'swift-rank'),
                        'description' => __('Product brand (if set)', 'swift-rank'),
                    ),
                ),
            ));
        }
    }

    /**
     * Register a variable group
     *
     * @param string $key   Group key.
     * @param array  $group Group data (label, icon, variables).
     */
    protected function register_group($key, $group)
    {
        $this->variable_groups[$key] = $group;
    }

    /**
     * Get all registered variable groups
     *
     * @return array
     */
    public function get_variable_groups()
    {
        return apply_filters('swift_rank_variable_groups', $this->variable_groups);
    }

    /**
     * Replace template variables in schema JSON
     *
     * @param string $json JSON string.
     * @return string
     */
    public function replace_variables($json)
    {
        // Get all replacements
        $replacements = $this->get_replacements();

        // Escape replacement values for JSON to prevent control character errors
        $escaped_replacements = array();
        foreach ($replacements as $key => $value) {
            // JSON encode the value to escape it, then remove the surrounding quotes
            $encoded = wp_json_encode($value);
            // Remove the quotes that wp_json_encode adds (we want the escaped string, not a JSON string)
            $escaped_replacements[$key] = trim($encoded, '"');
        }

        // Replace basic variables
        $json = str_replace(array_keys($escaped_replacements), array_values($escaped_replacements), $json);

        // Replace dynamic variables (option, meta, etc.)
        $json = $this->replace_dynamic_variables($json);

        // Allow Pro plugin to add additional replacements
        $json = apply_filters('swift_rank_replace_variables', $json);

        return $json;
    }

    /**
     * Get all variable replacements
     *
     * @return array Array of variable => value pairs.
     */
    protected function get_replacements()
    {
        global $post;

        $replacements = array();

        // Site variables
        $replacements = array_merge($replacements, $this->get_site_replacements());

        // Post variables (if in the loop)
        if ($post) {
            $replacements = array_merge($replacements, $this->get_post_replacements($post));
        }

        // Allow filtering of replacements
        $replacements = apply_filters('swift_rank_variable_replacements', $replacements, $post);

        return $replacements;
    }

    /**
     * Get site-level variable replacements
     *
     * @return array
     */
    protected function get_site_replacements()
    {
        // Get site logo with fallback chain: custom_logo -> site_icon -> default_image
        $site_logo = '';
        if (has_custom_logo()) {
            $site_logo = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full');
        }

        if (empty($site_logo)) {
            $site_logo = get_site_icon_url();
        }

        // If still empty and Pro is active, use default_image
        if (empty($site_logo) && defined('SWIFT_RANK_PRO_VERSION')) {
            $settings = get_option('swift_rank_settings', array());
            $default_image = isset($settings['default_image']) ? $settings['default_image'] : '';

            // Handle array/object (from new storage) or string (legacy)
            if (is_array($default_image) && isset($default_image['url'])) {
                $site_logo = $default_image['url'];
            } elseif (is_string($default_image) && !empty($default_image)) {
                $site_logo = $default_image;
            }
        }

        return array(
            '{site_name}' => get_bloginfo('name'),
            '{site_url}' => get_home_url(),
            '{site_description}' => get_bloginfo('description'),
            '{site_logo}' => $site_logo,
            '{current_url}' => (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        );
    }

    /**
     * Get post-level variable replacements
     *
     * @param WP_Post $post Post object.
     * @return array
     */
    protected function get_post_replacements($post)
    {
        $replacements = array(
            '{post_title}' => get_the_title($post),
            '{post_excerpt}' => get_the_excerpt($post),
            '{post_content}' => wp_strip_all_tags(get_the_content(null, false, $post)),
            '{post_url}' => get_permalink($post),
            '{post_date}' => get_the_date('c', $post),
            '{post_modified}' => get_the_modified_date('c', $post),
            '{author_name}' => get_the_author_meta('display_name', $post->post_author),
            '{author_url}' => get_author_posts_url($post->post_author),
            '{author_bio}' => get_the_author_meta('description', $post->post_author),
            '{author_email}' => get_the_author_meta('email', $post->post_author),
            '{author_avatar}' => get_avatar_url($post->post_author),
        );

        // Featured image with fallback
        $replacements['{featured_image}'] = $this->get_featured_image_replacement($post);

        // WooCommerce variables (if WooCommerce is active and this is a product)
        if (class_exists('WooCommerce') && function_exists('wc_get_product')) {
            $product = wc_get_product($post->ID);

            if ($product) {
                // Product title
                $replacements['{woo_product_title}'] = $product->get_name();

                // Price variables
                $replacements['{woo_product_price}'] = $product->get_regular_price();
                $replacements['{woo_product_sale_price}'] = $product->get_sale_price() ? $product->get_sale_price() : '';
                $replacements['{woo_product_currency}'] = get_woocommerce_currency();

                // SKU
                $replacements['{woo_product_sku}'] = $product->get_sku() ? $product->get_sku() : '';

                // Stock status - map to Schema.org format
                $stock_status = $product->get_stock_status();
                $stock_status_map = array(
                    'instock' => 'InStock',
                    'outofstock' => 'OutOfStock',
                    'onbackorder' => 'PreOrder',
                );
                $replacements['{woo_product_stock_status}'] = isset($stock_status_map[$stock_status]) ? $stock_status_map[$stock_status] : $stock_status;

                // Product Brand (common meta keys)
                $brand = get_post_meta($post->ID, '_product_brand', true);
                if (empty($brand)) {
                    $brand = get_post_meta($post->ID, 'brand', true);
                }
                if (empty($brand)) {
                    // Check for brand taxonomy (common in WooCommerce extensions)
                    $brand_terms = get_the_terms($post->ID, 'product_brand');
                    if (!empty($brand_terms) && !is_wp_error($brand_terms)) {
                        $brand = $brand_terms[0]->name;
                    }
                }
                $replacements['{woo_product_brand}'] = $brand ? $brand : '';
            } else {
                // Not a product - return empty values
                $replacements['{woo_product_title}'] = '';
                $replacements['{woo_product_price}'] = '';
                $replacements['{woo_product_sale_price}'] = '';
                $replacements['{woo_product_currency}'] = '';
                $replacements['{woo_product_sku}'] = '';
                $replacements['{woo_product_stock_status}'] = '';
                $replacements['{woo_product_brand}'] = '';
            }
        }

        return $replacements;
    }

    /**
     * Get featured image replacement value
     *
     * @param WP_Post $post Post object.
     * @return string
     */
    protected function get_featured_image_replacement($post)
    {
        if (has_post_thumbnail($post)) {
            return get_the_post_thumbnail_url($post, 'full');
        }

        // Use default image from settings if available (Pro only)
        if (defined('SWIFT_RANK_PRO_VERSION')) {
            $settings = get_option('swift_rank_settings', array());
            $default_image = isset($settings['default_image']) ? $settings['default_image'] : '';

            // Handle array/object (from new storage) or string (legacy)
            $fallback_image = '';
            if (is_array($default_image) && isset($default_image['url'])) {
                $fallback_image = $default_image['url'];
            } elseif (is_string($default_image) && !empty($default_image)) {
                $fallback_image = $default_image;
            }

            // Check if fallback is a variable (e.g. {site_logo})
            if (!empty($fallback_image) && strpos($fallback_image, '{') !== false) {
                // Simple recursive check for site variables
                $site_replacements = $this->get_site_replacements();
                if (isset($site_replacements[$fallback_image])) {
                    return $site_replacements[$fallback_image];
                }
            }

            return $fallback_image;
        }

        // No featured image and no default - return empty string
        return '';
    }

    /**
     * Replace dynamic variables (option, meta, etc.)
     *
     * @param string $json JSON string.
     * @return string
     */
    protected function replace_dynamic_variables($json)
    {
        global $post;

        // Replace {option:option_name} variables
        $json = preg_replace_callback(
            '/\{option:([a-zA-Z0-9_-]+)\}/',
            function ($matches) {
                $option_name = $matches[1];
                $option_value = get_option($option_name);
                if ($option_value) {
                    // Escape for JSON to prevent control character errors
                    $encoded = wp_json_encode($option_value);
                    return trim($encoded, '"');
                }
                return $matches[0];
            },
            $json
        );

        // Replace {meta:meta_key} variables
        if ($post) {
            $json = preg_replace_callback(
                '/\{meta:([a-zA-Z0-9_-]+)\}/',
                function ($matches) use ($post) {
                    $meta_key = $matches[1];
                    $meta_value = get_post_meta($post->ID, $meta_key, true);
                    if ($meta_value) {
                        // Escape for JSON to prevent control character errors
                        $encoded = wp_json_encode($meta_value);
                        return trim($encoded, '"');
                    }
                    return $matches[0];
                },
                $json
            );
        }

        // Allow Pro plugin to add more dynamic variable patterns
        $json = apply_filters('swift_rank_replace_dynamic_variables', $json, $post);

        return $json;
    }
}
