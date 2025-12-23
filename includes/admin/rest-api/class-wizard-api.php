<?php
/**
 * Setup Wizard REST API Endpoints
 *
 * @package Swift_Rank
 */

namespace Swift_Rank\Admin\REST;

if (!defined('ABSPATH')) {
    exit;
}

class Wizard_API
{
    /**
     * Namespace for the REST API
     */
    const NAMESPACE = 'swift-rank/v1';

    /**
     * Initialize the REST API
     */
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    /**
     * Register REST API routes
     */
    public function register_routes()
    {
        // Get wizard state
        register_rest_route(self::NAMESPACE , '/wizard/get-state', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_wizard_state'),
            'permission_callback' => array($this, 'check_permissions'),
        ));

        // Get existing data for pre-population
        register_rest_route(self::NAMESPACE , '/wizard/get-data', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_wizard_data'),
            'permission_callback' => array($this, 'check_permissions'),
        ));

        // Save wizard state
        register_rest_route(self::NAMESPACE , '/wizard/save-state', array(
            'methods' => 'POST',
            'callback' => array($this, 'save_wizard_state'),
            'permission_callback' => array($this, 'check_permissions'),
        ));

        // Save step data
        register_rest_route(self::NAMESPACE , '/wizard/save-step', array(
            'methods' => 'POST',
            'callback' => array($this, 'save_step_data'),
            'permission_callback' => array($this, 'check_permissions'),
        ));

        // Complete wizard
        register_rest_route(self::NAMESPACE , '/wizard/complete', array(
            'methods' => 'POST',
            'callback' => array($this, 'complete_wizard'),
            'permission_callback' => array($this, 'check_permissions'),
        ));
    }

    /**
     * Check permissions
     */
    public function check_permissions()
    {
        return current_user_can('manage_options');
    }

    /**
     * Get wizard state
     */
    public function get_wizard_state($request)
    {
        $state = get_option('swift_rank_wizard_state', array(
            'completed_steps' => array(),
            'current_step' => 1,
            'is_complete' => false,
        ));

        return rest_ensure_response($state);
    }

    /**
     * Get existing data for wizard pre-population
     */
    public function get_wizard_data($request)
    {
        $settings = get_option('swift_rank_settings', array());


        $data = array(
            2 => array(
                'knowledge_graph_type' => isset($settings['knowledge_graph_type']) ? $settings['knowledge_graph_type'] : 'Organization',
                'organization_fields' => isset($settings['organization_fields']) ? $settings['organization_fields'] : array(),
                'person_fields' => isset($settings['person_fields']) ? $settings['person_fields'] : array(),
                'localbusiness_fields' => isset($settings['localbusiness_fields']) ? $settings['localbusiness_fields'] : array(),
            ),
            3 => array(
                'facebook' => isset($settings['facebook']) ? $settings['facebook'] : '',
                'twitter' => isset($settings['twitter']) ? $settings['twitter'] : '',
                'linkedin' => isset($settings['linkedin']) ? $settings['linkedin'] : '',
                'instagram' => isset($settings['instagram']) ? $settings['instagram'] : '',
                'youtube' => isset($settings['youtube']) ? $settings['youtube'] : '',
                'custom_profiles' => isset($settings['custom_profiles']) ? $settings['custom_profiles'] : array(),
            ),
            4 => array(
                'post_enabled' => isset($settings['auto_schema_post_enabled']) ? $settings['auto_schema_post_enabled'] : true,
                'post_type' => isset($settings['auto_schema_post_type']) ? $settings['auto_schema_post_type'] : 'Article',
                'page_enabled' => isset($settings['auto_schema_page_enabled']) ? $settings['auto_schema_page_enabled'] : true,
                'search_enabled' => isset($settings['auto_schema_search_enabled']) ? $settings['auto_schema_search_enabled'] : true,
                'woocommerce_enabled' => isset($settings['auto_schema_woocommerce_enabled']) ? $settings['auto_schema_woocommerce_enabled'] : true,
            ),
            5 => array(
                'knowledge_graph_enabled' => isset($settings['knowledge_graph_enabled']) ? $settings['knowledge_graph_enabled'] : true,
                'breadcrumb_enabled' => isset($settings['breadcrumb_enabled']) ? $settings['breadcrumb_enabled'] : true,
                'sitelinks_searchbox' => isset($settings['sitelinks_searchbox']) ? $settings['sitelinks_searchbox'] : false,
            ),
        );

        // Parse social profiles from settings
        if (!empty($settings['social_profiles']) && is_array($settings['social_profiles'])) {
            $custom_profiles_meta = isset($settings['custom_profiles_meta']) ? $settings['custom_profiles_meta'] : array();

            foreach ($settings['social_profiles'] as $url) {
                $url = esc_url($url);
                $url_lower = strtolower($url);

                if (stripos($url_lower, 'facebook.com') !== false) {
                    $data[3]['facebook'] = $url;
                } elseif (stripos($url_lower, 'twitter.com') !== false || stripos($url_lower, 'x.com') !== false) {
                    $data[3]['twitter'] = $url;
                } elseif (stripos($url_lower, 'linkedin.com') !== false) {
                    $data[3]['linkedin'] = $url;
                } elseif (stripos($url_lower, 'instagram.com') !== false || stripos($url_lower, 'instagr.am') !== false) {
                    $data[3]['instagram'] = $url;
                } elseif (stripos($url_lower, 'youtube.com') !== false || stripos($url_lower, 'youtu.be') !== false) {
                    $data[3]['youtube'] = $url;
                } else {
                    // Custom profile - retrieve platform name from metadata
                    $platform = isset($custom_profiles_meta[$url]) ? $custom_profiles_meta[$url] : '';
                    $data[3]['custom_profiles'][] = array(
                        'platform' => $platform,
                        'url' => $url,
                    );
                }
            }
        }

        return rest_ensure_response($data);
    }

    /**
     * Save wizard state
     */
    public function save_wizard_state($request)
    {
        $state = $request->get_json_params();

        update_option('swift_rank_wizard_state', $state);

        return rest_ensure_response(array('success' => true));
    }

    /**
     * Save step data
     */
    public function save_step_data($request)
    {
        $params = $request->get_json_params();
        $step = isset($params['step']) ? intval($params['step']) : 0;
        $data = isset($params['data']) ? $params['data'] : array();

        if ($step < 1 || $step > 6) {
            return new \WP_Error('invalid_step', __('Invalid step number', 'swift-rank'), array('status' => 400));
        }

        // Save data based on step
        switch ($step) {
            case 1:
                // Welcome step - no data to save
                break;

            case 2:
                // Site Info - save to Knowledge Graph
                $this->save_site_info($data);
                break;

            case 3:
                // Social Profiles - save to Knowledge Graph
                $this->save_social_profiles($data);
                break;

            case 4:
                // Content Types - save to auto-schema settings
                $this->save_content_types($data);
                break;

            case 5:
                // Enhancements - save to general settings
                $this->save_enhancements($data);
                break;

            case 6:
                // Upgrade step - no data to save (just tracking completion)
                break;
        }

        // Update wizard state
        $wizard_state = get_option('swift_rank_wizard_state', array(
            'completed_steps' => array(),
            'current_step' => 1,
            'is_complete' => false,
        ));

        if (!in_array($step, $wizard_state['completed_steps'])) {
            $wizard_state['completed_steps'][] = $step;
        }

        $wizard_state['current_step'] = min($step + 1, 5);
        update_option('swift_rank_wizard_state', $wizard_state);

        return rest_ensure_response(array('success' => true));
    }

    /**
     * Save site info (Step 2)
     */
    private function save_site_info($data)
    {
        $settings = get_option('swift_rank_settings', array());

        // Save Knowledge Graph type
        if (isset($data['knowledge_graph_type'])) {
            $settings['knowledge_graph_enabled'] = true;
            $settings['knowledge_graph_type'] = sanitize_text_field($data['knowledge_graph_type']);
        }

        // Save organization fields
        if (isset($data['organization_fields']) && is_array($data['organization_fields'])) {
            $settings['organization_fields'] = $this->sanitize_schema_fields($data['organization_fields']);
        }

        // Save person fields
        if (isset($data['person_fields']) && is_array($data['person_fields'])) {
            $settings['person_fields'] = $this->sanitize_schema_fields($data['person_fields']);
        }

        // Save local business fields
        if (isset($data['localbusiness_fields']) && is_array($data['localbusiness_fields'])) {
            $settings['localbusiness_fields'] = $this->sanitize_schema_fields($data['localbusiness_fields']);
        }

        update_option('swift_rank_settings', $settings);
    }

    /**
     * Sanitize schema fields array
     */
    private function sanitize_schema_fields($fields)
    {
        $sanitized = array();

        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitize_schema_fields($value);
            } elseif (filter_var($value, FILTER_VALIDATE_URL)) {
                $sanitized[$key] = esc_url_raw($value);
            } elseif (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $sanitized[$key] = sanitize_email($value);
            } else {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }

        return $sanitized;
    }

    /**
     * Save content types (Step 3)
     */
    private function save_content_types($data)
    {
        $settings = get_option('swift_rank_settings', array());

        // Post settings
        if (isset($data['post_enabled'])) {
            $settings['auto_schema_post_enabled'] = (bool) $data['post_enabled'];
        }
        if (isset($data['post_type'])) {
            $settings['auto_schema_post_type'] = sanitize_text_field($data['post_type']);
        }

        // Page settings
        if (isset($data['page_enabled'])) {
            $settings['auto_schema_page_enabled'] = (bool) $data['page_enabled'];
        }

        // Search settings
        if (isset($data['search_enabled'])) {
            $settings['auto_schema_search_enabled'] = (bool) $data['search_enabled'];
        }

        // WooCommerce settings
        if (isset($data['woocommerce_enabled']) && class_exists('WooCommerce')) {
            $settings['auto_schema_woocommerce_enabled'] = (bool) $data['woocommerce_enabled'];
        }

        update_option('swift_rank_settings', $settings);
    }

    /**
     * Save enhancements (Step 4)
     */
    private function save_enhancements($data)
    {
        $settings = get_option('swift_rank_settings', array());

        // Knowledge Graph settings
        if (isset($data['knowledge_graph_enabled'])) {
            $settings['knowledge_graph_enabled'] = (bool) $data['knowledge_graph_enabled'];
        }

        if (isset($data['breadcrumb_enabled'])) {
            $settings['breadcrumb_enabled'] = (bool) $data['breadcrumb_enabled'];
        }

        if (isset($data['sitelinks_searchbox'])) {
            $settings['sitelinks_searchbox'] = (bool) $data['sitelinks_searchbox'];
        }

        update_option('swift_rank_settings', $settings);
    }

    /**
     * Save social profiles (Step 3)
     */
    private function save_social_profiles($data)
    {
        $settings = get_option('swift_rank_settings', array());

        // Save individual social profile fields
        $social_fields = ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube'];
        foreach ($social_fields as $field) {
            if (isset($data[$field])) {
                $value = $data[$field];
                // Use sanitize_text_field if it looks like a variable ({...} or %%...%%)
                // Avoid using strpos('%') alone as it triggers on encoded URLs (e.g. %20)
                if ((strpos($value, '{') !== false && strpos($value, '}') !== false) || preg_match('/%%.+%%/', $value)) {
                    $settings[$field] = sanitize_text_field($value);
                } else {
                    $settings[$field] = esc_url_raw($value);
                }
            }
        }

        // Save custom profiles as repeater field
        if (!empty($data['custom_profiles']) && is_array($data['custom_profiles'])) {
            $custom_profiles = array();
            foreach ($data['custom_profiles'] as $profile) {
                $url = isset($profile['url']) ? $profile['url'] : '';
                // Check for variables
                if ((strpos($url, '{') !== false && strpos($url, '}') !== false) || preg_match('/%%.+%%/', $url)) {
                    $sanitized_url = sanitize_text_field($url);
                } else {
                    $sanitized_url = esc_url_raw($url);
                }

                $custom_profiles[] = array(
                    'platform' => isset($profile['platform']) ? sanitize_text_field($profile['platform']) : '',
                    'url' => $sanitized_url,
                );
            }
            $settings['custom_profiles'] = $custom_profiles;
        }

        update_option('swift_rank_settings', $settings);
    }

    /**
     * Complete wizard
     */
    public function complete_wizard($request)
    {
        $wizard_state = get_option('swift_rank_wizard_state', array());
        $wizard_state['is_complete'] = true;
        $wizard_state['completed_at'] = current_time('mysql');

        update_option('swift_rank_wizard_state', $wizard_state);

        // Delete the pending transient
        delete_transient('swift_rank_wizard_pending');

        return rest_ensure_response(array('success' => true));
    }
}

// Initialize with proper namespace
new \Swift_Rank\Admin\REST\Wizard_API();
