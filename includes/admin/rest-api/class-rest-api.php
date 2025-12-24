<?php
/**
 * REST API Handler
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Swift_Rank_REST_API class
 *
 * Handles REST API routes and endpoints for Swift Rank.
 * All endpoints require 'manage_options' capability (admin only).
 */
class Swift_Rank_REST_API
{

    /**
     * Instance of this class
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return Swift_Rank_REST_API
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
        add_action('rest_api_init', array($this, 'register_settings_for_rest'));
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    /**
     * Register settings for REST API
     * This is needed for the /wp/v2/settings endpoint to work
     */
    public function register_settings_for_rest()
    {
        register_setting('swift_rank_settings_group', 'swift_rank_settings', array(
            'type' => 'object',
            'sanitize_callback' => array($this, 'sanitize_settings'),
            'show_in_rest' => array(
                'schema' => array(
                    'type' => 'object',
                    'additionalProperties' => true,
                ),
            ),
        ));
    }

    /**
     * Sanitize settings
     *
     * @param array $input Input data.
     * @return array
     */
    public function sanitize_settings($input)
    {


        $sanitized = array();

        if (isset($input['code_placement'])) {
            $allowed_placements = array('head', 'footer');
            $sanitized['code_placement'] = in_array($input['code_placement'], $allowed_placements, true) ? $input['code_placement'] : 'head';
        }

        if (isset($input['default_image'])) {
            if (is_array($input['default_image']) && isset($input['default_image']['url'])) {
                // Return sanitized array
                $sanitized['default_image'] = array(
                    'id' => isset($input['default_image']['id']) ? absint($input['default_image']['id']) : 0,
                    'url' => $this->is_template_variable($input['default_image']['url']) ? sanitize_text_field($input['default_image']['url']) : esc_url_raw($input['default_image']['url']),
                );
                // Preserve other fields if present (alt, width, height)
                if (isset($input['default_image']['alt'])) {
                    $sanitized['default_image']['alt'] = sanitize_text_field($input['default_image']['alt']);
                }
                if (isset($input['default_image']['width'])) {
                    $sanitized['default_image']['width'] = absint($input['default_image']['width']);
                }
                if (isset($input['default_image']['height'])) {
                    $sanitized['default_image']['height'] = absint($input['default_image']['height']);
                }
            } else {
                // Legacy string support
                $sanitized['default_image'] = $this->is_template_variable($input['default_image']) ? sanitize_text_field($input['default_image']) : esc_url_raw($input['default_image']);
            }
        }

        if (isset($input['auto_schema'])) {
            $sanitized['auto_schema'] = (bool) $input['auto_schema'];
        }

        // Breadcrumb settings (Pro feature).
        if (isset($input['breadcrumb_enabled'])) {
            $sanitized['breadcrumb_enabled'] = (bool) $input['breadcrumb_enabled'];
        }

        if (isset($input['breadcrumb_separator'])) {
            $sanitized['breadcrumb_separator'] = sanitize_text_field($input['breadcrumb_separator']);
        }

        if (isset($input['breadcrumb_show_home'])) {
            $sanitized['breadcrumb_show_home'] = (bool) $input['breadcrumb_show_home'];
        }

        if (isset($input['breadcrumb_home_text'])) {
            $sanitized['breadcrumb_home_text'] = sanitize_text_field($input['breadcrumb_home_text']);
        }

        // Sitelinks searchbox (Pro feature).
        if (isset($input['sitelinks_searchbox'])) {
            $sanitized['sitelinks_searchbox'] = (bool) $input['sitelinks_searchbox'];
        }

        // Minify schema setting.
        if (isset($input['minify_schema'])) {
            $sanitized['minify_schema'] = (bool) $input['minify_schema'];
        }

        // Disable Yoast SEO schema setting.
        if (isset($input['disable_yoast_schema'])) {
            $sanitized['disable_yoast_schema'] = (bool) $input['disable_yoast_schema'];
        }

        // Disable AIOSEO schema setting.
        if (isset($input['disable_aioseo_schema'])) {
            $sanitized['disable_aioseo_schema'] = (bool) $input['disable_aioseo_schema'];
        }

        // Disable RankMath schema setting.
        if (isset($input['disable_rankmath_schema'])) {
            $sanitized['disable_rankmath_schema'] = (bool) $input['disable_rankmath_schema'];
        }

        // Knowledge Graph settings.
        if (isset($input['knowledge_graph_enabled'])) {
            $sanitized['knowledge_graph_enabled'] = (bool) $input['knowledge_graph_enabled'];
        }

        if (isset($input['knowledge_graph_type'])) {
            $sanitized['knowledge_graph_type'] = sanitize_text_field($input['knowledge_graph_type']);
        }

        // Organization fields (structured object for FieldsBuilder).
        if (isset($input['organization_fields']) && is_array($input['organization_fields'])) {
            $sanitized['organization_fields'] = $this->sanitize_organization_fields($input['organization_fields']);
        }

        // Person fields (structured object for FieldsBuilder).
        if (isset($input['person_fields']) && is_array($input['person_fields'])) {
            $sanitized['person_fields'] = $this->sanitize_person_fields($input['person_fields']);
        }

        // LocalBusiness fields (structured object for FieldsBuilder).
        if (isset($input['localbusiness_fields']) && is_array($input['localbusiness_fields'])) {
            $sanitized['localbusiness_fields'] = $this->sanitize_localbusiness_fields($input['localbusiness_fields']);
        }

        // Auto-schema settings
        if (isset($input['auto_schema_post_enabled'])) {
            $sanitized['auto_schema_post_enabled'] = (bool) $input['auto_schema_post_enabled'];
        }

        if (isset($input['auto_schema_post_type'])) {
            $allowed_types = array('Article', 'BlogPosting', 'NewsArticle', 'ScholarlyArticle', 'TechArticle');
            $sanitized['auto_schema_post_type'] = in_array($input['auto_schema_post_type'], $allowed_types, true) ? $input['auto_schema_post_type'] : 'Article';
        }

        if (isset($input['auto_schema_page_enabled'])) {
            $sanitized['auto_schema_page_enabled'] = (bool) $input['auto_schema_page_enabled'];
        }

        if (isset($input['auto_schema_search_enabled'])) {
            $sanitized['auto_schema_search_enabled'] = (bool) $input['auto_schema_search_enabled'];
        }

        if (isset($input['auto_schema_woocommerce_enabled'])) {
            $sanitized['auto_schema_woocommerce_enabled'] = (bool) $input['auto_schema_woocommerce_enabled'];
        }

        // Individual social profile fields
        $social_fields = ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube'];
        foreach ($social_fields as $field) {
            if (isset($input[$field])) {
                // Check for variables ({...} or %%...%%)
                if ($this->is_template_variable($input[$field]) || preg_match('/%%.+%%/', $input[$field])) {
                    $sanitized[$field] = sanitize_text_field($input[$field]);
                } else {
                    $sanitized[$field] = esc_url_raw($input[$field]);
                }
            }
        }

        // Custom profiles (repeater field)
        if (isset($input['custom_profiles']) && is_array($input['custom_profiles'])) {
            $sanitized['custom_profiles'] = array_map(function ($profile) {
                $url = isset($profile['url']) ? $profile['url'] : '';
                // Check for variables ({...} or %%...%%)
                if ($this->is_template_variable($url) || preg_match('/%%.+%%/', $url)) {
                    $sanitized_url = sanitize_text_field($url);
                } else {
                    $sanitized_url = esc_url_raw($url);
                }

                return [
                    'platform' => isset($profile['platform']) ? sanitize_text_field($profile['platform']) : '',
                    'url' => $sanitized_url,
                ];
            }, $input['custom_profiles']);
        }


        return $sanitized;
    }

    /**
     * Sanitize organization fields
     *
     * @param array $fields Organization fields.
     * @return array
     */
    private function sanitize_organization_fields($fields)
    {


        $sanitized = array();

        // Text fields
        $text_fields = array('organizationType', 'name', 'phone', 'email', 'streetAddress', 'city', 'state', 'postalCode', 'country', 'priceRange');
        foreach ($text_fields as $field) {
            if (isset($fields[$field])) {
                $sanitized[$field] = sanitize_text_field($fields[$field]);
            }
        }

        // URL fields - preserve template variables
        $url_fields = array('url');
        foreach ($url_fields as $field) {
            if (isset($fields[$field])) {
                // Don't sanitize if it's a template variable
                if ($this->is_template_variable($fields[$field])) {
                    $sanitized[$field] = sanitize_text_field($fields[$field]);
                } else {
                    $sanitized[$field] = esc_url_raw($fields[$field]);
                }
            }
        }

        // Logo field (can be string or array)
        if (isset($fields['logo'])) {
            if (is_array($fields['logo']) && isset($fields['logo']['url'])) {
                $sanitized['logo'] = array(
                    'id' => isset($fields['logo']['id']) ? absint($fields['logo']['id']) : 0,
                    'url' => $this->is_template_variable($fields['logo']['url']) ? sanitize_text_field($fields['logo']['url']) : esc_url_raw($fields['logo']['url']),
                );
            } elseif (is_string($fields['logo'])) {
                if ($this->is_template_variable($fields['logo'])) {
                    $sanitized['logo'] = sanitize_text_field($fields['logo']);
                } else {
                    $sanitized['logo'] = esc_url_raw($fields['logo']);
                }
            }
        }

        // Textarea fields
        if (isset($fields['description'])) {
            $sanitized['description'] = sanitize_textarea_field($fields['description']);
        }

        // Social profiles repeater
        if (isset($fields['socialProfiles']) && is_array($fields['socialProfiles'])) {
            $sanitized['socialProfiles'] = array_map(function ($profile) {
                if (is_array($profile) && isset($profile['url'])) {
                    // Don't sanitize if it's a template variable
                    if ($this->is_template_variable($profile['url'])) {
                        return array('url' => sanitize_text_field($profile['url']));
                    }
                    return array('url' => esc_url_raw($profile['url']));
                }
                return array('url' => '');
            }, $fields['socialProfiles']);
        }

        // Opening hours (Pro feature)
        if (isset($fields['openingHours']) && is_array($fields['openingHours'])) {
            $sanitized['openingHours'] = $fields['openingHours']; // Already sanitized by Pro plugin
        }



        return $sanitized;
    }

    /**
     * Sanitize person fields
     *
     * @param array $fields Person fields.
     * @return array
     */
    private function sanitize_person_fields($fields)
    {


        $sanitized = array();

        // Text fields
        $text_fields = array('name', 'phone', 'email', 'jobTitle', 'worksFor', 'gender', 'birthDate', 'nationality', 'streetAddress', 'city', 'state', 'postalCode', 'country');
        foreach ($text_fields as $field) {
            if (isset($fields[$field])) {
                $sanitized[$field] = sanitize_text_field($fields[$field]);
            }
        }

        // URL fields - preserve template variables
        $url_fields = array('url');
        foreach ($url_fields as $field) {
            if (isset($fields[$field])) {
                // Don't sanitize if it's a template variable
                if ($this->is_template_variable($fields[$field])) {
                    $sanitized[$field] = sanitize_text_field($fields[$field]);
                } else {
                    $sanitized[$field] = esc_url_raw($fields[$field]);
                }
            }
        }
        // Image field (can be string or array)
        if (isset($fields['image'])) {
            if (is_array($fields['image']) && isset($fields['image']['url'])) {
                $sanitized['image'] = array(
                    'id' => isset($fields['image']['id']) ? absint($fields['image']['id']) : 0,
                    'url' => $this->is_template_variable($fields['image']['url']) ? sanitize_text_field($fields['image']['url']) : esc_url_raw($fields['image']['url']),
                );
            } elseif (is_string($fields['image'])) {
                if ($this->is_template_variable($fields['image'])) {
                    $sanitized['image'] = sanitize_text_field($fields['image']);
                } else {
                    $sanitized['image'] = esc_url_raw($fields['image']);
                }
            }
        }

        // Textarea fields
        if (isset($fields['description'])) {
            $sanitized['description'] = sanitize_textarea_field($fields['description']);
        }

        // Social profiles repeater
        if (isset($fields['socialProfiles']) && is_array($fields['socialProfiles'])) {
            $sanitized['socialProfiles'] = array_map(function ($profile) {
                if (is_array($profile) && isset($profile['url'])) {
                    // Don't sanitize if it's a template variable
                    if ($this->is_template_variable($profile['url'])) {
                        return array('url' => sanitize_text_field($profile['url']));
                    }
                    return array('url' => esc_url_raw($profile['url']));
                }
                return array('url' => '');
            }, $fields['socialProfiles']);
        }



        return $sanitized;
    }

    /**
     * Sanitize local business fields
     *
     * @param array $fields Local business fields.
     * @return array
     */
    private function sanitize_localbusiness_fields($fields)
    {

        $sanitized = array();

        // Text fields
        $text_fields = array('businessType', 'name', 'description', 'phone', 'email', 'streetAddress', 'city', 'state', 'postalCode', 'country', 'latitude', 'longitude', 'priceRange', 'paymentAccepted', 'currenciesAccepted', 'servesCuisine', 'acceptsReservations');
        foreach ($text_fields as $field) {
            if (isset($fields[$field])) {
                $sanitized[$field] = sanitize_text_field($fields[$field]);
            }
        }

        // URL fields - preserve template variables
        $url_fields = array('url', 'menu');
        foreach ($url_fields as $field) {
            if (isset($fields[$field])) {
                // Don't sanitize if it's a template variable
                if ($this->is_template_variable($fields[$field])) {
                    $sanitized[$field] = sanitize_text_field($fields[$field]);
                } else {
                    $sanitized[$field] = esc_url_raw($fields[$field]);
                }
            }
        }
        // Image field (can be string or array)
        if (isset($fields['image'])) {
            if (is_array($fields['image']) && isset($fields['image']['url'])) {
                $sanitized['image'] = array(
                    'id' => isset($fields['image']['id']) ? absint($fields['image']['id']) : 0,
                    'url' => $this->is_template_variable($fields['image']['url']) ? sanitize_text_field($fields['image']['url']) : esc_url_raw($fields['image']['url']),
                );
            } elseif (is_string($fields['image'])) {
                if ($this->is_template_variable($fields['image'])) {
                    $sanitized['image'] = sanitize_text_field($fields['image']);
                } else {
                    $sanitized['image'] = esc_url_raw($fields['image']);
                }
            }
        }

        // Social profiles repeater
        if (isset($fields['socialProfiles']) && is_array($fields['socialProfiles'])) {
            $sanitized['socialProfiles'] = array_map(function ($profile) {
                if (is_array($profile) && isset($profile['url'])) {
                    // Don't sanitize if it's a template variable
                    if ($this->is_template_variable($profile['url'])) {
                        return array('url' => sanitize_text_field($profile['url']));
                    }
                    return array('url' => esc_url_raw($profile['url']));
                }
                return array('url' => '');
            }, $fields['socialProfiles']);
        }

        // Opening hours (complex structure)
        if (isset($fields['openingHours']) && is_array($fields['openingHours'])) {
            $sanitized['openingHours'] = array();
            $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
            foreach ($days as $day) {
                if (isset($fields['openingHours'][$day]) && is_array($fields['openingHours'][$day])) {
                    $day_data = $fields['openingHours'][$day];
                    $sanitized['openingHours'][$day] = array(
                        'closed' => isset($day_data['closed']) ? (bool) $day_data['closed'] : false,
                        'opens' => isset($day_data['opens']) ? sanitize_text_field($day_data['opens']) : '',
                        'closes' => isset($day_data['closes']) ? sanitize_text_field($day_data['closes']) : '',
                    );
                }
            }
        }



        return $sanitized;
    }

    /**
     * Check if a value is a template variable
     *
     * @param string $value Value to check.
     * @return bool True if it's a template variable.
     */
    private function is_template_variable($value)
    {
        if (empty($value) || !is_string($value)) {
            return false;
        }
        // Check if value contains template variables like {site_url}, {post_title}, etc.
        return (strpos($value, '{') !== false && strpos($value, '}') !== false);
    }

    /**
     * Register REST API routes
     * All routes require 'manage_options' capability (admin only)
     */
    public function register_rest_routes()
    {
        // Get templates endpoint
        register_rest_route('swift-rank/v1', '/templates', array(
            'methods' => 'GET',
            'callback' => array($this, 'handle_rest_get_templates'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));

        // Export templates endpoint
        register_rest_route('swift-rank/v1', '/export', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_rest_export'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));

        // Import templates endpoint
        register_rest_route('swift-rank/v1', '/import', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_rest_import'),
            'permission_callback' => array($this, 'check_admin_permission'),
        ));
    }

    /**
     * Check if current user has admin permission
     *
     * @return bool
     */
    public function check_admin_permission()
    {
        return current_user_can('manage_options');
    }

    /**
     * Handle REST API get templates
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response
     */
    public function handle_rest_get_templates($request)
    {
        $templates = get_posts(array(
            'post_type' => 'sr_template',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        $formatted_templates = array();
        foreach ($templates as $template) {
            $formatted_templates[] = array(
                'id' => $template->ID,
                'title' => array(
                    'rendered' => $template->post_title,
                ),
                'modified' => $template->post_modified,
            );
        }

        return rest_ensure_response($formatted_templates);
    }

    /**
     * Handle REST API export
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function handle_rest_export($request)
    {
        $template_ids = $request->get_param('template_ids');
        if (empty($template_ids) || !is_array($template_ids)) {
            return new WP_Error('no_templates', __('No templates selected for export.', 'swift-rank'), array('status' => 400));
        }

        $export_data = array(
            'version' => '1.0',
            'export_date' => current_time('mysql'),
            'templates' => array(),
        );

        foreach ($template_ids as $template_id) {
            $template = get_post($template_id);
            if (!$template || 'sr_template' !== $template->post_type) {
                continue;
            }

            $schema_data = get_post_meta($template_id, '_schema_template_data', true);
            $conditions = get_post_meta($template_id, '_schema_template_conditions', true);
            $schema_type = get_post_meta($template_id, '_schema_type', true);
            $schema_subtype = get_post_meta($template_id, '_schema_subtype', true);

            $export_data['templates'][] = array(
                'title' => $template->post_title,
                'content' => $template->post_content,
                'status' => $template->post_status,
                'schema_data' => $schema_data,
                'conditions' => $conditions,
                'schema_type' => $schema_type,
                'schema_subtype' => $schema_subtype,
            );
        }

        return rest_ensure_response($export_data);
    }

    /**
     * Handle REST API import
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function handle_rest_import($request)
    {
        $import_data = $request->get_json_params();
        if (empty($import_data['templates'])) {
            return new WP_Error('no_templates', __('No templates found in import data.', 'swift-rank'), array('status' => 400));
        }

        $imported_count = 0;
        foreach ($import_data['templates'] as $template_data) {
            $post_id = wp_insert_post(array(
                'post_title' => sanitize_text_field($template_data['title']),
                'post_content' => isset($template_data['content']) ? wp_kses_post($template_data['content']) : '',
                'post_status' => isset($template_data['status']) ? sanitize_key($template_data['status']) : 'publish',
                'post_type' => 'sr_template',
            ));

            if (!is_wp_error($post_id)) {
                if (isset($template_data['schema_data'])) {
                    update_post_meta($post_id, '_schema_template_data', $template_data['schema_data']);
                }
                if (isset($template_data['conditions'])) {
                    update_post_meta($post_id, '_schema_template_conditions', $template_data['conditions']);
                }
                if (isset($template_data['schema_type'])) {
                    update_post_meta($post_id, '_schema_type', sanitize_text_field($template_data['schema_type']));
                }
                if (isset($template_data['schema_subtype'])) {
                    update_post_meta($post_id, '_schema_subtype', sanitize_text_field($template_data['schema_subtype']));
                }
                ++$imported_count;
            }
        }

        return rest_ensure_response(array('imported_count' => $imported_count));
    }
}
