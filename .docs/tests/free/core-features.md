# Schema Engine Free - Core Features Test Cases

## Test Suite: Installation & Activation

### TC-F-001: Plugin Installation
**Priority:** High
**Description:** Verify plugin can be installed successfully

**Preconditions:**
- WordPress installation is accessible
- User has admin privileges

**Test Steps:**
1. Navigate to WordPress admin dashboard
2. Go to Plugins > Add New
3. Search for "Schema Engine"
4. Click "Install Now"
5. Wait for installation to complete

**Expected Result:**
- Plugin installs without errors
- Success message is displayed
- "Activate" button appears

**Status:** ☐ Pass ☐ Fail

---

### TC-F-002: Plugin Activation
**Priority:** High
**Description:** Verify plugin activates without errors

**Preconditions:**
- Plugin is installed
- User has admin privileges

**Test Steps:**
1. Click "Activate" button
2. Observe activation process

**Expected Result:**
- Plugin activates successfully
- No PHP errors or warnings
- Admin menu "Schema Engine" appears in sidebar
- Welcome message/notice appears (if applicable)

**Status:** ☐ Pass ☐ Fail

---

### TC-F-003: Database Tables Creation
**Priority:** High
**Description:** Verify necessary database tables are created on activation

**Preconditions:**
- Plugin is installed but not activated

**Test Steps:**
1. Activate plugin
2. Check database for plugin tables using phpMyAdmin or SQL query

**Expected Result:**
- Required database tables are created
- Tables have proper structure
- No SQL errors

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Schema Templates

### TC-F-004: Access Templates Page
**Priority:** High
**Description:** Verify user can access schema templates page

**Preconditions:**
- Plugin is activated
- User is logged in as admin

**Test Steps:**
1. Navigate to Schema Engine menu in admin sidebar
2. Click on "Templates" submenu

**Expected Result:**
- Templates page loads successfully
- Page displays list of templates (or empty state if none exist)
- "Add New" button is visible
- Page layout renders correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-F-005: Create New Template
**Priority:** High
**Description:** Verify user can create a new schema template

**Preconditions:**
- User is on Templates page
- User has admin privileges

**Test Steps:**
1. Click "Add New" button
2. Enter template title: "Test Article Template"
3. Select schema type: "Article"
4. Fill in required fields:
   - Headline: "{post_title}"
   - Description: "{post_excerpt}"
5. Click "Publish" button

**Expected Result:**
- Template is created successfully
- Success message appears
- Template appears in templates list
- Template can be viewed/edited

**Status:** ☐ Pass ☐ Fail

---

### TC-F-006: Edit Existing Template
**Priority:** High
**Description:** Verify user can edit an existing template

**Preconditions:**
- At least one template exists

**Test Steps:**
1. Navigate to Templates page
2. Click "Edit" on an existing template
3. Modify template title
4. Change field values
5. Click "Update" button

**Expected Result:**
- Template updates successfully
- Changes are saved to database
- Updated values display correctly
- Success message appears

**Status:** ☐ Pass ☐ Fail

---

### TC-F-007: Delete Template
**Priority:** Medium
**Description:** Verify user can delete a template

**Preconditions:**
- At least one template exists

**Test Steps:**
1. Navigate to Templates page
2. Hover over a template
3. Click "Trash" link
4. Confirm deletion if prompted

**Expected Result:**
- Template is moved to trash
- Template is removed from active list
- Success message appears
- Template can be restored from trash

**Status:** ☐ Pass ☐ Fail

---

### TC-F-008: Bulk Delete Templates
**Priority:** Medium
**Description:** Verify user can bulk delete multiple templates

**Preconditions:**
- At least two templates exist

**Test Steps:**
1. Navigate to Templates page
2. Check checkboxes for 2+ templates
3. Select "Move to Trash" from Bulk Actions dropdown
4. Click "Apply" button

**Expected Result:**
- All selected templates move to trash
- Templates are removed from active list
- Success message shows number of deleted templates

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Schema Type Selector

### TC-F-009: Schema Type Dropdown Display
**Priority:** High
**Description:** Verify schema type selector displays all available types

**Preconditions:**
- User is creating/editing a template

**Test Steps:**
1. Create new template or edit existing one
2. Locate schema type selector dropdown
3. Click on dropdown to expand

**Expected Result:**
- Dropdown displays all free schema types:
  - Article
  - BlogPosting
  - NewsArticle
  - Product
  - LocalBusiness
  - Organization
  - VideoObject
  - Person
- Types are displayed in correct order
- Each type shows icon and label

**Status:** ☐ Pass ☐ Fail

---

### TC-F-010: Schema Type Selection
**Priority:** High
**Description:** Verify user can select a schema type

**Preconditions:**
- User is creating/editing a template

**Test Steps:**
1. Open schema type dropdown
2. Click on "Product" schema type
3. Observe UI changes

**Expected Result:**
- Schema type is selected
- Dropdown closes
- Product-specific fields appear in form
- Selection persists after page reload

**Status:** ☐ Pass ☐ Fail

---

### TC-F-011: Pro Schema Type Badge
**Priority:** Medium
**Description:** Verify Pro schema types display Pro badge when Pro plugin is not active

**Preconditions:**
- Pro plugin is NOT activated
- User is creating/editing a template

**Test Steps:**
1. Open schema type dropdown
2. Look for Pro schema types (Recipe, Event, HowTo, etc.)

**Expected Result:**
- Pro types are visible in dropdown
- Pro badge/icon is displayed next to Pro types
- Pro types may show upgrade notice on hover
- Pro types can be selected but may show upgrade prompt

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Knowledge Base

### TC-F-012: Access Knowledge Base
**Priority:** High
**Description:** Verify user can access Knowledge Base page

**Preconditions:**
- Plugin is activated

**Test Steps:**
1. Navigate to Schema Engine menu
2. Click "Knowledge Base" submenu

**Expected Result:**
- Knowledge Base page loads successfully
- Page displays empty state or existing entities
- Interface is user-friendly

**Status:** ☐ Pass ☐ Fail

---

### TC-F-013: Create Organization Entity
**Priority:** High
**Description:** Verify user can create an Organization in Knowledge Base

**Preconditions:**
- User is on Knowledge Base page

**Test Steps:**
1. Click "Add Organization" or similar button
2. Fill in organization details:
   - Name: "Test Company"
   - URL: "https://example.com"
   - Logo: Upload test image
3. Save entity

**Expected Result:**
- Organization is created successfully
- Entity appears in Knowledge Base list
- All fields are saved correctly
- Entity can be referenced in templates

**Status:** ☐ Pass ☐ Fail

---

### TC-F-014: Create Person Entity
**Priority:** High
**Description:** Verify user can create a Person in Knowledge Base

**Preconditions:**
- User is on Knowledge Base page

**Test Steps:**
1. Click "Add Person" or similar button
2. Fill in person details:
   - Name: "John Doe"
   - Job Title: "CEO"
   - Image: Upload test image
3. Save entity

**Expected Result:**
- Person is created successfully
- Entity appears in Knowledge Base list
- All fields are saved correctly
- Entity can be referenced in templates

**Status:** ☐ Pass ☐ Fail

---

### TC-F-015: Edit Knowledge Base Entity
**Priority:** Medium
**Description:** Verify user can edit existing Knowledge Base entity

**Preconditions:**
- At least one entity exists in Knowledge Base

**Test Steps:**
1. Navigate to Knowledge Base page
2. Click "Edit" on an entity
3. Modify entity fields
4. Save changes

**Expected Result:**
- Entity updates successfully
- Changes are reflected immediately
- Updated values display in templates using this entity

**Status:** ☐ Pass ☐ Fail

---

### TC-F-016: Delete Knowledge Base Entity
**Priority:** Medium
**Description:** Verify user can delete a Knowledge Base entity

**Preconditions:**
- At least one entity exists

**Test Steps:**
1. Navigate to Knowledge Base page
2. Click "Delete" on an entity
3. Confirm deletion

**Expected Result:**
- Entity is deleted successfully
- Entity is removed from list
- Warning shown if entity is in use by templates

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Dynamic Field Values

### TC-F-017: Post Title Dynamic Field
**Priority:** High
**Description:** Verify {post_title} dynamic field works correctly

**Preconditions:**
- Template exists with {post_title} field
- Template is assigned to a post

**Test Steps:**
1. Create/edit a post with title "Test Post Title"
2. Assign schema template to post
3. View post's schema output in page source

**Expected Result:**
- Schema output contains "Test Post Title" in headline field
- Dynamic field is replaced correctly
- No placeholder text remains

**Status:** ☐ Pass ☐ Fail

---

### TC-F-018: Post Excerpt Dynamic Field
**Priority:** High
**Description:** Verify {post_excerpt} dynamic field works correctly

**Preconditions:**
- Template exists with {post_excerpt} field

**Test Steps:**
1. Create post with excerpt "This is a test excerpt"
2. Assign schema template
3. View schema output

**Expected Result:**
- Schema output contains excerpt text
- If no excerpt exists, first 160 characters of content used
- Dynamic field populates correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-F-019: Featured Image Dynamic Field
**Priority:** High
**Description:** Verify {featured_image} dynamic field works correctly

**Preconditions:**
- Template exists with {featured_image} field

**Test Steps:**
1. Create post and set featured image
2. Assign schema template
3. View schema output

**Expected Result:**
- Schema output contains full URL to featured image
- Image URL is valid and accessible
- Fallback works if no featured image set

**Status:** ☐ Pass ☐ Fail

---

### TC-F-020: Post Date Dynamic Fields
**Priority:** Medium
**Description:** Verify {post_date} and {post_modified} dynamic fields work

**Preconditions:**
- Template exists with date fields

**Test Steps:**
1. Create post (note creation date)
2. Assign schema template
3. View schema output
4. Edit post (changes modified date)
5. View schema output again

**Expected Result:**
- {post_date} shows correct publication date in ISO 8601 format
- {post_modified} shows correct modification date
- Dates update when post is modified

**Status:** ☐ Pass ☐ Fail

---

### TC-F-021: Author Dynamic Fields
**Priority:** Medium
**Description:** Verify {post_author} and {post_author_id} dynamic fields work

**Preconditions:**
- Template exists with author fields

**Test Steps:**
1. Create post as specific author
2. Assign schema template
3. View schema output

**Expected Result:**
- {post_author} shows author display name
- {post_author_id} shows correct author ID
- Author reference can be used for Person schema

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Settings Page

### TC-F-022: Access Settings Page
**Priority:** High
**Description:** Verify user can access plugin settings

**Preconditions:**
- Plugin is activated

**Test Steps:**
1. Navigate to Schema Engine menu
2. Click "Settings" submenu

**Expected Result:**
- Settings page loads successfully
- All settings tabs are visible
- Current settings values display correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-F-023: General Settings Save
**Priority:** High
**Description:** Verify general settings can be saved

**Preconditions:**
- User is on Settings page

**Test Steps:**
1. Navigate to General tab
2. Modify settings (enable/disable features)
3. Click "Save Changes" button

**Expected Result:**
- Settings save successfully
- Success message appears
- Settings persist after page reload

**Status:** ☐ Pass ☐ Fail

---

### TC-F-024: Default Schema Settings
**Priority:** Medium
**Description:** Verify default schema can be set for post types

**Preconditions:**
- At least one template exists

**Test Steps:**
1. Navigate to Settings
2. Select default template for "Posts" post type
3. Save settings

**Expected Result:**
- Default template is set
- New posts automatically use this template
- Setting can be overridden per post

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Post Metabox

### TC-F-025: Schema Metabox Display
**Priority:** High
**Description:** Verify schema metabox displays in post editor

**Preconditions:**
- Plugin is activated
- User is editing a post

**Test Steps:**
1. Create new post or edit existing post
2. Scroll down to metaboxes area
3. Locate "Schema Engine" metabox

**Expected Result:**
- Schema metabox is visible
- Metabox is properly styled
- Controls are functional
- Metabox can be collapsed/expanded

**Status:** ☐ Pass ☐ Fail

---

### TC-F-026: Template Selection in Post
**Priority:** High
**Description:** Verify user can select schema template for individual post

**Preconditions:**
- At least one template exists
- User is editing a post

**Test Steps:**
1. Open Schema Engine metabox
2. Click template dropdown
3. Select a template
4. Save/update post

**Expected Result:**
- Template dropdown shows all available templates
- Template can be selected
- Selection is saved with post
- Selected template outputs schema on frontend

**Status:** ☐ Pass ☐ Fail

---

### TC-F-027: Override Template Fields
**Priority:** Medium
**Description:** Verify user can override template field values per post

**Preconditions:**
- Template is assigned to post
- User is editing post

**Test Steps:**
1. Open Schema Engine metabox
2. Locate template fields
3. Override a field value (e.g., change headline)
4. Save post
5. View schema output

**Expected Result:**
- Field override option is available
- Override value is saved
- Schema output uses override value instead of template value
- Original template remains unchanged

**Status:** ☐ Pass ☐ Fail

---

### TC-F-028: Disable Schema for Post
**Priority:** Medium
**Description:** Verify user can disable schema output for individual post

**Preconditions:**
- User is editing a post with schema assigned

**Test Steps:**
1. Open Schema Engine metabox
2. Find "Disable Schema" or similar toggle
3. Enable the disable option
4. Save post
5. View post frontend

**Expected Result:**
- Schema can be disabled
- No schema appears in page source when disabled
- Setting persists
- Schema can be re-enabled

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Schema Output

### TC-F-029: Schema Output in Page Source
**Priority:** Critical
**Description:** Verify schema markup appears in page source

**Preconditions:**
- Template is assigned to published post

**Test Steps:**
1. Create/use post with schema template
2. Publish post
3. Visit post on frontend
4. View page source (Ctrl+U or Cmd+U)
5. Search for "application/ld+json"

**Expected Result:**
- Schema script tag is present in HTML
- Script type is "application/ld+json"
- JSON-LD content is valid
- Schema appears in <head> or before </body>

**Status:** ☐ Pass ☐ Fail

---

### TC-F-030: Valid JSON-LD Format
**Priority:** Critical
**Description:** Verify schema output is valid JSON-LD format

**Preconditions:**
- Post with schema is published

**Test Steps:**
1. View post with schema
2. Copy schema JSON from page source
3. Validate using JSON validator (jsonlint.com)
4. Test with Google Rich Results Test

**Expected Result:**
- JSON is syntactically valid
- No parsing errors
- @context and @type are present
- Google Rich Results Test validates successfully

**Status:** ☐ Pass ☐ Fail

---

### TC-F-031: Multiple Schemas on Same Page
**Priority:** Medium
**Description:** Verify multiple schemas can coexist on same page

**Preconditions:**
- Page has multiple schema sources (template + FAQ block)

**Test Steps:**
1. Create post with schema template
2. Add FAQ block with schema
3. Publish and view page source

**Expected Result:**
- Multiple script tags with schema appear
- Each schema is valid independently
- No conflicts between schemas
- Both schemas validate correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-F-032: Homepage Schema Output
**Priority:** High
**Description:** Verify schema can be output on homepage

**Preconditions:**
- Homepage template/settings configured

**Test Steps:**
1. Configure homepage schema in settings
2. Visit site homepage
3. View page source

**Expected Result:**
- Schema appears on homepage
- Organization/WebSite schema loads correctly
- No duplicate schemas appear

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: FAQ Block

### TC-F-033: Add FAQ Block
**Priority:** High
**Description:** Verify FAQ block can be added to post

**Preconditions:**
- Block editor is enabled
- User is editing a post

**Test Steps:**
1. Click "Add block" (+) button
2. Search for "FAQ"
3. Click Schema Engine FAQ block
4. Observe block insertion

**Expected Result:**
- FAQ block appears in search results
- Block inserts successfully
- Block has proper controls and UI
- Block can be configured

**Status:** ☐ Pass ☐ Fail

---

### TC-F-034: Add FAQ Items
**Priority:** High
**Description:** Verify FAQ items can be added and edited

**Preconditions:**
- FAQ block is added to post

**Test Steps:**
1. Click "Add Question" button
2. Enter question: "What is Schema Engine?"
3. Enter answer: "Schema Engine is a WordPress plugin."
4. Add another question
5. Save post

**Expected Result:**
- Multiple FAQ items can be added
- Questions and answers are editable
- Items can be reordered
- Items can be deleted
- Changes persist on save

**Status:** ☐ Pass ☐ Fail

---

### TC-F-035: FAQ Schema Output
**Priority:** Critical
**Description:** Verify FAQ block generates valid FAQ schema

**Preconditions:**
- FAQ block with items exists in published post

**Test Steps:**
1. Add FAQ block with 2+ questions
2. Publish post
3. View page source
4. Find FAQ schema in JSON-LD

**Expected Result:**
- FAQPage schema is present
- @type is "FAQPage"
- mainEntity array contains Question items
- Each question has name and acceptedAnswer
- Schema validates in Google Rich Results Test

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Performance & Compatibility

### TC-F-036: Plugin Performance
**Priority:** Medium
**Description:** Verify plugin doesn't significantly impact page load time

**Preconditions:**
- Site is published with schema

**Test Steps:**
1. Measure page load time without plugin
2. Activate plugin and add schema
3. Measure page load time with plugin
4. Compare times

**Expected Result:**
- Page load time increase is minimal (<100ms)
- No noticeable performance degradation
- Database queries are optimized

**Status:** ☐ Pass ☐ Fail

---

### TC-F-037: Block Editor Compatibility
**Priority:** High
**Description:** Verify plugin works with Gutenberg block editor

**Preconditions:**
- Gutenberg editor is enabled

**Test Steps:**
1. Create new post in block editor
2. Add content blocks
3. Use schema metabox
4. Save post

**Expected Result:**
- No JavaScript errors
- Metabox functions correctly
- Blocks don't conflict with plugin
- Schema outputs correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-F-038: Classic Editor Compatibility
**Priority:** Medium
**Description:** Verify plugin works with Classic Editor

**Preconditions:**
- Classic Editor plugin is installed

**Test Steps:**
1. Install Classic Editor plugin
2. Create post in classic editor
3. Use schema metabox
4. Save post

**Expected Result:**
- Metabox displays correctly in classic editor
- All features work as expected
- No layout issues

**Status:** ☐ Pass ☐ Fail

---

### TC-F-039: Theme Compatibility
**Priority:** Medium
**Description:** Verify plugin works with popular WordPress themes

**Test Steps:**
1. Test with default WordPress theme (Twenty Twenty-Four)
2. Test with popular theme (Astra, GeneratePress)
3. Add schema to posts
4. View frontend output

**Expected Result:**
- Plugin works with all tested themes
- No CSS conflicts
- Schema outputs correctly regardless of theme

**Status:** ☐ Pass ☐ Fail

---

### TC-F-040: Plugin Conflict Test
**Priority:** Medium
**Description:** Verify plugin works alongside common WordPress plugins

**Preconditions:**
- Popular plugins installed (Yoast SEO, Contact Form 7, etc.)

**Test Steps:**
1. Activate Schema Engine with other plugins
2. Test core functionality
3. Check for JavaScript errors
4. Test schema output

**Expected Result:**
- No conflicts with other plugins
- All plugins function normally
- No JavaScript console errors
- Schema outputs correctly

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Security

### TC-F-041: User Permission Check
**Priority:** High
**Description:** Verify proper user permission checks are in place

**Preconditions:**
- Test with different user roles

**Test Steps:**
1. Login as Editor role
2. Try to access Schema Engine settings
3. Try to create template
4. Login as Author role
5. Try same actions

**Expected Result:**
- Only authorized roles can access admin features
- Proper permission error messages show
- Users can only modify their own content
- No unauthorized access possible

**Status:** ☐ Pass ☐ Fail

---

### TC-F-042: XSS Protection
**Priority:** Critical
**Description:** Verify plugin sanitizes user input to prevent XSS

**Preconditions:**
- User has template edit access

**Test Steps:**
1. Create template
2. Try to input XSS payload: `<script>alert('XSS')</script>`
3. Save template
4. View template output

**Expected Result:**
- Script tags are sanitized/escaped
- No JavaScript execution occurs
- Special characters are properly encoded
- Schema output is safe

**Status:** ☐ Pass ☐ Fail

---

### TC-F-043: SQL Injection Protection
**Priority:** Critical
**Description:** Verify plugin protects against SQL injection

**Preconditions:**
- Database access available

**Test Steps:**
1. Try to input SQL injection payload in search/filter fields
2. Example: `' OR '1'='1`
3. Submit form
4. Check database queries

**Expected Result:**
- Input is properly sanitized
- Prepared statements are used
- No SQL injection is possible
- Database remains secure

**Status:** ☐ Pass ☐ Fail

---

### TC-F-044: Nonce Verification
**Priority:** High
**Description:** Verify CSRF protection via nonce verification

**Preconditions:**
- User is logged in

**Test Steps:**
1. Create template
2. Inspect form HTML for nonce field
3. Try to submit form with invalid/missing nonce

**Expected Result:**
- Nonce field is present in forms
- Forms validate nonce on submission
- Invalid nonce prevents form submission
- Proper error message displays

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Error Handling

### TC-F-045: Missing Required Fields
**Priority:** High
**Description:** Verify proper handling when required fields are empty

**Preconditions:**
- User is creating template

**Test Steps:**
1. Create new template
2. Select schema type
3. Leave required fields empty
4. Try to publish

**Expected Result:**
- Validation error message appears
- Required fields are highlighted
- Template doesn't publish with missing data
- User-friendly error message shown

**Status:** ☐ Pass ☐ Fail

---

### TC-F-046: Invalid Field Values
**Priority:** Medium
**Description:** Verify handling of invalid field values

**Preconditions:**
- User is creating template

**Test Steps:**
1. Create template with Product schema
2. Enter invalid price: "abc"
3. Enter invalid URL format
4. Save template

**Expected Result:**
- Validation catches invalid formats
- Helpful error messages appear
- User can correct errors
- Valid formats are enforced

**Status:** ☐ Pass ☐ Fail

---

### TC-F-047: Database Error Handling
**Priority:** Medium
**Description:** Verify graceful handling of database errors

**Test Steps:**
1. Simulate database connection issue
2. Try to save template
3. Observe error handling

**Expected Result:**
- User-friendly error message displays
- No raw SQL errors shown to user
- Error is logged for admin
- User can retry action

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Multisite

### TC-F-048: Network Activation
**Priority:** Low (if multisite supported)
**Description:** Verify plugin can be network activated

**Preconditions:**
- WordPress Multisite is set up

**Test Steps:**
1. Login as network admin
2. Go to Network Admin > Plugins
3. Network activate Schema Engine

**Expected Result:**
- Plugin activates network-wide
- Works on all subsites
- Settings can be managed per site or network-wide

**Status:** ☐ Pass ☐ Fail ☐ N/A

---

### TC-F-049: Individual Site Activation
**Priority:** Low (if multisite supported)
**Description:** Verify plugin works when activated on individual subsite

**Preconditions:**
- WordPress Multisite is set up

**Test Steps:**
1. Navigate to individual subsite
2. Activate plugin on that site only
3. Test functionality

**Expected Result:**
- Plugin works correctly on individual site
- Doesn't affect other subsites
- Data is site-specific

**Status:** ☐ Pass ☐ Fail ☐ N/A

---

## Test Suite: Uninstallation

### TC-F-050: Plugin Deactivation
**Priority:** High
**Description:** Verify plugin can be deactivated cleanly

**Preconditions:**
- Plugin is activated

**Test Steps:**
1. Go to Plugins page
2. Deactivate Schema Engine
3. Check for errors

**Expected Result:**
- Plugin deactivates successfully
- No PHP errors occur
- Site remains functional
- Data is preserved

**Status:** ☐ Pass ☐ Fail

---

### TC-F-051: Plugin Deletion
**Priority:** High
**Description:** Verify plugin can be deleted with data cleanup option

**Preconditions:**
- Plugin is deactivated

**Test Steps:**
1. Delete plugin from Plugins page
2. Check database for leftover tables/options

**Expected Result:**
- Plugin files are removed
- If cleanup option enabled, data is removed
- If cleanup disabled, data is preserved
- No orphaned files remain

**Status:** ☐ Pass ☐ Fail

---

## Notes
- All tests should be performed on a staging environment first
- Document any issues found with screenshots and steps to reproduce
- Retest after bug fixes
- Test on multiple browsers (Chrome, Firefox, Safari, Edge)
- Test on different WordPress versions (latest and one version back)
