import React from 'react';
import Icon from './Icon';

/**
 * ProSidebarNotice Component
 * 
 * A reusable sidebar box for promoting Pro features.
 * Displays an upgrade notice with feature list and upgrade button.
 * 
 * @param {Object} props
 * @param {Array} props.features - Array of feature strings to display (optional)
 * @param {string} props.title - Title for the notice (default: "Upgrade to Pro")
 * @param {string} props.description - Description text (default: "Unlock powerful features...")
 * @param {string} props.buttonText - Text for the upgrade button (default: "Upgrade Now")
 * @param {string} props.upgradeUrl - URL for the upgrade link (default: from settings)
 */
const ProSidebarNotice = ({
    features,
    title = 'Upgrade to Pro',
    description = 'Unlock powerful features to enhance your schema markup.',
    buttonText = 'Upgrade Now',
    upgradeUrl
}) => {
    // Comprehensive default features list relevant to Swift Rank Pro
    const defaultFeatures = [
        'Advanced Schema Types (Product, Recipe, Video)',
        'WooCommerce Integration',
        'Opening Hours & Business Info',
        'Custom Field Variables',
        'Category & Tag Variables',
        'ACF Field Integration',
        'Priority Support'
    ];

    const featureList = features || defaultFeatures;

    // Get upgrade URL from multiple possible sources
    const url = upgradeUrl
        || (typeof window.swiftRankSettings !== 'undefined' && window.swiftRankSettings.upgradeUrl)
        || (typeof window.swiftRankConfig !== 'undefined' && window.swiftRankConfig.upgradeUrl)
        || (typeof window.swiftRankPostMetabox !== 'undefined' && window.swiftRankPostMetabox.upgradeUrl)
        || (typeof window.swiftRankMetabox !== 'undefined' && window.swiftRankMetabox.upgradeUrl)
        || 'https://toolpress.net/swift-rank/pricing';

    return (
        <div className="swift-rank-sidebar-box swift-rank-pro-box">
            <div className="box-icon">
                <Icon name="star" size={24} />
            </div>
            <h3>{title}</h3>
            <p>{description}</p>
            <ul className="feature-list">
                {featureList.map((feature, index) => (
                    <li key={index}>
                        <Icon name="check" size={16} />
                        {feature}
                    </li>
                ))}
            </ul>
            <a
                href={url}
                target="_blank"
                rel="noopener noreferrer"
                className="button button-primary"
            >
                <Icon name="external-link" size={16} />
                {buttonText}
            </a>
        </div>
    );
};

export default ProSidebarNotice;
