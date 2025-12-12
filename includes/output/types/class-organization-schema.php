<?php
/**
 * Organization Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_Organization class
 *
 * Builds Organization and LocalBusiness schema types.
 */
class Schema_Organization implements Schema_Builder_Interface
{

	/**
	 * Build organization schema from fields
	 *
	 * @param array $fields Field values.
	 * @return array Schema array (without @context).
	 */
	public function build($fields)
	{
		$org_type = !empty($fields['organizationType']) ? $fields['organizationType'] : 'Organization';

		$schema = array(
			'@type' => $org_type,
		);

		if (!empty($fields['name'])) {
			$schema['name'] = $fields['name'];
		}

		if (!empty($fields['url'])) {
			$schema['url'] = $fields['url'];
		}

		if (!empty($fields['description'])) {
			$schema['description'] = $fields['description'];
		}

		// Logo fallback logic
		$logo_url = (isset($fields['logo']) && !empty($fields['logo'])) ? $fields['logo'] : '';

		if (empty($logo_url) && defined('SWIFT_RANK_PRO_VERSION')) {
			$settings = get_option('swift_rank_settings', array());
			if (!empty($settings['default_image'])) {
				$logo_url = $settings['default_image'];
			}
		}

		if (empty($logo_url)) {
			$logo_url = '{site_logo}';
		}

		if (!empty($logo_url)) {
			// Ensure we don't output empty string if {site_logo} resolves to nothing (unlikely due to falbacks)
			$schema['logo'] = $logo_url;
		}

		// Image logic (mirrors Logo logic)
		$image_url = (isset($fields['image']) && !empty($fields['image'])) ? $fields['image'] : '';

		if (empty($image_url) && defined('SWIFT_RANK_PRO_VERSION')) {
			$settings = get_option('swift_rank_settings', array());
			if (!empty($settings['default_image'])) {
				$image_url = $settings['default_image'];
			}
		}

		if (empty($image_url)) {
			$image_url = '{site_logo}';
		}

		if (!empty($image_url)) {
			$schema['image'] = $image_url;
		}

		// Contact information
		if (!empty($fields['phone']) || !empty($fields['email'])) {
			if (!empty($fields['phone'])) {
				$schema['telephone'] = $fields['phone'];
			}
			if (!empty($fields['email'])) {
				$schema['email'] = $fields['email'];
			}
		}

		// Address
		$has_address = false;
		$address = array('@type' => 'PostalAddress');

		if (!empty($fields['streetAddress'])) {
			$address['streetAddress'] = $fields['streetAddress'];
			$has_address = true;
		}

		if (!empty($fields['city'])) {
			$address['addressLocality'] = $fields['city'];
			$has_address = true;
		}

		if (!empty($fields['state'])) {
			$address['addressRegion'] = $fields['state'];
			$has_address = true;
		}

		if (!empty($fields['postalCode'])) {
			$address['postalCode'] = $fields['postalCode'];
			$has_address = true;
		}

		if (!empty($fields['country'])) {
			$address['addressCountry'] = $fields['country'];
			$has_address = true;
		}

		if ($has_address) {
			$schema['address'] = $address;
		}

		// Social profiles (sameAs)
		if (!empty($fields['socialProfiles']) && is_array($fields['socialProfiles'])) {
			$social_array = $this->build_social_profiles($fields['socialProfiles']);
			if (!empty($social_array)) {
				$schema['sameAs'] = $social_array;
			}
		}

		return $schema;
	}

	/**
	 * Build social profiles array
	 *
	 * @param array $profiles Social profile data.
	 * @return array
	 */
	private function build_social_profiles($profiles)
	{
		$social_array = array();

		// Patterns for URLs that should be filtered out (but NOT template variables)
		$invalid_patterns = array(
			'/wp-admin/',            // Admin URLs
			'/^http:\/\/site_url/',  // Placeholder http://site_url (literal string, not variable)
			'/^https:\/\/site_url/', // Placeholder https://site_url (literal string, not variable)
		);

		foreach ($profiles as $profile) {
			$url = '';

			if (is_array($profile) && !empty($profile['url'])) {
				$url = trim($profile['url']);
			} elseif (is_string($profile) && !empty($profile)) {
				$url = trim($profile);
			}

			// Skip if empty
			if (empty($url)) {
				continue;
			}

			// Allow template variables - they'll be replaced later
			$is_variable = preg_match('/^\{[^}]+\}$/', $url);

			if ($is_variable) {
				// Add template variables as-is (they'll be replaced by variable replacer)
				$social_array[] = $url;
				continue;
			}

			// Filter out invalid URLs (but not variables)
			$is_invalid = false;
			foreach ($invalid_patterns as $pattern) {
				if (preg_match($pattern, $url)) {
					$is_invalid = true;
					break;
				}
			}

			// Add valid URLs (includes example.com for testing)
			if (!$is_invalid && filter_var($url, FILTER_VALIDATE_URL)) {
				$social_array[] = $url;
			}
		}

		return $social_array;
	}

	/**
	 * Get schema.org structure for Organization type
	 *
	 * @return array Schema.org structure specification.
	 */
	public function get_schema_structure()
	{
		return array(
			'@type' => 'Organization',
			'@context' => 'https://schema.org',
			'label' => __('Organization', 'swift-rank'),
			'description' => __('An organization such as a school, NGO, corporation, club, etc.', 'swift-rank'),
			'url' => 'https://schema.org/Organization',
			'icon' => 'building-2',
			'subtypes' => array(
				'Organization' => __('Organization - General organization type', 'swift-rank'),
				'Corporation' => __('Corporation - A business corporation', 'swift-rank'),
				'EducationalOrganization' => __('Educational Organization - Schools, universities, etc.', 'swift-rank'),
				'GovernmentOrganization' => __('Government Organization - Government agencies', 'swift-rank'),
				'NGO' => __('NGO - Non-governmental organization', 'swift-rank'),
			),
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
				'name' => 'organizationType',
				'label' => __('Organization Type', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('The specific type of organization. Choose the most accurate type for better SEO.', 'swift-rank'),
				'options' => array(
					array(
						'label' => __('Organization', 'swift-rank'),
						'value' => 'Organization',
						'description' => __('General organization type', 'swift-rank'),
					),
					array(
						'label' => __('Corporation', 'swift-rank'),
						'value' => 'Corporation',
						'description' => __('A business corporation', 'swift-rank'),
					),
					array(
						'label' => __('Educational Organization', 'swift-rank'),
						'value' => 'EducationalOrganization',
						'description' => __('Schools, universities, etc.', 'swift-rank'),
					),
					array(
						'label' => __('Government Organization', 'swift-rank'),
						'value' => 'GovernmentOrganization',
						'description' => __('Government agencies', 'swift-rank'),
					),
					array(
						'label' => __('NGO', 'swift-rank'),
						'value' => 'NGO',
						'description' => __('Non-governmental organization', 'swift-rank'),
					),
				),
				'default' => 'Organization',
			),
			array(
				'name' => 'name',
				'label' => __('Name', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Organization name. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{site_name}',
				'options' => array(
					array(
						'label' => __('Site Name', 'swift-rank'),
						'value' => '{site_name}',
					),
				),
				'default' => '{site_name}',
				'required' => true,
			),
			array(
				'name' => 'url',
				'label' => __('URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Organization website URL. Click pencil icon to enter custom URL.', 'swift-rank'),
				'placeholder' => '{site_url}',
				'options' => array(
					array(
						'label' => __('Site URL', 'swift-rank'),
						'value' => '{site_url}',
					),
				),
				'default' => '{site_url}',
				'required' => true,
			),
			array(
				'name' => 'description',
				'label' => __('Description', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'textarea',
				'rows' => 4,
				'tooltip' => __('Organization description. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{site_description}',
				'options' => array(
					array(
						'label' => __('Site Description', 'swift-rank'),
						'value' => '{site_description}',
					),
				),
				'default' => '{site_description}',
			),
			array(
				'name' => 'logo',
				'label' => __('Logo', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'image',
				'returnObject' => true,
				'tooltip' => __('Organization logo. Select from list or click pencil to upload custom image.', 'swift-rank'),
				'placeholder' => '{site_logo}',
				'options' => array(
					array(
						'label' => __('Site Logo', 'swift-rank'),
						'value' => '{site_logo}',
					),
				),
				'default' => '{site_logo}',
			),
			// array(
			// 	'name' => 'image',
			// 	'label' => __('Image', 'swift-rank'),
			// 	'type' => 'select',
			// 	'allowCustom' => true,
			// 	'customType' => 'image',
			// 	'returnObject' => true,
			// 	'tooltip' => __('Organization image. Select from list or click pencil to upload custom image.', 'swift-rank'),
			// 	'placeholder' => '{featured_image}',
			// 	'options' => array(
			// 		array(
			// 			'label' => __('Featured Image', 'swift-rank'),
			// 			'value' => '{featured_image}',
			// 		),
			// 	),
			// 	'default' => '{featured_image}',
			// ),
			array(
				'name' => 'phone',
				'label' => __('Phone', 'swift-rank'),
				'type' => 'tel',
				'tooltip' => __('Contact phone number with country code (e.g., +1-800-555-1212).', 'swift-rank'),
				'placeholder' => '+1-800-555-1212',
			),
			array(
				'name' => 'email',
				'label' => __('Email', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Contact email address for the organization.', 'swift-rank'),
				'placeholder' => 'info@example.com',
			),
			array(
				'name' => 'streetAddress',
				'label' => __('Street Address', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Street address of the organization (e.g., 123 Main Street).', 'swift-rank'),
				'placeholder' => '123 Main Street',
			),
			array(
				'name' => 'city',
				'label' => __('City', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('City where the organization is located.', 'swift-rank'),
				'placeholder' => 'New York',
			),
			array(
				'name' => 'state',
				'label' => __('State/Region', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('State or region where the organization is located.', 'swift-rank'),
				'placeholder' => 'NY',
			),
			array(
				'name' => 'postalCode',
				'label' => __('Postal Code', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Postal/ZIP code of the organization.', 'swift-rank'),
				'placeholder' => '10001',
			),
			array(
				'name' => 'country',
				'label' => __('Country', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Country where the organization is located.', 'swift-rank'),
				'placeholder' => 'US',
			),
			array(
				'name' => 'socialProfiles',
				'label' => __('Social Media Profiles', 'swift-rank'),
				'type' => 'repeater',
				'tooltip' => __('Add social media profile URLs for your organization. These appear as sameAs property in schema.', 'swift-rank'),
				'hideInKnowledgeBase' => true,
				'fields' => array(
					array(
						'name' => 'url',
						'label' => __('Profile URL', 'swift-rank'),
						'type' => 'url',
						'placeholder' => 'https://facebook.com/company',
					),
				),
			),
		);
	}

}
