<?php
/**
 * Schema Helper Class
 *
 * Provides helper functions for retrieving schema types and subtypes.
 * Allows both base and Pro plugins to register their schema types via filters.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schema_Type_Helper class
 *
 * Centralized helper for schema type and subtype management.
 */
class Schema_Type_Helper
{

    /**
     * Get all available schema types
     *
     * Schema types are collected via filter hooks, allowing both
     * the base plugin and Pro plugin to register their types.
     *
     * @return array Array of schema type definitions.
     */
    public static function get_schema_types()
    {
        $schema_types = array();

        /**
         * Filter to register schema types.
         *
         * Both base and Pro plugins should hook into this filter
         * to register their schema types.
         *
         * Expected format:
         * array(
         *     'type_key' => array(
         *         'label'       => 'Type Label',
         *         'value'       => 'TypeValue',
         *         'description' => 'Type description',
         *         'isPro'       => false,
         *         'isDisabled'  => false,
         *         'structure'   => array() // Full schema structure from get_schema_structure()
         *     )
         * )
         *
         * @param array $schema_types Array of schema type definitions.
         */
        $schema_types = apply_filters('swift_rank_register_types', $schema_types);

        return $schema_types;
    }

    /**
     * Get all available schema subtypes
     *
     * Optionally filter by parent type.
     *
     * @param string $type Optional. Filter subtypes by parent type (e.g., 'Article', 'Organization').
     * @return array Array of subtype value => label pairs.
     */
    public static function get_schema_subtypes($type = '')
    {
        $all_subtypes = array();

        /**
         * Filter to register schema subtypes.
         *
         * Both base and Pro plugins should hook into this filter
         * to register their schema subtypes.
         *
         * Expected format:
         * array(
         *     'parent_type' => array(
         *         'SubtypeValue' => 'Subtype Label',
         *         'AnotherSubtype' => 'Another Label'
         *     )
         * )
         *
         * @param array $all_subtypes Array of subtypes grouped by parent type.
         */
        $all_subtypes = apply_filters('swift_rank_register_subtypes', $all_subtypes);

        // If a specific type is requested, return only its subtypes
        if (!empty($type)) {
            return isset($all_subtypes[$type]) ? $all_subtypes[$type] : array();
        }

        // Otherwise, merge all subtypes into a flat array
        $merged_subtypes = array();
        foreach ($all_subtypes as $parent_type => $subtypes) {
            $merged_subtypes = array_merge($merged_subtypes, $subtypes);
        }

        return $merged_subtypes;
    }

    /**
     * Get formatted schema types for React select component
     *
     * Converts the registered schema types into the format expected
     * by the React select component in the admin UI.
     *
     * @return array Array of schema types formatted for React select.
     */
    public static function get_types_for_select()
    {
        $registered_types = self::get_schema_types();
        $formatted_types = array();

        foreach ($registered_types as $type_key => $type_data) {
            $formatted_types[] = array(
                'label' => isset($type_data['label']) ? $type_data['label'] : $type_key,
                'value' => isset($type_data['value']) ? $type_data['value'] : $type_key,
                'description' => isset($type_data['description']) ? $type_data['description'] : '',
                'icon' => isset($type_data['icon']) ? $type_data['icon'] : '',
                'isPro' => isset($type_data['isPro']) ? $type_data['isPro'] : false,
                'isDisabled' => isset($type_data['isDisabled']) ? $type_data['isDisabled'] : false,
                'fields' => isset($type_data['fields']) ? $type_data['fields'] : array(),
            );
        }

        return $formatted_types;
    }

    /**
     * Get formatted schema subtypes for filter dropdown
     *
     * @param string $type Optional. Filter by parent type.
     * @return array Array of subtype value => label pairs.
     */
    public static function get_subtypes_for_filter($type = '')
    {
        return self::get_schema_subtypes($type);
    }
}
