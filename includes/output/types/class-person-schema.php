<?php
/**
 * Person Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_Person class
 *
 * Builds Person schema type.
 */
class Schema_Person implements Schema_Builder_Interface
{

	/**
	 * Build person schema from fields
	 *
	 * @param array $fields Field values.
	 * @return array Schema array (without @context).
	 */
	public function build($fields)
	{
		$schema = array(
			'@type' => 'Person',
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

		// Image with fallback to site_logo variable
		$image_url = isset($fields['image']) ? $fields['image'] : '{site_logo}';
		if (!empty($image_url)) {
			$schema['image'] = $image_url;
		}

		if (!empty($fields['jobTitle'])) {
			$schema['jobTitle'] = $fields['jobTitle'];
		}

		if (!empty($fields['email'])) {
			$schema['email'] = $fields['email'];
		}

		if (!empty($fields['phone'])) {
			$schema['telephone'] = $fields['phone'];
		}

		if (!empty($fields['worksFor'])) {
			$schema['worksFor'] = array(
				'@type' => 'Organization',
				'name' => $fields['worksFor'],
			);
		}

		if (!empty($fields['gender'])) {
			$schema['gender'] = $fields['gender'];
		}

		if (!empty($fields['birthDate'])) {
			$schema['birthDate'] = $fields['birthDate'];
		}

		if (!empty($fields['nationality'])) {
			$schema['nationality'] = $fields['nationality'];
		}

		// Social profiles (sameAs)
		if (!empty($fields['socialProfiles']) && is_array($fields['socialProfiles'])) {
			$social_array = array();
			foreach ($fields['socialProfiles'] as $profile) {
				if (is_array($profile) && !empty($profile['url'])) {
					$url = trim($profile['url']);
					if (!empty($url)) {
						$social_array[] = $url;
					}
				} elseif (is_string($profile) && !empty($profile)) {
					$social_array[] = trim($profile);
				}
			}
			if (!empty($social_array)) {
				$schema['sameAs'] = $social_array;
			}
		}

		return $schema;
	}

	/**
	 * Get schema.org structure for Person type
	 *
	 * @return array Schema.org structure specification.
	 */
	public function get_schema_structure()
	{
		return array(
			'@type' => 'Person',
			'@context' => 'https://schema.org',
			'label' => __('Person', 'swift-rank'),
			'description' => __('A person (alive, dead, undead, or fictional).', 'swift-rank'),
			'url' => 'https://schema.org/Person',
			'icon' => 'user',
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
				'label' => __('Name', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Person name. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{site_name}',
				'options' => array(
					array(
						'label' => __('Site Name', 'swift-rank'),
						'value' => '{site_name}',
					),
					array(
						'label' => __('Author Name', 'swift-rank'),
						'value' => '{author_name}',
					),
				),
				'default' => '{site_name}',
				'required' => true,
			),
			array(
				'name' => 'url',
				'label' => __('Website URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Person website URL. Click pencil icon to enter custom URL.', 'swift-rank'),
				'placeholder' => '{site_url}',
				'options' => array(
					array(
						'label' => __('Site URL', 'swift-rank'),
						'value' => '{site_url}',
					),
					array(
						'label' => __('Author URL', 'swift-rank'),
						'value' => '{author_url}',
					),
				),
				'default' => '{site_url}',
			),
			array(
				'name' => 'description',
				'label' => __('Bio', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'textarea',
				'rows' => 4,
				'tooltip' => __('Person bio. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{site_description}',
				'options' => array(
					array(
						'label' => __('Site Description', 'swift-rank'),
						'value' => '{site_description}',
					),
					array(
						'label' => __('Author Bio', 'swift-rank'),
						'value' => '{author_bio}',
					),
				),
				'default' => '{site_description}',
			),
			array(
				'name' => 'image',
				'label' => __('Image URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'image',
				'returnObject' => true,
				'tooltip' => __('Person photo. Select from list or click pencil to upload custom image.', 'swift-rank'),
				'placeholder' => '{site_logo}',
				'options' => array(
					array(
						'label' => __('Site Logo', 'swift-rank'),
						'value' => '{site_logo}',
					),
					array(
						'label' => __('Author Avatar', 'swift-rank'),
						'value' => '{author_avatar}',
					),
				),
				'default' => '{site_logo}',
			),
			array(
				'name' => 'jobTitle',
				'label' => __('Job Title', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('The job title or position of the person (e.g., CEO, Writer).', 'swift-rank'),
				'placeholder' => 'Software Engineer',
			),
			array(
				'name' => 'email',
				'label' => __('Email', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Email address of the person. Use {author_email} for author email.', 'swift-rank'),
				'placeholder' => 'person@example.com',
			),
			array(
				'name' => 'phone',
				'label' => __('Phone', 'swift-rank'),
				'type' => 'tel',
				'tooltip' => __('Phone number of the person with country code.', 'swift-rank'),
				'placeholder' => '+1-800-555-1212',
			),
			array(
				'name' => 'worksFor',
				'label' => __('Works For (Organization)', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('The name of the organization the person works for.', 'swift-rank'),
				'placeholder' => 'Acme Corporation',
			),
			array(
				'name' => 'gender',
				'label' => __('Gender', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('The gender of the person (optional).', 'swift-rank'),
				'options' => array(
					array(
						'label' => __('Not Specified', 'swift-rank'),
						'value' => '',
						'description' => __('No gender specified', 'swift-rank'),
					),
					array(
						'label' => __('Male', 'swift-rank'),
						'value' => 'Male',
						'description' => __('Male gender', 'swift-rank'),
					),
					array(
						'label' => __('Female', 'swift-rank'),
						'value' => 'Female',
						'description' => __('Female gender', 'swift-rank'),
					),
					array(
						'label' => __('Non-binary', 'swift-rank'),
						'value' => 'Non-binary',
						'description' => __('Non-binary gender', 'swift-rank'),
					),
				),
				'default' => '',
			),
			array(
				'name' => 'birthDate',
				'label' => __('Birth Date', 'swift-rank'),
				'type' => 'date',
				'tooltip' => __('Birth date in YYYY-MM-DD format (optional). Click pencil icon to pick date.', 'swift-rank'),
				'placeholder' => '1990-01-15',
			),
			array(
				'name' => 'nationality',
				'label' => __('Nationality', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('The nationality of the person (optional).', 'swift-rank'),
				'placeholder' => 'American',
			),
			array(
				'name' => 'socialProfiles',
				'label' => __('Social Media Profiles', 'swift-rank'),
				'type' => 'repeater',
				'tooltip' => __('Add social media profile URLs for this person. These appear as sameAs property in schema.', 'swift-rank'),
				'hideInKnowledgeBase' => true,
				'fields' => array(
					array(
						'name' => 'url',
						'label' => __('Profile URL', 'swift-rank'),
						'type' => 'url',
						'placeholder' => 'https://twitter.com/username',
					),
				),
			),
		);
	}

}
