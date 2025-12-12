<?php
/**
 * WebSite Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schema_Website class
 *
 * Builds WebSite schema type.
 */
class Schema_Website implements Schema_Builder_Interface
{

    /**
     * Build website schema
     *
     * @param array $fields Settings/Fields array.
     * @return array Schema array (with @context).
     */
    public function build($fields = array())
    {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => get_home_url(),
        );

        // Check for Sitelinks Searchbox setting
        // If $fields is empty, try fetching options (fallback)
        if (empty($fields)) {
            $settings = get_option('swift_rank_settings', array());
            $sitelinks_searchbox = isset($settings['sitelinks_searchbox']) ? $settings['sitelinks_searchbox'] : false;
        } else {
            $sitelinks_searchbox = isset($fields['sitelinks_searchbox']) ? $fields['sitelinks_searchbox'] : false;
        }

        // Sitelinks Searchbox is a Pro feature - only output if Pro is active
        $is_pro_active = defined('SWIFT_RANK_PRO_VERSION');

        if ($sitelinks_searchbox && $is_pro_active) {
            // Add SearchAction for sitelinks searchbox
            $schema['potentialAction'] = array(
                '@type' => 'SearchAction',
                'target' => array(
                    '@type' => 'EntryPoint',
                    'urlTemplate' => get_home_url() . '/?s={search_term_string}',
                ),
                'query-input' => 'required name=search_term_string',
            );
        }

        return $schema;
    }

    /**
     * Get schema.org structure for WebSite type
     *
     * @return array Schema.org structure specification.
     */
    public function get_schema_structure()
    {
        return array(
            '@type' => 'WebSite',
            '@context' => 'https://schema.org',
            'label' => __('WebSite', 'swift-rank'),
            'description' => __('A WebSite is a set of related web pages and other items typically served from a single web domain.', 'swift-rank'),
            'url' => 'https://schema.org/WebSite',
            'icon' => 'globe',
            'showInDropdown' => false, // Hide from template dropdown (managed via settings)
        );
    }

    /**
     * Get field definitions for the admin UI
     *
     * @return array Empty array - no user-editable fields.
     */
    public function get_fields()
    {
        return array();
    }

}
