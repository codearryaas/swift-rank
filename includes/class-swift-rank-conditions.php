<?php
/**
 * Swift Rank Conditions Class
 *
 * Handles condition matching logic for schema templates.
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Swift_Rank_Conditions class
 *
 * Centralized condition matching logic used by both frontend output and admin metabox.
 */
class Swift_Rank_Conditions
{

	/**
	 * Check if conditions have any rules defined
	 *
	 * @param array $conditions Conditions to check.
	 * @return bool
	 */
	public static function has_rules($conditions)
	{
		if (empty($conditions)) {
			return false;
		}

		if (isset($conditions['groups'])) {
			foreach ($conditions['groups'] as $group) {
				if (!empty($group['rules'])) {
					return true;
				}
			}
			return false;
		}

		// Legacy format.
		return !empty($conditions['postTypes']) || !empty($conditions['specificPosts']);
	}

	/**
	 * Check if conditions match
	 *
	 * @param array       $conditions Conditions to check.
	 * @param int|null    $post_id    Post ID (null for frontend context).
	 * @param string|null $post_type  Post type (null for frontend context).
	 * @return bool
	 */
	public static function matches_conditions($conditions, $post_id = null, $post_type = null)
	{
		if (empty($conditions)) {
			return false;
		}

		// Handle new grouped conditions format.
		if (isset($conditions['groups']) && !empty($conditions['groups'])) {
			return self::matches_grouped_conditions($conditions, $post_id, $post_type);
		}

		// Handle flat conditions format (legacy).
		return self::matches_flat_conditions($conditions, $post_id, $post_type);
	}

	/**
	 * Check if grouped conditions match
	 *
	 * @param array       $conditions Grouped conditions.
	 * @param int|null    $post_id    Post ID.
	 * @param string|null $post_type  Post type.
	 * @return bool
	 */
	private static function matches_grouped_conditions($conditions, $post_id, $post_type)
	{
		$groups = isset($conditions['groups']) ? $conditions['groups'] : array();
		$group_logic = isset($conditions['logic']) ? $conditions['logic'] : 'or';

		if (empty($groups)) {
			return false;
		}

		$group_results = array();

		foreach ($groups as $group) {
			$rules = isset($group['rules']) ? $group['rules'] : array();
			$rule_logic = isset($group['logic']) ? $group['logic'] : 'and';

			if (empty($rules)) {
				$group_results[] = false;
				continue;
			}

			$rule_results = array();
			foreach ($rules as $rule) {
				$rule_results[] = self::evaluate_rule($rule, $post_id, $post_type);
			}

			// Apply rule logic within group.
			if ('and' === $rule_logic) {
				$group_results[] = !in_array(false, $rule_results, true);
			} else {
				$group_results[] = in_array(true, $rule_results, true);
			}
		}

		// Apply group logic.
		if ('and' === $group_logic) {
			return !in_array(false, $group_results, true);
		} else {
			return in_array(true, $group_results, true);
		}
	}

	/**
	 * Check if flat conditions match (legacy format)
	 *
	 * @param array       $conditions Flat conditions.
	 * @param int|null    $post_id    Post ID.
	 * @param string|null $post_type  Post type.
	 * @return bool
	 */
	private static function matches_flat_conditions($conditions, $post_id, $post_type)
	{
		// Check front page.
		if (!empty($conditions['frontPage']) && is_front_page()) {
			return true;
		}

		// Check home page (blog index).
		if (!empty($conditions['homePage']) && is_home()) {
			return true;
		}

		// Check post types.
		if (!empty($conditions['postTypes']) && is_array($conditions['postTypes'])) {
			if ($post_type && in_array($post_type, $conditions['postTypes'], true)) {
				return true;
			}
		}

		// Check specific posts/pages by ID.
		if (!empty($conditions['specificPosts']) && is_array($conditions['specificPosts'])) {
			foreach ($conditions['specificPosts'] as $specific_id) {
				if ($post_id && (int) $specific_id === (int) $post_id) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Evaluate a single rule
	 *
	 * @param array       $rule      The rule to evaluate.
	 * @param int|null    $post_id   Post ID.
	 * @param string|null $post_type Post type.
	 * @return bool
	 */
	private static function evaluate_rule($rule, $post_id, $post_type)
	{
		$condition_type = isset($rule['conditionType']) ? $rule['conditionType'] : '';
		$operator = isset($rule['operator']) ? $rule['operator'] : 'equal_to';
		$value = isset($rule['value']) ? $rule['value'] : array();

		if (empty($condition_type)) {
			return false;
		}

		// Ensure value is an array.
		if (!is_array($value)) {
			$value = !empty($value) ? array($value) : array();
		}

		$result = false;

		switch ($condition_type) {
			case 'whole_site':
				// Whole site always matches - returns true for any page.
				$result = true;
				break;

			case 'location':
				$result = self::evaluate_location_rule($value);
				break;

			case 'post_type':
				$result = self::evaluate_post_type_rule($value, $post_type);
				break;

			case 'singular':
				$result = self::evaluate_singular_rule($value, $post_id);
				break;



			default:
				$result = false;
				break;
		}

		// Allow other plugins to evaluate rules (e.g. Pro version)
		$result = apply_filters('swift_rank_evaluate_rule', $result, $rule);

		// Apply operator.
		if ('not_equal_to' === $operator) {
			$result = !$result;
		}

		return $result;
	}

	/**
	 * Evaluate location rule
	 *
	 * @param array $value Values to match.
	 * @return bool
	 */
	private static function evaluate_location_rule($value)
	{
		if (empty($value) || !is_array($value)) {
			return false;
		}

		$location = $value[0];

		switch ($location) {
			case 'whole_site':
				// Whole site always matches
				return true;

			case 'home_page':
				// Home page matches both front page and blog index
				return is_front_page() || is_home();

			case 'author_archive':
				// Author archive - handled by Pro filter, but provide fallback
				return is_author();

			default:
				return false;
		}
	}

	/**
	 * Evaluate post type rule
	 *
	 * @param array       $value     Values to match.
	 * @param string|null $post_type Post type.
	 * @return bool
	 */
	private static function evaluate_post_type_rule($value, $post_type)
	{
		if (empty($value) || !is_array($value)) {
			return false;
		}

		// In admin context, use provided post_type.
		if ($post_type) {
			return in_array($post_type, $value, true);
		}

		// In frontend context, check current post type.
		if (!is_singular()) {
			return false;
		}

		$current_post_type = get_post_type();
		return in_array($current_post_type, $value, true);
	}

	/**
	 * Evaluate singular post rule
	 *
	 * @param array    $value   Values to match.
	 * @param int|null $post_id Post ID.
	 * @return bool
	 */
	private static function evaluate_singular_rule($value, $post_id)
	{
		if (empty($value) || !is_array($value)) {
			return false;
		}

		// In admin context, use provided post_id.
		if ($post_id) {
			// Convert post_id to int for comparison
			$post_id_int = (int) $post_id;

			// Check both strict match and integer comparison
			foreach ($value as $val) {
				if ($val == $post_id_int || (int) $val === $post_id_int) {
					return true;
				}
			}
			return false;
		}

		// In frontend context, check current post ID.
		if (!is_singular()) {
			return false;
		}

		$current_post_id = get_the_ID();
		$current_post_id_int = (int) $current_post_id;

		foreach ($value as $val) {
			if ($val == $current_post_id_int || (int) $val === $current_post_id_int) {
				return true;
			}
		}
		return false;
	}
}
