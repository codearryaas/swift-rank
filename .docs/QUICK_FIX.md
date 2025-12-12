# Schema Engine - Quick Fix Guide

## ⚠️ Metabox Not Working? Follow These Steps

### Step 1: Rebuild Assets (REQUIRED)
```bash
cd /Users/rakeshlawaju/Local\ Sites/skynet/app/public/wp-content/plugins/schema-engine
npm run build
```

**Why?** The build structure changed to use separate folders:
- Old: `build/metabox.js`
- New: `build/metabox/index.js`

### Step 2: Verify Build Succeeded
```bash
ls -la build/metabox/
ls -la build/post-metabox/
ls -la build/blocks/faq/
ls -la build/blocks/howto/
```

You should see `index.js` and `index.asset.php` files in each folder.

### Step 3: Clear Caches
1. **WordPress Cache** - If using a cache plugin, clear it
2. **Browser Cache** - Hard refresh: `Cmd+Shift+R` (Mac) or `Ctrl+Shift+R` (Windows)
3. **Server Cache** - If using server-side caching, clear it

### Step 4: Test
1. Go to **Schema Engine > Schema Templates**
2. Edit or create a template
3. The metabox should now appear

---

## Features Implemented

### ✅ Schema Code Placement
- Go to **Schema Engine > Settings > General**
- Choose where schema outputs:
  - **Head** (recommended) - In `<head>` section
  - **Footer** - Before `</body>` tag
- Save settings
- Schema will now output in selected location

### ✅ Default Image Fallback
- Go to **Schema Engine > Settings > General**
- Upload a **Default Schema Image**
- This image will be used when:
  - Post has no featured image
  - Schema template uses `{featured_image}` variable
- Recommended size: 1200x675px (16:9 ratio)

### ✅ FAQ Block
- Add via block editor
- Search for "FAQ"
- Multiple question/answer pairs
- Automatic FAQPage schema
- Toggle schema on/off

### ✅ HowTo Block
- Add via block editor
- Search for "How-To"
- Step-by-step instructions
- Optional images per step
- Automatic HowTo schema
- Toggle schema on/off

---

## Common Issues

### Issue: "Cannot find module '@wordpress/scripts'"
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Issue: Build fails with errors
```bash
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Issue: Blocks don't appear in editor
1. Make sure `build/blocks/faq/index.js` exists
2. Make sure `build/blocks/howto/index.js` exists
3. Clear WordPress cache
4. Hard refresh browser

### Issue: Schema not in selected position
1. Clear all caches
2. View page source (not preview)
3. Search for `application/ld+json`
4. Verify position

---

## Build Commands

**Development** (hot reload):
```bash
npm run start
```

**Production** (optimized):
```bash
npm run build
```

**Watch mode** (auto-rebuild on changes):
```bash
npm run start
```

---

## Verification Checklist

After running `npm run build`, verify:

- [ ] `build/metabox/index.js` exists
- [ ] `build/metabox/index.asset.php` exists
- [ ] `build/post-metabox/index.js` exists
- [ ] `build/post-metabox/index.asset.php` exists
- [ ] `build/blocks/faq/index.js` exists
- [ ] `build/blocks/howto/index.js` exists
- [ ] No errors in browser console
- [ ] Metabox appears when editing Schema Template
- [ ] Blocks appear in block inserter

---

## File Structure

```
schema-engine/
├── build/
│   ├── metabox/
│   │   ├── index.js
│   │   ├── index.asset.php
│   │   └── index.css
│   ├── post-metabox/
│   │   ├── index.js
│   │   └── index.asset.php
│   └── blocks/
│       ├── faq/
│       │   ├── index.js
│       │   └── index.asset.php
│       └── howto/
│           ├── index.js
│           └── index.asset.php
├── src/
│   ├── metabox/
│   ├── post-metabox/
│   └── blocks/
│       ├── faq/
│       └── howto/
└── includes/
    ├── class-schema-engine-admin.php
    ├── class-schema-engine-cpt.php
    ├── class-schema-engine-output.php
    └── class-schema-engine-blocks.php
```

---

## Support

See [TROUBLESHOOTING.md](TROUBLESHOOTING.md) for detailed troubleshooting steps.

---

**Last Updated:** 2025-11-29
