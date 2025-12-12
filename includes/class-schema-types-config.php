<?php
/**
 * Schema Types Configuration
 *
 * Centralized configuration for all schema types and their Pro status.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schema_Types_Config class
 *
 * Central registry for schema type definitions and Pro requirements.
 */
class Schema_Types_Config
{
    /**
     * Get list of Pro-only schema types
     *
     * Derived from the keys of get_pro_type_definitions() to maintain
     * a single source of truth.
     *
     * @return array List of schema type names that require Pro license.
     */
    public static function get_pro_types()
    {
        return array_keys(self::get_pro_type_definitions());
    }

    /**
     * Check if a schema type requires Pro license
     *
     * @param string $schema_type Schema type name.
     * @return bool True if Pro required, false otherwise.
     */
    public static function is_pro_type($schema_type)
    {
        return in_array($schema_type, self::get_pro_types(), true);
    }

    /**
     * Get Pro type definitions for registration
     *
     * @return array Array of Pro type definitions with labels, descriptions, etc.
     */
    public static function get_pro_type_definitions()
    {
        return array(
            'Recipe' => array(
                'label' => __('Recipe', 'swift-rank'),
                'value' => 'Recipe',
                'description' => __('A recipe with ingredients, cooking time, and instructions', 'swift-rank'),
                'icon' => 'chef-hat',
                'isPro' => true,
                'isDisabled' => false,
                'structure' => array(),
                'fields' => array(),
            ),
            'Event' => array(
                'label' => __('Event', 'swift-rank'),
                'value' => 'Event',
                'description' => __('An event with date, time, and location information', 'swift-rank'),
                'icon' => 'calendar',
                'isPro' => true,
                'isDisabled' => false,
                'structure' => array(),
                'fields' => array(),
            ),
            'HowTo' => array(
                'label' => __('How-To', 'swift-rank'),
                'value' => 'HowTo',
                'description' => __('Step-by-step instructions for completing a task', 'swift-rank'),
                'icon' => 'list-checks',
                'isPro' => true,
                'isDisabled' => false,
                'structure' => array(),
                'fields' => array(),
            ),
            'Custom' => array(
                'label' => __('Custom Schema', 'swift-rank'),
                'value' => 'Custom',
                'description' => __('Build your own custom schema structure visually', 'swift-rank'),
                'icon' => 'code',
                'isPro' => true,
                'isDisabled' => false,
                'structure' => array(),
                'fields' => array(),
            ),
        );
    }
}
