# Schema Master User Guide

## Quick Start Guide

This guide will help you set up Schema Master on your WordPress site in just a few minutes.

## Step 1: Installation

1. Log in to your WordPress admin dashboard
2. Navigate to **Plugins → Add New**
3. Click **Upload Plugin**
4. Choose the Schema Master ZIP file
5. Click **Install Now**
6. Click **Activate**

## Step 2: Basic Configuration

### Access Settings

1. Go to **Settings → Schema Master**
2. You'll see two tabs:
   - **Knowledge Graph**: Main configuration
   - **Help & Variables**: Documentation and reference

### Enable Schema

1. Check the box: **Enable Knowledge Graph Schema**
2. This enables schema output on your homepage

## Step 3: Choose Your Schema Type

Select the type that best represents your website:

### Option A: Organization
**Choose this if you're:**
- A company or corporation
- A non-profit organization
- A general business without a physical storefront

### Option B: Local Business
**Choose this if you're:**
- A restaurant, cafe, or bar
- A retail store with a physical location
- A service business with operating hours
- Any business customers visit in person

### Option C: Person
**Choose this if you're:**
- A freelancer or consultant
- Running a personal blog
- Building a professional portfolio
- An author or content creator

## Step 4: Fill in Basic Information

### For Organization or Local Business

**Name** (Required)
- Enter your business name
- Default is `{site_name}` (your WordPress site name)
- You can use custom text

**Logo** (Recommended)
- Click **Upload** to select an image from media library
- Or paste a direct URL
- Best: Square logo, PNG format, under 60px height

**Industry** (Organization only)
- Select from the dropdown
- Choose the category that best fits
- Helps search engines categorize your business

### For Person

**Name** (Required)
- Your full name
- Can use `{site_name}` or custom text

**Profile Image** (Recommended)
- Professional photo or avatar
- Same upload process as logo

**Job Title**
- Your current position
- Example: "Web Developer", "Marketing Consultant"

**Gender** (Optional)
- Your gender identity
- Example: "Male", "Female", "Non-binary"

## Step 5: Add Contact Information

**Phone Number**
- Format: +1-555-123-4567
- Use international format with country code
- Click **Insert Variable** for dynamic values

**Email Address**
- Your contact email
- Example: contact@example.com
- Use `{option:admin_email}` for WordPress admin email

**Contact Type**
- Default: "Customer Service"
- Other options: "Sales", "Technical Support", "Reservations"

**Fax Number** (Optional)
- Same format as phone
- Most businesses can leave this empty

## Step 6: Add Your Address

Fill in all applicable fields:

- **Street Address**: 123 Main Street, Suite 100
- **City**: New York
- **State/Region**: NY or New York
- **Postal Code**: 10001
- **Country**: United States

**Note:** Address is optional but recommended for local businesses

## Step 7: Configure Hours (Local Business Only)

If you selected "Local Business", set your hours:

1. Each day shows: Opens / Closes times
2. Check **Closed** for days you're not open
3. Times use 24-hour format:
   - 9:00 AM = 09:00
   - 5:00 PM = 17:00
   - 12:00 PM = 12:00

**Common Configurations:**

Monday-Friday 9-5:
- Monday-Friday: 09:00 to 17:00
- Saturday-Sunday: Check "Closed"

Restaurant Hours:
- Tuesday-Saturday: 11:00 to 22:00
- Sunday-Monday: Check "Closed"

## Step 8: Add Social Media Profiles

1. Click **Add Social Profile**
2. Enter the full URL to your profile
3. Click **Add Social Profile** again for more
4. Examples:
   - https://facebook.com/yourpage
   - https://twitter.com/yourhandle
   - https://linkedin.com/company/yourcompany
   - https://instagram.com/yourhandle

**Remove profiles:**
- Click the **Remove** button next to any profile

## Step 9: Price Range (Local Business Only)

Indicate your price level:

**Symbol Format:**
- $ = Inexpensive
- $$ = Moderate
- $$$ = Expensive
- $$$$ = Very Expensive

**Range Format:**
- $10-$50
- $25-$100
- $50-$200

## Step 10: Save Your Settings

1. Scroll to the bottom
2. Click **Save Changes**
3. Wait for confirmation message

## Step 11: Test Your Schema

### Quick Visual Check

1. Open your homepage
2. Right-click → View Page Source (or Ctrl+U / Cmd+U)
3. Search for "Schema Master"
4. You should see JSON-LD code between script tags

### Google Rich Results Test

1. Visit: https://search.google.com/test/rich-results
2. Enter your homepage URL
3. Click **Test URL**
4. Wait for results
5. Check for:
   - ✅ Valid items detected
   - ❌ Any errors or warnings

### Fix Common Errors

**Error: Missing required field**
- Go back to settings
- Fill in the required field
- Save and test again

**Error: Invalid URL**
- Check logo URL is complete
- Verify social media URLs are correct
- Ensure URLs start with https://

**Error: Invalid format**
- Check phone number format
- Verify email address
- Check opening hours format

## Using Variables

Variables let you insert dynamic content that updates automatically.

### How to Use

1. Click in any field that supports variables
2. Click **Insert Variable** button
3. Select a variable from the dropdown
4. Variable appears in the field: `{variable_name}`

### Most Useful Variables

**{site_name}**
- Your WordPress site name
- Updates if you change site name in Settings → General

**{site_url}**
- Your homepage URL
- Useful for building full URLs

**{option:admin_email}**
- Your WordPress admin email
- From Settings → General

**{option:blogdescription}**
- Your site tagline
- From Settings → General

### Variable Examples

**Organization Name:**
```
{site_name}
```
Shows: Your WordPress Site Name

**Logo with Site URL:**
```
{site_url}/wp-content/uploads/2024/logo.png
```
Shows: https://example.com/wp-content/uploads/2024/logo.png

**Contact Email:**
```
{option:admin_email}
```
Shows: admin@example.com

**Mixed Text:**
```
Contact {site_name} Support
```
Shows: Contact Your Site Name Support

## Tips for Best Results

### Logo Tips

✅ **Do:**
- Use a square or 16:9 aspect ratio
- Keep height under 60px for Google
- Use PNG format with transparency
- Host on your own domain
- Use HTTPS URLs

❌ **Don't:**
- Use extremely large images
- Use hotlinks from other sites
- Use low-quality or pixelated images

### Contact Information Tips

✅ **Do:**
- Use consistent phone format
- Include country code on phone
- Use business email addresses
- Keep information up to date

❌ **Don't:**
- Use personal email addresses
- Include multiple phone numbers
- Use temporary contact information

### Social Media Tips

✅ **Do:**
- Add all active social profiles
- Use complete URLs
- Verify links work before saving
- Keep profiles updated

❌ **Don't:**
- Add inactive accounts
- Use shortened URLs
- Include too many profiles (stick to main ones)

### Opening Hours Tips

✅ **Do:**
- Use 24-hour format consistently
- Mark closed days explicitly
- Double-check times are correct
- Update for seasonal changes

❌ **Don't:**
- Leave hours blank (mark as closed instead)
- Use AM/PM notation
- Forget to save after changes

## Troubleshooting Common Issues

### Schema Not Showing

**Problem:** Schema doesn't appear in page source

**Solutions:**
1. Verify plugin is activated
2. Check "Enable Knowledge Graph Schema" is checked
3. Clear your site cache
4. Check you're viewing the homepage
5. Disable other schema plugins temporarily

### Google Test Shows Errors

**Problem:** Google Rich Results Test shows errors

**Solutions:**
1. Read the specific error message
2. Check all required fields are filled
3. Verify URL formats are correct
4. Test phone and email formats
5. Check opening hours are complete

### Variables Not Working

**Problem:** Variables show as `{variable_name}` instead of actual values

**Solutions:**
1. Check variable syntax is correct
2. Verify the variable exists
3. For options, confirm the option exists in WordPress
4. Save settings and clear cache
5. Check for typos in variable names

### Logo Not Loading

**Problem:** Logo URL is set but doesn't validate

**Solutions:**
1. Verify image URL is publicly accessible
2. Test URL in browser directly
3. Check HTTPS is used
4. Verify image file exists
5. Check file permissions

### Conflicts with Other Plugins

**Problem:** Schema conflicts with Yoast, Rank Math, etc.

**Solutions:**
1. Check which schema is appearing (view source)
2. Disable conflicting schema in other plugins
3. Test with one plugin at a time
4. Contact support if conflicts persist

## Advanced Usage

### Custom Post Type Integration

Currently, Schema Master only outputs on the homepage. For custom post types:
1. Use other plugins for post-specific schema
2. Schema Master handles site-wide organization info
3. Both can coexist without conflicts

### Multiple Locations

For businesses with multiple locations:
1. Main location in Schema Master
2. Use LocalBusiness post type for individual locations
3. Consider dedicated local business plugins

### Multi-language Sites

For WPML or Polylang:
1. Configure schema in default language
2. Variables will adapt to current language
3. Consider translating static text fields

### Developers

For custom integrations:
1. See developer documentation
2. Use WordPress hooks and filters
3. Extend TP_Schema_Output class
4. Add custom variables via filters

## Updating Your Schema

### When to Update

Update your schema when:
- Business information changes
- You move locations
- Operating hours change
- Contact information changes
- You add new social profiles

### How to Update

1. Go to **Settings → Schema Master**
2. Make your changes
3. Click **Save Changes**
4. Clear site cache
5. Test with Google Rich Results Test

### After Updates

1. Verify changes appear in page source
2. Run validation tests
3. Monitor search console for errors
4. Check rich results in search

## Frequently Asked Questions

**Q: How long before Google shows rich results?**
A: Google needs to crawl your site first. This can take days to weeks. Schema doesn't guarantee rich results.

**Q: Can I add schema to other pages?**
A: This version focuses on homepage knowledge graph. Other plugins can add page-specific schema.

**Q: Does this work with page builders?**
A: Yes! Schema Master works with any theme or page builder. It outputs in the site header.

**Q: Can I have multiple organizations?**
A: One knowledge graph per site. For multiple locations, use additional plugins for location-specific schema.

**Q: Does this slow down my site?**
A: No. The plugin adds minimal code and only runs on the homepage.

**Q: Can I customize the schema output?**
A: Yes, developers can extend the plugin. See developer documentation.

**Q: Is this compatible with WordPress 6.x?**
A: Yes, compatible with WordPress 5.0 and above.

**Q: Do I need coding knowledge?**
A: No! The interface is user-friendly. Variables are optional.

## Getting Support

### Before Contacting Support

1. Read this user guide
2. Check the Help & Variables tab
3. Test with validation tools
4. Clear cache and test again
5. Disable other plugins temporarily

### When Contacting Support

Include:
- WordPress version
- PHP version
- Active theme name
- List of active plugins
- Screenshot of settings
- Screenshot of errors
- URL to your homepage

## Next Steps

After completing setup:

1. ✅ Test schema with Google Rich Results Test
2. ✅ Verify schema appears in page source
3. ✅ Submit homepage to Google Search Console
4. ✅ Monitor for schema errors in Search Console
5. ✅ Update schema when business info changes

## Additional Resources

- **Google Search Central**: https://developers.google.com/search/docs/appearance/structured-data
- **Schema.org Documentation**: https://schema.org/
- **Google Rich Results Test**: https://search.google.com/test/rich-results
- **Schema Validator**: https://validator.schema.org/

---

**Congratulations!** You've successfully set up Schema Master. Your site now has professional structured data that helps search engines understand your business better.
