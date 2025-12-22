<?php
/**
 * Blocks Class
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Swift_Rank_Blocks class
 *
 * Handles block registration and enqueuing.
 */
class Swift_Rank_Blocks
{

	/**
	 * Instance of this class
	 *
	 * @var Swift_Rank_Blocks
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Swift_Rank_Blocks
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
		add_action('init', array($this, 'register_blocks'));
	}

	/**
	 * Register blocks
	 */
	public function register_blocks()
	{
		// Register FAQ block.
		$this->register_block('faq');

		// Register FAQ Item block.
		$this->register_block('faq-item');

		// Register HowTo block.
		// $this->register_block('howto');

		// Register HowTo Step block.
		// $this->register_block('howto-step');

		// Initialize FAQ block server-side rendering.
		require_once SWIFT_RANK_PLUGIN_DIR . 'includes/blocks/class-faq-block.php';
		FAQ_Block::get_instance();

		// Initialize HowTo block server-side rendering.
		// require_once SWIFT_RANK_PLUGIN_DIR . 'includes/blocks/class-howto-block.php';
		// HowTo_Block::get_instance();
	}

	/**
	 * Register a single block
	 *
	 * @param string $block_name Block name without namespace.
	 */
	private function register_block($block_name)
	{
		$build_path = SWIFT_RANK_PLUGIN_DIR . 'build/blocks/' . $block_name . '/index.js';
		$asset_path = SWIFT_RANK_PLUGIN_DIR . 'build/blocks/' . $block_name . '/index.asset.php';
		$style_path = SWIFT_RANK_PLUGIN_DIR . 'build/blocks/' . $block_name . '/index.css';
		$editor_path = SWIFT_RANK_PLUGIN_DIR . 'build/blocks/' . $block_name . '/style-index.css';
		$view_path = SWIFT_RANK_PLUGIN_DIR . 'build/blocks/' . $block_name . '/view.js';
		$view_asset_path = SWIFT_RANK_PLUGIN_DIR . 'build/blocks/' . $block_name . '/view.asset.php';

		// Check if build file exists.
		if (!file_exists($build_path)) {
			return;
		}

		// Load asset file.
		$asset = file_exists($asset_path) ? require $asset_path : array(
			'dependencies' => array(),
			'version' => SWIFT_RANK_VERSION,
		);

		// Register block script.
		wp_register_script(
			'swift-rank-block-' . $block_name,
			SWIFT_RANK_PLUGIN_URL . 'build/blocks/' . $block_name . '/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		// Register view script if it exists.
		$view_script_handle = null;
		if (file_exists($view_path)) {
			$view_asset = file_exists($view_asset_path) ? require $view_asset_path : array(
				'dependencies' => array(),
				'version' => SWIFT_RANK_VERSION,
			);

			wp_register_script(
				'swift-rank-block-' . $block_name . '-view',
				SWIFT_RANK_PLUGIN_URL . 'build/blocks/' . $block_name . '/view.js',
				$view_asset['dependencies'],
				$view_asset['version'],
				true
			);

			$view_script_handle = 'swift-rank-block-' . $block_name . '-view';
		}

		// Register editor style.
		if (file_exists($style_path)) {
			wp_register_style(
				'swift-rank-block-' . $block_name . '-editor',
				SWIFT_RANK_PLUGIN_URL . 'build/blocks/' . $block_name . '/index.css',
				array('wp-edit-blocks'),
				$asset['version']
			);
		}

		// Register frontend style.
		if (file_exists($editor_path)) {
			wp_register_style(
				'swift-rank-block-' . $block_name,
				SWIFT_RANK_PLUGIN_URL . 'build/blocks/' . $block_name . '/style-index.css',
				array(),
				$asset['version']
			);
		}

		// Build args for register_block_type.
		$block_args = array(
			'editor_script' => 'swift-rank-block-' . $block_name,
			'editor_style' => 'swift-rank-block-' . $block_name . '-editor',
			'style' => 'swift-rank-block-' . $block_name,
		);

		if ($view_script_handle) {
			$block_args['view_script'] = $view_script_handle;
		}

		// Register block type.
		register_block_type(
			SWIFT_RANK_PLUGIN_DIR . 'build/blocks/' . $block_name,
			$block_args
		);
	}
}
