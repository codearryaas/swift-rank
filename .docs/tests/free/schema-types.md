# Schema Engine Free - Schema Types Test Cases

## Test Suite: Article Schema

### TC-FS-001: Create Article Schema Template
**Priority:** Critical
**Description:** Verify Article schema template can be created

**Test Steps:**
1. Create new template
2. Select "Article" schema type
3. Fill required fields:
   - Headline: "{post_title}"
   - Description: "{post_excerpt}"
   - Image: "{featured_image}"
   - Date Published: "{post_date}"
   - Author: Reference to author
   - Publisher: Reference to organization
4. Save template

**Expected Result:**
- Template saves successfully
- All fields are preserved
- Template can be assigned to posts

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-002: Article Schema Output Validation
**Priority:** Critical
**Description:** Verify Article schema outputs valid markup

**Test Steps:**
1. Create post with Article template
2. Publish post
3. View page source
4. Copy schema JSON
5. Test in Google Rich Results Test

**Expected Result:**
- Schema includes @context: "https://schema.org"
- @type is "Article"
- All required properties present: headline, image, author, publisher, datePublished
- Validates without errors in Google Rich Results Test
- Eligible for rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-003: Article Schema - Author Reference
**Priority:** High
**Description:** Verify author reference works correctly

**Test Steps:**
1. Create Knowledge Base Person entity
2. Create Article template
3. Set author field to reference Person entity
4. Assign to post
5. View schema output

**Expected Result:**
- Author appears as nested Person object
- Author @type is "Person"
- Author properties (name, url) populate correctly
- Can also use {post_author_id} for dynamic author

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-004: Article Schema - Publisher Reference
**Priority:** High
**Description:** Verify publisher reference works correctly

**Test Steps:**
1. Create Knowledge Base Organization entity
2. Create Article template
3. Set publisher field to reference Organization
4. View schema output

**Expected Result:**
- Publisher appears as nested Organization object
- Publisher @type is "Organization"
- Publisher must have name and logo
- Schema validates successfully

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: BlogPosting Schema

### TC-FS-005: Create BlogPosting Template
**Priority:** High
**Description:** Verify BlogPosting schema extends Article correctly

**Test Steps:**
1. Create new template
2. Select "Article" type
3. Choose "BlogPosting" subtype
4. Fill fields similar to Article
5. Save template

**Expected Result:**
- BlogPosting is available as Article subtype
- @type in output is "BlogPosting"
- Inherits all Article properties
- Additional blog-specific properties available

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-006: BlogPosting Schema Validation
**Priority:** High
**Description:** Verify BlogPosting schema validates correctly

**Test Steps:**
1. Create post with BlogPosting template
2. View schema output
3. Validate with Google Rich Results Test

**Expected Result:**
- @type is "BlogPosting"
- All Article requirements met
- Validates as valid blog post
- Eligible for Article rich results

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: NewsArticle Schema

### TC-FS-007: Create NewsArticle Template
**Priority:** High
**Description:** Verify NewsArticle schema template creation

**Test Steps:**
1. Create new template
2. Select "Article" type
3. Choose "NewsArticle" subtype
4. Fill required fields
5. Save template

**Expected Result:**
- NewsArticle available as Article subtype
- @type outputs as "NewsArticle"
- All Article properties inherited
- News-specific fields available

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-008: NewsArticle Schema Validation
**Priority:** High
**Description:** Verify NewsArticle meets Google News requirements

**Test Steps:**
1. Create NewsArticle template with all fields
2. Publish news post
3. Validate with Google Rich Results Test

**Expected Result:**
- @type is "NewsArticle"
- Required properties: headline, image, datePublished, dateModified
- Author and publisher included
- Passes Google News validation

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Product Schema

### TC-FS-009: Create Product Schema Template
**Priority:** Critical
**Description:** Verify Product schema template creation

**Test Steps:**
1. Create new template
2. Select "Product" schema type
3. Fill required fields:
   - Name: "{post_title}"
   - Description: "{post_excerpt}"
   - Image: "{featured_image}"
   - SKU: "TEST-SKU-001"
   - Brand: "Test Brand"
   - Offers (price, currency, availability)
4. Save template

**Expected Result:**
- Template saves successfully
- All product fields preserved
- Offers object configured correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-010: Product Schema - Offers Validation
**Priority:** Critical
**Description:** Verify Product offers object is valid

**Test Steps:**
1. Create Product template
2. Configure offers:
   - Price: "99.99"
   - Price Currency: "USD"
   - Availability: "InStock"
   - URL: "{post_url}"
3. View schema output

**Expected Result:**
- Offers @type is "Offer"
- Price is valid number
- Currency is valid ISO code
- Availability uses schema.org URL format
- URL is valid and accessible

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-011: Product Schema - Aggregate Rating
**Priority:** Medium
**Description:** Verify aggregate rating can be added to Product

**Test Steps:**
1. Create Product template
2. Add aggregateRating fields:
   - Rating Value: "4.5"
   - Review Count: "127"
   - Best Rating: "5"
3. View schema output

**Expected Result:**
- AggregateRating object present
- @type is "AggregateRating"
- All rating values valid
- Shows star rating in search results

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-012: Product Schema Google Validation
**Priority:** Critical
**Description:** Verify Product schema passes Google validation

**Test Steps:**
1. Create complete Product template
2. Publish product post
3. Test in Google Rich Results Test

**Expected Result:**
- All required properties present
- Eligible for Product rich results
- No errors or warnings
- Preview shows correctly

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: LocalBusiness Schema

### TC-FS-013: Create LocalBusiness Template
**Priority:** High
**Description:** Verify LocalBusiness schema template creation

**Test Steps:**
1. Create new template
2. Select "LocalBusiness" schema type
3. Fill required fields:
   - Name: "Test Business"
   - Address: Complete postal address
   - Telephone: "+1-555-0100"
   - Opening Hours: Add hours
4. Save template

**Expected Result:**
- Template saves successfully
- Address object structured correctly
- Opening hours format valid
- All business fields preserved

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-014: LocalBusiness - Business Types
**Priority:** High
**Description:** Verify LocalBusiness subtypes work correctly

**Test Steps:**
1. Create LocalBusiness template
2. Test different business types:
   - Restaurant
   - Store
   - Hotel
   - AutoRepair
3. View schema output for each

**Expected Result:**
- Subtype appears as @type
- Each type has specific properties
- Type-specific fields available
- Validates correctly per type

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-015: LocalBusiness - Address Format
**Priority:** High
**Description:** Verify postal address format is correct

**Test Steps:**
1. Create LocalBusiness template
2. Fill address fields:
   - Street Address: "123 Main St"
   - Address Locality: "Springfield"
   - Address Region: "IL"
   - Postal Code: "62701"
   - Address Country: "US"
3. View schema output

**Expected Result:**
- Address @type is "PostalAddress"
- All address components present
- Format matches schema.org spec
- Google Maps compatible

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-016: LocalBusiness - Opening Hours
**Priority:** Medium
**Description:** Verify opening hours specification works

**Test Steps:**
1. Create LocalBusiness template
2. Add opening hours:
   - Monday-Friday: 9:00-17:00
   - Saturday: 10:00-14:00
   - Sunday: Closed
3. View schema output

**Expected Result:**
- openingHoursSpecification array present
- Each day has correct format
- Times in 24-hour format
- Closed days handled properly

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-017: LocalBusiness Schema Validation
**Priority:** High
**Description:** Verify LocalBusiness passes Google validation

**Test Steps:**
1. Create complete LocalBusiness template
2. Publish business post/page
3. Test in Google Rich Results Test

**Expected Result:**
- All required properties present
- Eligible for Local Business rich results
- Shows in Google Maps/Search
- No validation errors

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Organization Schema

### TC-FS-018: Create Organization Template
**Priority:** High
**Description:** Verify Organization schema template creation

**Test Steps:**
1. Create new template
2. Select "Organization" schema type
3. Fill required fields:
   - Name: "{site_name}"
   - URL: "{site_url}"
   - Logo: Upload logo image
   - Contact info
   - Social profiles (sameAs)
4. Save template

**Expected Result:**
- Template saves successfully
- Logo URL valid
- Social profiles array formatted correctly
- Can be used as Knowledge Base entity

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-019: Organization - Logo Requirements
**Priority:** High
**Description:** Verify logo meets Google requirements

**Test Steps:**
1. Create Organization with logo
2. Test logo image:
   - Minimum 112x112px
   - Aspect ratio requirements
3. Validate schema

**Expected Result:**
- Logo appears in schema
- ImageObject format correct
- Meets Google logo guidelines
- Shows in knowledge panel

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-020: Organization - Social Profiles
**Priority:** Medium
**Description:** Verify sameAs social profiles work correctly

**Test Steps:**
1. Create Organization template
2. Add sameAs array with URLs:
   - Facebook page
   - Twitter profile
   - LinkedIn page
   - Instagram profile
3. View schema output

**Expected Result:**
- sameAs is array of URLs
- All URLs are valid and accessible
- Helps with knowledge graph
- Links appear in search results

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-021: Organization Schema Validation
**Priority:** High
**Description:** Verify Organization schema validates correctly

**Test Steps:**
1. Create complete Organization template
2. View schema output
3. Test with Google Rich Results Test

**Expected Result:**
- @type is "Organization"
- Required properties present (name, url, logo)
- No validation errors
- Eligible for knowledge panel

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Person Schema

### TC-FS-022: Create Person Template
**Priority:** High
**Description:** Verify Person schema template creation

**Test Steps:**
1. Create new template
2. Select "Person" schema type
3. Fill fields:
   - Name: "John Doe"
   - Job Title: "CEO"
   - Image: Profile photo
   - URL: Personal website
   - Works For: Organization reference
4. Save template

**Expected Result:**
- Template saves successfully
- All person fields preserved
- Organization reference works
- Can be used in Knowledge Base

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-023: Person - Author Integration
**Priority:** High
**Description:** Verify Person schema integrates with WordPress users

**Test Steps:**
1. Create Person entity in Knowledge Base
2. Link to WordPress user
3. Create Article with author reference
4. View schema output

**Expected Result:**
- Person can be linked to WP user
- Author data pulls from Person entity
- Dynamic fields work ({post_author})
- Author appears correctly in Article schema

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-024: Person - Social Profiles
**Priority:** Medium
**Description:** Verify Person social profiles (sameAs) work

**Test Steps:**
1. Create Person template
2. Add sameAs array with personal URLs:
   - Twitter
   - LinkedIn
   - Personal website
3. View schema output

**Expected Result:**
- sameAs array contains all URLs
- URLs are valid
- Helps with knowledge graph
- Can appear in author rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-025: Person Schema Validation
**Priority:** High
**Description:** Verify Person schema validates correctly

**Test Steps:**
1. Create complete Person template
2. View schema output
3. Test with Google Rich Results Test

**Expected Result:**
- @type is "Person"
- Required properties present (name)
- Image URL valid
- No validation errors

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: VideoObject Schema

### TC-FS-026: Create VideoObject Template
**Priority:** High
**Description:** Verify VideoObject schema template creation

**Test Steps:**
1. Create new template
2. Select "VideoObject" schema type
3. Fill required fields:
   - Name: "{post_title}"
   - Description: "{post_excerpt}"
   - Thumbnail URL: "{featured_image}"
   - Upload Date: "{post_date}"
   - Content URL: Video file URL
   - Embed URL: YouTube/Vimeo URL
4. Save template

**Expected Result:**
- Template saves successfully
- All video fields preserved
- Duration field accepts ISO 8601 format
- Multiple URL types supported

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-027: VideoObject - Duration Format
**Priority:** High
**Description:** Verify video duration format is correct

**Test Steps:**
1. Create VideoObject template
2. Set duration in ISO 8601 format:
   - "PT1H30M" (1 hour 30 minutes)
   - "PT15M" (15 minutes)
   - "PT2M30S" (2 minutes 30 seconds)
3. View schema output

**Expected Result:**
- Duration format is ISO 8601
- Converts correctly from input
- Displays correctly in rich results
- Validates without errors

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-028: VideoObject - Thumbnail Requirements
**Priority:** High
**Description:** Verify video thumbnail meets requirements

**Test Steps:**
1. Create VideoObject with thumbnail
2. Check thumbnail requirements:
   - Minimum 160x90px
   - Maximum 1920x1080px
   - Supported formats: JPG, PNG, GIF
3. Validate schema

**Expected Result:**
- Thumbnail URL is valid
- Image meets size requirements
- Appears in video rich results
- No validation warnings

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-029: VideoObject - YouTube Integration
**Priority:** High
**Description:** Verify YouTube video integration works

**Test Steps:**
1. Create VideoObject template
2. Add YouTube video:
   - Content URL: YouTube watch URL
   - Embed URL: YouTube embed URL
3. Publish post
4. View schema and test

**Expected Result:**
- Both URLs formatted correctly
- embedUrl uses /embed/ format
- Video appears in search results
- Click-through works correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-030: VideoObject Schema Validation
**Priority:** Critical
**Description:** Verify VideoObject passes Google validation

**Test Steps:**
1. Create complete VideoObject template
2. Publish video post
3. Test with Google Rich Results Test

**Expected Result:**
- All required properties present
- Eligible for Video rich results
- Thumbnail shows in preview
- No validation errors

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Schema Relationships

### TC-FS-031: Nested Schema Objects
**Priority:** High
**Description:** Verify nested schema objects work correctly

**Test Steps:**
1. Create Product with nested Offer
2. Create Article with nested Person (author)
3. Create VideoObject with nested Person (creator)
4. View schema outputs

**Expected Result:**
- Nested objects have correct @type
- All nested properties present
- Nesting follows schema.org spec
- Validates correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-032: Schema References
**Priority:** High
**Description:** Verify schema can reference other schemas by @id

**Test Steps:**
1. Create Organization with @id
2. Create Article that references Organization by @id
3. View schema output

**Expected Result:**
- Referenced entity uses @id
- Reference appears as {"@id": "url#id"}
- Reduces duplication
- Validates correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-033: Multiple Schema Types on Page
**Priority:** Medium
**Description:** Verify multiple different schemas can coexist

**Test Steps:**
1. Add Article schema via template
2. Add Organization schema in footer
3. Add BreadcrumbList schema
4. View page source

**Expected Result:**
- All schemas present in separate script tags
- Each schema is valid independently
- No conflicts between schemas
- All validate successfully

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Schema Subtypes

### TC-FS-034: Article Subtypes Selection
**Priority:** Medium
**Description:** Verify Article subtypes can be selected

**Test Steps:**
1. Create Article template
2. View available subtypes:
   - Article (base)
   - BlogPosting
   - NewsArticle
   - ScholarlyArticle
   - TechArticle
3. Select different subtypes

**Expected Result:**
- All Article subtypes available
- Subtype selection changes @type
- Subtype-specific fields appear
- Each validates correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-035: LocalBusiness Subtypes
**Priority:** Medium
**Description:** Verify LocalBusiness subtypes work correctly

**Test Steps:**
1. Create LocalBusiness template
2. Test subtypes:
   - Restaurant
   - Store
   - FoodEstablishment
   - AutoRepair
   - Dentist
3. Check type-specific properties

**Expected Result:**
- All business subtypes available
- @type changes to subtype
- Subtype properties appear
- Each type validates correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-036: Organization Subtypes
**Priority:** Low
**Description:** Verify Organization subtypes if available

**Test Steps:**
1. Create Organization template
2. Check for subtypes:
   - Corporation
   - EducationalOrganization
   - GovernmentOrganization
   - NGO
3. Select different subtypes

**Expected Result:**
- Organization subtypes available
- @type changes accordingly
- Properties match subtype
- Validates per subtype

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Dynamic Field Replacement

### TC-FS-037: Post Meta Fields in Schema
**Priority:** High
**Description:** Verify custom post meta can be used in schema

**Test Steps:**
1. Create custom field "product_price"
2. Create Product template
3. Use {meta:product_price} in price field
4. View schema output

**Expected Result:**
- Meta field value appears in schema
- {meta:field_name} syntax works
- Updates when meta value changes
- Handles missing meta gracefully

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-038: Taxonomy Terms in Schema
**Priority:** Medium
**Description:** Verify taxonomy terms can be used in schema

**Test Steps:**
1. Create Product with category taxonomy
2. Use {category} or {tag} in schema fields
3. View schema output

**Expected Result:**
- Taxonomy terms appear correctly
- Multiple terms handled (array or comma-separated)
- Updates when terms change
- Missing terms handled gracefully

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-039: Site-Wide Dynamic Fields
**Priority:** Medium
**Description:** Verify site-wide dynamic fields work

**Test Steps:**
1. Use dynamic fields in template:
   - {site_name}
   - {site_url}
   - {site_description}
2. View schema output

**Expected Result:**
- All site fields populate correctly
- Values match WordPress settings
- Consistent across all posts
- Update when site settings change

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Schema Validation Tools

### TC-FS-040: Google Rich Results Test
**Priority:** Critical
**Description:** Verify all schemas pass Google Rich Results Test

**Test Steps:**
1. For each schema type, create example
2. Publish and get public URL
3. Test URL in Google Rich Results Test
4. Document results

**Expected Result:**
- All schemas show as "Valid"
- Eligible for rich results (when applicable)
- No errors or critical warnings
- Preview renders correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-041: Schema.org Validator
**Priority:** High
**Description:** Verify schemas validate against schema.org spec

**Test Steps:**
1. Copy schema JSON from page source
2. Paste into schema.org validator
3. Check for errors

**Expected Result:**
- Schema is valid per schema.org
- All properties recognized
- Nesting structure correct
- No unknown properties

**Status:** ☐ Pass ☐ Fail

---

### TC-FS-042: JSON-LD Playground Test
**Priority:** Medium
**Description:** Verify JSON-LD format is correct

**Test Steps:**
1. Copy schema JSON
2. Test in JSON-LD Playground
3. View visualization

**Expected Result:**
- JSON-LD parses correctly
- Graph visualizes properly
- Context resolves correctly
- No syntax errors

**Status:** ☐ Pass ☐ Fail

---

## Notes
- Test each schema type with minimum required fields
- Test each schema type with all optional fields
- Verify rich results eligibility for applicable types
- Document Google Search Console performance
- Test schema updates when post content changes
- Verify schema caching behavior
