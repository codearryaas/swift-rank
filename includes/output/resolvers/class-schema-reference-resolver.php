<?php
/**
 * Schema Reference Resolver Class
 *
 * Resolves reference objects to @id values for connected graph structure.
 * Handles resolution of relationships between schema entities.
 *
 * @package Swift_Rank_Pro
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Schema_Reference_Resolver class
 */
if (!class_exists('Schema_Reference_Resolver')) {
	class Schema_Reference_Resolver
	{

		/**
		 * Store user Person schemas that need to be added to the graph
		 *
		 * @var array
		 */
		private static $user_schemas = array();

		/**
		 * Store extra schemas that need to be added to the graph (e.g. referenced KB entities)
		 *
		 * @var array
		 */
		private static $extra_schemas = array();

		/**
		 * Initialize the resolver
		 */
		public static function init()
		{
			// Hook into schema output to add user Person schemas to the graph
			add_filter('swift_rank_schemas', array(__CLASS__, 'add_user_schemas_to_graph'), 20);
			// Hook to add extra schemas (KB entities)
			add_filter('swift_rank_schemas', array(__CLASS__, 'add_extra_schemas_to_graph'), 20);
		}

		/**
		 * Add user Person schemas to the graph
		 *
		 * @param array $schemas Existing schemas.
		 * @return array Modified schemas with user Person schemas added.
		 */
		public static function add_user_schemas_to_graph($schemas)
		{
			$user_schemas = self::get_user_schemas();

			if (!empty($user_schemas)) {
				// Filter out duplicates (check by @id)
				foreach ($user_schemas as $user_schema) {
					if (isset($user_schema['@id'])) {
						// Check if ID already exists in main schemas
						$exists = false;
						foreach ($schemas as $existing_schema) {
							if (isset($existing_schema['@id']) && $existing_schema['@id'] === $user_schema['@id']) {
								$exists = true;
								break;
							}
						}
						if (!$exists) {
							$schemas[] = $user_schema;
						}
					} else {
						$schemas[] = $user_schema;
					}
				}

				// Clear after adding
				self::clear_user_schemas();
			}

			return $schemas;
		}

		/**
		 * Add extra schemas to the graph
		 *
		 * @param array $schemas Existing schemas.
		 * @return array Modified schemas.
		 */
		public static function add_extra_schemas_to_graph($schemas)
		{
			if (!empty(self::$extra_schemas)) {
				// Filter out duplicates
				foreach (self::$extra_schemas as $extra_schema) {
					if (isset($extra_schema['@id'])) {
						$exists = false;
						foreach ($schemas as $existing_schema) {
							if (isset($existing_schema['@id']) && $existing_schema['@id'] === $extra_schema['@id']) {
								$exists = true;
								break;
							}
						}
						if (!$exists) {
							$schemas[] = $extra_schema;
						}
					}
				}
				self::$extra_schemas = array();
			}
			return $schemas;
		}

		/**
		 * Resolve a reference object to an @id value
		 *
		 * @param mixed $value The field value (could be string, array, or reference object).
		 * @return mixed The resolved value (string, @id reference, or inline object).
		 */
		public static function resolve($value)
		{
			// If not an array or doesn't have 'type' key, return as-is
			if (!is_array($value) || !isset($value['type'])) {
				return $value;
			}

			// Check if it's a reference type OR a direct image object (from hybrid field)
			// Direct image object: {id: 123, url: '...'} or {url: '...'}
			if (isset($value['type']) && $value['type'] === 'reference') {
				// Standard reference logic
				$source = isset($value['source']) ? $value['source'] : '';
				$id = isset($value['id']) ? $value['id'] : null;
			} elseif (isset($value['url']) || isset($value['id'])) {
				// Direct image/media object
				// If ID exists, treat as media reference
				if (!empty($value['id'])) {
					$source = 'media';
					$id = $value['id'];
				} elseif (!empty($value['url'])) {
					// URL only (custom input)
					// Treat as media reference using URL as ID
					return self::resolve_url_reference($value['url']);
				} else {
					return null;
				}
			} else {
				// Unknown format
				return $value;
			}

			if (empty($source) || empty($id)) {
				return null;
			}

			switch ($source) {
				case 'user':
					return self::resolve_user_reference($id);

				case 'media':
					return self::resolve_media_reference($id);


				case 'post':
					return self::resolve_post_reference($id);

				case 'schema_template':
					return self::resolve_schema_template_reference($id);

				case 'knowledge_base':
					return self::resolve_knowledge_base_reference($id);

				default:
					return null;
			}
		}

		/**
		 * Resolve a user reference to @id
		 *
		 * @param int $user_id User ID.
		 * @return array|null @id reference or null if user not found.
		 */
		private static function resolve_user_reference($user_id)
		{
			// Handle variable replacement for post author
			if ($user_id === '{post_author_id}') {
				$post_id = get_the_ID();
				$post = get_post($post_id);
				if ($post) {
					$user_id = $post->post_author;
				}
			}

			$user = get_user_by('ID', $user_id);

			if (!$user) {
				return null;
			}

			// Check if Schema_Reference_Manager exists (from base plugin)
			if (!class_exists('Schema_Reference_Manager')) {
				return null;
			}

			$user_id_ref = Schema_Reference_Manager::get_user_id($user_id);

			// Build Person schema for this user and store it for graph output
			$person_schema = array(
				'@type' => 'Person',
				'@id' => $user_id_ref,
				'name' => $user->display_name,
			);

			// Add author URL if exists
			$author_url = get_author_posts_url($user_id);
			if ($author_url) {
				$person_schema['url'] = $author_url;
			}

			// Add email if available (privacy consideration - only if user opted in)
			// You could add a user meta check here for privacy
			// if (get_user_meta($user_id, 'show_email_in_schema', true)) {
			//     $person_schema['email'] = $user->user_email;
			// }

			// Add description if available
			$description = get_user_meta($user_id, 'description', true);
			if (!empty($description)) {
				$person_schema['description'] = $description;
			}

			// Add avatar/image
			$avatar_url = get_avatar_url($user_id, array('size' => 512));
			if ($avatar_url) {
				$person_schema['image'] = array(
					'@type' => 'ImageObject',
					'url' => $avatar_url,
				);
			}

			// Store this schema to be added to graph (keyed by user_id to avoid duplicates)
			self::$user_schemas[$user_id] = $person_schema;

			// Return just the @id reference for the field
			return array(
				'@type' => 'Person',
				'@id' => $user_id_ref,
			);
		}

		/**
		 * Resolve a media reference to an ImageObject
		 *
		 * @param int $media_id Attachment ID.
		 * @return array|null ImageObject schema or null if not found.
		 */
		private static function resolve_media_reference($media_id)
		{
			$attachment = get_post($media_id);

			if (!$attachment || $attachment->post_type !== 'attachment') {
				return null;
			}

			$url = wp_get_attachment_url($media_id);
			if (!$url) {
				return null;
			}

			$schema = array(
				'@type' => 'ImageObject',
				'@id' => $url, // Use URL as ID for images usually
				'url' => $url,
				'contentUrl' => $url,
			);

			// Add name/caption
			if (!empty($attachment->post_title)) {
				$schema['name'] = $attachment->post_title;
			}
			if (!empty($attachment->post_excerpt)) {
				$schema['caption'] = $attachment->post_excerpt;
			}
			if (!empty($attachment->post_content)) {
				$schema['description'] = $attachment->post_content;
			}

			// Add dimensions
			$meta = wp_get_attachment_metadata($media_id);
			if (!empty($meta['width'])) {
				$schema['width'] = $meta['width'];
			}
			if (!empty($meta['height'])) {
				$schema['height'] = $meta['height'];
			}


			// Add to extra schemas queue to ensure it's in the graph
			self::$extra_schemas[] = $schema;

			return array(
				'@type' => 'ImageObject',
				'@id' => $url,
			);
		}




		/**
		 * Resolve a URL-only reference to an ImageObject
		 *
		 * @param string $url Image URL.
		 * @return array ImageObject reference.
		 */
		private static function resolve_url_reference($url)
		{
			$schema = array(
				'@type' => 'ImageObject',
				'@id' => $url,
				'url' => $url,
				'contentUrl' => $url,
			);

			// Add to extra schemas queue to ensure it's in the graph
			self::$extra_schemas[] = $schema;

			return array(
				'@type' => 'ImageObject',
				'@id' => $url,
			);
		}

		/**
		 * Resolve a post reference to @id
		 *
		 * @param int $post_id Post ID.
		 * @return array|null @id reference or null if post not found.
		 */
		private static function resolve_post_reference($post_id)
		{
			$post = get_post($post_id);

			if (!$post) {
				return null;
			}

			// Check if Schema_Reference_Manager exists (from base plugin)
			if (!class_exists('Schema_Reference_Manager')) {
				return null;
			}

			// Generate @id for the post (could be article, product, etc.)
			$post_type_object = get_post_type_object($post->post_type);
			$schema_type = 'Article'; // Default

			// You could add mapping logic here based on post type
			// For now, default to Article for posts
			if ($post->post_type === 'post') {
				$schema_type = 'Article';
			}

			return array(
				'@type' => $schema_type,
				'@id' => Schema_Reference_Manager::get_id($schema_type, $post_id),
			);
		}

		/**
		 * Resolve a schema template reference to @id
		 *
		 * @param int $template_id Template post ID.
		 * @return array|null @id reference or null if template not found.
		 */
		private static function resolve_schema_template_reference($template_id)
		{
			$template = get_post($template_id);

			if (!$template || $template->post_type !== 'sr_template') {
				return null;
			}

			$schema_type = get_post_meta($template_id, '_schema_type', true);

			if (empty($schema_type)) {
				return null;
			}

			// Check if Schema_Reference_Manager exists (from base plugin)
			if (!class_exists('Schema_Reference_Manager')) {
				return null;
			}

			// Generate a clean ID for the template reference
			// Use home URL + template slug for a clean, stable ID
			// Example: http://site.com/template-slug/#organization
			$template_slug = $template->post_name;
			$ref_id = home_url('/' . $template_slug . '/#' . strtolower($schema_type));

			// Get template data (stored as array with 'schemaType' and 'fields')
			$template_data = get_post_meta($template_id, '_schema_template_data', true);

			if (!is_array($template_data) || empty($template_data['fields'])) {
				// If no fields data, just return the reference
				return array(
					'@type' => $schema_type,
					'@id' => $ref_id,
				);
			}

			// Build the full schema object from template fields
			$schema = array(
				'@type' => $schema_type,
				'@id' => $ref_id,
			);

			// Add all fields from template
			foreach ($template_data['fields'] as $field_name => $field_value) {
				// Skip empty values and internal fields
				if ($field_value === null || $field_value === '' || strpos($field_name, '_') === 0) {
					continue;
				}

				$schema[$field_name] = $field_value;
			}

			// Handle variable replacement in the schema
			// This ensures global variables like {site_name} are resolved
			$schema = self::replace_variables($schema);

			// Add to extra schemas queue to ensure it's in the graph
			self::$extra_schemas[] = $schema;

			return array(
				'@type' => $schema_type,
				'@id' => $ref_id,
			);
		}

		/**
		 * Resolve a knowledge base reference to @id or inline schema
		 *
		 * @param string $key Knowledge base key (typically 'knowledge_base').
		 * @return array|null @id reference or inline schema, or null if not found.
		 */
		private static function resolve_knowledge_base_reference($key)
		{
			// Check if Schema_Reference_Manager exists (from base plugin)
			if (!class_exists('Schema_Reference_Manager')) {
				return null;
			}

			// Get knowledge base settings
			$settings = get_option('swift_rank_settings', array());

			// Ensure we always have an entity to output if referenced, regardless of enabled status
			// But basic check if settings exist
			if (empty($settings)) {
				return null;
			}

			$kb_type = isset($settings['knowledge_base_type']) ? $settings['knowledge_base_type'] : 'Organization';
			$kb_schema = null;
			$kb_ref = null;

			// Build schema and reference based on type
			if ($kb_type === 'Organization') {
				$kb_ref = array(
					'@type' => 'Organization',
					'@id' => Schema_Reference_Manager::get_organization_id(),
				);
				$kb_schema = $kb_ref; // Start with ID/Type
				$kb_fields = isset($settings['organization_fields']) ? $settings['organization_fields'] : array();
			} elseif ($kb_type === 'Person') {
				$kb_ref = array(
					'@type' => 'Person',
					'@id' => home_url('/#person'),
				);
				$kb_schema = $kb_ref;
				$kb_fields = isset($settings['person_fields']) ? $settings['person_fields'] : array();
			} elseif ($kb_type === 'LocalBusiness') {
				$kb_ref = array(
					'@type' => 'LocalBusiness',
					'@id' => home_url('/#localbusiness'),
				);
				$kb_schema = $kb_ref;
				$kb_fields = isset($settings['localbusiness_fields']) ? $settings['localbusiness_fields'] : array();
			}

			if ($kb_schema && isset($kb_fields)) {
				// Use the base plugin's schema builder to ensure proper structuring
				// This ensures logo becomes ImageObject, address becomes PostalAddress, etc.
				if (class_exists('Schema_Output_Handler')) {
					$output_handler = Schema_Output_Handler::get_instance();
					$built_schema = $output_handler->build_schema($kb_type, $kb_fields);

					// Merge the built schema (which has proper structure) with our @id
					if (!empty($built_schema)) {
						// Remove @context if present (we only need it in the graph root)
						unset($built_schema['@context']);
						$kb_schema = array_merge($kb_schema, $built_schema);
					}
				} else {
					// Fallback: Add all fields from KB settings if handler not available
					foreach ($kb_fields as $field_name => $field_value) {
						// Skip empty values
						if ($field_value === null || $field_value === '' || (is_array($field_value) && empty($field_value))) {
							continue;
						}

						$kb_schema[$field_name] = $field_value;
					}
				}

				// Variable replacement is already done by build_schema, but apply again to be safe
				$kb_schema = self::replace_variables($kb_schema);

				// Add to extra schemas queue to ensure it's in the graph
				self::$extra_schemas[] = $kb_schema;
			}

			// ALWAYS return the @id reference
			return $kb_ref;
		}

		/**
		 * Recursively process schema array to resolve all reference fields
		 *
		 * @param array $schema Schema array.
		 * @return array Processed schema with resolved references.
		 */
		public static function process_schema($schema)
		{
			if (!is_array($schema)) {
				return $schema;
			}

			foreach ($schema as $key => $value) {
				if (is_array($value)) {
					// Check if this is a reference object
					if (isset($value['type']) && $value['type'] === 'reference') {
						$resolved = self::resolve($value);
						if ($resolved !== null) {
							$schema[$key] = $resolved;
						} else {
							// If resolution fails, remove the field
							unset($schema[$key]);
						}
					} else {
						// Recursively process nested arrays
						$schema[$key] = self::process_schema($value);
					}
				}
			}

			return $schema;
		}

		/**
		 * Check if a value is a reference object
		 *
		 * @param mixed $value Value to check.
		 * @return bool True if it's a reference object.
		 */
		public static function is_reference($value)
		{
			return is_array($value) && isset($value['type']) && $value['type'] === 'reference';
		}

		/**
		 * Get all user Person schemas that need to be added to the graph
		 *
		 * @return array Array of Person schemas.
		 */
		public static function get_user_schemas()
		{
			return array_values(self::$user_schemas);
		}

		/**
		 * Replace variables in a schema array
		 *
		 * @param array $schema Schema array.
		 * @return array Schema with variables replaced.
		 */
		private static function replace_variables($schema)
		{
			// Load variable replacer if not already loaded
			if (!class_exists('Schema_Variable_Replacer')) {
				// Try to find the file
				$plugin_dir = defined('SWIFT_RANK_PLUGIN_DIR') ? SWIFT_RANK_PLUGIN_DIR : WP_PLUGIN_DIR . '/swift-rank/';
				if (file_exists($plugin_dir . 'includes/utils/class-schema-variable-replacer.php')) {
					require_once $plugin_dir . 'includes/utils/class-schema-variable-replacer.php';
				}
			}

			// Get replacer class (Pro plugin may override)
			$replacer_class = apply_filters('swift_rank_variable_replacer_class', 'Schema_Variable_Replacer');

			if (!class_exists($replacer_class)) {
				return $schema;
			}

			// Create replacer instance
			$replacer = new $replacer_class();

			// Wrap string in JSON to use replace_variables method (it works on JSON strings)
			$json = wp_json_encode($schema);

			// If encoding failed, return original
			if (!$json) {
				return $schema;
			}

			$replaced_json = $replacer->replace_variables($json);
			$decoded = json_decode($replaced_json, true);

			return is_array($decoded) ? $decoded : $schema;
		}

		/**
		 * Clear user schemas (called after output)
		 */
		public static function clear_user_schemas()
		{
			self::$user_schemas = array();
		}
	}


}

// Initialize the resolver
if (class_exists('Schema_Reference_Resolver')) {
	Schema_Reference_Resolver::init();
}
