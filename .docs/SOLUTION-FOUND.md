# Solution Found: Why JobPosting Schema Wasn't Showing

## 🎯 Root Causes Identified

I found **TWO critical issues** preventing your JobPosting schema from outputting:

---

## Issue #1: Empty Conditions (CRITICAL)

### The Problem

Your template data shows:
```php
[includeConditions] => Array
(
    [logic] => or
    [groups] => Array
    (
        [0] => Array
        (
            [logic] => and
            [rules] => Array
            (
                // ❌ EMPTY! No rules defined
            )
        )
    )
)
```

### Why This Blocks Output

In `class-schema-output-handler.php:461`:
```php
// Check if at least one group has rules
if ( ! Schema_Engine_Conditions::has_rules( $include_conditions ) ) {
    return false; // ❌ Template is skipped!
}
```

**The template has NO conditions/rules, so `has_rules()` returns false and the template never outputs!**

### The Fix

**You MUST add conditions to your template:**

1. Go to WordPress Admin → Schema Templates
2. Edit your JobPosting template
3. Go to the **Conditions** tab
4. Click **"Add Condition"**
5. Set a condition, for example:
   - **Post Type** = **Post** (or your custom post type)
   - OR **Specific Post** = Select specific posts
6. Click **Update** to save

**Without conditions, the template will NEVER display on any page!**

---

## Issue #2: Missing Required Field (jobLocations)

### The Problem

Your template data shows:
```php
[fields] => Array
(
    [title] => {post_title}
    [description] => {post_content}
    [datePosted] => {post_date}
    [hiringOrganizationName] => {site_name}
    [hiringOrganizationUrl] => {site_url}
    [hiringOrganizationLogo] => {site_logo}
    [baseSalaryUnit] => YEAR
    [baseSalaryCurrency] => USD
    // ❌ jobLocations is missing!
)
```

### Why This Matters

Google **requires** `jobLocation` for JobPosting schema. Without it:
- Schema is incomplete
- Google won't show it in rich results
- May cause validation errors

### The Fix (Two Options)

#### Option A: Add jobLocations to Your Template (Recommended)

1. Edit your JobPosting template
2. Find the **Job Location(s)** field (it's a repeater field)
3. Click "Add Location"
4. Fill in at least:
   - **City** (e.g., "San Francisco")
   - **Country** (e.g., "US") - **Required by Google**
5. Update template

#### Option B: Use the Fallback (Already Implemented)

I've updated `class-job-posting-schema.php` to add a default location if none is provided:

```php
// Fallback: Create a generic location if none provided
$schema['jobLocation'] = array(
    '@type' => 'Place',
    'address' => array(
        '@type' => 'PostalAddress',
        'addressCountry' => 'US', // Default
    ),
);
```

This ensures the schema always has a location, even if you don't specify one. However, you should add a real location for better SEO results!

---

## ✅ Complete Solution

### Step 1: Add Conditions (MUST DO!)

**In WordPress Admin:**
1. Go to **Schema Templates** → Find your JobPosting template
2. Click **Edit**
3. Go to the **Conditions** tab
4. Add at least one condition:

**Example Condition Setup:**
- Click "Add Rule"
- Select: **Post Type** → **equals** → **Post** (or your job post type)
- Click **Update**

### Step 2: Add Job Location (Recommended)

**Still in the same template:**
1. Scroll to the **Schema** tab (or main fields area)
2. Find **Job Location(s)** field
3. Click "Add Location"
4. Fill in:
   ```
   City: San Francisco
   State/Region: CA
   Country: US
   ```
5. Click **Update**

### Step 3: Verify Template is Published

- Make sure template status is **"Published"** (not Draft)
- Check the publish date

### Step 4: Test on Frontend

1. Go to a post that matches your conditions
2. View page source (Ctrl/Cmd + U)
3. Search for: `"@type": "JobPosting"`
4. You should see the schema!

---

## 🔍 Example: Correct Template Data

After fixing, your template data should look like this:

```php
Array
(
    [schemaType] => JobPosting
    [fields] => Array
    (
        [title] => {post_title}
        [description] => {post_content}
        [datePosted] => {post_date}
        [hiringOrganizationName] => {site_name}
        [hiringOrganizationUrl] => {site_url}
        [hiringOrganizationLogo] => {site_logo}
        [jobLocations] => Array  // ✅ NOW PRESENT!
        (
            [0] => Array
            (
                [addressLocality] => San Francisco
                [addressRegion] => CA
                [addressCountry] => US
            )
        )
        [baseSalaryUnit] => YEAR
        [baseSalaryCurrency] => USD
    )
    [includeConditions] => Array
    (
        [logic] => or
        [groups] => Array
        (
            [0] => Array
            (
                [logic] => and
                [rules] => Array  // ✅ NOW HAS RULES!
                (
                    [0] => Array
                    (
                        [field] => post_type
                        [operator] => equals
                        [value] => post
                    )
                )
            )
        )
    )
)
```

---

## 📊 Why This Happens

### The Conditions Check

The plugin has a safety mechanism to prevent templates from accidentally displaying everywhere:

```php
// If no conditions are set or groups are empty, don't display
if ( empty( $include_conditions ) ) {
    return false;
}

// Check if at least one group has rules
if ( ! Schema_Engine_Conditions::has_rules( $include_conditions ) ) {
    return false; // ← Your template failed here!
}
```

**This is by design** - templates without conditions would display on EVERY page, which is usually not wanted.

### The Required Fields

According to Google's JobPosting documentation:
- `title` - Required
- `description` - Required
- `datePosted` - Required
- `hiringOrganization` - Required
- **`jobLocation` - Required** ← You were missing this!

Without all required fields, Google won't show your job posting in search results.

---

## 🧪 Quick Test

After making the changes, test with this script:

```
http://yoursite.local/wp-content/plugins/schema-engine/debug-schema-output.php
```

It should now show:
- ✅ Template exists
- ✅ Template has conditions
- ✅ Template has jobLocations
- ✅ Schema builds successfully

---

## 📋 Checklist

Before testing on frontend, verify:

- [ ] Template has at least one condition/rule
- [ ] Condition matches the post type you're testing on
- [ ] Template has jobLocations field filled in (or fallback will be used)
- [ ] Template is **Published** (not Draft)
- [ ] You're viewing a post that matches the conditions
- [ ] Cache is cleared

---

## 🎯 Expected Output

After fixing, you should see in page source:

```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "JobPosting",
      "@id": "https://yoursite.com/job/#jobposting",
      "title": "Your Job Title",
      "description": "Job description here...",
      "datePosted": "2024-01-15",
      "hiringOrganization": {
        "@type": "Organization",
        "name": "Your Company",
        "url": "https://yoursite.com",
        "logo": "https://yoursite.com/logo.png"
      },
      "jobLocation": {
        "@type": "Place",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "San Francisco",
          "addressRegion": "CA",
          "addressCountry": "US"
        }
      },
      "baseSalary": {
        "@type": "MonetaryAmount",
        "currency": "USD",
        "value": {
          "@type": "QuantitativeValue",
          "unitText": "YEAR"
        }
      }
    }
  ]
}
```

---

## 🔧 Files Modified

**`class-job-posting-schema.php`** (Lines 96-106)
- Added fallback location when `jobLocations` is empty
- Ensures schema always has required `jobLocation` field
- Default country: "US" (users should override with real location)

---

## ⚡ TL;DR

**The schema wasn't showing because:**
1. ❌ **Empty conditions** - Template had no rules, so it never displayed
2. ❌ **Missing jobLocations** - Required field was empty

**To fix:**
1. ✅ Add at least one condition to your template (Post Type = Post)
2. ✅ Add job location in the template fields
3. ✅ Make sure template is Published
4. ✅ Test on a matching post

---

**Last Updated:** December 3, 2024
**Issue:** Template not displaying on frontend
**Root Cause:** Empty conditions array
**Status:** ✅ IDENTIFIED - Follow steps above to fix
