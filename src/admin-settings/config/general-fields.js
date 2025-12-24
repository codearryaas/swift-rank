import { __ } from '@wordpress/i18n';

/**
 * Field configurations for General Settings tab
 * These fields will be rendered using FieldsBuilder component
 */

const generalFields = [
    // Auto-Schema Settings Section
    {
        name: 'auto_schema_heading',
        label: __('Auto-Enable Schema', 'swift-rank'),
        type: 'heading',
        description: __('Automatically generate schema markup for common content types. Templates will override these settings when present.', 'swift-rank'),
        group: 'auto_schema',
        groupStart: true,
    },
    {
        name: 'auto_schema_post_enabled',
        label: __('Enable for Blog Posts', 'swift-rank'),
        type: 'toggle',
        default: true,
        tooltip: __('Automatically add Article schema to blog posts.', 'swift-rank'),
        description: __('Automatically generates Article schema for all blog posts. This helps search engines understand your content better and may enhance how your posts appear in search results. Custom templates will override this setting.', 'swift-rank'),
        group: 'auto_schema',
    },
    {
        name: 'auto_schema_post_type',
        label: __('Article Type', 'swift-rank'),
        type: 'select',
        options: [
            { label: __('Article - General article content', 'swift-rank'), value: 'Article' },
            { label: __('BlogPosting - Blog posts and informal articles', 'swift-rank'), value: 'BlogPosting' },
            { label: __('NewsArticle - News and current events', 'swift-rank'), value: 'NewsArticle' },
            { label: __('ScholarlyArticle - Academic and research articles', 'swift-rank'), value: 'ScholarlyArticle' },
            { label: __('TechArticle - Technical and how-to articles', 'swift-rank'), value: 'TechArticle' },
        ],
        default: 'Article',
        tooltip: __('Select the specific Article type for your posts.', 'swift-rank'),
        description: __('Choose the Article type that best matches your content. BlogPosting is ideal for informal blog content, NewsArticle for news sites, TechArticle for tutorials and technical guides.', 'swift-rank'),
        parent: 'auto_schema_post_enabled',
        indent: true,
        group: 'auto_schema',
    },
    {
        name: 'auto_schema_page_enabled',
        label: __('Enable for Pages', 'swift-rank'),
        type: 'toggle',
        default: true,
        tooltip: __('Automatically add WebPage schema to pages.', 'swift-rank'),
        description: __('Automatically generates WebPage schema for all WordPress pages. This provides basic structured data for static content like your About, Contact, or Privacy pages.', 'swift-rank'),
        group: 'auto_schema',
    },
    {
        name: 'auto_schema_search_enabled',
        label: __('Enable for Search Results', 'swift-rank'),
        type: 'toggle',
        default: true,
        tooltip: __('Automatically add SearchResultsPage schema to search results.', 'swift-rank'),
        description: __('Adds SearchResultsPage schema to your site\'s search results pages, helping search engines identify them correctly.', 'swift-rank'),
        group: 'auto_schema',
    },
    {
        name: 'auto_schema_woocommerce_enabled',
        label: __('Enable for WooCommerce Products', 'swift-rank'),
        type: 'toggle',
        default: true,
        tooltip: __('Automatically add Product schema to WooCommerce products.', 'swift-rank'),
        description: __('Automatically generates Product schema for WooCommerce products, including price, availability, and ratings. This can enable rich product snippets in search results.', 'swift-rank'),
        condition: () => {
            // Check if WooCommerce is active
            return typeof window.swiftRankSettings !== 'undefined' &&
                window.swiftRankSettings.isWooCommerceActive;
        },
        group: 'auto_schema',
    },
    {
        name: 'sitelinks_searchbox',
        label: __('Enable Google Sitelinks Searchbox', 'swift-rank'),
        type: 'toggle',
        default: false,
        tooltip: __('Add WebSite schema with SearchAction to enable the search box in Google sitelinks.', 'swift-rank'),
        description: __('Enables the Google sitelinks search box feature, allowing users to search your site directly from Google search results. This appears below your site name in Google.', 'swift-rank'),
        isPro: true,
        group: 'auto_schema',
        groupEnd: true,
    },
    {
        name: 'default_image',
        label: __('Default Fallback Image', 'swift-rank'),
        type: 'image',
        default: '',
        tooltip: __('Fallback image for schema when no featured image is set.', 'swift-rank'),
        description: __('Upload a default image to use in schema markup when posts or pages don\'t have a featured image. Google recommends images be at least 1200px wide for best results.', 'swift-rank'),
        isPro: true,
    },
    {
        name: 'auto_image_schema_enabled',
        label: __('Auto Image Schema', 'swift-rank'),
        type: 'toggle',
        default: false,
        tooltip: __('Automatically generate ImageObject schema from content images.', 'swift-rank'),
        description: __('Automatically creates ImageObject schema markup for images found in your post content. Extracts images from Gutenberg image blocks or IMG tags and generates structured data with dimensions, captions, and alt text.', 'swift-rank'),
        isPro: true,
    },
    {
        name: 'code_placement',
        label: __('Schema Code Location', 'swift-rank'),
        type: 'select',
        options: [
            { label: __('Head - In the <head> section (Recommended)', 'swift-rank'), value: 'head' },
            { label: __('Footer - Before closing </body> tag', 'swift-rank'), value: 'footer' },
        ],
        default: 'head',
        tooltip: __('Choose where to output the schema JSON-LD code.', 'swift-rank'),
        description: __('Controls where schema markup appears in your HTML. The <head> section is recommended and most commonly used by major sites.', 'swift-rank'),
        isPro: true,
    },
    {
        name: 'minify_schema',
        label: __('Minify Schema Output', 'swift-rank'),
        type: 'toggle',
        default: true,
        tooltip: __('Remove whitespace from schema output to reduce file size.', 'swift-rank'),
        description: __('Removes unnecessary whitespace from schema JSON-LD output to reduce HTML file size. Disable this if you need to read the schema in your page source for debugging.', 'swift-rank'),
    },
    {
        name: 'disable_yoast_schema',
        label: __('Disable Yoast SEO Schema', 'swift-rank'),
        type: 'toggle',
        default: false,
        tooltip: __('Disable Yoast SEO\'s JSON-LD schema output.', 'swift-rank'),
        description: __('Prevents Yoast SEO from outputting its own schema markup. Use this if you want Swift Rank to be the sole provider of schema on your site to avoid conflicts.', 'swift-rank'),
        condition: () => {
            return typeof window.swiftRankSettings !== 'undefined' &&
                window.swiftRankSettings.isYoastActive;
        },
    },
    {
        name: 'disable_aioseo_schema',
        label: __('Disable All in One SEO Schema', 'swift-rank'),
        type: 'toggle',
        default: false,
        tooltip: __('Disable All in One SEO\'s JSON-LD schema output.', 'swift-rank'),
        description: __('Prevents All in One SEO from outputting its own schema markup. Use this if you want Swift Rank to be the sole provider of schema on your site to avoid conflicts.', 'swift-rank'),
        condition: () => {
            return typeof window.swiftRankSettings !== 'undefined' &&
                window.swiftRankSettings.isAioseoActive;
        },
    },
    {
        name: 'disable_rankmath_schema',
        label: __('Disable Rank Math Schema', 'swift-rank'),
        type: 'toggle',
        default: false,
        tooltip: __('Disable Rank Math\'s JSON-LD schema output.', 'swift-rank'),
        description: __('Prevents Rank Math from outputting its own schema markup. Use this if you want Swift Rank to be the sole provider of schema on your site to avoid conflicts.', 'swift-rank'),
        condition: () => {
            return typeof window.swiftRankSettings !== 'undefined' &&
                window.swiftRankSettings.isRankMathActive;
        },
    },
    {
        name: 'setup_wizard_heading',
        label: __('Setup Wizard', 'swift-rank'),
        type: 'heading',
        description: __('Need to change your initial configuration? Run the setup wizard again to quickly reconfigure your schema settings.', 'swift-rank'),
        group: 'setup_wizard',
        groupStart: true,
    },
    {
        name: 'wizard_reconfigure',
        type: 'button',
        buttonLabel: __('Run Setup Wizard', 'swift-rank'),
        buttonClass: 'button-secondary',
        description: __('Launch the guided setup wizard to reconfigure your schema settings, social profiles, and knowledge base information.', 'swift-rank'),
        group: 'setup_wizard',
        groupEnd: true,
        onClick: () => {
            window.location.href = 'admin.php?page=swift-rank-setup';
        },
    },
];

export default generalFields;
