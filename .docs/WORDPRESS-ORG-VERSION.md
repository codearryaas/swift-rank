# WordPress.org Submission Version

## Overview
This version has been simplified for WordPress.org submission. The plugin now focuses solely on Knowledge Graph schema (Organization/LocalBusiness/Person) for homepage display.

## Major Changes from Previous Version

### 1. Removed Custom Post Type
- **Removed**: `tp_schema_template` custom post type
- **Reason**: WordPress.org prefers simpler, settings-based plugins for initial submission
- **Impact**: No template system for creating reusable schemas

### 2. Removed Post/Page Metaboxes
- **Removed**: Individual post/page schema metaboxes
- **Reason**: Focused on homepage-only schema output
- **Impact**: Schema only outputs on homepage, not on individual posts/pages

### 3. Simplified Menu Structure
- **Changed**: Moved from top-level admin menu to Settings submenu
- **Location**: Settings → Schema Master
- **Reason**: WordPress.org guidelines recommend Settings submenu for configuration-only plugins

### 4. Streamlined Settings Page
- **Removed**: General tab (auto-schema for posts)
- **Removed**: Help & Variables tab (no longer needed without templates)
- **Kept**: Knowledge Graph settings only
- **Layout**: Direct form display (no tabbed navigation)

### 5. Simplified Schema Output
- **Removed**: Post-specific schema generation
- **Removed**: Template-based schema rendering
- **Removed**: Variable replacement for post content
- **Kept**: Organization/LocalBusiness/Person schema on homepage

### 6. Removed Unused Classes
Deleted files:
- `class-tp-schema-template-cpt.php` - Template custom post type
- `class-tp-schema-template-metabox.php` - Template editor metabox
- `class-tp-schema-metabox.php` - Post/page metabox
- `class-tp-schema-helpers.php` - Variable replacement system
- `class-tp-schema-templates.php` - Schema type definitions

### 7. Remaining Core Files
- `schema-master.php` - Main plugin file (loads admin and output classes)
- `includes/class-tp-schema-admin.php` - Settings page
- `includes/class-tp-schema-output.php` - Schema output (homepage only)
- `assets/css/admin.css` - Admin styling (native WordPress design)
- `assets/js/admin.js` - Admin JavaScript
- `uninstall.php` - Cleanup on uninstall

## Current Features

### Knowledge Graph Schema
Add organization or person information to your homepage for better search engine recognition:

**Organization Types:**
- Organization (generic)
- LocalBusiness (with location info)
- Person (individual/personal site)

**Fields:**
- Basic Info: Name, Logo, Type
- Contact: Phone, Email, Fax, Contact Type
- Address: Street, City, State, Postal Code, Country
- Business Details: Price Range, Opening Hours
- Social Profiles: Multiple social media URLs

### Schema Output
- JSON-LD format in `<head>` section
- Homepage only (not on posts/pages)
- Follows Schema.org standards
- Google Rich Results compatible

## Future Versions (Post-Approval)

After WordPress.org approval, we can add back advanced features:
1. Custom post type for reusable schema templates
2. Post/page metaboxes for individual schemas
3. Variable replacement system (`{post_title}`, `{meta:field}`, etc.)
4. Multiple schema types (Product, Event, Recipe, Review, etc.)
5. Conditional template display (by post type, taxonomy, etc.)
6. Auto-schema generation for posts/pages

## WordPress.org Compliance

### Design
- ✅ Native WordPress admin UI
- ✅ Standard WordPress colors and typography
- ✅ Uses core WordPress form classes
- ✅ Matches WordPress admin patterns

### Code Quality
- ✅ Proper sanitization (sanitize_text_field, esc_url_raw, etc.)
- ✅ Output escaping (esc_html, esc_attr, esc_url)
- ✅ Nonces for form submissions
- ✅ Capability checks (manage_options)
- ✅ Internationalization ready (text domain: schema-master)

### Functionality
- ✅ Settings-based configuration
- ✅ No database tables (uses wp_options)
- ✅ Clean uninstall (removes settings)
- ✅ No external dependencies
- ✅ Focused feature set

### File Structure
```
schema-master/
├── schema-master.php                      # Main plugin file
├── uninstall.php                          # Cleanup script
├── README.md                              # WordPress.org readme
├── CHANGELOG.md                           # Version history
├── includes/
│   ├── class-tp-schema-admin.php          # Settings page
│   └── class-tp-schema-output.php         # Frontend output
├── assets/
│   ├── css/
│   │   └── admin.css                      # Admin styling
│   └── js/
│       └── admin.js                       # Admin scripts
└── languages/                             # Translations
```

## Testing Checklist

- [ ] Plugin activates without errors
- [ ] Settings page accessible under Settings → Schema Master
- [ ] Form fields save correctly
- [ ] Organization schema outputs on homepage
- [ ] Schema validates on Google Rich Results Test
- [ ] Schema validates on Schema.org Validator
- [ ] Plugin deactivates cleanly
- [ ] Uninstall removes all settings
- [ ] No PHP errors/warnings
- [ ] Compatible with latest WordPress version

## Submission Notes

**Plugin Name**: Schema Master  
**Plugin Slug**: schema-master  
**Version**: 1.0.0  
**Requires WordPress**: 5.0+  
**Requires PHP**: 7.0+  
**License**: GPL v2 or later  

**Description**: Add Schema.org structured data to your WordPress site. Supports Organization and LocalBusiness schema with Knowledge Graph integration for better search engine visibility.

**Key Features for WordPress.org Listing**:
- Simple settings-based configuration
- Knowledge Graph support for homepage
- Organization, LocalBusiness, and Person schema types
- JSON-LD output format
- Native WordPress admin design
- No external dependencies
- Clean and lightweight

## Support & Documentation

After approval, create:
1. WordPress.org support forum presence
2. FAQ section in readme.txt
3. Screenshots for plugin listing
4. Installation instructions
5. Usage documentation
6. Schema validation guide
