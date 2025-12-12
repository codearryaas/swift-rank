# Schema Engine Settings

The global settings allow you to configure site-wide schema information, primarily for your Knowledge Graph (Organization, Person, or Local Business) that appears on your homepage.

## Accessing Settings

To access the settings, navigate to **Schema Engine → Settings** in your WordPress admin dashboard.

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

- **Default Image**: Set a fallback image to be used when no specific image is available.
- **Auto Schema**: Automatically generate basic schema for pages.

## Advanced Settings

### Output Settings
- **Code Placement** (Pro): Choose whether to output schema in the `<head>` or `<footer>`.
- **Minify JSON-LD**: 
  - **Enabled**: Removes whitespace to reduce page size.
  - **Disabled**: Outputs formatted, readable JSON-LD (useful for debugging).

### Search & Navigation (Pro)
- **Sitelinks Searchbox**: Enable the Sitelinks Searchbox schema to potentially show a search box for your site in Google results.
- **Breadcrumbs**: Enable BreadcrumbList schema to help search engines understand your site structure.
  - **Show Home**: Include the homepage in breadcrumbs.
  - **Home Text**: Custom text for the home link.
  - **Separator**: Character to use as separator.

## License (Pro Only)

If you have Schema Engine Pro, you can manage your license key here to receive automatic updates.
