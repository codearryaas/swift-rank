# Copilot Instructions: Schema Master WordPress Plugin

## Project Overview
WordPress plugin for comprehensive Schema.org structured data management. Supports schema templates with conditional display, individual post schemas, and a dynamic variable system. Based on schema-master-creator architecture.

**Environment**: Local by Flywheel ("skynet" site) at `/wp-content/plugins/schema-master`  
**Reference Plugin**: `schema-master-creator` (sibling directory - study for patterns)

## Core Architecture

### Plugin Structure (Singleton Pattern)
```
schema-master/
├── schema-master.php                          # Main file: constants, autoloader, singleton init
├── uninstall.php                          # Cleanup on uninstall
├── includes/
│   ├── class-schema-master-template-cpt.php   # Schema Template custom post type
│   ├── class-schema-master-template-metabox.php # Template editor UI
│   ├── class-schema-master-metabox.php        # Post/page metabox for individual schemas
│   ├── class-schema-master-output.php         # Frontend schema rendering (wp_head)
│   ├── class-schema-master-templates.php      # Schema type definitions & generators
│   ├── class-schema-master-helpers.php        # Variable replacement system
│   └── class-schema-master-admin.php          # Settings page & global schemas
├── assets/
│   ├── css/admin.css                      # Native WordPress admin styling
│   └── js/admin.js                        # Schema type switcher, variable insertion
└── languages/                             # i18n files
```

### Key Classes & Responsibilities
- **TP_Schema_Template_CPT**: Registers `tp_schema_template` CPT, stores reusable templates with conditions (post types, taxonomies, specific posts)
- **TP_Schema_Metabox**: Adds metabox to posts/pages; automatically renders template fields when conditions match (NO dropdown)
- **TP_Schema_Output**: Outputs JSON-LD in `<head>` based on conditions (auto-schema, custom, templates)
- **TP_Schema_Helpers**: Variable replacement engine (`{post_title}`, `{meta:field_name}`, etc.)
- **TP_Schema_Templates**: Contains `get_template()` method with switch-case for all schema types

## Design Guidelines

### Native WordPress UI Standards
All backend interfaces MUST follow native WordPress admin design patterns:

**Colors & Theming**:
- Use WordPress admin color scheme variables
- Primary blue: `#2271b1` (WP admin blue)
- Hover blue: `#135e96`
- Background: `#f0f0f1` (WP admin gray)
- White panels: `#fff`
- Border color: `#c3c4c7` or `#dcdcde`
- Text colors: `#1d2327` (dark), `#50575e` (medium), `#787c82` (light)
- Error red: `#d63638`
- Success green: `#00a32a`

**Typography**:
- Font family: `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif`
- Use WordPress standard font sizes
- Heading hierarchy matches WP admin (`<h1>`, `<h2>`, `<h3>`)

**Form Elements**:
- Use `.form-table` for settings forms
- Standard `<th>` and `<td>` structure
- `.regular-text` for text inputs (25em width)
- `.small-text` for small inputs (like numbers)
- `.large-text` for larger text inputs
- `.widefat` for full-width inputs
- `.button`, `.button-primary`, `.button-secondary` classes
- `.description` for help text below fields
- Checkbox and radio styles match WP defaults

**Admin Pages**:
- `.wrap` container for all admin pages
- Page title with `<h1>` using `get_admin_page_title()`
- `.nav-tab-wrapper` and `.nav-tab` for tabbed interfaces
- Match WP Settings API patterns
- Use `do_settings_sections()` and `settings_fields()`

**Metaboxes**:
- Standard `.postbox` structure for custom post types
- `.inside` for content padding
- Native metabox styling for post editor
- Use WP's metabox functions

**Notices & Alerts**:
- `.notice`, `.notice-success`, `.notice-error`, `.notice-warning`
- `.notice-info` for informational messages
- `.is-dismissible` for dismissible notices

**Icons**:
- Use Dashicons (`.dashicons`) for all icons
- Common icons: `dashicons-editor-help`, `dashicons-admin-generic`, `dashicons-editor-code`
- Match icon sizes to WP standards (20px typical)

**Spacing & Layout**:
- Consistent padding: 20px for panels, 12px for inner elements
- Margin bottom: 20px between major sections
- Use WP's grid system when available
- Responsive breakpoints match WP admin (782px)

**Buttons & Actions**:
- `.button` (default gray)
- `.button-primary` (blue action button)
- `.button-secondary` (outlined)
- `.button-link` (text-only)
- `.button-link-delete` (red delete action)
- Standard button heights and padding

**DO NOT**:
- ❌ Use custom color schemes that don't match WP admin
- ❌ Create custom button styles that differ from WP buttons
- ❌ Use non-standard fonts or font weights
- ❌ Add heavy animations that feel un-WordPress-like
- ❌ Use Material Design, Bootstrap, or other non-WP frameworks
- ❌ Create custom form element styles that differ from WP
- ❌ Use box-shadows that don't match WP standards (subtle only)
- ❌ Implement custom tab designs that differ from `.nav-tab`

**DO**:
- ✅ Study core WordPress admin pages (Settings → General, Posts → Add New)
- ✅ Use WordPress core CSS classes whenever possible
- ✅ Match spacing, padding, and margins to WP admin exactly
- ✅ Keep hover effects subtle and WP-like
- ✅ Use WP's built-in media uploader
- ✅ Follow WP's responsive design patterns
- ✅ Use `wp_enqueue_style()` to load WP core styles when needed
- ✅ Test with different WP admin color schemes
- ✅ Keep designs clean, minimal, and functional

## Critical Patterns

### 1. Variable System (Curly Braces - No Spaces)
**Format**: `{variable_name}` (NOT `{{variable_name}}` like schema-master-creator)

**Available Variables**:
```php
// Post context
{post_title}, {post_content}, {post_excerpt}
{post_date}, {post_modified}, {post_author}, {post_author_url}
{post_url}, {featured_image}, {image_width}, {image_height}

// Site context
{site_name}, {site_url}

// Custom fields
{meta:field_name} // e.g., {meta:price}, {meta:event_date}
```

**Implementation** (`class-schema-master-helpers.php`):
```php
public static function replace_variables( $json, $post ) {
    $replacements = array(
        '{post_title}' => get_the_title( $post->ID ),
        // ... etc
    );
    $json = str_replace( array_keys($replacements), array_values($replacements), $json );
    
    // Handle {meta:*} with regex
    $json = preg_replace_callback(
        '/\{meta:([a-zA-Z0-9_-]+)\}/',
        function($m) use ($post) {
            return get_post_meta($post->ID, $m[1], true);
        },
        $json
    );
    return $json;
}
```

### 2. Schema Template Conditional Logic & Metabox Display
Templates match posts via post meta `_tp_template_conditions`:
```php
array(
    'post_types' => array('post', 'page'),           // Apply to these post types
    'taxonomies' => array('category' => array(5,7)), // Apply to posts in categories 5,7
    'specific_posts' => '12,34,56'                   // Apply to specific post IDs
)
```

**Automatic Metabox Rendering** (in `class-schema-master-metabox.php`):
```php
// Get all published templates
$templates = get_posts(array('post_type' => 'tp_schema_template', 'post_status' => 'publish'));

// Find matching template for current post
$matching_template = null;
foreach ($templates as $template) {
    $conditions = get_post_meta($template->ID, '_tp_template_conditions', true);
    if (self::matches_conditions($post, $conditions)) {
        $matching_template = $template;
        break; // Use first matching template
    }
}

// If template matches, render its fields directly in metabox
if ($matching_template) {
    $schema_type = get_post_meta($matching_template->ID, '_tp_template_schema_type', true);
    $template_fields = get_post_meta($matching_template->ID, '_tp_template_fields', true);
    
    // Render type-specific fields based on schema type
    $this->render_template_fields($schema_type, $template_fields, $post);
}
```

**Key Behavior**: 
- NO dropdown selection needed
- Template fields auto-populate in metabox when conditions match
- User edits field values directly
- Individual field values stored in post meta: `_tp_schema_field_{fieldname}`

### 3. Schema Output Priority (Frontend)
**Order** (in `class-schema-master-output.php::output_schema()`):
1. Check if schema enabled in settings (`tp_schema_settings['enabled']`)
2. Homepage: Output organization schema if enabled
3. Singular posts:
   - Check for `_tp_schema_template_id` → if exists:
     - Load template schema type
     - Get field values from post meta `_tp_schema_field_{fieldname}`
     - Build schema array using template structure
     - Replace variables with actual values
   - Else if auto-schema enabled → generate Article schema
4. Output all schemas as JSON-LD in `<script type="application/ld+json">`

**Template-Based Output Example**:
```php
// Get template ID from post
$template_id = get_post_meta($post->ID, '_tp_schema_template_id', true);

if ($template_id) {
    $schema_type = get_post_meta($template_id, '_tp_template_schema_type', true);
    $template_fields = get_post_meta($template_id, '_tp_template_fields', true);
    
    // Build schema with post-specific field values
    $schema = $this->build_schema_from_template($schema_type, $template_fields, $post);
    
    // Replace variables
    $schema_json = json_encode($schema);
    $schema_json = TP_Schema_Helpers::replace_variables($schema_json, $post);
}
```

### 4. Schema Type Definitions
**Location**: `class-schema-master-templates.php::get_template($type, $post)`

**Pattern**: Large switch statement with default values using variables:
```php
case 'Product':
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => '{post_title}',
        'description' => '{post_excerpt}',
        'image' => '{featured_image}',
        'offers' => array(
            '@type' => 'Offer',
            'price' => '{meta:price}',
            'priceCurrency' => 'USD',
            'availability' => 'https://schema.org/InStock'
        )
    );
```

**Supported Types**: Article, BlogPosting, NewsArticle, Event, Product, Recipe, Review, HowTo, VideoObject, LocalBusiness, Person, Organization, FAQPage, Custom

### 5. Meta Box Field Rendering (Automatic Display)
**Template Creation** (in `class-schema-master-template-metabox.php` - admin creates template):
```php
private function render_type_fields($type, $data) {
    switch ($type) {
        case 'Event':
            echo '<label>Event Start Date</label>';
            echo '<input type="datetime-local" name="tp_template_fields[startDate]" value="...">';
            echo '<label>Location</label>';
            echo '<input type="text" name="tp_template_fields[location][name]">';
            break;
        case 'Product':
            echo '<label>Product Name <span class="required">*</span></label>';
            echo '<input type="text" name="tp_template_fields[name]" value="' . esc_attr($data['name'] ?? '{post_title}') . '">';
            echo '<label>Price</label>';
            echo '<input type="number" name="tp_template_fields[price]" value="' . esc_attr($data['price'] ?? '{meta:price}') . '">';
            break;
    }
}
```

**Post/Page Metabox** (in `class-schema-master-metabox.php` - shows fields on matching posts):
```php
// When template matches, render fields for user to fill
private function render_template_fields($schema_type, $template_fields, $post) {
    switch ($schema_type) {
        case 'Product':
            $name = get_post_meta($post->ID, '_tp_schema_field_name', true) ?: $template_fields['name'];
            echo '<label>Product Name</label>';
            echo '<input type="text" name="tp_schema_fields[name]" value="' . esc_attr($name) . '">';
            
            $price = get_post_meta($post->ID, '_tp_schema_field_price', true) ?: $template_fields['price'];
            echo '<label>Price</label>';
            echo '<input type="number" name="tp_schema_fields[price]" value="' . esc_attr($price) . '">';
            break;
    }
}
```

**Meta storage**: 
- Templates: `_tp_template_fields` (defaults/variables) + `_tp_template_schema_type`
- Post overrides: `_tp_schema_field_{fieldname}` (individual field values)
- Post tracking: `_tp_schema_template_id` (which template is applied)

## Development Workflow

### Adding a New Schema Type
1. Add case to `TP_Schema_Templates::get_template()` with schema structure
2. Add option to schema type dropdown in metabox classes
3. Add type-specific fields to `render_type_fields()` in template metabox
4. Add column label to `TP_Schema_Template_CPT::render_custom_columns()`

### Testing Schema Output
```bash
# View schema in page source
curl -s http://skynet.local/sample-post/ | grep -A 50 'application/ld+json'

# Validate with Google Rich Results Test
# Copy JSON-LD from browser inspector → paste at schema.org/validator
```

### Debugging
```php
// In class methods
error_log('Schema Master: ' . print_r($schema_data, true));

// Enable in wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## WordPress Conventions

### Security (Always Applied)
- **Nonces**: Verify with `wp_verify_nonce($_POST['_wpnonce'], 'tp_schema_action')`
- **Capabilities**: Check `current_user_can('edit_post', $post_id)` before saving
- **Sanitization**: 
  - `sanitize_text_field()` for text inputs
  - `wp_kses_post()` for HTML content
  - `absint()` for IDs
- **Escaping**: 
  - `esc_html()` for text output
  - `esc_attr()` for HTML attributes
  - `esc_url()` for URLs

### Data Storage
- **Settings**: `get_option('tp_schema_settings')` (array with enabled, post_types, auto_schema, organization_schema)
- **Templates**: CPT `tp_schema_template` with meta:
  - `_tp_template_schema_type` - Schema type (Product, Event, etc.)
  - `_tp_template_fields` - Default values with variables
  - `_tp_template_conditions` - Matching rules (post_types, taxonomies, specific_posts)
- **Post Schemas**: 
  - `_tp_schema_template_id` - ID of applied template
  - `_tp_schema_field_{fieldname}` - Individual field values (e.g., `_tp_schema_field_price`, `_tp_schema_field_name`)
  - Values can contain variables like `{post_title}` or literal values like `$29.99`

### Coding Standards
- **Prefix**: `tp_schema_` for all functions/globals, `TP_Schema_` for classes
- **Hooks**: Format as `tp_schema_{action}` (e.g., `tp_schema_before_output`)
- **Indentation**: Tabs (WordPress standard)
- **Braces**: Always use braces, even for single-line statements
- **Translation**: Wrap strings in `__('text', 'schema-master')` or `_e('text', 'schema-master')`

## Common Tasks

### Enqueue Admin Assets
```php
add_action('admin_enqueue_scripts', function($hook) {
    if ('post.php' !== $hook && 'post-new.php' !== $hook) return;
    wp_enqueue_style('schema-master-admin', TP_SCHEMA_PLUGIN_URL . 'assets/css/admin.css', array(), TP_SCHEMA_VERSION);
    wp_enqueue_script('schema-master-admin', TP_SCHEMA_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), TP_SCHEMA_VERSION, true);
});
```

### Save Metabox Data
```php
add_action('save_post', function($post_id) {
    if (!isset($_POST['tp_schema_nonce']) || !wp_verify_nonce($_POST['tp_schema_nonce'], 'tp_schema_save')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Save individual field values
    if (isset($_POST['tp_schema_fields']) && is_array($_POST['tp_schema_fields'])) {
        foreach ($_POST['tp_schema_fields'] as $field_name => $field_value) {
            update_post_meta($post_id, '_tp_schema_field_' . $field_name, sanitize_text_field($field_value));
        }
    }
    
    // Track which template is applied
    if (isset($_POST['tp_schema_template_id'])) {
        update_post_meta($post_id, '_tp_schema_template_id', absint($_POST['tp_schema_template_id']));
    }
});
```

## Reference Materials
- Study `schema-master-creator` plugin in sibling directory for implementation patterns
- Schema.org: https://schema.org/docs/schemas.html
- Google Rich Results Test: https://search.google.com/test/rich-results
- WordPress Plugin Handbook: https://developer.wordpress.org/plugins/
