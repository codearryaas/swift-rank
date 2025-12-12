---
description: Migrate a feature or component from Schema Engine (Free) to Schema Engine Pro
---
This workflow outlines the steps to move functionality from the Free plugin to the Pro plugin, ensuring proper separation of concerns and licensing enforcement.

### 1. Identify and Move Files
- [ ] Identify the PHP class or file responsible for the feature in the Free plugin (`schema-engine`).
- [ ] Move the file to the appropriate directory in the Pro plugin (`schema-engine-pro`).
    - *Example*: Move `includes/admin/class-user-profile-schema.php` to `includes/class-schema-engine-pro-user-profile.php`.
- [ ] Rename the class to follow Pro naming conventions (prefix with `Schema_Engine_Pro_`).
    - *Example*: Rename `Schema_Engine_User_Profile` to `Schema_Engine_Pro_User_Profile`.

### 2. Update Plugin Initialization
- [ ] **Free Plugin**: Remove the `require_once` and class initialization from `schema-engine.php` (or wherever it was loaded).
- [ ] **Pro Plugin**: Add the `require_once` and class initialization to `schema-engine-pro.php`.
    - Ensure it is wrapped in appropriate checks (e.g., `is_admin()`).

### 3. Implement Filters for Shared Logic
If the feature relies on core logic in the Free plugin that needs to be extended:
- [ ] **Free Plugin**: Refactor the core method to remove the specific logic (e.g., a `switch` case).
- [ ] **Free Plugin**: Add an `apply_filters` hook in place of the removed logic.
    - *Example*: `return apply_filters('schema_engine_evaluate_rule', $match, $rule);`
- [ ] **Pro Plugin**: Create a class (e.g., `Schema_Engine_Pro_Logic`) handled the logic.
- [ ] **Pro Plugin**: Hook into the new filter to run the Pro-specific logic.

### 4. Restrict Frontend Output
- [ ] In the Free plugin's output handler (e.g., `class-schema-output-handler.php`), wrap the call to the feature's output method in a Pro version check.
    - *Example*:
      ```php
      if (defined('SCHEMA_ENGINE_PRO_VERSION')) {
          // Output Pro feature
      }
      ```

### 5. Update Frontend UI (React/JS)
- [ ] In Javascript files, check for the Pro activation flag (`isProActivated` or similar).
- [ ] Conditionally render or hide UI components based on this flag.
    - *Example*:
      ```javascript
      if (isProActivated) {
          options.push({ value: 'pro_feature', label: 'Pro Feature' });
      }
      ```
- [ ] Ensure the Pro flag is localized in the PHP script (e.g., `wp_localize_script`).

### 6. Verify
- [ ] **Build**: Run `npm run build` or `yarn build` to update assets.
- [ ] **Test Free**: Ensure feature is NOT available when Pro is disabled (or code detects it's missing).
- [ ] **Test Pro**: Ensure feature IS available and functioning when Pro is enabled.
