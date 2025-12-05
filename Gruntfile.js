/**
 * Grunt Configuration for Swift Rank
 *
 * Build process for WordPress.org deployment
 */

module.exports = function(grunt) {
	'use strict';

	// Load package.json
	const pkg = grunt.file.readJSON('package.json');

	// Project configuration
	grunt.initConfig({
		pkg: pkg,

		// Clean build directory
		clean: {
			build: ['build'],
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
							'!build/**',
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
                            '!CHANGELOG.md',
                            '!README.md',
                            '!yarn.lock'
						],
						dest: 'build/swift-rank/'
					}
				]
			}
		},

		// Create ZIP file for WordPress.org
		compress: {
			build: {
				options: {
					mode: 'zip',
					archive: function() {
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
						cwd: 'build/',
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
		'clean:build',
		'clean:release',
		'copy:build',
		'compress:build'
	]);

	// Default task
	grunt.registerTask('default', ['build']);

	// Display version info
	grunt.registerTask('version', 'Display plugin version', function() {
		const pluginFile = grunt.file.read('swift-rank.php');
		const versionMatch = pluginFile.match(/Version:\s*([0-9.]+)/);
		const version = versionMatch ? versionMatch[1] : 'unknown';
		grunt.log.writeln('Plugin Version: ' + version);
	});

	// Sync version from package.json to plugin files
	grunt.registerTask('sync-version', 'Sync version from package.json to plugin files', function() {
		const pkg = grunt.file.readJSON('package.json');
		const version = pkg.version;

		grunt.log.writeln('Syncing version ' + version + ' to plugin files...');

		// Update main plugin file (swift-rank.php)
		let pluginFile = grunt.file.read('swift-rank.php');
		pluginFile = pluginFile.replace(
			/Version:\s*([0-9.]+)/,
			'Version: ' + version
		);
		pluginFile = pluginFile.replace(
			/define\(\s*'SWIFT_RANK_VERSION',\s*'([0-9.]+)'\s*\);/,
			"define( 'SWIFT_RANK_VERSION', '" + version + "' );"
		);
		grunt.file.write('swift-rank.php', pluginFile);
		grunt.log.ok('Updated swift-rank.php');

		// Update readme.txt
		let readmeFile = grunt.file.read('readme.txt');
		readmeFile = readmeFile.replace(
			/Stable tag:\s*([0-9.]+)/,
			'Stable tag: ' + version
		);
		grunt.file.write('readme.txt', readmeFile);
		grunt.log.ok('Updated readme.txt');

		grunt.log.writeln('Version sync complete!');
	});

	// Bundle task: sync version then build
	grunt.registerTask('bundle', [
		'sync-version',
		'build'
	]);
};
