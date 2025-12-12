# Schema Engine - Build Instructions

## WordPress.org Deployment Build

This document explains how to build a release package for WordPress.org upload.

### Prerequisites

- Node.js (v14 or higher)
- npm (Node Package Manager)

### Installation

Install the required dependencies:

```bash
npm install
```

This will install:
- Grunt task runner
- Grunt plugins for cleaning, copying, and compressing files

### Build Commands

#### Build Release Package

Create a production-ready ZIP file for WordPress.org:

```bash
npm run build
```

Or directly with Grunt:

```bash
grunt build
```

This will:
1. Clean the `build/` and `release/` directories
2. Copy all plugin files to `build/schema-engine/` (excluding development files)
3. Create a ZIP file in `release/schema-engine.[version].zip`

The version number is automatically extracted from the main plugin file (`schema-engine.php`).

#### Check Version

Display the current plugin version:

```bash
grunt version
```

### What Gets Excluded

The build process automatically excludes:
- `node_modules/` - NPM dependencies
- `build/` - Build output directory
- `release/` - Release ZIP files
- `.git/` - Git repository files
- `.github/` - GitHub-specific files
- `Gruntfile.js` - Grunt configuration
- `package.json` and `package-lock.json` - NPM files
- `composer.json` and `composer.lock` - Composer files
- `phpcs.xml` and `phpunit.xml` - Development tools
- `tests/` and `bin/` - Test files
- Log files and OS-specific files

### Output

After running the build:
- **build/schema-engine/** - Contains the complete plugin ready for distribution
- **release/schema-engine.[version].zip** - ZIP file ready to upload to WordPress.org

### WordPress.org Upload

1. Run `npm run build`
2. Navigate to the `release/` directory
3. Find the ZIP file named `schema-engine.[version].zip`
4. Upload this ZIP file to WordPress.org SVN or plugin upload

### Directory Structure

```
schema-engine/
├── assets/              # CSS and JS files
├── includes/            # PHP classes
├── languages/           # Translation files
├── build/              # Build output (excluded from git)
├── release/            # ZIP files (excluded from git)
├── node_modules/       # NPM dependencies (excluded from git)
├── Gruntfile.js        # Build configuration
├── package.json        # NPM configuration
├── schema-engine.php   # Main plugin file
└── README.md           # Plugin documentation
```

### Continuous Integration

To automate builds in CI/CD pipelines:

```yaml
# Example GitHub Actions
- name: Install dependencies
  run: npm install

- name: Build plugin
  run: npm run build

- name: Upload artifact
  uses: actions/upload-artifact@v2
  with:
    name: plugin-package
    path: release/*.zip
```

### Troubleshooting

**Issue**: `grunt: command not found`
**Solution**: Install grunt-cli globally: `npm install -g grunt-cli`

**Issue**: Build fails with permission errors
**Solution**: Check directory permissions for `build/` and `release/` folders

**Issue**: ZIP file is too large
**Solution**: Verify exclusion patterns in `Gruntfile.js` are working correctly

### Development vs Production

- **Development**: Work directly in the plugin directory
- **Production**: Always use the built ZIP from `release/` directory
- The build process ensures only necessary files are included

### Version Management

1. Update version in `schema-engine.php` header:
   ```php
   * Version: 1.0.1
   ```

2. Update version in `package.json`:
   ```json
   "version": "1.0.1"
   ```

3. Run build - it will automatically use the version from `schema-engine.php`

### Support

For build-related issues, please check:
- [Grunt Documentation](https://gruntjs.com/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
