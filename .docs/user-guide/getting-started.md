# Getting Started with Schema Engine

Welcome to Schema Engine! This guide will help you get started with adding structured data (schema markup) to your WordPress website.

## What is Schema Markup?

Schema markup is structured data that helps search engines understand your website content better. It can enhance your search results with rich snippets, including star ratings, images, prices, and more.

## First Steps After Installation

After installing and activating Schema Engine, you'll find a new menu item in your WordPress dashboard: **Schema Engine**.

### 1. Configure Your Knowledge Graph

The Knowledge Graph represents your website's identity in search results.

1. Navigate to **Schema Engine → Settings**
2. Under the **Knowledge Graph** section:
   - Select your **Entity Type**: Organization, Person, or Local Business
   - Fill in your basic information (name, description, URL)
   - Upload your logo or profile image
   - Add contact information (optional but recommended)
   - Add your physical address (if applicable)

3. Add your **Social Profiles**:
   - Click **Add Profile**
   - Select the social network
   - Enter the full URL to your profile
   - Repeat for all your official social media accounts

4. Click **Save Settings**

### 2. Understanding the Interface

Schema Engine has three main areas:

#### Schema Templates
- Located at **Schema Engine → All Schemas**
- Each template defines structured data rules
- Templates apply automatically based on conditions you set

#### Settings
- Located at **Schema Engine → Settings**
- Configure site-wide schema (Knowledge Graph)
- Set default images and global options
- Manage your Pro license (if applicable)

#### Add New Schema
- Located at **Schema Engine → Add New**
- Create new schema templates
- Configure schema type, fields, and display conditions

## Your First Schema Template

Let's create a simple Article schema for your blog posts.

### Step 1: Create a New Template

1. Go to **Schema Engine → Add New**
2. Enter a title: "Blog Post Article Schema"
3. Click **Publish** to save it (you can edit it after)

### Step 2: Select Schema Type

1. In the **Schema Engine** panel on the right, find **Schema Type**
2. Select **Article** from the dropdown
3. The panel will refresh showing Article-specific fields

### Step 3: Configure Basic Fields

You'll see several fields. Here's what to do:

**Headline** (Required)
- Default: `{post_title}` - This automatically uses your post's title
- Leave as is

**Description**
- Default: `{post_excerpt}` - Uses your post excerpt
- Leave as is or customize

**Image** (Required)
- Default: `{featured_image}` - Uses your featured image
- Leave as is

**Author Name**
- Default: `{author_name}` - Uses the post author's name
- Leave as is

**Publisher**
- This references your Organization/Person from Settings
- No action needed

**Date Published**
- Default: `{post_date}` - Uses publish date
- Leave as is

**Date Modified**
- Default: `{post_modified}` - Uses last modified date
- Leave as is

### Step 4: Set Display Conditions

Scroll down to the **Display Conditions** section.

1. Click **Add Condition Group**
2. Click **Add Rule**
3. Configure the rule:
   - **Type**: Post Type
   - **Operator**: Equal To
   - **Value**: Select "Posts"
4. Click outside to save the rule

This tells Schema Engine to apply this schema to all blog posts.

### Step 5: Publish Your Template

1. Click **Update** in the top right
2. Your Article schema is now active!

## Viewing Your Schema

To see if your schema is working:

1. Open any blog post on your website
2. View the page source (right-click → View Page Source)
3. Search for `application/ld+json`
4. You should see your schema markup in JSON-LD format

### Quick Validation

While viewing a blog post (logged in as admin):

1. Look at the admin bar at the top
2. Hover over **Schema Engine**
3. Click **Google Rich Results Test**
4. Google will analyze your schema and show any errors or warnings

## Understanding Variables

You noticed `{post_title}` and `{post_excerpt}` in the fields. These are **dynamic variables**.

**What are variables?**
- Placeholders that get replaced with actual content
- Wrapped in curly braces: `{variable_name}`
- Pull data from the current post, author, or site settings

**Common variables:**
- `{post_title}` - Post title
- `{post_excerpt}` - Post excerpt
- `{post_content}` - Full post content
- `{featured_image}` - Featured image URL
- `{author_name}` - Author's display name
- `{site_name}` - Your website name
- `{site_url}` - Your homepage URL

**When to use variables:**
- Use variables for content that changes per post
- Use static text for content that stays the same

**Example:**
- Good: Headline = `{post_title}` (changes for each post)
- Good: Publisher = Your Company Name (same for all posts)

## Next Steps

Now that you have your first schema template working, you can:

1. **Create More Templates**: Different schema types for different content
   - Product schema for WooCommerce products (Pro)
   - FAQ schema for FAQ pages
   - Video schema for video content (Pro)
   - LocalBusiness schema for location pages

2. **Refine Display Conditions**: Target specific pages or categories
   - Show schema only on specific categories
   - Exclude certain posts or pages
   - Combine multiple conditions

3. **Explore Pro Features** (if you have Pro):
   - Advanced schema types (Recipe, Event, HowTo)
   - Custom schema builder
   - Opening hours for local businesses
   - Advanced display conditions

## Common Questions

### Do I need to create schema for every post manually?

No! That's the power of templates. Create one template with display conditions, and it automatically applies to all matching posts.

### What happens if I have multiple templates?

If multiple templates match the same page, Schema Engine combines them into a single schema graph, ensuring they're properly connected.

### Can I override schema for a specific post?

Yes! Edit the post and use the **Schema Engine** metabox to customize or disable schema for that specific post.

### How do I know if my schema is valid?

Use the validation tools:
- Admin bar → Schema Engine → Google Rich Results Test
- Admin bar → Schema Engine → Schema.org Validator
- Both test your schema and report any errors

### Does schema affect my site's performance?

Schema Engine is lightweight and optimized for performance. The schema markup is minimal JSON-LD code that doesn't impact page load times.

## Tips for Success

1. **Start Simple**: Begin with basic Article or Organization schema
2. **Use Variables**: Let Schema Engine populate content automatically
3. **Validate Regularly**: Check your schema after making changes
4. **Fill Required Fields**: Red asterisks (*) indicate required fields
5. **Use Unique Titles**: Give each template a descriptive title for easy management
6. **Test on Different Pages**: Ensure your conditions work as expected

## Getting Help

If you need assistance:

- **Documentation**: Check the user guides in the `.docs/user-guide/` folder
- **Support**: Visit [ToolPress.net Support](https://toolpress.net/support)
- **Community**: Join the WordPress community forums

## What's Next?

Continue learning with these guides:

- [Creating Schema Templates](templates.md) - Deep dive into template creation
- [Using Dynamic Variables](dynamic-variables.md) - Master variable usage
- [Setting Display Conditions](display-conditions.md) - Advanced targeting
- [Testing Your Schema](testing-schema.md) - Validation and debugging
- [Article Schema Setup](schema-types/article.md) - Complete Article schema guide
- [Organization Schema Setup](schema-types/organization.md) - Organization schema guide
- [FAQ Schema Setup](schema-types/faq.md) - FAQ schema guide

Welcome to better search visibility with Schema Engine!
