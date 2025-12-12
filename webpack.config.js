const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,
	entry: {
		'template-metabox/index': './src/template-metabox/index.js',
		'post-metabox/index': './src/post-metabox/index.js',
		'blocks/faq/index': './src/blocks/faq/index.js',
		'blocks/faq/view': './src/blocks/faq/view.js',
		'blocks/faq-item/index': './src/blocks/faq-item/index.js',
		'blocks/howto/index': './src/blocks/howto/index.js',
		'blocks/howto-step/index': './src/blocks/howto-step/index.js',
		'admin-settings/index': './src/admin-settings/index.js',
		'user-profile/index': './src/user-profile/index.js',
		'setup-wizard': './src/setup-wizard/index.js'
	}
};
