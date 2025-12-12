<?php
/**
 * Auto Schema Generator
 *
 * Generates default schema markup for common page types based on settings.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schema_Auto_Generator class
 *
 * Generates automatic schema for posts, pages, search, and WooCommerce products.
 */
class Schema_Auto_Generator
{
    /**
     * Generate Article schema for posts
     *
     * @param string $article_type Article type (Article, BlogPosting, NewsArticle, etc.).
     * @return array Schema fields.
     */
    public static function generate_post_schema($article_type = 'Article')
    {
        return array(
            'articleType' => $article_type,
            'headline' => '{post_title}',
            'url' => '{post_url}',
            'description' => '{post_excerpt}',
            'imageUrl' => '{featured_image}',
            'authorName' => '{author_name}',
            'authorUrl' => '{author_url}',
            'publisherName' => '{site_name}',
            'publisherLogo' => '{site_logo}',
            'datePublished' => '{post_date}',
            'dateModified' => '{post_modified}',
        );
    }

    /**
     * Generate WebPage schema for pages
     *
     * @return array Schema fields.
     */
    public static function generate_page_schema()
    {
        return array(
            'name' => '{post_title}',
            'url' => '{post_url}',
            'description' => '{post_excerpt}',
            'image' => '{featured_image}',
            'datePublished' => '{post_date}',
            'dateModified' => '{post_modified}',
        );
    }

    /**
     * Generate SearchResultsPage schema for search pages
     *
     * @return array Schema fields.
     */
    public static function generate_search_schema()
    {
        return array(
            'name' => 'Search Results',
            'url' => '{current_url}',
        );
    }

    /**
     * Generate Product schema for WooCommerce products
     *
     * @return array Schema fields.
     */
    public static function generate_woocommerce_schema()
    {
        return array(
            'name' => '{post_title}',
            'url' => '{post_url}',
            'description' => '{post_excerpt}',
            'image' => '{featured_image}',
            'sku' => '{woo_product_sku}',
            'brand' => '{woo_product_brand}',
            'price' => '{woo_product_price}',
            'priceCurrency' => '{woo_product_currency}',
            'availability' => '{woo_product_stock_status}',
        );
    }

    /**
     * Check if auto-schema should be generated for current page
     *
     * @param array $settings Plugin settings.
     * @return array|false Schema data if should generate, false otherwise.
     */
    public static function should_generate_auto_schema($settings)
    {
        // Check for posts
        if (is_singular('post')) {
            // Default to true if not set (matches field default)
            $post_enabled = isset($settings['auto_schema_post_enabled']) ? $settings['auto_schema_post_enabled'] : true;
            // Convert to boolean
            $post_enabled = filter_var($post_enabled, FILTER_VALIDATE_BOOLEAN);

            if ($post_enabled) {
                $article_type = isset($settings['auto_schema_post_type']) ? $settings['auto_schema_post_type'] : 'Article';
                return array(
                    'schemaType' => $article_type,
                    'fields' => self::generate_post_schema($article_type),
                );
            }
        }

        // Check for pages
        if (is_page()) {
            // Default to true if not set (matches field default)
            $page_enabled = isset($settings['auto_schema_page_enabled']) ? $settings['auto_schema_page_enabled'] : true;
            $page_enabled = filter_var($page_enabled, FILTER_VALIDATE_BOOLEAN);

            if ($page_enabled) {
                return array(
                    'schemaType' => 'WebPage',
                    'fields' => self::generate_page_schema(),
                );
            }
        }

        // Check for search pages
        if (is_search()) {
            // Default to true if not set (matches field default)
            $search_enabled = isset($settings['auto_schema_search_enabled']) ? $settings['auto_schema_search_enabled'] : true;
            $search_enabled = filter_var($search_enabled, FILTER_VALIDATE_BOOLEAN);

            if ($search_enabled) {
                return array(
                    'schemaType' => 'SearchResultsPage',
                    'fields' => self::generate_search_schema(),
                );
            }
        }

        // Check for WooCommerce products
        if (class_exists('WooCommerce') && is_singular('product')) {
            // Default to true if not set (matches field default)
            $woo_enabled = isset($settings['auto_schema_woocommerce_enabled']) ? $settings['auto_schema_woocommerce_enabled'] : true;
            $woo_enabled = filter_var($woo_enabled, FILTER_VALIDATE_BOOLEAN);

            if ($woo_enabled) {
                return array(
                    'schemaType' => 'Product',
                    'fields' => self::generate_woocommerce_schema(),
                );
            }
        }

        return false;
    }
}
