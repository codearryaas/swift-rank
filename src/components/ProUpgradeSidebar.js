/**
 * Pro Upgrade Sidebar Component
 *
 * Displays a sidebar panel promoting Pro features.
 * Used in template editor sidebar when Pro is not activated.
 */

import { __ } from '@wordpress/i18n';
import Icon from './Icon';

const ProUpgradeSidebar = () => {
	// Get the upgrade URL from config or use default
	const upgradeUrl = window.swiftRankConfig?.upgradeUrl || 'https://toolpress.net/swift-rank/pricing';

	const proFeatures = [
		{
			icon: 'admin-post',
			title: __('Pro Schema Types', 'swift-rank'),
			items: [
				__('Product Schema', 'swift-rank'),
				__('Recipe Schema', 'swift-rank'),
				__('Podcast Episode Schema', 'swift-rank'),
			]
		},
		{
			icon: 'forms',
			title: __('Pro Fields', 'swift-rank'),
			items: [
				__('Opening Hours', 'swift-rank'),
				__('Price Range', 'swift-rank'),
				__('Nutrition Information', 'swift-rank'),
			]
		},
		{
			icon: 'editor-code',
			title: __('Pro Variables', 'swift-rank'),
			items: [
				__('Categories & Tags', 'swift-rank'),
				__('Custom Fields (meta)', 'swift-rank'),
				__('WooCommerce Fields', 'swift-rank'),
				__('SEO Plugin Fields', 'swift-rank'),
			]
		},
		{
			icon: 'admin-plugins',
			title: __('Integrations', 'swift-rank'),
			items: [
				__('WooCommerce', 'swift-rank'),
				__('Yoast SEO', 'swift-rank'),
				__('RankMath', 'swift-rank'),
				__('ACF (Advanced Custom Fields)', 'swift-rank'),
			]
		}
	];

	return (
		<div className="swift-rank-pro-sidebar">
			<div className="pro-sidebar-header">
				<Icon name="star" size={16} className="pro-star" />
				<h3>{__('Upgrade to Pro', 'swift-rank')}</h3>
			</div>

			<p className="pro-sidebar-description">
				{__('Unlock powerful schema features to boost your SEO and get more rich results in search.', 'swift-rank')}
			</p>

			<div className="pro-features-list">
				{proFeatures.map((feature, index) => (
					<div key={index} className="pro-feature-group">
						<div className="pro-feature-header">
							<strong>{feature.title}</strong>
						</div>
						<ul className="pro-feature-items">
							{feature.items.map((item, itemIndex) => (
								<li key={itemIndex}>
									<Icon name="check" size={14} />
									{item}
								</li>
							))}
						</ul>
					</div>
				))}
			</div>

			<a
				href={upgradeUrl}
				target="_blank"
				rel="noopener noreferrer"
				className="pro-upgrade-button"
			>
				<Icon name="external-link" size={16} />
				{__('Get Swift Rank Pro', 'swift-rank')}
			</a>
		</div>
	);
};

export default ProUpgradeSidebar;
