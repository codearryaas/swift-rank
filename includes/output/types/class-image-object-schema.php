<?php
/**
 * ImageObject Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_Image_Object class
 *
 * Builds ImageObject schema type with Google Image License metadata.
 */
class Schema_Image_Object implements Schema_Builder_Interface
{

    /**
     * Build schema from fields
     *
     * @param array $fields Field values.
     * @return array Schema array (without @context).
     */
    public function build($fields)
    {
        // Default values
        $content_url_value = !empty($fields['contentUrl']) ? $fields['contentUrl'] : '{featured_image}';

        // Handle if contentUrl is an object/array (from hybrid field)
        $image_data = array();
        if (is_array($content_url_value)) {
            $image_data = $content_url_value;
            $content_url = isset($content_url_value['url']) ? $content_url_value['url'] : '';
        } else {
            $content_url = $content_url_value;
        }

        $name = !empty($fields['name']) ? $fields['name'] : '{post_title}';

        $schema = array(
            '@type' => 'ImageObject',
            'name' => $name,
            'contentUrl' => $content_url,
        );

        // Description/Caption
        // If specific caption field is set, use it. Otherwise try alt text from image data.
        if (!empty($fields['caption'])) {
            $schema['caption'] = $fields['caption'];
        } elseif (!empty($image_data['alt'])) {
            $schema['caption'] = $image_data['alt'];
        }

        // Dimensions
        // Use manual width if set, otherwise try image data width
        if (!empty($fields['width'])) {
            $schema['width'] = $fields['width'];
        } elseif (!empty($image_data['width'])) {
            $schema['width'] = $image_data['width'];
        }

        // Use manual height if set, otherwise try image data height
        if (!empty($fields['height'])) {
            $schema['height'] = $fields['height'];
        } elseif (!empty($image_data['height'])) {
            $schema['height'] = $image_data['height'];
        }

        // License Metadata (Google Header)
        if (!empty($fields['license'])) {
            $schema['license'] = $fields['license'];
        }

        if (!empty($fields['acquireLicensePage'])) {
            $schema['acquireLicensePage'] = $fields['acquireLicensePage'];
        }

        // Creator
        if (!empty($fields['creator'])) {
            // If it's a simple string, we can use it, but Schema.org recommends Person/Organization
            // For simplicity in this implementation, we'll create a Person object if it's a string
            $schema['creator'] = array(
                '@type' => 'Person',
                'name' => $fields['creator'],
            );
        }

        // Credit & Copyright
        if (!empty($fields['creditText'])) {
            $schema['creditText'] = $fields['creditText'];
        }

        if (!empty($fields['copyrightNotice'])) {
            $schema['copyrightNotice'] = $fields['copyrightNotice'];
        }

        // Representative of Page
        if (isset($fields['representativeOfPage'])) {
            // Convert to boolean
            $is_representative = filter_var($fields['representativeOfPage'], FILTER_VALIDATE_BOOLEAN);
            // Only include if true or explicitly set? Spec says Boolean.
            $schema['representativeOfPage'] = $is_representative;
        }

        return $schema;
    }

    /**
     * Get schema.org structure
     *
     * @return array Schema.org structure specification.
     */
    public function get_schema_structure()
    {
        return array(
            '@type' => 'ImageObject',
            '@context' => 'https://schema.org',
            'label' => __('Image Object', 'swift-rank'),
            'description' => __('Image metadata with licensing information for Google Images.', 'swift-rank'),
            'url' => 'https://schema.org/ImageObject',
            'icon' => 'image',
            'showInDropdown' => false, // Hide from template dropdown (created dynamically via references)
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
                'name' => 'contentUrl',
                'label' => __('Image URL', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('The actual image file URL. Click pencil icon to enter custom URL.', 'swift-rank'),
                'placeholder' => '{featured_image}',
                'options' => array(
                    array(
                        'label' => __('Featured Image', 'swift-rank'),
                        'value' => '{featured_image}',
                    ),
                ),
                'default' => '{featured_image}',
                'required' => true,
            ),
            array(
                'name' => 'name',
                'label' => __('Name/Title', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('The name of the image. Click pencil icon to use variables.', 'swift-rank'),
                'placeholder' => '{post_title}',
                'options' => array(
                    array(
                        'label' => __('Post Title', 'swift-rank'),
                        'value' => '{post_title}',
                    ),
                ),
                'default' => '{post_title}',
            ),
            array(
                'name' => 'caption',
                'label' => __('Caption', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('The caption relating to the image. Click pencil icon to use variables.', 'swift-rank'),
                'placeholder' => '{post_excerpt}',
                'options' => array(
                    array(
                        'label' => __('Post Excerpt', 'swift-rank'),
                        'value' => '{post_excerpt}',
                    ),
                ),
                'default' => '',
            ),
            array(
                'name' => 'width',
                'label' => __('Width (px)', 'swift-rank'),
                'type' => 'number',
                'tooltip' => __('The width of the image in pixels.', 'swift-rank'),
                'placeholder' => '1200',
            ),
            array(
                'name' => 'height',
                'label' => __('Height (px)', 'swift-rank'),
                'type' => 'number',
                'tooltip' => __('The height of the image in pixels.', 'swift-rank'),
                'placeholder' => '630',
            ),
            array(
                'name' => 'license',
                'label' => __('License URL', 'swift-rank'),
                'type' => 'url',
                'tooltip' => __('A URL to a page that describes the license governing the image\'s use.', 'swift-rank'),
                'placeholder' => 'https://creativecommons.org/licenses/by/4.0/',
            ),
            array(
                'name' => 'acquireLicensePage',
                'label' => __('Acquire License Page', 'swift-rank'),
                'type' => 'url',
                'tooltip' => __('A URL to a page where the user can find information on how to license the image.', 'swift-rank'),
                'placeholder' => 'https://example.com/how-to-license-image',
            ),
            array(
                'name' => 'creator',
                'label' => __('Creator Name', 'swift-rank'),
                'type' => 'text',
                'tooltip' => __('The name of the creator of the image.', 'swift-rank'),
                'placeholder' => 'Jane Doe',
            ),
            array(
                'name' => 'creditText',
                'label' => __('Credit Text', 'swift-rank'),
                'type' => 'text',
                'tooltip' => __('Text that can be used to credit the image.', 'swift-rank'),
                'placeholder' => 'Jane Doe / Unsplash',
            ),
            array(
                'name' => 'copyrightNotice',
                'label' => __('Copyright Notice', 'swift-rank'),
                'type' => 'text',
                'tooltip' => __('A notice regarding the copyright of the image.', 'swift-rank'),
                'placeholder' => '© 2024 Jane Doe',
            ),
            array(
                'name' => 'representativeOfPage',
                'label' => __('Representative of Page', 'swift-rank'),
                'type' => 'toggle',
                'tooltip' => __('Indicates whether this image is representative of the content of the page.', 'swift-rank'),
                'default' => false,
            ),
        );
    }
}
