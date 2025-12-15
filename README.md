# Swift Rank - WordPress Schema Plugin

A comprehensive WordPress plugin for managing structured data (Schema.org) with reusable templates, conditional display, and dynamic variable system.

## Features

### Core Functionality
✅ **Schema Templates** - Create reusable schema templates with conditions
✅ **Conditional Display** - Auto-apply templates based on post types, locations, or specific posts
✅ **Dynamic Variables** - Use `{post_title}`, `{featured_image}`, `{meta:custom_field}` etc.
✅ **Auto Schema** - Automatically generate schema for posts/pages
✅ **Knowledge Graph** - Add Organization/Person/LocalBusiness schema
✅ **JSON-LD Output** - Clean, valid JSON-LD in `<head>`
✅ **Schema Graph** - Interconnected schema with @id references

### Supported Schema Types (Free)

- **Article** (BlogPosting, NewsArticle)
- **Product**
- **Organization**
- **Person**
- **LocalBusiness**
- **Review**
- **VideoObject**
- **FAQ Page**
- **Breadcrumb**
- **Job Posting**
- **WebPage**
- **Website**
- **ImageObject**

### Pro Schema Types

- **Recipe** - Food recipes with ingredients and instructions
- **Event** - Events with date, time, and location
- **HowTo** - Step-by-step instructions
- **Podcast Episode** - Podcast content
- **Custom Schema** - Visual schema builder

### Variable System

Templates use single curly braces `{variable_name}`:

**Post Variables:**
- `{post_title}` - Post title
- `{post_content}` - Post content (HTML stripped)
- `{post_excerpt}` - Post excerpt
- `{post_date}` - Publish date (ISO 8601)
- `{post_modified}` - Last modified date
- `{post_url}` - Post permalink
- `{featured_image}` - Featured image URL

**Author Variables:**
- `{author_name}` - Author display name
- `{author_url}` - Author archive URL
- `{author_bio}` - Author biography
- `{author_email}` - Author email
- `{author_avatar}` - Author profile image

**Site Variables:**
- `{site_name}` - Site name
- `{site_url}` - Site URL
- `{site_description}` - Site tagline
- `{site_logo}` - Site logo URL

**Custom Field Variables:**
- `{meta:field_name}` - Any custom field value
- `{option:option_name}` - WordPress option value

## Installation

### Free Version

1. Upload `/swift-rank/` to `/wp-content/plugins/`
2. Activate the plugin through WordPress admin
3. Go to **Swift Rank → Settings** to configure
4. Create schema templates under **Swift Rank → Add New**

### Pro Version

1. Install and activate Swift Rank (free version) first
2. Upload and activate Swift Rank Pro
3. Go to **Swift Rank → Settings → License** to activate your license key

## Quick Start Guide

### Step 1: Configure Knowledge Graph

1. Go to **Swift Rank → Settings**
2. Select your entity type (Organization, Person, or LocalBusiness)
3. Fill in basic information (name, logo, contact details)
4. Add social media profiles
5. Save settings

### Step 2: Create Your First Schema Template

1. Go to **Swift Rank → Add New**
2. Enter a title (e.g., "Blog Post Article Schema")
3. Select schema type (e.g., Article)
4. Configure fields using variables:
   - Headline: `{post_title}`
   - Description: `{post_excerpt}`
   - Image: `{featured_image}`
   - Author: `{author_name}`
   - Date Published: `{post_date}`
5. Set display conditions (e.g., Post Type = Posts)
6. Publish template

### Step 3: Verify Schema Output

1. Visit any matching page on your site
2. View page source (right-click → View Page Source)
3. Search for `application/ld+json`
4. Verify schema appears correctly

### Step 4: Validate Schema

1. While viewing a page (logged in), look at the admin bar
2. Hover over **Swift Rank**
3. Click **Google Rich Results Test**
4. Review results and fix any errors

## Display Conditions

Templates can be targeted using:

- **Whole Site** - Every page
- **Post Type** - All posts of a specific type (Posts, Pages, Products, etc.)
- **Singular** - Specific individual posts/pages by ID
- **Location** - Special pages (Front Page, Blog Page, Search Page)

Conditions support AND/OR logic for complex targeting.

## Template Matching

When multiple templates match the same page:
- All matching schemas are output
- Schemas are combined into a single @graph
- Each schema gets a unique @id
- Schemas automatically reference each other when appropriate

## Data Storage

- **Settings:** `swift_rank_settings` option
- **Templates:** Custom post type `sr_template`
- **Template Meta:** Various meta fields for schema configuration
- **Post Meta:** Schema overrides per post

## File Structure

```
swift-rank/
├── swift-rank.php                     # Main plugin file
├── includes/
│   ├── admin/                         # Admin interface
│   ├── output/                        # Frontend output & schema types
│   ├── utils/                         # Utilities & helpers
│   └── schema-types-registration.php  # Schema type registration
├── src/                               # React source files
├── build/                             # Compiled assets
├── assets/                            # Static assets
└── .docs/                             # Documentation
    └── user-guide/                    # User documentation
```

## Testing Schema

### Using Built-in Tools

1. **Admin Bar Shortcuts** (when logged in):
   - Swift Rank → Google Rich Results Test
   - Swift Rank → Schema.org Validator

2. **Manual Validation**:
   - [Google Rich Results Test](https://search.google.com/test/rich-results)
   - [Schema.org Validator](https://validator.schema.org/)

### Command Line

```bash
# View schema in page source
curl -s https://yoursite.com/sample-post/ | grep -A 50 'application/ld+json'
```

## Requirements

- **WordPress:** 5.0 or higher
- **PHP:** 7.0 or higher
- **Browser:** Modern browsers (Chrome, Firefox, Safari, Edge)

## Documentation

Comprehensive user guides are available in `.docs/user-guide/`:

- [Installation Guide](/.docs/user-guide/installation.md)
- [Getting Started](/.docs/user-guide/getting-started.md)
- [Creating Templates](/.docs/user-guide/creating-templates.md)
- [Display Conditions](/.docs/user-guide/display-conditions.md)
- [Dynamic Variables](/.docs/user-guide/dynamic-variables.md)
- [Testing Schema](/.docs/user-guide/testing-schema.md)
- [Settings](/.docs/user-guide/settings.md)

## Support

- **Documentation:** Check the user guides in `.docs/user-guide/`
- **Support:** Visit [ToolPress.net Support](https://toolpress.net/support)
- **Pro License:** Required for Pro features and priority support

## License

GPL v2 or later

## Version

2.0.0
