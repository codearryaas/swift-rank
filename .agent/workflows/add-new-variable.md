---
description: Add new variable placeholders to the Schema Engine Pro plugin
---
This workflow outlines the steps to add new supported variables (e.g., `{custom_data}`, `{user_role}`) to the Schema Engine Pro plugin. These variables can be used in schema templates and are dynamically replaced during frontend output.

### 1. Register Variable Group (Pro)
- [ ] Open `schema-engine-pro/includes/class-schema-variable-replacer-pro.php`.
- [ ] Locate the `register_variable_groups()` method.
- [ ] Add a new group using `$this->register_group()`:
    ```php
    // Add Custom Group
    $this->register_group('my_custom_group', array(
        'label' => __('My Custom Group', 'schema-engine-pro'),
        'icon' => 'admin-generic', // Dashicon name without 'dashicons-' prefix
        'variables' => array(
            array(
                'value' => '{my_variable}',
                'label' => __('My Variable', 'schema-engine-pro'),
                'description' => __('Description of what this variable outputs', 'schema-engine-pro'),
            ),
             // Add more variables...
        ),
    ));
    ```

### 2. Implement Replacement Logic
Determine the scope of your variable: **Post-level**, **Site-level**, **User-level**, or **Dynamic**.

#### Option A: Post-Level Variables (Depends on Current Post)
- [ ] In `class-schema-variable-replacer-pro.php`, override or update `get_post_replacements($post)`.
- [ ] Add logic to calculate the value:
    ```php
    protected function get_post_replacements($post)
    {
        // Get base replacements
        $replacements = parent::get_post_replacements($post);

        // Add your custom logic
        $custom_value = get_post_meta($post->ID, '_some_key', true);
        $replacements['{my_variable}'] = !empty($custom_value) ? $custom_value : '';

        return $replacements;
    }
    ```

#### Option B: Site-Level Variables (Global)
- [ ] In `class-schema-variable-replacer-pro.php`, override or update `get_site_replacements()`.
- [ ] Add logic:
    ```php
    protected function get_site_replacements()
    {
        $replacements = parent::get_site_replacements();
        $replacements['{my_global_var}'] = get_option('some_option');
        return $replacements;
    }
    ```

#### Option C: User-Level Variables (Context Aware)
- [ ] In `class-schema-variable-replacer-pro.php`, update `get_user_replacements()`.
- [ ] Ensure you handle both **Author Archive** context and **Current User** context if applicable used for personalization.

#### Option D: Dynamic Variables (Regex Matched)
- [ ] Use `replace_dynamic_variables($json)` if your variable follows a pattern like `{custom:key}`.
- [ ] Use `preg_replace_callback` to find and replace matches.

### 4. Verify
- [ ] **Admin UI**: Go to Schema Engine > Schema Templates > Edit.
- [ ] **Variable Picker**: Click the variable icon ({}) and verify your new group and variables appear.
- [ ] **Frontend**: Create a schema using the new variable. View the page source and verify the `{variable}` is replaced with the correct value.
