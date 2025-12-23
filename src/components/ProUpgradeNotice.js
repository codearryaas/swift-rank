/**
 * Pro Upgrade Notice Component
 *
 * Displays an upgrade notice when a Pro feature is selected
 * but the Pro plugin is not activated.
 */

import { __ } from '@wordpress/i18n';
import Icon from './Icon';

const ProUpgradeNotice = ({ schemaType, featureType = null }) => {
	// Get the upgrade URL from config or use default
	const upgradeUrl = window.swiftRankConfig?.upgradeUrl || 'https://toolpress.net/swift-rank/pricing';

	// Features to highlight based on schema type or feature type
	const getFeatures = () => {
		// Handle specific feature types (like opening_hours)
		if (featureType === 'opening_hours') {
			return [
				__('Opening Hours Display', 'swift-rank'),
				__('Holiday Hours Support', 'swift-rank'),
				__('Multiple Time Slots', 'swift-rank'),
			];
		}

		switch (schemaType) {
			case 'Product':
				return [
					__('WooCommerce Integration', 'swift-rank'),
					__('Review & Rating Support', 'swift-rank'),
					__('Price & Availability', 'swift-rank'),
				];
			case 'Recipe':
				return [
					__('Nutrition Information', 'swift-rank'),
					__('Step-by-Step Instructions', 'swift-rank'),
					__('Video Support', 'swift-rank'),
				];
			case 'PodcastEpisode':
				return [
					__('Audio File Integration', 'swift-rank'),
					__('Series & Season Support', 'swift-rank'),
					__('Transcript Support', 'swift-rank'),
				];
			case 'LocalBusiness':
				return [
					__('Opening Hours', 'swift-rank'),
					__('Price Range', 'swift-rank'),
					__('Service Area', 'swift-rank'),
				];
			default:
				return [
					__('Advanced Schema Types', 'swift-rank'),
					__('Paywall Content Support', 'swift-rank'),
					__('Advanced Variables (ACF)', 'swift-rank'),
					__('Priority Support', 'swift-rank'),
				];
		}
	};

	const features = getFeatures();

	return (
		<div className="swift-rank-pro-upgrade-notice">
			<div className="upgrade-icon">
				<Icon name="star" size={24} />
			</div>
			<h3>{__('Upgrade to Swift Rank Pro', 'swift-rank')}</h3>
			<p>
				{featureType === 'opening_hours' && __('Display your business hours in Google search results with the Opening Hours feature.', 'swift-rank')}
				{!featureType && schemaType === 'Product' && __('Unlock powerful Product schema with WooCommerce integration and rich snippets.', 'swift-rank')}
				{!featureType && schemaType === 'Recipe' && __('Create beautiful recipe cards in search results with cooking times, ingredients, and more.', 'swift-rank')}
				{!featureType && schemaType === 'PodcastEpisode' && __('Optimize your podcast episodes for search with audio players in search results.', 'swift-rank')}
				{!featureType && schemaType === 'LocalBusiness' && __('Enhance your local business listing with opening hours, price range, and more.', 'swift-rank')}
				{!featureType && !['Product', 'Recipe', 'PodcastEpisode', 'LocalBusiness'].includes(schemaType) && __('Get access to advanced schema types and premium features.', 'swift-rank')}
			</p>
			<div className="upgrade-features">
				{features.map((feature, index) => (
					<span key={index} className="feature-item">
						<Icon name="check" size={16} />
						{feature}
					</span>
				))}
			</div>
			<a
				href={upgradeUrl}
				target="_blank"
				rel="noopener noreferrer"
				className="upgrade-button"
			>
				<Icon name="external-link" size={16} />
				{__('Upgrade to Pro', 'swift-rank')}
			</a>
		</div>
	);
};

export default ProUpgradeNotice;
