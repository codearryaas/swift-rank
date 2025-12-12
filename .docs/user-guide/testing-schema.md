# Testing Your Schema

Testing and validating your schema markup is crucial for ensuring search engines can understand your content correctly. This guide covers everything you need to test, validate, and debug your schema.

## Why Test Schema?

**Prevent Errors:**
- Invalid schema won't generate rich results
- Errors can confuse search engines
- Warnings may reduce effectiveness

**Ensure Quality:**
- All required fields are present
- Data is in the correct format
- Schema matches your content

**Improve Results:**
- Validated schema is more likely to appear as rich results
- Proper schema improves search visibility
- Quality schema builds trust with search engines

## Quick Validation Tools

Schema Engine includes built-in validation shortcuts in the WordPress admin bar.

### Admin Bar Validator (Frontend Only)

When logged in and viewing your site frontend, look for the **Schema Engine** menu in the admin bar at the top of the page.

**Available Tools:**
1. **Google Rich Results Test**
2. **Schema.org Validator**

**How to Use:**

1. Navigate to any page on your site (frontend)
2. Look at the admin bar (top of page)
3. Hover over **Schema Engine**
4. Click **Google Rich Results Test** or **Schema.org Validator**
5. Tool opens in new tab with your page pre-loaded
6. Review results

**What Each Tool Does:**

**Google Rich Results Test:**
- Tests which rich results your page is eligible for
- Shows preview of how it might appear in search
- Reports errors and warnings
- Google's official validation tool

**Schema.org Validator:**
- Validates against schema.org specifications
- Checks schema structure and syntax
- Reports technical errors
- More technical than Google's tool

## Manual Testing Methods

### Method 1: View Page Source

**Steps:**
1. Visit a page with schema
2. Right-click → **View Page Source** (or Ctrl+U / Cmd+U)
3. Search for `application/ld+json` (Ctrl+F / Cmd+F)
4. Review the JSON-LD schema code

**What to Look For:**
- Schema appears in `<script type="application/ld+json">` tags
- JSON is properly formatted
- No syntax errors (missing commas, brackets)
- Required fields are present
- URLs are absolute (not relative)

**Example Valid Schema:**
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "@id": "https://example.com/post#article",
  "headline": "My Blog Post",
  "description": "This is a description",
  "image": "https://example.com/image.jpg",
  "datePublished": "2025-01-15T10:00:00+00:00",
  "author": {
    "@type": "Person",
    "name": "John Doe"
  }
}
</script>
```

### Method 2: Browser DevTools

**Steps:**
1. Right-click on page → **Inspect** (or F12)
2. Go to **Console** tab
3. Run this JavaScript:

```javascript
// Get all JSON-LD scripts
const scripts = document.querySelectorAll('script[type="application/ld+json"]');

// Parse and display each
scripts.forEach((script, index) => {
  console.log(`Schema ${index + 1}:`, JSON.parse(script.textContent));
});
```

**Benefits:**
- See parsed JSON (easier to read)
- Quickly check if schema exists
- Validate JSON syntax

### Method 3: Copy and Paste Validation

**Steps:**
1. View page source
2. Find and copy the JSON-LD content (between `<script>` tags)
3. Go to https://validator.schema.org/
4. Select **Code Snippet** tab
5. Paste your JSON
6. Click **Run Test**

**Benefits:**
- Offline validation possible
- Test before publishing
- Catch syntax errors early

## Google Rich Results Test

Google's official tool for testing structured data.

**URL:** https://search.google.com/test/rich-results

### How to Use

**Method 1: Test Live URL**
1. Go to Google Rich Results Test
2. Enter your page URL
3. Click **Test URL**
4. Wait for results (10-30 seconds)

**Method 2: Test Code**
1. Go to Google Rich Results Test
2. Click **Code** tab
3. Paste your page HTML or just the JSON-LD
4. Click **Test Code**

### Understanding Results

**Valid Schema:**
```
✓ Page is eligible for rich results
✓ No errors detected
⚠ X warnings found
```

**Errors (Red):**
- Critical issues that prevent rich results
- Must be fixed for schema to work
- Common: Missing required fields

**Warnings (Yellow):**
- Non-critical issues
- Recommended to fix but not required
- May improve rich result appearance

**Items Detected:**
```
Article
├── ✓ Headline
├── ✓ Image
├── ✓ Date published
└── ⚠ Author (recommended)
```

### Common Google Errors

**"Missing field 'image'"**
- Cause: No image URL provided
- Fix: Add `{featured_image}` variable or upload image

**"Invalid URL"**
- Cause: Relative URL instead of absolute
- Fix: Use full URLs (https://example.com/...)

**"Invalid date format"**
- Cause: Wrong date format
- Fix: Use ISO 8601 format (use `{post_date}` variable)

**"Missing required field"**
- Cause: Required field is empty
- Fix: Fill required fields (marked with *)

**"The field headline exceeds 110 characters"**
- Cause: Headline too long
- Fix: Shorten headline or trim in template

## Schema.org Validator

Technical validation against official schema.org specifications.

**URL:** https://validator.schema.org/

### How to Use

**Method 1: URL**
1. Select **Fetch URL** tab
2. Enter your page URL
3. Click **Run Test**

**Method 2: Code Snippet**
1. Select **Code Snippet** tab
2. Paste JSON-LD code
3. Click **Run Test**

### Understanding Results

**No Errors:**
```
✓ No errors found
```

**Errors Found:**
```
✗ Property 'invalid_field' not recognized
✗ Value 'abc' for field 'price' is not numeric
```

**Key Differences from Google:**
- More technical/strict
- Validates against schema.org spec
- Doesn't show rich result previews
- Catches structural issues Google might ignore

## Testing Workflow

### For New Templates

**1. Create Template (Draft)**
```
- Set up schema type and fields
- Configure display conditions
- Save as Draft
```

**2. Preview Test**
```
- View a page that should match conditions
- View source → Check schema appears
- Copy JSON for validation
```

**3. Validate Code**
```
- Paste into Google Rich Results Test (Code tab)
- Fix any errors
- Test until no errors
```

**4. Test Live**
```
- Publish template
- View matching page on frontend
- Use admin bar → Google Rich Results Test (URL)
- Verify rich result eligibility
```

**5. Test Multiple Pages**
```
- Test different posts/pages
- Ensure variables populate correctly
- Check conditions work as expected
```

**6. Final Check**
```
- Schema.org Validator for technical validation
- Google Search Console (after indexing)
```

### For Existing Templates

**Regular Maintenance:**

**Monthly:**
- Spot-check random pages
- Verify schema still appears
- Check for new warnings

**After Updates:**
- Test affected templates
- Verify fields still populate
- Check for breaking changes

**Before Major Changes:**
- Document current working state
- Test changes in staging
- Validate before deploying

## Testing Checklist

### Pre-Launch Checklist

- [ ] Template published (not draft)
- [ ] All required fields filled
- [ ] Variables used for dynamic content
- [ ] Display conditions configured
- [ ] Tested on sample pages
- [ ] No errors in Google Rich Results Test
- [ ] No errors in Schema.org Validator
- [ ] Schema appears in page source
- [ ] Images are high quality (1200x630+)
- [ ] URLs are absolute (not relative)
- [ ] Dates in ISO 8601 format
- [ ] No placeholder text ("TODO", "example")

### Post-Launch Checklist

- [ ] Schema appears on live site
- [ ] Admin bar validation links work
- [ ] No JavaScript console errors
- [ ] Schema validates on multiple pages
- [ ] Different post types tested
- [ ] Edge cases tested (no image, no excerpt, etc.)
- [ ] Cache cleared (if caching plugin active)
- [ ] Mobile version tested

## Common Issues and Solutions

### Issue 1: Schema Not Appearing

**Symptoms:**
- No JSON-LD in page source
- Validation tools show no schema

**Possible Causes:**
1. Template is Draft (not Published)
2. Display conditions don't match page
3. JavaScript error blocking output
4. Caching plugin showing old version

**Solutions:**
```
1. Check template status → Publish
2. Review display conditions
3. Check browser console for errors
4. Clear all caches (plugin, browser, CDN)
5. Test in incognito/private mode
```

### Issue 2: Missing Required Fields

**Symptoms:**
- Google shows "Missing field" error
- Red errors in validation

**Possible Causes:**
1. Field left empty in template
2. Variable has no value (no featured image, etc.)
3. Field removed from output due to being empty

**Solutions:**
```
1. Fill all required fields (marked with *)
2. Ensure posts have required data (featured image, excerpt)
3. Provide fallback values
4. Set default image in Settings
```

### Issue 3: Invalid Date Format

**Symptoms:**
- "Invalid date" error in validators
- Dates appear as regular text

**Possible Causes:**
1. Using wrong date format
2. Manual date entry instead of variable
3. Date variable not working

**Solutions:**
```
1. Use date variables: {post_date}, {post_modified}
2. Don't enter dates manually
3. Variables auto-format to ISO 8601
```

### Issue 4: Relative URLs

**Symptoms:**
- "Invalid URL" errors
- URLs like "/image.jpg" instead of "https://..."

**Possible Causes:**
1. Manually entered relative URLs
2. Image URL variable returning relative path
3. Plugin conflict

**Solutions:**
```
1. Use full URLs: https://example.com/image.jpg
2. Use {featured_image} variable (auto-generates full URL)
3. Check if other plugins modify image URLs
```

### Issue 5: Duplicate Schemas

**Symptoms:**
- Two Article schemas on same page
- Conflicting data

**Possible Causes:**
1. Multiple templates matching same page
2. Other plugin outputting schema
3. Theme adding schema

**Solutions:**
```
1. Review all template conditions
2. Make conditions mutually exclusive
3. Disable schema in other plugins/theme
4. Check page source for all JSON-LD blocks
```

### Issue 6: Variables Not Replacing

**Symptoms:**
- Schema shows {post_title} literally
- Variables appear as text

**Possible Causes:**
1. Typo in variable name
2. Invalid variable
3. Post has no value for that field

**Solutions:**
```
1. Check spelling: {post_title} not {posttitle}
2. Use variable picker to insert correctly
3. Add data to post (excerpt, featured image)
4. Check variable documentation
```

## Advanced Testing

### Testing with Different Data

**Test Pages With:**
- Featured image vs. no featured image
- Excerpt vs. no excerpt
- Long title vs. short title
- Multiple authors
- Custom fields populated vs. empty

**Why:** Ensures variables handle missing data gracefully.

### Testing Edge Cases

**Test On:**
- Very old posts (date format changes)
- Posts with special characters in title
- Posts with multiple categories
- Posts with no author (deleted user)
- Draft vs. published posts

### Performance Testing

**Check:**
- Page load time (before/after schema)
- Number of database queries
- Memory usage
- Time to first byte (TTFB)

**Tools:**
- WordPress Query Monitor plugin
- Browser DevTools → Network tab
- GTmetrix / Pingdom

**Schema Impact:**
- Should be minimal (<0.1s)
- JSON-LD is lightweight
- No external requests

### Testing in Google Search Console

After your pages are indexed by Google:

**1. Access Search Console**
```
https://search.google.com/search-console
```

**2. Navigate to Enhancements**
```
Enhancements → [Schema Type]
```

**3. Review Reports**
```
- Valid items (green)
- Warnings (yellow)
- Errors (red)
```

**4. Test Live URL**
```
URL Inspection → Test Live URL
→ View tested page → More info
```

**Note:** Takes 1-2 weeks after publishing for data to appear.

## Validation Best Practices

### Before Publishing

1. **Test in draft mode**
2. **Validate code snippet** (not live URL)
3. **Fix all errors**
4. **Address critical warnings**
5. **Test on multiple page types**

### After Publishing

1. **Test live URL** in Google Rich Results Test
2. **Verify on different pages**
3. **Check admin bar shortcuts work**
4. **Monitor Google Search Console**
5. **Set up regular checks**

### Ongoing Maintenance

1. **Monthly validation** of key pages
2. **After WordPress updates**
3. **After plugin updates**
4. **After theme changes**
5. **When adding new templates**

## Testing Tools Summary

| Tool | URL | Purpose | Best For |
|------|-----|---------|----------|
| Google Rich Results Test | search.google.com/test/rich-results | Rich result eligibility | Pre-launch validation |
| Schema.org Validator | validator.schema.org | Technical validation | Technical accuracy |
| Admin Bar Shortcuts | (On your site, admin bar) | Quick validation | Regular checks |
| Google Search Console | search.google.com/search-console | Production monitoring | Post-index monitoring |
| JSON-LD Playground | json-ld.org/playground | JSON-LD syntax | Development testing |

## Quick Reference

### Valid Schema Checklist

✓ All required fields present
✓ No syntax errors
✓ URLs are absolute
✓ Dates in ISO 8601 format
✓ Images are high quality
✓ No placeholder text
✓ Variables replaced correctly
✓ Schema appears in page source
✓ Google Rich Results Test passes
✓ Schema.org Validator passes

### Testing Frequency

**New Templates:** Before publishing
**Existing Templates:** Monthly spot-checks
**After Updates:** Immediately
**Major Changes:** Staging → Production
**Critical Pages:** Weekly

### When to Re-test

- Created new template
- Modified existing template
- WordPress core update
- Plugin update
- Theme change
- New content type added
- Schema errors reported
- Rich results disappeared

## Getting Help

If you encounter persistent validation errors:

1. **Check Documentation:** Review user guides
2. **Search Console:** Check for specific error messages
3. **Community:** WordPress forums, schema.org community
4. **Support:** Contact Schema Engine support (Pro users)
5. **Google Help:** Google's structured data documentation

## Next Steps

Now that you know how to test your schema:

- **Learn Specific Types:** [Article Schema](schema-types/article.md)
- **Master Variables:** [Dynamic Variables](dynamic-variables.md)
- **Refine Conditions:** [Display Conditions](display-conditions.md)

**Remember:** Valid schema is the foundation of rich results. Test early, test often, and fix errors before they impact your search visibility!
