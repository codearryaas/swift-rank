# Roadmap: UI for Schema Relationships (Phase 3)

> **Goal**: Empower users to manually link schema entities together via the WordPress admin interface, creating a rich, interconnected Knowledge Graph.

## 🧠 The Concept

Currently, fields like "Author" are often simple text inputs or dropdowns of WordPress users. We want to upgrade this to a **Relationship Field** that can link to:
1.  **WordPress Objects**: Users, Posts, Pages.
2.  **Global Schemas**: Organization, Person (defined in settings).
3.  **Other Schemas on Page**: e.g., linking a Review to a Product on the same page.

## 🛠️ Technical Architecture

### 1. New Field Type: `schema_reference`
We will introduce a new field type in our schema definitions.

```php
// Example in Class_Article_Schema.php
'author' => [
    'type' => 'schema_reference',
    'label' => __('Author', 'schema-engine'),
    'targets' => ['Person', 'Organization'], // Allowed schema types
    'sources' => ['users', 'global_settings'] // Where to look for them
]
```

### 2. React Component: `SchemaReferenceField`
A smart dropdown component in the React Metabox.

*   **UI**: Searchable Select (Combobox).
*   **Options**: Grouped by source.
    *   *Global Settings* (e.g., "Site Organization")
    *   *Users* (e.g., "John Doe")
    *   *Other Schemas* (future: "Product defined on this page")
*   **Value Stored**: A reference object, not just a string.
    ```json
    {
      "type": "reference",
      "source": "user",
      "id": 123
    }
    ```

### 3. Data Resolution (Output Handler)
When generating JSON-LD, we need to intercept these reference objects and convert them into `@id` references.

*   **Input**: `{ "type": "reference", "source": "user", "id": 123 }`
*   **Resolution**: `Schema_Reference_Manager::get_user_id(123)`
*   **Output**: `{ "@id": "https://site.com/author/john/#person" }`

---

## 📅 Implementation Steps

### Step 1: Backend Infrastructure (API & Resolution)
*   [ ] **Create API Endpoint**: `GET /wp-json/schema-engine/v1/entities`
    *   Returns list of potential link targets (Users, Global Schemas).
*   [ ] **Update Reference Manager**: Add methods to resolve different source types to IDs.
    *   `get_user_id($id)`
    *   `get_post_id($id)`
    *   `get_global_id($key)`

### Step 2: Frontend Component (React)
*   [ ] **Create `SchemaReferenceField.js`**:
    *   Uses `react-select` or `ComboboxControl`.
    *   Fetches data from the new API endpoint.
    *   Handles saving the complex value object.
*   [ ] **Register Field Type**: Add to `FieldFactory` or `FieldsBuilder` in React app.

### Step 3: Schema Definition Updates
*   [ ] **Update `Article` Schema**: Change `author` field to `schema_reference`.
*   [ ] **Update `Review` Schema**: Change `itemReviewed` to `schema_reference`.
*   [ ] **Update `Product` Schema**: Change `brand` to `schema_reference`.

### Step 4: Output Logic
*   [ ] **Update `Schema_Output_Handler`**:
    *   Detect fields with `type: reference`.
    *   Call `Schema_Reference_Manager` to get the correct `@id`.
    *   Replace the value with `{"@id": "..."}`.

---

## 🎨 UI Mockup

**Field Label**: Author
**Input**: [ Select Author... ▼ ]
  *   **Global**
      *   🏢 My Company (Organization)
  *   **Users**
      *   👤 John Doe
      *   👤 Jane Smith

---

## 🔄 Backward Compatibility (Critical)

**What happens to existing manual text fields?**
We cannot simply replace text fields with dropdowns, or we lose existing data (e.g., "John Doe" typed manually).

**Strategy: Hybrid Input**
The `SchemaReferenceField` component must support **both** references and custom text.

1.  **Data Storage**:
    *   *New*: `{"type": "reference", "source": "user", "id": 123}`
    *   *Legacy*: `"John Doe"` (string)

2.  **UI Behavior**:
    *   If value is a string -> Show as "Custom Text" value. Allow user to clear and select a reference.
    *   Allow user to type a custom name if the entity doesn't exist in the dropdown (Creatable Select).

3.  **Output Logic**:
    *   If reference -> Output `{"@id": "..."}`
    *   If string -> Output `{"@type": "Person", "name": "John Doe"}` (Preserve old behavior)

### Handling Dependent Fields (e.g., Author URL)
What about fields like `author_url` that usually accompany `author_name`?

*   **If Reference Selected**: The URL is **automatically derived** from the referenced entity (e.g., User Profile URL). The manual `author_url` field should be **hidden** or shown as "Auto-filled".
*   **If Custom Text**: The `author_url` field remains **visible and editable**, allowing the user to manually enter the URL.

---

## ⚠️ Challenges

1.  **Circular References**: A links to B, B links to A. JSON-LD handles this fine with `@id`, but UI needs to be careful not to crash.
2.  **Missing Targets**: What if the referenced User is deleted? Output handler must fallback gracefully (e.g., skip the field or output raw name).
3.  **Performance**: Fetching thousands of users for the dropdown. Need AJAX search for large sites.

## 🚀 Phasing

*   **Phase 3.1**: "Author" linking (Users & Global Org). High impact, easier to implement.
*   **Phase 3.2**: "Internal" linking (Post to Post).
*   **Phase 3.3**: "Intra-page" linking (Schema to Schema on same page). Most complex.
