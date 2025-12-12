# Schema Master - WordPress Schema Plugin

A comprehensive WordPress plugin for managing structured data (Schema.org) with reusable templates, conditional display, and dynamic variable system.

## Features

### Core Functionality
✅ **Schema Templates** - Create reusable schema templates with conditions
✅ **Conditional Display** - Auto-apply templates based on post types, taxonomies, or specific posts
✅ **Dynamic Variables** - Use `{post_title}`, `{featured_image}`, `{meta:custom_field}` etc.
✅ **Auto Schema** - Automatically generate Article schema for posts/pages
✅ **Organization Schema** - Add organization information to homepage
✅ **JSON-LD Output** - Clean, valid JSON-LD in `<head>`

### Supported Schema Types
- Article / BlogPosting / NewsArticle
- Event
- Product
- Recipe
- Review
- HowTo
- VideoObject
- LocalBusiness
- Person
- Organization
- FAQPage
- Custom (write your own)

### Variable System
Templates use single curly braces `{variable_name}`:

**Post Variables:**
- `{post_title}` - Post title
- `{post_content}` - Post content (HTML stripped)
- `{post_excerpt}` - Post excerpt
- `{post_date}` - Publish date (ISO 8601)
- `{post_modified}` - Last modified date
- `{post_author}` - Author name
- `{post_author_url}` - Author URL
- `{post_url}` - Post permalink
- `{featured_image}` - Featured image URL
- `{image_width}` - Featured image width
- `{image_height}` - Featured image height

**Site Variables:**
- `{site_name}` - Site name
- `{site_url}` - Site URL

**Custom Field Variables:**
- `{meta:field_name}` - Any custom field value

## Installation

1. Upload `/schema-master/` to `/wp-content/plugins/`
2. Activate the plugin through WordPress admin
3. Go to **Schema** → **Settings** to configure
4. Create schema templates under **Schema** → **Schema Templates**

## Usage

### Step 1: Configure Settings
1. Go to **Schema** → **Settings**
2. Enable schema output globally
3. Select post types where schema should be available
4. Enable auto-schema if desired
5. Configure organization schema for homepage (optional)

### Step 2: Create Schema Template
1. Go to **Schema** → **Schema Templates**
2. Click **Add New**
3. Give your template a title (e.g., "Product Schema")
4. Select schema type (e.g., Product)
5. Set conditions:
   - Select post types (e.g., Products, Posts)
   - Or specify post IDs
6. Publish template

### Step 3: Edit Posts/Pages
1. Edit any post/page that matches template conditions
2. Template fields will automatically appear in "Schema Data" metabox
3. Fill in the fields (can use variables)
4. Update/Publish post

### Step 4: Verify Output
View page source and look for:
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  ...
}
</script>
```

## Template Matching Logic

Templates match posts based on conditions:

1. **Post Types** - Template applies to selected post types
2. **Taxonomies** - Template applies to posts in specific categories/tags
3. **Specific Posts** - Template applies to specific post IDs

**Priority:** First matching template wins. If no template matches and auto-schema is enabled, Article schema is generated.

## Data Storage

- **Settings:** `tp_schema_settings` option
- **Templates:** Custom post type `tp_schema_template`
- **Template Meta:**
  - `_tp_template_schema_type` - Schema type
  - `_tp_template_fields` - Default field values
  - `_tp_template_conditions` - Matching conditions
- **Post Meta:**
  - `_tp_schema_template_id` - Applied template ID
  - `_tp_schema_field_{fieldname}` - Individual field values

## File Structure

```
schema-master/
├── schema-master.php                          # Main plugin file
├── uninstall.php                          # Uninstall cleanup
├── includes/
│   ├── class-schema-master-admin.php          # Settings page
│   ├── class-schema-master-helpers.php        # Variable replacement
│   ├── class-schema-master-metabox.php        # Post/page metabox
│   ├── class-schema-master-output.php         # Frontend output
│   ├── class-schema-master-template-cpt.php   # Template CPT
│   ├── class-schema-master-template-metabox.php # Template editor
│   └── class-schema-master-templates.php      # Schema definitions
├── assets/
│   ├── css/admin.css                      # Admin styles
│   └── js/admin.js                        # Admin scripts
└── README.md                              # This file
```

## Development

### Adding New Schema Types

1. Add to `TP_Schema_Helpers::get_schema_types()`
2. Add case to `TP_Schema_Templates::get_template()`
3. (Optional) Add type-specific fields to `TP_Schema_Metabox::render_template_fields()`

### Testing Schema

```bash
# View schema in page source
curl -s http://yourdomain.local/sample-post/ | grep -A 50 'application/ld+json'

# Validate with Google Rich Results Test
# https://search.google.com/test/rich-results
```

## Requirements

- WordPress 5.0+
- PHP 7.0+

## License

GPL v2 or later

## Version

1.0.0
