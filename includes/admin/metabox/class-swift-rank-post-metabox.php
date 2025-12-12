<?php
/**
 * Post Schema Metabox Class
 *
 * Adds schema metabox to posts/pages to display matching templates
 * and allow field overrides.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Swift_Rank_Post_Metabox class
 *
 * Handles the schema metabox for posts and pages.
 */
class Swift_Rank_Post_Metabox
{

	/**
	 * Instance of this class
	 *
	 * @var Swift_Rank_Post_Metabox
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Swift_Rank_Post_Metabox
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
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_meta_data'), 10, 2);
		add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
	}

	/**
	 * Add metaboxes only when templates match the current post
	 */
	public function add_meta_boxes()
	{
		global $post;

		if (!$post) {
			return;
		}

		$post_type = $post->post_type;

		// Skip sr_template and attachment.
		if (in_array($post_type, array('sr_template', 'attachment'), true)) {
			return;
		}

		// Always show metabox.
		add_meta_box(
			'swift_rank_post_metabox',
			__('Swift Rank', 'swift-rank'),
			array($this, 'render_metabox'),
			$post_type,
			'normal',
			'default'
		);
	}

	/**
	 * Render the metabox
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_metabox($post)
	{
		wp_nonce_field('swift_rank_post_metabox', 'swift_rank_post_nonce');

		// We proceed even if no templates match, to allow the React app to render notices
		// and potentially other UI elements (like global pro upgrade prompts)

		// Get saved overrides.
		$saved_overrides = get_post_meta($post->ID, '_swift_rank_overrides', true);
		$overrides_json = $saved_overrides ? wp_json_encode($saved_overrides) : '{}';

		// Hidden input for storing overrides - this is critical for form submission.
		echo '<input type="hidden" id="swift-rank-overrides-input" name="_swift_rank_overrides" value="' . esc_attr($overrides_json) . '" />';

		// Render the React root.
		echo '<div id="swift-rank-post-metabox-root" data-post-id="' . esc_attr($post->ID) . '"></div>';
	}

	/**
	 * Enqueue assets for post metabox
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_assets($hook)
	{
		global $post, $post_type;

		// Only load on post edit screens (not sr_template).
		if (('post.php' !== $hook && 'post-new.php' !== $hook) || 'sr_template' === $post_type) {
			return;
		}

		if (!$post) {
			return;
		}




		// Always enqueue assets, the React app handles empty template states


		$asset_file = SWIFT_RANK_PLUGIN_DIR . 'build/post-metabox/index.asset.php';

		if (file_exists($asset_file)) {
			$asset = require $asset_file;

			// Enqueue WordPress media.
			wp_enqueue_media();

			wp_enqueue_script(
				'swift-rank-post-metabox',
				SWIFT_RANK_PLUGIN_URL . 'build/post-metabox/index.js',
				$asset['dependencies'],
				$asset['version'],
				true
			);

			wp_enqueue_style(
				'swift-rank-post-metabox',
				SWIFT_RANK_PLUGIN_URL . 'build/post-metabox/style-index.css',
				array('wp-components'),
				$asset['version']
			);

			// Get saved field overrides for this post.
			$saved_overrides = get_post_meta($post->ID, '_swift_rank_overrides', true);

			// Get schema types with fields for the React component.
			$schema_types = Schema_Type_Helper::get_types_for_select();

			// Get variable groups from variable replacer
			require_once SWIFT_RANK_PLUGIN_DIR . 'includes/utils/class-schema-variable-replacer.php';
			$replacer_class = apply_filters('swift_rank_variable_replacer_class', 'Schema_Variable_Replacer');
			if (class_exists($replacer_class)) {
				$variable_replacer = new $replacer_class();
			} else {
				$variable_replacer = new Schema_Variable_Replacer();
			}
			$variable_groups = $variable_replacer->get_variable_groups();

			wp_localize_script(
				'swift-rank-post-metabox',
				'swiftRankPostMetabox',
				array(
					'postId' => $post->ID,
					'postType' => $post_type,
					'matchingTemplates' => Schema_Template_Loader::get_instance()->get_templates_for_post($post->ID),
					'savedOverrides' => $saved_overrides ? $saved_overrides : array(),
					'nonce' => wp_create_nonce('swift_rank_post_metabox'),
					'schemaTypes' => $schema_types,
					'variableGroups' => $variable_groups,
					'isProActivated' => defined('SWIFT_RANK_PRO_VERSION'),
				)
			);

			// Also set global config for field renderers
			wp_localize_script(
				'swift-rank-post-metabox',
				'swiftRankData',
				array(
					'templatesUrl' => admin_url('edit.php?post_type=sr_template'),
					'newTemplateUrl' => admin_url('post-new.php?post_type=sr_template'),
				)
			);

			// Also set global config for field renderers
			wp_localize_script(
				'swift-rank-post-metabox',
				'swiftRankConfig',
				array(
					'isProActivated' => defined('SWIFT_RANK_PRO_VERSION'),
					'upgradeUrl' => apply_filters('swift_rank_upgrade_url', 'https://toolpress.net/swift-rank/pricing'),
				)
			);
		}
	}






	/**
	 * Save meta data - only store field overrides
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function save_meta_data($post_id, $post)
	{
		// Don't save for sr_template.
		if ('sr_template' === $post->post_type) {
			return;
		}

		// Check nonce.
		if (!isset($_POST['swift_rank_post_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['swift_rank_post_nonce'])), 'swift_rank_post_metabox')) {
			return;
		}

		// Check autosave.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// Check permissions.
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		// Save schema field overrides (only changed fields).
		if (isset($_POST['_swift_rank_overrides'])) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- JSON decoded and sanitized below.
			$overrides = json_decode(wp_unslash($_POST['_swift_rank_overrides']), true);
			if (is_array($overrides)) {
				// Sanitize recursively.
				$overrides = $this->sanitize_schema_data($overrides);
				// Only save if there are actual overrides.
				if (!empty($overrides)) {
					update_post_meta($post_id, '_swift_rank_overrides', $overrides);
				} else {
					delete_post_meta($post_id, '_swift_rank_overrides');
				}
			}
		}
	}

	/**
	 * Sanitize schema data recursively
	 *
	 * @param mixed $data Data to sanitize.
	 * @param bool  $is_top_level Whether this is the top level of the data structure.
	 * @return mixed
	 */
	private function sanitize_schema_data($data, $is_top_level = true)
	{
		if (is_array($data)) {
			$sanitized = array();
			foreach ($data as $key => $value) {
				// Keep template ID keys as strings at top level (they come from JS as strings).
				if ($is_top_level && is_numeric($key)) {
					$sanitized_key = (string) $key;
				} elseif (is_numeric($key)) {
					$sanitized_key = (int) $key;
				} else {
					// Sanitize key but retain letter case for schema field names.
					// Remove non-alphanumeric characters except underscores, hyphens, and periods.
					$sanitized_key = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $key);
				}
				$sanitized[$sanitized_key] = $this->sanitize_schema_data($value, false);
			}
			return $sanitized;
		}

		if (is_string($data)) {
			// Allow URLs and variables.
			if (filter_var($data, FILTER_VALIDATE_URL) || preg_match('/\{[^}]+\}/', $data)) {
				return $data;
			}
			return sanitize_text_field($data);
		}

		if (is_bool($data) || is_int($data) || is_float($data)) {
			return $data;
		}

		return '';
	}
}
