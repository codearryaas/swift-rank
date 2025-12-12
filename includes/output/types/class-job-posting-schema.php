<?php
/**
 * Job Posting Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_Job_Posting class
 *
 * Builds JobPosting schema type.
 */
class Schema_Job_Posting implements Schema_Builder_Interface
{

	/**
	 * Get schema.org structure for JobPosting type
	 *
	 * @return array Schema.org structure specification.
	 */
	public function get_schema_structure()
	{
		return array(
			'@type' => 'JobPosting',
			'@context' => 'https://schema.org',
			'label' => __('Job Posting', 'swift-rank'),
			'description' => __('A listing that describes a job opening in an organization.', 'swift-rank'),
			'url' => 'https://schema.org/JobPosting',
			'icon' => 'briefcase',
		);
	}

	/**
	 * Build job posting schema from fields
	 *
	 * @param array $fields Field values.
	 * @return array Schema array (without @context).
	 */
	public function build($fields)
	{
		// Required fields with fallback values
		$title = isset($fields['title']) ? $fields['title'] : '{post_title}';
		$description = isset($fields['description']) ? $fields['description'] : '{post_content}';
		$date_posted = isset($fields['datePosted']) ? $fields['datePosted'] : '{post_date}';

		$schema = array(
			'@type' => 'JobPosting',
			'title' => $title,
			'description' => $description,
			'datePosted' => $date_posted,
		);

		// Hiring Organization (required)
		$org_name = isset($fields['hiringOrganizationName']) ? $fields['hiringOrganizationName'] : '{site_name}';
		$hiring_org = array(
			'@type' => 'Organization',
			'name' => $org_name,
		);

		// Organization URL
		if (!empty($fields['hiringOrganizationUrl'])) {
			$hiring_org['url'] = $fields['hiringOrganizationUrl'];
		}

		// Organization Logo
		if (!empty($fields['hiringOrganizationLogo'])) {
			$hiring_org['logo'] = $fields['hiringOrganizationLogo'];
		}

		$schema['hiringOrganization'] = $hiring_org;

		// Job Location (required) - can be array for multiple locations
		if (!empty($fields['jobLocations']) && is_array($fields['jobLocations'])) {
			$locations = array();
			foreach ($fields['jobLocations'] as $location) {
				if (!empty($location['addressLocality']) || !empty($location['addressCountry'])) {
					$place = array(
						'@type' => 'Place',
						'address' => array(
							'@type' => 'PostalAddress',
						),
					);

					if (!empty($location['streetAddress'])) {
						$place['address']['streetAddress'] = $location['streetAddress'];
					}
					if (!empty($location['addressLocality'])) {
						$place['address']['addressLocality'] = $location['addressLocality'];
					}
					if (!empty($location['addressRegion'])) {
						$place['address']['addressRegion'] = $location['addressRegion'];
					}
					if (!empty($location['postalCode'])) {
						$place['address']['postalCode'] = $location['postalCode'];
					}
					if (!empty($location['addressCountry'])) {
						$place['address']['addressCountry'] = $location['addressCountry'];
					}

					$locations[] = $place;
				}
			}

			if (!empty($locations)) {
				$schema['jobLocation'] = count($locations) === 1 ? $locations[0] : $locations;
			}
		} else {
			// Fallback: Create a generic location if none provided (required by Google)
			// This allows the schema to output even without explicit location data
			$schema['jobLocation'] = array(
				'@type' => 'Place',
				'address' => array(
					'@type' => 'PostalAddress',
					'addressCountry' => 'US', // Default to US, users should override this
				),
			);
		}

		// Employment Type (recommended)
		if (!empty($fields['employmentType'])) {
			$schema['employmentType'] = $fields['employmentType'];
		}

		// Base Salary (recommended)
		if (!empty($fields['baseSalaryValue'])) {
			$salary = array(
				'@type' => 'MonetaryAmount',
				'currency' => isset($fields['baseSalaryCurrency']) ? $fields['baseSalaryCurrency'] : 'USD',
				'value' => array(
					'@type' => 'QuantitativeValue',
					'value' => $fields['baseSalaryValue'],
					'unitText' => isset($fields['baseSalaryUnit']) ? $fields['baseSalaryUnit'] : 'YEAR',
				),
			);

			// If salary range provided
			if (!empty($fields['baseSalaryMinValue']) && !empty($fields['baseSalaryMaxValue'])) {
				$salary['value'] = array(
					'@type' => 'QuantitativeValue',
					'minValue' => $fields['baseSalaryMinValue'],
					'maxValue' => $fields['baseSalaryMaxValue'],
					'unitText' => isset($fields['baseSalaryUnit']) ? $fields['baseSalaryUnit'] : 'YEAR',
				);
			}

			$schema['baseSalary'] = $salary;
		}

		// Valid Through (recommended if job has deadline)
		if (!empty($fields['validThrough'])) {
			$schema['validThrough'] = $fields['validThrough'];
		}

		// Job Location Type (for remote jobs)
		if (!empty($fields['jobLocationType'])) {
			$schema['jobLocationType'] = $fields['jobLocationType'];
		}

		// Applicant Location Requirements (required for 100% remote)
		if (!empty($fields['applicantLocationRequirements']) && is_array($fields['applicantLocationRequirements'])) {
			$location_reqs = array();
			foreach ($fields['applicantLocationRequirements'] as $req) {
				if (!empty($req['name'])) {
					$location_reqs[] = array(
						'@type' => 'Country',
						'name' => $req['name'],
					);
				}
			}
			if (!empty($location_reqs)) {
				$schema['applicantLocationRequirements'] = $location_reqs;
			}
		}

		// Identifier (recommended)
		if (!empty($fields['identifier'])) {
			$schema['identifier'] = array(
				'@type' => 'PropertyValue',
				'name' => isset($fields['identifierName']) ? $fields['identifierName'] : $org_name,
				'value' => $fields['identifier'],
			);
		}

		// Experience Requirements
		if (!empty($fields['experienceRequirements'])) {
			$schema['experienceRequirements'] = array(
				'@type' => 'OccupationalExperienceRequirements',
				'monthsOfExperience' => $fields['experienceRequirements'],
			);
		}

		// Education Requirements
		if (!empty($fields['educationRequirements'])) {
			$schema['educationRequirements'] = array(
				'@type' => 'EducationalOccupationalCredential',
				'credentialCategory' => $fields['educationRequirements'],
			);
		}

		// Direct Apply
		if (!empty($fields['directApply'])) {
			$schema['directApply'] = filter_var($fields['directApply'], FILTER_VALIDATE_BOOLEAN);
		}

		return $schema;
	}

	/**
	 * Get field definitions for the admin UI
	 *
	 * @return array Array of field configurations for React components.
	 */
	public function get_fields()
	{
		return array(
			// Basic Information
			array(
				'name' => 'title',
				'label' => __('Job Title', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Job title. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{post_title}',
				'options' => array(
					array(
						'label' => __('Post Title', 'swift-rank'),
						'value' => '{post_title}',
					),
				),
				'default' => '{post_title}',
				'required' => true,
			),
			array(
				'name' => 'description',
				'label' => __('Job Description', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'textarea',
				'rows' => 6,
				'tooltip' => __('Job description. Click pencil icon to use variables.', 'swift-rank'),
				'placeholder' => '{post_content}',
				'options' => array(
					array(
						'label' => __('Post Content', 'swift-rank'),
						'value' => '{post_content}',
					),
					array(
						'label' => __('Post Excerpt', 'swift-rank'),
						'value' => '{post_excerpt}',
					),
				),
				'default' => '{post_content}',
				'required' => true,
			),
			array(
				'name' => 'datePosted',
				'label' => __('Date Posted', 'swift-rank'),
				'type' => 'date',
				'tooltip' => __('Date posted in YYYY-MM-DD format.', 'swift-rank'),
				'placeholder' => '{post_date}',
				'default' => '{post_date}',
				'required' => true,
			),

			// Hiring Organization
			array(
				'name' => 'hiringOrganizationName',
				'label' => __('Hiring Organization Name', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Hiring organization name. Click pencil icon to use variables.', 'swift-rank'),
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
				'name' => 'hiringOrganizationUrl',
				'label' => __('Hiring Organization URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Hiring organization URL. Click pencil icon to enter custom URL.', 'swift-rank'),
				'placeholder' => '{site_url}',
				'options' => array(
					array(
						'label' => __('Site URL', 'swift-rank'),
						'value' => '{site_url}',
					),
				),
				'default' => '{site_url}',
			),
			array(
				'name' => 'hiringOrganizationLogo',
				'label' => __('Hiring Organization Logo', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Hiring organization logo. Click pencil icon to enter custom URL.', 'swift-rank'),
				'placeholder' => '{site_logo}',
				'options' => array(
					array(
						'label' => __('Site Logo', 'swift-rank'),
						'value' => '{site_logo}',
					),
				),
				'default' => '{site_logo}',
			),

			// Job Location
			array(
				'name' => 'jobLocations',
				'label' => __('Job Location(s)', 'swift-rank'),
				'type' => 'repeater',
				'tooltip' => __('Physical workplace location(s). Add multiple for jobs in multiple locations.', 'swift-rank'),
				'required' => true,
				'fields' => array(
					array(
						'name' => 'streetAddress',
						'label' => __('Street Address', 'swift-rank'),
						'type' => 'text',
						'placeholder' => '123 Main Street',
					),
					array(
						'name' => 'addressLocality',
						'label' => __('City', 'swift-rank'),
						'type' => 'text',
						'placeholder' => 'New York',
					),
					array(
						'name' => 'addressRegion',
						'label' => __('State/Region', 'swift-rank'),
						'type' => 'text',
						'placeholder' => 'NY',
					),
					array(
						'name' => 'postalCode',
						'label' => __('Postal Code', 'swift-rank'),
						'type' => 'text',
						'placeholder' => '10001',
					),
					array(
						'name' => 'addressCountry',
						'label' => __('Country', 'swift-rank'),
						'type' => 'text',
						'tooltip' => __('Required - use 2-letter ISO code (e.g., US, GB, CA).', 'swift-rank'),
						'placeholder' => 'US',
						'required' => true,
					),
				),
			),

			// Employment Details
			array(
				'name' => 'employmentType',
				'label' => __('Employment Type', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('The type of employment offered.', 'swift-rank'),
				'options' => array(
					array(
						'label' => __('Full-Time', 'swift-rank'),
						'value' => 'FULL_TIME',
						'description' => __('Full-time position', 'swift-rank'),
					),
					array(
						'label' => __('Part-Time', 'swift-rank'),
						'value' => 'PART_TIME',
						'description' => __('Part-time position', 'swift-rank'),
					),
					array(
						'label' => __('Contractor', 'swift-rank'),
						'value' => 'CONTRACTOR',
						'description' => __('Contractor/freelance position', 'swift-rank'),
					),
					array(
						'label' => __('Temporary', 'swift-rank'),
						'value' => 'TEMPORARY',
						'description' => __('Temporary position', 'swift-rank'),
					),
					array(
						'label' => __('Intern', 'swift-rank'),
						'value' => 'INTERN',
						'description' => __('Internship position', 'swift-rank'),
					),
					array(
						'label' => __('Volunteer', 'swift-rank'),
						'value' => 'VOLUNTEER',
						'description' => __('Volunteer position', 'swift-rank'),
					),
					array(
						'label' => __('Per Diem', 'swift-rank'),
						'value' => 'PER_DIEM',
						'description' => __('Per diem position', 'swift-rank'),
					),
					array(
						'label' => __('Other', 'swift-rank'),
						'value' => 'OTHER',
						'description' => __('Other employment type', 'swift-rank'),
					),
				),
			),

			// Salary Information
			array(
				'name' => 'baseSalaryValue',
				'label' => __('Base Salary (Single Value)', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Salary amount (e.g., 50000). Leave empty if using salary range.', 'swift-rank'),
				'placeholder' => '50000',
			),
			array(
				'name' => 'baseSalaryMinValue',
				'label' => __('Base Salary Min (Range)', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Minimum salary in range (e.g., 40000).', 'swift-rank'),
				'placeholder' => '40000',
			),
			array(
				'name' => 'baseSalaryMaxValue',
				'label' => __('Base Salary Max (Range)', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Maximum salary in range (e.g., 60000).', 'swift-rank'),
				'placeholder' => '60000',
			),
			array(
				'name' => 'baseSalaryUnit',
				'label' => __('Salary Unit', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('The time unit for the salary.', 'swift-rank'),
				'options' => array(
					array('label' => __('Yearly', 'swift-rank'), 'value' => 'YEAR'),
					array('label' => __('Monthly', 'swift-rank'), 'value' => 'MONTH'),
					array('label' => __('Weekly', 'swift-rank'), 'value' => 'WEEK'),
					array('label' => __('Daily', 'swift-rank'), 'value' => 'DAY'),
					array('label' => __('Hourly', 'swift-rank'), 'value' => 'HOUR'),
				),
				'default' => 'YEAR',
			),
			array(
				'name' => 'baseSalaryCurrency',
				'label' => __('Currency', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('3-letter ISO 4217 currency code (e.g., USD, EUR, GBP).', 'swift-rank'),
				'placeholder' => 'USD',
				'default' => 'USD',
			),

			// Additional Details
			array(
				'name' => 'validThrough',
				'label' => __('Valid Through', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Job posting expiration date in ISO 8601 format (e.g., 2024-12-31). Required if job has deadline.', 'swift-rank'),
				'placeholder' => '2024-12-31',
			),
			array(
				'name' => 'identifier',
				'label' => __('Job Identifier', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Your unique identifier for this job posting (e.g., job-123).', 'swift-rank'),
				'placeholder' => 'job-123',
			),

			// Remote Work
			array(
				'name' => 'jobLocationType',
				'label' => __('Job Location Type', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('Use TELECOMMUTE for 100% remote positions. Must also specify eligible locations.', 'swift-rank'),
				'options' => array(
					array('label' => __('On-site', 'swift-rank'), 'value' => ''),
					array('label' => __('Remote (Telecommute)', 'swift-rank'), 'value' => 'TELECOMMUTE'),
				),
			),
			array(
				'name' => 'applicantLocationRequirements',
				'label' => __('Eligible Remote Locations', 'swift-rank'),
				'type' => 'repeater',
				'tooltip' => __('Required for 100% remote jobs. Specify eligible countries/regions for remote workers.', 'swift-rank'),
				'fields' => array(
					array(
						'name' => 'name',
						'label' => __('Country/Region Name', 'swift-rank'),
						'type' => 'text',
						'placeholder' => 'United States',
					),
				),
			),

			// Requirements
			array(
				'name' => 'experienceRequirements',
				'label' => __('Experience Required (Months)', 'swift-rank'),
				'type' => 'number',
				'tooltip' => __('Minimum months of experience required (e.g., 24 for 2 years).', 'swift-rank'),
				'placeholder' => '24',
			),
			array(
				'name' => 'educationRequirements',
				'label' => __('Education Requirements', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('Required educational credential level.', 'swift-rank'),
				'options' => array(
					array('label' => __('None', 'swift-rank'), 'value' => ''),
					array('label' => __('High School', 'swift-rank'), 'value' => 'HighSchool'),
					array('label' => __('Associate Degree', 'swift-rank'), 'value' => 'AssociateDegree'),
					array('label' => __('Bachelor\'s Degree', 'swift-rank'), 'value' => 'BachelorDegree'),
					array('label' => __('Master\'s Degree', 'swift-rank'), 'value' => 'MasterDegree'),
					array('label' => __('Doctoral Degree', 'swift-rank'), 'value' => 'DoctoralDegree'),
					array('label' => __('Professional Certificate', 'swift-rank'), 'value' => 'ProfessionalCertificate'),
				),
			),

			// Direct Apply
			array(
				'name' => 'directApply',
				'label' => __('Direct Apply', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('Whether the job can be applied to directly on your website.', 'swift-rank'),
				'options' => array(
					array('label' => __('Yes', 'swift-rank'), 'value' => 'true'),
					array('label' => __('No', 'swift-rank'), 'value' => 'false'),
				),
			),
		);
	}
}
