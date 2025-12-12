# Smart Validation Update

## Changes Made

Added intelligent validation that **detects variables** and applies appropriate sanitization based on whether the field contains a variable or actual data.

### New Helper Method

```php
private function contains_variable($value)
```
- Detects variable patterns like `{post_title}`, `{meta:email}`, `{site_url}`, etc.
- Returns `true` if value contains `{...}` pattern

### Updated Fields with Smart Validation

#### 1. **Email Field** (`organization_email`)
- **With variable** (e.g., `{meta:contact_email}`): Uses `sanitize_text_field()` - preserves variable
- **Without variable** (e.g., `info@example.com`): Uses `sanitize_email()` - validates email format

#### 2. **Phone Field** (`organization_phone`)
- **With variable** (e.g., `{meta:phone}`): Uses `sanitize_text_field()` - preserves variable
- **Without variable** (e.g., `+1 (555) 123-4567`): Validates phone format - allows only numbers, spaces, dashes, parentheses, plus signs, dots

#### 3. **Fax Field** (`organization_fax`)
- **With variable** (e.g., `{meta:fax}`): Uses `sanitize_text_field()` - preserves variable
- **Without variable** (e.g., `+1-555-123-4568`): Validates fax format - allows only numbers, spaces, dashes, parentheses, plus signs, dots

#### 4. **Logo URL** (`organization_logo`)
- **With variable** (e.g., `{featured_image}`): Uses `sanitize_text_field()` - preserves variable
- **Without variable** (e.g., `https://example.com/logo.png`): Uses `esc_url_raw()` - validates URL format

#### 5. **Social Profile URLs** (`organization_social[]`)
- **With variable** (e.g., `{meta:facebook_url}`): Uses `sanitize_text_field()` - preserves variable
- **Without variable** (e.g., `https://facebook.com/page`): Uses `esc_url_raw()` - validates URL format

## Benefits

✅ **Variables work in all fields** - No more stripped variables  
✅ **Actual data is validated** - Email format checked when entering real emails  
✅ **Phone/fax validation** - Removes invalid characters from phone numbers  
✅ **URL validation** - Ensures proper URL format for logos and social links  
✅ **Backward compatible** - Existing data continues to work

## Examples

### Before (Broken)
```
Email: {meta:contact_email}  →  Saved as: (empty - stripped by sanitize_email)
Phone: {meta:phone_number}   →  Saved as: metaphonenumber (letters stripped)
```

### After (Working)
```
Email: {meta:contact_email}  →  Saved as: {meta:contact_email}  ✓
Email: info@example.com      →  Saved as: info@example.com     ✓ (validated)
Phone: {meta:phone_number}   →  Saved as: {meta:phone_number}  ✓
Phone: (555) 123-4567        →  Saved as: (555) 123-4567       ✓ (validated)
Phone: abc123def             →  Saved as: 123                   ✓ (cleaned)
```

## Testing

1. **Test with variables**:
   - Enter `{meta:email}` in email field → Should save as-is
   - Enter `{site_url}/logo.png` in logo field → Should save as-is

2. **Test with real data**:
   - Enter `invalid-email` in email field → Should validate/clean
   - Enter `+1 (555) 123-4567` in phone field → Should keep valid characters
   - Enter `phone: 555-1234 ext 123` in phone field → Should clean to `555-1234123`

3. **Test mixed**:
   - Some fields with variables, some with real data → Each should validate appropriately
