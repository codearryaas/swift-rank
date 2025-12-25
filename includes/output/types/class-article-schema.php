<?php
/**
 * Article Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_Article class
 *
 * Builds Article, BlogPosting, and NewsArticle schema types.
 */
class Schema_Article implements Schema_Builder_Interface
{

	/**
	 * Build article schema from fields
	 *
	 * @param array $fields Field values.
	 * @return array Schema array (without @context).
	 */
	public function build($fields)
	{
		// Use articleType if set, otherwise default to Article
		$article_type = !empty($fields['articleType']) ? $fields['articleType'] : 'Article';

		// Get field values with fallback to variables
		$headline = isset($fields['headline']) ? $fields['headline'] : '{post_title}';
		$date_published = isset($fields['datePublished']) ? $fields['datePublished'] : '{post_date}';
		$date_modified = isset($fields['dateModified']) ? $fields['dateModified'] : '{post_modified}';

		// Handle authorName - could be string or reference object (Pro feature)
		// If it's an array (reference object), we'll handle it as a special case
		// Handle authorName - could be string or reference object (Pro feature)
		// If it's an array (reference object), we'll handle it as a special case
		$author_name = '{author_name}'; // Default
		$is_reference = false;

		if (isset($fields['authorName'])) {
			if (is_string($fields['authorName'])) {
				$author_name = $fields['authorName'];
			} elseif (is_array($fields['authorName']) && !empty($fields['authorName'])) {
				// This is likely a reference object from Pro plugin
				// Set a placeholder that Pro filter will replace
				$is_reference = true;
				$author_name = '{author_name}'; // Fallback if Pro doesn't handle it
			}
		}

		$schema = array(
			'@type' => $article_type,
			'headline' => $headline,
			'url' => isset($fields['url']) ? $fields['url'] : '{post_url}',
			'datePublished' => $date_published,
			'dateModified' => $date_modified,
		);

		// Description
		if (!empty($fields['description'])) {
			$schema['description'] = $fields['description'];
		}

		// Handle both 'image' and 'imageUrl' field names - use featured image as default
		// We prioritize imageUrl, then image. If both are unset (not empty string, but UNSET), use default.
		// If either is confirmed empty string, we respect it.
		$image_url = '{featured_image}'; // Default

		if (isset($fields['imageUrl'])) {
			$image_url = $fields['imageUrl'];
		} elseif (isset($fields['image'])) {
			$image_url = $fields['image'];
		}

		$schema['image'] = $image_url;

		// Author - always include with default variable
		$schema['author'] = array(
			'@type' => 'Person',
			'name' => $author_name,
		);

		// Add author URL if provided
		if (!empty($fields['authorUrl'])) {
			$schema['author']['url'] = $fields['authorUrl'];
		}

		// Publisher
		// Publisher
		$publisher_name = isset($fields['publisherName']) ? $fields['publisherName'] : '{site_name}';
		$publisher = array(
			'@type' => 'Organization',
			'name' => $publisher_name,
		);

		// Publisher Logo
		// Publisher Logo
		$publisher_logo = isset($fields['publisherLogo']) ? $fields['publisherLogo'] : '{site_logo}';

		if (!empty($publisher_logo)) {
			$publisher['logo'] = array(
				'@type' => 'ImageObject',
				'url' => $publisher_logo,
			);
		}

		$schema['publisher'] = $publisher;

		return $schema;
	}

	/**
	 * Get schema.org structure for Article type
	 *
	 * @return array Schema.org structure specification.
	 */
	public function get_schema_structure()
	{
		return array(
			'@type' => 'Article',
			'@context' => 'https://schema.org',
			'label' => __('Article', 'swift-rank'),
			'description' => __('An article, such as a news article or piece of investigative report.', 'swift-rank'),
			'url' => 'https://schema.org/Article',
			'icon' => 'file-text',
			'supports_language' => true,
			'subtypes' => array(
				'Article' => __('Article - General article content', 'swift-rank'),
				'BlogPosting' => __('BlogPosting - Blog posts and informal articles', 'swift-rank'),
				'NewsArticle' => __('NewsArticle - News and current events', 'swift-rank'),
				'ScholarlyArticle' => __('ScholarlyArticle - Academic and research articles', 'swift-rank'),
				'TechArticle' => __('TechArticle - Technical and how-to articles', 'swift-rank'),
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
		$fields = array(
			array(
				'name' => 'articleType',
				'label' => __('Article Sub-Type', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('The specific type of article. Different types may have slightly different schema properties and appear differently in search results.', 'swift-rank'),
				'options' => array(
					array(
						'label' => __('Article', 'swift-rank'),
						'value' => 'Article',
						'description' => __('General article content', 'swift-rank'),
					),
					array(
						'label' => __('NewsArticle', 'swift-rank'),
						'value' => 'NewsArticle',
						'description' => __('News and current events', 'swift-rank'),
					),
					array(
						'label' => __('BlogPosting', 'swift-rank'),
						'value' => 'BlogPosting',
						'description' => __('Blog posts and informal articles', 'swift-rank'),
					),
					array(
						'label' => __('ScholarlyArticle', 'swift-rank'),
						'value' => 'ScholarlyArticle',
						'description' => __('Academic and research articles', 'swift-rank'),
					),
					array(
						'label' => __('TechArticle', 'swift-rank'),
						'value' => 'TechArticle',
						'description' => __('Technical and how-to articles', 'swift-rank'),
					),
				),
				'default' => 'Article',
			),
			array(
				'name' => 'headline',
				'label' => __('Headline', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Article headline. Click pencil icon to use variables.', 'swift-rank'),
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
				'tooltip' => __('Article URL. Click pencil icon to enter custom URL.', 'swift-rank'),
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
				'name' => 'description',
				'label' => __('Description', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'textarea',
				'rows' => 4,
				'tooltip' => __('Article description. Click pencil icon to use variables.', 'swift-rank'),
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
				'name' => 'authorName',
				'label' => __('Author Name', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Article author. Click pencil icon to use variables.', 'swift-rank'),
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
				'name' => 'imageUrl',
				'label' => __('Image URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Article image. Click pencil icon to enter custom URL.', 'swift-rank'),
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
				'name' => 'publisherName',
				'label' => __('Publisher Name', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Publisher name. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{site_name}',
				'options' => array(
					array(
						'label' => __('Site Name', 'swift-rank'),
						'value' => '{site_name}',
					),
				),
				'default' => '{site_name}',
				'required' => true,
			),
			array(
				'name' => 'publisherLogo',
				'label' => __('Publisher Logo URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Publisher logo. Click pencil icon to enter custom URL.', 'swift-rank'),
				'placeholder' => '{site_logo}',
				'options' => array(
					array(
						'label' => __('Site Logo', 'swift-rank'),
						'value' => '{site_logo}',
					),
				),
				'default' => '{site_logo}',
				'required' => true,
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
				'required' => true,
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

		// Add paywall settings notice if Pro is not active
		if (!defined('SWIFT_RANK_PRO_VERSION')) {
			$fields[] = array(
				'name' => 'paywall_settings_placeholder',
				'label' => __('Paywall Settings', 'swift-rank'),
				'type' => 'notice',
				'message' => __('Upgrade to Pro to manage paywalled content settings', 'swift-rank'),
				'linkText' => __('Upgrade to Pro', 'swift-rank'),
				'isPro' => true,
				'tooltip' => __('Enable paywall markup to help Google identify subscription content.', 'swift-rank'),
			);
		}

		return $fields;
	}

}
