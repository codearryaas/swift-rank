# Schema Templates

Schema Templates allow you to define structured data rules that apply to multiple pages on your site automatically. Instead of manually adding schema to every post, you create a template once, and Schema Engine applies it where you specify.

## Creating a New Template

1. Go to **Schema Engine → Add New**.
2. Enter a title for your template (e.g., "Blog Post Schema" or "Product Schema").
3. In the **Schema Engine** panel, configure your template settings.

## Schema Types

The first step is to select the type of schema you want to output. Schema Engine supports various types, including:

- **Article**: For blog posts and news articles.
- **Product** (Pro): For e-commerce products.
- **Review** (Pro): For review content.
- **Service** (Pro): For service pages.
- **Event** (Pro): For event pages.
- **Job Posting** (Pro): For job listings.
- **Recipe** (Pro): For food recipes.
- **Video Object** (Pro): For video content.
- **Custom JSON**: For advanced users who want to paste raw JSON-LD.

Select the appropriate type from the dropdown menu. Once selected, you will see fields specific to that schema type.

## Configuring Fields

Most fields can be set to:
- **Static Text**: Enter fixed text that will be the same on all pages.
- **Variables**: Use dynamic variables to pull data from the current post/page.

### Using Variables
Click the **Insert Variable** button (or `{ }` icon) next to a field to select a variable. Common variables include:
- `{post_title}`: The title of the current post.
- `{post_excerpt}`: The excerpt of the current post.
- `{post_date}`: The publish date.
- `{featured_image}`: The URL of the featured image.
- `{author_name}`: The name of the post author.

## Display Conditions

Conditions determine where this template will be applied on your website. You can create complex rules using groups.

### Adding Conditions
1. Scroll to the **Display Conditions** section.
2. Click **Add Condition Group**.
3. Add rules to the group.

### Rule Types
- **Whole Site**: Applies to every page on the site.
- **Post Type**: Applies to all posts of a specific type (e.g., Posts, Pages, Products).
- **Singular**: Applies to specific individual posts or pages.
- **Location**: Applies to special pages like Front Page, Blog Page, or Search Results.

### Logic Groups (AND/OR)
- **Rules within a group** use AND logic (all rules in the group must match).
- **Separate groups** use OR logic (if *any* group matches, the template is applied).

*Example: To apply schema to all "Posts" EXCEPT a specific category, you might need to use exclusion rules (if available) or be specific with your inclusion rules.*

## Saving Your Template

Once you have configured the schema type, fields, and conditions:
1. Click **Publish** (or **Update**) in the top right corner.
2. Your schema will now be automatically output on all pages matching your conditions.
