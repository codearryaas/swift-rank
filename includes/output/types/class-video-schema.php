<?php
/**
 * Video Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_Video class
 *
 * Builds VideoObject schema type.
 */
class Schema_Video implements Schema_Builder_Interface
{

    /**
     * Build video schema from fields
     *
     * @param array $fields Field values.
     * @return array Schema array (without @context).
     */
    public function build($fields)
    {
        // Required fields
        $name = isset($fields['name']) ? $fields['name'] : '{post_title}';
        $description = isset($fields['description']) ? $fields['description'] : '{post_excerpt}';
        $upload_date = isset($fields['uploadDate']) ? $fields['uploadDate'] : '{post_date}';

        $schema = array(
            '@type' => 'VideoObject',
            'name' => $name,
            'description' => $description,
            'uploadDate' => $upload_date,
        );

        // Thumbnail URL (required)
        $thumbnail_url = isset($fields['thumbnailUrl']) ? $fields['thumbnailUrl'] : '{featured_image}';
        if (!empty($thumbnail_url)) {
            $schema['thumbnailUrl'] = $thumbnail_url;
        }

        // Content URL (video file URL)
        if (!empty($fields['contentUrl'])) {
            $schema['contentUrl'] = $fields['contentUrl'];
        }

        // Embed URL (YouTube, Vimeo, etc.)
        if (!empty($fields['embedUrl'])) {
            $schema['embedUrl'] = $fields['embedUrl'];
        }

        // Duration (ISO 8601 format, e.g., PT1H30M)
        if (!empty($fields['duration'])) {
            $schema['duration'] = $fields['duration'];
        }





        // Additional optional fields
        if (!empty($fields['url'])) {
            $schema['url'] = $fields['url'];
        }

        if (!empty($fields['width'])) {
            $schema['width'] = $fields['width'];
        }

        if (!empty($fields['height'])) {
            $schema['height'] = $fields['height'];
        }

        return $schema;
    }

    /**
     * Get schema.org structure for Video type
     *
     * @return array Schema.org structure specification.
     */
    public function get_schema_structure()
    {
        return array(
            '@type' => 'VideoObject',
            '@context' => 'https://schema.org',
            'label' => __('Video Object', 'swift-rank'),
            'description' => __('A video file or video content.', 'swift-rank'),
            'url' => 'https://schema.org/VideoObject',
            'icon' => 'video',
        );
    }

    /**
     * Get field definitions for the admin UI
     *
     * @return array Array of field configurations for React components.
     */
    public function get_fields()
    {
        $fields = array(
            array(
                'name' => 'name',
                'label' => __('Video Name', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Video title. Click pencil icon to use variables.', 'swift-rank'),
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
                'name' => 'description',
                'label' => __('Description', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'customType' => 'textarea',
                'rows' => 4,
                'tooltip' => __('Video description. Click pencil icon to use variables.', 'swift-rank'),
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
                'required' => true,
            ),
            array(
                'name' => 'thumbnailUrl',
                'label' => __('Thumbnail URL', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Video thumbnail. Click pencil icon to enter custom URL.', 'swift-rank'),
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
                'name' => 'contentUrl',
                'label' => __('Content URL', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Direct video file URL. Click pencil icon to enter custom URL.', 'swift-rank'),
                'placeholder' => 'https://example.com/video.mp4',
                'options' => array(),
            ),
            array(
                'name' => 'embedUrl',
                'label' => __('Embed URL', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Video embed URL. Click pencil icon to enter custom URL.', 'swift-rank'),
                'placeholder' => 'https://www.youtube.com/embed/VIDEO_ID',
                'options' => array(),
            ),
            array(
                'name' => 'duration',
                'label' => __('Duration', 'swift-rank'),
                'type' => 'text',
                'tooltip' => __('Video duration in ISO 8601 format (e.g., PT1H30M for 1 hour 30 minutes, PT5M for 5 minutes).', 'swift-rank'),
                'placeholder' => 'PT5M30S',
            ),
            array(
                'name' => 'uploadDate',
                'label' => __('Upload Date', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Upload date. Click pencil icon to use variables.', 'swift-rank'),
                'placeholder' => '{post_date}',
                'options' => array(
                    array(
                        'label' => __('Post Date', 'swift-rank'),
                        'value' => '{post_date}',
                    ),
                ),
                'default' => '{post_date}',
                'required' => true,
            ),
            array(
                'name' => 'url',
                'label' => __('URL', 'swift-rank'),
                'type' => 'select',
                'allowCustom' => true,
                'tooltip' => __('Video page URL. Click pencil icon to enter custom URL.', 'swift-rank'),
                'placeholder' => '{post_url}',
                'options' => array(
                    array(
                        'label' => __('Post URL', 'swift-rank'),
                        'value' => '{post_url}',
                    ),
                ),
                'default' => '{post_url}',
            ),
            array(
                'name' => 'width',
                'label' => __('Width', 'swift-rank'),
                'type' => 'number',
                'tooltip' => __('The width of the video in pixels (e.g., 1920).', 'swift-rank'),
                'placeholder' => '1920',
            ),
            array(
                'name' => 'height',
                'label' => __('Height', 'swift-rank'),
                'type' => 'number',
                'tooltip' => __('The height of the video in pixels (e.g., 1080).', 'swift-rank'),
                'placeholder' => '1080',
            ),
        );

        // Add Pro features notice if Pro is not active
        if (!defined('SWIFT_RANK_PRO_VERSION')) {
            $fields[] = array(
                'name' => 'video_pro_features_placeholder',
                'label' => __('Pro Features', 'swift-rank'),
                'type' => 'notice',
                'message' => __('Upgrade to Pro to unlock advanced video schema features:<br><br><strong>• Seek To Action Target</strong> - Enable deep linking to specific timestamps in your video<br><strong>• Live Broadcast Settings</strong> - Mark videos as live broadcasts with start/end dates<br><strong>• Video Clips</strong> - Define key clips or segments within your video with timestamps<br><strong>• Paywall Settings</strong> - Manage paywalled content and subscription markup', 'swift-rank'),
                'linkText' => __('Upgrade to Pro', 'swift-rank'),
                'isPro' => true,
                'allowHtml' => true,
                'tooltip' => __('Pro version adds advanced video schema features including deep linking, live broadcasts, video clips, and paywall support.', 'swift-rank'),
            );
        }

        return $fields;
    }
}
