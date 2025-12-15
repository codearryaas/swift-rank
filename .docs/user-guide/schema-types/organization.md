# Organization Schema Setup Guide

Organization schema tells search engines about your company, business, or organization. It's essential for building your Knowledge Graph and can result in rich panels in Google Search results.

## What is Organization Schema?

Organization schema (`@type: Organization`) provides structured data about your business entity. It includes your company name, logo, contact information, social profiles, and more.

**Benefits:**
- Knowledge Graph panel in Google Search
- Enhanced brand presence in search results
- Verified social profile connections
- Improved local SEO (combined with LocalBusiness)
- Trust signals for users and search engines

**Best For:**
- Businesses and companies
- Non-profit organizations
- Educational institutions
- Government organizations
- Any entity that wants a Knowledge Graph presence

## Where to Configure Organization Schema

Organization schema is configured in two places:

### 1. Settings → Knowledge Graph (Recommended)

**Primary Method:**
- Go to **Swift Rank → Settings**
- Knowledge Graph section
- Configure once, applies site-wide
- Automatically referenced by Article schema
- Appears on homepage and throughout site

**Best for:** Most websites

### 2. Schema Template (Advanced)

**Alternative Method:**
- Create Organization schema template
- Full control over fields and conditions
- Can create multiple Organization schemas
- Advanced configurations

**Best for:** Multi-brand sites, holding companies

## Quick Setup (Settings Method)

### Step 1: Access Settings

1. Navigate to **Swift Rank → Settings**
2. Find the **Knowledge Graph** section

### Step 2: Select Entity Type

**Entity Type** dropdown:
- Select **Organization**

Panel refreshes with Organization fields.

### Step 3: Select Organization Type

**Organization Type** dropdown:

**General Types:**
- **Organization** (default, generic)
- **Corporation**
- **NGO** (Non-profit)
- **Government Organization**
- **Educational Organization**

**Business Types:**
- **LocalBusiness** (for physical locations)
- Specific business types (Restaurant, Store, etc.)

**Recommendation:** Choose the most specific type that applies.

### Step 4: Configure Basic Information

**Name** * (Required)
```
Your Organization Name
```
- Official company/organization name
- What you want to appear in Knowledge Graph
- Default: Site name (customize as needed)

**Description**
```
Brief description of your organization
```
- 1-2 sentences
- What does your organization do?
- Used in Knowledge Graph and search results

**URL** * (Required)
```
https://yourwebsite.com
```
- Your homepage URL
- Default: Site URL (usually correct)
- Must be absolute URL

**Logo / Image** * (Required)
```
Upload logo image
```
- Click "Upload" to select logo
- Minimum: 112x112px
- Recommended: 600x60px or larger
- Square or landscape format
- Used in Knowledge Graph and as Publisher logo

### Step 5: Add Contact Information (Optional)

**Phone Number**
```
+1-555-123-4567
```
- International format recommended
- Include country code (+1 for US)
- Public business phone

**Email**
```
info@yourcompany.com
```
- Public contact email
- Customer service or general inquiries
- Avoid personal emails

### Step 6: Add Address (Optional)

**Street Address**
```
123 Main Street, Suite 100
```

**City**
```
New York
```

**State / Region**
```
NY
```

**Postal Code**
```
10001
```

**Country**
```
United States
```

**When to include address:**
- Physical business location
- LocalBusiness type
- You want to appear in local search
- Public office location

**When to skip:**
- Online-only business
- Privacy concerns
- No public location

### Step 7: Add Social Profiles

Social profiles help Google verify your official accounts.

**To add a profile:**
1. Click **Add Profile**
2. **Social Network** dropdown:
   - Facebook
   - Twitter / X
   - LinkedIn
   - Instagram
   - YouTube
   - Pinterest
   - TikTok
3. **URL**: Full URL to your profile
   ```
   https://facebook.com/yourcompany
   https://twitter.com/yourcompany
   https://linkedin.com/company/yourcompany
   ```
4. Repeat for all official social accounts

**Best Practices:**
- Only add verified/official accounts
- Use full URLs (not usernames)
- Add all major platforms you're active on
- Keep URLs updated if you change handles

### Step 8: Save Settings

1. Review all fields
2. Click **Save Settings** at bottom
3. Organization schema now outputs site-wide

### Step 9: Verify Output

**Test on Homepage:**
1. Visit your homepage (frontend)
2. Admin bar → **Swift Rank** → **Google Rich Results Test**
3. Verify Organization schema detected
4. Check for errors or warnings

## Schema Template Method (Advanced)

For advanced users who want more control or multiple Organization schemas.

### Step 1: Create Template

1. Go to **Swift Rank → Add New**
2. Title: "Organization Schema - Main"

### Step 2: Select Schema Type

**Schema Type** dropdown:
- Select **Organization**

### Step 3: Configure Organization Type

**Organization Type** dropdown:
- Select specific type (Corporation, NGO, etc.)

### Step 4: Fill Fields

**Name** *
```
{site_name}                    (uses site name)
Your Company Name              (or static name)
```

**Description**
```
Brief description of your organization
```

**URL** *
```
{site_url}                     (uses homepage URL)
```

**Logo** *
```
{site_logo}                    (uses logo from settings)
https://example.com/logo.png   (or specific URL)
```

**Phone**
```
+1-555-123-4567
```

**Email**
```
info@example.com
```

**Address** (Individual fields)
- Street Address
- City
- State
- Postal Code
- Country

**Social Profiles** (Repeater)
- Add multiple profiles
- Each with Network and URL

### Step 5: Set Display Conditions

**For Homepage Only:**
```
Condition Group 1:
└── Location = Front Page
```

**For All Pages:**
```
Condition Group 1:
└── Whole Site
```

**Recommendation:** Homepage only, unless you need Organization referenced elsewhere.

### Step 6: Publish & Test

1. Click **Publish**
2. Visit homepage
3. Validate with Google Rich Results Test

## Organization Types Explained

### Organization (Generic)

**Use for:** General organizations that don't fit specific types

**Examples:** Consortiums, cooperatives, clubs

**When uncertain:** Use this default type

### Corporation

**Use for:** For-profit companies

**Examples:** Tech companies, retail corporations, service companies

**Legal structure:** Incorporated businesses

### NGO (Non-Governmental Organization)

**Use for:** Non-profit organizations

**Examples:** Charities, foundations, advocacy groups

**Tax status:** 501(c)(3) or equivalent

### Educational Organization

**Use for:** Schools, universities, training centers

**Examples:** Universities, K-12 schools, online course providers

**Focus:** Education as primary mission

### Government Organization

**Use for:** Government agencies and departments

**Examples:** City government, federal agencies, public services

**Authority:** Official government entity

### LocalBusiness

**Use for:** Businesses with physical locations

**Examples:** Restaurants, stores, offices

**See:** [LocalBusiness Schema Setup](localbusiness.md) for details

## Complete Example (Settings)

**Configuration:**
```
Entity Type: Organization
Organization Type: Corporation

Basic Information:
- Name: Acme Software Inc.
- Description: Leading provider of enterprise software solutions for small and medium businesses.
- URL: https://acmesoftware.com
- Logo: [Uploaded logo - 800x200px]

Contact Information:
- Phone: +1-800-555-ACME
- Email: contact@acmesoftware.com

Address:
- Street: 500 Technology Drive, Suite 300
- City: San Francisco
- State: CA
- Postal Code: 94103
- Country: United States

Social Profiles:
- Facebook: https://facebook.com/acmesoftware
- Twitter: https://twitter.com/acmesoftware
- LinkedIn: https://linkedin.com/company/acme-software
- YouTube: https://youtube.com/c/acmesoftware
```

**Expected Output:**
```json
{
  "@context": "https://schema.org",
  "@type": "Corporation",
  "@id": "https://acmesoftware.com#organization",
  "name": "Acme Software Inc.",
  "description": "Leading provider of enterprise software solutions for small and medium businesses.",
  "url": "https://acmesoftware.com",
  "logo": {
    "@type": "ImageObject",
    "url": "https://acmesoftware.com/wp-content/uploads/logo.png",
    "@id": "https://acmesoftware.com#logo"
  },
  "image": "https://acmesoftware.com/wp-content/uploads/logo.png",
  "telephone": "+1-800-555-2263",
  "email": "contact@acmesoftware.com",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "500 Technology Drive, Suite 300",
    "addressLocality": "San Francisco",
    "addressRegion": "CA",
    "postalCode": "94103",
    "addressCountry": "United States"
  },
  "sameAs": [
    "https://facebook.com/acmesoftware",
    "https://twitter.com/acmesoftware",
    "https://linkedin.com/company/acme-software",
    "https://youtube.com/c/acmesoftware"
  ]
}
```

## Schema Relationships

Organization schema automatically connects to other schemas:

### As Article Publisher

When you create Article schema:
```json
{
  "@type": "Article",
  "publisher": {
    "@type": "Organization",
    "@id": "https://example.com#organization"
  }
}
```

The Article references your Organization via `@id`.

### As Website Owner

```json
{
  "@type": "WebSite",
  "publisher": {
    "@type": "Organization",
    "@id": "https://example.com#organization"
  }
}
```

### As LocalBusiness Parent

```json
{
  "@type": "LocalBusiness",
  "parentOrganization": {
    "@type": "Organization",
    "@id": "https://example.com#organization"
  }
}
```

## Common Issues

### Issue: Logo not appearing in Google

**Causes:**
1. Logo too small (under 112x112px)
2. Logo URL not absolute
3. Logo not accessible (404, permissions)
4. Image format not supported

**Solutions:**
1. Upload high-resolution logo (600x60px+)
2. Use absolute URLs
3. Verify logo URL loads in browser
4. Use JPG, PNG, or WebP format

### Issue: Organization not showing in Knowledge Graph

**Causes:**
1. New site (not enough authority)
2. Missing required fields
3. Schema errors
4. Not enough external mentions/links

**Solutions:**
1. Ensure all required fields filled
2. Validate schema (no errors)
3. Build brand presence (social, links)
4. Be patient (takes weeks/months)
5. Claim your Google Business Profile

**Note:** Knowledge Graph appearance is not guaranteed. Google decides based on authority and relevance.

### Issue: Wrong organization type showing

**Cause:** Type set incorrectly

**Solution:**
1. Review Organization Type selection
2. Choose most specific applicable type
3. Save settings
4. Clear cache
5. Revalidate

### Issue: Social profiles not connecting

**Causes:**
1. Wrong URL format
2. Profile not verified
3. Profile privacy settings

**Solutions:**
1. Use full URLs (https://facebook.com/page)
2. Ensure profiles are public
3. Verify you control the accounts
4. Wait for Google to crawl and verify (takes time)

## Required vs Optional Fields

### Required by Google

For Organization to be valid:

- ✓ **@type**: Organization (or subtype)
- ✓ **name**: Organization name
- ✓ **url**: Homepage URL
- ✓ **logo**: Logo image (for Publisher usage)

### Highly Recommended

For Knowledge Graph eligibility:

- ✓ **description**: What you do
- ✓ **sameAs**: Social profiles (at least 2-3)
- ✓ **image**: Logo or representative image

### Optional but Valuable

Enhances local and contact information:

- **telephone**: Public phone number
- **email**: Contact email
- **address**: Physical location
- **founder**: Company founder(s)
- **foundingDate**: When established
- **numberOfEmployees**: Company size

## Best Practices

### 1. Be Accurate

- Use official company name
- Provide real contact information
- Keep social profiles updated
- Don't exaggerate or mislead

### 2. Be Complete

- Fill all applicable fields
- Add all major social profiles
- Include address if you have physical location
- Provide logo and description

### 3. Be Consistent

- Use same name across web
- Match name on social profiles
- Consistent branding
- Same logo everywhere

### 4. Optimize Logo

- High resolution (600x60px minimum)
- Clear and recognizable
- Works at small sizes
- Square or landscape format
- Transparent background if PNG

### 5. Write Good Description

- 1-2 sentences
- Clear and concise
- What you do, who you serve
- Include keywords naturally
- No marketing fluff

### 6. Maintain Social Profiles

- Keep URLs current
- Use official accounts only
- Ensure profiles are public and active
- Verify accounts where possible

## Testing Checklist

Before considering Organization schema complete:

- [ ] Entity Type selected (Organization)
- [ ] Organization Type chosen (specific subtype)
- [ ] Name filled (official company name)
- [ ] URL configured (homepage)
- [ ] Logo uploaded (600x60px+, high quality)
- [ ] Description written (1-2 sentences)
- [ ] Contact info added (if applicable)
- [ ] Address filled (if physical location)
- [ ] Social profiles added (2-3 minimum)
- [ ] Settings saved
- [ ] Schema appears on homepage
- [ ] Google Rich Results Test passes
- [ ] No errors in validation
- [ ] Logo displays correctly
- [ ] Schema references work in Article schema

## Advanced Configurations

### Multiple Brands/Organizations

**Scenario:** Company with multiple brands

**Solution 1: Multiple Templates**
```
Template 1: Main Organization (Homepage)
Template 2: Brand A Organization (Brand A pages)
Template 3: Brand B Organization (Brand B pages)
```

**Solution 2: Parent/Child Relationship**
```json
{
  "@type": "Organization",
  "name": "Parent Company",
  "subOrganization": [
    {
      "@type": "Organization",
      "name": "Brand A"
    },
    {
      "@type": "Organization",
      "name": "Brand B"
    }
  ]
}
```

### Founder Information

**Add to schema (custom field):**
```json
{
  "@type": "Organization",
  "founder": {
    "@type": "Person",
    "name": "Jane Doe"
  },
  "foundingDate": "2010-01-15"
}
```

### Employee Count

**Add to schema (custom field):**
```json
{
  "@type": "Organization",
  "numberOfEmployees": {
    "@type": "QuantitativeValue",
    "value": 150
  }
}
```

**Note:** Swift Rank free version uses Settings for Organization. Pro version templates offer more fields.

## Settings vs Template: Which to Use?

### Use Settings When:

✓ Single organization
✓ Simple configuration
✓ Same info site-wide
✓ You're not a developer
✓ Standard use case

**Most users should use Settings.**

### Use Template When:

✓ Multiple organizations/brands
✓ Need advanced fields
✓ Different org per page section
✓ Custom configurations
✓ Testing variations

## Integration with Other Schemas

### Article Schema

Articles automatically reference your Organization as publisher:
```json
{
  "@type": "Article",
  "publisher": {
    "@id": "https://example.com#organization"
  }
}
```

### LocalBusiness Schema

LocalBusiness can reference Organization as parent:
```json
{
  "@type": "LocalBusiness",
  "parentOrganization": {
    "@id": "https://example.com#organization"
  }
}
```

### WebSite Schema

Website schema references Organization:
```json
{
  "@type": "WebSite",
  "publisher": {
    "@id": "https://example.com#organization"
  }
}
```

## Next Steps

- **Add Article Schema:** [Article Schema Setup](article.md)
- **Create LocalBusiness:** [LocalBusiness Schema Setup](localbusiness.md)
- **Test Your Schema:** [Testing Schema](../testing-schema.md)
- **Learn Variables:** [Dynamic Variables](../dynamic-variables.md)

Organization schema is the foundation of your Knowledge Graph. Set it up correctly, and it enhances all your other schema markup!
