# Schema Engine - AI Development Guide

> **Purpose**: This document provides essential context for AI assistants working on Schema Engine plugin development and feature planning.

## 📋 Quick Reference

**Plugin Name**: Schema Engine  
**Type**: WordPress Plugin (Free + Pro)  
**Purpose**: Schema.org structured data (JSON-LD) management  
**Tech Stack**: PHP 7.4+, WordPress 5.0+, React, SCSS, Webpack  
**Repository Structure**: `/schema-engine` (free) + `/schema-engine-pro` (premium)

---

## 🏗️ Architecture Overview

### Plugin Structure

```
schema-engine/
├── schema-engine.php          # Main plugin file
├── includes/
│   ├── admin/                 # Admin UI (PHP-based settings, metaboxes)
│   ├── blocks/                # Gutenberg blocks (FAQ, HowTo)
│   ├── output/                # Schema output handlers
│   │   ├── types/             # Schema builders (Article, FAQ, etc.)
│   │   └── class-schema-output-handler.php
│   ├── class-schema-engine-admin.php
│   └── schema-types-registration.php
├── src/                       # React components (built with Webpack)
│   ├── admin-settings/        # Settings page React app
│   ├── post-metabox/          # Post editor metabox
│   ├── template-metabox/      # Template editor metabox
│   ├── components/            # Shared React components
│   └── blocks/                # Block editor components
├── .docs/                     # Documentation
└── release/                   # Build output

schema-engine-pro/
├── schema-engine-pro.php
├── includes/
│   ├── output/types/          # Pro schema types
│   └── class-*-pro.php        # Pro extensions
└── assets/
```

### Key Design Patterns

#### 1. Schema Builder Pattern
Each schema type implements `Schema_Builder_Interface`:
```php
interface Schema_Builder_Interface {
    public function build($fields);           // Generate JSON-LD
    public function get_schema_structure();   // Define structure
    public function get_fields();             # Define UI fields
}
```

**Location**: `includes/output/types/class-*-schema.php`

#### 2. Extensibility via Hooks
```php
// Field extensibility
apply_filters('schema_engine_get_fields', $fields, $type);

// Schema output extensibility  
apply_filters('schema_engine_output_schema', $schema, $fields, $type);
```

**Pro plugins hook into these to extend functionality**

#### 3. React Component Architecture
- Settings: Tabbed interface (`SettingsApp.js`)
- Metaboxes: Field-based editors
- Components: Reusable UI elements (Select, Input, Repeater)
- Global exposure: `window.schemaEngineComponents`

#### 4. Template System
- Custom Post Type: `schema_template`
- Conditional matching: Post types, taxonomies, specific posts
- Field overrides: Template defaults → Post-specific values
- Variable replacement: `{post_title}`, `{meta:field}`, etc.

---

## 🎯 Core Concepts

### Schema Type Registration Flow

1. **Define Builder Class**
   ```php
   class Schema_Article implements Schema_Builder_Interface {
       public function build($fields) { /* ... */ }
       public function get_schema_structure() { /* ... */ }
       public function get_fields() { /* ... */ }
   }
   ```

2. **Register in Output Handler**
   ```php
   // includes/output/class-schema-output-handler.php
   $this->builders['Article'] = new Schema_Article();
   ```

3. **Add to Registry**
   ```php
   // includes/schema-types-registration.php
   $schema_types[] = [
       'value' => 'Article',
       'label' => 'Article',
       'description' => '...'
   ];
   ```

### Free vs Pro Split

**Free Plugin** (`schema-engine`):
- Core functionality
- Common schema types (Article, FAQ, Organization, Person, Product, Video)
- Basic field definitions
- Template system
- Import/Export

**Pro Plugin** (`schema-engine-pro`):
- Additional schema types (Event, Recipe, HowTo, Podcast, etc.)
- Enhanced fields for free types
- Advanced features
- Hooks into free plugin's filters

**Pro Extension Pattern**:
```php
class Schema_Video_Pro {
    public function __construct() {
        add_filter('schema_engine_get_fields', [$this, 'add_pro_fields'], 10, 2);
        add_filter('schema_engine_output_schema', [$this, 'add_pro_data'], 10, 3);
    }
}
```

---

## 🛠️ Development Workflow

### Adding a New Schema Type

**Template to Follow**: Use `class-video-schema.php` or `class-article-schema.php` as reference

**Steps**:

1. **Create Builder Class**
   ```php
   // includes/output/types/class-{name}-schema.php
   class Schema_{Name} implements Schema_Builder_Interface {
       public function build($fields) {
           $schema = [
               '@context' => 'https://schema.org',
               '@type' => '{SchemaType}',
               // ... properties
           ];
           return $schema;
       }
       
       public function get_schema_structure() {
           return [
               'type' => '{SchemaType}',
               'label' => __('Human Name', 'schema-engine'),
               'description' => __('Description', 'schema-engine')
           ];
       }
       
       public function get_fields() {
           return [
               'field_name' => [
                   'type' => 'text',
                   'label' => __('Field Label', 'schema-engine'),
                   'required' => true
               ],
               // ... more fields
           ];
       }
   }
   ```

2. **Register Builder**
   ```php
   // includes/output/class-schema-output-handler.php
   require_once SCHEMA_ENGINE_PATH . 'includes/output/types/class-{name}-schema.php';
   $this->builders['{SchemaType}'] = new Schema_{Name}();
   ```

3. **Add to Type Registry**
   ```php
   // includes/schema-types-registration.php
   // Add to $free_schema_types or let it auto-register from builder
   ```

4. **Build Assets**
   ```bash
   cd schema-engine
   yarn start  # Development mode
   # or
   yarn build  # Production build
   ```

### Field Types Reference

```javascript
// Available field types in React metabox
{
    type: 'text'        // Text input
    type: 'textarea'    // Textarea
    type: 'url'         // URL input
    type: 'date'        // Date picker
    type: 'image'       // Image upload
    type: 'select'      // Dropdown
    type: 'repeater'    // Repeating fields
}
```

### Styling Guidelines

- **Admin UI**: WordPress native styles + custom SCSS
- **Components**: SCSS modules in `/src/components/style.scss`
- **Color Scheme**: 
  - Pro gold gradient: `linear-gradient(135deg, #f0b849 0%, #d99c00 100%)`
  - WordPress blue: `#2271b1`
  - Borders: `#c3c4c7`

---

## 🎨 UI/UX Patterns

### Settings Page Structure
```javascript
// Tab-based navigation
- General (site-wide settings)
- Breadcrumb (Pro)
- Import/Export
- Help
- Marketplace
- Upgrade (if not Pro)
```

### Metabox Patterns

**Template Metabox**: Schema type selector + conditional display rules  
**Post Metabox**: Dynamic fields based on active templates

### Component Reusability

**Global Components** (`window.schemaEngineComponents`):
```javascript
import { Select, Input, Repeater } from '../components';
```

---

## 🔌 Integration Points

### WordPress Hooks Used

**Actions**:
- `admin_enqueue_scripts` - Load admin assets
- `wp_head` - Output schema JSON-LD
- `save_post` - Save metabox data

**Filters**:
- `schema_engine_get_fields` - Modify field definitions
- `schema_engine_output_schema` - Modify schema output
- `schema_engine_settings_tabs` - Add custom tabs

### External Integrations

**Current**:
- WordPress core (posts, pages, custom post types)
- Gutenberg blocks (FAQ, HowTo)

**Planned**:
- WooCommerce (product schema)
- Google Search Console API (validation)
- EDD/WP Simple Pay (licensing)

---

## 📊 Feature Planning Guidelines

### When Adding New Features

**Ask These Questions**:

1. **User Value**: Does this solve a real pain point?
2. **Competitive Gap**: Do competitors have this?
3. **Free vs Pro**: Where should this live?
   - Core functionality → Free
   - Advanced/niche → Pro
   - Revenue driver (WooCommerce, bulk tools) → Pro
4. **Implementation Complexity**: Low/Medium/High?
5. **Maintenance Burden**: Ongoing API dependencies?
6. **Schema.org Compliance**: Is this valid schema markup?

### Free vs Pro Decision Matrix

| Feature Type | Free | Pro |
|-------------|------|-----|
| Common schema types (Article, FAQ, etc.) | ✅ | - |
| Niche schema types (Job, Course, etc.) | ❌ | ✅ |
| Basic field mapping | ✅ | - |
| Custom schema builder | ❌ | ✅ |
| WooCommerce integration | ❌ | ✅ |
| Bulk operations | ❌ | ✅ |
| Priority support | ❌ | ✅ |
| Advanced analytics | ❌ | ✅ |

### Feature Prioritization Framework

**P0 (Critical)**:
- Bug fixes
- Security issues
- Core functionality breaks

**P1 (High)**:
- Competitive gaps (validator, review schema)
- High-demand features (WooCommerce)
- Trust builders (validation tools)

**P2 (Medium)**:
- Nice-to-haves (more schema types)
- UX improvements
- Performance optimizations

**P3 (Low)**:
- Future innovations (AI features)
- Experimental features

---

## 🔍 Common Development Scenarios

### Scenario 1: Adding a Pro Feature to Existing Free Type

**Example**: Add "clips" field to Video schema (Pro)

```php
// schema-engine-pro/includes/class-video-schema-pro.php
class Schema_Video_Pro {
    public function add_pro_fields($fields, $type) {
        if ($type !== 'VideoObject') return $fields;
        
        $fields['clips'] = [
            'type' => 'repeater',
            'label' => __('Clips', 'schema-engine'),
            'fields' => [ /* ... */ ]
        ];
        
        return $fields;
    }
}
```

### Scenario 2: Creating a Gutenberg Block

**Example**: Review block with star rating

```javascript
// src/blocks/review/edit.js
import { RichText, InspectorControls } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
    return (
        <>
            <InspectorControls>
                {/* Rating selector */}
            </InspectorControls>
            <div className="review-block">
                {/* Review content */}
            </div>
        </>
    );
}
```

### Scenario 3: Adding Settings Tab

```javascript
// src/admin-settings/components/NewTab.js
const NewTab = ({ settings, updateSetting }) => {
    return <div>Tab content</div>;
};

// Add to SettingsApp.js
const defaultTabs = [
    // ...
    { name: 'newtab', title: 'New Tab', component: NewTab }
];
```

---

## 🚨 Important Considerations

### Schema Markup Best Practices

1. **Use JSON-LD**: Always output JSON-LD format
2. **Validate Everything**: Schema must pass Google's validator
3. **Match Page Content**: Schema should reflect actual page content
4. **Required Properties**: Check schema.org for required fields
5. **Avoid Spam**: Don't add hidden/misleading schema

### WordPress Compatibility

- **Minimum PHP**: 7.4+
- **Minimum WordPress**: 5.0+
- **Tested Up To**: Keep updated
- **Multisite**: Consider multisite compatibility
- **Translation Ready**: Use `__()`, `_e()` for all strings

### Performance Considerations

- **Lazy Load**: Only load scripts on relevant pages
- **Minimize DB Queries**: Cache when possible
- **Asset Optimization**: Minify CSS/JS for production
- **Schema Caching**: Consider caching generated schema

---

## 📚 Reference Documents

**In This Repo**:
- `/README.md` - Plugin overview
- `/.docs/feature-recommendations.md` - Competitive analysis & roadmap
- `/includes/output/README.md` - Output system docs

**External**:
- [Schema.org](https://schema.org/) - Schema types reference
- [Google Search Central](https://developers.google.com/search/docs/appearance/structured-data) - Google's requirements
- [Rich Results Test](https://search.google.com/test/rich-results) - Validation tool

---

## 🎯 Quick Commands

```bash
# Development
cd schema-engine
yarn install
yarn start          # Watch mode
yarn build          # Production build

# Testing
# Test schema output
curl -s http://localhost/sample-post/ | grep -A 50 'application/ld+json'

# Lint checking (if configured)
yarn lint
```

---

## 💡 Tips for AI Assistants

### When Implementing Features

1. **Check Existing Patterns**: Look at similar features first
2. **Maintain Consistency**: Follow established naming conventions
3. **Use Filters**: Make everything extensible via WordPress hooks
4. **Document Intent**: Add clear comments for complex logic
5. **Test Schema**: Always validate with Google's tool
6. **Think Free vs Pro**: Consider monetization strategy

### Code Style

- **PHP**: WordPress coding standards
- **JavaScript**: ES6+, React hooks
- **Naming**: 
  - Classes: `Schema_{Type}`, `Schema_{Type}_Pro`
  - Functions: `snake_case`
  - React: `PascalCase` components, `camelCase` variables
- **Documentation**: PHPDoc for classes/methods

### When Stuck

**Common Issues**:
- Schema not appearing → Check builder registration
- Fields not showing → Verify `get_fields()` return
- React not updating → Check `yarn start` is running
- Pro feature not working → Verify Pro plugin is active

**Debug Tools**:
- View page source for JSON-LD output
- WordPress Debug mode: `WP_DEBUG` in wp-config.php
- React DevTools for component debugging
- Console logs in browser DevTools

---

## 🎓 Learning Resources

**For Schema Markup**:
- Schema.org documentation
- Google's Structured Data Guidelines
- Schema Pro (competitor) - for inspiration

**For WordPress Development**:
- WordPress Plugin Handbook
- WordPress Coding Standards
- React in WordPress (Gutenberg docs)

---

## ✅ Checklist for New Features

Before implementing, confirm:

- [ ] Feature aligns with plugin goals
- [ ] Determined if Free or Pro
- [ ] Checked competitive landscape
- [ ] Reviewed Schema.org requirements
- [ ] Considered backward compatibility
- [ ] Planned for extensibility (hooks)
- [ ] Documented user-facing changes
- [ ] Created/updated tests
- [ ] Validated schema output
- [ ] Updated version numbers
- [ ] Added to changelog

---

**Last Updated**: December 2024  
**Maintained By**: Schema Engine Development Team
