# Changelog

## [1.0.0] - 2025

### Fixed

1. **Schema Type Fields Dynamic Loading**
   - Fixed AJAX handler for schema type change in template editor
   - Simplified admin.js to use WordPress global `ajaxurl` instead of requiring localization
   - Schema type preview now shows when type is selected
   - Action: `tp_schema_get_template_fields` is properly registered

2. **Enhanced Template Conditions**
   - Added Category selection to template conditions
   - Added Tag selection to template conditions
   - Templates can now match posts by:
     - Post Types (existing)
     - Categories (new)
     - Tags (new)
     - Specific Post IDs (existing)

3. **Variable Replacement in Frontend**
   - Confirmed variable replacement system is working correctly
   - Variables are replaced in this order:
     1. Template fields merged with post-specific values
     2. Schema converted to JSON
     3. TP_Schema_Helpers::replace_variables() replaces all standard variables
     4. JSON decoded back to array for output
   - Supported variables:
     - `{post_title}`, `{post_content}`, `{post_excerpt}`
     - `{post_date}`, `{post_modified}`
     - `{post_author}`, `{post_author_url}`, `{post_url}`
     - `{featured_image}`, `{image_width}`, `{image_height}`
     - `{site_name}`, `{site_url}`
     - `{meta:field_name}` for custom fields

### Implementation Details

#### Template Conditions UI (class-schema-master-template-metabox.php)
- Lines 310-395: Added category and tag checkbox groups
- Conditions stored in `_tp_template_conditions` meta with structure:
  ```php
  array(
      'post_types' => array('post', 'page'),
      'taxonomies' => array(
          'category' => array(5, 7, 9),
          'post_tag' => array(12, 15)
      ),
      'specific_posts' => array(123, 456)
  )
  ```

#### AJAX Handler (admin.js)
- Lines 8-30: Simplified schema type change handler
- Uses WordPress global `ajaxurl` (available in admin by default)
- Shows loading state while fetching preview
- Gracefully falls back to simple message if AJAX fails

#### Variable Replacement Flow (class-schema-master-output.php)
- Line 264-293: build_schema_from_template() method
  1. Gets base template structure from TP_Schema_Templates
  2. Merges with saved template fields
  3. Applies post-specific field values from meta
  4. Converts to JSON string
  5. Calls TP_Schema_Helpers::replace_variables()
  6. Decodes back to array

### Testing Recommendations

1. **Template Conditions**:
   - Create a template, select post type "Post"
   - Select a few categories
   - Save template
   - Create a post in one of those categories
   - Verify template metabox appears on that post

2. **Schema Type Preview**:
   - Edit a schema template
   - Change schema type dropdown
   - Verify preview appears below with template structure

3. **Variable Replacement**:
   - Create a template with variables like `{post_title}`, `{meta:price}`
   - Apply to a post with those meta fields
   - View page source
   - Verify JSON-LD has actual values, not `{post_title}`

### Known Issues

- WordPress coding standards warnings in template-metabox.php (cosmetic only, no functionality impact)
- These are alignment and formatting issues that don't affect execution

### Next Steps

Test the plugin in Local by Flywheel:
```bash
# Activate plugin
wp plugin activate schema-master --path="/Users/rakeshlawaju/Local Sites/skynet/app/public"

# Create a test template
# Go to WordPress admin → Schema Templates → Add New
# Select schema type, set conditions, save

# Create a test post matching conditions
# Verify schema appears in page source
curl -s http://skynet.local/your-test-post/ | grep -A 20 'application/ld+json'
```
