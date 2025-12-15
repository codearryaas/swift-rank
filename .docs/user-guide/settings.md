# Swift Rank Settings

The global settings allow you to configure site-wide schema information, primarily for your Knowledge Graph (Organization, Person, or Local Business) that appears on your homepage.

## Accessing Settings

To access the settings, navigate to **Swift Rank → Settings** in your WordPress admin dashboard.

## Knowledge Graph

This section controls the schema markup that describes your website's entity. This is crucial for Google's Knowledge Graph panel.

### Entity Type
Choose the type that best represents your website:
- **Organization**: For businesses, companies, non-profits, etc.
- **Person**: For personal blogs, portfolios, or author sites.
- **Local Business**: For businesses with a physical location (stores, restaurants, etc.).

### Basic Information
Fields vary based on the selected Entity Type.

**Common Fields:**
- **Name**: The name of your organization or yourself. Defaults to your site name.
- **Description**: A brief description of your entity.
- **URL**: The website URL.
- **Image / Logo**: Upload a high-quality logo (for Organizations) or profile picture (for Persons).

**Organization Specifics:**
- **Organization Type**: Select the specific type (e.g., Corporation, NGO).

**Person Specifics:**
- **Job Title**: Your professional title.
- **Works For**: The organization you work for.
- **Gender**: Optional.
- **Birth Date**: Optional.
- **Nationality**: Optional.

**Local Business Specifics:**
- **Business Type**: Select the specific type (e.g., Restaurant, Store).
- **Price Range**: The price range of your services (e.g., $$-$$$).
- **Opening Hours** (Pro): Define your business hours.
- **Geo Coordinates**: Latitude and Longitude.
- **Menu URL**: Link to your menu (for food establishments).
- **Accepts Reservations**: Yes/No.

### Contact Information
Add public contact details:
- **Phone Number**: International format (e.g., +1-555-0123).
- **Email**: Public contact email.

### Address
Enter the physical address of your entity:
- **Street Address**
- **City**
- **State / Region**
- **Postal Code**
- **Country**

## Social Profiles

Add links to your official social media profiles. This helps search engines understand your digital presence.

1. Click **Add Profile**.
2. Select the social network (Facebook, Twitter/X, LinkedIn, Instagram, YouTube, etc.).
3. Enter the full URL to your profile.

## General Settings

### Default Image

Set a fallback image to be used when schema fields don't have a specific image available.

- **Use Case**: When a post has no featured image, this image will be used in schema markup
- **Recommended Size**: 1200x630 pixels or larger
- **Format**: JPG or PNG

### Auto Schema

Auto Schema automatically generates schema markup for your content when no templates match the current page. This is perfect for quick setup or as a fallback for content without specific templates.

#### How Auto Schema Works

Auto Schema only applies when:
1. No schema templates match the current page
2. The content type has Auto Schema enabled in settings
3. Required data is available (title, image, etc.)

Auto Schema uses dynamic variables to populate fields automatically, pulling data from your posts, pages, and site settings.

#### Posts

- **Enable Auto Schema for Posts**: Automatically generate schema for blog posts
- **Schema Type**: Choose the type of Article schema to generate:
  - **Article**: General articles (default)
  - **BlogPosting**: Blog posts
  - **NewsArticle**: News articles
  - **ScholarlyArticle**: Academic articles
  - **TechArticle**: Technical articles

**When enabled**, all blog posts without matching templates will automatically get Article schema with:
- Headline from post title
- Description from post excerpt
- Image from featured image
- Author information
- Publish and modified dates

#### Pages

- **Enable Auto Schema for Pages**: Automatically generate WebPage schema for pages

**When enabled**, all pages without matching templates will automatically get WebPage schema.

#### Search Results

- **Enable Auto Schema for Search**: Add SearchResultsPage schema to search results pages

This helps search engines understand your site's search functionality.

#### WooCommerce Products

- **Enable Auto Schema for WooCommerce**: Automatically generate Product schema for WooCommerce products

**When enabled**, all products without matching templates will automatically get Product schema with:
- Product name
- Description
- Price and currency
- Availability
- SKU
- Images

### Minify JSON-LD

- **Enabled**: Removes whitespace to reduce page size (recommended for production)
- **Disabled**: Outputs formatted, readable JSON-LD (useful for debugging)

## Breadcrumb Schema

Configure BreadcrumbList schema to help search engines understand your site hierarchy and navigation structure.

### Settings

- **Enable Breadcrumbs**: Turn breadcrumb schema on/off globally
- **Separator**: Character to use between breadcrumb items (e.g., `»`, `>`, `/`)
- **Show Home**: Include the homepage as the first breadcrumb item
- **Home Text**: Custom text for the home link (default: "Home")

### How Breadcrumbs Work

When enabled, Swift Rank automatically generates breadcrumb schema based on your site's hierarchy:
- **Posts**: Home → Category → Post Title
- **Pages**: Home → Parent Page → Current Page
- **Archives**: Home → Archive Type → Archive Name
- **Custom Post Types**: Home → Post Type Archive → Post Title

Breadcrumbs appear in search results and help users understand page location within your site.

## Import/Export

The Import/Export feature allows you to backup, migrate, or share your schema templates.

### Exporting Templates

1. Go to **Swift Rank → Settings → Import/Export**
2. Select the templates you want to export (or select all)
3. Click **Export Templates**
4. A JSON file will be downloaded to your computer

**What's Exported:**
- Template title and content
- Schema type and subtype
- All field values
- Display conditions
- Template status (published/draft)

### Importing Templates

1. Go to **Swift Rank → Settings → Import/Export**
2. Click **Choose File** and select your JSON export file
3. Click **Import Templates**
4. Imported templates will be created as drafts for review

**Important Notes:**
- Imported templates are created as drafts to prevent accidental overwrites
- Review and publish imported templates after verifying they're correct
- Template IDs are not preserved (new IDs are assigned)

### Use Cases

- **Backup**: Export all templates before major changes or updates
- **Migration**: Move templates from staging to production or between sites
- **Sharing**: Share template configurations with team members or clients
- **Version Control**: Keep snapshots of template configurations over time

## Advanced Settings

### Output Settings

- **Code Placement** (Pro): Choose whether to output schema in the `<head>` or `<footer>` section
  - **Head** (Recommended): Schema appears in the `<head>` section (default)
  - **Footer**: Schema appears before the closing `</body>` tag

### Search & Navigation (Pro)

- **Sitelinks Searchbox**: Enable the Sitelinks Searchbox schema to potentially show a search box for your site in Google results
  - Requires a working search function on your site
  - May appear in Google search results for branded queries

## Marketplace

Browse and install extensions and add-ons for Swift Rank.

- **Available Extensions**: View compatible plugins and integrations
- **One-Click Install**: Install extensions directly from the marketplace
- **Updates**: Manage extension updates

## License (Pro Only)

If you have Swift Rank Pro, you can manage your license key here to receive automatic updates and support.

### Activating Your License

1. Go to **Swift Rank → Settings → License**
2. Enter your license key
3. Click **Activate License**

### License Benefits

- Automatic updates for Pro features
- Access to Pro schema types (Recipe, Event, HowTo, Podcast Episode, Custom)
- Priority support
- Advanced features and settings
