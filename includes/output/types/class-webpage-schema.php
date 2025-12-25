<?php
/**
 * WebPage Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_Webpage class
 *
 * Builds WebPage schema type.
 */
class Schema_Webpage implements Schema_Builder_Interface
{

    /**
     * Build WebPage schema from fields
     *
     * @param array $fields Field values.
     * @return array Schema array (without @context).
     */
    public function build($fields)
    {
        $schema = array(
            '@type' => 'WebPage',
        );

        if (!empty($fields['name'])) {
            $schema['name'] = $fields['name'];
        }

        if (!empty($fields['url'])) {
            $schema['url'] = $fields['url'];
        }

        if (!empty($fields['description'])) {
            $schema['description'] = $fields['description'];
        }

        // Image logic
        $image_url = (isset($fields['image']) && !empty($fields['image'])) ? $fields['image'] : '';

        if (empty($image_url) && defined('SWIFT_RANK_PRO_VERSION')) {
            $settings = get_option('swift_rank_settings', array());
            if (!empty($settings['default_image'])) {
                $image_url = $settings['default_image'];
            }
        }

        if (empty($image_url)) {
            $image_url = '{featured_image}';
        }

        if (!empty($image_url)) {
            $schema['image'] = $image_url;
        }

        // Dates
        $date_published = isset($fields['datePublished']) ? $fields['datePublished'] : '{post_date}';
        $date_modified = isset($fields['dateModified']) ? $fields['dateModified'] : '{post_modified}';

        $schema['datePublished'] = $date_published;
        $schema['dateModified'] = $date_modified;

        // Link to WebSite
        $schema['isPartOf'] = array(
            '@type' => 'WebSite',
            '@id' => '{site_url}/#website',
        );

        // Link to Organization (About)
        // Determine KG type from settings
        $settings = get_option('swift_rank_settings', array());
        $kg_type = isset($settings['knowledge_graph_type']) ? $settings['knowledge_graph_type'] : 'Organization';

        $schema['about'] = array(
            '@type' => $kg_type,
            '@id' => '{site_url}/#' . strtolower($kg_type),
        );


        return $schema;
    }

    /**
     * Get schema.org structure for WebPage type
     *
     * @return array Schema.org structure specification.
     */
    public function get_schema_structure()
    {
        return array(
            '@type' => 'WebPage',
            '@context' => 'https://schema.org',
            'label' => __('WebPage', 'swift-rank'),
            'description' => __('A web page. Every web page is implicitly assumed to be declared to be of type WebPage.', 'swift-rank'),
            'url' => 'https://schema.org/WebPage',
            'icon' => 'file',
            'supports_language' => true,
            'subtypes' => array(
                'WebPage' => __('WebPage - General web page', 'swift-rank'),
                'AboutPage' => __('AboutPage - About Us page', 'swift-rank'),
                'ContactPage' => __('ContactPage - Contact Us page', 'swift-rank'),
                'FAQPage' => __('FAQPage - Frequently Asked Questions page', 'swift-rank'),
                'CheckoutPage' => __('CheckoutPage - Checkout page', 'swift-rank'),
                'CollectionPage' => __('CollectionPage - Collection of items', 'swift-rank'),
                'ProfilePage' => __('ProfilePage - User profile page', 'swift-rank'),
                'SearchResultsPage' => __('SearchResultsPage - Search results page', 'swift-rank'),
            ),
        );
    }

    /**
     * Get field definitions for the admin UI
     *
     * @return array Array of field configurations for React components.
     */
    public function get_fields()
    {
        return array(
            array(
                'name' => 'name',
                'label' => __('Name', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Page name or title. Click pencil icon to use variables.', 'swift-rank'),
                'placeholder' => '{post_title}',
                'options' => array(
                    array(
                        'label' => __('Post Title', 'swift-rank'),
                        'value' => '{post_title}',
                    ),
                ),
                'default' => '{post_title}',
                'required' => true,
            ),
            array(
                'name' => 'url',
                'label' => __('URL', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Page URL. Click pencil icon to enter custom URL.', 'swift-rank'),
                'placeholder' => '{post_url}',
                'options' => array(
                    array(
                        'label' => __('Post URL', 'swift-rank'),
                        'value' => '{post_url}',
                    ),
                ),
                'default' => '{post_url}',
                'required' => true,
            ),
            array(
                'name' => 'description',
                'label' => __('Description', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'customType' => 'textarea',
                'rows' => 4,
                'tooltip' => __('Page description. Click pencil icon to use variables.', 'swift-rank'),
                'placeholder' => '{post_excerpt}',
                'options' => array(
                    array(
                        'label' => __('Post Excerpt', 'swift-rank'),
                        'value' => '{post_excerpt}',
                    ),
                    array(
                        'label' => __('Post Content', 'swift-rank'),
                        'value' => '{post_content}',
                    ),
                ),
                'default' => '{post_excerpt}',
            ),
            array(
                'name' => 'image',
                'label' => __('Image', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'customType' => 'image',
                'returnObject' => true,
                'tooltip' => __('Page image. Select from list or click pencil to upload custom image.', 'swift-rank'),
                'placeholder' => '{featured_image}',
                'options' => array(
                    array(
                        'label' => __('Featured Image', 'swift-rank'),
                        'value' => '{featured_image}',
                    ),
                ),
                'default' => '{featured_image}',
            ),
            array(
                'name' => 'datePublished',
                'label' => __('Date Published', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Publication date. Click pencil icon to use variables.', 'swift-rank'),
                'placeholder' => '{post_date}',
                'options' => array(
                    array(
                        'label' => __('Post Date', 'swift-rank'),
                        'value' => '{post_date}',
                    ),
                ),
                'default' => '{post_date}',
            ),
            array(
                'name' => 'dateModified',
                'label' => __('Date Modified', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Last modified date. Click pencil icon to use variables.', 'swift-rank'),
                'placeholder' => '{post_modified}',
                'options' => array(
                    array(
                        'label' => __('Post Modified Date', 'swift-rank'),
                        'value' => '{post_modified}',
                    ),
                ),
                'default' => '{post_modified}',
            ),
        );
    }

}
