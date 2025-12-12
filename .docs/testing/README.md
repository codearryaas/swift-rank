# Schema Engine Testing Documentation

Complete testing documentation and test cases for Schema Engine (Free and Pro).

## 📁 Documentation Structure

```
.docs/testing/
├── README.md                          # This file - Overview
├── testing-guide.md                   # Complete testing guide (comprehensive)
├── quick-reference.md                 # Quick reference for testers
└── test-execution-tracker.md          # Template for tracking test runs

tests/
├── README.md                          # Test cases overview
├── free/
│   ├── core-features.md              # 51 test cases for core features
│   └── schema-types.md               # 42 test cases for schema types
└── pro/
    ├── pro-features.md               # 47 test cases for Pro features
    └── pro-schema-types.md           # 45 test cases for Pro schema types
```

## 📊 Test Coverage Summary

### Total Test Cases: **185**

| Category | Test Cases | Coverage |
|----------|------------|----------|
| **Free Plugin** | **93** | **50%** |
| - Core Features | 51 | 28% |
| - Schema Types | 42 | 22% |
| **Pro Plugin** | **92** | **50%** |
| - Pro Features | 47 | 25% |
| - Pro Schema Types | 45 | 24% |

### Test Priority Breakdown

| Priority | Count | Percentage |
|----------|-------|------------|
| Critical | ~35 | 19% |
| High | ~70 | 38% |
| Medium | ~55 | 30% |
| Low | ~25 | 13% |

## 🚀 Quick Start Guide

### For Testers

1. **Read the Testing Guide First**
   - Start with [testing-guide.md](testing-guide.md)
   - Understand testing approach and tools
   - Set up your test environment

2. **Reference Quick Guide**
   - Use [quick-reference.md](quick-reference.md) during testing
   - Quick access to commands and checklists
   - Common issues and solutions

3. **Execute Test Cases**
   - Find test cases in [../../tests/](../../tests/)
   - Follow test case format exactly
   - Use [test-execution-tracker.md](test-execution-tracker.md) to track progress

4. **Report Issues**
   - Use bug report template from guide
   - Include all required information
   - Link to test case that failed

### For Test Case Writers

1. **Review Existing Tests**
   - Read current test cases for format
   - Understand test structure
   - Note naming conventions

2. **Write New Tests**
   - Use standard template format
   - Assign appropriate priority
   - Include clear expected results
   - Consider edge cases

3. **Update Documentation**
   - Update test counts in README files
   - Add new tests to tracker template
   - Update quick reference if needed

## 📖 Documentation Files

### [Testing Guide](testing-guide.md)
**Comprehensive testing procedures and best practices**

Contents:
- Test environment setup
- Testing approach and methodology
- Test case structure and format
- Testing tools and their usage
- Test execution procedures
- Bug reporting guidelines
- Testing checklists
- Best practices
- Appendix with resources

**When to use:** Primary reference for all testing activities

---

### [Quick Reference](quick-reference.md)
**Quick access guide for testers**

Contents:
- Test case locations and time estimates
- Critical test cases list
- Essential commands (WP-CLI, SQL)
- Schema validation quick checks
- Common test scenarios
- Environment setup checklist
- Bug report template
- Browser testing matrix
- Performance benchmarks
- Testing tools quick access

**When to use:** During active testing for quick lookups

---

### [Test Execution Tracker](test-execution-tracker.md)
**Template for tracking test runs**

Contents:
- Release information fields
- Test execution summary tables
- Detailed test case checklists
- Bug tracking section
- Environment details
- Notes and observations
- Sign-off approvals

**When to use:** For each release testing cycle

---

### [Test Cases README](../../tests/README.md)
**Overview of all test cases**

Contents:
- Directory structure
- Test case file descriptions
- Total test coverage
- Test execution strategies
- Test priority guidelines
- Test case maintenance procedures
- Best practices
- Resources and support

**When to use:** To understand test case organization

## 🎯 Testing Workflows

### Workflow 1: Smoke Testing (30 min)
**Use Case:** Quick verification after code changes

1. Review [Critical test cases](quick-reference.md#critical-test-cases-must-pass)
2. Execute 10-15 critical tests
3. Document any failures
4. All must pass to proceed

**Files needed:**
- [quick-reference.md](quick-reference.md)
- Critical test cases from [tests/](../../tests/)

---

### Workflow 2: Feature Testing (3-4 hours)
**Use Case:** Testing specific feature or bug fix

1. Identify relevant test cases
2. Set up test environment
3. Execute related test cases
4. Run regression tests for affected areas
5. Document results in tracker

**Files needed:**
- [testing-guide.md](testing-guide.md) - Setup
- Relevant test case files from [tests/](../../tests/)
- [test-execution-tracker.md](test-execution-tracker.md)

---

### Workflow 3: Release Testing (1-2 days)
**Use Case:** Full testing before release

1. Review [Testing Guide](testing-guide.md)
2. Set up clean test environment
3. Execute all test cases systematically
4. Track progress in [Test Execution Tracker](test-execution-tracker.md)
5. Document all issues
6. Get sign-off approval

**Files needed:**
- All documentation files
- All test case files
- [test-execution-tracker.md](test-execution-tracker.md)

---

### Workflow 4: Test Maintenance (Ongoing)
**Use Case:** Keeping test cases up to date

1. Review test cases monthly/quarterly
2. Add tests for new features
3. Update tests for changed features
4. Remove obsolete tests
5. Update all README files

**Files needed:**
- All test case files in [tests/](../../tests/)
- All README files

## 🛠️ Essential Tools

### Validation Tools
- **Google Rich Results Test** - https://search.google.com/test/rich-results
- **Schema.org Validator** - https://validator.schema.org/
- **JSON-LD Playground** - https://json-ld.org/playground/

### WordPress Tools
- **Query Monitor Plugin** - Performance and debugging
- **Debug Bar Plugin** - WordPress debugging toolbar
- **WP-CLI** - Command line interface

### Performance Tools
- **GTmetrix** - https://gtmetrix.com/
- **WebPageTest** - https://www.webpagetest.org/
- **Google PageSpeed Insights** - https://pagespeed.web.dev/

### Security Tools
- **Sucuri SiteCheck** - https://sitecheck.sucuri.net/
- **WPScan** - https://wpscan.com/

## 📋 Pre-Release Checklist

Before any release, ensure:

### Testing Complete
- [ ] All Critical tests pass
- [ ] All High priority tests pass
- [ ] Medium/Low tests executed or documented
- [ ] Regression tests completed
- [ ] Browser compatibility verified
- [ ] Performance benchmarks met

### Documentation Updated
- [ ] Test cases reflect current features
- [ ] Known issues documented
- [ ] Testing guide is current
- [ ] Changelog updated

### Sign-off Obtained
- [ ] QA approval
- [ ] Technical lead approval
- [ ] Product owner approval (if applicable)

## 🐛 Bug Severity Guidelines

### Critical (P0) - Fix Immediately
- Site crashes or becomes inaccessible
- Data loss or corruption
- Security vulnerabilities
- Complete feature failure

### High (P1) - Fix Before Release
- Major feature broken
- Significant user experience issue
- Affects many users
- Difficult workaround

### Medium (P2) - Fix When Possible
- Minor feature issue
- Moderate UX impact
- Affects some users
- Workaround available

### Low (P3) - Future Release
- Cosmetic issues
- Edge case bugs
- Minimal impact
- Easy workaround

## 📈 Testing Metrics to Track

### Quality Metrics
- Test pass rate (target: >95%)
- Bug discovery rate
- Critical bug count (target: 0)
- Test coverage percentage

### Performance Metrics
- Page load time impact (target: <100ms)
- Database queries added (target: <5)
- Memory usage (target: <5MB)
- Schema generation time (target: <50ms)

### Process Metrics
- Time to execute test suite
- Test automation coverage
- Bug fix turnaround time
- Regression rate

## 🔄 Continuous Improvement

### Monthly Review
- Review test execution metrics
- Update test cases as needed
- Add tests for edge cases found
- Remove obsolete tests

### Quarterly Audit
- Full test case review
- Update all documentation
- Review and improve test processes
- Evaluate automation opportunities

### After Each Release
- Post-mortem on any issues found
- Add tests to prevent recurrence
- Update testing documentation
- Gather feedback from testers

## 💡 Best Practices

### Testing
✅ **DO:**
- Test with clean environment
- Follow test cases exactly
- Document everything
- Test with realistic data
- Clear cache between tests
- Test both positive and negative scenarios

❌ **DON'T:**
- Skip preconditions
- Test on production
- Assume previous tests still pass
- Test only happy paths
- Ignore warnings
- Test with outdated software

### Documentation
✅ **DO:**
- Keep test cases up to date
- Use clear, specific language
- Include screenshots for issues
- Version control test data
- Document workarounds

❌ **DON'T:**
- Write vague test steps
- Skip expected results
- Forget to update counts
- Leave outdated information
- Use ambiguous language

## 📞 Support & Contact

### Questions About Testing
- Review [Testing Guide](testing-guide.md) first
- Check [Quick Reference](quick-reference.md) for common tasks
- Consult existing test cases for examples

### Reporting Documentation Issues
- Note which document has the issue
- Describe the problem clearly
- Suggest improvement if possible
- Create issue or pull request

### Contributing
Contributions to test cases and documentation are welcome:
1. Follow existing format and style
2. Update all relevant README files
3. Test your test cases before submitting
4. Submit pull request with clear description

## 📚 Additional Resources

### Internal Documentation
- Plugin user documentation
- Developer documentation
- API documentation
- Architecture documentation

### External Resources
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Schema.org Documentation](https://schema.org/)
- [Google Search Central](https://developers.google.com/search)
- [WordPress Testing Handbook](https://make.wordpress.org/core/handbook/testing/)

## 📝 Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2024-01-15 | Initial test documentation and test cases | Team |

---

## Summary

This testing documentation provides:
- **185 comprehensive test cases** covering all features
- **Complete testing guide** with procedures and best practices
- **Quick reference** for active testing
- **Test execution tracker** for release management
- **Organized structure** for easy navigation

All documentation is maintained in the `.docs/testing/` directory, while test cases are in the `tests/` directory.

For the most current information, always refer to the individual documentation files listed above.

---

**Maintained by:** Schema Engine Team
**Last Updated:** 2024-01-15
**Total Pages:** ~8 documentation files
**Total Test Cases:** 185
