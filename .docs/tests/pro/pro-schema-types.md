# Schema Engine Pro - Pro Schema Types Test Cases

## Test Suite: Recipe Schema

### TC-PS-001: Create Recipe Schema Template
**Priority:** Critical
**Description:** Verify Recipe schema template can be created

**Preconditions:**
- Pro plugin and license activated

**Test Steps:**
1. Create new template
2. Select "Recipe" schema type
3. Fill required fields:
   - Name: "{post_title}"
   - Description: "{post_excerpt}"
   - Image: "{featured_image}"
   - Prep Time: "PT30M" (30 minutes)
   - Cook Time: "PT1H" (1 hour)
   - Total Time: "PT1H30M" (1.5 hours)
   - Recipe Yield: "4 servings"
4. Add ingredients (repeater field)
5. Add instructions (repeater field)
6. Save template

**Expected Result:**
- Template saves successfully
- All recipe fields preserved
- Repeater fields work for ingredients and instructions
- Time format validates (ISO 8601 duration)

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-002: Recipe Ingredients Repeater
**Priority:** High
**Description:** Verify recipe ingredients can be added as array

**Preconditions:**
- Recipe template being edited

**Test Steps:**
1. Locate ingredients field
2. Add multiple ingredients:
   - "2 cups flour"
   - "1 cup sugar"
   - "3 eggs"
   - "1 tsp vanilla extract"
3. Reorder ingredients
4. Delete an ingredient
5. Save template
6. View schema output

**Expected Result:**
- Multiple ingredients can be added
- recipeIngredient is array of strings
- Items can be reordered
- Items can be deleted
- Order preserved in schema output
- Array format is valid

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-003: Recipe Instructions Repeater
**Priority:** High
**Description:** Verify recipe instructions work as HowToStep objects

**Preconditions:**
- Recipe template being edited

**Test Steps:**
1. Locate instructions field
2. Add multiple steps:
   - Step 1: "Preheat oven to 350°F"
   - Step 2: "Mix dry ingredients"
   - Step 3: "Add wet ingredients"
   - Step 4: "Bake for 30 minutes"
3. Reorder steps
4. View schema output

**Expected Result:**
- Multiple steps can be added
- recipeInstructions is array of HowToStep objects
- Each step has @type "HowToStep"
- Each step has text property
- Steps can be reordered
- Step numbers update automatically

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-004: Recipe Time Durations
**Priority:** High
**Description:** Verify recipe time fields accept ISO 8601 duration format

**Preconditions:**
- Recipe template being edited

**Test Steps:**
1. Test various time formats:
   - prepTime: "PT15M" (15 minutes)
   - cookTime: "PT2H" (2 hours)
   - totalTime: "PT2H15M" (2 hours 15 min)
2. Save template
3. View schema output
4. Validate with Google Rich Results Test

**Expected Result:**
- ISO 8601 duration format accepted
- Can input in PT format
- May have helper UI for time input
- Total time should equal prep + cook
- Validates correctly with Google

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-005: Recipe Nutrition Information
**Priority:** Medium
**Description:** Verify nutrition information can be added to Recipe

**Preconditions:**
- Recipe template being edited

**Test Steps:**
1. Locate nutrition section (if available)
2. Add nutrition values:
   - Calories: "250"
   - Fat Content: "10g"
   - Carbohydrate Content: "30g"
   - Protein Content: "8g"
3. Save and view schema output

**Expected Result:**
- Nutrition object present
- @type is "NutritionInformation"
- All nutrition properties valid
- Shows in recipe rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-006: Recipe Schema Validation
**Priority:** Critical
**Description:** Verify Recipe schema passes Google validation

**Preconditions:**
- Complete Recipe template created

**Test Steps:**
1. Create post with Recipe template
2. Publish post
3. Test in Google Rich Results Test
4. Check for recipe rich result eligibility

**Expected Result:**
- All required properties present
- Eligible for Recipe rich results
- Shows recipe preview with image, rating, time, calories
- No validation errors
- Recipe card displays in test tool

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-007: Recipe Rating and Reviews
**Priority:** Medium
**Description:** Verify Recipe can include aggregate rating

**Preconditions:**
- Recipe template being edited

**Test Steps:**
1. Add aggregateRating fields:
   - Rating Value: "4.8"
   - Review Count: "156"
   - Best Rating: "5"
2. Save template
3. View schema output

**Expected Result:**
- AggregateRating object present
- Rating displays in rich results
- Star rating shows in preview
- Review count visible

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-008: Recipe Video
**Priority:** Low
**Description:** Verify Recipe can include video property

**Preconditions:**
- Recipe template being edited

**Test Steps:**
1. Add video field
2. Link to recipe video (YouTube or self-hosted)
3. View schema output

**Expected Result:**
- Video property present
- Can be VideoObject or URL
- Video shows in recipe rich results
- Increases rich result eligibility

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Event Schema

### TC-PS-009: Create Event Schema Template
**Priority:** Critical
**Description:** Verify Event schema template can be created

**Preconditions:**
- Pro plugin and license activated

**Test Steps:**
1. Create new template
2. Select "Event" schema type
3. Fill required fields:
   - Name: "{post_title}"
   - Description: "{post_excerpt}"
   - Image: "{featured_image}"
   - Start Date: "2024-06-15T19:00:00"
   - End Date: "2024-06-15T22:00:00"
   - Location: Add Place or VirtualLocation
4. Save template

**Expected Result:**
- Template saves successfully
- Event fields properly structured
- Date picker or ISO format input works
- Location object configured

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-010: Event - Physical Location
**Priority:** High
**Description:** Verify Event with physical location (Place)

**Preconditions:**
- Event template being edited

**Test Steps:**
1. Select location type: "Place"
2. Fill location details:
   - Name: "Convention Center"
   - Address: Complete postal address
3. Set eventAttendanceMode: "OfflineEventAttendanceMode"
4. View schema output

**Expected Result:**
- Location @type is "Place"
- Address properly nested
- eventAttendanceMode set correctly
- Shows physical location in rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-011: Event - Online/Virtual Event
**Priority:** High
**Description:** Verify Event with virtual location

**Preconditions:**
- Event template being edited

**Test Steps:**
1. Select location type: "VirtualLocation"
2. Add virtual event URL
3. Set eventAttendanceMode: "OnlineEventAttendanceMode"
4. View schema output

**Expected Result:**
- Location @type is "VirtualLocation"
- URL property present
- eventAttendanceMode set to online
- Virtual event indicator in rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-012: Event - Hybrid Event
**Priority:** Medium
**Description:** Verify Event can be both physical and virtual

**Preconditions:**
- Event template being edited

**Test Steps:**
1. Configure both Place and VirtualLocation
2. Set eventAttendanceMode: "MixedEventAttendanceMode"
3. View schema output

**Expected Result:**
- Both location types present
- eventAttendanceMode set to mixed
- Shows as hybrid event
- Both locations display in rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-013: Event Dates and Times
**Priority:** Critical
**Description:** Verify Event date/time handling

**Preconditions:**
- Event template being edited

**Test Steps:**
1. Set startDate: "2024-07-20T18:00:00-05:00"
2. Set endDate: "2024-07-20T21:00:00-05:00"
3. Test with timezone
4. Test multi-day event
5. View schema output

**Expected Result:**
- Dates in ISO 8601 format
- Timezone included if specified
- Start before end validation
- Multi-day events supported
- Shows correctly in calendar

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-014: Event Organizer
**Priority:** Medium
**Description:** Verify Event can have organizer (Organization or Person)

**Preconditions:**
- Event template being edited
- Knowledge Base has Organization/Person entities

**Test Steps:**
1. Add organizer field
2. Reference Organization or Person from KB
3. View schema output

**Expected Result:**
- Organizer object present
- Can be Organization or Person
- Reference works correctly
- Organizer details in rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-015: Event Offers (Tickets)
**Priority:** High
**Description:** Verify Event can have ticket offers

**Preconditions:**
- Event template being edited

**Test Steps:**
1. Add offers field
2. Configure ticket offer:
   - Price: "25.00"
   - Price Currency: "USD"
   - URL: Ticket purchase URL
   - Availability: "InStock"
   - Valid From: Start date of sales
3. View schema output

**Expected Result:**
- Offers array present
- Each offer is Offer type
- Price and currency valid
- Purchase URL included
- Shows "Buy Tickets" in rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-016: Event Status
**Priority:** Medium
**Description:** Verify Event can have status (scheduled, cancelled, postponed)

**Preconditions:**
- Event template being edited

**Test Steps:**
1. Set eventStatus field:
   - EventScheduled (default)
   - EventCancelled
   - EventPostponed
   - EventRescheduled
2. View schema output for each

**Expected Result:**
- eventStatus uses schema.org EventStatusType
- Different statuses display correctly
- Cancelled/postponed shows in rich results
- Warning messages appear as appropriate

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-017: Event Schema Validation
**Priority:** Critical
**Description:** Verify Event schema passes Google validation

**Preconditions:**
- Complete Event template created

**Test Steps:**
1. Create event post
2. Publish event
3. Test in Google Rich Results Test
4. Check for Event rich result eligibility

**Expected Result:**
- All required properties present (name, startDate, location)
- Eligible for Event rich results
- Shows event preview with date, location, tickets
- No validation errors
- Calendar integration possible

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: HowTo Schema

### TC-PS-018: Create HowTo Schema Template
**Priority:** Critical
**Description:** Verify HowTo schema template can be created

**Preconditions:**
- Pro plugin and license activated

**Test Steps:**
1. Create new template
2. Select "HowTo" schema type
3. Fill required fields:
   - Name: "{post_title}"
   - Description: "{post_excerpt}"
   - Image: "{featured_image}"
   - Total Time: "PT2H"
4. Add steps (repeater field)
5. Save template

**Expected Result:**
- Template saves successfully
- HowTo fields properly structured
- Steps repeater works
- Time duration validates

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-019: HowTo Steps with Text
**Priority:** High
**Description:** Verify HowTo steps can be added with text instructions

**Preconditions:**
- HowTo template being edited

**Test Steps:**
1. Add multiple steps:
   - Step 1: "Gather all materials"
   - Step 2: "Prepare the work area"
   - Step 3: "Follow safety precautions"
   - Step 4: "Begin assembly"
2. Reorder steps
3. View schema output

**Expected Result:**
- Multiple steps can be added
- step property is array of HowToStep
- Each step has @type "HowToStep"
- Each step has text or name
- Steps numbered correctly
- Shows as numbered list in rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-020: HowTo Steps with Images
**Priority:** Medium
**Description:** Verify HowTo steps can include images

**Preconditions:**
- HowTo template being edited

**Test Steps:**
1. Add step with image:
   - Text: "Connect the cables"
   - Image: Upload step image
2. Add more steps with images
3. View schema output

**Expected Result:**
- Each step can have image
- Image shows in step
- image property in HowToStep
- Step images display in rich results
- Visual guide enhances usability

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-021: HowTo - Tools Required
**Priority:** Medium
**Description:** Verify HowTo can list required tools

**Preconditions:**
- HowTo template being edited

**Test Steps:**
1. Add tool field (if available)
2. List required tools:
   - "Screwdriver"
   - "Wrench"
   - "Pliers"
3. View schema output

**Expected Result:**
- tool property present
- Array of HowToTool or text
- Tools listed in rich results
- Helps users prepare

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-022: HowTo - Supplies/Materials
**Priority:** Medium
**Description:** Verify HowTo can list required supplies

**Preconditions:**
- HowTo template being edited

**Test Steps:**
1. Add supply field
2. List required supplies:
   - "Wood boards"
   - "Screws"
   - "Paint"
3. View schema output

**Expected Result:**
- supply property present
- Array of HowToSupply or text
- Supplies listed separately from tools
- Shows in rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-023: HowTo - Estimated Cost
**Priority:** Low
**Description:** Verify HowTo can include estimated cost

**Preconditions:**
- HowTo template being edited

**Test Steps:**
1. Add estimatedCost field
2. Set to "50 USD" or MonetaryAmount object
3. View schema output

**Expected Result:**
- estimatedCost property present
- Can be text or MonetaryAmount
- Shows cost estimate in rich results
- Helps users budget

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-024: HowTo - Total Time
**Priority:** High
**Description:** Verify HowTo time duration works

**Preconditions:**
- HowTo template being edited

**Test Steps:**
1. Set totalTime: "PT3H30M" (3 hours 30 min)
2. View schema output
3. Test with different durations

**Expected Result:**
- totalTime in ISO 8601 duration format
- Shows time estimate in rich results
- Helps users plan
- Validates correctly

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-025: HowTo Schema Validation
**Priority:** Critical
**Description:** Verify HowTo schema passes Google validation

**Preconditions:**
- Complete HowTo template created

**Test Steps:**
1. Create HowTo post
2. Publish post
3. Test in Google Rich Results Test
4. Check for HowTo rich result eligibility

**Expected Result:**
- All required properties present (name, step)
- Eligible for HowTo rich results
- Shows step-by-step preview
- Images appear if included
- No validation errors

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: PodcastEpisode Schema

### TC-PS-026: Create PodcastEpisode Template
**Priority:** High
**Description:** Verify PodcastEpisode schema template can be created

**Preconditions:**
- Pro plugin and license activated

**Test Steps:**
1. Create new template
2. Select "PodcastEpisode" schema type
3. Fill required fields:
   - Name: "{post_title}"
   - Description: "{post_excerpt}"
   - Date Published: "{post_date}"
   - Duration: "PT45M"
4. Add audio file (associatedMedia)
5. Add podcast series info
6. Save template

**Expected Result:**
- Template saves successfully
- All podcast fields preserved
- Audio media object configured
- Series relationship set up

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-027: PodcastEpisode - Audio File
**Priority:** Critical
**Description:** Verify PodcastEpisode can link to audio file

**Preconditions:**
- PodcastEpisode template being edited

**Test Steps:**
1. Add associatedMedia field
2. Configure MediaObject:
   - @type: "MediaObject"
   - contentUrl: Link to MP3 file
   - encodingFormat: "audio/mpeg"
3. View schema output

**Expected Result:**
- associatedMedia is MediaObject
- contentUrl points to audio file
- encodingFormat specified
- Audio file accessible
- Shows play button in rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-028: PodcastEpisode - Series Information
**Priority:** High
**Description:** Verify PodcastEpisode can reference podcast series

**Preconditions:**
- PodcastEpisode template being edited

**Test Steps:**
1. Add partOfSeries field
2. Configure PodcastSeries:
   - @type: "PodcastSeries"
   - name: "My Podcast Show"
   - url: Series homepage
3. View schema output

**Expected Result:**
- partOfSeries object present
- Series name and URL included
- Links episode to series
- Series info in rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-029: PodcastEpisode - Duration
**Priority:** High
**Description:** Verify podcast duration format

**Preconditions:**
- PodcastEpisode template being edited

**Test Steps:**
1. Set duration: "PT1H15M30S" (1 hr, 15 min, 30 sec)
2. Test various durations
3. View schema output

**Expected Result:**
- Duration in ISO 8601 format
- Accurate episode length
- Shows duration in rich results
- Helps listeners plan

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-030: PodcastEpisode Schema Validation
**Priority:** High
**Description:** Verify PodcastEpisode schema validates correctly

**Preconditions:**
- Complete PodcastEpisode template created

**Test Steps:**
1. Create podcast episode post
2. Publish episode
3. Test in Google Rich Results Test
4. Check for Podcast rich result eligibility

**Expected Result:**
- All required properties present
- Eligible for Podcast rich results (if supported)
- Audio file accessible
- No validation errors
- Episode appears in podcast platforms

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Custom Schema

### TC-PS-031: Custom Schema - JSON Editor
**Priority:** Critical
**Description:** Verify Custom schema type provides JSON editor

**Preconditions:**
- Pro plugin and license activated

**Test Steps:**
1. Create new template
2. Select "Custom" schema type
3. Locate JSON editor
4. Enter custom JSON schema

**Expected Result:**
- JSON editor appears
- Syntax highlighting works
- Line numbers shown
- Can input any schema.org type
- Validation on save

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-032: Custom Schema - Course Type
**Priority:** Medium
**Description:** Verify can create Course schema using Custom type

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Enter Course schema JSON:
```json
{
  "@type": "Course",
  "name": "{post_title}",
  "description": "{post_excerpt}",
  "provider": {
    "@type": "Organization",
    "name": "My University"
  },
  "hasCourseInstance": {
    "@type": "CourseInstance",
    "courseMode": "online",
    "courseWorkload": "PT10H"
  }
}
```
2. Save and test

**Expected Result:**
- Course schema outputs correctly
- Dynamic fields work in JSON
- Validates with Google
- Eligible for Course rich results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-033: Custom Schema - Book Type
**Priority:** Medium
**Description:** Verify can create Book schema using Custom type

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Enter Book schema JSON:
```json
{
  "@type": "Book",
  "name": "{post_title}",
  "author": {
    "@type": "Person",
    "name": "Author Name"
  },
  "isbn": "978-3-16-148410-0",
  "publisher": {
    "@type": "Organization",
    "name": "Publisher Name"
  }
}
```
2. Save and test

**Expected Result:**
- Book schema outputs correctly
- All Book properties supported
- Validates correctly
- Shows in book search results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-034: Custom Schema - Movie Type
**Priority:** Low
**Description:** Verify can create Movie schema using Custom type

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Enter Movie schema:
```json
{
  "@type": "Movie",
  "name": "{post_title}",
  "director": {
    "@type": "Person",
    "name": "Director Name"
  },
  "duration": "PT2H15M",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "8.5",
    "bestRating": "10"
  }
}
```
2. Save and test

**Expected Result:**
- Movie schema outputs correctly
- All Movie properties work
- Rating displays correctly
- Shows in movie search results

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-035: Custom Schema - Any Schema.org Type
**Priority:** Medium
**Description:** Verify Custom type supports any valid schema.org type

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Try various schema.org types:
   - SoftwareApplication
   - MusicAlbum
   - MedicalCondition
   - Service
   - RealEstateListing
2. Test each

**Expected Result:**
- Any valid @type accepted
- All schema.org types supported
- Complex nested structures work
- Validates per type specifications

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-036: Custom Schema - Dynamic Fields in JSON
**Priority:** High
**Description:** Verify dynamic fields work inside custom JSON

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Use dynamic fields in JSON:
   - {post_title}
   - {post_excerpt}
   - {post_date}
   - {featured_image}
   - {meta:custom_field}
2. Save and view output

**Expected Result:**
- All dynamic fields replaced
- Works same as in regular templates
- No syntax issues with curly braces
- Complex field paths work

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-037: Custom Schema - JSON Validation
**Priority:** High
**Description:** Verify JSON syntax validation in Custom schema

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Enter invalid JSON:
   - Missing closing bracket
   - Extra comma
   - Missing quotes
2. Try to save
3. Observe validation

**Expected Result:**
- Syntax errors caught
- Error message shows line number
- Cannot save invalid JSON
- Helpful error descriptions
- Suggests fixes

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-038: Custom Schema - Complex Nested Objects
**Priority:** Medium
**Description:** Verify deeply nested schema objects work

**Preconditions:**
- Custom schema type selected

**Test Steps:**
1. Create complex nested structure:
   - Multiple levels deep
   - Arrays of objects
   - References within references
2. Save and view output

**Expected Result:**
- Complex nesting supported
- All levels render correctly
- Arrays within objects work
- Performance acceptable
- Valid JSON-LD output

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Schema Type Ordering

### TC-PS-039: Pro Schema Types Order in Dropdown
**Priority:** Medium
**Description:** Verify Pro schema types appear in correct order

**Preconditions:**
- Pro plugin activated

**Test Steps:**
1. Create new template
2. Open schema type dropdown
3. Note order of schema types

**Expected Result:**
- Free types appear first (Article, BlogPosting, etc.)
- Pro types appear after Free types:
  - Recipe
  - Event
  - HowTo
  - PodcastEpisode
  - Custom (always last)
- Order matches registration priority

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-040: Preset Types Order Matches Dropdown
**Priority:** Medium
**Description:** Verify preset modal types order matches schema dropdown

**Preconditions:**
- Pro plugin activated
- Presets modal open

**Test Steps:**
1. Open schema presets modal
2. View left panel type list
3. Compare to schema type dropdown order

**Expected Result:**
- Preset type list order matches dropdown
- Same types in same order
- Consistent user experience
- No confusion between interfaces

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Pro Schema Type Features

### TC-PS-041: Schema Type Icons
**Priority:** Low
**Description:** Verify all Pro schema types have appropriate icons

**Preconditions:**
- Pro plugin activated

**Test Steps:**
1. View schema type dropdown
2. Check icon for each Pro type:
   - Recipe: chef-hat
   - Event: calendar
   - HowTo: list-checks
   - PodcastEpisode: podcast/mic
   - Custom: code
3. Check in presets modal too

**Expected Result:**
- All Pro types have icons
- Icons are appropriate and recognizable
- Icons display correctly
- Same icons in dropdown and presets

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-042: Schema Type Descriptions
**Priority:** Low
**Description:** Verify Pro schema types have helpful descriptions

**Preconditions:**
- Pro plugin activated

**Test Steps:**
1. View schema type dropdown or info
2. Read descriptions for Pro types
3. Check clarity and usefulness

**Expected Result:**
- Each Pro type has description
- Descriptions are clear and helpful
- Explains when to use each type
- No grammatical errors

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-043: Pro Type Field Variations
**Priority:** Medium
**Description:** Verify each Pro schema type has appropriate fields

**Preconditions:**
- Pro plugin activated

**Test Steps:**
1. Create template for each Pro type
2. Review available fields
3. Compare to schema.org spec

**Expected Result:**
- Fields match schema.org requirements
- Required fields are marked
- Optional fields available
- Field types appropriate (text, date, array, etc.)
- No missing important fields

**Status:** ☐ Pass ☐ Fail

---

## Test Suite: Integration Between Pro Types

### TC-PS-044: Recipe with HowTo Instructions
**Priority:** Low
**Description:** Verify Recipe can use HowToStep objects in instructions

**Preconditions:**
- Recipe template being edited

**Test Steps:**
1. Create Recipe
2. Use HowToStep format for instructions
3. View schema output

**Expected Result:**
- recipeInstructions can be HowToStep array
- Both formats supported
- Rich results work with either format
- Flexibility for content creators

**Status:** ☐ Pass ☐ Fail

---

### TC-PS-045: Event with Podcast Performance
**Priority:** Low
**Description:** Verify Event can reference podcast as performance

**Preconditions:**
- Event template being edited

**Test Steps:**
1. Create Event (e.g., podcast recording)
2. Add performer (podcast host)
3. Link to resulting podcast episode
4. View schema output

**Expected Result:**
- Event and Podcast can be related
- performer property works
- recordedIn or similar properties available
- Schemas complement each other

**Status:** ☐ Pass ☐ Fail

---

## Notes
- Test all Pro schema types with Google Rich Results Test
- Verify each type is eligible for appropriate rich results
- Document any Google Search Console insights
- Test schema updates when post content changes
- Verify proper handling of optional vs required fields
- Test error states and validation for each field type
- Document any browser-specific issues
- Test with real-world content examples
- Verify mobile responsiveness of admin interfaces
- Check for accessibility compliance (WCAG)
