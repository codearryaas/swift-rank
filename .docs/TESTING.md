# Schema Master Plugin - Testing Guide

## Quick Test Checklist

### 1. Plugin Activation
```bash
cd "/Users/rakeshlawaju/Local Sites/skynet/app/public"
wp plugin activate schema-master
```

### 2. Test Schema Type Preview (Fix #1)

**Steps**:
1. Go to: WordPress Admin → Schema Templates → Add New
2. Enter a template name (e.g., "Product Template")
3. Click the "Schema Type" dropdown
4. Select "Product"
5. **Expected**: Preview should appear below showing the Product schema structure
6. Change to "Event"
7. **Expected**: Preview updates to show Event schema structure

**What to check**:
- Preview appears after selection
- Preview updates when type changes
- No JavaScript errors in browser console (F12)

### 3. Test Template Conditions (Fix #2)

**Steps**:
1. Still on the template edit screen
2. Scroll down to "Template Conditions" metabox
3. **Expected**: Should see sections for:
   - Post Types (checkboxes: Post, Page, etc.)
   - Categories (checkboxes: Uncategorized, etc.)
   - Tags (checkboxes: if any tags exist)
   - Specific Post IDs (text field)

**What to check**:
- Check "Post" post type
- Check a category (e.g., "Uncategorized")
- Save template (click "Publish")
- Go to Posts → Add New
- Create a post in that category
- **Expected**: Schema Master metabox should appear on post edit screen showing template fields

### 4. Test Variable Replacement (Fix #3)

**Steps**:
1. Edit your template (Schema Templates → Edit)
2. In the schema type fields, use variables:
   - For Product name: `{post_title}`
   - For Product description: `{post_excerpt}`
   - For Product image: `{featured_image}`
3. Save template
4. Edit a post that matches the template
5. Fill in post title, excerpt, set featured image
6. Update/publish post
7. View post on frontend
8. Right-click → View Page Source
9. Search for "application/ld+json"

**What to check**:
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "Actual Post Title Here",  ← Should NOT be {post_title}
  "description": "Actual excerpt here...",  ← Should NOT be {post_excerpt}
  "image": "https://skynet.local/wp-content/uploads/image.jpg"  ← Real URL
}
</script>
```

### 5. Test Custom Meta Fields

**Steps**:
1. Edit template, add a custom field variable: `{meta:price}`
2. Edit matching post
3. Scroll to "Custom Fields" (bottom of editor, may need to enable in Screen Options)
4. Add new field: name=`price`, value=`29.99`
5. Update post
6. View source on frontend
7. **Expected**: JSON-LD should show `"price": "29.99"`, not `{meta:price}`

## Common Issues

### Issue: Schema type preview not showing
**Fix**: Check browser console (F12) for errors. Ensure ajaxurl is defined (it should be automatically in WP admin).

### Issue: Template not appearing on post
**Fix**: 
- Verify template is published (not draft)
- Check template conditions match post (right post type, right category)
- Clear any caches

### Issue: Variables not replaced
**Fix**:
- Ensure variables use single curly braces: `{post_title}` not `{{post_title}}`
- Check spelling matches exactly (case-sensitive)
- For meta fields, use `{meta:field_name}` format

## Testing Command Line

```bash
# Check if plugin is active
wp plugin list --path="/Users/rakeshlawaju/Local Sites/skynet/app/public"

# View schema output from command line
curl -s http://skynet.local/ | grep -A 30 'application/ld+json'

# Or for specific post (replace with your post slug)
curl -s http://skynet.local/your-post-slug/ | grep -A 30 'application/ld+json'
```

## Debug Mode

To see detailed logs, add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Then check: `/Users/rakeshlawaju/Local Sites/skynet/app/public/wp-content/debug.log`

## Success Criteria

✅ Schema type dropdown shows preview  
✅ Template conditions include Categories and Tags  
✅ Variables are replaced with actual values in frontend  
✅ No PHP errors in debug.log  
✅ No JavaScript errors in browser console  
✅ JSON-LD validates at https://validator.schema.org/
