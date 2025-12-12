# Debug Instructions: Review & JobPosting Not Showing in Frontend

## Quick Diagnosis

I've verified that both schemas are properly registered and NOT restricted to Pro. Here's how to debug what's happening:

---

## Step 1: Run the Debug Script

Access this URL in your browser:
```
http://yoursite.local/wp-content/plugins/schema-engine/debug-schema-output.php
```

This will show you:
- ✅ If classes are loaded
- ✅ If schemas are registered in output handler
- ✅ If they're restricted to Pro (they're not!)
- ✅ If you can build schemas successfully
- ✅ How many templates you have created
- ✅ What's blocking the output

---

## Step 2: Common Issues & Solutions

### Issue 1: No Templates Created

**Symptom:** Debug script shows "0 Review templates" and "0 JobPosting templates"

**Solution:**
1. Go to WordPress Admin → **Schema Templates** → **Add New**
2. Click the schema type dropdown
3. Select **"Review"** or **"Job Posting"**
4. Fill in the fields (use template variables like `{post_title}`)
5. Set conditions (e.g., "Post Type = Post")
6. Click **Publish** (not Save Draft!)

### Issue 2: Templates Not Published

**Symptom:** Templates exist but are in Draft status

**Solution:**
1. Go to WordPress Admin → **Schema Templates**
2. Find your Review/JobPosting templates
3. Click Edit
4. Click **Publish** button
5. Verify status changes from "Draft" to "Published"

### Issue 3: Conditions Don't Match

**Symptom:** Template exists and is published, but doesn't show on posts

**Solution:**
1. Edit your template
2. Go to the **Conditions** tab
3. Make sure conditions match your test post:
   - If testing on regular posts: Set "Post Type = Post"
   - If testing on pages: Set "Post Type = Page"
   - If testing on custom posts: Set appropriate post type
4. Update template
5. Refresh your test post

### Issue 4: Caching

**Symptom:** Everything looks correct but schema still doesn't appear

**Solution:**
1. Clear any WordPress caching plugins (WP Rocket, W3 Total Cache, etc.)
2. Clear browser cache (Ctrl+Shift+R / Cmd+Shift+R)
3. If using LocalWP, restart the site
4. Try viewing in an incognito/private window

---

## Step 3: Verify Frontend Output

### Check Page Source

1. Go to a post that should have the schema
2. Right-click → **View Page Source** (or Ctrl/Cmd + U)
3. Search for: `application/ld+json`
4. You should see something like:

```html
<script type="application/ld+json" class="schema-engine">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Review",
      "@id": "...",
      "itemReviewed": {
        "@type": "Product",
        "name": "..."
      },
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "4.5"
      }
    }
  ]
}
</script>
```

### If Schema Doesn't Appear

Check these in order:

1. **Template is published?** (not draft)
2. **Conditions match?** (post type, category, etc.)
3. **Required fields filled?** (schema may fail if missing required fields)
4. **Any PHP errors?** (check WordPress debug log)
5. **Pro restriction?** (run debug script to confirm - should show as FREE)

---

## Step 4: Test With Article Schema (Known Working)

To verify the system works, test with Article schema first:

1. Create an Article template
2. Set condition: "Post Type = Post"
3. Publish template
4. View any post
5. Check page source for `"@type": "Article"`

**If Article shows but Review/JobPosting don't:**
- Check if Review/JobPosting templates are published
- Check if conditions match
- Check required fields are filled

**If Article doesn't show either:**
- There's a broader issue with schema output
- Check if any other plugin is blocking output
- Check if theme is removing wp_head/wp_footer hooks

---

## Step 5: Specific Checks

### For Review Schema

**Required Fields:**
- Item Type (e.g., Product)
- Item Name
- Author Name
- Rating Value (e.g., 4.5)

**Template Variables You Can Use:**
- `{post_title}` → Post title
- `{author_name}` → Post author
- `{post_content}` → Post content (for review body)
- `{featured_image}` → Featured image (for item image)

**Example Template:**
```
Item Type: Product
Item Name: {post_title}
Author Name: {author_name}
Rating Value: 4.5
Review Body: {post_content}
Date Published: {post_date}
```

### For JobPosting Schema

**Required Fields:**
- Job Title
- Job Description
- Date Posted
- Hiring Organization Name
- Job Location (at least one)

**Template Variables You Can Use:**
- `{post_title}` → Job title
- `{post_content}` → Job description
- `{post_date}` → Posted date
- `{site_name}` → Company name

**Example Template:**
```
Job Title: {post_title}
Job Description: {post_content}
Date Posted: {post_date}
Hiring Organization: {site_name}
Job Location:
  - City: San Francisco
  - State: CA
  - Country: US
```

---

## Step 6: Enable WordPress Debug (If Needed)

If you're still having issues, enable debug mode:

1. Edit `wp-config.php`
2. Find `define('WP_DEBUG', false);`
3. Change to:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   define('WP_DEBUG_DISPLAY', false);
   ```
4. Check `/wp-content/debug.log` for errors
5. Look for errors related to Schema_Review or Schema_Job_Posting

---

## Quick Checklist

Run through this checklist:

- [ ] Ran debug script at `debug-schema-output.php`
- [ ] Debug script shows Review and JobPosting are registered
- [ ] Debug script shows they are FREE (not Pro-restricted)
- [ ] Debug script shows schemas can be built successfully
- [ ] Created at least one Review or JobPosting template
- [ ] Template is **Published** (not Draft)
- [ ] Template conditions match a test post
- [ ] All required fields are filled in the template
- [ ] Visited the test post on frontend (not in admin)
- [ ] Checked page source for `application/ld+json`
- [ ] Cleared all caches
- [ ] Tested in incognito/private window

---

## Expected Behavior

### In Admin:
1. ✅ Review appears in schema type dropdown with ⭐ star icon
2. ✅ Job Posting appears in schema type dropdown with 💼 briefcase icon
3. ✅ All fields show when creating a template
4. ✅ Template can be saved and published

### On Frontend:
1. ✅ When viewing a post that matches template conditions
2. ✅ Page source contains `<script type="application/ld+json">`
3. ✅ JSON-LD contains `"@type": "Review"` or `"@type": "JobPosting"`
4. ✅ All template variables are replaced with actual values
5. ✅ No PHP errors in debug log

---

## Still Not Working?

If you've tried everything above and it's still not working, provide:

1. **Debug script output** (screenshot or copy/paste)
2. **Template screenshot** (showing fields and conditions)
3. **Page source** (search for "ld+json" and copy that section)
4. **Debug log errors** (if any)

This will help identify the specific issue.

---

## Files to Check

1. **Output Handler:** `includes/output/class-schema-output-handler.php`
   - Line 117: Should have `require_once __DIR__ . '/types/class-review-schema.php';`
   - Line 129: Should have `'Review' => new Schema_Review(),`
   - Line 130: Should have `'JobPosting' => new Schema_Job_Posting(),`

2. **Schema Classes:**
   - `includes/output/types/class-review-schema.php` (should exist)
   - `includes/output/types/class-job-posting-schema.php` (should exist)

3. **Pro Restrictions:** `includes/output/class-schema-output-handler.php` (line 32-40)
   - Review should NOT be in `$pro_schema_types` array
   - JobPosting should NOT be in `$pro_schema_types` array

---

## Contact Info

If you need more help, provide:
- Debug script results
- WordPress version
- PHP version
- Any error messages
- Screenshots of templates and conditions

---

**Last Updated:** December 3, 2024
