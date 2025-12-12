<?php
/**
 * FAQ Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_FAQ class
 *
 * Builds FAQPage schema type.
 */
class Schema_FAQ implements Schema_Builder_Interface
{

	/**
	 * Build FAQ schema from fields
	 *
	 * @param array $fields Field values.
	 * @return array Schema array (without @context).
	 */
	public function build($fields)
	{
		$schema = array(
			'@type' => 'FAQPage',
			'mainEntity' => array(),
		);

		// Page name (optional)
		if (!empty($fields['name'])) {
			$schema['name'] = $fields['name'];
		}

		// Page URL (optional)
		if (!empty($fields['url'])) {
			$schema['url'] = $fields['url'];
		}

		// Build FAQ items
		if (!empty($fields['items']) && is_array($fields['items'])) {
			foreach ($fields['items'] as $item) {
				if (!empty($item['question']) && !empty($item['answer'])) {
					$schema['mainEntity'][] = array(
						'@type' => 'Question',
						'name' => $item['question'],
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text' => $item['answer'],
						),
					);
				}
			}
		}

		return $schema;
	}

	/**
	 * Get schema.org structure for FAQPage type
	 *
	 * @return array Schema.org structure specification.
	 */
	public function get_schema_structure()
	{
		return array(
			'@type' => 'FAQPage',
			'@context' => 'https://schema.org',
			'label' => __('FAQ Page', 'swift-rank'),
			'description' => __('A Frequently Asked Questions page.', 'swift-rank'),
			'url' => 'https://schema.org/FAQPage',
			'icon' => 'help-circle',
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
				'label' => __('Page Name', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Optional name for the FAQ page. Use {post_title} to automatically use the post title.', 'swift-rank'),
				'placeholder' => '{post_title}',
				'default' => '{post_title}',
			),
			array(
				'name' => 'url',
				'label' => __('URL', 'swift-rank'),
				'type' => 'url',
				'tooltip' => __('The canonical URL of the FAQ page. Use {post_url} to automatically use the post permalink.', 'swift-rank'),
				'placeholder' => '{post_url}',
				'default' => '{post_url}',
			),
			array(
				'name' => 'items',
				'label' => __('FAQ Items', 'swift-rank'),
				'type' => 'repeater',
				'tooltip' => __('Add your frequently asked questions and answers.', 'swift-rank'),
				'required' => true,
				'fields' => array(
					array(
						'name' => 'question',
						'label' => __('Question', 'swift-rank'),
						'type' => 'text',
						'placeholder' => __('Enter the question', 'swift-rank'),
						'required' => true,
					),
					array(
						'name' => 'answer',
						'label' => __('Answer', 'swift-rank'),
						'type' => 'textarea',
						'rows' => 4,
						'placeholder' => __('Enter the answer', 'swift-rank'),
						'required' => true,
					),
				),
			),
		);
	}

}
