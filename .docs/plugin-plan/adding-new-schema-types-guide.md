# Schema Engine - Adding New Schema Types Guide

> **Purpose**: Step-by-step guide for AI assistants to add new schema types to the Schema Engine plugin.

## 📋 Overview

This guide walks through the complete process of adding a new schema type to Schema Engine, from research to implementation. Follow these steps carefully to ensure proper integration with the plugin architecture.

---

## 🎯 Prerequisites

Before adding a new schema type, ensure you have:

1. **Schema.org documentation** for the target schema type
2. **Google's structured data guidelines** (if applicable)
3. **An appropriate icon** from Lucide icons (https://lucide.dev/)
4. Understanding of **required vs. optional properties**

---

## 📚 Step 1: Research the Schema Type

### 1.1 Official Documentation

**Required Reading**:
- Schema.org specification: `https://schema.org/{SchemaType}`
- Google Search Central guidelines: `https://developers.google.com/search/docs/appearance/structured-data/{type}`

**What to Look For**:
- Required properties (must be included)
- Recommended properties (should be included for rich results)
- Property data types and formats
- Valid values for enumerations
- Nested objects and their structure

### 1.2 Google Requirements

Check if Google has specific requirements for rich results:

```bash
# Google URLs to check:
https://developers.google.com/search/docs/appearance/structured-data/{type}
https://support.google.com/webmasters/answer/{answer_id}
```

**Document**:
- ✅ Required fields for rich results
- ✅ Field formats (ISO 8601 dates, currency codes, etc.)
- ✅ Conditional requirements (e.g., remote jobs need location requirements)
- ✅ Common validation errors to avoid

### 1.3 Competitive Analysis (Optional)

Review how competitors implement the schema:
- Schema Pro
- Rank Math
- Yoast SEO

---

## 🛠️ Step 2: Create the Schema Builder Class

### 2.1 File Location & Naming

**Path**: `includes/output/types/class-{name}-schema.php`

**Naming Convention**:
- File: `class-job-posting-schema.php` (kebab-case)
- Class: `Schema_Job_Posting` (PascalCase with underscores)

### 2.2 Class Template

```php
<?php
/**
 * {Schema Type Name} Schema Builder
 *
 * @package Schema_Engine
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once dirname(__FILE__) . '/interface-schema-builder.php';

/**
 * Schema_{Name} class
 *
 * Builds {SchemaType} schema type.
 */
class Schema_{Name} implements Schema_Builder_Interface
{

    /**
     * Build schema from fields
     *
     * @param array $fields Field values.
     * @return array Schema array (without @context).
     */
    public function build($fields)
    {
        // Implementation here
        // See detailed guide below
    }

    /**
     * Get schema.org structure
     *
     * @return array Schema.org structure specification.
     */
    public function get_schema_structure()
    {
        return array(
            '@type' => '{SchemaType}',
            '@context' => 'https://schema.org',
            'label' => __('Human Readable Name', 'schema-engine'),
            'description' => __('Brief description of what this schema represents', 'schema-engine'),
            'url' => 'https://schema.org/{SchemaType}',
            'icon' => 'icon-name', // From Lucide
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
            // Field definitions here
            // See detailed guide below
        );
    }
}
```

### 2.3 Implementing the `build()` Method

The `build()` method takes field values and returns a schema array.

**Best Practices**:

1. **Use fallback values for common fields**:
   ```php
   $title = !empty($fields['title']) ? $fields['title'] : '{post_title}';
   ```

2. **Handle required vs. optional fields**:
   ```php
   // Required field - always include
   $schema['title'] = $title;

   // Optional field - only include if provided
   if (!empty($fields['optionalField'])) {
       $schema['optionalField'] = $fields['optionalField'];
   }
   ```

3. **Build nested objects**:
   ```php
   $schema['organization'] = array(
       '@type' => 'Organization',
       'name' => $fields['orgName'],
   );
   ```

4. **Handle arrays/repeater fields**:
   ```php
   if (!empty($fields['locations']) && is_array($fields['locations'])) {
       $locations = array();
       foreach ($fields['locations'] as $location) {
           if (!empty($location['name'])) {
               $locations[] = array(
                   '@type' => 'Place',
                   'name' => $location['name'],
               );
           }
       }
       if (!empty($locations)) {
           // Single item = object, multiple = array
           $schema['location'] = count($locations) === 1 ? $locations[0] : $locations;
       }
   }
   ```

5. **Format special values**:
   ```php
   // Boolean
   if (isset($fields['isRemote'])) {
       $schema['isRemote'] = filter_var($fields['isRemote'], FILTER_VALIDATE_BOOLEAN);
   }

   // Dates (should be ISO 8601)
   $schema['datePosted'] = !empty($fields['datePosted']) ? $fields['datePosted'] : '{post_date}';
   ```

### 2.4 Implementing the `get_fields()` Method

Define all fields that will appear in the admin UI.

**Available Field Types**:
- `text` - Single-line text input
- `textarea` - Multi-line text
- `url` - URL input
- `image` - Image uploader
- `date` - Date picker
- `datetime-local` - Date and time picker
- `number` - Numeric input
- `tel` - Phone number input
- `email` - Email input
- `select` - Dropdown selector
- `repeater` - Repeating field groups

**Field Configuration Options**:

```php
array(
    'name' => 'fieldName',              // Required: Field identifier
    'label' => __('Field Label', 'schema-engine'),  // Required: Display label
    'type' => 'text',                   // Required: Field type
    'tooltip' => __('Help text', 'schema-engine'),  // Optional: Tooltip/help
    'placeholder' => 'Example value',   // Optional: Placeholder text
    'default' => '{post_title}',        // Optional: Default value
    'required' => true,                 // Optional: Mark as required
    'rows' => 4,                        // Optional: For textarea
    'options' => array(),               // Required for select
    'fields' => array(),                // Required for repeater
    'condition' => array(               // Optional: Conditional display
        'field' => 'otherField',
        'value' => 'specificValue',
    ),
),
```

**Dynamic Variables**:

WordPress content can be automatically pulled using these variables:
- `{post_title}` - Post title
- `{post_content}` - Post content
- `{post_excerpt}` - Post excerpt
- `{post_date}` - Post published date
- `{post_modified}` - Post modified date
- `{post_url}` - Post permalink
- `{featured_image}` - Featured image URL
- `{author_name}` - Post author name
- `{author_url}` - Post author URL
- `{author_avatar}` - Post author avatar
- `{author_bio}` - Post author bio
- `{site_name}` - Site name
- `{site_url}` - Site URL
- `{site_logo}` - Site logo URL
- `{site_description}` - Site tagline

**Example - Basic Fields**:

```php
array(
    'name' => 'title',
    'label' => __('Title', 'schema-engine'),
    'type' => 'text',
    'tooltip' => __('The main title. Use {post_title} for post title.', 'schema-engine'),
    'placeholder' => '{post_title}',
    'default' => '{post_title}',
    'required' => true,
),
array(
    'name' => 'description',
    'label' => __('Description', 'schema-engine'),
    'type' => 'textarea',
    'rows' => 4,
    'tooltip' => __('Detailed description.', 'schema-engine'),
    'default' => '{post_excerpt}',
),
```

**Example - Select Field**:

```php
array(
    'name' => 'employmentType',
    'label' => __('Employment Type', 'schema-engine'),
    'type' => 'select',
    'tooltip' => __('The type of employment.', 'schema-engine'),
    'options' => array(
        array(
            'label' => __('Full-Time', 'schema-engine'),
            'value' => 'FULL_TIME',
            'description' => __('Full-time position', 'schema-engine'),
        ),
        array(
            'label' => __('Part-Time', 'schema-engine'),
            'value' => 'PART_TIME',
            'description' => __('Part-time position', 'schema-engine'),
        ),
    ),
),
```

**Example - Repeater Field**:

```php
array(
    'name' => 'locations',
    'label' => __('Locations', 'schema-engine'),
    'type' => 'repeater',
    'tooltip' => __('Add multiple locations.', 'schema-engine'),
    'fields' => array(
        array(
            'name' => 'name',
            'label' => __('Location Name', 'schema-engine'),
            'type' => 'text',
            'placeholder' => 'New York Office',
        ),
        array(
            'name' => 'address',
            'label' => __('Address', 'schema-engine'),
            'type' => 'text',
            'placeholder' => '123 Main St',
        ),
    ),
),
```

---

## 🎨 Step 3: Add Icon Support

### 3.1 Choose an Icon

Visit https://lucide.dev/icons and search for an appropriate icon.

**Selection Criteria**:
- ✅ Visually represents the schema type
- ✅ Simple and recognizable at 14-16px
- ✅ Works well in monochrome/single color
- ❌ Avoid overly complex icons

### 3.2 Get the SVG Path

1. Find your icon on https://lucide.dev/icons/{icon-name}
2. Visit the GitHub source: `https://raw.githubusercontent.com/lucide-icons/lucide/main/icons/{icon-name}.svg`
3. Extract the `d` attribute(s) from `<path>` elements
4. Concatenate multiple paths with `M` separator if needed

**Example**:
```xml
<!-- Original SVG -->
<svg>
    <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
    <path d="M2 6h20..."/>
</svg>

<!-- Concatenated for our use -->
'briefcase': 'M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16M2 6h20...'
```

### 3.3 Add Icon to React Component

**File**: `src/components/LucideIcon.js`

Add your icon to the `iconPaths` object:

```javascript
const iconPaths = {
    // ... existing icons
    'your-icon-name': 'M... SVG path here ...',
};
```

### 3.4 Add Icon to PHP Renderer

**File**: `includes/admin/cpt/class-cpt-columns.php`

In the `render_lucide_icon()` method, add to the `$icon_paths` array:

```php
$icon_paths = array(
    // ... existing icons
    'your-icon-name' => 'M... SVG path here ...',
);
```

**Important**: The SVG path must be **identical** in both JavaScript and PHP!

### 3.5 Reference Icon in Schema Structure

In your schema class's `get_schema_structure()` method:

```php
public function get_schema_structure()
{
    return array(
        '@type' => 'YourSchemaType',
        // ... other properties
        'icon' => 'your-icon-name', // Icon name from Lucide
    );
}
```

---

## 📝 Step 4: Registration (Automatic)

**Good News**: Schema types are auto-registered! 🎉

The registration system automatically:
1. Scans `includes/output/types/class-*-schema.php` files
2. Instantiates the class
3. Calls `get_schema_structure()` to get metadata
4. Calls `get_fields()` to get field definitions
5. Registers the type for use throughout the plugin

**No manual registration needed** unless:
- The type should be Pro-only (add to `$pro_schema_types` array in `schema-types-registration.php`)
- The type should be skipped from template selection (add to `$skip_types` array)

---

## ✅ Step 5: Testing Checklist

### 5.1 Validation Testing

**Schema Structure**:
- [ ] Class implements `Schema_Builder_Interface`
- [ ] `build()` method returns valid array
- [ ] `get_schema_structure()` returns complete structure with icon
- [ ] `get_fields()` returns array of field definitions

**Icon Display**:
- [ ] Icon appears in template editor dropdown
- [ ] Icon appears in post metabox schema badges
- [ ] Icon appears in admin template list
- [ ] Icon renders correctly at 14px and 16px

### 5.2 Functional Testing

**Create a Template**:
1. Go to Schema Templates → Add New
2. Select your new schema type from dropdown
3. Verify all fields appear correctly
4. Fill in field values
5. Save template

**Apply to Post**:
1. Create/edit a post that matches template conditions
2. Open post editor
3. Check Schema Engine metabox
4. Verify template appears with icon
5. Override a field value
6. Save post

**Validate Output**:
1. View the post on frontend
2. View page source
3. Find `<script type="application/ld+json">`
4. Copy the JSON-LD
5. Test with:
   - Google Rich Results Test: https://search.google.com/test/rich-results
   - Schema.org validator: https://validator.schema.org/

**Check for**:
- ✅ All required properties present
- ✅ Property values correctly formatted
- ✅ Nested objects properly structured
- ✅ No validation errors
- ✅ Eligible for rich results (if applicable)

### 5.3 Edge Cases

Test these scenarios:
- [ ] Empty/missing optional fields
- [ ] Repeater fields with 0, 1, and multiple items
- [ ] Dynamic variables (`{post_title}`, etc.) resolve correctly
- [ ] Conditional fields show/hide properly
- [ ] Field validation (URLs, dates, numbers) works
- [ ] Special characters in fields are properly escaped

---

## 📚 Step 6: Documentation

### 6.1 Update Icons Documentation

**File**: `.docs/plugin-plan/icons-implementation-guide.md`

Add your icon to the "Current Icon Set" section:

```markdown
- `your-icon-name` - Description of what it represents
```

### 6.2 Add Schema Type Documentation (Optional)

For complex schema types, consider creating a guide:

**File**: `.docs/schema-types/{type}-schema.md`

Include:
- Purpose and use cases
- Required vs. optional fields
- Google rich result requirements
- Common validation issues
- Examples

---

## 🎓 Real-World Example: Job Posting Schema

Let's walk through a complete example.

### Research Phase

**Schema.org**: https://schema.org/JobPosting

**Required Properties**:
- `title` (Text)
- `description` (Text)
- `datePosted` (Date)
- `hiringOrganization` (Organization)
- `jobLocation` (Place)

**Google Requirements**: https://developers.google.com/search/docs/appearance/structured-data/job-posting

**Additional Requirements**:
- `validThrough` (if job has expiration)
- `baseSalary` (recommended for rich results)
- `employmentType` (recommended)
- For 100% remote: `jobLocationType: "TELECOMMUTE"` + `applicantLocationRequirements`

### Icon Selection

Search Lucide for "briefcase" → Found `briefcase` icon

SVG Path: `M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16M2 6h20a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z`

### Implementation

**File Created**: `includes/output/types/class-job-posting-schema.php`

**Key Implementation Details**:

1. **Required fields with fallbacks**:
   ```php
   $title = !empty($fields['title']) ? $fields['title'] : '{post_title}';
   $description = !empty($fields['description']) ? $fields['description'] : '{post_content}';
   ```

2. **Nested organization object**:
   ```php
   $hiring_org = array(
       '@type' => 'Organization',
       'name' => $org_name,
   );
   if (!empty($fields['hiringOrganizationLogo'])) {
       $hiring_org['logo'] = $fields['hiringOrganizationLogo'];
   }
   $schema['hiringOrganization'] = $hiring_org;
   ```

3. **Repeater for multiple locations**:
   ```php
   if (!empty($fields['jobLocations']) && is_array($fields['jobLocations'])) {
       $locations = array();
       foreach ($fields['jobLocations'] as $location) {
           // Build location objects
       }
       // Single location = object, multiple = array
       $schema['jobLocation'] = count($locations) === 1 ? $locations[0] : $locations;
   }
   ```

4. **Conditional requirements** (remote jobs):
   ```php
   if (!empty($fields['jobLocationType'])) {
       $schema['jobLocationType'] = $fields['jobLocationType'];
   }
   if (!empty($fields['applicantLocationRequirements'])) {
       // Build location requirements
   }
   ```

### Testing Results

✅ Schema validates in Google Rich Results Test
✅ All required properties present
✅ Icon displays correctly in all locations
✅ Repeater fields work with multiple entries
✅ Dynamic variables resolve correctly

---

## 🚨 Common Pitfalls & Solutions

### Issue: Icon Not Showing

**Symptoms**: Icon missing in dropdown/badges

**Solutions**:
1. Verify icon name matches in:
   - Schema class `icon` property
   - `LucideIcon.js` iconPaths object
   - `class-cpt-columns.php` icon_paths array
2. Check SVG path is valid and identical in both files
3. Run `yarn build` to compile React changes

### Issue: Fields Not Appearing

**Symptoms**: Fields don't show in template editor

**Solutions**:
1. Verify `get_fields()` returns array
2. Check field `name` property is unique
3. Ensure `type` is a valid field type
4. For conditional fields, verify condition syntax

### Issue: Schema Not Outputting

**Symptoms**: JSON-LD not in page source

**Solutions**:
1. Check template conditions match the post
2. Verify `build()` method returns array
3. Ensure required fields have values
4. Check for PHP errors in debug log

### Issue: Validation Errors

**Symptoms**: Google Rich Results Test shows errors

**Solutions**:
1. Check required properties are present
2. Verify date formats (ISO 8601)
3. Ensure nested objects have `@type`
4. Check enumeration values match spec
5. Validate URLs are absolute and valid

---

## 📚 Reference Materials

### Schema.org Resources
- Main site: https://schema.org/
- Full hierarchy: https://schema.org/docs/full.html
- Pending schemas: https://pending.schema.org/

### Google Resources
- Structured Data Guidelines: https://developers.google.com/search/docs/appearance/structured-data/intro-structured-data
- Rich Results Test: https://search.google.com/test/rich-results
- Schema Markup Validator: https://validator.schema.org/

### Plugin Resources
- AI Development Guide: `.docs/plugin-plan/ai-development-guide.md`
- Icons Guide: `.docs/plugin-plan/icons-implementation-guide.md`
- Existing Schema Types: `includes/output/types/`

### Lucide Icons
- Icon browser: https://lucide.dev/icons
- GitHub repo: https://github.com/lucide-icons/lucide
- Icon license: ISC (free to use)

---

## 🎯 Quick Reference Checklist

Before submitting a new schema type:

- [ ] Created `class-{name}-schema.php` in `includes/output/types/`
- [ ] Implemented `Schema_Builder_Interface`
- [ ] Added `build()` method with required fields
- [ ] Added `get_schema_structure()` with icon
- [ ] Added `get_fields()` with all field definitions
- [ ] Chose appropriate Lucide icon
- [ ] Added icon to `LucideIcon.js`
- [ ] Added icon to `class-cpt-columns.php`
- [ ] Tested in template editor
- [ ] Tested in post metabox
- [ ] Validated JSON-LD output
- [ ] Tested with Google Rich Results Test
- [ ] Updated icons documentation
- [ ] Run `yarn build` to compile React changes

---

**Last Updated**: December 2024
**Maintained By**: Schema Engine Development Team
