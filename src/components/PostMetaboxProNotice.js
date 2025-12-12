/**
 * Post Metabox Pro Notice Component
 *
 * Displays a compact notice bar at the top of post metabox
 * promoting Pro features when Pro is not activated.
 */

import { __ } from '@wordpress/i18n';
import Icon from './Icon';

import ProNotice from './ProNotice';

const PostMetaboxProNotice = () => {
	// Check context
	const isUserProfile = window.swiftRankPostMetabox?.context === 'user-profile';

	const message = isUserProfile
		? __('Unlock Pro to assign schema templates to users based on roles.', 'swift-rank')
		: __('Unlock Pro schema types, advanced variables & WooCommerce integration', 'swift-rank');

	return (
		<ProNotice
			message={message}
			linkText={__('Upgrade to Pro', 'swift-rank')}
			compact={true}
			className="swift-rank-post-pro-notice"
		/>
	);
};

export default PostMetaboxProNotice;
