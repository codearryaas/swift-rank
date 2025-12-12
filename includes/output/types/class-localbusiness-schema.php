<?php
/**
 * LocalBusiness Schema Builder
 *
 * @package Swift_Rank
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_LocalBusiness class
 *
 * Builds LocalBusiness schema types with Google-supported subtypes.
 */
class Schema_LocalBusiness implements Schema_Builder_Interface
{

	/**
	 * Build local business schema from fields
	 *
	 * @param array $fields Field values.
	 * @return array Schema array (without @context).
	 */
	public function build($fields)
	{
		$business_type = !empty($fields['businessType']) ? $fields['businessType'] : 'LocalBusiness';

		$schema = array(
			'@type' => $business_type,
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

		// Logo/Image
		// Logo/Image
		$image_url = isset($fields['image']) ? $fields['image'] : '{site_logo}';


		if (!empty($image_url)) {
			$schema['image'] = $image_url;
		}

		// Contact information
		if (!empty($fields['phone'])) {
			$schema['telephone'] = $fields['phone'];
		}

		if (!empty($fields['email'])) {
			$schema['email'] = $fields['email'];
		}

		// Price range
		if (!empty($fields['priceRange'])) {
			$schema['priceRange'] = $fields['priceRange'];
		}

		// Address (required for LocalBusiness)
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

		// Geo coordinates
		if (!empty($fields['latitude']) && !empty($fields['longitude'])) {
			$schema['geo'] = array(
				'@type' => 'GeoCoordinates',
				'latitude' => $fields['latitude'],
				'longitude' => $fields['longitude'],
			);
		}

		// Opening hours
		if (!empty($fields['openingHours']) && is_array($fields['openingHours'])) {
			$opening_hours_spec = $this->build_opening_hours($fields['openingHours']);
			if (!empty($opening_hours_spec)) {
				$schema['openingHoursSpecification'] = $opening_hours_spec;
			}
		}

		// Payment methods accepted
		if (!empty($fields['paymentAccepted'])) {
			$schema['paymentAccepted'] = $fields['paymentAccepted'];
		}

		// Currencies accepted
		if (!empty($fields['currenciesAccepted'])) {
			$schema['currenciesAccepted'] = $fields['currenciesAccepted'];
		}

		// Social profiles (sameAs)
		if (!empty($fields['socialProfiles']) && is_array($fields['socialProfiles'])) {
			$social_array = $this->build_social_profiles($fields['socialProfiles']);
			if (!empty($social_array)) {
				$schema['sameAs'] = $social_array;
			}
		}

		// Restaurant-specific fields
		if (in_array($business_type, array('Restaurant', 'FoodEstablishment'))) {
			if (!empty($fields['servesCuisine'])) {
				$schema['servesCuisine'] = $fields['servesCuisine'];
			}
			if (!empty($fields['menu'])) {
				$schema['menu'] = $fields['menu'];
			}
			if (!empty($fields['acceptsReservations'])) {
				$schema['acceptsReservations'] = $fields['acceptsReservations'] === 'true' || $fields['acceptsReservations'] === true;
			}
		}

		return $schema;
	}

	/**
	 * Build opening hours specification
	 *
	 * @param array $opening_hours Opening hours data.
	 * @return array
	 */
	private function build_opening_hours($opening_hours)
	{
		$opening_hours_spec = array();
		$days_of_week = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

		foreach ($days_of_week as $day) {
			if (isset($opening_hours[$day]) && is_array($opening_hours[$day])) {
				$day_info = $opening_hours[$day];

				// Skip if marked as closed
				if (isset($day_info['closed']) && $day_info['closed']) {
					continue;
				}

				// Add opening hours specification
				if (!empty($day_info['opens']) && !empty($day_info['closes'])) {
					$opening_hours_spec[] = array(
						'@type' => 'OpeningHoursSpecification',
						'dayOfWeek' => $day,
						'opens' => $day_info['opens'],
						'closes' => $day_info['closes'],
					);
				}
			}
		}

		return $opening_hours_spec;
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
		foreach ($profiles as $profile) {
			if (is_array($profile) && !empty($profile['url'])) {
				$url = trim($profile['url']);
				if (!empty($url)) {
					$social_array[] = $url;
				}
			} elseif (is_string($profile) && !empty($profile)) {
				$social_array[] = trim($profile);
			}
		}
		return $social_array;
	}

	/**
	 * Get schema.org structure for LocalBusiness type
	 *
	 * @return array Schema.org structure specification.
	 */
	public function get_schema_structure()
	{
		return array(
			'@type' => 'LocalBusiness',
			'@context' => 'https://schema.org',
			'label' => __('Local Business', 'swift-rank'),
			'description' => __('A physical business location with address and operating hours.', 'swift-rank'),
			'url' => 'https://schema.org/LocalBusiness',
			'icon' => 'storefront',
			'subtypes' => $this->get_business_subtypes(),
		);
	}

	/**
	 * Get Google-supported LocalBusiness subtypes
	 *
	 * @return array
	 */
	private function get_business_subtypes()
	{
		return array(
			'LocalBusiness' => __('Local Business - General business with physical location', 'swift-rank'),
			'AnimalShelter' => __('Animal Shelter', 'swift-rank'),
			'AutomotiveBusiness' => __('Automotive Business - Car dealers, repair shops', 'swift-rank'),
			'ChildCare' => __('Child Care - Daycare, preschool', 'swift-rank'),
			'Dentist' => __('Dentist', 'swift-rank'),
			'DryCleaningOrLaundry' => __('Dry Cleaning or Laundry', 'swift-rank'),
			'EmergencyService' => __('Emergency Service - Fire, police, ambulance', 'swift-rank'),
			'EmploymentAgency' => __('Employment Agency', 'swift-rank'),
			'EntertainmentBusiness' => __('Entertainment Business - Movie theater, casino', 'swift-rank'),
			'FinancialService' => __('Financial Service - Bank, credit union', 'swift-rank'),
			'FoodEstablishment' => __('Food Establishment - General dining place', 'swift-rank'),
			'Restaurant' => __('Restaurant', 'swift-rank'),
			'Bakery' => __('Bakery', 'swift-rank'),
			'BarOrPub' => __('Bar or Pub', 'swift-rank'),
			'Brewery' => __('Brewery', 'swift-rank'),
			'CafeOrCoffeeShop' => __('Cafe or Coffee Shop', 'swift-rank'),
			'FastFoodRestaurant' => __('Fast Food Restaurant', 'swift-rank'),
			'IceCreamShop' => __('Ice Cream Shop', 'swift-rank'),
			'Winery' => __('Winery', 'swift-rank'),
			'GovernmentOffice' => __('Government Office', 'swift-rank'),
			'HealthAndBeautyBusiness' => __('Health and Beauty Business - Salon, spa', 'swift-rank'),
			'BeautySalon' => __('Beauty Salon', 'swift-rank'),
			'DaySpa' => __('Day Spa', 'swift-rank'),
			'HairSalon' => __('Hair Salon', 'swift-rank'),
			'HealthClub' => __('Health Club - Gym, fitness center', 'swift-rank'),
			'NailSalon' => __('Nail Salon', 'swift-rank'),
			'TattooParlor' => __('Tattoo Parlor', 'swift-rank'),
			'HomeAndConstructionBusiness' => __('Home and Construction Business', 'swift-rank'),
			'Electrician' => __('Electrician', 'swift-rank'),
			'GeneralContractor' => __('General Contractor', 'swift-rank'),
			'HVACBusiness' => __('HVAC Business - Heating, cooling', 'swift-rank'),
			'HousePainter' => __('House Painter', 'swift-rank'),
			'Locksmith' => __('Locksmith', 'swift-rank'),
			'MovingCompany' => __('Moving Company', 'swift-rank'),
			'Plumber' => __('Plumber', 'swift-rank'),
			'RoofingContractor' => __('Roofing Contractor', 'swift-rank'),
			'InternetCafe' => __('Internet Cafe', 'swift-rank'),
			'LegalService' => __('Legal Service - Law firm, attorney', 'swift-rank'),
			'Attorney' => __('Attorney', 'swift-rank'),
			'Notary' => __('Notary', 'swift-rank'),
			'Library' => __('Library', 'swift-rank'),
			'LodgingBusiness' => __('Lodging Business - Hotel, motel', 'swift-rank'),
			'BedAndBreakfast' => __('Bed and Breakfast', 'swift-rank'),
			'Campground' => __('Campground', 'swift-rank'),
			'Hostel' => __('Hostel', 'swift-rank'),
			'Hotel' => __('Hotel', 'swift-rank'),
			'Motel' => __('Motel', 'swift-rank'),
			'Resort' => __('Resort', 'swift-rank'),
			'MedicalBusiness' => __('Medical Business', 'swift-rank'),
			'MedicalClinic' => __('Medical Clinic', 'swift-rank'),
			'Pharmacy' => __('Pharmacy', 'swift-rank'),
			'Physician' => __('Physician', 'swift-rank'),
			'ProfessionalService' => __('Professional Service - Accountant, consultant', 'swift-rank'),
			'AccountingService' => __('Accounting Service', 'swift-rank'),
			'RadioStation' => __('Radio Station', 'swift-rank'),
			'RealEstateAgent' => __('Real Estate Agent', 'swift-rank'),
			'RecyclingCenter' => __('Recycling Center', 'swift-rank'),
			'SelfStorage' => __('Self Storage', 'swift-rank'),
			'ShoppingCenter' => __('Shopping Center - Mall', 'swift-rank'),
			'SportsActivityLocation' => __('Sports Activity Location - Stadium, gym', 'swift-rank'),
			'BowlingAlley' => __('Bowling Alley', 'swift-rank'),
			'ExerciseGym' => __('Exercise Gym', 'swift-rank'),
			'GolfCourse' => __('Golf Course', 'swift-rank'),
			'PublicSwimmingPool' => __('Public Swimming Pool', 'swift-rank'),
			'SkiResort' => __('Ski Resort', 'swift-rank'),
			'SportsClub' => __('Sports Club', 'swift-rank'),
			'StadiumOrArena' => __('Stadium or Arena', 'swift-rank'),
			'TennisComplex' => __('Tennis Complex', 'swift-rank'),
			'Store' => __('Store - Retail store', 'swift-rank'),
			'AutoPartsStore' => __('Auto Parts Store', 'swift-rank'),
			'BikeStore' => __('Bike Store', 'swift-rank'),
			'BookStore' => __('Book Store', 'swift-rank'),
			'ClothingStore' => __('Clothing Store', 'swift-rank'),
			'ComputerStore' => __('Computer Store', 'swift-rank'),
			'ConvenienceStore' => __('Convenience Store', 'swift-rank'),
			'DepartmentStore' => __('Department Store', 'swift-rank'),
			'ElectronicsStore' => __('Electronics Store', 'swift-rank'),
			'Florist' => __('Florist', 'swift-rank'),
			'FurnitureStore' => __('Furniture Store', 'swift-rank'),
			'GardenStore' => __('Garden Store', 'swift-rank'),
			'GroceryStore' => __('Grocery Store', 'swift-rank'),
			'HardwareStore' => __('Hardware Store', 'swift-rank'),
			'HobbyShop' => __('Hobby Shop', 'swift-rank'),
			'HomeGoodsStore' => __('Home Goods Store', 'swift-rank'),
			'JewelryStore' => __('Jewelry Store', 'swift-rank'),
			'LiquorStore' => __('Liquor Store', 'swift-rank'),
			'MensClothingStore' => __('Mens Clothing Store', 'swift-rank'),
			'MobilePhoneStore' => __('Mobile Phone Store', 'swift-rank'),
			'MovieRentalStore' => __('Movie Rental Store', 'swift-rank'),
			'MusicStore' => __('Music Store', 'swift-rank'),
			'OfficeEquipmentStore' => __('Office Equipment Store', 'swift-rank'),
			'OutletStore' => __('Outlet Store', 'swift-rank'),
			'PawnShop' => __('Pawn Shop', 'swift-rank'),
			'PetStore' => __('Pet Store', 'swift-rank'),
			'ShoeStore' => __('Shoe Store', 'swift-rank'),
			'SportingGoodsStore' => __('Sporting Goods Store', 'swift-rank'),
			'TireShop' => __('Tire Shop', 'swift-rank'),
			'ToyStore' => __('Toy Store', 'swift-rank'),
			'WholesaleStore' => __('Wholesale Store', 'swift-rank'),
			'TelevisionStation' => __('Television Station', 'swift-rank'),
			'TouristInformationCenter' => __('Tourist Information Center', 'swift-rank'),
			'TravelAgency' => __('Travel Agency', 'swift-rank'),
		);
	}

	/**
	 * Get field definitions for the admin UI
	 *
	 * @return array Array of field configurations for React components.
	 */
	public function get_fields()
	{
		$business_types = $this->get_business_subtypes();
		$type_options = array();

		foreach ($business_types as $value => $label) {
			$type_options[] = array(
				'label' => $label,
				'value' => $value,
			);
		}

		return array(
			array(
				'name' => 'businessType',
				'label' => __('Business Type', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('The specific type of local business. Choose the most accurate type for better local SEO.', 'swift-rank'),
				'options' => $type_options,
				'default' => 'LocalBusiness',
			),
			array(
				'name' => 'name',
				'label' => __('Business Name', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Business name. Click pencil icon to use variables.', 'swift-rank'),
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
				'label' => __('Website URL', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'tooltip' => __('Business website URL. Click pencil icon to enter custom URL.', 'swift-rank'),
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
				'name' => 'description',
				'label' => __('Description', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'textarea',
				'rows' => 4,
				'tooltip' => __('Business description. Click pencil icon to use variables.', 'swift-rank'),
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
				'name' => 'image',
				'label' => __('Business Image', 'swift-rank'),
				'type' => 'select',
				'allowCustom' => true,
				'customType' => 'image',
				'returnObject' => true,
				'tooltip' => __('Business image. Select from list or click pencil to upload custom image.', 'swift-rank'),
				'placeholder' => '{site_logo}',
				'options' => array(
					array(
						'label' => __('Site Logo', 'swift-rank'),
						'value' => '{site_logo}',
					),
				),
				'default' => '{site_logo}',
			),
			array(
				'name' => 'phone',
				'label' => __('Phone Number', 'swift-rank'),
				'type' => 'tel',
				'tooltip' => __('Business phone number with country code (e.g., +1-800-555-1212).', 'swift-rank'),
				'placeholder' => '+1-800-555-1212',
				'required' => true,
			),
			array(
				'name' => 'email',
				'label' => __('Email', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Business contact email address.', 'swift-rank'),
				'placeholder' => 'info@business.com',
			),
			array(
				'name' => 'streetAddress',
				'label' => __('Street Address', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Street address of the business location.', 'swift-rank'),
				'placeholder' => '123 Main Street',
				'required' => true,
			),
			array(
				'name' => 'city',
				'label' => __('City', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('City where the business is located.', 'swift-rank'),
				'placeholder' => 'New York',
				'required' => true,
			),
			array(
				'name' => 'state',
				'label' => __('State/Region', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('State or region where the business is located.', 'swift-rank'),
				'placeholder' => 'NY',
				'required' => true,
			),
			array(
				'name' => 'postalCode',
				'label' => __('Postal Code', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Postal/ZIP code of the business location.', 'swift-rank'),
				'placeholder' => '10001',
				'required' => true,
			),
			array(
				'name' => 'country',
				'label' => __('Country', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Country where the business is located.', 'swift-rank'),
				'placeholder' => 'US',
				'required' => true,
			),
			array(
				'name' => 'latitude',
				'label' => __('Latitude', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Geographic latitude coordinate (e.g., 40.7128).', 'swift-rank'),
				'placeholder' => '40.7128',
			),
			array(
				'name' => 'longitude',
				'label' => __('Longitude', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Geographic longitude coordinate (e.g., -74.0060).', 'swift-rank'),
				'placeholder' => '-74.0060',
			),
			array(
				'name' => 'priceRange',
				'label' => __('Price Range', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Price range indicator (e.g., $, $$, $$$, $$$$).', 'swift-rank'),
				'placeholder' => '$$',
			),
			array(
				'name' => 'paymentAccepted',
				'label' => __('Payment Methods Accepted', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Payment methods accepted (e.g., Cash, Credit Card, PayPal).', 'swift-rank'),
				'placeholder' => 'Cash, Credit Card, PayPal',
			),
			array(
				'name' => 'currenciesAccepted',
				'label' => __('Currencies Accepted', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Currencies accepted (e.g., USD, EUR, GBP).', 'swift-rank'),
				'placeholder' => 'USD',
			),
			array(
				'name' => 'openingHours',
				'label' => __('Opening Hours', 'swift-rank'),
				'type' => 'opening_hours',
				'tooltip' => __('Set the business hours for each day of the week.', 'swift-rank'),
				'isPro' => true,
			),
			array(
				'name' => 'servesCuisine',
				'label' => __('Cuisine Type', 'swift-rank'),
				'type' => 'text',
				'tooltip' => __('Type of cuisine served (for restaurants).', 'swift-rank'),
				'placeholder' => 'Italian, Pizza',
				'condition' => array(
					'field' => 'businessType',
					'values' => array('Restaurant', 'FoodEstablishment'),
				),
			),
			array(
				'name' => 'menu',
				'label' => __('Menu URL', 'swift-rank'),
				'type' => 'url',
				'tooltip' => __('URL to the restaurant menu (for restaurants).', 'swift-rank'),
				'placeholder' => 'https://example.com/menu',
				'condition' => array(
					'field' => 'businessType',
					'values' => array('Restaurant', 'FoodEstablishment'),
				),
			),
			array(
				'name' => 'acceptsReservations',
				'label' => __('Accepts Reservations', 'swift-rank'),
				'type' => 'select',
				'tooltip' => __('Whether the restaurant accepts reservations.', 'swift-rank'),
				'options' => array(
					array('label' => __('Yes', 'swift-rank'), 'value' => 'true'),
					array('label' => __('No', 'swift-rank'), 'value' => 'false'),
				),
				'default' => 'false',
				'condition' => array(
					'field' => 'businessType',
					'values' => array('Restaurant', 'FoodEstablishment'),
				),
			),
			array(
				'name' => 'socialProfiles',
				'label' => __('Social Media Profiles', 'swift-rank'),
				'type' => 'repeater',
				'tooltip' => __('Add social media profile URLs. These appear as sameAs property in schema.', 'swift-rank'),
				'hideInKnowledgeBase' => true,
				'fields' => array(
					array(
						'name' => 'url',
						'label' => __('Profile URL', 'swift-rank'),
						'type' => 'url',
						'placeholder' => 'https://facebook.com/business',
					),
				),
			),
		);
	}

}
