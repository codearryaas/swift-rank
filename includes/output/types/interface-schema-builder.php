<?php
/**
 * Schema Builder Interface
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Interface Schema_Builder_Interface
 *
 * Defines the contract for schema builders.
 */
interface Schema_Builder_Interface
{
	/**
	 * Build schema array from fields
	 *
	 * @param array $fields Field values.
	 * @return array Schema array (without @context).
	 */
	public function build($fields);

	/**
	 * Get schema.org structure/specification for this schema type
	 *
	 * Returns the schema.org specification including:
	 * - @type: Schema type name
	 * - properties: Array of property definitions with:
	 *   - name: Property name
	 *   - description: What this property represents
	 *   - type: Expected value type (Text, URL, ImageObject, etc.)
	 *   - required: Whether this property is required
	 *   - expectedType: Schema.org expected type
	 *
	 * @return array Schema.org structure specification.
	 */
	public function get_schema_structure();
}
