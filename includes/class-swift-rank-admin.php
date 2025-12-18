<?php
/**
 * Admin Settings Class
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Swift_Rank_Admin class
 */
class Swift_Rank_Admin
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
	 * @return Swift_Rank_Admin
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
		add_action('admin_menu', array($this, 'add_admin_menu'));
		add_action('admin_init', array($this, 'register_settings'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
	}

	/**
	 * Get tooltip HTML
	 *
	 * @param string $text Tooltip text.
	 * @return string
	 */
	private function get_tooltip_html($text)
	{
		return '<span class="swift-rank-tooltip"><span class="dashicons dashicons-editor-help"></span><span class="swift-rank-tooltip-text">' . esc_html($text) . '</span></span>';
	}

	/**
	 * Render input field with variable button
	 *
	 * @param array $args Field arguments.
	 * @return void
	 */
	private function render_input_field($args)
	{
		$defaults = array(
			'type' => 'text',
			'name' => '',
			'id' => '',
			'value' => '',
			'placeholder' => '',
			'class' => 'regular-text',
			'required' => false,
			'description' => '',
			'upload' => false,
		);

		$args = wp_parse_args($args, $defaults);

		// Remove HTML5 validation if field contains variables.
		$has_variable = !empty($args['value']) && preg_match('/\{[^}]+\}/', $args['value']);
		$input_type = $has_variable ? 'text' : $args['type'];

		?>
		<div class="swift-rank-field-wrapper">
			<div class="swift-rank-input-group">
				<input type="<?php echo esc_attr($input_type); ?>" name="<?php echo esc_attr($args['name']); ?>"
					id="<?php echo esc_attr($args['id']); ?>" value="<?php echo esc_attr($args['value']); ?>"
					class="<?php echo esc_attr($args['class'] . ' swift-rank-variable-field'); ?>"
					placeholder="<?php echo esc_attr($args['placeholder']); ?>" <?php echo $args['required'] && !$has_variable ? 'required' : ''; ?> />
				<div class="swift-rank-button-group">
					<?php if ($args['upload']): ?>
						<button type="button" class="button button-secondary swift-rank-upload-image-btn"
							data-target="<?php echo esc_attr($args['id']); ?>">
							<span class="dashicons dashicons-format-image"></span>
							<?php esc_html_e('Upload', 'swift-rank'); ?>
						</button>
					<?php endif; ?>
					<button type="button" class="button button-secondary swift-rank-insert-variable-btn"
						data-target="<?php echo esc_attr($args['id']); ?>">
						<span class="dashicons dashicons-plus-alt2"></span>
						<?php esc_html_e('Insert Variable', 'swift-rank'); ?>
					</button>
				</div>
			</div>
			<?php if (!empty($args['description'])): ?>
				<p class="description"><?php echo wp_kses_post($args['description']); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get required indicator HTML
	 *
	 * @return string
	 */
	private function get_required_indicator()
	{
		return '<span class="swift-rank-required-indicator" aria-hidden="true">*</span>';
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu()
	{
		// Main settings page.
		add_options_page(
			__('Swift Rank', 'swift-rank'),
			__('Swift Rank', 'swift-rank'),
			'manage_options',
			'swift-rank',
			array($this, 'render_settings_page')
		);

		// Schema Validator submenu under Tools.
		add_management_page(
			__('Schema Validator', 'swift-rank'),
			__('Schema Validator', 'swift-rank'),
			'manage_options',
			'schema-validator',
			array($this, 'render_validator_page')
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings()
	{
		register_setting('swift_rank_settings_group', 'swift_rank_settings', array($this, 'sanitize_settings'));

		// Organization schema section.
		add_settings_section(
			'swift_rank_organization_section',
			__('Knowledge Graph Settings', 'swift-rank'),
			array($this, 'organization_section_callback'),
			'swift_rank_knowledge_graph'
		);

		// Enable organization schema.
		add_settings_field(
			'swift_rank_organization_enabled',
			__('Enable Knowledge Graph Schema', 'swift-rank') . $this->get_tooltip_html(__('Enable Knowledge Graph schema on the homepage.', 'swift-rank')),
			array($this, 'organization_enabled_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// Data Type.
		add_settings_field(
			'swift_rank_organization_type',
			__('Data Type', 'swift-rank') . $this->get_tooltip_html(__('Select whether this site represents an Organization or a Person.', 'swift-rank')),
			array($this, 'organization_type_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// Organization: Industry.
		add_settings_field(
			'swift_rank_organization_industry',
			__('Industry', 'swift-rank') . $this->get_tooltip_html(__('The industry that best describes your organization.', 'swift-rank')),
			array($this, 'organization_industry_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section',
			array('class' => 'swift-rank-organization-only')
		);

		// Person: Job Title.
		add_settings_field(
			'swift_rank_organization_job_title',
			__('Job Title', 'swift-rank') . $this->get_tooltip_html(__('The job title of the person.', 'swift-rank')),
			array($this, 'organization_job_title_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section',
			array('class' => 'swift-rank-person-only')
		);

		// Person: Gender.
		add_settings_field(
			'swift_rank_organization_gender',
			__('Gender', 'swift-rank') . $this->get_tooltip_html(__('The gender of the person.', 'swift-rank')),
			array($this, 'organization_gender_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section',
			array('class' => 'swift-rank-person-only')
		);

		// Person: Works For.
		add_settings_field(
			'swift_rank_organization_works_for',
			__('Works For (Organization)', 'swift-rank') . $this->get_tooltip_html(__('The organization the person works for.', 'swift-rank')),
			array($this, 'organization_works_for_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section',
			array('class' => 'swift-rank-person-only')
		);

		// Organization name.
		add_settings_field(
			'swift_rank_organization_name',
			__('Name', 'swift-rank') . $this->get_required_indicator() . $this->get_tooltip_html(__('The legal name of the organization or person.', 'swift-rank')),
			array($this, 'organization_name_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// Organization logo.
		add_settings_field(
			'swift_rank_organization_logo',
			__('Logo/Image URL', 'swift-rank') . $this->get_tooltip_html(__('URL to the logo (Organization/LocalBusiness) or profile image (Person).', 'swift-rank')),
			array($this, 'organization_logo_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// Phone number.
		add_settings_field(
			'swift_rank_organization_phone',
			__('Phone Number', 'swift-rank') . $this->get_tooltip_html(__('Contact phone number.', 'swift-rank')),
			array($this, 'organization_phone_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// Email.
		add_settings_field(
			'swift_rank_organization_email',
			__('Email Address', 'swift-rank') . $this->get_tooltip_html(__('Contact email address.', 'swift-rank')),
			array($this, 'organization_email_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// Fax.
		add_settings_field(
			'swift_rank_organization_fax',
			__('Fax Number', 'swift-rank') . $this->get_tooltip_html(__('Fax number (optional).', 'swift-rank')),
			array($this, 'organization_fax_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section',
			array('class' => 'swift-rank-organization-only')
		);

		// Contact Type.
		add_settings_field(
			'swift_rank_organization_contact_type',
			__('Contact Type', 'swift-rank') . $this->get_tooltip_html(__('The type of contact (e.g., Customer Service, Sales, Technical Support).', 'swift-rank')),
			array($this, 'organization_contact_type_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section',
			array('class' => 'swift-rank-organization-only')
		);

		// Address.
		add_settings_field(
			'swift_rank_organization_address',
			__('Street Address', 'swift-rank') . $this->get_tooltip_html(__('Street address.', 'swift-rank')),
			array($this, 'organization_address_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// City.
		add_settings_field(
			'swift_rank_organization_city',
			__('City', 'swift-rank') . $this->get_tooltip_html(__('City.', 'swift-rank')),
			array($this, 'organization_city_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// State.
		add_settings_field(
			'swift_rank_organization_state',
			__('State/Region', 'swift-rank') . $this->get_tooltip_html(__('State or region.', 'swift-rank')),
			array($this, 'organization_state_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// Postal Code.
		add_settings_field(
			'swift_rank_organization_postal_code',
			__('Postal Code', 'swift-rank') . $this->get_tooltip_html(__('Postal/Zip code.', 'swift-rank')),
			array($this, 'organization_postal_code_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// Country.
		add_settings_field(
			'swift_rank_organization_country',
			__('Country', 'swift-rank') . $this->get_tooltip_html(__('Country.', 'swift-rank')),
			array($this, 'organization_country_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);

		// Price Range (for LocalBusiness).
		add_settings_field(
			'swift_rank_organization_price_range',
			__('Price Range', 'swift-rank') . $this->get_tooltip_html(__('Price range for the business (e.g., $, $$, $$$, $$$$). Only used for LocalBusiness.', 'swift-rank')),
			array($this, 'organization_price_range_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section',
			array('class' => 'swift-rank-localbusiness-only')
		);

		// Opening hours.
		add_settings_field(
			'swift_rank_organization_hours',
			__('Opening Hours', 'swift-rank') . $this->get_tooltip_html(__('Opening hours for the organization.', 'swift-rank')),
			array($this, 'organization_hours_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section',
			array('class' => 'swift-rank-localbusiness-only')
		);

		// Social profiles.
		add_settings_field(
			'swift_rank_organization_social',
			__('Social Media Profiles', 'swift-rank') . $this->get_tooltip_html(__('Links to social media profiles.', 'swift-rank')),
			array($this, 'organization_social_field_callback'),
			'swift_rank_knowledge_graph',
			'swift_rank_organization_section'
		);
	}

	/**
	 * Check if a value contains a variable pattern
	 *
	 * @param string $value Value to check.
	 * @return bool
	 */
	private function contains_variable($value)
	{
		return !empty($value) && preg_match('/\{[^}]+\}/', $value);
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

		if (isset($input['auto_schema'])) {
			$sanitized['auto_schema'] = (bool) $input['auto_schema'];
		}

		if (isset($input['organization_schema'])) {
			$sanitized['organization_schema'] = (bool) $input['organization_schema'];
		}

		if (isset($input['organization_type'])) {
			$sanitized['organization_type'] = sanitize_text_field($input['organization_type']);
		}

		if (isset($input['organization_industry'])) {
			$sanitized['organization_industry'] = sanitize_text_field($input['organization_industry']);
		}

		if (isset($input['organization_job_title'])) {
			$sanitized['organization_job_title'] = sanitize_text_field($input['organization_job_title']);
		}

		if (isset($input['organization_gender'])) {
			$sanitized['organization_gender'] = sanitize_text_field($input['organization_gender']);
		}

		if (isset($input['organization_works_for'])) {
			$sanitized['organization_works_for'] = sanitize_text_field($input['organization_works_for']);
		}

		if (isset($input['organization_name'])) {
			$sanitized['organization_name'] = sanitize_text_field($input['organization_name']);
		}

		if (isset($input['organization_logo'])) {
			// Allow variables in logo URL, validate URL only if no variables.
			$value = trim($input['organization_logo']);
			if ($this->contains_variable($value)) {
				$sanitized['organization_logo'] = sanitize_text_field($value);
			} else {
				$sanitized['organization_logo'] = esc_url_raw($value);
			}
		}

		if (isset($input['organization_phone'])) {
			// Allow variables or validate phone number format.
			$value = trim($input['organization_phone']);
			if ($this->contains_variable($value)) {
				$sanitized['organization_phone'] = sanitize_text_field($value);
			} else {
				// Basic phone validation - allow numbers, spaces, dashes, parentheses, plus.
				$sanitized['organization_phone'] = preg_replace('/[^0-9\s\-\(\)\+\.]/', '', $value);
			}
		}

		if (isset($input['organization_email'])) {
			// Allow variables or validate email format.
			$value = trim($input['organization_email']);
			if ($this->contains_variable($value)) {
				$sanitized['organization_email'] = sanitize_text_field($value);
			} else {
				$sanitized['organization_email'] = sanitize_email($value);
			}
		}

		if (isset($input['organization_fax'])) {
			// Allow variables or validate fax number format.
			$value = trim($input['organization_fax']);
			if ($this->contains_variable($value)) {
				$sanitized['organization_fax'] = sanitize_text_field($value);
			} else {
				// Basic fax validation - allow numbers, spaces, dashes, parentheses, plus.
				$sanitized['organization_fax'] = preg_replace('/[^0-9\s\-\(\)\+\.]/', '', $value);
			}
		}

		if (isset($input['organization_contact_type'])) {
			$sanitized['organization_contact_type'] = sanitize_text_field($input['organization_contact_type']);
		}

		if (isset($input['organization_address'])) {
			$sanitized['organization_address'] = sanitize_text_field($input['organization_address']);
		}

		if (isset($input['organization_city'])) {
			$sanitized['organization_city'] = sanitize_text_field($input['organization_city']);
		}

		if (isset($input['organization_state'])) {
			$sanitized['organization_state'] = sanitize_text_field($input['organization_state']);
		}

		if (isset($input['organization_postal_code'])) {
			$sanitized['organization_postal_code'] = sanitize_text_field($input['organization_postal_code']);
		}

		if (isset($input['organization_country'])) {
			$sanitized['organization_country'] = sanitize_text_field($input['organization_country']);
		}

		if (isset($input['organization_price_range'])) {
			$sanitized['organization_price_range'] = sanitize_text_field($input['organization_price_range']);
		}

		if (isset($input['organization_hours']) && is_array($input['organization_hours'])) {
			$sanitized['organization_hours'] = array();
			$days_of_week = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
			foreach ($days_of_week as $day) {
				if (isset($input['organization_hours'][$day]) && is_array($input['organization_hours'][$day])) {
					$sanitized['organization_hours'][$day] = array(
						'opens' => isset($input['organization_hours'][$day]['opens']) ? sanitize_text_field($input['organization_hours'][$day]['opens']) : '09:00',
						'closes' => isset($input['organization_hours'][$day]['closes']) ? sanitize_text_field($input['organization_hours'][$day]['closes']) : '17:00',
						'closed' => isset($input['organization_hours'][$day]['closed']) ? true : false,
					);
				}
			}
		}

		if (isset($input['organization_social']) && is_array($input['organization_social'])) {
			$sanitized['organization_social'] = array();
			foreach ($input['organization_social'] as $index => $profile) {
				if (is_array($profile) && isset($profile['url'])) {
					$url = trim($profile['url']);
					// Skip empty URLs.
					if (empty($url)) {
						continue;
					}
					// Allow variables or validate URL format.
					if ($this->contains_variable($url)) {
						$sanitized['organization_social'][] = array(
							'url' => sanitize_text_field($url),
						);
					} else {
						$sanitized_url = esc_url_raw($url);
						// Only add if URL is valid.
						if (!empty($sanitized_url)) {
							$sanitized['organization_social'][] = array(
								'url' => $sanitized_url,
							);
						}
					}
				}
			}
			// Re-index array to remove gaps.
			$sanitized['organization_social'] = array_values($sanitized['organization_social']);
		}

		return $sanitized;
	}

	/**
	 * Organization section callback
	 */
	public function organization_section_callback()
	{
		echo '<p>' . esc_html__('Add knowledge graph schema to identify your business or person on the homepage.', 'swift-rank') . '</p>';
	}

	/**
	 * Organization enabled field callback
	 */
	public function organization_enabled_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$organization_enabled = isset($options['organization_schema']) ? $options['organization_schema'] : false;
		?>
		<label>
			<input type="checkbox" name="swift_rank_settings[organization_schema]" value="1" <?php checked($organization_enabled, true); ?> />
			<?php esc_html_e('Add knowledge graph schema to homepage', 'swift-rank'); ?>
		</label>
		<?php
	}

	/**
	 * Organization type field callback
	 */
	public function organization_type_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$type = isset($options['organization_type']) ? $options['organization_type'] : 'Organization';
		?>
		<select name="swift_rank_settings[organization_type]" id="swift_rank_settings_organization_type">
			<option value="Organization" <?php selected($type, 'Organization'); ?>>
				<?php esc_html_e('Organization', 'swift-rank'); ?>
			</option>
			<option value="LocalBusiness" <?php selected($type, 'LocalBusiness'); ?>>
				<?php esc_html_e('Local Business', 'swift-rank'); ?>
			</option>
			<option value="Person" <?php selected($type, 'Person'); ?>><?php esc_html_e('Person', 'swift-rank'); ?></option>
		</select>
		<p class="description">
			<?php esc_html_e('Select the type of entity to represent. Choose "Local Business" for businesses with physical locations.', 'swift-rank'); ?>
		</p>
		<?php
	}

	/**
	 * Industry field callback
	 */
	public function organization_industry_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$industry = isset($options['organization_industry']) ? $options['organization_industry'] : '';
		$industries = array(
			'Technology' => 'Technology',
			'Finance' => 'Finance',
			'Healthcare' => 'Healthcare',
			'Retail' => 'Retail',
			'Education' => 'Education',
			'Entertainment' => 'Entertainment',
			'Real Estate' => 'Real Estate',
			'Construction' => 'Construction',
			'Manufacturing' => 'Manufacturing',
			'Transportation' => 'Transportation',
			'Hospitality' => 'Hospitality',
			'Consulting' => 'Consulting',
			'Legal' => 'Legal',
			'Non-Profit' => 'Non-Profit',
			'Other' => 'Other',
		);
		?>
		<select name="swift_rank_settings[organization_industry]" id="swift_rank_settings_organization_industry">
			<option value=""><?php esc_html_e('Select Industry', 'swift-rank'); ?></option>
			<?php foreach ($industries as $key => $label): ?>
				<option value="<?php echo esc_attr($key); ?>" <?php selected($industry, $key); ?>>
					<?php echo esc_html($label); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<p class="description">
			<?php esc_html_e('Select the industry that best describes your organization.', 'swift-rank'); ?>
		</p>
		<?php
	}

	/**
	 * Job Title field callback
	 */
	public function organization_job_title_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$value = isset($options['organization_job_title']) ? $options['organization_job_title'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_job_title]',
				'id' => 'swift_rank_settings_organization_job_title',
				'value' => $value,
				'placeholder' => 'Software Engineer',
				'description' => __('Job title of the person.', 'swift-rank'),
			)
		);
	}

	/**
	 * Gender field callback
	 */
	public function organization_gender_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$value = isset($options['organization_gender']) ? $options['organization_gender'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_gender]',
				'id' => 'swift_rank_settings_organization_gender',
				'value' => $value,
				'placeholder' => 'Male',
				'description' => __('Gender of the person (e.g., Male, Female, Non-binary).', 'swift-rank'),
			)
		);
	}

	/**
	 * Works For field callback
	 */
	public function organization_works_for_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$value = isset($options['organization_works_for']) ? $options['organization_works_for'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_works_for]',
				'id' => 'swift_rank_settings_organization_works_for',
				'value' => $value,
				'placeholder' => 'Acme Corporation',
				'description' => __('Organization name the person works for (appears in Person schema only).', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization name field callback
	 */
	public function organization_name_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$name = isset($options['organization_name']) ? $options['organization_name'] : '{site_name}';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_name]',
				'id' => 'swift_rank_settings_organization_name',
				'value' => $name,
				'placeholder' => '',
				'required' => true,
				'description' => __('Use {site_name} for automatic site name or enter a custom name.', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization logo field callback
	 */
	public function organization_logo_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$logo = isset($options['organization_logo']) ? $options['organization_logo'] : '';

		$this->render_input_field(
			array(
				'type' => 'url',
				'name' => 'swift_rank_settings[organization_logo]',
				'id' => 'swift_rank_settings_organization_logo',
				'value' => $logo,
				'placeholder' => 'https://example.com/logo.png',
				'upload' => true,
				'description' => __('Enter the full URL to your organization logo. Recommended: square or 16:9 aspect ratio, max height 60px, transparent PNG preferred.', 'swift-rank'),
			)
		);

		if (!empty($logo)):
			?>
			<div class="swift-rank-image-preview">
				<img src="<?php echo esc_url($logo); ?>" alt="<?php esc_attr_e('Logo preview', 'swift-rank'); ?>" />
			</div>
			<?php
		endif;
	}

	/**
	 * Organization phone field callback
	 */
	public function organization_phone_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$phone = isset($options['organization_phone']) ? $options['organization_phone'] : '';

		$this->render_input_field(
			array(
				'type' => 'tel',
				'name' => 'swift_rank_settings[organization_phone]',
				'id' => 'swift_rank_settings_organization_phone',
				'value' => $phone,
				'placeholder' => '+1-555-123-4567',
				'description' => __('Enter phone in international format (e.g., +1-555-123-4567). Use variables like {meta:phone} for dynamic content.', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization email field callback
	 */
	public function organization_email_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$email = isset($options['organization_email']) ? $options['organization_email'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_email]',
				'id' => 'swift_rank_settings_organization_email',
				'value' => $email,
				'placeholder' => 'contact@example.com',
				'description' => __('Enter contact email address. Use variables like {option:admin_email} for dynamic content.', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization fax field callback
	 */
	public function organization_fax_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$fax = isset($options['organization_fax']) ? $options['organization_fax'] : '';

		$this->render_input_field(
			array(
				'type' => 'tel',
				'name' => 'swift_rank_settings[organization_fax]',
				'id' => 'swift_rank_settings_organization_fax',
				'value' => $fax,
				'placeholder' => '+1-555-123-4567',
				'description' => __('Enter fax number in international format (optional).', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization contact type field callback
	 */
	public function organization_contact_type_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$value = isset($options['organization_contact_type']) ? $options['organization_contact_type'] : 'Customer Service';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_contact_type]',
				'id' => 'swift_rank_settings_organization_contact_type',
				'value' => $value,
				'placeholder' => 'Customer Service',
				'description' => __('Contact type (e.g., Customer Service, Sales, Technical Support). Appears in contact point schema.', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization address field callback
	 */
	public function organization_address_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$address = isset($options['organization_address']) ? $options['organization_address'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_address]',
				'id' => 'swift_rank_settings_organization_address',
				'value' => $address,
				'placeholder' => '123 Main Street',
				'description' => __('Street address for your organization (e.g., 123 Main Street, Suite 100).', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization city field callback
	 */
	public function organization_city_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$city = isset($options['organization_city']) ? $options['organization_city'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_city]',
				'id' => 'swift_rank_settings_organization_city',
				'value' => $city,
				'placeholder' => 'New York',
				'description' => __('City name for postal address (e.g., New York, Los Angeles).', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization state field callback
	 */
	public function organization_state_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$state = isset($options['organization_state']) ? $options['organization_state'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_state]',
				'id' => 'swift_rank_settings_organization_state',
				'value' => $state,
				'placeholder' => 'NY',
				'description' => __('State, province, or region (e.g., NY, California, Ontario).', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization postal code field callback
	 */
	public function organization_postal_code_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$postal_code = isset($options['organization_postal_code']) ? $options['organization_postal_code'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_postal_code]',
				'id' => 'swift_rank_settings_organization_postal_code',
				'value' => $postal_code,
				'placeholder' => '10001',
				'description' => __('Postal code or ZIP code (e.g., 10001, SW1A 1AA, 75001).', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization country field callback
	 */
	public function organization_country_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$country = isset($options['organization_country']) ? $options['organization_country'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_country]',
				'id' => 'swift_rank_settings_organization_country',
				'value' => $country,
				'placeholder' => 'United States',
				'description' => __('Country name (e.g., United States, United Kingdom, Canada).', 'swift-rank'),
			)
		);
	}

	/**
	 * Organization price range field callback
	 */
	public function organization_price_range_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$price_range = isset($options['organization_price_range']) ? $options['organization_price_range'] : '';

		$this->render_input_field(
			array(
				'type' => 'text',
				'name' => 'swift_rank_settings[organization_price_range]',
				'id' => 'swift_rank_settings_organization_price_range',
				'value' => $price_range,
				'placeholder' => '$$',
				'description' => __('Price range for LocalBusiness only. Common formats:', 'swift-rank'),
			)
		);
		?>
		<p class="description" style="margin-top: 4px; padding-left: 12px; border-left: 3px solid #ddd;">
			<strong><?php esc_html_e('Symbol format:', 'swift-rank'); ?></strong> $ (inexpensive), $$ (moderate), $$$
			(expensive), $$$$ (very expensive)<br>
			<strong><?php esc_html_e('Range format:', 'swift-rank'); ?></strong> $10-$50, $25-$100, etc.<br>
			<strong><?php esc_html_e('Examples:', 'swift-rank'); ?></strong> "$$", "$15-$35", "$$$"
		</p>
		<?php
	}

	/**
	 * Organization hours field callback
	 */
	public function organization_hours_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$hours = isset($options['organization_hours']) ? $options['organization_hours'] : array();

		// Ensure hours is an array, not a string (for backwards compatibility).
		if (!is_array($hours)) {
			$hours = array();
		}

		// Default hours for all days.
		$days_of_week = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$default_hours = array(
			'Monday' => array(
				'closed' => false,
				'opens' => '09:00',
				'closes' => '17:00',
			),
			'Tuesday' => array(
				'closed' => false,
				'opens' => '09:00',
				'closes' => '17:00',
			),
			'Wednesday' => array(
				'closed' => false,
				'opens' => '09:00',
				'closes' => '17:00',
			),
			'Thursday' => array(
				'closed' => false,
				'opens' => '09:00',
				'closes' => '17:00',
			),
			'Friday' => array(
				'closed' => false,
				'opens' => '09:00',
				'closes' => '17:00',
			),
			'Saturday' => array(
				'closed' => true,
				'opens' => '09:00',
				'closes' => '17:00',
			),
			'Sunday' => array(
				'closed' => true,
				'opens' => '09:00',
				'closes' => '17:00',
			),
		);

		// Merge with saved hours.
		foreach ($days_of_week as $day) {
			if (!isset($hours[$day]) || !is_array($hours[$day])) {
				$hours[$day] = $default_hours[$day];
			}
		}
		?>
		<div class="swift-rank-opening-hours-container">
			<?php
			foreach ($days_of_week as $day):
				$day_data = isset($hours[$day]) ? $hours[$day] : $default_hours[$day];
				$is_closed = isset($day_data['closed']) && $day_data['closed'];
				?>
				<div class="swift-rank-hours-row <?php echo $is_closed ? 'closed' : ''; ?>"
					data-day="<?php echo esc_attr($day); ?>">
					<label class="day-label"><?php echo esc_html($day); ?></label>
					<label style="display: flex; align-items: center; gap: 6px; font-size: 12px;">
						<input type="checkbox" name="swift_rank_settings[organization_hours][<?php echo esc_attr($day); ?>][closed]"
							value="1" class="swift-rank-closed-checkbox" <?php checked($is_closed); ?> />
						<span class="swift-rank-closed-label">Closed</span>
					</label>
					<div>
						<label style="display: block; margin-bottom: 2px; font-size: 11px; color: #646970;">Opens</label>
						<input type="time" name="swift_rank_settings[organization_hours][<?php echo esc_attr($day); ?>][opens]"
							value="<?php echo esc_attr($day_data['opens'] ?? '09:00'); ?>"
							class="regular-text swift-rank-opens-time" />
					</div>
					<div>
						<label style="display: block; margin-bottom: 2px; font-size: 11px; color: #646970;">Closes</label>
						<input type="time" name="swift_rank_settings[organization_hours][<?php echo esc_attr($day); ?>][closes]"
							value="<?php echo esc_attr($day_data['closes'] ?? '17:00'); ?>"
							class="regular-text swift-rank-closes-time" />
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="description" style="margin-top: 12px;">
			<?php esc_html_e('Check "Closed" for days when your business is not open. Times are in 24-hour format.', 'swift-rank'); ?>
		</p>
		<?php
	}

	/**
	 * Organization social field callback
	 */
	public function organization_social_field_callback()
	{
		$options = get_option('swift_rank_settings', array());
		$social = isset($options['organization_social']) ? $options['organization_social'] : array();

		// Convert old textarea format to array if needed.
		if (!is_array($social)) {
			// It's a string, convert it.
			if (!empty($social)) {
				$social_lines = array_filter(array_map('trim', explode("\n", $social)));
				$social = array();
				foreach ($social_lines as $url) {
					$social[] = array('url' => $url);
				}
			} else {
				$social = array();
			}
		} else {
			// Already an array, but check if it's the new format.
			// If any item is just a string (not ['url' => ...]), convert it.
			$converted = array();
			foreach ($social as $item) {
				if (is_array($item) && isset($item['url'])) {
					// Already in new format.
					$converted[] = $item;
				} elseif (is_string($item)) {
					// Old format: just a URL string.
					$converted[] = array('url' => $item);
				}
			}
			$social = $converted;
		}

		// Ensure at least one empty item exists.
		if (empty($social)) {
			$social = array(
				array('url' => ''),
			);
		}
		?>
		<div class="swift-rank-repeater swift-rank-settings-repeater" data-repeater-path="organization_social">
			<div id="swift_rank_repeater_organization_social" class="swift-rank-repeater-items">
				<?php
				foreach ($social as $index => $profile):
					$url = $profile['url'] ?? '';
					$has_variable = !empty($url) && preg_match('/\{[^}]+\}/', $url);
					$input_type = $has_variable ? 'text' : 'url';
					?>
					<div class="swift-rank-repeater-item">
						<button type="button" class="button button-small button-link-delete swift-rank-remove-repeater-item">
							<span class="dashicons dashicons-no-alt"></span>
							<?php esc_html_e('Remove', 'swift-rank'); ?>
						</button>
						<div class="swift-rank-repeater-item-content">
							<label class="swift-rank-repeater-item-label">
								<?php esc_html_e('Social Media Profile URL', 'swift-rank'); ?>
							</label>
							<div class="swift-rank-input-group">
								<input type="<?php echo esc_attr($input_type); ?>"
									name="swift_rank_settings[organization_social][<?php echo esc_attr($index); ?>][url]"
									id="swift_rank_settings_organization_social_<?php echo esc_attr($index); ?>"
									value="<?php echo esc_attr($url); ?>" class="regular-text swift-rank-variable-field"
									placeholder="https://facebook.com/yourpage" />
								<div class="swift-rank-button-group">
									<button type="button" class="button button-secondary swift-rank-insert-variable-btn"
										data-target="swift_rank_settings_organization_social_<?php echo esc_attr($index); ?>">
										<span class="dashicons dashicons-plus-alt2"></span>
										<?php esc_html_e('Insert Variable', 'swift-rank'); ?>
									</button>
								</div>
							</div>
							<p class="description">
								<?php esc_html_e('Enter a social media profile URL (e.g., Facebook, Twitter, LinkedIn, Instagram)', 'swift-rank'); ?>
							</p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<button type="button" class="button button-secondary swift-rank-add-repeater-item"
				data-repeater-id="swift_rank_repeater_organization_social">
				<span class="dashicons dashicons-plus-alt"></span>
				<?php esc_html_e('Add Social Profile', 'swift-rank'); ?>
			</button>
			<p class="description">
				<?php esc_html_e('Add social media profile URLs for your organization. These appear in your Knowledge Graph.', 'swift-rank'); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page()
	{
		if (!current_user_can('manage_options')) {
			wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'swift-rank'));
		}
		?>
		<div class="wrap swift-rank-admin">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

			<div class="swift-rank-settings-header">
				<p><?php esc_html_e('Configure Schema.org structured data settings for your WordPress site.', 'swift-rank'); ?>
				</p>
			</div>

			<?php
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			// This is a tab navigation query parameter, not form data requiring nonce verification.
			$active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'knowledge_graph';
			// phpcs:enable WordPress.Security.NonceVerification.Recommended
			?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=swift-rank&tab=knowledge_graph"
					class="nav-tab <?php echo 'knowledge_graph' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Knowledge Graph', 'swift-rank'); ?></a>
				<a href="?page=swift-rank&tab=help"
					class="nav-tab <?php echo 'help' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Help', 'swift-rank'); ?></a>
			</h2>

			<?php if ('help' === $active_tab): ?>
				<?php $this->render_help_tab(); ?>
			<?php else: ?>
				<form method="post" action="options.php">
					<?php
					settings_fields('swift_rank_settings_group');
					do_settings_sections('swift_rank_knowledge_graph');
					submit_button();
					?>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render Schema Validator page
	 */
	public function render_validator_page()
	{
		$home_url = esc_url(get_home_url());
		?>
		<div class="wrap">
			<h1><?php esc_html_e('Schema Validator', 'swift-rank'); ?></h1>
			<p><?php esc_html_e('Test and validate your schema markup with these tools:', 'swift-rank'); ?></p>

			<div class="swift-rank-help-section" style="max-width: 1200px;">
				<div class="postbox">
					<div class="inside">
						<h2 style="margin-top: 0;"><?php esc_html_e('Quick Validation', 'swift-rank'); ?></h2>
						<p><?php esc_html_e('Test your homepage schema with these validation tools:', 'swift-rank'); ?></p>

						<div
							style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px; margin-top: 20px;">
							<div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 16px;">
								<h3 style="margin-top: 0;">
									<span class="dashicons dashicons-google"
										style="font-size: 20px; vertical-align: middle;"></span>
									<?php esc_html_e('Google Rich Results Test', 'swift-rank'); ?>
								</h3>
								<p><?php esc_html_e('Test how Google sees your structured data and check for rich results eligibility.', 'swift-rank'); ?>
								</p>
								<a href="https://search.google.com/test/rich-results?url=<?php echo urlencode($home_url); ?>"
									class="button button-primary" target="_blank" rel="noopener">
									<?php esc_html_e('Test with Google', 'swift-rank'); ?> →
								</a>
							</div>

							<div style="background: #fff8e5; border-left: 4px solid #dba617; padding: 16px;">
								<h3 style="margin-top: 0;">
									<span class="dashicons dashicons-yes-alt"
										style="font-size: 20px; vertical-align: middle;"></span>
									<?php esc_html_e('Schema.org Validator', 'swift-rank'); ?>
								</h3>
								<p><?php esc_html_e('Validate your schema markup against official Schema.org standards.', 'swift-rank'); ?>
								</p>
								<a href="https://validator.schema.org/#url=<?php echo urlencode($home_url); ?>"
									class="button button-primary" target="_blank" rel="noopener">
									<?php esc_html_e('Validate Schema', 'swift-rank'); ?> →
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="postbox" style="margin-top: 20px;">
					<div class="inside">
						<h3 style="margin-top: 0;"><?php esc_html_e('Manual Inspection', 'swift-rank'); ?></h3>
						<ol style="line-height: 1.8;">
							<li><strong><?php esc_html_e('View Page Source', 'swift-rank'); ?></strong> -
								<?php esc_html_e('Visit your homepage and press Ctrl+U (Windows) or Cmd+U (Mac) to view the source code.', 'swift-rank'); ?>
							</li>
							<li><strong><?php esc_html_e('Search for Schema', 'swift-rank'); ?></strong> -
								<?php esc_html_e('Look for "Swift Rank" comment or search for "application/ld+json" to find the schema output.', 'swift-rank'); ?>
							</li>
							<li><strong><?php esc_html_e('Verify Data', 'swift-rank'); ?></strong> -
								<?php esc_html_e('Check that all your organization information appears correctly in the JSON-LD script.', 'swift-rank'); ?>
							</li>
						</ol>
						<a href="<?php echo esc_url($home_url); ?>" class="button button-secondary" target="_blank">
							<?php esc_html_e('Open Homepage', 'swift-rank'); ?> →
						</a>
					</div>
				</div>

				<div class="postbox" style="margin-top: 20px;">
					<div class="inside">
						<h3 style="margin-top: 0;"><?php esc_html_e('Common Validation Errors', 'swift-rank'); ?></h3>
						<table class="widefat striped">
							<thead>
								<tr>
									<th><?php esc_html_e('Error', 'swift-rank'); ?></th>
									<th><?php esc_html_e('Solution', 'swift-rank'); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php esc_html_e('Missing required field', 'swift-rank'); ?></td>
									<td><?php esc_html_e('Fill in all required fields marked with * in the Knowledge Graph settings.', 'swift-rank'); ?>
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e('Invalid URL format', 'swift-rank'); ?></td>
									<td><?php esc_html_e('Ensure all URLs start with https:// or http:// and are properly formatted.', 'swift-rank'); ?>
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e('Invalid phone number', 'swift-rank'); ?></td>
									<td><?php esc_html_e('Use international format with country code (e.g., +1-555-123-4567).', 'swift-rank'); ?>
									</td>
								</tr>
								<tr>
									<td><?php esc_html_e('Logo too large', 'swift-rank'); ?></td>
									<td><?php esc_html_e('Google recommends logos with max height of 60px for best results.', 'swift-rank'); ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render help tab content
	 */
	private function render_help_tab()
	{
		?>
		<div class="swift-rank-help-section" style="max-width: 1200px;">
			<h2><?php esc_html_e('Knowledge Graph Settings', 'swift-rank'); ?></h2>
			<p><?php esc_html_e('Learn how to configure your Knowledge Graph schema for optimal search engine visibility.', 'swift-rank'); ?>
			</p>

			<div class="postbox" style="margin-top: 20px;">
				<div class="inside">
					<h3 style="margin-top: 0;"><?php esc_html_e('What is Knowledge Graph?', 'swift-rank'); ?></h3>
					<p><?php esc_html_e('The Knowledge Graph is structured data that tells search engines important information about your organization, business, or person. This helps search engines display rich information about you in search results.', 'swift-rank'); ?>
					</p>

					<h4><?php esc_html_e('Schema Types', 'swift-rank'); ?></h4>
					<ul style="line-height: 1.8;">
						<li><strong><?php esc_html_e('Organization', 'swift-rank'); ?></strong> -
							<?php esc_html_e('For companies, corporations, and general businesses without physical locations.', 'swift-rank'); ?>
						</li>
						<li><strong><?php esc_html_e('LocalBusiness', 'swift-rank'); ?></strong> -
							<?php esc_html_e('For businesses with physical locations, operating hours, and price ranges (e.g., restaurants, stores, offices).', 'swift-rank'); ?>
						</li>
						<li><strong><?php esc_html_e('Person', 'swift-rank'); ?></strong> -
							<?php esc_html_e('For personal websites, portfolios, or individual professionals (e.g., freelancers, authors, consultants).', 'swift-rank'); ?>
						</li>
					</ul>
				</div>
			</div>

			<div class="postbox" style="margin-top: 20px;">
				<div class="inside">
					<h3 style="margin-top: 0;"><?php esc_html_e('Important Fields', 'swift-rank'); ?></h3>
					<table class="widefat striped">
						<thead>
							<tr>
								<th><?php esc_html_e('Field', 'swift-rank'); ?></th>
								<th><?php esc_html_e('Description', 'swift-rank'); ?></th>
								<th><?php esc_html_e('Required', 'swift-rank'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><strong><?php esc_html_e('Name', 'swift-rank'); ?></strong></td>
								<td><?php esc_html_e('The legal name of your organization or your personal name. Use variables like {site_name} for dynamic content.', 'swift-rank'); ?>
								</td>
								<td><span style="color: #d63638;">✓</span></td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e('Logo / Image', 'swift-rank'); ?></strong></td>
								<td><?php esc_html_e('URL to your logo (for organizations) or profile photo (for persons). Recommended: square or 16:9 ratio, max 60px height, PNG format.', 'swift-rank'); ?>
								</td>
								<td><?php esc_html_e('Recommended', 'swift-rank'); ?></td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e('Contact Information', 'swift-rank'); ?></strong></td>
								<td><?php esc_html_e('Phone, email, and fax numbers help users contact you directly from search results. Use international format for phone numbers.', 'swift-rank'); ?>
								</td>
								<td><?php esc_html_e('Optional', 'swift-rank'); ?></td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e('Address', 'swift-rank'); ?></strong></td>
								<td><?php esc_html_e('Full postal address including street, city, state, postal code, and country. Important for local businesses.', 'swift-rank'); ?>
								</td>
								<td><?php esc_html_e('Optional', 'swift-rank'); ?></td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e('Social Media Profiles', 'swift-rank'); ?></strong></td>
								<td><?php esc_html_e('Links to your official social media accounts (Facebook, Twitter, LinkedIn, Instagram, etc.). Helps verify your identity.', 'swift-rank'); ?>
								</td>
								<td><?php esc_html_e('Recommended', 'swift-rank'); ?></td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e('Opening Hours', 'swift-rank'); ?></strong></td>
								<td><?php esc_html_e('Business hours for each day of the week. Only for LocalBusiness schema type.', 'swift-rank'); ?>
								</td>
								<td><?php esc_html_e('LocalBusiness only', 'swift-rank'); ?></td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e('Price Range', 'swift-rank'); ?></strong></td>
								<td><?php esc_html_e('Indicates pricing level using $, $$, $$$, or $$$$ symbols. Only for LocalBusiness schema type.', 'swift-rank'); ?>
								</td>
								<td><?php esc_html_e('LocalBusiness only', 'swift-rank'); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<h2 style="margin-top: 40px;"><?php esc_html_e('Available Variables', 'swift-rank'); ?></h2>
			<p><?php esc_html_e('You can use these variables in your schema fields. They will be replaced with actual values when the schema is output.', 'swift-rank'); ?>
			</p>

			<div
				style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-top: 20px;">
				<div class="postbox">
					<div class="inside">
						<h3 style="margin-top: 0;"><?php esc_html_e('🌐 Site Variables', 'swift-rank'); ?></h3>
						<table class="widefat striped">
							<thead>
								<tr>
									<th><?php esc_html_e('Variable', 'swift-rank'); ?></th>
									<th><?php esc_html_e('Description', 'swift-rank'); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><code>{site_name}</code></td>
									<td><?php esc_html_e('Your WordPress site name', 'swift-rank'); ?></td>
								</tr>
								<tr>
									<td><code>{site_url}</code></td>
									<td><?php esc_html_e('Your site home URL', 'swift-rank'); ?></td>
								</tr>
								<tr>
									<td><code>{site_description}</code></td>
									<td><?php esc_html_e('Your site tagline/description', 'swift-rank'); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="postbox">
					<div class="inside">
						<h3 style="margin-top: 0;"><?php esc_html_e('🔧 Custom Variables', 'swift-rank'); ?></h3>
						<table class="widefat striped">
							<thead>
								<tr>
									<th><?php esc_html_e('Variable', 'swift-rank'); ?></th>
									<th><?php esc_html_e('Description', 'swift-rank'); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><code>{option:option_name}</code></td>
									<td><?php esc_html_e('Get any WordPress option value (replace "option_name" with actual option name)', 'swift-rank'); ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="postbox" style="margin-top: 20px;">
				<div class="inside">
					<h3 style="margin-top: 0;"><?php esc_html_e('💡 How to Use Variables', 'swift-rank'); ?></h3>
					<ol style="line-height: 1.8;">
						<li><strong><?php esc_html_e('Click "Insert Variable" button', 'swift-rank'); ?></strong> -
							<?php esc_html_e('Look for the button next to input fields in the settings.', 'swift-rank'); ?>
						</li>
						<li><strong><?php esc_html_e('Select a variable', 'swift-rank'); ?></strong> -
							<?php esc_html_e('Choose from the dropdown menu of available variables.', 'swift-rank'); ?>
						</li>
						<li><strong><?php esc_html_e('Variable is inserted', 'swift-rank'); ?></strong> -
							<?php esc_html_e('The variable will be added to the field at cursor position.', 'swift-rank'); ?>
						</li>
						<li><strong><?php esc_html_e('Mix with text', 'swift-rank'); ?></strong> -
							<?php esc_html_e('You can combine variables with static text as needed.', 'swift-rank'); ?>
						</li>
					</ol>

					<div style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 12px; margin-top: 16px;">
						<h4 style="margin-top: 0;"><?php esc_html_e('Example Usage', 'swift-rank'); ?></h4>
						<p><strong><?php esc_html_e('Organization Name:', 'swift-rank'); ?></strong> <code>{site_name}</code>
						</p>
						<p><strong><?php esc_html_e('Custom Option:', 'swift-rank'); ?></strong>
							<code>{option:blogdescription}</code>
						</p>
						<p style="margin-bottom: 0;"><strong><?php esc_html_e('Mixed:', 'swift-rank'); ?></strong>
							<code>Contact {site_name} at {option:admin_email}</code>
						</p>
					</div>
				</div>
			</div>

			<div class="postbox" style="margin-top: 20px;">
				<div class="inside">
					<h3 style="margin-top: 0;"><?php esc_html_e('🔍 Testing Your Schema', 'swift-rank'); ?></h3>
					<p><?php esc_html_e('After configuring your schema settings, validate it with these tools:', 'swift-rank'); ?>
					</p>
					<ul style="line-height: 1.8;">
						<li>
							<strong><a href="https://search.google.com/test/rich-results" target="_blank"
									rel="noopener"><?php esc_html_e('Google Rich Results Test', 'swift-rank'); ?></a></strong><br>
							<span
								class="description"><?php esc_html_e('Test how Google sees your structured data and check for errors.', 'swift-rank'); ?></span>
						</li>
						<li style="margin-top: 12px;">
							<strong><a href="https://validator.schema.org/" target="_blank"
									rel="noopener"><?php esc_html_e('Schema.org Validator', 'swift-rank'); ?></a></strong><br>
							<span
								class="description"><?php esc_html_e('Validate your schema markup against Schema.org standards.', 'swift-rank'); ?></span>
						</li>
						<li style="margin-top: 12px;">
							<strong><?php esc_html_e('View Page Source', 'swift-rank'); ?></strong><br>
							<span
								class="description"><?php esc_html_e('Visit your homepage and view source (Ctrl+U / Cmd+U). Look for JSON-LD script tag in the <head> section.', 'swift-rank'); ?></span>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueue admin assets
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets($hook)
	{
		// Only load on Swift Rank admin pages and validator page.
		if (false === strpos($hook, 'swift-rank') && false === strpos($hook, 'schema-validator')) {
			return;
		}

		// Enqueue WordPress media library.
		wp_enqueue_media();

		wp_enqueue_style(
			'swift-rank-admin',
			SWIFT_RANK_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			SWIFT_RANK_VERSION
		);

		wp_enqueue_script(
			'swift-rank-admin',
			SWIFT_RANK_PLUGIN_URL . 'assets/js/admin.js',
			array('jquery'),
			SWIFT_RANK_VERSION,
			true
		);

		// Add schema variables for Insert Variable dropdown.
		wp_add_inline_script(
			'swift-rank-admin',
			'var swiftRankSchemaVariables = ' . wp_json_encode($this->get_schema_variables()) . ';',
			'before'
		);
	}

	/**
	 * Get schema variables for Insert Variable dropdown
	 *
	 * @return array
	 */
	private function get_schema_variables()
	{
		return array(
			'Site Info' => array(
				array(
					'label' => 'Site Name',
					'value' => '{site_name}',
				),
				array(
					'label' => 'Site URL',
					'value' => '{site_url}',
				),
				array(
					'label' => 'Site Description',
					'value' => '{site_description}',
				),
			),
			'Custom Fields' => array(
				array(
					'label' => 'Option Value (replace option_name)',
					'value' => '{option:option_name}',
				),
			),
		);
	}
}
