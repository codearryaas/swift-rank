<?php
/**
 * FAQ Block Server-Side Rendering
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * FAQ_Block class
 *
 * Handles server-side rendering and schema output for FAQ block.
 */
class FAQ_Block
{

	/**
	 * Instance of this class
	 *
	 * @var FAQ_Block
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return FAQ_Block
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
		// Hook into schema output handler to add FAQ block schemas
		add_filter('swift_rank_schemas', array($this, 'add_faq_block_schema'), 10, 1);
	}

	/**
	 * Add FAQ block schema to the schema collection
	 *
	 * @param array $schemas Existing schemas.
	 * @return array Modified schemas array.
	 */
	public function add_faq_block_schema($schemas)
	{
		if (!is_singular()) {
			return $schemas;
		}

		global $post;

		if (!$post || empty($post->post_content)) {
			return $schemas;
		}

		// Extract FAQ items from post content
		$faq_items = $this->extract_faq_items($post->post_content);

		if (empty($faq_items)) {
			return $schemas;
		}

		// Get schema handler instance
		$schema_handler = Schema_Output_Handler::get_instance();

		// Prepare fields for FAQ schema builder
		$fields = array(
			'url' => get_permalink($post->ID),
			'items' => $faq_items,
		);

		// Build schema using the registered FAQ builder
		// This will use the Schema_FAQ class and respect all settings (minification, etc.)
		$faq_schema = $schema_handler->build_schema('FAQPage', $fields);

		if (!empty($faq_schema)) {
			$schemas[] = $faq_schema;
		}

		return $schemas;
	}

	/**
	 * Extract FAQ items from block content
	 *
	 * @param string $content Post content.
	 * @return array Array of FAQ items.
	 */
	private function extract_faq_items($content)
	{
		$faq_items = array();

		// Parse blocks from content
		if (!function_exists('parse_blocks')) {
			return $faq_items;
		}

		$blocks = parse_blocks($content);

		foreach ($blocks as $block) {
			// Check if this is an FAQ block
			if ('swift-rank/faq' === $block['blockName']) {
				$enable_schema = isset($block['attrs']['enableSchema']) ? $block['attrs']['enableSchema'] : true;

				if (!$enable_schema) {
					continue;
				}

				// Extract FAQ items from inner blocks
				if (!empty($block['innerBlocks'])) {
					foreach ($block['innerBlocks'] as $inner_block) {
						if ('swift-rank/faq-item' === $inner_block['blockName']) {
							$question = isset($inner_block['attrs']['question']) ? $inner_block['attrs']['question'] : '';
							$answer = isset($inner_block['attrs']['answer']) ? $inner_block['attrs']['answer'] : '';

							// Skip empty items
							if (empty($question) || empty($answer)) {
								continue;
							}

							// Strip HTML tags for schema
							$question_text = wp_strip_all_tags($question);
							$answer_text = wp_strip_all_tags($answer);

							if (!empty($question_text) && !empty($answer_text)) {
								$faq_items[] = array(
									'question' => $question_text,
									'answer' => $answer_text,
								);
							}
						}
					}
				}
			}

			// Recursively check inner blocks for nested FAQ blocks
			if (!empty($block['innerBlocks'])) {
				$nested_content = serialize_blocks($block['innerBlocks']);
				$nested_items = $this->extract_faq_items($nested_content);
				$faq_items = array_merge($faq_items, $nested_items);
			}
		}

		return $faq_items;
	}
}
