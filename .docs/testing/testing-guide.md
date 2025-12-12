# Schema Engine Testing Guide

## Table of Contents
1. [Introduction](#introduction)
2. [Test Environment Setup](#test-environment-setup)
3. [Testing Approach](#testing-approach)
4. [Test Case Structure](#test-case-structure)
5. [Testing Tools](#testing-tools)
6. [Test Execution](#test-execution)
7. [Bug Reporting](#bug-reporting)
8. [Testing Checklist](#testing-checklist)
9. [Best Practices](#best-practices)
10. [Appendix](#appendix)

---

## Introduction

This guide provides comprehensive instructions for testing the Schema Engine plugin (Free and Pro versions). It covers manual testing procedures, automated testing tools, and best practices for ensuring quality and reliability.

### Purpose
- Ensure plugin functionality works as expected
- Verify schema markup validity and Google compliance
- Catch bugs before release
- Maintain high quality standards
- Document testing procedures for team members

### Scope
This guide covers:
- Free plugin features and schema types
- Pro plugin features and schema types
- WordPress compatibility testing
- Third-party plugin integration testing
- Performance and security testing

---

## Test Environment Setup

### 1. Local Development Environment

#### Requirements
- **PHP Version:** 7.4 or higher (test with multiple versions: 7.4, 8.0, 8.1, 8.2)
- **WordPress Version:** 6.0 or higher (test with latest and one version back)
- **Database:** MySQL 5.7+ or MariaDB 10.3+
- **Web Server:** Apache 2.4+ or Nginx 1.18+

#### Recommended Setup Tools
- **Local by Flywheel** - Easy WordPress local development
- **Laravel Valet** - Lightweight development environment (Mac)
- **XAMPP/MAMP** - Cross-platform development stack
- **Docker** - Containerized development environment

#### Setup Steps

1. **Create Fresh WordPress Installation**
   ```bash
   # Using WP-CLI
   wp core download
   wp core config --dbname=test_db --dbuser=root --dbpass=password
   wp core install --url=http://test.local --title="Test Site" --admin_user=admin --admin_password=admin --admin_email=admin@test.local
   ```

2. **Install Schema Engine Free**
   - Download latest version from repository
   - Upload to `/wp-content/plugins/schema-engine/`
   - Activate plugin
   - Verify no activation errors

3. **Install Schema Engine Pro** (for Pro testing)
   - Upload Pro plugin to `/wp-content/plugins/schema-engine-pro/`
   - Activate Pro plugin
   - Activate license (use test license key)

4. **Enable Debug Mode**
   ```php
   // Add to wp-config.php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   define('WP_DEBUG_DISPLAY', false);
   define('SCRIPT_DEBUG', true);
   ```

5. **Install Helper Plugins**
   - Query Monitor - Debug database queries and performance
   - Debug Bar - WordPress debugging toolbar
   - User Switching - Quickly switch between user roles

### 2. Staging Environment

Create a staging environment that mirrors production:
- Same PHP version as production
- Same WordPress version
- Same theme
- Same must-use plugins
- Test with real content (sanitized)

### 3. Testing Plugins to Install

For integration testing, install:
- **SEO Plugins:** Yoast SEO, Rank Math
- **Page Builders:** Elementor, Beaver Builder
- **E-commerce:** WooCommerce, Easy Digital Downloads
- **Forms:** Contact Form 7, Gravity Forms
- **Caching:** WP Super Cache, W3 Total Cache
- **Job Boards:** WP Job Manager

---

## Testing Approach

### 1. Types of Testing

#### Manual Testing
- Hands-on testing of features and user workflows
- Exploratory testing to find edge cases
- User experience evaluation
- Visual regression testing

#### Functional Testing
- Verify each feature works as documented
- Test all user actions and workflows
- Validate expected outputs
- Check error handling

#### Integration Testing
- Test plugin compatibility with WordPress core
- Test with popular themes
- Test with third-party plugins
- Verify database interactions

#### Performance Testing
- Measure page load times
- Monitor database query count
- Check memory usage
- Test with large datasets

#### Security Testing
- Test user permission checks
- Verify input sanitization
- Check for XSS vulnerabilities
- Test CSRF protection (nonce verification)
- Validate SQL injection prevention

#### Regression Testing
- Retest previously fixed bugs
- Verify existing features still work after updates
- Test backward compatibility

### 2. Testing Phases

#### Phase 1: Unit Testing (if automated tests exist)
- Run PHPUnit tests
- Verify individual functions/methods
- Check edge cases and error conditions

#### Phase 2: Feature Testing
- Test each feature independently
- Follow test cases from `/tests/` directory
- Verify against requirements

#### Phase 3: Integration Testing
- Test plugin with WordPress core features
- Test with common themes and plugins
- Verify third-party integrations

#### Phase 4: User Acceptance Testing (UAT)
- Test real-world scenarios
- Get feedback from beta users
- Validate user workflows

#### Phase 5: Release Candidate Testing
- Full regression test
- Performance benchmarking
- Final security audit

---

## Test Case Structure

Each test case follows this format:

```markdown
### TC-XXX-000: Test Case Title
**Priority:** Critical/High/Medium/Low
**Description:** Brief description of what is being tested

**Preconditions:**
- Condition 1 that must be true before testing
- Condition 2

**Test Steps:**
1. Step 1
2. Step 2
3. Step 3

**Expected Result:**
- Expected outcome 1
- Expected outcome 2

**Status:** ☐ Pass ☐ Fail

**Notes:** (optional)
Additional information, known issues, or observations
```

### Test Case Numbering Convention

- **TC-F-XXX:** Free plugin core features
- **TC-FS-XXX:** Free plugin schema types
- **TC-P-XXX:** Pro plugin features
- **TC-PS-XXX:** Pro plugin schema types

### Priority Levels

- **Critical:** Core functionality, data loss risk, security issues
- **High:** Major features, common user actions
- **Medium:** Minor features, uncommon scenarios
- **Low:** Nice-to-have features, rare edge cases

---

## Testing Tools

### 1. Schema Validation Tools

#### Google Rich Results Test
- **URL:** https://search.google.com/test/rich-results
- **Purpose:** Validate schema markup and check rich result eligibility
- **How to Use:**
  1. Enter page URL or paste schema JSON
  2. Click "Test URL" or "Test Code"
  3. Review results for errors and warnings
  4. Check rich result preview

#### Schema.org Validator
- **URL:** https://validator.schema.org/
- **Purpose:** Validate against schema.org specifications
- **How to Use:**
  1. Paste schema JSON-LD
  2. Click "Validate"
  3. Review detected types and properties
  4. Fix any unrecognized properties

#### JSON-LD Playground
- **URL:** https://json-ld.org/playground/
- **Purpose:** Visualize and debug JSON-LD
- **How to Use:**
  1. Paste JSON-LD code
  2. View visualization and expanded form
  3. Check context resolution
  4. Debug syntax issues

### 2. WordPress Testing Tools

#### Query Monitor
- Monitor database queries
- Track PHP errors and notices
- View HTTP API calls
- Check hook execution
- Measure performance

#### Debug Bar
- View queries and cache stats
- Check WordPress constants
- Review action/filter hooks
- Inspect globals

#### WP-CLI
```bash
# Run tests
wp plugin test schema-engine

# Check for errors
wp plugin verify-checksums schema-engine

# Generate test data
wp post generate --count=100
```

### 3. Browser Tools

#### Chrome DevTools
- **Console:** Check for JavaScript errors
- **Network:** Monitor AJAX requests and load times
- **Application:** Inspect localStorage, cookies
- **Lighthouse:** Run performance and SEO audits

#### Keyboard Navigation Test
- Test with Tab, Enter, Escape keys
- Verify focus indicators
- Check screen reader compatibility

### 4. Performance Testing Tools

#### GTmetrix
- **URL:** https://gtmetrix.com/
- Test page load performance
- Get optimization recommendations

#### WebPageTest
- **URL:** https://www.webpagetest.org/
- Detailed performance analysis
- Test from multiple locations

#### P3 Plugin Performance Profiler
- Measure plugin performance impact
- Identify slow plugins
- Compare before/after metrics

### 5. Security Testing Tools

#### WPScan
```bash
# Scan for vulnerabilities
wpscan --url http://test.local --api-token YOUR_TOKEN
```

#### Sucuri SiteCheck
- **URL:** https://sitecheck.sucuri.net/
- Scan for malware and security issues

---

## Test Execution

### 1. Pre-Test Preparation

#### Checklist
- [ ] Environment is clean (fresh WordPress install or reset)
- [ ] Required plugins installed and activated
- [ ] Debug mode enabled
- [ ] Test data prepared
- [ ] Browser cache cleared
- [ ] Test case document opened
- [ ] Screen recording started (if applicable)

### 2. Executing Test Cases

#### Process
1. **Read entire test case** before starting
2. **Check preconditions** are met
3. **Execute each step** exactly as written
4. **Observe results** carefully
5. **Compare** actual vs expected results
6. **Mark status** (Pass/Fail)
7. **Document issues** if test fails
8. **Take screenshots** of errors

#### Tips
- Don't skip steps
- Don't assume - verify everything
- Test both positive and negative scenarios
- Clear cache between tests when relevant
- Use different user roles for permission tests

### 3. Recording Results

#### For Passing Tests
```markdown
**Status:** ☑ Pass
**Test Date:** 2024-01-15
**Tester:** John Doe
**Browser:** Chrome 120 / Firefox 121
**Notes:** All features worked as expected
```

#### For Failing Tests
```markdown
**Status:** ☑ Fail
**Test Date:** 2024-01-15
**Tester:** John Doe
**Browser:** Chrome 120
**Failure Details:**
- Step 3 failed: Schema did not output
- Error in console: "undefined property 'schema'"
**Screenshots:** bug-001-screenshot.png
**Bug Report:** #BUG-1234
```

---

## Bug Reporting

### 1. Bug Report Template

```markdown
## Bug Report #XXX

**Title:** Clear, concise description

**Priority:** Critical/High/Medium/Low

**Environment:**
- Plugin Version: 1.2.3
- WordPress Version: 6.4.2
- PHP Version: 8.1
- Browser: Chrome 120 (if applicable)
- Theme: Twenty Twenty-Four

**Steps to Reproduce:**
1. Step 1
2. Step 2
3. Step 3

**Expected Behavior:**
What should happen

**Actual Behavior:**
What actually happens

**Screenshots/Videos:**
[Attach files or links]

**Console Errors:**
```
Error message from browser console
```

**PHP Errors:**
```
Error from debug.log
```

**Additional Context:**
Any other relevant information
```

### 2. Bug Severity Levels

#### Critical (P0)
- Site crashes or becomes inaccessible
- Data loss or corruption
- Security vulnerabilities
- Complete feature failure
- **Action:** Fix immediately, hotfix release

#### High (P1)
- Major feature broken
- Significant user experience issue
- Affects many users
- Workaround is difficult
- **Action:** Fix in next release

#### Medium (P2)
- Minor feature issue
- Moderate user experience impact
- Affects some users
- Workaround available
- **Action:** Fix when possible

#### Low (P3)
- Cosmetic issues
- Edge case bugs
- Minimal impact
- Easy workaround
- **Action:** Fix in future release

### 3. Bug Tracking

Use GitHub Issues, Jira, or similar tool:
- Assign priority label
- Assign to developer
- Link to test case
- Track status (Open, In Progress, Testing, Closed)
- Reference in version control commits

---

## Testing Checklist

### Pre-Release Testing Checklist

#### General
- [ ] All test cases executed
- [ ] No critical or high-priority bugs
- [ ] Performance benchmarks met
- [ ] Security audit completed
- [ ] Documentation updated
- [ ] Changelog prepared

#### Functional Testing
- [ ] Installation and activation work
- [ ] Settings save correctly
- [ ] Templates create, edit, delete
- [ ] Schema outputs on frontend
- [ ] Dynamic fields populate
- [ ] Knowledge Base functions
- [ ] Post metabox works
- [ ] All schema types validate

#### Compatibility Testing
- [ ] WordPress latest version
- [ ] WordPress previous version
- [ ] PHP 7.4, 8.0, 8.1, 8.2
- [ ] Default WordPress themes
- [ ] Popular themes (Astra, GeneratePress)
- [ ] Common plugins (Yoast, Elementor)
- [ ] WooCommerce integration (if Pro)
- [ ] Block editor and Classic editor

#### Browser Testing
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile browsers (iOS Safari, Chrome Mobile)

#### Performance Testing
- [ ] Page load time acceptable
- [ ] Database queries optimized
- [ ] Memory usage normal
- [ ] No JavaScript errors
- [ ] No PHP warnings/notices

#### Security Testing
- [ ] Input sanitization works
- [ ] Output escaping in place
- [ ] Nonce verification present
- [ ] Permission checks enforced
- [ ] No SQL injection possible
- [ ] No XSS vulnerabilities

#### Accessibility Testing
- [ ] Keyboard navigation works
- [ ] ARIA labels present
- [ ] Screen reader compatible
- [ ] Color contrast sufficient
- [ ] Focus indicators visible

### Post-Release Monitoring

After release, monitor:
- [ ] User feedback and bug reports
- [ ] WordPress.org support forum
- [ ] Google Search Console for schema errors
- [ ] Site performance metrics
- [ ] Update/activation success rate

---

## Best Practices

### 1. Testing Best Practices

#### Do's
- ✅ Test with fresh WordPress install
- ✅ Test with real content, not Lorem Ipsum
- ✅ Test all user roles (Admin, Editor, Author, Contributor)
- ✅ Test responsive layouts (desktop, tablet, mobile)
- ✅ Document all issues immediately
- ✅ Retest after bug fixes
- ✅ Clear cache between tests
- ✅ Use version control for test data
- ✅ Automate repetitive tests when possible
- ✅ Test error scenarios, not just happy path

#### Don'ts
- ❌ Don't skip preconditions
- ❌ Don't test on production site
- ❌ Don't assume previous tests still pass
- ❌ Don't report bugs without steps to reproduce
- ❌ Don't test with outdated browsers/WordPress
- ❌ Don't ignore console warnings
- ❌ Don't test only with sample data
- ❌ Don't mark test as pass without verification

### 2. Schema Testing Best Practices

#### Validation
- Always validate with Google Rich Results Test
- Cross-check with Schema.org validator
- Test schema updates when content changes
- Verify dynamic fields populate correctly
- Check schema appears in correct location (head vs body)

#### Real-World Testing
- Test with actual post content
- Use realistic field values
- Test with missing optional fields
- Test with long content
- Test with special characters and HTML entities

#### Performance
- Check schema doesn't slow page load
- Verify caching works (if implemented)
- Test with large number of posts
- Monitor database query count

### 3. Documentation

#### Test Documentation
- Keep test cases up to date
- Document workarounds for known issues
- Record test environment details
- Maintain test data sets
- Version control test plans

#### Bug Documentation
- Include clear reproduction steps
- Attach screenshots/videos
- Note affected versions
- Link to related issues
- Update when status changes

---

## Appendix

### A. Useful Commands

#### WP-CLI Commands
```bash
# Reset database
wp db reset --yes

# Install test plugins
wp plugin install query-monitor --activate
wp plugin install debug-bar --activate

# Generate test content
wp post generate --count=50
wp user generate --count=10

# Clear cache
wp cache flush
wp transient delete --all

# Export test data
wp export --dir=/path/to/exports/

# Check for errors
wp plugin verify-checksums --all
```

#### Database Queries
```sql
-- Check for schema templates
SELECT * FROM wp_posts WHERE post_type = 'sm_template';

-- View plugin options
SELECT * FROM wp_options WHERE option_name LIKE 'schema_engine%';

-- Check post meta
SELECT * FROM wp_postmeta WHERE meta_key LIKE '_schema_engine%';
```

### B. Test Data Templates

#### Sample Article Schema
```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Test Article Title",
  "description": "Test article description for schema validation",
  "image": "https://example.com/image.jpg",
  "author": {
    "@type": "Person",
    "name": "John Doe"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Test Publisher",
    "logo": {
      "@type": "ImageObject",
      "url": "https://example.com/logo.jpg"
    }
  },
  "datePublished": "2024-01-15T08:00:00+00:00",
  "dateModified": "2024-01-15T10:30:00+00:00"
}
```

#### Sample Product Schema
```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "Test Product",
  "description": "Test product description",
  "image": "https://example.com/product.jpg",
  "sku": "TEST-SKU-001",
  "brand": {
    "@type": "Brand",
    "name": "Test Brand"
  },
  "offers": {
    "@type": "Offer",
    "price": "99.99",
    "priceCurrency": "USD",
    "availability": "https://schema.org/InStock",
    "url": "https://example.com/product"
  }
}
```

### C. Common Issues and Solutions

#### Issue: Schema not appearing in page source
**Solution:**
- Check if template is assigned to post
- Verify plugin is activated
- Clear page cache
- Check for JavaScript errors
- Verify template fields are filled

#### Issue: Google Rich Results Test shows errors
**Solution:**
- Check required fields are present
- Verify date format is ISO 8601
- Ensure URLs are absolute, not relative
- Check image URLs are accessible
- Validate JSON syntax

#### Issue: Dynamic fields not populating
**Solution:**
- Verify field syntax: `{field_name}`
- Check post has the field value
- Clear template cache if exists
- Test with different post

#### Issue: Performance degradation
**Solution:**
- Enable caching
- Optimize database queries
- Reduce number of meta queries
- Use transients for expensive operations
- Profile with Query Monitor

### D. Resources

#### Official Documentation
- WordPress Codex: https://codex.wordpress.org/
- WordPress Developer Resources: https://developer.wordpress.org/
- Schema.org: https://schema.org/
- Google Search Central: https://developers.google.com/search

#### Testing Tools
- Google Rich Results Test: https://search.google.com/test/rich-results
- Schema Markup Validator: https://validator.schema.org/
- JSON-LD Playground: https://json-ld.org/playground/
- GTmetrix: https://gtmetrix.com/
- WebPageTest: https://www.webpagetest.org/

#### Community
- WordPress Support Forums: https://wordpress.org/support/
- Schema Engine GitHub: [Link to your repo]
- Stack Overflow: https://stackoverflow.com/questions/tagged/schema.org

---

## Conclusion

Thorough testing is essential for maintaining a high-quality plugin. Follow this guide systematically, document all findings, and continuously improve the testing process based on lessons learned.

### Testing Schedule Recommendation

- **Daily:** Run smoke tests during development
- **Weekly:** Execute full test suite during active development
- **Pre-Release:** Complete all test cases + regression testing
- **Post-Release:** Monitor for 48 hours, address urgent issues
- **Monthly:** Review and update test cases

### Continuous Improvement

- Regularly review and update test cases
- Add tests for newly discovered edge cases
- Automate repetitive manual tests
- Gather feedback from users
- Learn from bug patterns

---

**Version:** 1.0
**Last Updated:** 2024-01-15
**Maintained By:** Schema Engine Team
