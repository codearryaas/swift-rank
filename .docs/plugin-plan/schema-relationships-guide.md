# Schema Relationships & Graph Implementation Guide

> **Purpose**: This guide explores the concept of Schema Relationships (Linked Data), analyzes competitor implementations, and outlines a technical roadmap for implementing a connected Schema Graph in Schema Engine.

## 🧠 The Concept: Graph vs. Blobs

### The "Blob" Approach (Current)
Currently, Schema Engine outputs separate JSON-LD blocks for each schema type on a page.
```html
<script type="application/ld+json">{ "@type": "Article", ... }</script>
<script type="application/ld+json">{ "@type": "Person", ... }</script>
<script type="application/ld+json">{ "@type": "BreadcrumbList", ... }</script>
```
While valid, search engines have to guess the relationship between these entities. Is the "Person" the author of the "Article"? Is the "Article" part of the "WebSite"?

### The "Graph" Approach (Goal)
The goal is to output a single, interconnected graph using the `@graph` property and `@id` references.
```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Article",
      "@id": "https://site.com/post/#article",
      "author": { "@id": "https://site.com/#person" },
      "isPartOf": { "@id": "https://site.com/#webpage" }
    },
    {
      "@type": "Person",
      "@id": "https://site.com/#person",
      "name": "John Doe"
    },
    {
      "@type": "WebPage",
      "@id": "https://site.com/post/#webpage",
      "breadcrumb": { "@id": "https://site.com/post/#breadcrumb" }
    }
  ]
}
```
**Benefits:**
1.  **Explicit Relationships**: No guessing for Google.
2.  **Efficiency**: Shared entities (like Organization) are defined once and referenced multiple times.
3.  **Context**: Defines the "Main Entity" of the page clearly.

---

## 🕵️ Competitor Analysis

### Yoast SEO
- **Strategy**: "The Graph". Yoast is the pioneer of this approach.
- **Implementation**:
    -   Always outputs a `@graph` array.
    -   Standardized IDs: `url/#website`, `url/#webpage`, `url/#article`, `url/#author`.
    -   Automatic linking: The Article automatically references the WebPage it sits on, and the Author user.
-   **Developer API**: Allows manipulating the graph pieces via filters.

### Rank Math
- **Strategy**: Hybrid / Auto-ID.
-   **Implementation**:
    -   Generates IDs for main entities.
    -   Allows "nesting" via their UI (adding a Review inside a Product).
    -   Uses variable references to link data.

### Schema Pro
-   **Strategy**: Mapping & Nesting.
-   **Implementation**:
    -   Focuses heavily on mapping custom fields.
    -   "Nesting" is achieved by mapping a field to another schema type's properties.
    -   Less focus on a unified global graph, more on rich individual schema trees.

---

## 🚀 Implementation Plan for Schema Engine

To move from "Blobs" to "Graph", we need a 3-phase approach.

### Phase 1: ID Generation System (The Foundation)
We need a consistent way to generate unique IDs for entities.

**New Class: `Schema_Reference_Manager`**
```php
class Schema_Reference_Manager {
    public static function get_id($type, $context_id = null) {
        $url = get_permalink($context_id) ?: home_url();
        $hash = strtolower($type);
        return $url . '#' . $hash;
    }
}
```

**Update Builders**:
Update `Schema_Builder_Interface` and all schema types to support an `get_id()` method.

### Phase 2: The Graph Output (The Switch)
Modify `Schema_Output_Handler` to collect schemas instead of outputting them immediately.

**Current Logic**:
```php
foreach ($schemas as $schema) {
    $this->output_json_ld($schema); // Outputs <script>...
}
```

**New Logic**:
```php
$graph = [];
foreach ($schemas as $schema) {
    // Add @id if missing
    if (!isset($schema['@id'])) {
        $schema['@id'] = Schema_Reference_Manager::get_id($schema['@type']);
    }
    $graph[] = $schema;
}

// Output single script tag
$output = [
    '@context' => 'https://schema.org',
    '@graph' => $graph
];
echo '<script type="application/ld+json">' . json_encode($output) . '</script>';
```

### Phase 3: UI for Relationships (The Feature)
Give users control over connections.

**New Field Type: `schema_reference`**
In the React Metabox, add a field that lets users select another schema to link to.

**Example UI**:
-   **Field Label**: "Author"
-   **Input**: Dropdown (Select from existing Person schemas or Users)
-   **Output**: Instead of a string, it outputs `{"@id": "..."}`

**Example Code**:
```php
// In Schema_Article::get_fields()
'author' => [
    'type' => 'schema_reference',
    'target_type' => 'Person',
    'label' => 'Author'
]
```

---

## 🛠️ Technical Roadmap

1.  **Create `Schema_Reference_Manager` class** in `includes/utils/`.
2.  **Update `Schema_Output_Handler`** to support `@graph` output mode (toggleable setting initially).
3.  **Refactor Schema Builders** (`Article`, `Product`, etc.) to:
    -   Accept an ID parameter.
    -   Use the Reference Manager to generate default IDs.
4.  **Implement Automatic Linking**:
    -   Link `Article` -> `isPartOf` -> `WebPage`.
    -   Link `WebPage` -> `isPartOf` -> `WebSite`.
    -   Link `WebSite` -> `publisher` -> `Organization`.

## ⚠️ Challenges & Considerations

-   **External Schemas**: If a user manually adds schema via a custom field, it might not be in our graph. We need a way to ingest raw JSON-LD into the graph.
-   **ID Collisions**: Handled! We implemented a collision detection system in `Schema_Output_Handler`.
    -   If multiple schemas of the same type exist (e.g., multiple `VideoObject`s), we append a counter suffix.
    -   Example: `#videoobject`, `#videoobject-2`, `#videoobject-3`.
    -   This ensures every entity in the graph has a globally unique `@id`.

---

**Recommendation**: Start with **Phase 1 & 2** (Backend logic) to enable the "Graph" structure automatically. Then move to **Phase 3** (UI) to give users manual control.
