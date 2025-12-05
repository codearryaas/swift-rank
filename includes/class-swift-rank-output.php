<?php
/**
 * Schema Output Class
 *
 * @package Swift_Rank
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Swift_Rank_Output class
 *
 * Handles frontend schema JSON-LD output.
 */
class Swift_Rank_Output {


	/**
	 * Instance of this class
	 *
	 * @var Swift_Rank_Output
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Swift_Rank_Output
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'wp_head', array( $this, 'output_schema' ), 1 );
	}

	/**
	 * Output schema JSON-LD in wp_head
	 */
	public function output_schema() {
		$schemas = array();

		// Homepage: Output organization schema if enabled.
		if ( is_front_page() || is_home() ) {
			$organization_schema = $this->get_organization_schema();
			if ( ! empty( $organization_schema ) ) {
				$schemas[] = $organization_schema;
			}
		}

		// Output all schemas.
		if ( ! empty( $schemas ) ) {
			foreach ( $schemas as $schema ) {
				$this->output_json_ld( $schema );
			}
		}
	}

	/**
	 * Get organization schema for homepage
	 *
	 * @return array|null
	 */
	private function get_organization_schema() {
		$settings             = get_option( 'swift_rank_settings', array() );
		$organization_enabled = isset( $settings['organization_schema'] ) && $settings['organization_schema'];

		if ( ! $organization_enabled ) {
			return null;
		}

		$organization_name     = isset( $settings['organization_name'] ) ? $settings['organization_name'] : get_bloginfo( 'name' );
		$organization_logo     = isset( $settings['organization_logo'] ) ? $settings['organization_logo'] : '';
		$organization_type     = isset( $settings['organization_type'] ) ? $settings['organization_type'] : 'Organization';
		$organization_industry = isset( $settings['organization_industry'] ) ? $settings['organization_industry'] : '';
		$organization_hours    = isset( $settings['organization_hours'] ) ? $settings['organization_hours'] : '';

		// Use the selected organization type (don't auto-convert).

		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => $organization_type,
			'name'     => $organization_name,
			'url'      => get_home_url(),
		);

		// Add industry for Organization (not Person).
		if ( 'Person' !== $organization_type && ! empty( $organization_industry ) ) {
			$schema['knowsAbout'] = $organization_industry;
		}

		// Add logo for Organization/LocalBusiness (not Person).
		if ( 'Person' !== $organization_type && ! empty( $organization_logo ) ) {
			$schema['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => $organization_logo,
			);
		}

		// Add contact point (for Organization/LocalBusiness with phone and contact type).
		if ( 'Person' !== $organization_type && ( ! empty( $settings['organization_phone'] ) || ! empty( $settings['organization_email'] ) || ! empty( $settings['organization_fax'] ) ) ) {
			$contact_type  = isset( $settings['organization_contact_type'] ) ? $settings['organization_contact_type'] : 'Customer Service';
			$contact_point = array(
				'@type'       => 'ContactPoint',
				'contactType' => $contact_type,
			);

			if ( ! empty( $settings['organization_phone'] ) ) {
				$contact_point['telephone'] = $settings['organization_phone'];
			}

			if ( ! empty( $settings['organization_email'] ) ) {
				$contact_point['email'] = $settings['organization_email'];
			}

			if ( ! empty( $settings['organization_fax'] ) ) {
				$contact_point['faxNumber'] = $settings['organization_fax'];
			}

			$schema['contactPoint'] = $contact_point;
		}

		// Add address.
		$has_address = false;
		$address     = array( '@type' => 'PostalAddress' );

		if ( ! empty( $settings['organization_address'] ) ) {
			$address['streetAddress'] = $settings['organization_address'];
			$has_address              = true;
		}

		if ( ! empty( $settings['organization_city'] ) ) {
			$address['addressLocality'] = $settings['organization_city'];
			$has_address                = true;
		}

		if ( ! empty( $settings['organization_state'] ) ) {
			$address['addressRegion'] = $settings['organization_state'];
			$has_address              = true;
		}

		if ( ! empty( $settings['organization_postal_code'] ) ) {
			$address['postalCode'] = $settings['organization_postal_code'];
			$has_address           = true;
		}

		if ( ! empty( $settings['organization_country'] ) ) {
			$address['addressCountry'] = $settings['organization_country'];
			$has_address               = true;
		}

		if ( $has_address ) {
			$schema['address'] = $address;
		}

		// Add price range (for LocalBusiness only).
		if ( 'LocalBusiness' === $organization_type && ! empty( $settings['organization_price_range'] ) ) {
			$schema['priceRange'] = $settings['organization_price_range'];
		}

		// Add opening hours (only for LocalBusiness, not Organization or Person).
		if ( 'LocalBusiness' === $organization_type && ! empty( $settings['organization_hours'] ) ) {
			$hours_data = $settings['organization_hours'];

			// Handle new array format (day-based).
			if ( is_array( $hours_data ) ) {
				$opening_hours_spec = array();
				$days_of_week       = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );

				foreach ( $days_of_week as $day ) {
					if ( isset( $hours_data[ $day ] ) && is_array( $hours_data[ $day ] ) ) {
						$day_info = $hours_data[ $day ];

						// Skip if marked as closed.
						if ( isset( $day_info['closed'] ) && $day_info['closed'] ) {
							continue;
						}

						// Add opening hours specification.
						if ( ! empty( $day_info['opens'] ) && ! empty( $day_info['closes'] ) ) {
							$opening_hours_spec[] = array(
								'@type'     => 'OpeningHoursSpecification',
								'dayOfWeek' => $day,
								'opens'     => $day_info['opens'],
								'closes'    => $day_info['closes'],
							);
						}
					}
				}

				if ( ! empty( $opening_hours_spec ) ) {
					$schema['openingHoursSpecification'] = $opening_hours_spec;
				}
			} else {
				// Old string format (backwards compatibility).
				$hours_lines = explode( "\n", $hours_data );
				$hours_array = array();

				foreach ( $hours_lines as $line ) {
					$line = trim( $line );
					if ( ! empty( $line ) ) {
						$hours_array[] = $line;
					}
				}

				if ( ! empty( $hours_array ) ) {
					$schema['openingHours'] = $hours_array;
				}
			}
		}

		// Add social media profiles.
		if ( ! empty( $settings['organization_social'] ) ) {
			$social_data  = $settings['organization_social'];
			$social_array = array();

			// Handle new array format.
			if ( is_array( $social_data ) ) {
				foreach ( $social_data as $profile ) {
					if ( is_array( $profile ) && isset( $profile['url'] ) ) {
						$url = trim( $profile['url'] );
						if ( ! empty( $url ) && filter_var( $url, FILTER_VALIDATE_URL ) ) {
							$social_array[] = $url;
						}
					} elseif ( is_string( $profile ) ) {
						// Old format: direct URL string.
						$url = trim( $profile );
						if ( ! empty( $url ) && filter_var( $url, FILTER_VALIDATE_URL ) ) {
							$social_array[] = $url;
						}
					}
				}
			} else {
				// Old string format (backwards compatibility).
				$social_lines = explode( "\n", $social_data );
				foreach ( $social_lines as $line ) {
					$line = trim( $line );
					if ( ! empty( $line ) && filter_var( $line, FILTER_VALIDATE_URL ) ) {
						$social_array[] = $line;
					}
				}
			}

			if ( ! empty( $social_array ) ) {
				$schema['sameAs'] = $social_array;
			}
		}

		// Add Person specific fields.
		if ( 'Person' === $organization_type ) {
			if ( ! empty( $settings['organization_job_title'] ) ) {
				$schema['jobTitle'] = $settings['organization_job_title'];
			}
			if ( ! empty( $settings['organization_gender'] ) ) {
				$schema['gender'] = $settings['organization_gender'];
			}
			if ( ! empty( $settings['organization_works_for'] ) ) {
				$schema['worksFor'] = array(
					'@type' => 'Organization',
					'name'  => $settings['organization_works_for'],
				);
			}
			// Add phone and email directly to Person schema.
			if ( ! empty( $settings['organization_phone'] ) ) {
				$schema['telephone'] = $settings['organization_phone'];
			}
			if ( ! empty( $settings['organization_email'] ) ) {
				$schema['email'] = $settings['organization_email'];
			}
			// Add image to Person schema.
			if ( ! empty( $organization_logo ) ) {
				$schema['image'] = $organization_logo;
			}
		}

		// Replace variables (site variables only, no post context).
		$schema_json = wp_json_encode( $schema );
		$schema_json = $this->replace_site_variables( $schema_json );
		$schema      = json_decode( $schema_json, true );

		return $schema;
	}

	/**
	 * Replace site variables in schema JSON
	 *
	 * @param string $json JSON string.
	 * @return string
	 */
	private function replace_site_variables( $json ) {
		// Basic replacements.
		$replacements = array(
			'{site_name}'        => get_bloginfo( 'name' ),
			'{site_url}'         => get_home_url(),
			'{site_description}' => get_bloginfo( 'description' ),
		);

		// Replace basic variables.
		$json = str_replace( array_keys( $replacements ), array_values( $replacements ), $json );

		// Replace {option:option_name} variables.
		$json = preg_replace_callback(
			'/\{option:([a-zA-Z0-9_-]+)\}/',
			function ( $matches ) {
				$option_name  = $matches[1];
				$option_value = get_option( $option_name );
				return $option_value ? $option_value : $matches[0];
			},
			$json
		);

		return $json;
	}

	/**
	 * Output JSON-LD script tag
	 *
	 * @param array $schema Schema array.
	 */
	private function output_json_ld( $schema ) {
		if ( empty( $schema ) ) {
			return;
		}

		// Remove empty values.
		$schema = $this->remove_empty_values( $schema );

		// Encode to JSON with pretty print.
		$json = wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

		if ( empty( $json ) ) {
			return;
		}

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		// JSON-LD requires unescaped output. JSON is generated from validated data via wp_json_encode.
		echo "\n<!-- Swift Rank -->\n";
		echo '<script type="application/ld+json">' . "\n";
		echo $json . "\n";
		echo '</script>' . "\n";
		echo "<!-- /Swift Rank -->\n";
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Remove empty values from schema array
	 *
	 * @param array $schema Schema array.
	 * @return array
	 */
	private function remove_empty_values( $schema ) {
		foreach ( $schema as $key => $value ) {
			// Skip @context and @type.
			if ( '@context' === $key || '@type' === $key ) {
				continue;
			}

			if ( is_array( $value ) ) {
				$schema[ $key ] = $this->remove_empty_values( $value );
				// Remove array if it's empty after recursive cleaning.
				if ( empty( $schema[ $key ] ) ) {
					unset( $schema[ $key ] );
				}
			} elseif ( empty( $value ) && '0' !== $value && 0 !== $value ) {
				// Remove empty values (but keep '0' and 0).
				unset( $schema[ $key ] );
			}
		}

		return $schema;
	}
}
