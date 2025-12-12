# Schema Output Module

This directory contains the modular schema output system for Schema Engine.

## Structure

```
output/
├── class-schema-output-handler.php    # Main output handler class
├── types/                              # Individual schema type builders
│   ├── interface-schema-builder.php   # Schema builder interface
│   ├── class-article-schema.php       # Article/BlogPosting/NewsArticle
│   ├── class-organization-schema.php  # Organization/LocalBusiness
│   ├── class-person-schema.php        # Person
│   ├── class-product-schema.php       # Product
│   ├── class-event-schema.php         # Event
│   ├── class-local-business-schema.php # LocalBusiness
│   ├── class-faq-schema.php           # FAQPage
│   ├── class-howto-schema.php         # HowTo
│   ├── class-breadcrumb-schema.php    # BreadcrumbList
│   └── class-website-schema.php       # WebSite with SearchAction
└── README.md                           # This file
```

## How It Works

### Main Output Handler
The `Schema_Output_Handler` class coordinates all schema output to the frontend:
- Manages hook initialization (wp_head or wp_footer)
- Handles schema template matching and conditions
- Processes variable replacements
- Outputs JSON-LD markup
- Maintains backward compatibility

### Schema Builders
Each schema type has its own builder class that implements `Schema_Builder_Interface`:
- Encapsulates schema-specific logic
- Returns schema array (without @context)
- Can be easily extended or replaced

### Registration System
Schema builders are registered in the handler's constructor:
```php
$this->schema_builders = array(
    'Article' => new Schema_Article(),
    'Person' => new Schema_Person(),
    // ... etc
);
```

### Pro Plugin Integration
The Pro plugin can register additional builders:
```php
$output_handler = Schema_Output_Handler::get_instance();
$output_handler->register_builder('Recipe', new Schema_Recipe());
```

## Backward Compatibility

The original `class-schema-engine-output.php` has been converted to a compatibility wrapper that delegates to the new modular system. This ensures existing code continues to work without changes.

## Adding New Schema Types

### 1. Create a new builder class
Create a new file in `types/`:
```php
<?php
class Schema_MyType implements Schema_Builder_Interface
{
    public function build($fields)
    {
        return array(
            '@type' => 'MyType',
            // ... schema properties
        );
    }
}
```

### 2. Register the builder
In the main handler or via a filter:
```php
$output_handler = Schema_Output_Handler::get_instance();
$output_handler->register_builder('MyType', new Schema_MyType());
```

### 3. Use the filter hook
Alternatively, use the existing filter:
```php
add_filter('schema_engine_build_schema', function($schema, $type, $fields) {
    if ($type === 'MyType') {
        return array_merge($schema, array(
            '@type' => 'MyType',
            // ... schema properties
        ));
    }
    return $schema;
}, 10, 3);
```

## Benefits

1. **Separation of Concerns**: Each schema type has its own dedicated class
2. **Maintainability**: Easy to find and update schema-specific code
3. **Extensibility**: Simple to add new schema types or override existing ones
4. **Testability**: Individual builders can be tested in isolation
5. **Organization**: Clear structure makes the codebase easier to navigate
6. **Pro Integration**: Clean separation between free and Pro features

## Migration Notes

- Original file backed up as `class-schema-engine-output.php.bak`
- All existing functionality maintained
- No changes required to other parts of the plugin
- Pro plugin updated to use new builder system
