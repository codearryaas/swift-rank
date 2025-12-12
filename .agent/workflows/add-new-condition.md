---
description: Add a new condition type to the Schema Engine plugin
---
This workflow outlines the steps to add a new condition type (e.g., "Taxonomy", "Author", "Date") to the Schema Engine plugin.

### 1. Update React UI (Frontend)
- [ ] Locate `src/template-metabox/components/ConditionsTab.js`.
- [ ] Add the new condition type to `getConditionTypeOptions()`:
    ```javascript
    { value: 'new_condition_type', label: __('New Condition Label', 'schema-engine') }
    ```
- [ ] Implement the UI for the value field in `renderValueField()`:
    ```javascript
    case 'new_condition_type':
        return (
            <TextControl
                value={rule.value}
                onChange={(val) => updateRule(groupIndex, ruleIndex, 'value', val)}
            />
        );
    ```
    - *Tip*: Use `SelectControl`, `FormTokenField`, or other components as needed.
- [ ] (Optional) If this is a Pro-only feature, ensure it's wrapped in `if (isProActivated)` check or logic.

### 2. Implement Backend Logic (PHP)
- [ ] **Free Plugin**: Open `includes/class-schema-engine-conditions.php`.
    - If implementing in Free core, add a new case to `evaluate_rule()`:
      ```php
      case 'new_condition_type':
          $result = self::evaluate_new_condition_type_rule($value);
          break;
      ```
    - Implement the helper method `evaluate_new_condition_type_rule($value)`.

- [ ] **Pro Plugin (Recommended for Pro features)**: Open `includes/class-schema-engine-pro-conditions.php`.
    - Handle the condition in `evaluate_pro_rules()`:
      ```php
      if ('new_condition_type' === $condition_type) {
          return $this->evaluate_new_condition_type_rule($rule);
      }
      ```
    - Implement the helper method.

### 3. Register Data Sources (If needed)
- [ ] If your UI needs data (e.g., list of taxonomies), inject it via `wp_localize_script`.
- [ ] Open `includes/admin/cpt/class-cpt-metabox.php` (for free) or relevant Pro admin class.
- [ ] Add the data to the `schemaEngineMetabox` object in `wp_localize_script`.

### 4. Verify
- [ ] **Build**: Run `npm run build` or `yarn build`.
- [ ] **Test Admin**: Verify the new condition appears in the dropdown and saves correctly.
- [ ] **Test Frontend**: Verify the condition correctly evaluates to true/false on the frontend.
