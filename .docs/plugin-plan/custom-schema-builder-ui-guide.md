# Custom Schema Builder - UI Guide & Implementation Plan

> **Status**: Planning Phase
> **Target**: Pro Version Feature
> **Priority**: High

## 📋 Overview

The **Custom Schema Builder** is a visual, no-code interface that allows power users to construct complex, nested JSON-LD schema structures without writing code. It bridges the gap between pre-defined templates and raw code editing, offering complete flexibility while maintaining ease of use.

### Target Audience
- **SEO Agencies**: Need to implement specific, niche schema types for clients.
- **Power Users**: Want to go beyond standard schema types (e.g., `MedicalEntity`, `FinancialProduct`).
- **Developers**: Want a faster way to map dynamic WordPress data to schema properties.

---

## 🎨 User Interface Design

The builder will use a **split-screen interface** to maximize usability and clarity.

### Layout Structure

```
+---------------------------------------------------------------+
|  [ Header: Template Name | Save Button | Preview Toggle ]     |
+---------------------------------------------------------------+
|                               |                               |
|  **Left Panel: Structure**    |  **Right Panel: Properties**  |
|                               |                               |
|  [ Tree View of Schema ]      |  [ Context-Aware Editor ]     |
|                               |                               |
|  root (Article)               |  Selected Node: "author"      |
|  ├── headline                 |                               |
|  ├── datePublished            |  Type: Person                 |
|  ├── author (Person)          |                               |
|  │   ├── name                 |  [+ Add Property]             |
|  │   └── url                  |                               |
|  └── publisher (Org)          |  Properties:                  |
|      └── name                 |  - name: [Post Author Name]   |
|                               |  - url: [Post Author URL]     |
|                               |                               |
+---------------------------------------------------------------+
```

### Key UI Components

#### 1. The Structure Tree (Left Panel)
- **Visual Hierarchy**: Displays the nested structure of the JSON-LD.
- **Interactions**:
  - Click to select a node (object or property).
  - Drag-and-drop to reorder properties.
  - Right-click context menu: "Delete", "Duplicate", "Wrap in Array".
- **Indicators**:
  - Icons for different data types (String, Number, Object, Array).
  - Warning icons for missing required properties.

#### 2. The Property Editor (Right Panel)
- **Dynamic Form**: Changes based on the selected node type.
- **Schema.org Browser**:
  - When adding a property, a searchable dropdown shows valid properties for the current `@type`.
  - Tooltips display Schema.org definitions.
- **Value Mapper**:
  - **Static Value**: User types a fixed string/number.
  - **Dynamic Variable**: User selects from a "Variable Picker" (e.g., Post Title, Custom Field, Yoast SEO Description).
  - **Global Reference**: Link to a global entity (e.g., Organization Settings).

#### 3. The Variable Picker (Modal/Popover)
- A unified interface to select dynamic data.
- **Categories**: Post Data, Author Data, Site Data, Custom Fields (ACF/Meta Box), WooCommerce Data.
- **Search**: "Find variable..."

---

## 🛠 Core Features

### 1. Schema.org Type Browser
- **Function**: Allows users to set the `@type` of an object.
- **Data Source**: Index of Schema.org types (possibly cached locally or queried via API).
- **UI**: Searchable dropdown with descriptions.
  - *Example*: User searches "Med", sees "MedicalEntity", "MedicalCondition", etc.

### 2. Smart Property Auto-Complete
- **Function**: Suggests valid properties for the selected `@type`.
- **Logic**: If `@type` is `Recipe`, suggest `cookTime`, `prepTime`, `ingredients`.
- **Validation**: Flag invalid properties (e.g., adding `isbn` to a `Person`).

### 3. Dynamic Data Mapping
- **Function**: Map schema fields to WordPress data.
- **Supported Sources**:
  - Core: Title, Content, Excerpt, Date, ID, URL, Thumbnail.
  - Author: Name, Bio, Avatar, Meta.
  - Custom Fields: Post Meta, ACF, Pods, Meta Box.
  - Shortcodes: Parse shortcode output.

### 4. Visual JSON-LD Preview
- **Function**: Real-time preview of the generated code.
- **Toggle**: Switch between "Builder View" and "Code View".
- **Live Update**: Updates instantly as the user modifies the structure.

### 5. Template Management
- **Save & Reuse**: Save custom structures as templates.
- **Conditions**: Apply templates based on rules (e.g., "Post Type is 'Book'", "Category is 'Reviews'").

---

## 🏗 Technical Architecture

### Data Storage
Custom schema templates will be stored as a custom post type (`sm_template`) or a serialized array in options, similar to current templates but with a flag `is_custom_builder`.

**Data Structure Example:**
```json
{
  "root": {
    "@type": "Book",
    "properties": [
      {
        "key": "name",
        "value": "{post_title}",
        "type": "text"
      },
      {
        "key": "author",
        "type": "object",
        "schemaType": "Person",
        "properties": [
          {
            "key": "name",
            "value": "{author_name}",
            "type": "text"
          }
        ]
      }
    ]
  }
}
```

### React Components Structure
- `SchemaBuilder`: Main container.
- `SchemaTree`: Recursive component for the left panel.
- `PropertyInspector`: Right panel form.
- `TypeSelector`: AsyncSelect component for Schema.org types.
- `VariableInput`: Input field with a button to open the Variable Picker.

---

## 🚀 User Flow: Creating a "Book" Schema

1.  **Start**: User goes to **Schema Engine > Templates > Add New**.
2.  **Select Type**: Chooses "Custom Schema" (instead of a preset like Article).
3.  **Initialize**: The builder opens with a blank root object.
4.  **Set Root Type**: User searches for "Book" in the `@type` selector.
5.  **Add Properties**:
    -   User clicks "+ Add Property".
    -   Selects `name` -> Maps to "Post Title" variable.
    -   Selects `isbn` -> Maps to Custom Field `book_isbn`.
    -   Selects `author` -> Sets type to `Person`.
6.  **Nested Object**:
    -   Inside `author`, adds `name` -> Maps to "Author Name".
7.  **Review**: User clicks "Preview" to see the JSON-LD.
8.  **Save**: User saves the template and sets display conditions (e.g., "Post Type: Books").

---

## 📝 Future Enhancements (Phase 2)

-   **Import from URL**: Scrape a URL and rebuild its schema in the builder.
-   **Validation API**: Real-time check against Google's Rich Result Test.
-   **Global Snippets**: Create reusable fragments (e.g., a standard "Publisher" object) to drop into any template.
