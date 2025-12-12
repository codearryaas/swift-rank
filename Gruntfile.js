/**
 * Grunt Configuration for Schema Engine
 * 
 * Build process for WordPress.org deployment
 */

module.exports = function (grunt) {
	'use strict';

	// Load package.json
	const pkg = grunt.file.readJSON('package.json');

	// Project configuration
	grunt.initConfig({
		pkg: pkg,

		// Clean build directory
		clean: {
			// build: ['build'],
			release: ['release']
		},

		// Copy files to build directory
		copy: {
			build: {
				files: [
					{
						expand: true,
						src: [
							'**',
							'!node_modules/**',
							// '!build/**',
							'!release/**',
							'!.git/**',
							'!.gitignore',
							'!.DS_Store',
							'!Gruntfile.js',
							'!package.json',
							'!package-lock.json',
							'!composer.json',
							'!composer.lock',
							'!phpcs.xml',
							'!phpunit.xml',
							'!tests/**',
							'!bin/**',
							'!.github/**',
							'!*.log',
							'!*.md',
							'!yarn.lock',
							'!webpack.config.js',
							'!debug-*.php',
							'!test-*.php',
							'!*-debug.php',
							'!*-test.php',
							'!src/**' // Exclude src if not needed in release
						],
						dest: 'release/build/swift-rank/'
					}
				]
			}
		},

		// Create ZIP file for WordPress.org
		compress: {
			build: {
				options: {
					mode: 'zip',
					archive: function () {
						// Read version from main plugin file
						const pluginFile = grunt.file.read('swift-rank.php');
						const versionMatch = pluginFile.match(/Version:\s*([0-9.]+)/);
						const version = versionMatch ? versionMatch[1] : pkg.version;
						return 'release/swift-rank.' + version + '.zip';
					}
				},
				files: [
					{
						expand: true,
						cwd: 'release/build/',
						src: ['**/*'],
						dest: '/'
					}
				]
			}
		}
	});

	// Load Grunt plugins
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-compress');

	// Register tasks
	grunt.registerTask('build', [
		'sync-version',
		// 'clean:build',
		'clean:release',
		'copy:build',
		'compress:build'
	]);

	// Default task
	grunt.registerTask('default', ['build']);

	// Display version info
	grunt.registerTask('version', 'Display plugin version', function () {
		const pluginFile = grunt.file.read('swift-rank.php');
		const versionMatch = pluginFile.match(/Version:\s*([0-9.]+)/);
		const version = versionMatch ? versionMatch[1] : 'unknown';
		grunt.log.writeln('Plugin Version: ' + version);
	});

	// Sync version from package.json to all other files
	grunt.registerTask('sync-version', 'Sync version from package.json to all files', function () {
		const version = pkg.version;

		grunt.log.writeln('');
		grunt.log.writeln('Syncing version ' + version.cyan + ' to all files...');
		grunt.log.writeln('');

		// 1. Update swift-rank.php
		try {
			let pluginContent = grunt.file.read('swift-rank.php');

			// Update plugin header Version
			pluginContent = pluginContent.replace(/(\* Version:\s+)([0-9.]+)/, '$1' + version);

			// Update SWIFT_RANK_VERSION constant
			pluginContent = pluginContent.replace(/(define\('SWIFT_RANK_VERSION',\s+')([0-9.]+)('\);)/, '$1' + version + '$3');

			grunt.file.write('swift-rank.php', pluginContent);
			grunt.log.ok('✓ swift-rank.php → ' + version);
		} catch (error) {
			grunt.log.error('✗ Failed to update swift-rank.php');
			return false;
		}

		// 2. Update readme.txt
		try {
			let readmeContent = grunt.file.read('readme.txt');

			// Update Stable tag
			readmeContent = readmeContent.replace(/(Stable tag:\s+)([0-9.]+)/, '$1' + version);

			grunt.file.write('readme.txt', readmeContent);
			grunt.log.ok('✓ readme.txt → ' + version);
		} catch (error) {
			grunt.log.error('✗ Failed to update readme.txt');
			return false;
		}

		grunt.log.writeln('');
		grunt.log.ok('✓ All files synced to version ' + version);
		grunt.log.writeln('');
	});

	// Check version consistency across files
	grunt.registerTask('check-version', 'Verify version consistency', function () {
		const pluginFile = grunt.file.read('swift-rank.php');
		const readmeFile = grunt.file.read('readme.txt');

		// Extract versions
		const headerVersion = pluginFile.match(/\* Version:\s+([0-9.]+)/);
		const constantVersion = pluginFile.match(/define\('SWIFT_RANK_VERSION',\s+'([0-9.]+)'\);/);
		const readmeVersion = readmeFile.match(/Stable tag:\s+([0-9.]+)/);
		const packageVersion = pkg.version;

		const versions = {
			'package.json': packageVersion,
			'Plugin Header': headerVersion ? headerVersion[1] : 'NOT FOUND',
			'PHP Constant': constantVersion ? constantVersion[1] : 'NOT FOUND',
			'readme.txt': readmeVersion ? readmeVersion[1] : 'NOT FOUND'
		};

		// Check if all versions match
		const uniqueVersions = [...new Set(Object.values(versions))];

		grunt.log.writeln('');
		grunt.log.writeln('Version Check:');
		grunt.log.writeln('==============');

		for (const [file, version] of Object.entries(versions)) {
			grunt.log.writeln(`${file}: ${version}`);
		}

		if (uniqueVersions.length > 1) {
			grunt.log.writeln('');
			grunt.log.error('⚠ WARNING: Version mismatch detected!');
			grunt.log.writeln('Run: grunt sync-version to sync all versions');
			grunt.log.writeln('');
			return false;
		} else {
			grunt.log.writeln('');
			grunt.log.ok('✓ All versions are in sync: ' + uniqueVersions[0]);
			grunt.log.writeln('');
		}
	});
};
