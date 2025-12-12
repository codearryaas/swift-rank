# Schema Engine Pro - Pro Features Test Cases

## Test Suite: Pro Plugin Activation

### TC-P-001: Pro Plugin Installation
**Priority:** Critical
**Description:** Verify Pro plugin can be installed alongside Free plugin

**Preconditions:**
- Free plugin is already installed and activated
- User has Pro plugin files

**Test Steps:**
1. Upload Pro plugin via Plugins > Add New > Upload
2. Install Pro plugin
3. Observe installation process

**Expected Result:**
- Pro plugin installs successfully
- No conflicts with Free plugin
- Both plugins show in plugins list
- Activation button appears

**Status:** ☐ Pass ☐ Fail

---

### TC-P-002: Pro Plugin Activation
**Priority:** Critical
**Description:** Verify Pro plugin activates and integrates with Free plugin

**Preconditions:**
- Free plugin is activated
- Pro plugin is installed

**Test Steps:**
1. Activate Pro plugin
2. Check admin dashboard
3. Verify Pro features appear

**Expected Result:**
- Pro plugin activates successfully
- No PHP errors or warnings
- Pro schema types become available
- Pro menu items appear
- License status displays

**Status:** ☐ Pass ☐ Fail

---

### TC-P-003: License Activation
**Priority:** High
**Description:** Verify license can be activated

**Preconditions:**
- Pro plugin is activated
- Valid license key available

**Test Steps:**
1. Navigate to Schema Engine > License (or Settings > License tab)
2. Enter valid license key
3. Click "Activate License"
4. Observe activation process

**Expected Result:**
- License activates successfully
- Success message displays
- License status shows "Active"
- Pro features fully enabled
- Expiration date displayed

**Status:** ☐ Pass ☐ Fail

---

### TC-P-004: Invalid License Handling
**Priority:** High
**Description:** Verify proper handling of invalid license keys

**Preconditions:**
- Pro plugin is activated

**Test Steps:**
1. Enter invalid/fake license key
2. Attempt to activate
3. Observe error handling

**Expected Result:**
- Clear error message displays
- License remains inactive
- Pro features remain disabled or limited
- User instructed how to get valid license

**Status:** ☐ Pass ☐ Fail

---

### TC-P-005: License Expiration Handling
**Priority:** Medium
**Description:** Verify behavior when license expires

**Preconditions:**
- Expired license or ability to simulate expiration

**Test Steps:**
1. Use expired license or set system date past expiration
2. Access Pro features
3. Observe behavior

**Expected Result:**
- Warning notification displays
- Grace period may apply
- User instructed to renew
- Core functionality may continue with warnings

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Pro Schema Types

### TC-P-006: Pro Schema Types Visible in Dropdown
**Priority:** Critical
**Description:** Verify Pro schema types appear in schema type selector

**Preconditions:**
- Pro plugin and license activated

**Test Steps:**
1. Create new template
2. Open schema type dropdown
3. Check for Pro types

**Expected Result:**
- Pro types appear in dropdown:
  - Recipe
  - Event
  - HowTo
  - PodcastEpisode
  - Custom
- Pro types no longer show Pro badge
- Pro types are selectable and functional
- Types ordered correctly in list

**Status:** ☐ Pass ☐ Fail

---

### TC-P-007: Pro Types Without License
**Priority:** High
**Description:** Verify Pro types show upgrade notice without active license

**Preconditions:**
- Pro plugin not activated OR license not active

**Test Steps:**
1. Create new template
2. Open schema type dropdown
3. Click on Pro schema type (Recipe, Event, etc.)

**Expected Result:**
- Pro badge visible on Pro types
- Clicking Pro type may show upgrade modal
- User directed to purchase/activate license
- Cannot use Pro type without license

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Schema Presets

### TC-P-008: Access Schema Presets Modal
**Priority:** High
**Description:** Verify Schema Presets modal can be opened

**Preconditions:**
- Pro plugin and license activated
- User is creating/editing template

**Test Steps:**
1. Navigate to template editor
2. Look for "Use Preset" or "Choose Preset" button
3. Click button

**Expected Result:**
- Presets modal opens
- Modal displays correctly
- Search and filter controls visible
- Preset cards display properly

**Status:** ☐ Pass ☐ Fail

---

### TC-P-009: Browse Schema Presets by Type
**Priority:** High
**Description:** Verify presets can be filtered by schema type

**Preconditions:**
- Presets modal is open

**Test Steps:**
1. View left panel with schema types
2. Click "All Types" - see all presets
3. Click "Article" - see only Article presets
4. Click "Product" - see only Product presets
5. Test other type filters

**Expected Result:**
- Type filter panel shows all types with presets
- Count displays for each type
- Clicking type filters presets correctly
- "All Types" shows all presets
- Types ordered same as schema type dropdown

**Status:** ☐ Pass ☐ Fail

---

### TC-P-010: Search Schema Presets
**Priority:** High
**Description:** Verify preset search functionality works

**Preconditions:**
- Presets modal is open

**Test Steps:**
1. Locate search box at top of presets grid
2. Type "blog" in search
3. Observe filtered results
4. Clear search
5. Try other search terms

**Expected Result:**
- Search filters presets in real-time
- Matches preset name and description
- Case-insensitive search
- Shows "No presets found" if no matches
- Clear search returns all presets

**Status:** ☐ Pass ☐ Fail

---

### TC-P-011: Apply Schema Preset
**Priority:** Critical
**Description:** Verify preset can be applied to template

**Preconditions:**
- Presets modal is open
- Creating new template

**Test Steps:**
1. Browse or search for preset
2. Click on preset card (e.g., "Blog Post")
3. Observe template changes

**Expected Result:**
- Modal closes after selection
- Schema type changes to preset's type
- All fields populate with preset values
- Dynamic fields ({post_title}, etc.) preserved
- Template can be further customized

**Status:** ☐ Pass ☐ Fail

---

### TC-P-012: Preset Categories Coverage
**Priority:** Medium
**Description:** Verify presets cover common use cases

**Preconditions:**
- Presets modal is open

**Test Steps:**
1. Browse all available presets
2. Verify coverage of common scenarios:
   - Blog posts
   - Products (physical & digital)
   - Events (online & in-person)
   - Recipes
   - Videos
   - Local businesses
   - Job listings
   - Courses
3. Document any missing common use cases

**Expected Result:**
- Comprehensive preset library
- Common use cases covered
- Quality preset configurations
- Helpful descriptions
- Realistic default values

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Advanced Fields

### TC-P-013: Conditional Field Display
**Priority:** Medium
**Description:** Verify fields show/hide based on conditions

**Preconditions:**
- Pro plugin activated
- Creating template with conditional fields

**Test Steps:**
1. Create Recipe template
2. Observe field visibility changes based on selections
3. Test other schema types with conditionals

**Expected Result:**
- Irrelevant fields hidden initially
- Fields appear when conditions met
- Conditional logic works correctly
- No JavaScript errors

**Status:** ☐ Pass ☐ Fail

---

### TC-P-014: Repeater Fields
**Priority:** High
**Description:** Verify repeater/array fields work correctly

**Preconditions:**
- Creating template with repeater fields (Recipe ingredients, HowTo steps)

**Test Steps:**
1. Create Recipe template
2. Add multiple ingredients using repeater
3. Add multiple instructions
4. Reorder items
5. Delete items
6. Save template

**Expected Result:**
- Can add unlimited items
- Items can be reordered (drag-drop)
- Items can be deleted
- Order persists on save
- Schema output has correct array structure

**Status:** ☐ Pass ☐ Fail

---

### TC-P-015: Rich Text Fields
**Priority:** Medium
**Description:** Verify rich text editor fields work

**Preconditions:**
- Creating template with rich text fields

**Test Steps:**
1. Locate rich text field (description, instructions)
2. Add formatted text:
   - Bold text
   - Italic text
   - Lists
   - Links
3. Save template
4. View schema output

**Expected Result:**
- Rich text editor functions
- Formatting saved correctly
- HTML properly handled in schema output
- No XSS vulnerabilities

**Status:** ☐ Pass ☐ Fail

---

### TC-P-016: Image Upload Fields
**Priority:** High
**Description:** Verify image upload functionality works

**Preconditions:**
- Creating template with image fields

**Test Steps:**
1. Locate image field
2. Click "Upload" or "Choose Image"
3. Select image from media library
4. Save template
5. View schema output

**Expected Result:**
- WordPress media library opens
- Image can be selected
- Image URL saved correctly
- Image displays in field preview
- Full URL appears in schema output

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Schema Reference Fields

### TC-P-017: Create Schema Reference Field
**Priority:** High
**Description:** Verify schema reference fields can link to other schemas

**Preconditions:**
- Knowledge Base has Person and Organization entities

**Test Steps:**
1. Create Article template
2. Set author field to reference Person from Knowledge Base
3. Set publisher field to reference Organization
4. Save template
5. Assign to post
6. View schema output

**Expected Result:**
- Reference dropdown shows available entities
- Can select entity from dropdown
- Schema output includes nested object OR @id reference
- Referenced entity properties appear correctly
- Reference updates if entity changes

**Status:** ☐ Pass ☐ Fail

---

### TC-P-018: Dynamic User References
**Priority:** High
**Description:** Verify can reference WordPress users dynamically

**Preconditions:**
- Creating Article template

**Test Steps:**
1. Set author field to reference post author
2. Use dynamic field: {post_author_id}
3. Assign template to posts by different authors
4. View schema output for each

**Expected Result:**
- Author changes per post
- Correct author data appears for each post
- Can map WP user to Person schema
- Author URL and bio pulled correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-P-019: Knowledge Base Entity References
**Priority:** High
**Description:** Verify static references to Knowledge Base entities

**Preconditions:**
- Organization entity exists in Knowledge Base

**Test Steps:**
1. Create template
2. Set publisher to specific Organization entity
3. Use in multiple posts
4. View schema outputs

**Expected Result:**
- Same organization appears in all posts
- Organization data consistent
- Changes to KB entity reflect in all posts using it
- Can choose from dropdown of KB entities

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Custom Schema Builder

### TC-P-020: Access Custom Schema Builder
**Priority:** High
**Description:** Verify Custom schema type provides JSON editor

**Preconditions:**
- Pro plugin and license activated

**Test Steps:**
1. Create new template
2. Select "Custom" schema type
3. Observe interface

**Expected Result:**
- Custom schema type is available
- JSON editor or visual builder appears
- Can input custom schema structure
- Syntax highlighting works (if JSON editor)
- Preview/validation available

**Status:** ☐ Pass ☐ Fail

---

### TC-P-021: Create Custom Schema with JSON
**Priority:** High
**Description:** Verify custom schema can be created with raw JSON

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Enter custom JSON schema:
```json
{
  "@type": "Course",
  "name": "{post_title}",
  "description": "{post_excerpt}",
  "provider": {
    "@type": "Organization",
    "name": "My School"
  }
}
```
2. Save template
3. Assign to post
4. View schema output

**Expected Result:**
- JSON input accepted
- Dynamic fields work in custom JSON
- Schema outputs correctly
- Valid JSON-LD format
- Custom @type appears

**Status:** ☐ Pass ☐ Fail

---

### TC-P-022: Custom Schema Validation
**Priority:** High
**Description:** Verify custom schema validates JSON syntax

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Enter invalid JSON (missing bracket, comma, etc.)
2. Try to save
3. Observe validation

**Expected Result:**
- Syntax errors highlighted
- Error message shows issue location
- Cannot save invalid JSON
- Helpful error messages
- Suggests corrections

**Status:** ☐ Pass ☐ Fail

---

### TC-P-023: Custom Schema - Any Schema.org Type
**Priority:** Medium
**Description:** Verify custom schema supports any schema.org type

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Create custom schemas for uncommon types:
   - SoftwareApplication
   - MusicAlbum
   - Book
   - Movie
   - MedicalCondition
2. View outputs

**Expected Result:**
- Any valid schema.org @type accepted
- All properties supported
- Nested objects work
- Validates with Google Rich Results Test

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: WooCommerce Integration

### TC-P-024: WooCommerce Detection
**Priority:** High
**Description:** Verify plugin detects WooCommerce installation

**Preconditions:**
- WooCommerce plugin is activated

**Test Steps:**
1. Activate WooCommerce
2. Activate Schema Engine Pro
3. Check for WooCommerce-specific features

**Expected Result:**
- Plugin detects WooCommerce
- WooCommerce presets appear
- Product schema fields show WooCommerce meta options
- WooCommerce-specific dynamic fields available

**Status:** ☐ Pass ☐ Fail

---

### TC-P-025: WooCommerce Product Schema Auto-Generation
**Priority:** Critical
**Description:** Verify Product schema auto-generates for WooCommerce products

**Preconditions:**
- WooCommerce activated
- Product schema template configured

**Test Steps:**
1. Create WooCommerce product
2. Set price, SKU, stock status
3. Add product images
4. View product page schema output

**Expected Result:**
- Product schema auto-applies to WooCommerce products
- Price pulled from WooCommerce
- SKU populated automatically
- Stock status maps to availability
- Product images included
- Ratings/reviews included if present

**Status:** ☐ Pass ☐ Fail

---

### TC-P-026: WooCommerce Variable Products
**Priority:** High
**Description:** Verify Variable Products schema uses AggregateOffer

**Preconditions:**
- WooCommerce activated
- Variable product exists

**Test Steps:**
1. Create variable product with variations
2. Set different prices for variations
3. View schema output

**Expected Result:**
- Offer @type is "AggregateOffer"
- lowPrice shows minimum variation price
- highPrice shows maximum variation price
- All variations considered
- Schema validates correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-P-027: WooCommerce Reviews Integration
**Priority:** High
**Description:** Verify product reviews generate AggregateRating

**Preconditions:**
- WooCommerce product with reviews exists

**Test Steps:**
1. Add reviews to WooCommerce product
2. View product schema output
3. Check for aggregateRating object

**Expected Result:**
- aggregateRating object present
- ratingValue is average rating
- reviewCount is total reviews
- bestRating is 5
- Updates when reviews change

**Status:** ☐ Pass ☐ Fail

---

### TC-P-028: WooCommerce Preset Templates
**Priority:** Medium
**Description:** Verify WooCommerce presets work correctly

**Preconditions:**
- WooCommerce activated
- Schema Presets modal accessible

**Test Steps:**
1. Open presets modal
2. Look for WooCommerce-specific presets:
   - WooCommerce Simple Product
   - WooCommerce Variable Product
3. Apply preset to template
4. Verify WooCommerce dynamic fields

**Expected Result:**
- WooCommerce presets available
- Use WooCommerce-specific dynamic fields
- {meta:_price}, {meta:_sku}, etc. work
- Preset properly configured for WooCommerce

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Easy Digital Downloads Integration

### TC-P-029: EDD Detection
**Priority:** Medium
**Description:** Verify plugin detects Easy Digital Downloads

**Preconditions:**
- Easy Digital Downloads plugin activated

**Test Steps:**
1. Activate EDD
2. Check Schema Engine Pro features
3. Look for EDD-specific options

**Expected Result:**
- Plugin detects EDD
- EDD presets appear
- EDD-specific dynamic fields available
- EDD downloads can use Product schema

**Status:** ☐ Pass ☐ Fail

---

### TC-P-030: EDD Product Schema
**Priority:** Medium
**Description:** Verify Product schema works for EDD downloads

**Preconditions:**
- EDD activated
- EDD download exists

**Test Steps:**
1. Create/edit EDD download
2. Assign Product schema template
3. View schema output

**Expected Result:**
- Product schema applies to downloads
- Price from EDD
- Digital product properties
- Download URL considerations
- Schema validates

**Status:** ☐ Pass ☐ Fail

---

### TC-P-031: EDD Presets
**Priority:** Low
**Description:** Verify EDD-specific presets exist and work

**Preconditions:**
- EDD activated

**Test Steps:**
1. Open presets modal
2. Look for EDD presets
3. Apply EDD preset

**Expected Result:**
- EDD Digital Download preset available
- EDD Software preset available
- Use EDD-specific meta fields
- Properly configured for digital products

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: WP Job Manager Integration

### TC-P-032: Job Manager Detection
**Priority:** Low
**Description:** Verify plugin detects WP Job Manager

**Preconditions:**
- WP Job Manager plugin activated

**Test Steps:**
1. Activate WP Job Manager
2. Check Schema Engine Pro features

**Expected Result:**
- Plugin detects WP Job Manager
- Job posting presets appear
- Job-specific fields available
- JobPosting schema type accessible

**Status:** ☐ Pass ☐ Fail

---

### TC-P-033: JobPosting Schema
**Priority:** Low
**Description:** Verify JobPosting schema works for job listings

**Preconditions:**
- WP Job Manager activated
- Job listing exists

**Test Steps:**
1. Create/edit job listing
2. Assign JobPosting template
3. Fill job details
4. View schema output

**Expected Result:**
- JobPosting schema applies correctly
- Company name, location from job meta
- Salary information if available
- Date posted, expiration date
- Employment type specified
- Validates with Google Jobs

**Status:** ☐ Pass ☐ Fail

---

### TC-P-034: Job Posting Presets
**Priority:** Low
**Description:** Verify job posting presets work correctly

**Preconditions:**
- WP Job Manager activated

**Test Steps:**
1. Open presets modal
2. Look for job posting presets:
   - Full-Time Job Listing
   - Remote Job Listing
3. Apply preset

**Expected Result:**
- Job presets available
- Use WP Job Manager meta fields
- Properly configured for Google Jobs
- Include required properties

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Pro Settings

### TC-P-035: Pro Settings Tab
**Priority:** Medium
**Description:** Verify Pro settings tab exists and is accessible

**Preconditions:**
- Pro plugin and license activated

**Test Steps:**
1. Navigate to Schema Engine > Settings
2. Look for Pro-specific settings tab
3. Click on Pro tab

**Expected Result:**
- Pro settings tab visible
- Tab loads correctly
- Pro-specific options displayed
- Settings can be modified and saved

**Status:** ☐ Pass ☐ Fail

---

### TC-P-036: Auto-Apply Schema Settings
**Priority:** Medium
**Description:** Verify can configure auto-apply rules for schemas

**Preconditions:**
- Pro plugin activated

**Test Steps:**
1. Navigate to Pro settings
2. Configure auto-apply rules:
   - Apply Product schema to all WooCommerce products
   - Apply Article schema to blog posts by default
3. Save settings
4. Test with new posts/products

**Expected Result:**
- Auto-apply rules can be configured
- New content automatically gets schema
- Can be overridden per post
- Rules work as configured

**Status:** ☐ Pass ☐ Fail

---

### TC-P-037: Default Schema by Post Type
**Priority:** Medium
**Description:** Verify can set default schema per post type

**Preconditions:**
- Multiple templates exist

**Test Steps:**
1. Go to settings
2. Set default template for:
   - Posts: BlogPosting template
   - Pages: WebPage template
   - Products: Product template
3. Save settings
4. Create new post/page/product

**Expected Result:**
- Default schema auto-applies
- Different defaults per post type
- Can override on individual items
- Settings persist

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Performance (Pro)

### TC-P-038: Schema Caching
**Priority:** Medium
**Description:** Verify schema output is cached for performance

**Preconditions:**
- Pro plugin activated
- Caching enabled

**Test Steps:**
1. View post with schema (first load)
2. Note generation time
3. Refresh page (cached load)
4. Compare times

**Expected Result:**
- Schema cached after first generation
- Subsequent loads faster
- Cache invalidates when content changes
- Cache setting configurable

**Status:** ☐ Pass ☐ Fail

---

### TC-P-039: Bulk Schema Generation
**Priority:** Low
**Description:** Verify can bulk generate/regenerate schemas

**Preconditions:**
- Multiple posts with schemas

**Test Steps:**
1. Access bulk tools (if available)
2. Regenerate schemas for all posts
3. Observe process

**Expected Result:**
- Bulk regeneration available
- Progress indicator shown
- Completes successfully
- All schemas updated

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Import/Export

### TC-P-040: Export Schema Templates
**Priority:** Medium
**Description:** Verify schema templates can be exported

**Preconditions:**
- At least one template exists

**Test Steps:**
1. Navigate to Templates page
2. Look for Export option
3. Export templates to JSON file
4. Verify file contents

**Expected Result:**
- Export function available
- Templates export as JSON
- All template data included
- File can be saved locally

**Status:** ☐ Pass ☐ Fail

---

### TC-P-041: Import Schema Templates
**Priority:** Medium
**Description:** Verify schema templates can be imported

**Preconditions:**
- Exported template JSON file available

**Test Steps:**
1. Navigate to Templates page
2. Look for Import option
3. Upload JSON file
4. Import templates
5. Verify imported templates

**Expected Result:**
- Import function available
- JSON file uploaded successfully
- Templates imported correctly
- All fields preserved
- No data loss

**Status:** ☐ Pass ☐ Fail

---

### TC-P-042: Export Knowledge Base
**Priority:** Low
**Description:** Verify Knowledge Base entities can be exported

**Preconditions:**
- Knowledge Base has entities

**Test Steps:**
1. Navigate to Knowledge Base
2. Export entities
3. Verify file

**Expected Result:**
- Export function available
- All entities exported
- Data preserved correctly
- Can be used for backup/migration

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Pro Support Features

### TC-P-043: Priority Support Access
**Priority:** Low
**Description:** Verify Pro users have priority support access

**Preconditions:**
- Valid Pro license activated

**Test Steps:**
1. Navigate to support/help section
2. Check for priority support badge/indicator
3. Try to submit support ticket

**Expected Result:**
- Priority support indicated
- Support ticket submission available
- License status verified
- Response time expectations shown

**Status:** ☐ Pass ☐ Fail

---

### TC-P-044: Pro Documentation Access
**Priority:** Low
**Description:** Verify Pro users can access Pro documentation

**Preconditions:**
- Pro license activated

**Test Steps:**
1. Look for documentation links
2. Access Pro-specific docs
3. Verify content accessibility

**Expected Result:**
- Pro documentation available
- Comprehensive guides for Pro features
- Video tutorials if available
- Up-to-date content

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Upgrade/Downgrade

### TC-P-045: Free to Pro Upgrade
**Priority:** High
**Description:** Verify smooth transition from Free to Pro

**Preconditions:**
- Free plugin in use with templates

**Test Steps:**
1. Install and activate Pro plugin
2. Activate license
3. Verify existing templates still work
4. Check Pro features enabled

**Expected Result:**
- No data loss during upgrade
- Existing templates preserved
- Free features continue working
- Pro features become available
- No errors or warnings

**Status:** ☐ Pass ☐ Fail

---

### TC-P-046: Pro Deactivation (Keep Free)
**Priority:** Medium
**Description:** Verify deactivating Pro preserves Free functionality

**Preconditions:**
- Both Free and Pro activated

**Test Steps:**
1. Deactivate Pro plugin
2. Keep Free plugin active
3. Check existing templates
4. Verify Free features work

**Expected Result:**
- Free plugin continues working
- Templates using Free features work
- Templates using Pro features show notices
- No data loss
- Pro types show upgrade notices again

**Status:** ☐ Pass ☐ Fail

---

### TC-P-047: License Deactivation
**Priority:** Medium
**Description:** Verify license can be deactivated for site transfer

**Preconditions:**
- Active license

**Test Steps:**
1. Navigate to License settings
2. Click "Deactivate License"
3. Confirm deactivation

**Expected Result:**
- License deactivates successfully
- Can be used on another site
- Pro features disabled/limited
- No data loss
- Can reactivate later

**Status:** ☐ Pass ☐ Fail

---

## Notes
- Test all Pro features with active license
- Test feature limitations without license
- Verify graceful degradation when license expires
- Test with different license tiers if applicable
- Document any WooCommerce/EDD version dependencies
- Test import/export for site migration scenarios
