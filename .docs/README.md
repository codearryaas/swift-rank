# Schema Master Documentation

## Overview

Schema Master is a WordPress plugin that adds Schema.org structured data to your website. It helps search engines understand your content better by providing structured data in JSON-LD format, which can improve your site's visibility in search results and enable rich snippets.

## Table of Contents

1. [Features](#features)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Schema Types](#schema-types)
5. [Variables System](#variables-system)
6. [Knowledge Graph Settings](#knowledge-graph-settings)
7. [Testing Your Schema](#testing-your-schema)
8. [Developer Documentation](#developer-documentation)
9. [Troubleshooting](#troubleshooting)
10. [Support](#support)

## Features

- **Knowledge Graph Support**: Add Organization, LocalBusiness, or Person schema to your homepage
- **JSON-LD Format**: Uses the recommended JSON-LD format for structured data
- **Dynamic Variables**: Use variables to insert dynamic content into your schema
- **Industry Selection**: Choose from 15+ industry categories
- **Contact Information**: Add phone, email, fax, and contact type
- **Address Details**: Complete postal address support
- **Opening Hours**: Configure business hours for each day of the week
- **Social Media Profiles**: Add links to all your social media accounts
- **Price Range**: Specify price range for local businesses
- **Person Schema**: Support for personal websites with job title, gender, and employer info
- **Image Upload**: Built-in media library integration for logos
- **Clean Output**: Automatically removes empty values from schema output
- **Variable System**: Insert dynamic WordPress data into schema fields

## Installation

### From WordPress Admin

1. Download the plugin ZIP file
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin" button
4. Choose the ZIP file and click "Install Now"
5. Activate the plugin

### Manual Installation

1. Extract the plugin ZIP file
2. Upload the `schema-master` folder to `/wp-content/plugins/`
3. Go to WordPress Admin → Plugins
4. Activate "Schema Master"

## Configuration

After activation, configure the plugin:

1. Go to **Settings → Schema Master**
2. Click on the **Knowledge Graph** tab
3. Enable "Enable Knowledge Graph Schema"
4. Configure your settings (see below)
5. Click "Save Changes"

## Schema Types

### Organization

General organization schema suitable for companies, non-profits, and businesses.

**Supported Fields:**
- Name
- Logo
- Industry
- Phone, Email, Fax
- Contact Type
- Address (Street, City, State, Postal Code, Country)
- Social Media Profiles

**When to Use:**
- Corporate websites
- Non-profit organizations
- General business sites without physical locations

### LocalBusiness

Extended organization schema for businesses with physical locations.

**Additional Fields:**
- Price Range
- Opening Hours (per day)

**When to Use:**
- Restaurants
- Retail stores
- Service businesses with physical locations
- Any business with operating hours

### Person

Schema for personal websites, portfolios, or individual professionals.

**Supported Fields:**
- Name
- Image/Photo
- Job Title
- Gender
- Works For (Organization)
- Phone, Email
- Address
- Social Media Profiles

**When to Use:**
- Personal blogs
- Professional portfolios
- Author websites
- Freelancer sites

## Variables System

Schema Master includes a powerful variables system that allows you to insert dynamic content into your schema fields.

### Available Variables

#### Site Variables

- `{site_name}` - Your WordPress site name
- `{site_url}` - Your site home URL
- `{site_description}` - Your site tagline/description

#### Custom Variables

- `{option:option_name}` - Get any WordPress option value

### How to Use Variables

1. **Click "Insert Variable" Button**: Look for the button next to input fields
2. **Select a Variable**: Choose from the dropdown menu
3. **Variable is Inserted**: Added at cursor position
4. **Mix with Text**: Combine variables with static text

### Example Usage

```
Organization Name: {site_name}
Custom Option: {option:blogdescription}
Mixed: Contact {site_name} at {option:admin_email}
```

### Variable Examples

```
Name Field: {site_name}
Result: Your WordPress Site Name

Logo Field: {site_url}/wp-content/uploads/logo.png
Result: https://example.com/wp-content/uploads/logo.png

Email Field: {option:admin_email}
Result: admin@example.com
```

## Knowledge Graph Settings

### Basic Settings

**Enable Knowledge Graph Schema**
- Checkbox to enable/disable schema output on homepage
- Schema only appears when enabled

**Data Type**
- Organization: General business or organization
- Local Business: Business with physical location and hours
- Person: Individual or personal website

### Organization/LocalBusiness Fields

**Industry** (Organization only)
- Select from predefined list
- Helps categorize your business
- Appears as "knowsAbout" in schema

**Name**
- Organization or business name
- Default: `{site_name}`
- Required field

**Logo URL**
- URL to your logo image
- Upload button for media library
- Recommended: Square or 16:9, max 60px height, PNG preferred
- Preview shown below field

**Contact Information**
- Phone: International format (e.g., +1-555-123-4567)
- Email: Valid email address
- Fax: Optional fax number
- Contact Type: e.g., "Customer Service", "Sales", "Technical Support"

**Address**
- Street Address: Physical address
- City: City name
- State/Region: State, province, or region
- Postal Code: ZIP or postal code
- Country: Country name

**Price Range** (LocalBusiness only)
- Symbol format: $, $$, $$$, $$$$
- Range format: $10-$50, $25-$100
- Helps users understand pricing level

**Opening Hours** (LocalBusiness only)
- Configure for each day of the week
- Opens/Closes times in 24-hour format
- Checkbox for closed days
- Visual day-by-day interface

**Social Media Profiles**
- Add multiple social media URLs
- Facebook, Twitter, LinkedIn, Instagram, etc.
- Appears as "sameAs" in schema

### Person Fields

**Name**
- Person's full name
- Default: `{site_name}`
- Required field

**Logo URL**
- Profile photo or avatar
- Same upload functionality as Organization

**Job Title**
- Current position or role
- e.g., "Software Engineer", "Web Developer"

**Gender**
- Gender identity
- e.g., "Male", "Female", "Non-binary"

**Works For (Organization)**
- Name of employer organization
- Creates linked Organization schema
- Supports variables

**Contact & Address**
- Same fields as Organization
- Optional for Person schema

## Testing Your Schema

After configuration, validate your schema:

### Google Rich Results Test

1. Visit [Google Rich Results Test](https://search.google.com/test/rich-results)
2. Enter your homepage URL
3. Click "Test URL"
4. Review results and fix any errors

### Schema.org Validator

1. Visit [Schema.org Validator](https://validator.schema.org/)
2. Enter your homepage URL
3. Review validation results

### Manual Verification

1. Visit your homepage
2. View page source (Ctrl+U / Cmd+U)
3. Look for `<!-- Schema Master -->` comment
4. Verify JSON-LD script tag in `<head>` section

### Expected Output Example

```html
<!-- Schema Master -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Your Company Name",
  "url": "https://example.com",
  "logo": {
    "@type": "ImageObject",
    "url": "https://example.com/logo.png"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "Customer Service",
    "telephone": "+1-555-123-4567",
    "email": "contact@example.com"
  },
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "123 Main Street",
    "addressLocality": "New York",
    "addressRegion": "NY",
    "postalCode": "10001",
    "addressCountry": "United States"
  },
  "sameAs": [
    "https://facebook.com/yourpage",
    "https://twitter.com/yourhandle"
  ]
}
</script>
<!-- /Schema Master -->
```

## Developer Documentation

### Plugin Architecture

```
schema-master/
├── assets/
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
├── includes/
│   ├── class-tp-schema-admin.php
│   └── class-tp-schema-output.php
├── languages/
├── schema-master.php
└── uninstall.php
```

### Main Classes

#### TP_Schema
Main plugin class (Singleton pattern)
- File: `schema-master.php`
- Handles plugin initialization
- Loads dependencies
- Manages activation/deactivation

#### TP_Schema_Admin
Admin interface and settings
- File: `includes/class-tp-schema-admin.php`
- Renders settings page
- Handles settings validation
- Manages admin assets

#### TP_Schema_Output
Frontend schema output
- File: `includes/class-tp-schema-output.php`
- Generates JSON-LD output
- Processes variables
- Outputs on homepage

### Hooks and Filters

#### Actions

```php
// Plugin initialization
add_action('plugins_loaded', array($this, 'load_textdomain'));

// Admin hooks
add_action('admin_menu', array($this, 'add_admin_menu'));
add_action('admin_init', array($this, 'register_settings'));
add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

// Frontend output
add_action('wp_head', array($this, 'output_schema'), 1);

// Activation/Deactivation
register_activation_hook(__FILE__, array($this, 'activate'));
register_deactivation_hook(__FILE__, array($this, 'deactivate'));
```

#### Filters

Currently no public filters, but you can extend functionality by modifying the schema array before output.

### Constants

```php
TP_SCHEMA_VERSION        // Plugin version
TP_SCHEMA_PLUGIN_DIR     // Plugin directory path
TP_SCHEMA_PLUGIN_URL     // Plugin directory URL
TP_SCHEMA_PLUGIN_BASENAME // Plugin basename
```

### Database Options

Settings stored in: `tp_schema_settings`

Structure:
```php
array(
    'organization_schema' => bool,
    'organization_type' => string,
    'organization_industry' => string,
    'organization_name' => string,
    'organization_logo' => string,
    'organization_phone' => string,
    'organization_email' => string,
    'organization_fax' => string,
    'organization_contact_type' => string,
    'organization_address' => string,
    'organization_city' => string,
    'organization_state' => string,
    'organization_postal_code' => string,
    'organization_country' => string,
    'organization_price_range' => string,
    'organization_hours' => array,
    'organization_social' => array,
    'organization_job_title' => string,
    'organization_gender' => string,
    'organization_works_for' => string,
)
```

### Extending the Plugin

#### Add Custom Variables

Modify the `get_schema_variables()` method in `TP_Schema_Admin`:

```php
private function get_schema_variables()
{
    return array(
        'Site Info' => array(
            array(
                'label' => 'Custom Variable',
                'value' => '{custom_var}',
            ),
        ),
    );
}
```

Then handle replacement in `replace_site_variables()` method in `TP_Schema_Output`:

```php
private function replace_site_variables($json)
{
    $replacements = array(
        '{custom_var}' => 'Your custom value',
    );
    return str_replace(array_keys($replacements), array_values($replacements), $json);
}
```

#### Add Custom Schema Types

Modify the schema type dropdown in the `organization_type_field_callback()` method.

#### Add New Fields

1. Add settings field in `register_settings()` method
2. Add field callback method
3. Add sanitization in `sanitize_settings()` method
4. Add to schema output in `get_organization_schema()` method

## Troubleshooting

### Schema Not Appearing

**Check:**
1. Is the plugin activated?
2. Is "Enable Knowledge Graph Schema" checked?
3. Are you viewing the homepage?
4. View page source to see if schema is present

### Invalid Schema Errors

**Common Issues:**
1. Empty required fields (name, URL)
2. Invalid URL formats
3. Invalid phone/email formats
4. Malformed opening hours

**Solution:**
- Use validation tools to identify specific errors
- Check all required fields are filled
- Verify URL formats are correct

### Variables Not Replacing

**Check:**
1. Variable syntax is correct: `{variable_name}`
2. Variable exists in the system
3. For option variables, the option exists: `{option:option_name}`

### Opening Hours Not Showing

**Check:**
1. Schema type is "Local Business"
2. At least one day has hours configured
3. Days are not all marked as "Closed"

### Logo Not Displaying in Rich Results

**Check:**
1. Image URL is accessible publicly
2. Image meets size requirements
3. Image format is supported (PNG, JPG)
4. HTTPS is used for the image URL

## Support

### Getting Help

1. Check this documentation first
2. Review the Help & Variables tab in plugin settings
3. Test with validation tools
4. Check browser console for JavaScript errors

### Reporting Issues

When reporting issues, include:
- WordPress version
- PHP version
- Plugin version
- Active theme
- Other active plugins
- Error messages or screenshots
- Steps to reproduce

### Common Questions

**Q: Does this work with Yoast SEO or Rank Math?**
A: Yes, Schema Master can work alongside other SEO plugins. However, be aware of potential schema conflicts if multiple plugins output similar schema types.

**Q: Can I use this on pages other than the homepage?**
A: Currently, knowledge graph schema only appears on the homepage. Future versions may include support for other pages.

**Q: Does this support WooCommerce products?**
A: Not yet. This version focuses on organization/business schema. Product schema support is planned for future releases.

**Q: Is the schema output cached?**
A: Schema output respects your site's caching. Clear cache after changing settings to see updates immediately.

**Q: Can I use custom post meta in variables?**
A: Not directly in this version. You can use WordPress options via `{option:option_name}` syntax.

## Best Practices

### Schema Configuration

1. **Be Accurate**: Only include information that accurately represents your business
2. **Use Variables**: Leverage variables for maintainability
3. **Complete Information**: Fill all relevant fields for best results
4. **Valid URLs**: Ensure all URLs are publicly accessible
5. **Test Regularly**: Validate schema after any changes

### Logo Guidelines

- Use square or 16:9 aspect ratio
- Maximum height: 60px (Google requirement)
- Transparent PNG preferred
- Host on your own domain
- Use HTTPS

### Opening Hours

- Use 24-hour format consistently
- Mark closed days explicitly
- Update for holidays or special hours
- Consider timezone implications

### Social Profiles

- Use official profile URLs
- Include only active profiles
- Use full URLs (not shortened links)
- Verify all links work

## Changelog

### Version 1.0.0
- Initial release
- Organization schema support
- LocalBusiness schema support
- Person schema support
- Knowledge graph integration
- Dynamic variables system
- Opening hours configuration
- Social media profiles
- Contact information
- Address details
- Admin interface with tabs
- Media library integration
- Variable insertion system

## Credits

Developed by Rakesh Lawaju
Website: https://racase.com.np

## License

GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
