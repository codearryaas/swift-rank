<?php
/**
 * Schema Output Handler Class
 *
 * Main class responsible for coordinating schema output to the frontend.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Schema_Output_Handler class
 *
 * Handles frontend schema JSON-LD output coordination.
 */
class Schema_Output_Handler
{
	// ============================================================================
	// PROPERTIES
	// ============================================================================

	/**
	 * Pro schema types that require Pro license
	 * Loaded from centralized config
	 *
	 * @var array
	 */
	private $pro_schema_types = array();

	/**
	 * Instance of this class
	 *
	 * @var Schema_Output_Handler
	 */
	private static $instance = null;

	/**
	 * Registry of schema type builders
	 *
	 * @var array
	 */
	private $schema_builders = array();

	/**
	 * Variable replacer instance
	 *
	 * @var Schema_Variable_Replacer
	 */
	private $variable_replacer;

	// ============================================================================
	// INITIALIZATION & SETUP
	// ============================================================================

	/**
	 * Get singleton instance
	 *
	 * @return Schema_Output_Handler
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
		// Load Pro types from centralized config
		if (class_exists('Schema_Types_Config')) {
			$this->pro_schema_types = Schema_Types_Config::get_pro_types();
		}

		$this->init_schema_output_hook();
		$this->register_schema_builders();

		// Hook to resolve {featured_image} and {site_logo} to object reference
		add_filter('swift_rank_output_schema', array($this, 'resolve_image_variable_references'), 10, 3);
	}

	/**
	 * Initialize schema output hook based on settings
	 */
	private function init_schema_output_hook()
	{
		$settings = get_option('swift_rank_settings', array());

		// Check for Yoast SEO Schema Disabling
		if (!empty($settings['disable_yoast_schema'])) {
			add_filter('wpseo_json_ld_output', '__return_false');
		}

		// Check for AIOSEO Schema Disabling
		if (!empty($settings['disable_aioseo_schema'])) {
			add_filter('aioseo_schema_disable', '__return_true');
		}

		// Check for Rank Math Schema Disabling
		if (!empty($settings['disable_rankmath_schema'])) {
			add_filter('rank_math/json_ld', '__return_false', 99);
		}

		// Code placement is a Pro feature - default to 'head' for free version
		$placement = 'head';
		if (defined('SWIFT_RANK_PRO_VERSION')) {
			$placement = isset($settings['code_placement']) ? $settings['code_placement'] : 'head';
		}

		if ('footer' === $placement) {
			add_action('wp_footer', array($this, 'output_schema'), 1);
		} else {
			add_action('wp_head', array($this, 'output_schema'), 1);
		}
	}

	/**
	 * Register all schema type builders
	 */
	private function register_schema_builders()
	{
		// Load and register schema builders
		require_once __DIR__ . '/../utils/class-schema-reference-manager.php';
		require_once __DIR__ . '/resolvers/class-schema-reference-resolver.php';
		require_once __DIR__ . '/types/class-article-schema.php';
		require_once __DIR__ . '/types/class-organization-schema.php';
		require_once __DIR__ . '/types/class-person-schema.php';
		require_once __DIR__ . '/types/class-localbusiness-schema.php';
		require_once __DIR__ . '/types/class-product-schema.php';
		require_once __DIR__ . '/types/class-faq-schema.php';
		require_once __DIR__ . '/types/class-video-schema.php';
		require_once __DIR__ . '/types/class-review-schema.php';
		require_once __DIR__ . '/types/class-job-posting-schema.php';
		require_once __DIR__ . '/types/class-breadcrumb-schema.php';
		require_once __DIR__ . '/types/class-webpage-schema.php';
		require_once __DIR__ . '/types/class-website-schema.php';
		require_once __DIR__ . '/class-auto-schema-generator.php';

		$this->schema_builders = array(
			'Article' => new Schema_Article(),
			'BlogPosting' => new Schema_Article(),
			'NewsArticle' => new Schema_Article(),
			'Organization' => new Schema_Organization(),
			'Person' => new Schema_Person(),
			'LocalBusiness' => new Schema_LocalBusiness(),
			'Product' => new Schema_Product(),
			'WebPage' => new Schema_Webpage(),
			'FAQPage' => new Schema_FAQ(),
			'VideoObject' => new Schema_Video(),
			'Review' => new Schema_Review(),
			'JobPosting' => new Schema_Job_Posting(),
		);
	}

	/**
	 * Initialize variable replacer
	 */
	private function init_variable_replacer()
	{
		require_once __DIR__ . '/../utils/class-schema-variable-replacer.php';

		// Allow Pro plugin to provide custom variable replacer class
		$replacer_class = apply_filters('swift_rank_variable_replacer_class', 'Schema_Variable_Replacer');

		if (class_exists($replacer_class)) {
			$this->variable_replacer = new $replacer_class();
		} else {
			$this->variable_replacer = new Schema_Variable_Replacer();
		}
	}

	/**
	 * Register a custom schema builder
	 *
	 * @param string $schema_type Schema type name.
	 * @param object $builder     Builder instance.
	 */
	public function register_builder($schema_type, $builder)
	{
		$this->schema_builders[$schema_type] = $builder;
	}

	// ============================================================================
	// SCHEMA OUTPUT
	// ============================================================================

	/**
	 * Output schema JSON-LD
	 */
	public function output_schema()
	{
		$schemas = array();
		$settings = get_option('swift_rank_settings', array());



		// 2. Output Breadcrumb schema on pages with hierarchical context (not homepage)
		// Following SEO best practices: breadcrumbs should appear on all pages that have a navigation path
		$show_breadcrumb = (
			is_singular() ||           // Single posts, pages, custom post types
			is_category() ||           // Category archives
			is_tag() ||                // Tag archives
			is_tax() ||                // Custom taxonomy archives
			is_author() ||             // Author archives
			is_date() ||               // Date archives (year, month, day)
			is_search() ||             // Search results
			is_post_type_archive()     // Custom post type archives
		) && !is_front_page() && !is_home();  // Exclude homepage and blog index

		if ($show_breadcrumb) {
			$breadcrumb_enabled = isset($settings['breadcrumb_enabled']) ? $settings['breadcrumb_enabled'] : false;
			if ($breadcrumb_enabled) {
				$breadcrumb_schema_builder = new Schema_Breadcrumb();
				$breadcrumb_schema = $breadcrumb_schema_builder->build($settings);
				if (!empty($breadcrumb_schema)) {
					// Apply variable replacement to breadcrumb schema
					$breadcrumb_json = wp_json_encode($breadcrumb_schema);
					$breadcrumb_json = $this->replace_template_variables($breadcrumb_json);
					$breadcrumb_schema = json_decode($breadcrumb_json, true);

					$schemas[] = $breadcrumb_schema;
				}
			}
		}

		// 3. Get post-specific schemas (from Swift Rank metabox)
		if (is_singular()) {
			$post_schemas = $this->get_post_schemas();
			if (!empty($post_schemas)) {
				$schemas = array_merge($schemas, $post_schemas);
			}
		}

		// 4. Get author-specific schemas
		if (is_author()) {
			$author_schemas = $this->get_author_schemas();
			if (!empty($author_schemas)) {
				$schemas = array_merge($schemas, $author_schemas);
			}
		}

		// 5. Get auto-generated schemas (if enabled and no post-specific schemas)
		// Auto-schemas are only generated if there are no post-specific schemas
		// Templates will override auto-schemas in the next step
		if (empty($post_schemas) && !is_author()) {
			$auto_schema = $this->get_auto_schema();
			if (!empty($auto_schema)) {
				// Check if templates exist for this page
				$template_schemas = $this->get_template_schemas();

				// Only use auto-schema if no templates exist
				if (empty($template_schemas)) {
					$schemas = array_merge($schemas, $auto_schema);
				} else {
					// Templates exist, use them instead
					$schemas = array_merge($schemas, $template_schemas);
				}
			}
		}

		// 6. Get schema templates that match current page (if no post-specific schemas and no auto-schema was used)
		// On author archives, get_author_schemas() handles template matching with overrides, so we skip this to avoid duplicates
		if (empty($post_schemas) && !is_author() && empty($auto_schema)) {
			$template_schemas = $this->get_template_schemas();
			if (!empty($template_schemas)) {
				$schemas = array_merge($schemas, $template_schemas);
			}
		}

		// 6. Allow other components (like blocks) to add schemas
		$schemas = apply_filters('swift_rank_schemas', $schemas);

		// 7. Output Knowledge Graph (Organization or Person) schema
		// Logic: Output on homepage (if enabled) OR if a WebPage schema is present (to satisfy 'about' link)
		$has_webpage_schema = false;
		foreach ($schemas as $schema) {
			if (isset($schema['@type']) && $schema['@type'] === 'WebPage') {
				$has_webpage_schema = true;
				break;
			}
		}

		$knowledge_graph_enabled = isset($settings['knowledge_graph_enabled']) ? $settings['knowledge_graph_enabled'] : false;
		$should_output_kg = ($knowledge_graph_enabled && (is_front_page() || is_home())) || $has_webpage_schema;

		if ($should_output_kg) {
			// Get schema type (Organization, Person, or LocalBusiness)
			$kg_type = isset($settings['knowledge_graph_type']) ? $settings['knowledge_graph_type'] : 'Organization';

			// Get appropriate fields based on type
			if ($kg_type === 'Person') {
				$kg_fields = isset($settings['person_fields']) ? $settings['person_fields'] : array();
			} elseif ($kg_type === 'LocalBusiness') {
				$kg_fields = isset($settings['localbusiness_fields']) ? $settings['localbusiness_fields'] : array();
			} else {
				$kg_fields = isset($settings['organization_fields']) ? $settings['organization_fields'] : array();
			}

			// Inject social profiles from Social Profiles tab into Knowledge Graph fields
			// Build social profiles array from individual fields
			$social_urls = array();
			$social_fields = ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube'];

			foreach ($social_fields as $field) {
				if (!empty($settings[$field])) {
					$social_urls[] = $settings[$field];
				}
			}

			// Add custom profiles
			if (!empty($settings['custom_profiles']) && is_array($settings['custom_profiles'])) {
				foreach ($settings['custom_profiles'] as $profile) {
					if (!empty($profile['url'])) {
						$social_urls[] = $profile['url'];
					}
				}
			}

			// Convert to repeater format expected by schema builders
			if (!empty($social_urls)) {
				$kg_fields['socialProfiles'] = array();
				foreach ($social_urls as $url) {
					$kg_fields['socialProfiles'][] = array('url' => $url);
				}
			}

			$kg_schema = $this->build_schema($kg_type, $kg_fields);
			if (!empty($kg_schema)) {
				// Ensure global ID for Knowledge Graph (Organization/Person/LocalBusiness)
				// This matches the ID reference used in WebPage's 'about' property
				if (!isset($kg_schema['@id'])) {
					$kg_schema['@id'] = home_url('/#' . strtolower($kg_type));
				}
				$schemas[] = $kg_schema;
			}
		}

		// 8. Output WebSite schema (Sitelinks Searchbox)
		// Logic: Output on homepage OR if a WebPage schema is present (to satisfy 'isPartOf' link)
		// Now checking class_exists without Pro version check as it is migrated to Free
		if ((is_front_page() || is_home() || $has_webpage_schema) && class_exists('Schema_Website')) {
			$website_schema_builder = new Schema_Website();
			// Pass settings to build method so it can check for sitelinks_searchbox
			$website_schema = $website_schema_builder->build($settings);
			if (!empty($website_schema)) {
				// Ensure global ID for WebSite
				if (!isset($website_schema['@id'])) {
					$website_schema['@id'] = home_url('/#website');
				}
				$schemas[] = $website_schema;
			}
		}

		// 9. Post-process all schemas to convert URL strings in image/logo fields to ImageObject references
		// This catches cases where variables like {site_logo} were replaced with plain URLs
		// Do this AFTER all schemas are collected (including KB and WebSite)
		$schemas = $this->convert_image_urls_to_references($schemas);

		// 10. Add any extra schemas that were queued during URL conversion
		if (class_exists('Schema_Reference_Resolver')) {
			$schemas = Schema_Reference_Resolver::add_extra_schemas_to_graph($schemas);
		}

		// Output all schemas as a connected graph
		if (!empty($schemas)) {
			$graph = array();
			$used_ids = array();
			$type_counts = array();

			foreach ($schemas as $schema) {
				// Add @id if missing to support graph connections
				if (!isset($schema['@id']) && isset($schema['@type'])) {
					// Use current post ID as context if available
					// On homepage/front page, use null to get homepage URL
					if (is_front_page() || is_home()) {
						$context_id = null;
					} else {
						$context_id = get_the_ID();
					}
					$type = $schema['@type'];

					// Track counts for this type to handle collisions
					if (!isset($type_counts[$type])) {
						$type_counts[$type] = 1;
						$suffix = '';
					} else {
						++$type_counts[$type];
						$suffix = (string) $type_counts[$type];
					}

					$schema['@id'] = Schema_Reference_Manager::get_id($type, $context_id, $suffix);
				}

				// Ensure ID is globally unique in this graph
				if (isset($schema['@id'])) {
					$original_id = $schema['@id'];
					$counter = 2;
					while (in_array($schema['@id'], $used_ids)) {
						$schema['@id'] = $original_id . '-' . $counter;
						++$counter;
					}
					$used_ids[] = $schema['@id'];
				}

				// Remove @context from inner nodes as it is defined at the root
				if (isset($schema['@context'])) {
					unset($schema['@context']);
				}

				$graph[] = $schema;
			}

			// Final Output Structure
			$output = array(
				'@context' => 'https://schema.org',
				'@graph' => $graph,
				'inLanguage' => get_bloginfo('language'),
			);

			$this->output_json_ld($output);
		}
	}

	// ============================================================================
	// SCHEMA RETRIEVAL
	// ============================================================================

	/**
	 * Get schemas for the current post based on matching templates and overrides
	 *
	 * @return array
	 */
	private function get_post_schemas()
	{
		global $post;

		if (!$post) {
			return array();
		}

		// Get matching templates for this post
		$matching_templates = Schema_Template_Loader::get_instance()->get_templates_for_post($post->ID);
		if (empty($matching_templates)) {
			return array();
		}
		// Get saved field overrides
		$saved_overrides = get_post_meta($post->ID, '_swift_rank_overrides', true);
		if (!is_array($saved_overrides)) {
			$saved_overrides = array();
		}

		$schemas = array();

		foreach ($matching_templates as $template) {
			$template_id = $template['id'];
			$schema_type = isset($template['schemaType']) ? $template['schemaType'] : '';
			$template_fields = isset($template['fields']) ? $template['fields'] : array();

			if (empty($schema_type)) {
				continue;
			}

			// Check if Pro schema type is allowed
			if (!$this->is_schema_type_allowed($schema_type)) {
				continue;
			}

			// Merge template fields with overrides (check string key first, then int)
			$overrides = array();
			$template_key = (string) $template_id;
			if (isset($saved_overrides[$template_key])) {
				$overrides = $saved_overrides[$template_key];
			} elseif (isset($saved_overrides[$template_id])) {
				$overrides = $saved_overrides[$template_id];
			}
			$fields = array_merge($template_fields, $overrides);

			// Build schema using registered builder or filter
			$schema = $this->build_schema($schema_type, $fields);

			if (!empty($schema)) {
				$schemas[] = $schema;
			}
		}


		return $schemas;
	}

	/**
	 * Get schemas for the current author archive based on user profile settings
	 *
	 * @return array
	 */
	private function get_author_schemas()
	{
		// Author schemas are a Pro feature
		if (!defined('SWIFT_RANK_PRO_VERSION')) {
			return array();
		}

		if (!is_author()) {
			return array();
		}

		$author = get_queried_object();
		if (!$author instanceof WP_User) {
			return array();
		}

		// Get all templates
		$templates = get_posts(array(
			'post_type' => 'sr_template',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		));

		$schemas = array();
		$overrides = get_user_meta($author->ID, '_swift_rank_overrides', true);

		foreach ($templates as $template) {
			$conditions = get_post_meta($template->ID, '_schema_template_conditions', true);

			// Check if conditions match (using core condition logic which handles is_author context)
			if (Swift_Rank_Conditions::matches_conditions($conditions)) {
				$schema_data = get_post_meta($template->ID, '_schema_template_data', true);
				if (!$schema_data) {
					continue;
				}

				// Check if Pro schema type is allowed
				if (!$this->is_schema_type_allowed($schema_data['schemaType'])) {
					continue;
				}

				// Apply user overrides if they exist for this template
				if (!empty($overrides) && isset($overrides[$template->ID])) {
					$template_overrides = $overrides[$template->ID];
					if (!isset($schema_data['fields'])) {
						$schema_data['fields'] = array();
					}
					$schema_data['fields'] = array_merge($schema_data['fields'], $template_overrides);
				}

				// Build schema
				$schema = $this->build_schema_from_template($schema_data);

				if (!empty($schema)) {
					$schemas[] = $schema;
				}
			}
		}

		return $schemas;
	}

	/**
	 * Get schema templates that match current conditions
	 *
	 * @return array
	 */
	private function get_template_schemas()
	{
		$schemas = array();

		// Query all published schema templates
		$templates = get_posts(
			array(
				'post_type' => 'sr_template',
				'post_status' => 'publish',
				'posts_per_page' => -1,
			)
		);

		foreach ($templates as $template) {
			$schema_data = get_post_meta($template->ID, '_schema_template_data', true);

			if (empty($schema_data) || empty($schema_data['schemaType'])) {
				continue;
			}

			// Check if Pro schema type is allowed
			if (!$this->is_schema_type_allowed($schema_data['schemaType'])) {
				continue;
			}

			// Check if template should be displayed on current page
			if (!$this->should_display_template($schema_data)) {
				continue;
			}

			// Build schema based on type
			$schema = $this->build_schema_from_template($schema_data);

			if (!empty($schema)) {
				$schemas[] = $schema;
			}
		}

		return $schemas;
	}

	/**
	 * Get auto-generated schema for current page
	 *
	 * @return array
	 */
	private function get_auto_schema()
	{
		$settings = get_option('swift_rank_settings', array());

		// Check if auto-schema should be generated
		$schema_data = Schema_Auto_Generator::should_generate_auto_schema($settings);

		if (!$schema_data) {
			return array();
		}

		// Build the schema
		$schema = $this->build_schema($schema_data['schemaType'], $schema_data['fields']);

		if (empty($schema)) {
			return array();
		}

		return array($schema);
	}

	// ============================================================================
	// SCHEMA BUILDING
	// ============================================================================

	/**
	 * Build schema using registered builders or filters
	 *
	 * @param string $schema_type Schema type.
	 * @param array  $fields      Field values.
	 * @return array
	 */
	public function build_schema($schema_type, $fields)
	{
		$schema = array(
			'@context' => 'https://schema.org',
		);

		// Check if we have a registered builder for this type
		if (isset($this->schema_builders[$schema_type])) {
			$builder = $this->schema_builders[$schema_type];
			$schema = array_merge($schema, $builder->build($fields));
		} else {
			// Allow other plugins to handle this schema type
			$schema = apply_filters('swift_rank_build_schema', $schema, $schema_type, $fields);

			// If not handled by filter (still just context), try generic fallback
			if (count($schema) === 1 && isset($schema['@context'])) {
				// Generic schema type
				$schema['@type'] = $schema_type;
				foreach ($fields as $key => $value) {
					if (!empty($value)) {
						$schema[$key] = $value;
					}
				}
			}
		}

		// Allow modifying the schema before variable replacement
		$schema = apply_filters('swift_rank_output_schema', $schema, $schema_type, $fields);

		// Replace variables with actual values
		$schema_json = wp_json_encode($schema);
		$schema_json = $this->replace_template_variables($schema_json);
		$schema = json_decode($schema_json, true);

		return $schema;
	}

	/**
	 * Build schema from template data
	 *
	 * @param array $schema_data Schema template data.
	 * @return array|null
	 */
	private function build_schema_from_template($schema_data)
	{
		$schema_type = $schema_data['schemaType'];
		$fields = isset($schema_data['fields']) ? $schema_data['fields'] : array();

		$schema = $this->build_schema($schema_type, $fields);

		return $schema;
	}

	/**
	 * Check if template should be displayed on current page
	 *
	 * @param array $schema_data Schema template data.
	 * @return bool
	 */
	private function should_display_template($schema_data)
	{
		$include_conditions = isset($schema_data['includeConditions']) ? $schema_data['includeConditions'] : array();

		// If no conditions are set or groups are empty, don't display
		if (empty($include_conditions)) {
			return false;
		}

		// Check if at least one group has rules
		if (!Swift_Rank_Conditions::has_rules($include_conditions)) {
			return false;
		}

		// Check if conditions match current page
		return Swift_Rank_Conditions::matches_conditions($include_conditions);
	}

	/**
	 * Check if a schema type is allowed based on Pro activation
	 *
	 * @param string $schema_type The schema type to check.
	 * @return bool True if allowed, false if Pro required but not active.
	 */
	private function is_schema_type_allowed($schema_type)
	{
		// If Pro is activated, all types are allowed
		if (defined('SWIFT_RANK_PRO_VERSION')) {
			return true;
		}

		// Check if this is a Pro schema type
		if (in_array($schema_type, $this->pro_schema_types, true)) {
			return false;
		}

		return true;
	}

	/**
	 * Replace template variables in schema JSON
	 *
	 * @param string $json JSON string.
	 * @return string
	 */
	private function replace_template_variables($json)
	{
		if (null === $this->variable_replacer) {
			$this->init_variable_replacer();
		}
		return $this->variable_replacer->replace_variables($json);
	}

	/**
	 * Output JSON-LD script tag
	 *
	 * @param array $schema Schema array.
	 */
	private function output_json_ld($schema)
	{
		if (empty($schema)) {
			return;
		}

		// Remove empty values
		$schema = $this->remove_empty_values($schema);

		// Check if minification is enabled
		$settings = get_option('swift_rank_settings', array());

		$minify = isset($settings['minify_schema']) ? $settings['minify_schema'] : true;

		// Encode to JSON - minified or pretty print based on settings
		$json_flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
		if (!$minify) {
			$json_flags |= JSON_PRETTY_PRINT;
		}
		$json = wp_json_encode($schema, $json_flags);

		if (empty($json)) {
			return;
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		// JSON-LD requires unescaped output. JSON is generated from validated data via wp_json_encode.

		if ($minify) {
			// Minified output - no comments, no extra newlines
			echo '<script type="application/ld+json" class="swift-rank">' . $json . '</script>';
		} else {
			// Pretty output - with comments and newlines for debugging
			echo "\n<!-- Swift Rank -->\n";
			echo '<script type="application/ld+json" class="swift-rank">' . "\n";
			echo $json . "\n";
			echo '</script>' . "\n";
			echo "<!-- /Swift Rank -->\n";
		}

		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	// ============================================================================
	// UTILITY & HELPER METHODS
	// ============================================================================

	/**
	 * Remove empty values from schema array
	 *
	 * @param array $schema Schema array.
	 * @return array
	 */
	private function remove_empty_values($schema)
	{
		foreach ($schema as $key => $value) {
			// Skip @context and @type
			if ('@context' === $key || '@type' === $key) {
				continue;
			}

			if (is_array($value)) {
				$schema[$key] = $this->remove_empty_values($value);

				// Remove array if it's empty after recursive cleaning
				// OR if it's an ImageObject with only @type (no url)
				if (empty($schema[$key])) {
					unset($schema[$key]);
				} elseif (isset($schema[$key]['@type']) && 'ImageObject' === $schema[$key]['@type'] && !isset($schema[$key]['url']) && !isset($schema[$key]['@id'])) {
					// Remove ImageObject that only has @type but no url or @id
					unset($schema[$key]);
				}
			} elseif (empty($value) && '0' !== $value && 0 !== $value) {
				// Remove empty values (but keep '0' and 0)
				unset($schema[$key]);
			}
		}

		return $schema;
	}

	// ============================================================================
	// VALIDATION & DEBUGGING
	// ============================================================================

	/**
	 * Get schema JSON for validation (without HTML wrapper)
	 *
	 * This method builds the schema and returns just the JSON string,
	 * useful for validation, debugging, and testing.
	 *
	 * @param string $schema_type Schema type.
	 * @param array  $fields      Field values.
	 * @param bool   $minify      Whether to minify the JSON.
	 * @return string|false JSON string or false on error.
	 */
	public function get_schema_for_validation($schema_type, $fields, $minify = false)
	{
		$schema = $this->build_schema($schema_type, $fields);

		if (empty($schema)) {
			return false;
		}

		// Remove empty values
		$schema = $this->remove_empty_values($schema);

		// Encode to JSON
		$json_flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
		if (!$minify) {
			$json_flags |= JSON_PRETTY_PRINT;
		}

		return wp_json_encode($schema, $json_flags);
	}

	/**
	 * Get schema.org structure for a specific schema type
	 *
	 * Returns the schema.org specification for the given type,
	 * useful for validation and React field generation.
	 *
	 * @param string $schema_type Schema type.
	 * @return array|false Schema structure or false if not found.
	 */
	public function get_schema_structure($schema_type)
	{
		if (isset($this->schema_builders[$schema_type])) {
			$builder = $this->schema_builders[$schema_type];
			if (method_exists($builder, 'get_schema_structure')) {
				return $builder->get_schema_structure();
			}
		}

		// Allow other plugins to provide structure via filter
		$structure = apply_filters('swift_rank_get_schema_structure', false, $schema_type);

		return $structure;
	}

	/**
	 * Get all available schema structures
	 *
	 * Returns an array of all registered schema type structures,
	 * useful for exposing to React/JavaScript.
	 *
	 * @return array Array of schema structures keyed by schema type.
	 */
	public function get_all_schema_structures()
	{
		$structures = array();

		foreach ($this->schema_builders as $schema_type => $builder) {
			if (method_exists($builder, 'get_schema_structure')) {
				$structures[$schema_type] = $builder->get_schema_structure();
			}
		}

		// Allow other plugins to add structures
		$structures = apply_filters('swift_rank_get_all_schema_structures', $structures);

		return $structures;
	}

	// ============================================================================
	// IMAGE PROCESSING
	// ============================================================================

	/**
	 * Resolve {featured_image} placeholder to ImageObject reference
	 *
	 * @param array  $schema      Schema data.
	 * @param string $schema_type Schema type.
	 * @param array  $fields      Fields data.
	 * @return array Modified schema.
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

		if (!class_exists('Schema_Reference_Resolver')) {
			return $schema;
		}

		$image_id = 0;
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

		// Handle {featured_image}
		if ($variable === '{featured_image}') {
			$post_id = get_the_ID();
			if (has_post_thumbnail($post_id)) {
				$image_id = get_post_thumbnail_id($post_id);
			} elseif ($target_field === 'image') {
				// Fallback to Default Schema Image for main image field (e.g. Article)
				// This ensures we output a proper ImageObject even if featured image is missing
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
		// We re-check is_array because variable might have been extracted above
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


		$resolved = null;
		if ($image_id) {
			$reference = array(
				'id' => $image_id,
				'type' => 'image',
			);

			$resolved = Schema_Reference_Resolver::resolve($reference);

			if ($resolved) {
				$schema[$target_field] = $resolved;
			}
		}

		// Fallback: If not resolved yet (no ID or ID failed) and it's a variable
		if (!$resolved && is_string($variable) && strpos($variable, '{') === 0) {
			// If ID resolution fails but we have a variable (e.g. {site_logo}),
			// try to resolve the variable to a URL and use that to create an ImageObject
			if (!isset($this->variable_replacer)) {
				$this->init_variable_replacer();
			}

			// Wrap variable in JSON to use replacer
			$json_var = wp_json_encode(array('url' => $variable));
			$replaced = $this->variable_replacer->replace_variables($json_var);
			$decoded = json_decode($replaced, true);

			if (is_array($decoded) && !empty($decoded['url']) && $decoded['url'] !== $variable) {
				// We successfully resolved the variable to a URL
				$reference = array(
					'url' => $decoded['url'],
					'type' => 'image', // Resolver handles URL in generic resolved now? No, need to pass it properly.
				);
				// Resolver::resolve handles URL input if we pass array with 'url'
				$resolved = Schema_Reference_Resolver::resolve(array('url' => $decoded['url'], 'type' => 'image'));

				if ($resolved) {
					$schema[$target_field] = $resolved;
				}
			}
		} elseif (!$resolved && is_string($variable) && filter_var($variable, FILTER_VALIDATE_URL)) {
			// If value is a direct URL (not ID, not variable), treat it as an image URL
			// This handles cases where user enters URL directly in settings
			// DEBUG: This block should convert plain URL strings to ImageObject references
			$reference = array(
				'url' => $variable,
				'type' => 'image',
			);
			$resolved = Schema_Reference_Resolver::resolve($reference);

			if ($resolved) {
				$schema[$target_field] = $resolved;
			}
		}

		return $schema;
	}

	/**
	 * Convert URL strings in image/logo fields to ImageObject references
	 * Processes all schemas in the collection
	 *
	 * @param array $schemas Array of schemas.
	 * @return array Modified schemas.
	 */
	private function convert_image_urls_to_references($schemas)
	{
		if (!class_exists('Schema_Reference_Resolver')) {
			return $schemas;
		}

		foreach ($schemas as $index => $schema) {
			// Check for image or logo fields with URL strings
			foreach (array('image', 'logo') as $field) {
				if (isset($schema[$field]) && is_string($schema[$field]) && filter_var($schema[$field], FILTER_VALIDATE_URL)) {
					// Convert URL string to ImageObject reference
					$reference = array(
						'url' => $schema[$field],
						'type' => 'image',
					);
					$resolved = Schema_Reference_Resolver::resolve($reference);

					if ($resolved) {
						$schemas[$index][$field] = $resolved;
					}
				}
			}
		}

		return $schemas;
	}



	/**
	 * Get the default schema image ID from settings
	 *
	 * @return int Image ID or 0 if not set/found.
	 */
	private function get_default_image_id()
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
