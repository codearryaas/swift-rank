# Variable System Architecture

## Overview

The Schema Engine plugin uses a **template variable system** that allows users to insert dynamic content into their schema markup. Variables like `{post_title}`, `{author_name}`, and `{site_url}` are replaced with actual values when the schema is output.

This document explains how the variable system works, how it's registered, and how to extend it.

---

## Architecture

### Core Components

1. **[`Schema_Variable_Replacer`](file:///Users/rakeshlawaju/Local%20Sites/skynet/app/public/wp-content/plugins/schema-engine/includes/utils/class-schema-variable-replacer.php)** (Base Plugin)
   - Handles **both registration and replacement** of variables
   - Registers base variable groups (Site, Content, Author)
   - Provides extendable methods for Pro plugin

2. **[`Schema_Variable_Replacer_Pro`](file:///Users/rakeshlawaju/Local%20Sites/skynet/app/public/wp-content/plugins/schema-engine-pro/includes/class-schema-variable-replacer-pro.php)** (Pro Plugin)
   - Extends base class to add Pro-specific variables
   - Adds Categories & Tags, Custom fields, Date/Time groups
   - Registered via `schema_engine_variable_replacer_class` filter

3. **[`Schema_Output_Handler`](file:///Users/rakeshlawaju/Local%20Sites/skynet/app/public/wp-content/plugins/schema-engine/includes/output/class-schema-output-handler.php)**
   - Uses variable replacer to process schema JSON before output
   - Initializes the appropriate replacer class (base or Pro)

---

## How Variables Work

### 1. Registration (PHP)

Variables are registered in the `register_variable_groups()` method:

```php
protected function register_variable_groups()
{
    // Register a group
    $this->register_group('site', array(
        'label' => __('Site', 'schema-engine'),
        'icon' => 'globe',
        'variables' => array(
            array(
                'value' => '{site_name}',
                'label' => __('Site Name', 'schema-engine'),
                'description' => __('Your website name', 'schema-engine'),
            ),
            // ... more variables
        ),
    ));
}
```

**Key Points:**
- Each group has a `label`, `icon`, and array of `variables`
- Each variable has `value`, `label`, and `description`
- Pro plugin extends this method to add more groups

### 2. Localization (PHP → JavaScript)

Variables are passed to JavaScript via `wp_localize_script`:

```php
// In class-cpt-metabox.php
$variable_replacer = new $replacer_class();
$variable_groups = $variable_replacer->get_variable_groups();

wp_localize_script(
    'schema-engine-metabox',
    'schemaEngineMetabox',
    array(
        'variableGroups' => $variable_groups,
        // ... other data
    )
);
```

### 3. Display (JavaScript)

The `VariablesPopup` component reads localized data:

```javascript
const getVariableGroups = () => {
    // Get variable groups from localized PHP data
    const localizedGroups = window.schemaEngineMetabox?.variableGroups || {};
    return localizedGroups;
};
```

### 4. Replacement (PHP)

When schema is output, variables are replaced with actual values:

```php
public function replace_variables($json)
{
    // Get all replacements
    $replacements = $this->get_replacements();
    
    // Replace basic variables
    $json = str_replace(array_keys($replacements), array_values($replacements), $json);
    
    // Replace dynamic variables (option, meta, etc.)
    $json = $this->replace_dynamic_variables($json);
    
    return $json;
}
```

---

## Variable Types

### Basic Variables

Replaced via simple `str_replace()`:

- `{site_name}` → Site name from WordPress settings
- `{post_title}` → Current post title
- `{author_name}` → Post author display name
- etc.

### Dynamic Variables

Replaced via `preg_replace_callback()`:

- `{option:option_name}` → WordPress option value
- `{meta:field_name}` → Post meta value
- `{acf:field_name}` → ACF field value (Pro)
- `{term_meta:key}` → Term meta value (Pro)
- `{user_meta:key}` → User meta value (Pro)

---

## Extending the Variable System

### Adding Variables in Pro Plugin

The Pro plugin extends the base class:

```php
class Schema_Variable_Replacer_Pro extends Schema_Variable_Replacer
{
    protected function register_variable_groups()
    {
        // Register base variables first
        parent::register_variable_groups();
        
        // Add Pro-specific groups
        $this->register_group('taxonomy', array(
            'label' => __('Categories & Tags', 'schema-engine-pro'),
            'icon' => 'category',
            'variables' => array(
                array(
                    'value' => '{categories}',
                    'label' => __('Categories', 'schema-engine-pro'),
                    'description' => __('Comma-separated list', 'schema-engine-pro'),
                ),
            ),
        ));
    }
    
    protected function get_post_replacements($post)
    {
        // Get base replacements
        $replacements = parent::get_post_replacements($post);
        
        // Add Pro-specific replacements
        $categories = get_the_category($post->ID);
        $replacements['{categories}'] = !empty($categories) 
            ? implode(', ', wp_list_pluck($categories, 'name')) 
            : '';
        
        return $replacements;
    }
}
```

### Registering Custom Replacer

The Pro plugin registers its custom replacer class:

```php
function schema_engine_pro_register_variable_replacer($class_name)
{
    return 'Schema_Variable_Replacer_Pro';
}
add_filter('schema_engine_variable_replacer_class', 'schema_engine_pro_register_variable_replacer');
```

---

## Available Filters

### `schema_engine_variable_replacer_class`

Override the variable replacer class:

```php
add_filter('schema_engine_variable_replacer_class', function($class) {
    return 'My_Custom_Variable_Replacer';
});
```

### `schema_engine_variable_groups`

Modify registered variable groups:

```php
add_filter('schema_engine_variable_groups', function($groups) {
    $groups['custom'] = array(
        'label' => 'Custom Group',
        'icon' => 'admin-generic',
        'variables' => array(/* ... */),
    );
    return $groups;
});
```

### `schema_engine_variable_replacements`

Modify variable replacement values:

```php
add_filter('schema_engine_variable_replacements', function($replacements, $post) {
    $replacements['{custom_var}'] = 'Custom Value';
    return $replacements;
}, 10, 2);
```

### `schema_engine_replace_variables`

Modify JSON after variable replacement:

```php
add_filter('schema_engine_replace_variables', function($json) {
    // Perform additional replacements
    return $json;
});
```

### `schema_engine_replace_dynamic_variables`

Modify JSON after dynamic variable replacement:

```php
add_filter('schema_engine_replace_dynamic_variables', function($json, $post) {
    // Add custom dynamic variable patterns
    return $json;
}, 10, 2);
```

---

## File Structure

```
schema-engine/
├── includes/
│   ├── utils/
│   │   └── class-schema-variable-replacer.php    # Base replacer class
│   ├── output/
│   │   └── class-schema-output-handler.php       # Uses replacer
│   └── admin/
│       └── cpt/
│           └── class-cpt-metabox.php             # Localizes variables
└── src/
    └── components/
        └── VariablesPopup.js                     # Displays variables

schema-engine-pro/
├── includes/
│   └── class-schema-variable-replacer-pro.php    # Pro extension
└── schema-engine-pro.php                         # Loads Pro replacer
```

---

## Best Practices

### 1. Always Extend, Never Modify

✅ **Good:** Extend the base class
```php
class My_Replacer extends Schema_Variable_Replacer {
    protected function register_variable_groups() {
        parent::register_variable_groups();
        // Add your groups
    }
}
```

❌ **Bad:** Modify base class directly

### 2. Use Filters for Simple Extensions

For adding a few variables, use filters instead of creating a new class:

```php
add_filter('schema_engine_variable_replacements', function($replacements) {
    $replacements['{my_var}'] = 'My Value';
    return $replacements;
});
```

### 3. Namespace Your Variables

Use prefixes to avoid conflicts:

```php
'{myplugin_custom_field}' // Good
'{custom_field}'          // Could conflict
```

### 4. Provide Clear Descriptions

Users see these in the UI:

```php
'description' => __('Comma-separated list of post categories', 'textdomain')
```

### 5. Handle Missing Data Gracefully

Always provide fallbacks:

```php
$replacements['{categories}'] = !empty($categories) 
    ? implode(', ', wp_list_pluck($categories, 'name')) 
    : ''; // Empty string if no categories
```

---

## Migration Notes

### Before (Hardcoded in JavaScript)

Variables were defined in both PHP and JavaScript:

- PHP: For replacement during output
- JavaScript: For displaying in variable picker

This led to duplication and maintenance issues.

### After (Centralized in PHP)

Variables are now:

1. **Registered** in PHP (`register_variable_groups()`)
2. **Localized** to JavaScript (`wp_localize_script()`)
3. **Displayed** from localized data (`window.schemaEngineMetabox.variableGroups`)
4. **Replaced** during output (`replace_variables()`)

**Benefits:**
- Single source of truth
- No duplication
- Easier to maintain
- Pro variables automatically included
- Fully extensible

---

## Troubleshooting

### Variables Not Showing in Picker

1. Check if variables are registered in `register_variable_groups()`
2. Verify localization in `class-cpt-metabox.php`
3. Check browser console for `window.schemaEngineMetabox.variableGroups`

### Variables Not Being Replaced

1. Check if replacement is defined in `get_replacements()`
2. For dynamic variables, check `replace_dynamic_variables()`
3. Verify the variable syntax matches exactly (case-sensitive)

### Pro Variables Not Available

1. Ensure Pro plugin is active
2. Check if `Schema_Variable_Replacer_Pro` is loaded
3. Verify filter is registered: `schema_engine_variable_replacer_class`

---

## Related Documentation

- [Schema Type Registration](file:///Users/rakeshlawaju/Local%20Sites/skynet/app/public/wp-content/plugins/schema-engine/.docs/plugin-plan/schema-type-registration.md)
- [Output Handler](file:///Users/rakeshlawaju/Local%20Sites/skynet/app/public/wp-content/plugins/schema-engine/.docs/plugin-plan/output-handler.md)
