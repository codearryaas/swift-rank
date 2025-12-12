# Schema Engine - Feature Recommendations

> **Research Summary**: Analysis based on current plugin capabilities (v2.x), competitive landscape (Schema Pro, Rank Math, AIOSEO, Yoast SEO), and 2024-2025 schema markup trends.

## Current Features Analysis

### Free Version (Schema Engine)
**Schema Types (Core):**
- Article
- FAQ
- Organization
- Person
- Product
- Video

**Gutenberg Blocks:**
- FAQ Block (with Q&A pairs)
- HowTo Block (with Steps) ✅

**Core Capabilities:**
- JSON-LD output
- Template system with conditional display
- Dynamic variable system
- Post/Template metaboxes
- **[IN PROGRESS]** Schema Graph implementation (Interconnected `@graph` structure)

### Pro Version (Schema Engine Pro)
**Additional Schema Types:**
- Breadcrumb ✅
- Event ✅
- HowTo ✅
- Podcast ✅
- Recipe ✅
- Website ✅
- Custom JSON-LD ✅

**Pro Extensions:**
- **Custom Schema Builder** (Visual JSON-LD editor) ✅
- **Local Business Enhancements**: Opening Hours Field (Multi-day support) ✅
- Video Pro features (Clips, SeekToAction, BroadcastEvent)
- License Management

---

## Competitive Gap Analysis (2024-2025)

### What Competitors Have That We Don't

| Feature | Schema Engine | Schema Pro | Rank Math Pro | AIOSEO Pro | Yoast SEO | Priority |
|---------|---------------|-----------|---------------|------------|-----------|----------|
| **Schema Types Count** | ~15 types | ✅ 20+ | ✅ 35+ | ✅ 20+ | Basic+ | 🔴 HIGH |
| **Schema Graph** | 🚧 In Progress | ✅ | ✅ | ✅ (Visual) | ✅ (Auto) | 🔴 HIGH |
| **Built-in Validation** | ❌ | ✅ | ✅ | ✅ | ❌ | 🔴 HIGH |
| **AI Schema Gen** | ❌ | ❌ | ✅ (Content AI) | ✅ | ✅ | 🔴 HIGH |
| **Custom Builder** | ✅ (Pro) | ❌ | ✅ | ✅ | ❌ | 🟢 DONE |
| **Competitor Import** | ❌ | ❌ | ✅ (URL Import) | ❌ | ❌ | 🟡 MEDIUM |
| **Local Business** | 🚧 (Opening Hours) | ✅ | ✅ Full | ✅ Full | ✅ | 🔴 HIGH |
| **Review Schema** | ❌ | ✅ | ✅ | ✅ | ❌ | 🔴 HIGH |
| **Course/Job/Book** | ❌ | ✅ | ✅ | ✅ | ❌ | 🟡 MEDIUM |

### Key Competitor Trends
1.  **AI Integration**: Rank Math and others are using AI to generate schema from content automatically.
2.  **Visual Validation**: AIOSEO provides a visual graph view to debug connections.
3.  **Interconnectedness**: Yoast and others emphasize a single `@graph` output where entities reference each other (e.g., Article -> Author -> Person).

---

## Recommended Features

### 🎯 HIGH PRIORITY - FREE VERSION

#### 1. **Schema Graph Implementation (Refinement)**
-   **Status**: 🚧 Partially Implemented (Container only, missing connections).
-   **Action**: Refactor to use `@id` references for true interconnectivity.
-   **Analysis**: [Read Schema Graph Analysis](schema-graph-analysis.md)

#### 2. **Built-in Schema Validator**
-   **Why**: #1 trust builder. Users want to know if it works without leaving the dashboard.
-   **Implementation**:
    -   Link to Google Rich Results Test (MVP).
    -   Internal validation logic (Phase 2).

#### 3. **Review/Rating Schema**
-   **Why**: High CTR impact in SERPs.
-   **Types**: Product, Book, Movie, Local Business.

#### 4. **Local Business Enhancements**
-   **Status**: 🚧 Basic type in Free, Opening Hours in Pro.
-   **Analysis**: [Read Local Business Analysis](local-business-analysis.md)
-   **Recommendation**: Consider moving basic "Standard Hours" to Free to avoid invalid schema errors.

### 🚀 HIGH PRIORITY - PRO VERSION

#### 1. **AI Schema Generator**
-   **Why**: The new standard. Competitors are doing it.
-   **Feature**: "Generate Schema from Content" button.

#### 2. **Advanced Local Business**
-   **Current**: Opening Hours added (Pro).
-   **Add**: Service Area (GeoShape), Multiple Locations.

#### 3. **Competitor Schema Import**
-   **Why**: "Steal" competitor strategy.
-   **Feature**: Input URL -> Scrape JSON-LD -> Convert to our Custom Builder format.

#### 4. **Visual Graph View**
-   **Why**: AIOSEO has this. Great for debugging relationships.
-   **Feature**: Visual node graph showing how Article connects to Author/Publisher.

---

## Implementation Roadmap

### Phase 1 (Completed/Current) ✅
1.  ✅ HowTo & FAQ Blocks
2.  ✅ Custom Schema Builder (Pro)
3.  ✅ Opening Hours Field (Pro)
4.  ✅ **Job Posting Schema** (Free) - [Read Analysis](job-posting-analysis.md)
5.  🚧 Schema Graph Architecture (Free)

### Phase 2 (Next 3 Months) - "The Trust & Growth Update"
1.  **Graph Refinement**: Fix `@id` linking for Article/Person/Org.
2.  **Validator**: Add "Test Schema" button.
3.  **Review Schema**: Add Review type.
4.  **Local Business**: Decide on Free vs Pro split for Opening Hours.

### Phase 3 (3-6 Months) - "The AI & Intelligence Update"
1.  **AI Generator**: "Magic Schema" button.
2.  **Competitor Import**: URL scraper.
3.  **Visual Graph**: Interactive node visualization.

---

## Conclusion

**Winning Strategy:**
1.  **Fix the Graph**: Make our schema technically superior by ensuring true interconnectivity (Article -> Author).
2.  **Leverage Free Features**: Promote our **Free Job Posting** schema (which others charge for) as a key differentiator.
3.  **AI & UX**: Move quickly to add AI generation and validation to catch up with market leaders.
