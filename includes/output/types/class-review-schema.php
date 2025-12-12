<?php
/**
 * Review Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_Review class
 *
 * Builds Review schema type with rating information.
 */
class Schema_Review implements Schema_Builder_Interface
{

	/**
	 * Build review schema from fields
	 *
	 * @param array $fields Field values.
	 * @return array Schema array (without @context).
	 */
	public function build($fields)
	{
		// Get field values with fallback to variables
		$author_name = isset($fields['authorName']) ? $fields['authorName'] : '{author_name}';
		$item_reviewed_name = isset($fields['itemReviewedName']) ? $fields['itemReviewedName'] : '{post_title}';
		$rating_value = isset($fields['ratingValue']) ? $fields['ratingValue'] : '5';

		$schema = array(
			'@type' => 'Review',
		);

		// Review body
		if (!empty($fields['reviewBody'])) {
			$schema['reviewBody'] = $fields['reviewBody'];
		}

		// Author - always include with default variable
		$author_type = isset($fields['authorType']) ? $fields['authorType'] : 'Person';
		$schema['author'] = array(
			'@type' => $author_type,
			'name' => $author_name,
		);

		// Add author URL if provided
		if (!empty($fields['authorUrl'])) {
			$schema['author']['url'] = $fields['authorUrl'];
		}

		// Item Reviewed
		$item_type = isset($fields['itemReviewedType']) ? $fields['itemReviewedType'] : 'Product';
		$schema['itemReviewed'] = array(
			'@type' => $item_type,
			'name' => $item_reviewed_name,
		);

		// Add item reviewed URL if provided
		if (!empty($fields['itemReviewedUrl'])) {
			$schema['itemReviewed']['url'] = $fields['itemReviewedUrl'];
		}

		// Add item reviewed image if provided
		if (!empty($fields['itemReviewedImage'])) {
			$schema['itemReviewed']['image'] = $fields['itemReviewedImage'];
		}

		// Review Rating
		$schema['reviewRating'] = array(
			'@type' => 'Rating',
			'ratingValue' => $rating_value,
		);

		// Best Rating
		$best_rating = isset($fields['bestRating']) ? $fields['bestRating'] : '5';
		$schema['reviewRating']['bestRating'] = $best_rating;

		// Worst Rating
		$worst_rating = isset($fields['worstRating']) ? $fields['worstRating'] : '1';
		$schema['reviewRating']['worstRating'] = $worst_rating;

		// Date Published
		if (!empty($fields['datePublished'])) {
			$schema['datePublished'] = $fields['datePublished'];
		}

		// Review headline/name
		if (!empty($fields['name'])) {
			$schema['name'] = $fields['name'];
		}

		return $schema;
	}

	/**
	 * Get schema.org structure for Review type
	 *
	 * @return array Schema.org structure specification.
	 */
	public function get_schema_structure()
	{
		return array(
			'@type' => 'Review',
			'@context' => 'https://schema.org',
			'label' => __('Review', 'swift-rank'),
			'description' => __('A review of an item - for example, of a restaurant, movie, or product.', 'swift-rank'),
			'url' => 'https://schema.org/Review',
			'icon' => 'star',
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
				'name' => 'itemReviewedType',
				'label' => __('Item Type', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('The type of item being reviewed.', 'swift-rank'),
				'options' => array(
					array(
						'label' => __('Product', 'swift-rank'),
						'value' => 'Product',
						'description' => __('Physical or digital product', 'swift-rank'),
					),
					array(
						'label' => __('LocalBusiness', 'swift-rank'),
						'value' => 'LocalBusiness',
						'description' => __('Local business or restaurant', 'swift-rank'),
					),
					array(
						'label' => __('Book', 'swift-rank'),
						'value' => 'Book',
						'description' => __('Published book', 'swift-rank'),
					),
					array(
						'label' => __('Movie', 'swift-rank'),
						'value' => 'Movie',
						'description' => __('Film or movie', 'swift-rank'),
					),
					array(
						'label' => __('Course', 'swift-rank'),
						'value' => 'Course',
						'description' => __('Educational course', 'swift-rank'),
					),
					array(
						'label' => __('Event', 'swift-rank'),
						'value' => 'Event',
						'description' => __('Event or happening', 'swift-rank'),
					),
					array(
						'label' => __('Recipe', 'swift-rank'),
						'value' => 'Recipe',
						'description' => __('Cooking recipe', 'swift-rank'),
					),
					array(
						'label' => __('SoftwareApplication', 'swift-rank'),
						'value' => 'SoftwareApplication',
						'description' => __('Software or app', 'swift-rank'),
					),
					array(
						'label' => __('Game', 'swift-rank'),
						'value' => 'Game',
						'description' => __('Video game', 'swift-rank'),
					),
					array(
						'label' => __('Organization', 'swift-rank'),
						'value' => 'Organization',
						'description' => __('Company or organization', 'swift-rank'),
					),
				),
				'default' => 'Product',
				'required' => true,
			),
			array(
				'name' => 'itemReviewedName',
				'label' => __('Item Name', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Item name. Click pencil icon to use variables.', 'swift-rank'),
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
				'name' => 'itemReviewedUrl',
				'label' => __('Item URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Item URL. Click pencil icon to enter custom URL.', 'swift-rank'),
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
				'name' => 'itemReviewedImage',
				'label' => __('Item Image URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Item image. Click pencil icon to enter custom URL.', 'swift-rank'),
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
				'name' => 'name',
				'label' => __('Review Title', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('The title or headline of the review (optional).', 'swift-rank'),
				'placeholder' => __('e.g., "Great product!"', 'swift-rank'),
			),
			array(
				'name' => 'reviewBody',
				'label' => __('Review Body', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'textarea',
				'rows' => 6,
				'tooltip' => __('Review text. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{post_content}',
				'options' => array(
					array(
						'label' => __('Post Content', 'swift-rank'),
						'value' => '{post_content}',
					),
					array(
						'label' => __('Post Excerpt', 'swift-rank'),
						'value' => '{post_excerpt}',
					),
				),
				'default' => '{post_content}',
			),
			array(
				'name' => 'authorType',
				'label' => __('Author Type', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('Whether the review author is a person or organization.', 'swift-rank'),
				'options' => array(
					array(
						'label' => __('Person', 'swift-rank'),
						'value' => 'Person',
						'description' => __('Individual reviewer', 'swift-rank'),
					),
					array(
						'label' => __('Organization', 'swift-rank'),
						'value' => 'Organization',
						'description' => __('Company or organization reviewer', 'swift-rank'),
					),
				),
				'default' => 'Person',
				'required' => true,
			),
			array(
				'name' => 'authorName',
				'label' => __('Author Name', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Review author. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{author_name}',
				'options' => array(
					array(
						'label' => __('Author Name', 'swift-rank'),
						'value' => '{author_name}',
					),
				),
				'default' => '{author_name}',
				'required' => true,
			),
			array(
				'name' => 'authorUrl',
				'label' => __('Author URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Author URL. Click pencil icon to enter custom URL.', 'swift-rank'),
				'placeholder' => '{author_url}',
				'options' => array(
					array(
						'label' => __('Author URL', 'swift-rank'),
						'value' => '{author_url}',
					),
				),
				'default' => '{author_url}',
			),
			array(
				'name' => 'ratingValue',
				'label' => __('Rating Value', 'swift-rank'),
				'type' => 'number',
				'tooltip' => __('The numerical rating given in this review (e.g., 4, 4.5). Use dots for decimals.', 'swift-rank'),
				'placeholder' => '5',
				'default' => '5',
				'required' => true,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
			),
			array(
				'name' => 'bestRating',
				'label' => __('Best Rating', 'swift-rank'),
				'type' => 'number',
				'tooltip' => __('The highest value allowed in this rating system (defaults to 5).', 'swift-rank'),
				'placeholder' => '5',
				'default' => '5',
				'min' => 1,
				'step' => 1,
			),
			array(
				'name' => 'worstRating',
				'label' => __('Worst Rating', 'swift-rank'),
				'type' => 'number',
				'tooltip' => __('The lowest value allowed in this rating system (defaults to 1).', 'swift-rank'),
				'placeholder' => '1',
				'default' => '1',
				'min' => 0,
				'step' => 1,
			),
			array(
				'name' => 'datePublished',
				'label' => __('Date Published', 'swift-rank'),
				'type' => 'date',
				'tooltip' => __('Publication date of the review. Click pencil icon to use variables or pick date.', 'swift-rank'),
				'placeholder' => '{post_date}',
				'default' => '{post_date}',
			),
		);
	}

}
