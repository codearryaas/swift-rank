# Schema Engine Test Cases

This directory contains comprehensive test cases for the Schema Engine plugin (Free and Pro versions).

## Directory Structure

```
tests/
├── README.md                          # This file
├── free/
│   ├── core-features.md              # Free plugin core functionality tests
│   └── schema-types.md               # Free plugin schema types tests
└── pro/
    ├── pro-features.md               # Pro plugin features tests
    └── pro-schema-types.md           # Pro plugin schema types tests
```

## Test Case Files

### Free Plugin

#### [Core Features](free/core-features.md) (51 Test Cases)
Tests for core functionality available in the free version:
- Installation & Activation (3 tests)
- Schema Templates (5 tests)
- Schema Type Selector (3 tests)
- Knowledge Base (5 tests)
- Dynamic Field Values (5 tests)
- Settings Page (3 tests)
- Post Metabox (4 tests)
- Schema Output (4 tests)
- FAQ Block (3 tests)
- Performance & Compatibility (5 tests)
- Security (4 tests)
- Error Handling (3 tests)
- Multisite (2 tests)
- Uninstallation (2 tests)

#### [Schema Types](free/schema-types.md) (42 Test Cases)
Tests for schema types included in free version:
- Article Schema (4 tests)
- BlogPosting Schema (2 tests)
- NewsArticle Schema (2 tests)
- Product Schema (4 tests)
- LocalBusiness Schema (5 tests)
- Organization Schema (4 tests)
- Person Schema (4 tests)
- VideoObject Schema (5 tests)
- Schema Relationships (3 tests)
- Schema Subtypes (3 tests)
- Dynamic Field Replacement (3 tests)
- Schema Validation Tools (3 tests)

### Pro Plugin

#### [Pro Features](pro/pro-features.md) (47 Test Cases)
Tests for Pro-only features:
- Pro Plugin Activation (5 tests)
- Pro Schema Types (2 tests)
- Schema Presets (5 tests)
- Advanced Fields (4 tests)
- Schema Reference Fields (3 tests)
- Custom Schema Builder (4 tests)
- WooCommerce Integration (5 tests)
- Easy Digital Downloads Integration (3 tests)
- WP Job Manager Integration (3 tests)
- Pro Settings (3 tests)
- Performance (Pro) (2 tests)
- Import/Export (3 tests)
- Pro Support Features (2 tests)
- Upgrade/Downgrade (3 tests)

#### [Pro Schema Types](pro/pro-schema-types.md) (45 Test Cases)
Tests for Pro schema types:
- Recipe Schema (8 tests)
- Event Schema (9 tests)
- HowTo Schema (8 tests)
- PodcastEpisode Schema (5 tests)
- Custom Schema (8 tests)
- Schema Type Ordering (2 tests)
- Pro Schema Type Features (3 tests)
- Integration Between Pro Types (2 tests)

## Total Test Coverage

- **Free Plugin:** 93 test cases
- **Pro Plugin:** 92 test cases
- **Total:** 185 test cases

## How to Use These Test Cases

### 1. Setup Test Environment
Follow the setup instructions in [Testing Guide](../.docs/testing/testing-guide.md)

### 2. Select Test Cases
Choose test cases based on:
- What you're testing (new feature, bug fix, release candidate)
- Available time (full suite vs. smoke tests)
- Priority (Critical/High tests first)

### 3. Execute Tests
1. Open the test case file
2. Follow preconditions
3. Execute test steps exactly as written
4. Compare actual results with expected results
5. Mark status (Pass/Fail)
6. Document any issues

### 4. Report Results
- For passing tests: Mark with ☑ Pass
- For failing tests: Mark with ☑ Fail and create bug report
- Track results in test management tool or spreadsheet

## Test Execution Strategies

### Smoke Testing (Quick - 30 minutes)
Run critical tests to verify basic functionality:
- TC-F-001, TC-F-002 (Installation/Activation)
- TC-F-005 (Create Template)
- TC-F-026 (Assign Template to Post)
- TC-F-029, TC-F-030 (Schema Output)
- TC-FS-002 (Article Schema Validation)
- TC-P-002, TC-P-003 (Pro Activation/License) - *if testing Pro*
- TC-PS-001 (Recipe Template) - *if testing Pro*

### Feature Testing (Medium - 2-4 hours)
Test specific feature area:
- Example: Testing new Recipe schema
  - All Recipe tests (TC-PS-001 through TC-PS-008)
  - Related preset tests (TC-P-011, TC-P-012)
  - Schema validation tests

### Full Regression Testing (Extensive - 1-2 days)
Execute all test cases before major release:
- All Free plugin tests (93 tests)
- All Pro plugin tests (92 tests)
- Integration tests with popular plugins
- Browser compatibility tests
- Performance benchmarks

### Release Candidate Testing (Comprehensive - 2-3 days)
Full regression + additional verification:
- All test cases
- Exploratory testing
- Real-world scenarios
- User acceptance testing
- Security audit
- Performance testing

## Test Priority Guidelines

### Critical (Must Pass Before Release)
- All installation/activation tests
- Core template creation and management
- Schema output and validation
- Security tests
- Data integrity tests

### High (Should Pass Before Release)
- All schema types tests
- Major features
- Common user workflows
- Third-party integrations
- Performance tests

### Medium (Should Fix Soon)
- Edge cases
- Optional features
- Minor UI issues
- Less common workflows

### Low (Can Fix Later)
- Cosmetic issues
- Rare scenarios
- Nice-to-have features

## Test Case Maintenance

### When to Update Test Cases

1. **New Feature Added**
   - Create new test cases for the feature
   - Update related existing tests
   - Add integration tests

2. **Bug Fixed**
   - Add regression test for the bug
   - Verify related tests still valid

3. **Feature Changed**
   - Update affected test cases
   - Modify expected results
   - Update screenshots/examples

4. **WordPress Version Updated**
   - Review compatibility tests
   - Update version requirements
   - Test with new WP features

### Test Case Review Schedule
- **Monthly:** Review and update 25% of test cases
- **Quarterly:** Full test case audit
- **Before Major Release:** Comprehensive review

## Best Practices

### Writing New Test Cases
- Use the standard template format
- Write clear, specific steps
- Define precise expected results
- Include preconditions
- Assign appropriate priority
- Consider edge cases

### Executing Tests
- Follow steps exactly as written
- Don't skip preconditions
- Test in clean environment
- Document actual results
- Take screenshots of failures
- Note any deviations

### Reporting Issues
- Reference test case number
- Include reproduction steps
- Attach screenshots/videos
- Note environment details
- Suggest severity level

## Automation Opportunities

Consider automating these repetitive tests:
- Schema validation (can use Google API)
- JSON-LD syntax checking
- Dynamic field replacement
- Database operations
- Performance benchmarks

Tools for automation:
- PHPUnit for unit tests
- Codeception for functional tests
- Selenium for browser automation
- WP-CLI for WordPress operations

## Common Testing Pitfalls

❌ **Don't:**
- Test on production site
- Skip cache clearing between tests
- Assume previous tests still pass
- Test only happy path scenarios
- Ignore console warnings
- Test with outdated software

✅ **Do:**
- Use fresh test environment
- Clear cache before testing
- Test error scenarios
- Document everything
- Test with realistic data
- Keep software up to date

## Resources

### Documentation
- [Testing Guide](../.docs/testing/testing-guide.md) - Comprehensive testing procedures
- [Bug Report Template](../.docs/testing/testing-guide.md#bug-reporting) - How to report bugs
- Plugin Documentation - User-facing documentation

### Tools
- [Google Rich Results Test](https://search.google.com/test/rich-results)
- [Schema.org Validator](https://validator.schema.org/)
- [JSON-LD Playground](https://json-ld.org/playground/)
- [Query Monitor Plugin](https://wordpress.org/plugins/query-monitor/)

### Support
- GitHub Issues - Report bugs and request features
- Support Forum - Get help from community
- Documentation - User guides and API docs

## Contributing

### Adding New Tests
1. Follow the test case template
2. Assign appropriate test ID
3. Set priority level
4. Submit pull request with new test cases

### Improving Existing Tests
1. Identify unclear or outdated tests
2. Propose improvements
3. Update test cases
4. Submit pull request

### Reporting Test Issues
If you find issues with test cases themselves:
1. Note which test case is problematic
2. Describe the issue
3. Suggest correction
4. Create issue or pull request

---

## Quick Reference

### Test Execution Checklist
- [ ] Environment set up correctly
- [ ] Required plugins installed
- [ ] Debug mode enabled
- [ ] Cache cleared
- [ ] Test data prepared
- [ ] Test case document open
- [ ] Results tracking ready

### Before Release Checklist
- [ ] All Critical tests pass
- [ ] All High priority tests pass
- [ ] No known security issues
- [ ] Performance benchmarks met
- [ ] Schema validation passes
- [ ] Browser compatibility verified
- [ ] Documentation updated

---

**Last Updated:** 2024-01-15
**Total Test Cases:** 185
**Test Coverage:** Free Plugin (93) + Pro Plugin (92)

For questions or contributions, please refer to the main project documentation or open an issue on GitHub.
