# Knowledge Base Variable Sanitization Debug

## Issue
Template variables like `{site_url}` were being corrupted during REST API sanitization, with `esc_url_raw()` adding `http://` prefix and breaking the variable format.

## Fixes Applied

### 1. Variable Preservation in REST API (`includes/admin/rest-api/class-rest-api.php`)

Added `is_template_variable()` helper method:
```php
private function is_template_variable($value) {
    if (empty($value) || !is_string($value)) {
        return false;
    }
    return (strpos($value, '{') !== false && strpos($value, '}') !== false);
}
```

Updated URL field sanitization in both Organization and Person schemas:
- **Before**: Always used `esc_url_raw()` which corrupted variables
- **After**: Check if value is a template variable first
  - If variable → use `sanitize_text_field()` to preserve format
  - If real URL → use `esc_url_raw()` for proper sanitization

Updated social profile sanitization:
- Same logic applied to `socialProfiles` repeater field URLs
- Preserves variables like `{author_url}`, `{site_url}`, etc.

### 2. UI Improvements (`src/admin-settings/components/KnowledgeBaseTab.js`)

- Removed disabled state from schema type dropdown
- Removed opacity/pointer-events styling that disabled fields
- Fields are now always active regardless of Knowledge Base toggle state
- Users can configure schema before enabling the feature

### 3. Debug Logging

Added comprehensive error logging throughout the sanitization process:
- Logs input received by `sanitize_settings()`
- Logs output after organization/person field sanitization
- Helps identify exactly where variables are being modified

## Testing Instructions

### 1. Enable Debug Logging
WordPress debug logging is already enabled via the code. Check logs at:
```
/wp-content/debug.log
```

### 2. Test Variable Preservation

1. Go to Schema Engine → Settings → Knowledge Base
2. Enable Knowledge Base Schema
3. Select "Organization" type
4. Fill in fields with template variables:
   - Name: `{site_name}`
   - URL: `{site_url}`
   - Logo: `{site_logo}`
   - Description: `{site_description}`
5. Add social profile with variable: `{author_url}`
6. Click "Save Settings"

### 3. Check Debug Output

Open browser console and check for:
```javascript
Saving settings: {
  knowledge_base_enabled: true,
  knowledge_base_type: "Organization",
  organization_fields: {
    name: "{site_name}",
    url: "{site_url}",
    logo: "{site_logo}",
    description: "{site_description}",
    socialProfiles: [
      { url: "{author_url}" }
    ]
  }
}
```

Check WordPress debug log (`/wp-content/debug.log`) for:
```
Schema Engine REST API - Input received:
Array
(
    [knowledge_base_enabled] => 1
    [organization_fields] => Array
        (
            [name] => {site_name}
            [url] => {site_url}
            ...
        )
)

Sanitizing organization fields - Input:
Array
(
    [name] => {site_name}
    [url] => {site_url}
    ...
)

Sanitizing organization fields - Output:
Array
(
    [name] => {site_name}
    [url] => {site_url}  // ← Should NOT be "http://site_url}"
    ...
)
```

### 4. Verify Frontend Output

1. Visit your homepage
2. View page source (Ctrl+U / Cmd+Option+U)
3. Search for `application/ld+json`
4. Check that Organization schema appears with REPLACED variables:

```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Your Actual Site Name",
  "url": "https://yoursite.local",
  "logo": "https://yoursite.local/path/to/logo.png",
  "description": "Your actual site description"
}
```

Variables should be replaced with actual values, NOT left as `{site_name}` or corrupted as `http://site_url}`.

## Expected Behavior

### Variables SHOULD Be Preserved:
- `{site_name}` → stays as `{site_name}` in database
- `{site_url}` → stays as `{site_url}` in database
- `{site_logo}` → stays as `{site_logo}` in database
- `{author_url}` → stays as `{author_url}` in database

### Real URLs SHOULD Be Sanitized:
- `example.com` → becomes `http://example.com`
- `https://twitter.com/user` → stays as `https://twitter.com/user`
- `//cdn.example.com/image.jpg` → stays as `//cdn.example.com/image.jpg`

### On Frontend:
- Variables should be REPLACED with actual values
- `{site_name}` → "Your Site Name"
- `{site_url}` → "https://yoursite.local"
- etc.

## Files Modified

1. `includes/admin/rest-api/class-rest-api.php`
   - Added `is_template_variable()` method
   - Updated `sanitize_organization_fields()` URL handling
   - Updated `sanitize_person_fields()` URL handling
   - Updated social profiles sanitization in both methods
   - Added debug logging throughout

2. `src/admin-settings/components/KnowledgeBaseTab.js`
   - Removed disabled state from dropdown
   - Removed opacity/pointer-events styling
   - Fields always active

3. `src/admin-settings/components/SettingsApp.js` (existing)
   - Already has console.log for save operations

## Cleanup

After testing is complete, you can remove debug logging by searching for and removing:
```php
error_log('Schema Engine REST API
error_log('Sanitizing organization fields
error_log('Sanitizing person fields
```

Or keep them for future debugging - they only log when Knowledge Base settings are saved.

## Related Variables

All Schema Engine template variables should be preserved:

**Site Variables:**
- `{site_name}`, `{site_url}`, `{site_description}`, `{site_logo}`

**Post Variables:**
- `{post_title}`, `{post_excerpt}`, `{post_content}`, `{post_url}`, `{featured_image}`, `{post_date}`, `{post_modified}`

**Author Variables:**
- `{author_name}`, `{author_url}`, `{author_bio}`, `{author_email}`, `{author_avatar}`

**Dynamic Variables:**
- `{option:option_name}`, `{meta:field_name}`

All of these should pass through sanitization intact.
