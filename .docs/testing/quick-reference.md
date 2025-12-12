# Schema Engine Testing - Quick Reference

## Test Case Locations

```
tests/
├── free/
│   ├── core-features.md       (51 tests)
│   └── schema-types.md        (42 tests)
└── pro/
    ├── pro-features.md        (47 tests)
    └── pro-schema-types.md    (45 tests)
```

## Test Execution Time Estimates

| Test Suite | Test Count | Estimated Time | Priority |
|------------|------------|----------------|----------|
| Smoke Tests | 10-15 | 30 minutes | Critical |
| Free Core Features | 51 | 3-4 hours | High |
| Free Schema Types | 42 | 3-4 hours | High |
| Pro Features | 47 | 3-4 hours | High |
| Pro Schema Types | 45 | 3-4 hours | High |
| **Full Test Suite** | **185** | **12-16 hours** | - |

## Critical Test Cases (Must Pass)

### Free Plugin
- **TC-F-001:** Plugin Installation
- **TC-F-002:** Plugin Activation
- **TC-F-005:** Create New Template
- **TC-F-026:** Template Selection in Post
- **TC-F-029:** Schema Output in Page Source
- **TC-F-030:** Valid JSON-LD Format
- **TC-F-041:** User Permission Check
- **TC-F-042:** XSS Protection
- **TC-F-043:** SQL Injection Protection
- **TC-FS-002:** Article Schema Output Validation
- **TC-FS-012:** Product Schema Google Validation

### Pro Plugin
- **TC-P-001:** Pro Plugin Installation
- **TC-P-002:** Pro Plugin Activation
- **TC-P-003:** License Activation
- **TC-P-011:** Apply Schema Preset
- **TC-PS-001:** Create Recipe Schema Template
- **TC-PS-006:** Recipe Schema Validation
- **TC-PS-017:** Event Schema Validation
- **TC-PS-025:** HowTo Schema Validation

## Test Priority Quick Guide

### 🔴 Critical (Must Fix Immediately)
- Site crashes
- Data loss
- Security vulnerabilities
- Complete feature failure

### 🟠 High (Fix Before Release)
- Major feature broken
- Significant UX issues
- Affects many users

### 🟡 Medium (Fix When Possible)
- Minor feature issues
- Moderate UX impact
- Workaround available

### 🟢 Low (Future Release)
- Cosmetic issues
- Edge cases
- Minimal impact

## Essential Testing Commands

### WP-CLI
```bash
# Reset test environment
wp db reset --yes && wp core install

# Generate test data
wp post generate --count=50
wp user generate --count=5

# Clear cache
wp cache flush
wp transient delete --all

# Check for errors
wp plugin verify-checksums schema-engine
```

### Database Queries
```sql
-- View schema templates
SELECT * FROM wp_posts WHERE post_type = 'sm_template';

-- View plugin settings
SELECT * FROM wp_options WHERE option_name LIKE 'schema_engine%';

-- View post schema assignments
SELECT * FROM wp_postmeta WHERE meta_key = '_schema_engine_template';
```

## Schema Validation Quick Checks

### 1. Google Rich Results Test
```
URL: https://search.google.com/test/rich-results
Input: Page URL or JSON-LD code
Check: ✓ Eligible for rich results, ✓ No errors
```

### 2. Schema.org Validator
```
URL: https://validator.schema.org/
Input: JSON-LD code
Check: ✓ Valid schema.org types, ✓ Recognized properties
```

### 3. JSON-LD Playground
```
URL: https://json-ld.org/playground/
Input: JSON-LD code
Check: ✓ Valid JSON syntax, ✓ Context resolves
```

## Common Test Scenarios

### Test: Article Schema
1. Create Article template
2. Set fields: headline, author, publisher, date
3. Assign to blog post
4. Verify schema in page source
5. Validate with Google Rich Results Test

### Test: Product Schema
1. Create Product template
2. Set fields: name, price, SKU, availability
3. Assign to product post
4. Check offers object structure
5. Validate with Google Rich Results Test

### Test: WooCommerce Integration (Pro)
1. Install WooCommerce
2. Create WooCommerce product
3. Apply Product schema preset
4. Verify price from WooCommerce
5. Check aggregateRating with reviews

### Test: Schema Preset (Pro)
1. Open preset modal
2. Filter by schema type
3. Search for preset
4. Apply preset to template
5. Verify fields populated

## Environment Setup Checklist

### Initial Setup
- [ ] Fresh WordPress installation
- [ ] PHP 7.4+ (test multiple versions)
- [ ] MySQL 5.7+ or MariaDB 10.3+
- [ ] WP_DEBUG enabled
- [ ] Query Monitor installed
- [ ] Browser cache cleared

### Per-Test Setup
- [ ] Test environment clean/reset
- [ ] Required plugins installed
- [ ] Test data prepared
- [ ] Cache cleared
- [ ] Console open (DevTools)

## Bug Report Quick Template

```markdown
**Bug ID:** #XXX
**Priority:** Critical/High/Medium/Low
**Test Case:** TC-XXX-000

**Environment:**
- Plugin: 1.2.3
- WordPress: 6.4.2
- PHP: 8.1
- Browser: Chrome 120

**Steps:**
1. [First step]
2. [Second step]
3. [Third step]

**Expected:** [What should happen]
**Actual:** [What happened]

**Screenshot:** [Attach]
**Console Error:** [Copy error]
```

## Browser Testing Matrix

| Browser | Version | Priority | Device |
|---------|---------|----------|--------|
| Chrome | Latest | High | Desktop |
| Firefox | Latest | High | Desktop |
| Safari | Latest | High | Desktop/Mac |
| Edge | Latest | Medium | Desktop |
| iOS Safari | Latest | High | Mobile |
| Chrome Mobile | Latest | High | Mobile |

## Integration Testing Partners

### Essential
- ✅ Default WordPress themes (Twenty Twenty-Four)
- ✅ Gutenberg block editor
- ✅ Classic editor

### High Priority
- ✅ Yoast SEO
- ✅ Rank Math
- ✅ WooCommerce (Pro)
- ✅ Elementor
- ✅ Beaver Builder

### Medium Priority
- ✅ Easy Digital Downloads (Pro)
- ✅ WP Job Manager (Pro)
- ✅ Contact Form 7
- ✅ Gravity Forms
- ✅ Advanced Custom Fields

## Performance Benchmarks

| Metric | Target | Critical Threshold |
|--------|--------|-------------------|
| Page Load Time Impact | < 100ms | < 200ms |
| Database Queries Added | < 5 | < 10 |
| Memory Usage | < 5MB | < 10MB |
| Schema Generation Time | < 50ms | < 100ms |

## Schema Type Support Matrix

### Free Plugin
| Schema Type | Supported | Subtypes | Rich Results |
|-------------|-----------|----------|--------------|
| Article | ✅ | BlogPosting, NewsArticle | ✅ |
| Product | ✅ | - | ✅ |
| LocalBusiness | ✅ | Multiple | ✅ |
| Organization | ✅ | - | ✅ |
| Person | ✅ | - | ✅ |
| VideoObject | ✅ | - | ✅ |

### Pro Plugin (Additional)
| Schema Type | Supported | Subtypes | Rich Results |
|-------------|-----------|----------|--------------|
| Recipe | ✅ | - | ✅ |
| Event | ✅ | - | ✅ |
| HowTo | ✅ | - | ✅ |
| PodcastEpisode | ✅ | - | ✅ |
| Custom | ✅ | Any | Varies |

## Testing Tools Quick Access

### Validation
- **Google Rich Results:** https://search.google.com/test/rich-results
- **Schema.org Validator:** https://validator.schema.org/
- **JSON-LD Playground:** https://json-ld.org/playground/

### Performance
- **GTmetrix:** https://gtmetrix.com/
- **WebPageTest:** https://www.webpagetest.org/
- **Google PageSpeed:** https://pagespeed.web.dev/

### Security
- **Sucuri SiteCheck:** https://sitecheck.sucuri.net/
- **WPScan:** https://wpscan.com/

### WordPress
- **Query Monitor Plugin:** WordPress.org
- **Debug Bar Plugin:** WordPress.org
- **WP-CLI:** https://wp-cli.org/

## Test Result Tracking

### Simple Spreadsheet Template
| Test ID | Test Name | Status | Date | Tester | Notes |
|---------|-----------|--------|------|--------|-------|
| TC-F-001 | Install Plugin | ✅ Pass | 2024-01-15 | John | - |
| TC-F-002 | Activate Plugin | ✅ Pass | 2024-01-15 | John | - |
| TC-F-005 | Create Template | ❌ Fail | 2024-01-15 | John | #BUG-123 |

### Status Indicators
- ✅ Pass
- ❌ Fail
- ⏸️ Blocked
- ⏭️ Skipped
- 🔄 Retest

## Common Issues & Quick Fixes

### Issue: Schema not appearing
**Quick Fixes:**
1. Clear page cache
2. Check template assigned to post
3. Verify debug mode for errors
4. Check browser console

### Issue: Validation errors
**Quick Fixes:**
1. Check required fields populated
2. Verify date format (ISO 8601)
3. Ensure absolute URLs
4. Validate JSON syntax

### Issue: Dynamic fields empty
**Quick Fixes:**
1. Check field syntax: `{field_name}`
2. Verify post has the data
3. Check field mapping
4. Clear template cache

### Issue: Performance slow
**Quick Fixes:**
1. Check Query Monitor
2. Clear all caches
3. Disable other plugins temporarily
4. Check database indexes

## Pre-Release Final Checklist

### Code Quality
- [ ] No PHP errors/warnings
- [ ] No JavaScript console errors
- [ ] Code follows WordPress standards
- [ ] Security review completed

### Functionality
- [ ] All Critical tests pass
- [ ] All High priority tests pass
- [ ] No data loss scenarios
- [ ] Backward compatibility maintained

### Schema Validation
- [ ] All schema types validate
- [ ] Google Rich Results eligible
- [ ] Dynamic fields work
- [ ] References work correctly

### Performance
- [ ] Load time acceptable
- [ ] Database queries optimized
- [ ] Memory usage normal
- [ ] Caching works (if implemented)

### Compatibility
- [ ] WordPress latest version
- [ ] PHP 7.4 - 8.2
- [ ] Popular themes work
- [ ] Common plugins compatible

### Documentation
- [ ] README updated
- [ ] Changelog prepared
- [ ] User docs current
- [ ] API docs accurate

## Contact & Support

- **Documentation:** [View full testing guide](testing-guide.md)
- **Test Cases:** [View all test cases](../../tests/)
- **Bug Reports:** GitHub Issues
- **Questions:** Support forum

---

**Last Updated:** 2024-01-15
**Version:** 1.0
