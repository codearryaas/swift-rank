# Schema Engine - Icons Implementation Guide

> **Purpose**: This document provides guidance for AI assistants when working with icons in the Schema Engine plugin.

## 📋 Overview

Schema Engine uses **WordPress Icons** (@wordpress/icons) for displaying visual indicators next to schema types throughout the admin interface. Icons improve user experience by providing quick visual identification of schema types.

---

## 🎨 Icon System Architecture

### Icon Source
- **Icon Library**: WordPress Icons (https://wordpress.github.io/gutenberg/?path=/story/icons-icon--library)
- **License**: GPL-2.0-or-later (same as WordPress)
- **Package**: `@wordpress/icons` (npm package, included with @wordpress/components)
- **Approach**: React components with tree-shaking support
- **Size**: Responsive (defaults to 24px, can be customized)

### Icon Storage Structure

Icons are defined in schema type classes within the `get_schema_structure()` method:

```php
public function get_schema_structure()
{
    return array(
        '@type' => 'Article',
        '@context' => 'https://schema.org',
        'label' => __('Article', 'schema-engine'),
        'description' => __('An article, such as a news article or piece of investigative report.', 'schema-engine'),
        'url' => 'https://schema.org/Article',
        'icon' => 'file-text', // Icon identifier
        'subtypes' => array(
            // ... subtypes
        ),
    );
}
```

---

## 🔧 Implementation Details

### 1. PHP Implementation (Backend)

**File**: `includes/admin/cpt/class-cpt-columns.php`

Icons are rendered in the template listing using inline SVG. This maintains compatibility with the existing PHP-based column rendering.

### 2. React Implementation (Frontend)

**Component**: `src/components/Icon.js`

The component uses the official `@wordpress/icons` npm package.

```javascript
import Icon from '../components/Icon';

// Usage
<Icon
    name="file-text"
    size={24}
/>
```

**Props**:
- `name` (string, required): Icon identifier (e.g., 'file-text', 'video')
- `size` (number, default: 24): Icon size in pixels
- `className` (string, optional): Additional CSS classes
- `style` (object, optional): Inline styles

---

## 📦 Icon Definitions by Schema Type

### Free Schema Types

| Schema Type | Icon | WordPress Icon | Rationale |
|------------|------|----------------|-----------|
| Article | 📄 | `page` (as fileText) | Represents text document/article content |
| FAQ Page | ❓ | `help` (as helpCircle) | Question mark symbolizes FAQ/help |
| Organization | 🏢 | `building` (as building2) | Building represents organization/business |
| Person | 👤 | `people` (as user) | User icon for person schema |
| Product | 🛍️ | `bag` (as shoppingBag) | Shopping bag for products |
| Video | 🎥 | `video` | Video camera/player symbol |

### Pro Schema Types

| Schema Type | Icon | WordPress Icon | Rationale |
|------------|------|----------------|-----------|
| Recipe | 👨‍🍳 | `category` (as chefHat) | Category/grid icon for recipes |
| Podcast | 🎙️ | `audio` (as podcast) | Audio icon for podcasts |
| Event | 📅 | `calendar` | Calendar for events/dates |
| HowTo | ✅ | `check` (as listChecks) | Check mark for step completion |
| JobPosting | 💼 | `archive` (as briefcase) | Briefcase for job postings |

---

## 🛠️ Adding a New Icon

### Step 1: Choose an Icon from WordPress Icons

1. Visit https://wordpress.github.io/gutenberg/?path=/story/icons-icon--library
2. Browse available icons and note the icon name
3. Choose an appropriate icon for your schema type

### Step 2: Add Icon to Schema Structure (PHP)

Edit the schema type class in `includes/output/types/class-{name}-schema.php`:

```php
public function get_schema_structure()
{
    return array(
        '@type' => 'YourSchemaType',
        'icon' => 'your-icon-name', // ← Add this line
    );
}
```

### Step 3: Add Icon to React Component

Add the icon import and mapping to `src/components/Icon.js`:

```javascript
import {
    page as fileText,
    yourIcon, // ← Add your new icon import
} from '@wordpress/icons';

const iconMap = {
    'file-text': fileText,
    'your-icon-name': yourIcon, // ← Add mapping
};
```

---

## 📚 Reference: Available Icons

### Current Icon Set

**Schema Type Icons:**
- `file-text` → `page`
- `help-circle` → `help`
- `building-2` → `building`
- `user` → `people`
- `shopping-bag` → `bag`
- `video` → `video`
- `chef-hat` → `category`
- `podcast` → `audio`
- `calendar` → `calendar`
- `list-checks` → `check`
- `briefcase` → `archive`

**UI/Action Icons:**
- `star` → `starFilled`
- `arrow-right` → `arrowRight`
- `chevron-up` → `chevronUp`
- `chevron-down` → `chevronDown`
- `pencil` → `edit`
- `lock` → `lock`
- `image` → `image`
- `check` → `check`
- `x` → `close`
- `external-link` → `external`
- `book-open` → `book`
- `message-circle` → `comment`
- `shield` → `shield`
- `shopping-cart` → `store`
- `refresh-cw` → `update`
- `code` → `code`
- `funnel` → `funnel`
- `settings` → `settings`
- `brackets` → `symbol`
- `info` → `info`
- `plus` → `plus`
- `users` → `people`
- `globe` → `globe`
- `trash-2` → `trash`

---

## ⚡ Benefits of Using @wordpress/icons

- No external dependencies (included with @wordpress/components)
- Consistent with WordPress admin interface
- Automatic updates with WordPress/Gutenberg
- Tree-shaking support for smaller bundles
- Official WordPress support
- GPL-compatible licensing

---

**Last Updated**: December 2024
**Maintained By**: Schema Engine Development Team
