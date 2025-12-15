# Article Schema Setup Guide

Article schema is one of the most important schema types for content-driven websites. It helps search engines understand your blog posts, news articles, and editorial content, making you eligible for rich results in Google Search.

## What is Article Schema?

Article schema (`@type: Article`) tells search engines about written content on your website. It includes information about the article's headline, author, publish date, images, and more.

**Benefits:**
- Eligibility for Article rich results
- Enhanced appearance in search results
- Better content understanding by search engines
- Potential for Top Stories carousel
- Author attribution in search results

**Best For:**
- Blog posts
- News articles
- Editorial content
- Tutorials and guides
- Case studies
- Opinion pieces

## Article Types

Swift Rank supports several Article subtypes:

### Article (Generic)
**Use for:** General articles, editorials, opinion pieces

**Best when:** Content doesn't fit other specific types

### BlogPosting
**Use for:** Blog posts, personal blog content

**Best when:** Content is from a blog section of your site

**Most common for:** WordPress "Posts" content type

### NewsArticle
**Use for:** News stories, current events

**Best when:** Your site is a news publication

**Requirements:** Time-sensitive news content

### TechArticle
**Use for:** Technical documentation, how-to guides

**Best when:** Content includes technical instructions

**Requirements:** Technical or instructional content

### ScholarlyArticle
**Use for:** Academic papers, research articles

**Best when:** Peer-reviewed or academic content

**Requirements:** Scholarly/academic context

**Recommendation:** For most WordPress blogs, use **BlogPosting**. For general content sites, use **Article**.

## Required vs Optional Fields

### Required Fields (Must Have)

These fields are required by Google for Article rich results:

**Headline** *
- The article title
- Maximum: 110 characters
- Recommended: `{post_title}`

**Image** *
- Article's main image
- Minimum: 696px wide
- Recommended: 1200x630px or larger
- Recommended: `{featured_image}`

**Date Published** *
- When the article was first published
- Format: ISO 8601
- Recommended: `{post_date}`

**Date Modified** *
- When the article was last updated
- Format: ISO 8601
- Recommended: `{post_modified}`

### Recommended Fields

These fields enhance your schema and may improve rich results:

**Description**
- Article summary or excerpt
- 50-160 characters ideal
- Recommended: `{post_excerpt}`

**Author**
- Person or Organization who wrote the article
- Recommended: `{author_name}`

**Publisher**
- Organization that published the article
- Auto-populated from Settings → Knowledge Graph

## Step-by-Step Setup

### Step 1: Create Article Template

1. Navigate to **Swift Rank → Add New**
2. Title: "Article Schema - Blog Posts"
3. Don't publish yet

### Step 2: Select Article Type

In the **Swift Rank** panel:

1. **Schema Type** dropdown → Select **Article**
2. Panel refreshes with Article fields

### Step 3: Configure Article Subtype

**Article Type** dropdown:
- Select **BlogPosting** (for blog posts)
- Or **Article** (for general articles)
- Or other subtype as appropriate

### Step 4: Configure Required Fields

**Headline** *
```
Field value: {post_title}
```
- Uses post title automatically
- Leave as default

**Description**
```
Field value: {post_excerpt}
```
- Uses post excerpt
- Auto-generates if no excerpt set
- Leave as default

**Image** *
```
Field value: {featured_image}
```
- Uses featured image
- Ensure posts have featured images set
- Set fallback image in Settings → Default Image

**Author Name**
```
Field value: {author_name}
```
- Automatically uses post author
- Leave as default

**Publisher**
- Auto-populated from Settings
- No action needed
- Ensure Settings → Knowledge Graph is configured

**Date Published** *
```
Field value: {post_date}
```
- Automatically uses publish date
- ISO 8601 format
- Leave as default

**Date Modified** *
```
Field value: {post_modified}
```
- Automatically uses last modified date
- Updates when post is edited
- Leave as default

### Step 5: Set Display Conditions

Scroll to **Display Conditions**:

**For Blog Posts:**
```
Condition Group 1:
└── Post Type = Posts
```

**For Multiple Post Types:**
```
Condition Group 1:
└── Post Type = Posts, Pages
```

**For Specific Category:**
```
Condition Group 1:
├── Post Type = Posts
└── (Select specific posts from that category via Singular)
```

### Step 6: Publish Template

1. Review all fields
2. Click **Publish**
3. Template is now active

### Step 7: Test Your Schema

1. Visit a blog post (frontend)
2. Admin bar → **Swift Rank** → **Google Rich Results Test**
3. Verify:
   - ✓ Article detected
   - ✓ No errors
   - All required fields present

## Field Configuration Reference

### Headline Field

**Purpose:** The article's main title

**Format:** Plain text, max 110 characters

**Recommended Value:**
```
{post_title}
```

**Alternative Values:**
```
{post_title} | {site_name}                    (with site name)
{post_title}                                   (simple, recommended)
```

**Google Requirements:**
- Required field
- Max 110 characters (truncated after)
- Should match visible page title

**Common Mistakes:**
- ✗ Using full post content
- ✗ Adding extra text manually
- ✓ Use {post_title} variable

### Image Field

**Purpose:** Main article image

**Format:** Absolute URL to image file

**Recommended Value:**
```
{featured_image}
```

**Google Requirements:**
- Required field
- Minimum width: 696px
- Recommended: 1200x630px or larger
- High resolution, sharp, and relevant
- Marked up with absolute URL
- Crawlable and indexable

**Image Best Practices:**
- Use high-quality images
- Avoid logos or text-heavy images
- Use actual article images, not generic stock
- Set featured image on all posts
- Configure fallback in Settings

**Fallback Strategy:**
```
1. Featured image on post
2. First image in post content
3. Default image from Settings
```

### Description Field

**Purpose:** Article summary

**Format:** Plain text, 50-160 characters ideal

**Recommended Value:**
```
{post_excerpt}
```

**Alternative Values:**
```
{post_excerpt}                                 (auto-generated if empty)
Manual description                             (same for all posts)
{post_excerpt} | By {author_name}              (with attribution)
```

**Google Requirements:**
- Not strictly required but recommended
- Used in search snippets
- 50-160 characters optimal

**Fallback:**
- If no excerpt: Auto-generates from content
- First 55 words of post content

### Author Field

**Purpose:** Who wrote the article

**Format:** Person name or reference

**Recommended Value:**
```
{author_name}
```

**Alternative Values:**
```
{author_name}                                  (post author)
Static name                                    (same author for all)
{site_name}                                    (organization as author)
```

**Schema Structure:**
```json
"author": {
  "@type": "Person",
  "name": "{author_name}",
  "url": "{author_url}"
}
```

**Best Practices:**
- Use real author names
- Ensure author profiles are complete
- Use Person type for individuals
- Use Organization for company-authored content

### Publisher Field

**Purpose:** Organization that published the article

**Format:** Organization reference

**Configuration:**
- Auto-populated from Settings → Knowledge Graph
- No manual configuration needed

**Requirements:**
```
Settings → Knowledge Graph:
- Entity Type: Organization (or Person)
- Name: Your organization name
- Logo: High-quality logo (600x60px minimum)
```

**Schema Structure:**
```json
"publisher": {
  "@type": "Organization",
  "name": "Your Company Name",
  "logo": {
    "@type": "ImageObject",
    "url": "https://example.com/logo.png"
  }
}
```

**Important:**
- Configure Knowledge Graph before creating Article schema
- Logo is required by Google
- Same publisher across all articles

### Date Published Field

**Purpose:** Original publication date

**Format:** ISO 8601 (YYYY-MM-DDTHH:MM:SS+00:00)

**Recommended Value:**
```
{post_date}
```

**Auto-formatting:**
- Variable automatically formats to ISO 8601
- Includes timezone offset
- No manual formatting needed

**Example Output:**
```
2025-01-15T10:30:00+00:00
```

**Google Requirements:**
- Required field
- Must be ISO 8601 format
- Should match actual publish date
- Don't fake dates to appear newer

### Date Modified Field

**Purpose:** Last significant update date

**Format:** ISO 8601

**Recommended Value:**
```
{post_modified}
```

**Behavior:**
- Updates automatically when post is edited
- Stays same as datePublished until first edit
- Important for showing freshness

**Google Requirements:**
- Required field
- Must be same or after datePublished
- Should reflect actual content updates

## Complete Example

Here's a complete Article schema template configuration:

**Template Name:** "Article Schema - Blog Posts"

**Schema Type:** Article

**Fields:**
```
Article Type: BlogPosting
Headline: {post_title}
Description: {post_excerpt}
Image: {featured_image}
Author Name: {author_name}
Publisher: (Auto from Settings)
Date Published: {post_date}
Date Modified: {post_modified}
```

**Display Conditions:**
```
Group 1:
└── Post Type = Posts
```

**Expected Output:**
```json
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "@id": "https://example.com/my-post#article",
  "headline": "10 SEO Tips for 2025",
  "description": "Learn the latest SEO strategies that actually work in 2025",
  "image": "https://example.com/wp-content/uploads/2025/01/seo-tips.jpg",
  "datePublished": "2025-01-15T09:00:00+00:00",
  "dateModified": "2025-01-16T14:30:00+00:00",
  "author": {
    "@type": "Person",
    "name": "Sarah Johnson",
    "@id": "https://example.com/author/sarah#person"
  },
  "publisher": {
    "@type": "Organization",
    "name": "My SEO Blog",
    "@id": "https://example.com#organization",
    "logo": {
      "@type": "ImageObject",
      "url": "https://example.com/logo.png"
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://example.com/my-post"
  }
}
```

## Advanced Configurations

### Multiple Article Types

**Scenario:** Different schema for blog posts vs. news articles

**Solution:** Create separate templates

**Template 1: Blog Posts**
```
Title: "Article - Blog Posts"
Article Type: BlogPosting
Conditions: Post Type = Posts
```

**Template 2: News Articles**
```
Title: "Article - News"
Article Type: NewsArticle
Conditions: Category = News
```

### Organization as Author

**Scenario:** Company-authored articles (not individual authors)

**Solution:** Use site name as author

**Configuration:**
```
Author Name: {site_name}
```

**Result:**
```json
"author": {
  "@type": "Organization",
  "name": "My Company"
}
```

### Custom Descriptions

**Scenario:** Custom meta description different from excerpt

**Solution:** Use custom meta field

**Configuration:**
```
Description: {meta:custom_description}
```

**Fallback:** Ensure custom field exists on posts

## Common Issues

### Issue: "Missing field 'image'"

**Cause:** Post has no featured image

**Solutions:**
1. Add featured images to all posts
2. Set default image in Settings
3. Use first content image as fallback (automatic)

### Issue: "Headline exceeds 110 characters"

**Cause:** Post title is too long

**Solutions:**
1. Shorten post titles
2. Custom headline field (advanced)
3. Accept warning (won't prevent indexing)

### Issue: Author shows wrong name

**Cause:** Variable pulling from WordPress author

**Solutions:**
1. Update WordPress author profile
2. Use custom author field
3. Verify author settings in WordPress Users

### Issue: Publisher logo missing

**Cause:** Knowledge Graph not configured

**Solutions:**
1. Go to Settings → Knowledge Graph
2. Select Entity Type (Organization)
3. Upload logo (600x60px minimum)
4. Save settings

### Issue: Schema appears on pages too

**Cause:** Display conditions include Pages

**Solutions:**
1. Review display conditions
2. Change from "Posts, Pages" to just "Posts"
3. Or create separate template for Pages

## Testing Checklist

Before marking Article schema complete:

- [ ] Template published
- [ ] All required fields filled (headline, image, dates)
- [ ] Author configured
- [ ] Publisher configured (Settings)
- [ ] Display conditions set correctly
- [ ] Tested on sample blog post
- [ ] Google Rich Results Test passes
- [ ] No errors, warnings acceptable
- [ ] Image is high quality (1200x630+)
- [ ] Dates in correct format
- [ ] Headline under 110 characters

## Rich Results Eligibility

After implementing Article schema:

**What to Expect:**
- Eligibility for Article rich results
- Enhanced search listings
- Author attribution
- Published/modified dates
- Featured image may appear

**Timeline:**
- Schema detected: Immediately
- Rich results: 1-2 weeks after indexing
- Not guaranteed: Google chooses when to show

**Check Status:**
- Google Search Console → Enhancements → Articles
- URL Inspection tool for specific pages

## Best Practices

1. **Always Set Featured Images:** Required for image field
2. **Write Good Excerpts:** Used in description and search snippets
3. **Configure Publisher:** Required in Settings
4. **Use Default Variables:** {post_title}, {post_date}, etc.
5. **Test Before Publishing:** Validate with Google's tool
6. **One Template Per Post Type:** Keep it simple
7. **Update When Content Changes:** Keep schema accurate
8. **Monitor Search Console:** Check for errors regularly

## Next Steps

- **Learn Organization Schema:** [Organization Schema Setup](organization.md)
- **Add FAQ Schema:** [FAQ Schema Setup](faq.md)
- **Master Variables:** [Dynamic Variables](../dynamic-variables.md)
- **Test Your Schema:** [Testing Schema](../testing-schema.md)

Article schema is foundational for content sites. Set it up correctly once, and it automatically enhances all your articles!
