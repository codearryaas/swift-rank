# Creating Schema Templates

Schema templates are the foundation of Swift Rank. Instead of manually adding schema to every page, you create a template once and let it apply automatically based on conditions.

## What is a Schema Template?

A schema template consists of three main components:

1. **Schema Type**: The type of structured data (Article, Product, Organization, etc.)
2. **Field Values**: The actual content for each schema property
3. **Display Conditions**: Rules that determine where the schema appears

## Template Workflow

```
Create Template → Select Schema Type → Configure Fields → Set Conditions → Publish
```

Once published, the template automatically outputs schema on pages matching your conditions.

## Creating Your First Template

### Step 1: Access the Template Editor

Navigate to **Swift Rank → Add New**

You'll see the familiar WordPress editor with additional Swift Rank panels on the right.

### Step 2: Name Your Template

Enter a descriptive title in the title field:

**Good titles:**
- "Article Schema - Blog Posts"
- "Product Schema - WooCommerce"
- "Organization - Homepage"
- "FAQ Schema - Support Pages"

**Why good titles matter:**
- Easy to find when you have many templates
- Clear indication of purpose
- Helps when troubleshooting

### Step 3: Select Schema Type

In the **Swift Rank** panel (right sidebar):

1. Find the **Schema Type** dropdown
2. Click to view available types
3. Select the type that matches your content

**Available Schema Types (Free):**
- Article (BlogPosting, NewsArticle)
- Product
- Organization
- Person
- LocalBusiness
- Review
- VideoObject
- FAQ Page
- Breadcrumb
- Job Posting
- WebPage
- Website
- ImageObject

**Available Schema Types (Pro):**
- All free types, plus:
- Recipe
- Event
- HowTo
- Podcast Episode
- Custom Schema (visual builder)

### Step 4: Configure Schema Fields

After selecting a type, the panel refreshes showing type-specific fields.

## Working with Fields

### Field Types

**Text Fields**
- Single-line text input
- Good for: names, headlines, short descriptions
- Example: Article headline, Product name

**Textarea Fields**
- Multi-line text input
- Good for: descriptions, long content
- Example: Product description, Article summary

**URL Fields**
- Website addresses
- Automatically validated
- Example: Product page URL, Image URL

**Image Fields**
- Image selection/upload
- Opens WordPress Media Library
- Example: Featured image, Logo, Product image

**Select/Dropdown Fields**
- Pre-defined options
- Choose from a list
- Example: Article type (BlogPosting, NewsArticle)

**Number Fields**
- Numeric values only
- Can have min/max limits
- Example: Price, Rating value

**Date Fields**
- Date/time selection
- ISO 8601 format output
- Example: Publish date, Event start date

**Repeater Fields**
- Multiple items of the same type
- Add/remove rows dynamically
- Example: FAQ items, Recipe ingredients

### Using Static vs Dynamic Values

**Static Values**
- Fixed text you type in
- Same across all pages
- Example: Your company name, Brand name

**Dynamic Values (Variables)**
- Placeholders that change per page
- Wrapped in curly braces `{}`
- Example: `{post_title}`, `{author_name}`

**Best Practices:**
```
✓ Good: Headline = {post_title}           (dynamic - changes per post)
✓ Good: Publisher = "My Company Name"     (static - same for all)
✗ Avoid: Headline = "My Blog Post"        (static - same for all posts)
```

### Required vs Optional Fields

**Required Fields** (marked with red asterisk *)
- Must be filled for valid schema
- Google requires these for rich results
- Examples: Article headline, Product name, Organization name

**Optional Fields**
- Enhance your schema but not required
- Add when data is available
- Examples: Organization email, Product SKU

### Field Tooltips

Hover over the info icon (ℹ) next to field labels for:
- Field descriptions
- Example values
- Best practices
- Google requirements

## Advanced Field Configuration

### Using the Variable Picker

Many fields have a **{} Insert Variable** button:

1. Click the button next to a field
2. Browse variable categories:
   - Site Variables
   - Content Variables
   - Author Variables
3. Click a variable to insert it
4. The variable appears in the field (e.g., `{post_title}`)

### Combining Static and Dynamic Content

You can mix static text with variables:

```
{author_name} | {site_name}
```
Output: "John Doe | My Website"

```
Review by {author_name} on {post_date}
```
Output: "Review by Jane Smith on 2025-01-15"

### Fallback Values

Some fields use multiple variables for fallback:

```
{post_excerpt}
```
If no excerpt: Automatically generates from content

```
{featured_image}
```
If no featured image: Uses default image from Settings

### Working with Repeater Fields

Repeater fields let you add multiple items (like FAQ questions or ingredients).

**Example: FAQ Items**

1. Find the **FAQ Items** repeater
2. Click **Add Item**
3. Fill in:
   - Question: "What is schema markup?"
   - Answer: "Schema markup is structured data..."
4. Click **Add Item** again for more questions
5. Drag to reorder items
6. Click **Remove** (× icon) to delete items

**Example: Social Profiles**

1. Click **Add Profile**
2. Select network: Facebook
3. Enter URL: https://facebook.com/yourpage
4. Repeat for Twitter, LinkedIn, etc.

## Template Organization

### Using Categories for Templates

While Swift Rank templates are custom post types, you can organize them:

**Naming Convention:**
- Prefix templates by type: "Article - ", "Product - ", "Local - "
- Example: "Article - Blog Posts", "Article - News Items"

**Draft vs Published:**
- **Draft**: Template exists but doesn't output schema
- **Published**: Template is active and outputs schema

**Status Management:**
- Keep experimental templates as drafts
- Publish only tested templates
- Archive old templates instead of deleting

### Template Priority

When multiple templates match the same page:

1. Swift Rank combines all matching schemas
2. Creates a connected schema graph
3. Each schema gets a unique @id reference
4. No conflicts - all schemas output together

**Example:**
- Page matches: "Article Schema" + "Breadcrumb Schema"
- Output: Both schemas in a single `<script>` tag
- Connected via @id references

## Template Examples

### Example 1: Blog Post Article Schema

**Template Name:** "Article Schema - Blog Posts"

**Schema Type:** Article

**Fields:**
- Article Type: `BlogPosting`
- Headline: `{post_title}`
- Description: `{post_excerpt}`
- Image: `{featured_image}`
- Author Name: `{author_name}`
- Publisher: (Auto-populated from Settings)
- Date Published: `{post_date}`
- Date Modified: `{post_modified}`

**Display Conditions:**
- Post Type = Posts

### Example 2: Product Schema for WooCommerce

**Template Name:** "Product Schema - WooCommerce"

**Schema Type:** Product (Pro)

**Fields:**
- Name: `{post_title}`
- Description: `{post_excerpt}`
- Image: `{featured_image}`
- SKU: `{meta:_sku}`
- Price: `{meta:_price}`
- Currency: `USD`
- Availability: `InStock`
- Brand: "Your Brand Name"

**Display Conditions:**
- Post Type = Products

### Example 3: FAQ Schema for Support Pages

**Template Name:** "FAQ Schema - Support Center"

**Schema Type:** FAQ Page

**Fields:**
- FAQ Items: (Repeater)
  - Item 1:
    - Question: "How do I install the plugin?"
    - Answer: "Navigate to Plugins → Add New..."
  - Item 2:
    - Question: "Is there a Pro version?"
    - Answer: "Yes, Swift Rank Pro offers..."

**Display Conditions:**
- Singular = Select specific FAQ pages

### Example 4: LocalBusiness Schema

**Template Name:** "Local Business - Main Location"

**Schema Type:** LocalBusiness

**Fields:**
- Business Type: `Restaurant`
- Name: "Your Restaurant Name"
- Description: "Best Italian restaurant in..."
- Image: (Upload logo)
- Price Range: `$$-$$$`
- Phone: `+1-555-123-4567`
- Email: `info@restaurant.com`
- Street Address: "123 Main Street"
- City: "New York"
- State: "NY"
- Postal Code: "10001"
- Country: "United States"

**Display Conditions:**
- Location = Front Page

## Best Practices

### Template Creation

1. **One Type Per Template**: Don't mix schema types in conditions
2. **Descriptive Names**: Always use clear, descriptive template names
3. **Test Before Publishing**: Use Draft status while testing
4. **Use Variables**: Automate as much as possible
5. **Fill Required Fields**: Never leave required fields empty

### Field Configuration

1. **Validate URLs**: Ensure all URLs are absolute, not relative
2. **High-Quality Images**: Use high-resolution images (1200x630+ for featured)
3. **Complete Dates**: Use ISO 8601 format (automatic with variables)
4. **Consistent Naming**: Use same publisher/organization across templates
5. **No Placeholders**: Don't publish with "TODO" or "Example" text

### Condition Management

1. **Specific First**: Create specific templates before broad ones
2. **Avoid Conflicts**: Don't create competing conditions
3. **Test Conditions**: View pages to ensure conditions work
4. **Document Logic**: Use template descriptions to note complex conditions

## Troubleshooting Templates

### Schema Not Appearing

**Check:**
1. Template is Published (not Draft)
2. Display conditions match the current page
3. Required fields are filled
4. No PHP/JavaScript errors in browser console

**Debugging:**
1. View page source
2. Search for `ld+json`
3. Check for validation errors

### Schema Has Errors

**Check:**
1. Required fields are present
2. URLs are absolute (not relative)
3. Dates are in correct format
4. Images are accessible (not 404)
5. Numbers are numeric (not text)

**Fix:**
1. Use Google Rich Results Test
2. Review error messages
3. Update field values
4. Re-test until errors clear

### Wrong Schema Appears

**Check:**
1. Review display conditions on all templates
2. Verify you're viewing the correct page
3. Check template priority/conflicts
4. Clear cache (if using caching plugin)

## Advanced Techniques

### Using Custom Meta Fields

Access post meta values:
```
{meta:custom_field_name}
```

Example for WooCommerce:
```
SKU: {meta:_sku}
Price: {meta:_price}
```

### Referencing Other Schemas

Swift Rank automatically connects related schemas:
- Article author references Person/Organization
- Product manufacturer references Organization
- Review itemReviewed references Product

### Creating Schema Variations

Create multiple templates for variations:

**Article Variations:**
- "Article - Blog Posts" (BlogPosting)
- "Article - News" (NewsArticle)
- "Article - Tutorials" (TechArticle)

Each with different conditions targeting different categories.

### Conditional Fields (Pro)

Some Pro fields show/hide based on other selections:
- Business Type shows relevant LocalBusiness subtypes
- Article Type shows type-specific fields
- Event Type shows online vs physical location fields

## Next Steps

Now that you understand template creation:

1. **Learn Variables**: [Using Dynamic Variables](dynamic-variables.md)
2. **Master Conditions**: [Setting Display Conditions](display-conditions.md)
3. **Validate Schema**: [Testing Your Schema](testing-schema.md)
4. **Explore Types**: [Schema Type Guides](schema-types/)

## Quick Reference

### Template Checklist

- [ ] Descriptive template name
- [ ] Appropriate schema type selected
- [ ] All required fields filled
- [ ] Variables used for dynamic content
- [ ] Static content for fixed values
- [ ] Display conditions configured
- [ ] Tested on sample page
- [ ] Validated with Google Rich Results Test
- [ ] Published (not Draft)

### Common Mistakes to Avoid

- Leaving required fields empty
- Using static text for content that should be dynamic
- Not testing display conditions
- Publishing without validation
- Using relative URLs instead of absolute
- Forgetting to publish the template
- Creating too broad conditions that conflict
- Not using high-quality images

Master these template creation skills, and you'll be able to add schema to any content type on your WordPress site!
