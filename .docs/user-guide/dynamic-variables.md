# Using Dynamic Variables

Dynamic variables are one of the most powerful features in Swift Rank. They allow you to create a single template that automatically populates with different content for each page.

## What Are Dynamic Variables?

Variables are placeholders that get replaced with actual content when your schema is generated.

**Format:**
```
{variable_name}
```

**Example:**
```
Template field: {post_title}
On Post A: "How to Use Schema Markup"
On Post B: "WordPress SEO Best Practices"
```

The same template produces different schema output for each post.

## How Variables Work

### The Process

1. You create a template with variables: `{post_title}`
2. User visits a page
3. Swift Rank checks display conditions
4. Template matches the page
5. Variables are replaced with actual values
6. Schema is output to the page

### Variables vs Static Text

**Static Text:**
```
Publisher: "My Company Name"
```
- Same on every page
- Good for brand names, fixed values

**Dynamic Variables:**
```
Headline: {post_title}
```
- Changes for each page
- Good for content that varies

**Combined:**
```
Title: {post_title} | {site_name}
Output: "My Blog Post | My Company Name"
```

## Available Variables

### Site Variables

These pull from your WordPress site settings.

| Variable | Description | Example Output |
|----------|-------------|----------------|
| `{site_name}` | Site title | "My Awesome Site" |
| `{site_url}` | Homepage URL | "https://example.com" |
| `{site_description}` | Site tagline | "Just another WordPress site" |
| `{site_logo}` | Site logo URL | "https://example.com/logo.png" |

**When to use:**
- Publisher information
- Organization references
- Default images
- Site-wide consistent data

**Example:**
```json
{
  "@type": "Organization",
  "name": "{site_name}",
  "url": "{site_url}",
  "description": "{site_description}",
  "logo": "{site_logo}"
}
```

### Content Variables

These pull from the current post/page being viewed.

| Variable | Description | Example Output |
|----------|-------------|----------------|
| `{post_title}` | Post/page title | "Getting Started Guide" |
| `{post_excerpt}` | Post excerpt | "Learn how to use..." |
| `{post_content}` | Full post content (HTML stripped) | "Full article text..." |
| `{post_url}` | Permalink | "https://example.com/post" |
| `{featured_image}` | Featured image URL | "https://example.com/image.jpg" |
| `{post_date}` | Publish date (ISO 8601) | "2025-01-15T10:30:00+00:00" |
| `{post_modified}` | Last modified date | "2025-01-16T14:22:00+00:00" |

**When to use:**
- Article content
- Product information
- Page-specific data
- Any content that changes per page

**Example:**
```json
{
  "@type": "Article",
  "headline": "{post_title}",
  "description": "{post_excerpt}",
  "image": "{featured_image}",
  "datePublished": "{post_date}",
  "dateModified": "{post_modified}"
}
```

### Author Variables

These pull from the post author's profile.

| Variable | Description | Example Output |
|----------|-------------|----------------|
| `{author_name}` | Author display name | "John Doe" |
| `{author_url}` | Author archive URL | "https://example.com/author/john" |
| `{author_bio}` | Author biographical info | "John is a writer..." |
| `{author_email}` | Author email | "john@example.com" |
| `{author_avatar}` | Author profile image | "https://gravatar.com/..." |

**When to use:**
- Article author information
- Review author details
- Person schema references

**Example:**
```json
{
  "@type": "Article",
  "author": {
    "@type": "Person",
    "name": "{author_name}",
    "url": "{author_url}",
    "description": "{author_bio}"
  }
}
```

### Custom Meta Variables (Advanced)

Access any post meta field using the pattern:

```
{meta:meta_key_name}
```

**Common Use Cases:**

**WooCommerce Product Data:**
```
Price: {meta:_price}
SKU: {meta:_sku}
Stock Status: {meta:_stock_status}
```

**Advanced Custom Fields (ACF):**
```
Custom Field: {meta:custom_field_name}
```

**Custom Post Meta:**
```
Any Meta: {meta:your_meta_key}
```

**Example for WooCommerce:**
```json
{
  "@type": "Product",
  "name": "{post_title}",
  "sku": "{meta:_sku}",
  "offers": {
    "@type": "Offer",
    "price": "{meta:_price}",
    "priceCurrency": "USD"
  }
}
```

### Option Variables (Advanced)

Access WordPress options:

```
{option:option_name}
```

**Examples:**
```
Admin Email: {option:admin_email}
Posts Per Page: {option:posts_per_page}
Custom Option: {option:my_custom_option}
```

## Variable Best Practices

### 1. Use Appropriate Variables

**Good:**
```
Article Headline: {post_title}        ✓ Post-specific
Article Image: {featured_image}       ✓ Post-specific
Publisher Name: "My Company"          ✓ Site-wide constant
```

**Bad:**
```
Article Headline: "My Article"        ✗ Static for all posts
Publisher Name: {post_title}          ✗ Wrong variable
Author: "John Doe"                    ✗ Static author
```

### 2. Provide Fallbacks

Some variables have built-in fallbacks:

**{post_excerpt}**
- First tries: Post excerpt field
- Falls back to: Auto-generated from content
- Last resort: Empty string

**{featured_image}**
- First tries: Post featured image
- Falls back to: First image in content
- Last resort: Default image from Settings

**{site_logo}**
- First tries: Custom logo from Settings
- Falls back to: WordPress site logo
- Last resort: Empty string

### 3. Combine Variables Effectively

**For Titles:**
```
{post_title} - {site_name}
Output: "My Blog Post - My Company"
```

**For Descriptions:**
```
{post_excerpt} | Written by {author_name}
Output: "This guide explains... | Written by Jane Doe"
```

**For Attribution:**
```
By {author_name} on {post_date}
Output: "By John Smith on 2025-01-15T10:00:00+00:00"
```

### 4. Mind the Data Types

Variables output as strings, but schema expects different types:

**Text Fields** - Variables work as-is:
```
"headline": "{post_title}"  → "My Post Title"
```

**URL Fields** - Ensure full URLs:
```
"url": "{post_url}"  → "https://example.com/post"
```

**Date Fields** - Use date variables (auto-formatted):
```
"datePublished": "{post_date}"  → "2025-01-15T10:30:00+00:00"
```

**Number Fields** - Be careful with meta fields:
```
"price": "{meta:_price}"  → "29.99" (string)
```
Swift Rank automatically converts numbers when needed.

### 5. Empty Variables

What happens if a variable has no value?

**Text fields:**
- Empty string: Field is removed from schema

**Required fields:**
- Empty string: Validation error (fix before publishing)

**Images:**
- No image: Uses fallback or removes field

**Best Practice:**
- Always test on posts with and without data
- Provide fallback values where possible
- Use required fields to ensure data exists

## Advanced Variable Usage

### Conditional Content

Some variables adapt to context:

**{post_excerpt}**
- Has excerpt: Uses excerpt
- No excerpt: Generates from content (first 55 words)
- No content: Empty

**{featured_image}**
- Has featured image: Uses featured image URL
- No featured image: Finds first image in content
- No images: Uses default image from Settings

### Escaped Characters

Variables are automatically escaped for JSON:

**Input:**
```
Post title: My "Amazing" Post & Guide
```

**Variable:**
```
{post_title}
```

**Output in JSON:**
```json
"headline": "My \"Amazing\" Post & Guide"
```

Swift Rank handles:
- Quotes: `"` becomes `\"`
- Backslashes: `\` becomes `\\`
- Newlines: Converted to spaces
- Control characters: Removed

### HTML Stripping

Content variables remove HTML:

**Input:**
```
Post content: <p>This is <strong>bold</strong> text</p>
```

**Variable:**
```
{post_content}
```

**Output:**
```
"text": "This is bold text"
```

## Real-World Examples

### Example 1: Article Schema

**Goal:** Auto-populate article schema for all blog posts

**Template:**
```
Headline: {post_title}
Description: {post_excerpt}
Image: {featured_image}
Author: {author_name}
Date Published: {post_date}
Date Modified: {post_modified}
Publisher: My Company Name
```

**Result on Post:**
```json
{
  "@type": "Article",
  "headline": "10 SEO Tips for 2025",
  "description": "Learn the latest SEO strategies...",
  "image": "https://example.com/seo-tips.jpg",
  "author": {
    "@type": "Person",
    "name": "Sarah Johnson"
  },
  "datePublished": "2025-01-15T09:00:00+00:00",
  "dateModified": "2025-01-16T12:30:00+00:00",
  "publisher": {
    "@type": "Organization",
    "name": "My Company Name"
  }
}
```

### Example 2: Product Schema (WooCommerce)

**Goal:** Product schema from WooCommerce meta fields

**Template:**
```
Name: {post_title}
Description: {post_excerpt}
Image: {featured_image}
SKU: {meta:_sku}
Price: {meta:_price}
Currency: USD
Brand: {site_name}
```

**Result on Product:**
```json
{
  "@type": "Product",
  "name": "Wireless Headphones",
  "description": "Premium noise-canceling headphones...",
  "image": "https://example.com/headphones.jpg",
  "sku": "WH-1000",
  "offers": {
    "@type": "Offer",
    "price": "299.99",
    "priceCurrency": "USD"
  },
  "brand": {
    "@type": "Brand",
    "name": "My Company Name"
  }
}
```

### Example 3: Review Schema

**Goal:** Review schema with author and dates

**Template:**
```
Review Body: {post_content}
Author: {author_name}
Date Published: {post_date}
Rating: {meta:review_rating}
Item Name: {meta:reviewed_item}
```

**Result on Review:**
```json
{
  "@type": "Review",
  "reviewBody": "This product exceeded my expectations...",
  "author": {
    "@type": "Person",
    "name": "Mike Chen"
  },
  "datePublished": "2025-01-10T14:20:00+00:00",
  "reviewRating": {
    "@type": "Rating",
    "ratingValue": "5"
  },
  "itemReviewed": {
    "@type": "Product",
    "name": "Wireless Headphones"
  }
}
```

### Example 4: LocalBusiness with Variables

**Goal:** Multiple locations with some dynamic content

**Template:**
```
Name: {site_name} - {post_title}
Description: {post_excerpt}
Image: {featured_image}
Phone: {meta:location_phone}
Address: {meta:street_address}
City: {meta:city}
```

**Result on Location Page:**
```json
{
  "@type": "LocalBusiness",
  "name": "My Restaurant - Downtown Location",
  "description": "Our flagship location in the heart of downtown",
  "image": "https://example.com/downtown.jpg",
  "telephone": "+1-555-123-4567",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "123 Main Street",
    "addressLocality": "New York"
  }
}
```

## Troubleshooting Variables

### Variable Not Replacing

**Symptoms:**
- Schema shows `{post_title}` literally
- Variable appears as text in output

**Causes:**
1. Typo in variable name
2. Unsupported variable
3. Variable in wrong field type

**Solutions:**
1. Check spelling: `{post_title}` not `{posttitle}`
2. Verify variable exists in documentation
3. Use variable picker to insert correctly

### Empty Output

**Symptoms:**
- Field is missing from schema
- Empty values in output

**Causes:**
1. Post has no data for that field
2. Featured image not set
3. Meta field doesn't exist

**Solutions:**
1. Add content to posts (excerpt, featured image)
2. Provide static fallback values
3. Check meta field names are correct

### Wrong Data Appears

**Symptoms:**
- Author showing wrong name
- Date showing wrong value
- Content from different post

**Causes:**
1. Using wrong variable
2. Post meta from different post
3. Caching issue

**Solutions:**
1. Review variable documentation
2. Clear site cache
3. Test on different posts

### Meta Variable Not Working

**Symptoms:**
- `{meta:field_name}` shows literally
- Meta value not appearing

**Causes:**
1. Meta key name incorrect
2. Meta field doesn't exist
3. Meta value is empty

**Solutions:**
```php
// Check meta key name
get_post_meta($post_id, 'meta_key_name', true);

// Verify in WordPress database
SELECT meta_key FROM wp_postmeta WHERE post_id = YOUR_ID;
```

## Pro Variable Features

Swift Rank Pro adds additional variable capabilities:

**Custom Variable Groups:**
- Define your own variable groups
- Create reusable variable sets
- Share across templates

**Advanced Fallbacks:**
- Multiple fallback variables
- Conditional variable replacement
- Default values per variable

**Date Formatting:**
- Custom date formats
- Timezone adjustments
- Relative dates

## Quick Reference

### Variable Syntax

**Basic:**
```
{variable_name}
```

**Post Meta:**
```
{meta:meta_key}
```

**Option:**
```
{option:option_name}
```

### Common Patterns

**Article Author:**
```json
"author": {
  "@type": "Person",
  "name": "{author_name}",
  "url": "{author_url}"
}
```

**Publisher Reference:**
```json
"publisher": {
  "@type": "Organization",
  "name": "{site_name}",
  "url": "{site_url}",
  "logo": "{site_logo}"
}
```

**Product Offers:**
```json
"offers": {
  "@type": "Offer",
  "price": "{meta:_price}",
  "priceCurrency": "USD",
  "availability": "InStock",
  "url": "{post_url}"
}
```

## Best Practices Summary

1. **Use variables for dynamic content** - Post titles, dates, author info
2. **Use static text for constants** - Brand names, currency, fixed values
3. **Test with real data** - Ensure variables populate correctly
4. **Provide fallbacks** - Set default images in Settings
5. **Check required fields** - Ensure data exists for required schema fields
6. **Validate output** - Test with Google Rich Results Test
7. **Use meta wisely** - Verify meta keys exist before using
8. **Keep it simple** - Don't over-complicate with too many variables

## Next Steps

- **Master Conditions:** [Setting Display Conditions](display-conditions.md)
- **Validate Schema:** [Testing Your Schema](testing-schema.md)
- **Learn Schema Types:** [Schema Type Guides](schema-types/)

Dynamic variables make Swift Rank powerful and flexible. Master them, and you can automate schema for thousands of pages with a single template!
