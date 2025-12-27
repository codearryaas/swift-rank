<?php
/**
 * Image Schema Output Handler
 *
 * Centralized handler for generating ImageObject schemas consistently
 * from attachment IDs, URLs, or variables.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Image_Schema_Output_Handler class
 *
 * Singleton class that provides centralized ImageObject schema generation.
 */
class Image_Schema_Output_Handler
{
    /**
     * Instance of this class.
     *
     * @var Image_Schema_Output_Handler
     */
    private static $instance = null;

    /**
     * Schema_Image_Object builder instance
     *
     * @var Schema_Image_Object
     */
    private $image_builder = null;

    /**
     * Get singleton instance.
     *
     * @return Image_Schema_Output_Handler
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
        // Load Schema_Image_Object if not already loaded
        if (!class_exists('Schema_Image_Object')) {
            require_once dirname(__FILE__) . '/types/class-image-object-schema.php';
        }
        $this->image_builder = new Schema_Image_Object();
    }

    /**
     * Generate ImageObject schema from various input types
     *
     * @param mixed $input Attachment ID (int), URL (string), or variable (string like {featured_image}).
     * @param array $context Optional context: post_id, index, type (featured|content), image_data.
     * @return array|null ImageObject schema array with @id, or null on failure.
     */
    public function generate_image_object($input, $context = array())
    {
        $attachment_id = 0;
        $image_url = '';
        $image_data = isset($context['image_data']) ? $context['image_data'] : array();

        // Resolve input to attachment ID and/or URL
        if (is_numeric($input)) {
            // Input is attachment ID
            $attachment_id = intval($input);
            $image_url = wp_get_attachment_url($attachment_id);
        } elseif (is_string($input)) {
            // Input is URL or variable
            if (filter_var($input, FILTER_VALIDATE_URL)) {
                // Direct URL
                $image_url = $input;
                $attachment_id = attachment_url_to_postid($image_url);
            } else {
                // Variable - will be resolved later by variable replacer
                $image_url = $input;
            }
        }

        // If we have attachment ID, get metadata
        if ($attachment_id && empty($image_data)) {
            $image_data = $this->get_image_metadata($attachment_id);
        }

        // Build fields array for Schema_Image_Object
        $fields = array(
            'contentUrl' => $image_url,
        );

        // Add metadata if available
        if (!empty($image_data['name'])) {
            $fields['name'] = $image_data['name'];
        }
        if (!empty($image_data['alt'])) {
            $fields['caption'] = $image_data['alt'];
        }
        if (!empty($image_data['width'])) {
            $fields['width'] = $image_data['width'];
        }
        if (!empty($image_data['height'])) {
            $fields['height'] = $image_data['height'];
        }

        // Generate ImageObject using Schema_Image_Object builder
        $image_object = $this->image_builder->build($fields);

        if (empty($image_object)) {
            return null;
        }

        // Generate and add @id
        $id = $this->generate_id($attachment_id, $image_url, $context);
        if ($id) {
            $image_object['@id'] = $id;
        }

        return $image_object;
    }

    /**
     * Generate @id for ImageObject
     *
     * @param int    $attachment_id Attachment ID (0 if not available).
     * @param string $image_url Image URL.
     * @param array  $context Context array with type, post_id, index.
     * @return string Generated @id.
     */
    private function generate_id($attachment_id, $image_url, $context)
    {
        $type = isset($context['type']) ? $context['type'] : '';
        $post_id = isset($context['post_id']) ? $context['post_id'] : get_the_ID();
        $index = isset($context['index']) ? $context['index'] : 0;

        // Use URL as primary @id for consistency
        if ($image_url && filter_var($image_url, FILTER_VALIDATE_URL)) {
            return $image_url;
        }

        // Fallback: Generate semantic hash fragment ID
        if ('featured' === $type && $attachment_id) {
            return home_url('/#/schema/image/featured-' . $attachment_id);
        } elseif ('content' === $type && $post_id) {
            return home_url('/#/schema/image/' . $post_id . '-' . $index);
        }

        // Last resort: use URL if available
        return $image_url;
    }

    /**
     * Get image data from attachment ID (Public API for Pro plugin)
     *
     * This method is used by both the free and Pro plugins to get standardized
     * image metadata from an attachment ID.
     *
     * @param int   $attachment_id Attachment ID.
     * @param array $attrs Optional block attributes for width/height overrides.
     * @return array|null Image data array or null if attachment not found.
     */
    public function get_image_data_from_attachment($attachment_id, $attrs = array())
    {
        if (!$attachment_id) {
            return null;
        }

        $image_url = wp_get_attachment_url($attachment_id);
        if (!$image_url) {
            return null;
        }

        $metadata = wp_get_attachment_metadata($attachment_id);
        $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        $caption = wp_get_attachment_caption($attachment_id);
        $attachment = get_post($attachment_id);

        // Width and height
        $width = isset($metadata['width']) ? $metadata['width'] : 0;
        $height = isset($metadata['height']) ? $metadata['height'] : 0;

        // Check for block-level width/height overrides
        if (!empty($attrs['width'])) {
            $width = $attrs['width'];
        }
        if (!empty($attrs['height'])) {
            $height = $attrs['height'];
        }

        return array(
            'url' => $image_url,
            'width' => $width,
            'height' => $height,
            'caption' => $caption ? $caption : '',
            'alt' => $alt_text ? $alt_text : '',
            'name' => $attachment ? $attachment->post_title : '',
            'description' => $attachment ? $attachment->post_content : '',
        );
    }

    /**
     * Get image metadata from attachment ID (Internal use)
     *
     * @param int $attachment_id Attachment ID.
     * @return array Image metadata array.
     */
    private function get_image_metadata($attachment_id)
    {
        // Use public method for consistency
        $data = $this->get_image_data_from_attachment($attachment_id);
        return $data ? $data : array();
    }

    /**
     * Convert image URL strings to ImageObject references
     *
     * Scans schemas for image/logo fields that are plain URL strings
     * and converts them to proper ImageObject references.
     *
     * @param array $schemas Array of schemas to process.
     * @return array Modified schemas with ImageObject references.
     */
    public function convert_image_urls_to_references($schemas)
    {
        foreach ($schemas as $index => $schema) {
            // Check for image or logo fields with URL strings
            foreach (array('image', 'logo') as $field) {
                if (isset($schema[$field]) && is_string($schema[$field]) && filter_var($schema[$field], FILTER_VALIDATE_URL)) {
                    // Convert URL string to ImageObject using centralized handler
                    $image_object = $this->generate_image_object($schema[$field], array(
                        'type' => $field === 'logo' ? 'logo' : 'featured',
                    ));

                    if ($image_object) {
                        $schemas[$index][$field] = $image_object;
                    }
                }
            }
        }

        return $schemas;
    }

    /**
     * Resolve image variable references in a schema
     *
     * Handles variables like {featured_image} and {site_logo} and converts them
     * to proper ImageObject schemas.
     *
     * @param array  $schema Schema array to process.
     * @param string $schema_type Schema type (e.g., 'Article', 'Organization').
     * @param array  $fields Original field values from schema builder.
     * @return array Modified schema with resolved image references.
     */
    public function resolve_image_variable_references($schema, $schema_type, $fields)
    {
        // Check both 'image' and 'logo' fields
        $target_field = null;
        if (isset($schema['image'])) {
            $target_field = 'image';
        } elseif (isset($schema['logo'])) {
            $target_field = 'logo';
        }

        if (!$target_field) {
            return $schema;
        }

        $variable = $schema[$target_field];

        // If already converted to ImageObject reference, skip processing
        if (is_array($variable) && isset($variable['@type']) && $variable['@type'] === 'ImageObject') {
            return $schema;
        }

        // Check if it's a hybrid field object with a variable URL (e.g. {url: '{featured_image}'})
        if (is_array($variable) && isset($variable['url']) && strpos($variable['url'], '{') === 0) {
            // Extract the variable to process it below
            $variable = $variable['url'];
        }

        // Resolve variable to attachment ID
        $image_id = 0;

        // Handle {featured_image}
        if ($variable === '{featured_image}') {
            $post_id = get_the_ID();
            if (has_post_thumbnail($post_id)) {
                $image_id = get_post_thumbnail_id($post_id);
            } elseif ($target_field === 'image') {
                // Fallback to Default Schema Image for main image field (e.g. Article)
                $image_id = $this->get_default_image_id();
            }
        }
        // Handle {site_logo}
        elseif ($variable === '{site_logo}') {
            // 1. Check Custom Logo
            if (has_custom_logo()) {
                $image_id = get_theme_mod('custom_logo');
            }
            // 2. Check Site Icon
            if (!$image_id) {
                $image_id = get_option('site_icon');
            }
            // 3. Check Pro Default Image
            if (!$image_id) {
                $image_id = $this->get_default_image_id();
            }
        }
        // Handle direct image object (from hybrid field) or other arrays
        elseif (is_array($schema[$target_field]) && isset($schema[$target_field]['id'])) {
            $image_id = absint($schema[$target_field]['id']);

            // Handle case where we have an empty image object (id=0, url='')
            if (0 === $image_id && empty($schema[$target_field]['url'])) {
                // Try fallback for main image field
                if ('image' === $target_field) {
                    $image_id = $this->get_default_image_id();
                }

                // If still no ID, unset this field so we don't output an empty array
                if (0 === $image_id) {
                    unset($schema[$target_field]);
                }
            }
        }

        // Use centralized handler to generate ImageObject
        if ($image_id || is_string($variable)) {
            // Determine input (ID or variable/URL)
            $input = $image_id ? $image_id : $variable;

            // Set context for @id generation
            $context = array(
                'type' => 'featured',
                'post_id' => get_the_ID(),
            );

            // Generate ImageObject
            $image_object = $this->generate_image_object($input, $context);

            if ($image_object) {
                $schema[$target_field] = $image_object;
            }
        }

        return $schema;
    }

    /**
     * Get the default schema image ID from settings
     *
     * @return int Image ID or 0 if not set/found.
     */
    public function get_default_image_id()
    {
        if (!defined('SWIFT_RANK_PRO_VERSION')) {
            return 0;
        }

        $settings = get_option('swift_rank_settings', array());
        $default_image = isset($settings['default_image']) ? $settings['default_image'] : '';

        // Handle array/object (new storage with ID) or string (legacy)
        if (is_array($default_image) && isset($default_image['id'])) {
            return (int) $default_image['id'];
        } elseif (is_string($default_image) && !empty($default_image)) {
            // Check if it's the {site_logo} variable
            if ($default_image === '{site_logo}') {
                if (has_custom_logo()) {
                    return get_theme_mod('custom_logo');
                }
                $site_icon = get_option('site_icon');
                if ($site_icon) {
                    return $site_icon;
                }
                return 0; // Return 0 to prevent infinite recursion if we keep checking
            }

            // We need an ID for the resolver. Try to find ID from URL.
            return attachment_url_to_postid($default_image);
        }

        return 0;
    }
}
