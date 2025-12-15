# Setting Display Conditions

Display conditions determine where your schema templates appear on your website. Master conditions, and you can target schema to exactly the right pages with precision.

## What Are Display Conditions?

Display conditions are rules that control when a schema template outputs its markup. Think of them as "If this page matches these rules, show this schema."

**Without conditions:**
- Schema appears everywhere (or nowhere)
- No control over placement

**With conditions:**
- Precise targeting
- Show schema only where relevant
- Different schema for different content types

## Understanding the Condition Panel

When creating or editing a schema template, scroll down to find the **Display Conditions** panel.

**Initial State:**
- No conditions = template doesn't appear anywhere
- Must add at least one condition group

**Panel Structure:**
```
Display Conditions
├── Condition Group 1
│   ├── Rule 1
│   ├── Rule 2
│   └── Add Rule button
├── Condition Group 2
│   └── Rule 1
└── Add Condition Group button
```

## Condition Anatomy

### Groups and Rules

**Rule**: A single condition check
```
Post Type equals Posts
```

**Group**: A collection of rules
```
Group 1:
- Post Type equals Posts
- AND Location not equals Search Page
```

**Template**: Multiple groups
```
Show schema if:
  (Group 1 matches) OR (Group 2 matches)
```

### Logic Operators

**Within a Group (AND/OR):**
- **AND**: ALL rules must match
- **OR**: AT LEAST ONE rule must match

**Between Groups (AND/OR):**
- **OR**: If ANY group matches, show schema
- **AND**: ALL groups must match (rare use case)

**Default Logic:**
- Rules within a group: **AND**
- Between groups: **OR**

## Condition Types

Swift Rank supports four main condition types:

### 1. Whole Site

**What it does:**
- Matches every page on your site
- Always returns true

**When to use:**
- Site-wide schema (Organization, Website)
- Breadcrumbs on all pages
- Global schema that appears everywhere

**Example:**
```
Condition Type: Whole Site
```

**Use Case:**
- Organization schema on every page
- Breadcrumb navigation
- Website schema with Sitelinks Searchbox

**Configuration:**
1. Select "Whole Site" from condition type
2. No additional settings needed
3. Schema appears on every page

### 2. Post Type

**What it does:**
- Matches specific post types
- Checks `get_post_type()`

**When to use:**
- All posts of a type (Posts, Pages, Products)
- Custom post types
- Content-type-specific schema

**Example:**
```
Condition Type: Post Type
Operator: Equal To
Value: Posts, Pages
```

**Available Post Types:**
- Posts (blog posts)
- Pages
- Products (WooCommerce)
- Custom Post Types (any registered CPT)

**Configuration:**
1. Select "Post Type"
2. Choose operator:
   - **Equal To**: Include these post types
   - **Not Equal To**: Exclude these post types
3. Select post types from dropdown (multiple allowed)

**Examples:**

**Show on all blog posts:**
```
Post Type = Posts
```

**Show on all WooCommerce products:**
```
Post Type = Products
```

**Show on posts and pages:**
```
Post Type = Posts, Pages
```

**Show on all except pages:**
```
Post Type ≠ Pages
```

### 3. Singular

**What it does:**
- Matches specific individual posts/pages by ID
- Checks `get_the_ID()`

**When to use:**
- One-off schema for specific pages
- Landing pages with unique schema
- Special content that needs custom markup

**Example:**
```
Condition Type: Singular
Operator: Equal To
Value: Post ID 123, Page ID 456
```

**Configuration:**
1. Select "Singular"
2. Choose operator:
   - **Equal To**: Include these specific posts
   - **Not Equal To**: Exclude these specific posts
3. Search and select posts/pages

**Examples:**

**Show only on About page:**
```
Singular = Page ID 42
```

**Show on multiple specific posts:**
```
Singular = Post ID 10, Post ID 20, Post ID 30
```

**Show on all posts EXCEPT one:**
```
Post Type = Posts
Singular ≠ Post ID 123
```
(Requires two rules in one group)

### 4. Location

**What it does:**
- Matches special page types
- WordPress conditional tags

**When to use:**
- Homepage
- Blog index page
- Search results
- 404 pages
- Archives

**Example:**
```
Condition Type: Location
Operator: Equal To
Value: Front Page
```

**Available Locations:**
- **Front Page**: Homepage (static or posts)
- **Home Page**: Blog index page
- **Search Page**: Search results

**Configuration:**
1. Select "Location"
2. Choose operator:
   - **Equal To**: Include these locations
   - **Not Equal To**: Exclude these locations
3. Select locations (multiple allowed)

**Examples:**

**Show only on homepage:**
```
Location = Front Page
```

**Show on all pages except search:**
```
Post Type = Posts
Location ≠ Search Page
```

**Show on blog index:**
```
Location = Home Page
```

## Building Condition Logic

### Simple Conditions (One Rule)

**Show on all blog posts:**
```
Group 1:
└── Post Type = Posts
```

**Show only on homepage:**
```
Group 1:
└── Location = Front Page
```

### Multiple Rules (AND Logic)

**Show on Posts but not on Search:**
```
Group 1:
├── Post Type = Posts        (AND)
└── Location ≠ Search Page
```

Both rules must match. Shows on post pages, but not when viewed in search results.

**Show on specific post types:**
```
Group 1:
└── Post Type = Posts, Pages
```

Matches if post type is Posts OR Pages (within the value).

### Multiple Groups (OR Logic)

**Show on Posts OR Pages:**
```
Group 1:
└── Post Type = Posts
                              (OR)
Group 2:
└── Post Type = Pages
```

If either group matches, schema appears.

**Show on homepage OR specific page:**
```
Group 1:
└── Location = Front Page
                              (OR)
Group 2:
└── Singular = Page ID 42
```

### Complex Conditions

**Posts in category OR specific pages:**
```
Group 1:
├── Post Type = Posts        (AND)
└── (Category = News)        [via post selection]
                              (OR)
Group 2:
└── Singular = Page ID 10, Page ID 20
```

**All products except one:**
```
Group 1:
├── Post Type = Products     (AND)
└── Singular ≠ Product ID 99
```

**Posts and Pages but not on Search:**
```
Group 1:
├── Post Type = Posts, Pages (AND)
└── Location ≠ Search Page
```

## Real-World Examples

### Example 1: Blog Article Schema

**Goal:** Article schema on all blog posts

**Conditions:**
```
Group 1:
└── Post Type = Posts
```

**Logic:** Show on any post

### Example 2: Product Schema (WooCommerce)

**Goal:** Product schema on all products

**Conditions:**
```
Group 1:
└── Post Type = Products
```

**Logic:** Show on any WooCommerce product

### Example 3: Organization Schema

**Goal:** Organization schema on homepage only

**Conditions:**
```
Group 1:
└── Location = Front Page
```

**Logic:** Show only on the front page

### Example 4: FAQ Schema on Support Pages

**Goal:** FAQ schema on specific support pages

**Conditions:**
```
Group 1:
└── Singular = Page ID 50, Page ID 51, Page ID 52
```

**Logic:** Show on pages 50, 51, and 52

### Example 5: LocalBusiness on Multiple Locations

**Goal:** LocalBusiness schema on location pages

**Conditions:**
```
Group 1:
└── Post Type = Locations
```

Where "Locations" is a custom post type.

**Logic:** Show on all location posts

### Example 6: Video Schema on Posts and Pages

**Goal:** Video schema on posts and pages with videos

**Conditions:**
```
Group 1:
└── Post Type = Posts, Pages
```

**Logic:** Show on posts and pages (template uses conditional fields to check for video meta)

### Example 7: Exclude Specific Posts

**Goal:** Article schema on all posts except announcements

**Conditions:**
```
Group 1:
├── Post Type = Posts                    (AND)
└── Singular ≠ Post ID 100, Post ID 101
```

**Logic:** Post type must be Posts AND post ID must not be 100 or 101

### Example 8: Homepage OR About Page

**Goal:** Organization schema on homepage and about page

**Conditions:**
```
Group 1:
└── Location = Front Page
                                          (OR)
Group 2:
└── Singular = Page ID 2
```

**Logic:** Show if homepage OR page ID 2

## Condition Priority

### When Multiple Templates Match

If multiple templates match the same page:

1. **All matching templates output their schema**
2. Schemas are combined into a single graph
3. Each schema gets a unique `@id`
4. Schemas reference each other when appropriate

**Example:**
- Template A: Article schema (matches blog posts)
- Template B: Breadcrumb schema (matches whole site)
- Result: Both schemas output on blog post pages

**Output:**
```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Article",
      "@id": "https://example.com/post#article",
      "headline": "My Post"
    },
    {
      "@type": "BreadcrumbList",
      "@id": "https://example.com/post#breadcrumb",
      "itemListElement": [...]
    }
  ]
}
```

### Avoiding Conflicts

**Same Schema Type:**
If two templates of the same type match:

**Example:**
- Template A: Article schema (Post Type = Posts)
- Template B: Article schema (Singular = Post ID 10)

**On Post ID 10:**
- Both templates match
- Both Article schemas output
- May cause duplicate data

**Solution:**
- Use more specific conditions
- Use "Not Equal To" operators to exclude
- Combine into one template with better conditions

**Best Practice:**
- One template per schema type per page
- Use specific conditions to avoid overlaps
- Test on various pages to ensure correct behavior

## Testing Conditions

### How to Test

**1. Create template in Draft mode**
```
Status: Draft
Conditions: Set your conditions
```

**2. View target pages**
- Visit pages that should match
- Visit pages that shouldn't match

**3. Check page source**
```
View Source → Search for "ld+json"
```

**4. Verify schema appears**
- Should appear on matching pages
- Should NOT appear on non-matching pages

**5. Publish when confirmed**
```
Status: Publish
```

### Testing Checklist

For each template:

- [ ] Test on matching page (schema appears)
- [ ] Test on non-matching page (schema doesn't appear)
- [ ] Test edge cases (search, archives, etc.)
- [ ] Test with other templates (no conflicts)
- [ ] Validate schema output
- [ ] Clear cache and retest

## Troubleshooting Conditions

### Schema Not Appearing

**Check:**
1. Template is Published (not Draft)
2. Conditions are set (at least one group)
3. At least one rule in each group
4. You're viewing the right type of page

**Debug:**
1. Simplify conditions to "Whole Site"
2. If appears: Conditions were too specific
3. Gradually add conditions back
4. Find which condition causes issue

### Schema Appearing on Wrong Pages

**Check:**
1. Review all condition groups
2. Check for "Whole Site" condition
3. Verify operators (Equal To vs Not Equal To)
4. Check other templates that might match

**Debug:**
1. List all active templates
2. Check conditions on each
3. Identify which template is causing output
4. Adjust conditions accordingly

### Multiple Schemas of Same Type

**Symptoms:**
- Duplicate Article schemas
- Two Organization schemas
- Conflicting data

**Cause:**
- Multiple templates matching same page

**Solution:**
1. Review all templates of that type
2. Make conditions mutually exclusive
3. Or combine templates into one with better conditions

## Advanced Techniques

### Exclusion Patterns

**Exclude specific pages from broad rule:**
```
Group 1:
├── Post Type = Posts               (AND)
└── Singular ≠ Post ID 10, Post ID 20
```

**Show everywhere except homepage:**
```
Group 1:
└── Location ≠ Front Page
```

### Combination Patterns

**Posts OR Pages:**
```
Group 1:
└── Post Type = Posts, Pages
```

**Multiple specific pages:**
```
Group 1:
└── Singular = Page ID 10, Page ID 20, Page ID 30
```

**Posts on non-search pages:**
```
Group 1:
├── Post Type = Posts        (AND)
└── Location ≠ Search Page
```

### Fallback Patterns

**Specific first, general fallback:**

**Template A (Specific):**
```
Singular = Post ID 10
```

**Template B (General):**
```
Post Type = Posts
Singular ≠ Post ID 10
```

Ensures Post ID 10 gets Template A, others get Template B.

## Best Practices

### Condition Design

1. **Start Specific**: Target exact pages first
2. **Test Thoroughly**: Verify on multiple page types
3. **Document Complex Logic**: Use template descriptions
4. **Avoid Overlaps**: One schema type per page
5. **Use Exclusions**: NOT operators for exceptions

### Organization

1. **Name Templates Clearly**: Include target in name
   - "Article - Blog Posts"
   - "Product - WooCommerce"
   - "Organization - Homepage"

2. **Group Related Templates**:
   - Article schema templates together
   - Product schema templates together

3. **Keep It Simple**:
   - Don't over-complicate conditions
   - Fewer groups = easier to maintain

### Maintenance

1. **Review Regularly**: Check conditions still make sense
2. **Update When Content Changes**: New post types, new pages
3. **Archive Old Templates**: Don't delete, set to Draft
4. **Document Decisions**: Note why conditions are set a certain way

## Quick Reference

### Condition Types Summary

| Type | Matches | Example |
|------|---------|---------|
| Whole Site | Every page | Organization schema |
| Post Type | All posts of type | Blog posts, Products |
| Singular | Specific posts by ID | About page, Contact |
| Location | Special pages | Homepage, Blog index |

### Operators

| Operator | Meaning | Use Case |
|----------|---------|----------|
| Equal To | Include | Show on these |
| Not Equal To | Exclude | Hide from these |

### Logic

| Scope | Default | Meaning |
|-------|---------|---------|
| Rules in Group | AND | All must match |
| Between Groups | OR | Any can match |

### Common Patterns

**All posts:**
```
Post Type = Posts
```

**Specific page:**
```
Singular = Page ID X
```

**Homepage only:**
```
Location = Front Page
```

**Posts except one:**
```
Post Type = Posts
Singular ≠ Post ID X
```

**Homepage OR About:**
```
Group 1: Location = Front Page
Group 2: Singular = Page ID X
```

## Next Steps

- **Validate Your Schema**: [Testing Your Schema](testing-schema.md)
- **Learn Schema Types**: [Article Schema](schema-types/article.md)
- **Master Variables**: [Dynamic Variables](dynamic-variables.md)

Master display conditions, and you'll have complete control over where your schema appears. Take the time to learn them well—they're the foundation of effective schema management!
