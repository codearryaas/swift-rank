# Troubleshooting Schema Engine

## Recent Updates (2025-11-29)

### Build Structure Changed
The webpack build now uses separate folders for each component:
- `build/metabox/index.js` (was `build/metabox.js`)
- `build/post-metabox/index.js` (was `build/post-metabox.js`)
- `build/blocks/faq/index.js` (new)
- `build/blocks/howto/index.js` (new)

**IMPORTANT:** After updating, you MUST rebuild:
```bash
npm run build
```

## Issue: Metabox Not Working After Update

### Quick Fix Steps

1. **Rebuild the Assets**
   ```bash
   cd /path/to/schema-engine
   npm run build
   ```
   This will create the new folder structure.

2. **Deactivate and Reactivate the Plugin**
   - Go to Plugins in WordPress admin
   - Deactivate "Schema Engine"
   - Activate "Schema Engine"
   - This will flush rewrite rules and register the custom post type

3. **Verify New Build Structure**
   ```bash
   ls -R build/
   ```
   Should show:
   - `build/metabox/index.js`
   - `build/metabox/index.asset.php`
   - `build/metabox/index.css`
   - `build/post-metabox/index.js`
   - `build/post-metabox/index.asset.php`
   - `build/blocks/faq/index.js`
   - `build/blocks/howto/index.js`

3. **Clear WordPress Cache**
   - If using a caching plugin, clear all caches
   - Clear browser cache (Cmd+Shift+R on Mac, Ctrl+Shift+R on Windows)

4. **Check Browser Console**
   - Open Chrome DevTools (F12)
   - Go to Console tab
   - Look for JavaScript errors
   - Common errors:
     - 404 on metabox.js = path issue
     - React errors = build issue

5. **Check PHP Error Log**
   - Enable WordPress debug mode in wp-config.php:
   ```php
   define( 'WP_DEBUG', true );
   define( 'WP_DEBUG_LOG', true );
   define( 'WP_DEBUG_DISPLAY', false );
   ```
   - Check `/wp-content/debug.log` for errors

### Verification Steps

1. **Verify Custom Post Type is Registered**
   - Go to WordPress admin
   - Look for "Schema Templates" menu item
   - If not visible, the CPT is not registered

2. **Verify Assets are Enqueued**
   - Create or edit a Schema Template
   - View page source (Cmd+U / Ctrl+U)
   - Search for "schema-engine-metabox"
   - Should see:
     ```html
     <script src=".../build/metabox.js?ver=..."></script>
     <link href=".../build/style-metabox.css?ver=..." rel="stylesheet">
     ```

3. **Verify React Root Element Exists**
   - Inspect the page (F12)
   - Look for: `<div id="schema-template-metabox-root"></div>`
   - Should be inside the "Schema Configuration" metabox

4. **Check for JavaScript Errors**
   - Open Browser Console (F12)
   - Look for errors related to:
     - `@wordpress/components` not found
     - `@wordpress/element` not found
     - React errors

### Common Issues

#### Issue: "Schema Templates" menu not showing
**Solution**: Deactivate and reactivate the plugin

#### Issue: Metabox shows but is empty
**Solution**:
- Check browser console for errors
- Verify build files exist
- Rebuild: `npm run build`

#### Issue: JavaScript errors about missing dependencies
**Solution**:
- Delete node_modules: `rm -rf node_modules`
- Delete package-lock.json: `rm package-lock.json`
- Reinstall: `npm install`
- Rebuild: `npm run build`

#### Issue: 404 error on metabox.js
**Solution**:
- Verify file exists in `build/metabox.js`
- Check file permissions: `chmod 644 build/metabox.js`

### Debug Mode

Add this to your wp-config.php temporarily:
```php
define( 'SCRIPT_DEBUG', true );
```

This will load unminified versions of WordPress scripts, making debugging easier.

### Manual Asset Check

Run this PHP snippet in WordPress (Tools > Plugin File Editor or create a temp page):
```php
<?php
echo 'Plugin Dir: ' . SCHEMA_ENGINE_PLUGIN_DIR . '<br>';
echo 'Plugin URL: ' . SCHEMA_ENGINE_PLUGIN_URL . '<br>';
echo 'Asset File Exists: ' . ( file_exists( SCHEMA_ENGINE_PLUGIN_DIR . 'build/metabox.asset.php' ) ? 'YES' : 'NO' ) . '<br>';
echo 'JS File Exists: ' . ( file_exists( SCHEMA_ENGINE_PLUGIN_DIR . 'build/metabox.js' ) ? 'YES' : 'NO' ) . '<br>';
echo 'CSS File Exists: ' . ( file_exists( SCHEMA_ENGINE_PLUGIN_DIR . 'build/style-metabox.css' ) ? 'YES' : 'NO' ) . '<br>';
?>
```

All should show "YES".

### Still Not Working?

1. Check PHP version (requires PHP 7.0+)
2. Check WordPress version (requires 5.0+)
3. Disable other plugins to check for conflicts
4. Switch to a default WordPress theme
5. Check server error logs

---

## New Features Troubleshooting

### Schema Code Placement Not Working

**Issue**: Schema still appears in head even though you selected "Footer"

**Solution**:
1. Clear all caches (WordPress, server, browser)
2. Check that setting is saved:
   ```php
   $settings = get_option( 'schema_engine_settings' );
   var_dump( $settings['code_placement'] ); // Should show 'footer'
   ```
3. View page source and search for the schema JSON-LD
4. It should appear before `</body>` tag if set to Footer

### Default Image Not Showing

**Issue**: Default image doesn't appear in schema when post has no featured image

**Solution**:
1. Go to Schema Engine > Settings > General
2. Upload a default image
3. Save settings
4. Create a post without a featured image
5. Add an Article schema template that uses `{featured_image}` variable
6. Check the schema output on frontend - it should use the default image

**Verification**:
```php
$settings = get_option( 'schema_engine_settings' );
echo $settings['default_image']; // Should show the image URL
```

### FAQ Block Not Showing

**Issue**: Can't find FAQ block in block editor

**Solution**:
1. Make sure you ran `npm run build`
2. Check that `build/blocks/faq/index.js` exists
3. Clear WordPress cache
4. Refresh the editor page
5. Search for "FAQ" in block inserter

### HowTo Block Not Showing

**Issue**: Can't find HowTo block in block editor

**Solution**:
1. Make sure you ran `npm run build`
2. Check that `build/blocks/howto/index.js` exists
3. Clear WordPress cache
4. Refresh the editor page
5. Search for "How-To" in block inserter

### Blocks Schema Not Outputting

**Issue**: FAQ or HowTo block added but no schema in page source

**Solution**:
1. Check that "Enable Schema" is toggled ON in block settings
2. Make sure page is published (not draft)
3. View page source (not preview)
4. Search for `application/ld+json`
5. Schema should be within the block's HTML

---

## Getting Help

If still having issues, provide:
1. WordPress version
2. PHP version
3. Node/NPM version (`node -v` and `npm -v`)
4. Browser console errors (screenshot)
5. PHP error log entries
6. Output of the Manual Asset Check above
7. Build directory listing (`ls -R build/`)
