# Schema Engine - Implementation Log

## Latest Updates

### FAQ & HowTo Blocks + Menu Reorganization (2025-11-29)

Major updates including new Gutenberg blocks with schema support, menu reorganization, and webpack build improvements.

#### New Features

1. **FAQ Block with FAQPage Schema**
   - Gutenberg block for adding Frequently Asked Questions
   - Automatic FAQPage schema markup generation
   - Multiple FAQ items with question/answer pairs
   - Toggle to enable/disable schema output
   - Rich text editing support

2. **HowTo Block with HowTo Schema**
   - Gutenberg block for step-by-step instructions
   - Automatic HowTo schema markup generation
   - Step-by-step editor with images
   - Total time field (ISO 8601 duration format)
   - Rich text editing for title, description, and steps

3. **Menu Reorganization**
   - Created new top-level "Schema Engine" menu
   - Moved settings from WordPress Settings menu to Schema Engine menu
   - Schema Templates now appear under Schema Engine menu
   - Schema Validator moved under Schema Engine menu

4. **Webpack Build Structure**
   - Updated to use separate build folders for each entry
   - `build/metabox/index.js` - Template metabox
   - `build/post-metabox/index.js` - Post metabox
   - `build/blocks/faq/index.js` - FAQ block
   - `build/blocks/howto/index.js` - HowTo block

#### Files Added

**FAQ Block:**
- `src/blocks/faq/index.js`
- `src/blocks/faq/block.json`
- `src/blocks/faq/edit.js`
- `src/blocks/faq/save.js`
- `src/blocks/faq/editor.scss`

**HowTo Block:**
- `src/blocks/howto/index.js`
- `src/blocks/howto/block.json`
- `src/blocks/howto/edit.js`
- `src/blocks/howto/save.js`
- `src/blocks/howto/editor.scss`

**Block Registration:**
- `includes/class-schema-engine-blocks.php`

#### Files Modified

- `includes/class-schema-engine-admin.php` - Updated menu structure
- `includes/class-schema-engine-cpt.php` - Changed to show under Schema Engine menu, updated build paths
- `includes/class-schema-engine-post-metabox.php` - Updated build paths
- `schema-engine.php` - Added blocks class initialization
- `webpack.config.js` - Updated for separate build folders

---

### Settings Page with Tabs (2025-11-29)

Added a tabbed settings interface to Schema Engine with General settings for schema output configuration.

#### New Settings Page Structure

The settings page now includes three tabs:

1. **General Tab** (Default)
   - Schema Code Placement dropdown
   - Default Schema Image upload field

2. **Knowledge Graph Tab**
   - All existing Knowledge Graph settings (Organization, LocalBusiness, Person)
   - Social profiles, contact information, opening hours, etc.

3. **Help Tab**
   - General Settings documentation
   - Knowledge Graph Settings documentation
   - Available Variables reference
   - Testing & Validation guides

#### New Settings Added

**Schema Code Placement**
- Field Type: Dropdown select
- Options:
  - Head (recommended) - Outputs in `<head>` section
  - Footer - Outputs before `</body>` tag
- Default: head
- Storage: `schema_engine_settings['code_placement']`

**Default Schema Image**
- Field Type: URL input with media library upload button
- Features:
  - Media library integration for easy image selection
  - Image preview after selection
  - URL input field for manual entry
  - Comprehensive help text with image requirements
- Storage: `schema_engine_settings['default_image']`
- Recommended Specs:
  - Minimum: 1200 x 675 pixels (16:9 ratio)
  - Formats: JPG, PNG, WebP

#### Files Modified
- `includes/class-schema-engine-admin.php` - Added new settings, tabs, and help content

---

## Schema Templates Implementation

### Overview
This implementation adds a flexible schema template system with a React-based metabox editor to the Schema Engine plugin.

## Features Implemented

### 1. Custom Post Type: `sm_template`
- **Location**: [includes/class-schema-engine-cpt.php](includes/class-schema-engine-cpt.php)
- Registers a custom post type for creating schema templates
- Hidden from public view, accessible only in admin
- Menu appears in WordPress admin with code icon

### 2. React Metabox with @wordpress/scripts
- **Location**: [src/metabox/](src/metabox/)
- Built using React and WordPress components
- Hot module reloading during development with `npm run start`
- Production build with `npm run build`

### 3. Schema Type Selector
- **Component**: [src/metabox/components/SchemaTypeSelector.js](src/metabox/components/SchemaTypeSelector.js)
- Supports 3 schema types:
  - Article
  - Organization
  - Person

### 4. Dynamic Schema Fields

#### Article Fields
- **Component**: [src/metabox/components/ArticleFields.js](src/metabox/components/ArticleFields.js)
- Fields:
  - Headline
  - Description
  - Author Name
  - Image URL
  - Publisher Name
  - Publisher Logo URL
  - Date Published
  - Date Modified
  - Article Type (Article, NewsArticle, BlogPosting, ScholarlyArticle, TechArticle)

#### Organization Fields
- **Component**: [src/metabox/components/OrganizationFields.js](src/metabox/components/OrganizationFields.js)
- Fields:
  - Name
  - URL
  - Description
  - Logo URL
  - Phone
  - Email
  - Address (Street, City, State, Postal Code, Country)
  - Organization Type

#### Person Fields
- **Component**: [src/metabox/components/PersonFields.js](src/metabox/components/PersonFields.js)
- Fields:
  - Name
  - URL
  - Description
  - Image URL
  - Job Title
  - Email
  - Phone
  - Works For (Organization)
  - Gender
  - Birth Date
  - Nationality

### 5. Display Conditions
- **Component**: [src/metabox/components/ConditionsPanel.js](src/metabox/components/ConditionsPanel.js)

#### Default Behavior
- Schema templates are included on the whole site by default
- Toggle control to change this behavior

#### Include Conditions
- Front Page
- Home Page (Blog Index)
- Specific Post Types (multi-select)

#### Exclude Conditions
- Front Page
- Home Page (Blog Index)
- Specific Post Types (multi-select)

### 6. Frontend Schema Output
- **Location**: [includes/class-schema-engine-output.php](includes/class-schema-engine-output.php)
- Automatically outputs schema templates based on conditions
- Filters published templates
- Checks exclusion conditions first
- Applies inclusion logic based on default behavior setting
- Builds appropriate schema based on type
- Supports variable replacement

### 7. Variable System
All schema fields support dynamic variables:

#### Site Variables
- `{site_name}` - WordPress site name
- `{site_url}` - Site home URL
- `{site_description}` - Site tagline

#### Post Variables
- `{post_title}` - Post title
- `{post_excerpt}` - Post excerpt
- `{post_date}` - Post publication date
- `{post_modified}` - Post modified date
- `{featured_image}` - Featured image URL

#### Author Variables
- `{author_name}` - Author display name
- `{author_url}` - Author posts URL
- `{author_bio}` - Author biography
- `{author_email}` - Author email
- `{author_avatar}` - Author avatar URL

#### Custom Variables
- `{option:option_name}` - Any WordPress option
- `{meta:meta_key}` - Any post meta field

### 8. Admin Bar Schema Validator
- **Location**: [includes/class-schema-engine-output.php](includes/class-schema-engine-output.php#L787)
- Appears in admin bar for logged-in admins
- Quick links to:
  - Google Rich Results Test
  - Schema.org Validator
- Automatically includes current page URL

## File Structure

```
schema-engine/
├── includes/
│   ├── class-schema-engine-admin.php     # Existing admin settings
│   ├── class-schema-engine-output.php    # Schema output + templates
│   └── class-schema-engine-cpt.php       # NEW: Custom post type
├── src/
│   └── metabox/                          # NEW: React metabox
│       ├── index.js
│       ├── SchemaMetabox.js
│       ├── style.scss
│       └── components/
│           ├── SchemaTypeSelector.js
│           ├── ArticleFields.js
│           ├── OrganizationFields.js
│           ├── PersonFields.js
│           └── ConditionsPanel.js
├── build/                                # NEW: Built assets
│   ├── metabox.js
│   ├── metabox.asset.php
│   └── style-metabox.css
├── package.json                          # Updated with @wordpress/scripts
├── webpack.config.js                     # NEW: Webpack config
└── schema-engine.php                     # Updated to load CPT class
```

## Development

### Install Dependencies
```bash
npm install
```

### Development Mode (Hot Reload)
```bash
npm run start
```

### Production Build
```bash
npm run build
```

### Grunt Build (Release)
```bash
npm run build:grunt
```

## Usage

### Creating a Schema Template

1. Go to **Schema Templates > Add New** in WordPress admin
2. Give your template a title (e.g., "Blog Post Article Schema")
3. Select a **Schema Type** (Article, Organization, or Person)
4. Fill in the schema fields
   - Use variables like `{post_title}`, `{author_name}`, etc. for dynamic content
5. Configure **Display Conditions**:
   - Toggle "Include on whole site by default" as needed
   - Add include conditions (e.g., Post Type: post)
   - Add exclude conditions if needed
6. **Publish** the template

### Example: Article Schema for Blog Posts

**Title**: Blog Post Article Schema

**Schema Type**: Article

**Fields**:
- Headline: `{post_title}`
- Description: `{post_excerpt}`
- Author Name: `{author_name}`
- Image URL: `{featured_image}`
- Publisher Name: `{site_name}`
- Publisher Logo URL: `https://example.com/logo.png`
- Date Published: `{post_date}`
- Date Modified: `{post_modified}`
- Article Type: BlogPosting

**Display Conditions**:
- Include on whole site: OFF
- Include Conditions: Post Type = post

### Validating Schema

1. Visit any page on your site (while logged in as admin)
2. Click **Schema** in the admin bar
3. Select:
   - **Google Rich Results Test** - to test with Google
   - **Schema.org Validator** - to validate against standards

## Notes

- Schema templates are only output when published
- Exclusion conditions take priority over inclusion conditions
- The "Include on whole site by default" toggle controls the base behavior
- All schema output is properly formatted as JSON-LD
- Empty fields are automatically removed from output
- Variables are replaced at runtime for each page
