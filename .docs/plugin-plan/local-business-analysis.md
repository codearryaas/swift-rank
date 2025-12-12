# Feature Analysis: Local Business & Opening Hours

## 1. Current Implementation Status

### Free Version (`schema-engine`)
*   **Schema Type**: `LocalBusiness` is fully registered and supported.
*   **Subtypes**: Extensive list supported (Restaurant, Dentist, Plumber, etc.).
*   **Fields**: Name, URL, Description, Image, Phone, Email, Price Range, Address, Geo Coordinates, Payment Accepted, Currencies, Social Profiles.
*   **Opening Hours Logic**: The `Schema_LocalBusiness` class *has* the logic to build `openingHoursSpecification` if data is provided (lines 111-116).
*   **UI Limitation**: The `openingHours` field definition in `get_fields()` is marked as `'isPro' => true` (line 495). This hides the input field in the Free version's React metabox.

### Pro Version (`schema-engine-pro`)
*   **UI Component**: `OpeningHoursField.js` provides a user-friendly interface for setting hours per day (Open/Closed, Time Range).
*   **Functionality**: Allows setting open/close times for Monday-Sunday.

## 2. Competitive Analysis (Opening Hours)

| Plugin | Free Version | Pro Version |
| :--- | :--- | :--- |
| **Schema Engine** | ❌ (UI Hidden) | ✅ Full UI |
| **Rank Math** | ✅ Basic (Open 24/7 or simple) | ✅ Advanced (Multiple slots) |
| **Yoast SEO** | ❌ (Requires Local SEO plugin) | ✅ (Local SEO plugin) |
| **AIOSEO** | ❌ | ✅ Full |
| **Schema Pro** | N/A | ✅ Full |

## 3. "Free vs Pro" Dilemma

The user asked: *"Do we need to add on free?"*

### Arguments for FREE:
1.  **Completeness**: A `LocalBusiness` schema without opening hours is often invalid or generates warnings in Google Search Console. Providing a "broken" or incomplete schema in Free hurts trust.
2.  **Competitor Parity**: Rank Math Free offers basic opening hours. To compete with Rank Math, we should offer at least a simple version.
3.  **User Acquisition**: "Fix your Local SEO errors" is a great hook. If Free users see errors because of missing hours, they might blame the plugin rather than upgrade.

### Arguments for PRO:
1.  **Upsell Value**: Local SEO is a high-value feature. Businesses are willing to pay for it.
2.  **Complexity**: The UI for opening hours (multi-day, exceptions) is complex to build and maintain.

### Recommendation
**Hybrid Approach**:
*   **Free**: Add a simple "Standard Hours" text field or a simplified "Open 9-5 Mon-Fri" checkbox. Or, allow manual entry of the `openingHours` string (e.g., "Mo-Fr 09:00-17:00").
*   **Pro**: Keep the rich `OpeningHoursField` UI (per-day toggles, time pickers) and add "Special Days/Holidays" support.

## 4. Recommended Next Steps
1.  **Immediate**: Keep as Pro-only for now to protect revenue, but ensure the Free version doesn't output invalid schema if hours are missing (it currently doesn't output the field at all, which is valid but less useful).
2.  **Future**: Move a *simplified* version to Free (e.g., "Same hours every day") and keep the granular control in Pro.
