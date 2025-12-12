# Feature Analysis: Schema Graph

## 1. Current Implementation Status

### Architecture
*   **Location**: `includes/output/class-schema-output-handler.php`
*   **Method**: `output_schema()` collects all schema arrays (Global, Post-specific, Templates).
*   **Graph Construction**:
    *   It iterates through all schemas.
    *   It assigns an `@id` using `Schema_Reference_Manager` if one is missing.
    *   It wraps everything in a single `@graph` array.
    *   It outputs one `<script type="application/ld+json">` block.

### The Problem (The "Fake" Graph)
While the output is technically a `@graph`, the nodes are **disconnected**.
*   **Article Schema**: Generates an inline `author` object (`@type: Person`) and `publisher` object (`@type: Organization`).
*   **Person Schema**: Generates a separate `Person` node in the graph.
*   **Result**: You have two "Person" entities in the graph (one inside Article, one standalone). Google doesn't know they are the same person.

**Current Output (Simplified):**
```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Article",
      "headline": "Hello World",
      "author": {
        "@type": "Person",
        "name": "Admin"
      }
    },
    {
      "@type": "Person",
      "@id": "https://site.com/#person",
      "name": "Admin",
      "sameAs": ["..."]
    }
  ]
}
```

**Desired Output (True Graph):**
```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Article",
      "headline": "Hello World",
      "author": { "@id": "https://site.com/#person" },
      "isPartOf": { "@id": "https://site.com/#webpage" }
    },
    {
      "@type": "Person",
      "@id": "https://site.com/#person",
      "name": "Admin"
    },
    {
      "@type": "WebPage",
      "@id": "https://site.com/#webpage",
      "url": "https://site.com/hello-world"
    }
  ]
}
```

## 2. Competitive Analysis

*   **Yoast SEO**: The gold standard for Schema Graph. Everything is connected (`Article` -> `WebPage` -> `WebSite` -> `Organization`).
*   **Rank Math**: Uses a similar graph approach.
*   **AIOSEO**: Also uses a graph.

## 3. Recommendations

### 1. Implement "Node Linking" Logic
We need to refactor `Schema_Article`, `Schema_Product`, etc., to accept **IDs** for relations instead of just embedding data.

**Proposed Logic:**
1.  **Global IDs**: Define standard IDs for the main entities:
    *   `#website`
    *   `#organization`
    *   `#person` (for author)
    *   `#webpage` (for current page)
    *   `#primaryimage`
2.  **Refactor Builders**:
    *   Update `Schema_Article` to set `"author": { "@id": "..." }` instead of building the array.
    *   Update `Schema_Article` to set `"isPartOf": { "@id": "#webpage" }`.
3.  **Ensure Nodes Exist**: The `Schema_Output_Handler` must ensure that if an Article references `#person`, the Person schema is actually added to the graph.

### 2. Visual Graph (Pro)
Once the backend logic is fixed, building a Visual Graph in the admin (like AIOSEO) becomes possible and highly valuable.

### Priority
**CRITICAL**. Without this, our "Schema Graph" is just a container, not a semantic structure. This is the biggest technical debt in the plugin right now.
