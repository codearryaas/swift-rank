<?php
/**
 * Product Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_Product class
 *
 * Builds Product schema type.
 */
class Schema_Product implements Schema_Builder_Interface
{

	/**
	 * Build product schema from fields
	 *
	 * @param array $fields Field values.
	 * @return array Schema array (without @context).
	 */
	public function build($fields)
	{
		$schema = array(
			'@type' => 'Product',
			'name' => isset($fields['name']) ? $fields['name'] : '{post_title}',
			'url' => isset($fields['url']) ? $fields['url'] : '{post_url}',
		);

		if (!empty($fields['description'])) {
			$schema['description'] = $fields['description'];
		}

		// Image fallback logic (mirrors Organization logo logic)
		$image_url = (isset($fields['image']) && !empty($fields['image'])) ? $fields['image'] : '';

		if (empty($image_url) && defined('SWIFT_RANK_PRO_VERSION')) {
			$settings = get_option('swift_rank_settings', array());
			if (!empty($settings['default_image'])) {
				$image_url = $settings['default_image'];
			}
		}

		if (empty($image_url)) {
			$image_url = '{featured_image}';
		}

		if (!empty($image_url)) {
			$schema['image'] = $image_url;
		}

		if (!empty($fields['sku'])) {
			$schema['sku'] = $fields['sku'];
		}

		if (!empty($fields['brand'])) {
			$schema['brand'] = array(
				'@type' => 'Brand',
				'name' => $fields['brand'],
			);
		}

		if (!empty($fields['price'])) {
			$availability = isset($fields['availability']) ? $fields['availability'] : 'InStock';

			// Map WooCommerce stock statuses and other formats to Schema.org availability
			$availability_map = array(
				// WooCommerce stock statuses (lowercase)
				'instock' => 'InStock',
				'outofstock' => 'OutOfStock',
				'onbackorder' => 'PreOrder',
				// Schema.org values (already correct)
				'InStock' => 'InStock',
				'OutOfStock' => 'OutOfStock',
				'PreOrder' => 'PreOrder',
				'Discontinued' => 'Discontinued',
				'LimitedAvailability' => 'LimitedAvailability',
				'OnlineOnly' => 'OnlineOnly',
				'PreSale' => 'PreSale',
				'SoldOut' => 'SoldOut',
			);

			// Normalize the availability value (case-insensitive lookup)
			$availability_lower = strtolower($availability);
			$availability_normalized = isset($availability_map[$availability_lower]) ? $availability_map[$availability_lower] : $availability;

			// If it's already a full URL, use it as-is, otherwise prepend schema.org
			if (strpos($availability_normalized, 'http') === 0) {
				$availability_url = $availability_normalized;
			} else {
				$availability_url = 'https://schema.org/' . $availability_normalized;
			}

			$schema['offers'] = array(
				'@type' => 'Offer',
				'price' => $fields['price'],
				'priceCurrency' => isset($fields['priceCurrency']) ? $fields['priceCurrency'] : 'USD',
				'availability' => $availability_url,
			);
		}

		return $schema;
	}

	/**
	 * Get schema.org structure for Product type
	 *
	 * @return array Schema.org structure specification.
	 */
	public function get_schema_structure()
	{
		return array(
			'@type' => 'Product',
			'@context' => 'https://schema.org',
			'label' => __('Product', 'swift-rank'),
			'description' => __('Any offered product or service.', 'swift-rank'),
			'url' => 'https://schema.org/Product',
			'icon' => 'shopping-bag',
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
				'label' => __('Product Name', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Product name. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{post_title}',
				'options' => array(
					array(
						'label' => __('Product Title', 'swift-rank'),
						'value' => '{post_title}',
					),
				),
				'default' => '{post_title}',
				'required' => true,
			),
			array(
				'name' => 'url',
				'label' => __('Product URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Product page URL. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{post_url}',
				'options' => array(
					array(
						'label' => __('Product URL', 'swift-rank'),
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
				'tooltip' => __('Product description. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{post_excerpt}',
				'options' => array(
					array(
						'label' => __('Product Excerpt', 'swift-rank'),
						'value' => '{post_excerpt}',
					),
					array(
						'label' => __('Product Content', 'swift-rank'),
						'value' => '{post_content}',
					),
				),
				'default' => '{post_excerpt}',
			),
			array(
				'name' => 'image',
				'label' => __('Product Image URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'image',
				'returnObject' => true,
				'tooltip' => __('Product image. Select from list or click pencil to upload custom image.', 'swift-rank'),
				'placeholder' => '{featured_image}',
				'options' => array(
					array(
						'label' => __('Product Image', 'swift-rank'),
						'value' => '{featured_image}',
					),
				),
				'default' => '{featured_image}',
				'required' => true,
			),
			array(
				'name' => 'sku',
				'label' => __('SKU', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Stock Keeping Unit. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => 'ABC123',
				'options' => array(
					array(
						'label' => __('Product SKU (WooCommerce)', 'swift-rank'),
						'value' => '{woo_product_sku}',
					),
				),
			),
			array(
				'name' => 'brand',
				'label' => __('Brand', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Product brand name. Click pencil icon to enter custom value.', 'swift-rank'),
				'placeholder' => 'Brand Name',
				'options' => array(
					array(
						'label' => __('Product Brand (WooCommerce)', 'swift-rank'),
						'value' => '{woo_product_brand}',
					),
					array(
						'label' => __('Site Title', 'swift-rank'),
						'value' => '{site_name}',
					),
				),
			),
			array(
				'name' => 'price',
				'label' => __('Price', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Product price (numeric value). Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '29.99',
				'options' => array(
					array(
						'label' => __('Product Price (WooCommerce)', 'swift-rank'),
						'value' => '{woo_product_price}',
					),
				),
			),
			array(
				'name' => 'priceCurrency',
				'label' => __('Currency Code', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('ISO 4217 currency code. Click pencil icon to enter custom code.', 'swift-rank'),
				'placeholder' => 'USD',
				'options' => array(
					array(
						'label' => __('USD - US Dollar', 'swift-rank'),
						'value' => 'USD',
					),
					array(
						'label' => __('EUR - Euro', 'swift-rank'),
						'value' => 'EUR',
					),
					array(
						'label' => __('GBP - British Pound', 'swift-rank'),
						'value' => 'GBP',
					),
					array(
						'label' => __('Currency (WooCommerce)', 'swift-rank'),
						'value' => '{woo_product_currency}',
					),
				),
				'default' => 'USD',
			),
			array(
				'name' => 'availability',
				'label' => __('Availability', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Product availability status. Click pencil icon to use variables like {woo_product_stock_status}.', 'swift-rank'),
				'placeholder' => '{woo_product_stock_status}',
				'options' => array(
					array(
						'label' => __('In Stock', 'swift-rank'),
						'value' => 'InStock',
					),
					array(
						'label' => __('Out of Stock', 'swift-rank'),
						'value' => 'OutOfStock',
					),
					array(
						'label' => __('Pre-Order', 'swift-rank'),
						'value' => 'PreOrder',
					),
					array(
						'label' => __('Discontinued', 'swift-rank'),
						'value' => 'Discontinued',
					),
					array(
						'label' => __('Limited Availability', 'swift-rank'),
						'value' => 'LimitedAvailability',
					),
					array(
						'label' => __('Sold Out', 'swift-rank'),
						'value' => 'SoldOut',
					),
					array(
						'label' => __('Product Stock Status (WooCommerce)', 'swift-rank'),
						'value' => '{woo_product_stock_status}',
					),
				),
				'default' => 'InStock',
			),
		);
	}

}
