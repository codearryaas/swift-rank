<?php
/**
 * HowTo Block Server-Side Rendering
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * HowTo_Block class
 *
 * Handles server-side rendering and schema output for HowTo block.
 */
class HowTo_Block
{

    /**
     * Instance of this class
     *
     * @var HowTo_Block
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return HowTo_Block
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        // Hook into schema output handler to add HowTo block schemas
        add_filter('swift_rank_schemas', array($this, 'add_howto_block_schema'), 10, 1);
    }

    /**
     * Add HowTo block schema to the schema collection
     *
     * @param array $schemas Existing schemas.
     * @return array Modified schemas array.
     */
    public function add_howto_block_schema($schemas)
    {
        if (!is_singular()) {
            return $schemas;
        }

        global $post;

        if (!$post || empty($post->post_content)) {
            return $schemas;
        }

        // Extract HowTo items from post content
        $howto_blocks = $this->extract_howto_blocks($post->post_content);

        if (empty($howto_blocks)) {
            return $schemas;
        }

        // Get schema handler instance
        $schema_handler = Schema_Output_Handler::get_instance();

        foreach ($howto_blocks as $howto_data) {
            // Build schema using the registered HowTo builder
            $howto_schema = $schema_handler->build_schema('HowTo', $howto_data);

            if (!empty($howto_schema)) {
                $schemas[] = $howto_schema;
            }
        }

        return $schemas;
    }

    /**
     * Extract HowTo blocks from content
     *
     * @param string $content Post content.
     * @return array Array of HowTo data.
     */
    private function extract_howto_blocks($content)
    {
        $howto_blocks = array();

        // Parse blocks from content
        if (!function_exists('parse_blocks')) {
            return $howto_blocks;
        }

        $blocks = parse_blocks($content);

        foreach ($blocks as $block) {
            // Check if this is a HowTo block
            if ('swift-rank/howto' === $block['blockName']) {
                $enable_schema = isset($block['attrs']['enableSchema']) ? $block['attrs']['enableSchema'] : true;

                if (!$enable_schema) {
                    continue;
                }

                $title = isset($block['attrs']['title']) ? $block['attrs']['title'] : '';
                $description = isset($block['attrs']['description']) ? $block['attrs']['description'] : '';
                $total_time = isset($block['attrs']['totalTime']) ? $block['attrs']['totalTime'] : '';

                $steps = array();

                // Extract steps from inner blocks
                if (!empty($block['innerBlocks'])) {
                    // error_log( 'HowTo Block found. InnerBlocks count: ' . count( $block['innerBlocks'] ) );
                    foreach ($block['innerBlocks'] as $inner_block) {
                        // error_log( 'Inner Block Name: ' . $inner_block['blockName'] );
                        if ('swift-rank/howto-step' === $inner_block['blockName']) {
                            $name = isset($inner_block['attrs']['name']) ? $inner_block['attrs']['name'] : '';
                            $text = isset($inner_block['attrs']['text']) ? $inner_block['attrs']['text'] : '';
                            $image = isset($inner_block['attrs']['image']) ? $inner_block['attrs']['image'] : '';
                            $url = isset($inner_block['attrs']['url']) ? $inner_block['attrs']['url'] : '';

                            // error_log( 'Step found: ' . $name );

                            // Skip empty steps
                            if (empty($name) && empty($text)) {
                                continue;
                            }

                            $steps[] = array(
                                '@type' => 'HowToStep',
                                'name' => wp_strip_all_tags($name),
                                'itemListElement' => array(
                                    '@type' => 'HowToDirection',
                                    'text' => wp_strip_all_tags($text),
                                ),
                                'image' => $image,
                                'url' => $url,
                            );
                        }
                    }
                }

                if (!empty($steps)) {
                    $howto_blocks[] = array(
                        'description' => wp_strip_all_tags($description),
                        'totalTime' => $total_time,
                        'steps' => $steps,
                    );
                }
            }

            // Recursively check inner blocks for nested HowTo blocks
            if (!empty($block['innerBlocks'])) {
                $nested_content = serialize_blocks($block['innerBlocks']);
                $nested_blocks = $this->extract_howto_blocks($nested_content);
                $howto_blocks = array_merge($howto_blocks, $nested_blocks);
            }
        }

        return $howto_blocks;
    }
}
