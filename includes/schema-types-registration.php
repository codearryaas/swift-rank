<?php
/**
 * Schema Types Registration
 *
 * Registers all schema types from the base plugin.
 * This file hooks into the swift_rank_register_types filter.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register base plugin schema types
 *
 * @param array $types Existing schema types.
 * @return array Modified schema types.
 */
function swift_rank_register_base_types($types)
{
    $types_dir = SWIFT_RANK_PLUGIN_DIR . 'includes/output/types/';
    $builder_files = glob($types_dir . 'class-*-schema.php');

    // Define Pro schema types that require Pro license
    // Load Pro types from centralized config
    $pro_schema_types = class_exists('Schema_Types_Config') ? Schema_Types_Config::get_pro_types() : array();

    // Check if Pro is activated
    $is_pro_activated = defined('SWIFT_RANK_PRO_VERSION');

    foreach ($builder_files as $file) {
        $basename = basename($file, '.php');

        // Convert class-article-schema to Article_Schema
        $class_name = str_replace('class-', '', $basename);
        $class_name = str_replace('-schema', '', $class_name);

        // Convert to PascalCase: article -> Article, local-business -> Local_Business
        $parts = explode('-', $class_name);
        $parts = array_map('ucfirst', $parts);
        $class_name = implode('_', $parts);
        $class_name = 'Schema_' . $class_name;

        // Skip interface and base classes
        if (strpos($basename, 'interface') !== false || strpos($basename, 'base') !== false) {
            continue;
        }

        require_once $file;

        if (class_exists($class_name)) {
            $builder = new $class_name();

            if (method_exists($builder, 'get_schema_structure')) {
                $structure = $builder->get_schema_structure();
                $type_value = isset($structure['@type']) ? $structure['@type'] : '';
                $description = isset($structure['description']) ? $structure['description'] : '';
                $label = isset($structure['label']) ? $structure['label'] : $type_value;

                // Get fields from the builder if method exists.
                $fields = array();
                if (method_exists($builder, 'get_fields')) {
                    $fields = $builder->get_fields();
                    // Allow filtering fields for all schema types
                    $fields = apply_filters('swift_rank_get_fields', $fields, $type_value);
                }

                if ($type_value) {
                    // Check if schema type should be hidden from dropdown
                    $show_in_dropdown = isset($structure['showInDropdown']) ? $structure['showInDropdown'] : true;
                    if (!$show_in_dropdown) {
                        continue;
                    }

                    // Check if this is a Pro type
                    $is_pro_type = in_array($type_value, $pro_schema_types, true);

                    $types[$type_value] = array(
                        'label' => $label,
                        'value' => $type_value,
                        'description' => $description,
                        'icon' => isset($structure['icon']) ? $structure['icon'] : '',
                        'isPro' => $is_pro_type,
                        'isDisabled' => false,
                        'structure' => $structure,
                        'fields' => $fields,
                    );
                }
            }
        }
    }

    // Register Pro schema types (without implementations) so they appear in dropdown
    // These will show Pro badges and upgrade notices when selected
    if (!$is_pro_activated) {
        // Load Pro type definitions from centralized config
        $pro_only_types = class_exists('Schema_Types_Config') ? Schema_Types_Config::get_pro_type_definitions() : array();

        // Add Pro types to the types array
        $types = array_merge($types, $pro_only_types);
    }

    return $types;
}
add_filter('swift_rank_register_types', 'swift_rank_register_base_types', 10);

/**
 * Register base plugin schema subtypes
 *
 * @param array $subtypes Existing schema subtypes.
 * @return array Modified schema subtypes.
 */
function swift_rank_register_base_subtypes($subtypes)
{
    $types_dir = SWIFT_RANK_PLUGIN_DIR . 'includes/output/types/';
    $builder_files = glob($types_dir . 'class-*-schema.php');

    foreach ($builder_files as $file) {
        $basename = basename($file, '.php');

        // Convert class-article-schema to Article_Schema
        $class_name = str_replace('class-', '', $basename);
        $class_name = str_replace('-schema', '', $class_name);

        // Convert to PascalCase: article -> Article, local-business -> Local_Business
        $parts = explode('-', $class_name);
        $parts = array_map('ucfirst', $parts);
        $class_name = implode('_', $parts);
        $class_name = 'Schema_' . $class_name;

        // Skip interface and base classes
        if (strpos($basename, 'interface') !== false || strpos($basename, 'base') !== false) {
            continue;
        }

        require_once $file;

        if (class_exists($class_name)) {
            $builder = new $class_name();

            if (method_exists($builder, 'get_schema_structure')) {
                $structure = $builder->get_schema_structure();

                // Get parent type
                $parent_type = isset($structure['@type']) ? $structure['@type'] : '';

                if ($parent_type) {
                    // Initialize parent type array if not exists
                    if (!isset($subtypes[$parent_type])) {
                        $subtypes[$parent_type] = array();
                    }

                    // Add main type as a subtype option
                    $subtypes[$parent_type][$parent_type] = $parent_type;

                    // Add subtypes if defined
                    if (isset($structure['subtypes']) && is_array($structure['subtypes'])) {
                        foreach ($structure['subtypes'] as $subtype_value => $subtype_description) {
                            // Format label nicely (e.g., LocalBusiness -> Local Business)
                            $label = preg_replace('/(?<!^)([A-Z])/', ' $1', $subtype_value);
                            $subtypes[$parent_type][$subtype_value] = $label;
                        }
                    }
                }
            }
        }
    }

    return $subtypes;
}
add_filter('swift_rank_register_subtypes', 'swift_rank_register_base_subtypes', 10);

/**
 * Reorder base schema types by popularity
 *
 * @param array $types Existing schema types.
 * @return array Reordered schema types.
 */
function swift_rank_reorder_base_types($types)
{
    // Define popular order for FREE types
    $free_order = array(
        'Article',
        'BlogPosting',
        'NewsArticle',
        'Product',
        'LocalBusiness',
        'Organization',
        'VideoObject',
        'Person',
    );

    $ordered_types = array();

    // Add free types in popularity order
    foreach ($free_order as $type_key) {
        if (isset($types[$type_key])) {
            $ordered_types[$type_key] = $types[$type_key];
            unset($types[$type_key]);
        }
    }

    // Add remaining types (including Pro types if not activated)
    if (!empty($types)) {
        foreach ($types as $key => $value) {
            $ordered_types[$key] = $value;
        }
    }

    return $ordered_types;
}
add_filter('swift_rank_register_types', 'swift_rank_reorder_base_types', 15);
