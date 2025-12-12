=== Swift Rank ===
Contributors: racase
Tags: schema, structured data, seo, json-ld, knowledge graph
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.0
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add Schema.org structured data to your WordPress site. Supports Organization, LocalBusiness, and Person schema with Knowledge Graph integration.

== Description ==

Swift Rank helps you add professional Schema.org structured data to your WordPress website using the recommended JSON-LD format. Improve your search engine visibility and enable rich snippets in Google search results with easy-to-configure knowledge graph schema.

= Key Features =

* **Knowledge Graph Schema** - Add Organization, LocalBusiness, or Person schema to your homepage
* **JSON-LD Format** - Uses Google's recommended JSON-LD structured data format
* **Dynamic Variables System** - Insert WordPress data dynamically into schema fields
* **Industry Categories** - Choose from 15+ predefined industry categories
* **Complete Contact Info** - Add phone, email, fax, and contact type information
* **Address Support** - Full postal address with street, city, state, postal code, and country
* **Operating Hours** - Configure business hours for each day of the week (LocalBusiness)
* **Price Range** - Specify price level for local businesses
* **Social Media Integration** - Add unlimited social media profile links
* **Media Library Integration** - Upload logos and images directly from WordPress
* **Person Schema Support** - Perfect for personal websites, portfolios, and freelancers
* **Clean Output** - Automatically removes empty values from schema output
* **User-Friendly Interface** - Intuitive admin panel with tooltips and help documentation

= Schema Types Supported =

**Organization**
General organization schema for companies, corporations, and non-profits. Includes name, logo, industry, contact information, address, and social profiles.

**LocalBusiness**
Extended organization schema for businesses with physical locations. Includes everything in Organization plus opening hours and price range.

**Person**
Schema for personal websites and portfolios. Includes name, image, job title, gender, employer information, contact details, and social profiles.

= Why Use Swift Rank? =

Schema markup helps search engines understand your content better, which can lead to:

* Enhanced search results with rich snippets
* Better visibility in Google's Knowledge Panel
* Improved local SEO for businesses with physical locations
* Higher click-through rates from search results
* Better representation in voice search results

= Dynamic Variables =

Swift Rank includes a powerful variables system:

* `{site_name}` - Your WordPress site name
* `{site_url}` - Your site home URL
* `{site_description}` - Your site tagline
* `{option:option_name}` - Any WordPress option value

Variables update automatically when your site settings change, keeping your schema data current without manual updates.

= Easy to Use =

1. Install and activate the plugin
2. Go to Settings → Swift Rank
3. Enable Knowledge Graph Schema
4. Choose your schema type (Organization, LocalBusiness, or Person)
5. Fill in your information
6. Save and test with Google Rich Results Test

== Installation ==

= Automatic Installation =

1. Log in to your WordPress admin dashboard
2. Navigate to Plugins → Add New
3. Search for "Swift Rank"
4. Click "Install Now" on the Swift Rank plugin
5. Activate the plugin

= Manual Installation =

1. Download the plugin ZIP file
2. Log in to your WordPress admin dashboard
3. Navigate to Plugins → Add New → Upload Plugin
4. Choose the downloaded ZIP file
5. Click "Install Now"
6. Activate the plugin

= After Activation =

1. Go to Settings → Swift Rank
2. Click the Knowledge Graph tab
3. Check "Enable Knowledge Graph Schema"
4. Select your schema type (Organization, LocalBusiness, or Person)
5. Fill in your information
6. Click "Save Changes"
7. Visit your homepage and view source to verify schema output
8. Test with Google Rich Results Test: https://search.google.com/test/rich-results

== Frequently Asked Questions ==

= What is Schema.org structured data? =

Schema.org is a collaborative project that provides a collection of schemas (structured data markup) that webmasters can use to mark up their pages. Search engines like Google use this data to better understand your content and display rich results.

= Does this plugin guarantee rich snippets in Google? =

No plugin can guarantee rich snippets. Schema markup helps Google understand your content, but Google decides when and how to display rich results based on many factors including content quality and relevance.

= Which schema type should I choose? =

* Choose **Organization** for general businesses, corporations, and non-profits
* Choose **LocalBusiness** for businesses with physical locations and operating hours
* Choose **Person** for personal websites, portfolios, or individual professionals

= How do I verify my schema is working? =

1. Visit your homepage
2. View page source (Ctrl+U or Cmd+U)
3. Look for `<!-- Swift Rank -->` comment
4. Test with Google Rich Results Test: https://search.google.com/test/rich-results
5. Validate with Schema.org validator: https://validator.schema.org/

= What are variables and how do I use them? =

Variables are placeholders that automatically insert dynamic WordPress data. Click the "Insert Variable" button next to any field, select a variable, and it will be added to your field. Variables update automatically when your site settings change.

= How do I add multiple social media profiles? =

In the Social Media Profiles section, click "Add Social Profile" to add as many profiles as you need. Enter the full URL for each profile.

= What image format should I use for my logo? =

Google recommends:
* Square or 16:9 aspect ratio
* Maximum height of 60px
* PNG format with transparent background preferred
* Hosted on your own domain with HTTPS


== Screenshots ==

1. Knowledge Graph Settings - Main configuration page with all schema options

== Changelog ==

== 1.0.2 - 2025-12-12 ==
- Fix: JSON-LD output was not being escaped properly.

== 1.0.1 - 2025-12-05 ==
- Update plugin name.

== 1.0.0 - 2025-11-27 ==
* Initial release

== Upgrade Notice ==

= 1.0.1 =
Plugin rebranded from "Schema Engine" to "Swift Rank". All functionality remains the same.

= 1.0.0 =
Initial release of Swift Rank. Add professional Schema.org structured data to your WordPress site.

== Support ==

For support, feature requests, or bug reports, please visit:
* Documentation: See the Help & Variables tab in plugin settings
* Website: https://racase.com.np/contact-me/
* WordPress Support Forum: https://wordpress.org/support/plugin/swift-rank/