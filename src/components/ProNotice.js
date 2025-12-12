import React from 'react';
import Icon from './Icon';

/**
 * ProNotice Component
 *
 * A reusable notice bar for Pro features with customizable content.
 *
 * @param {Object} props
 * @param {string} props.message - The main message to display
 * @param {string} props.linkText - Text for the upgrade link (default: "Upgrade to unlock")
 * @param {string} props.linkUrl - URL for the upgrade link (default: from settings)
 * @param {boolean} props.compact - Use compact styling (default: true)
 * @param {boolean} props.allowHtml - Allow HTML rendering in message (default: false)
 */
const ProNotice = ({
    message = 'This is a Pro feature.',
    linkText = 'Upgrade to unlock',
    linkUrl,
    compact = true,
    className = '',
    style = {},
    icon = 'lock',
    allowHtml = false
}) => {
    // Get upgrade URL from multiple possible sources
    const upgradeUrl = linkUrl
        || (typeof window.swiftRankSettings !== 'undefined' && window.swiftRankSettings.upgradeUrl)
        || (typeof window.swiftRankConfig !== 'undefined' && window.swiftRankConfig.upgradeUrl)
        || (typeof window.swiftRankPostMetabox !== 'undefined' && window.swiftRankPostMetabox.upgradeUrl)
        || (typeof window.swiftRankMetabox !== 'undefined' && window.swiftRankMetabox.upgradeUrl)
        || 'https://toolpress.net/swift-rank/pricing';


    return (
        <div className={`pro-field-notice ${compact ? 'compact' : ''} ${className}`} style={style}>
            <Icon name={icon} size={14} />
            <span className="pro-field-notice-text">
                {allowHtml ? (
                    <span dangerouslySetInnerHTML={{ __html: message }} />
                ) : (
                    message
                )}{' '}
                <a
                    href={upgradeUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="pro-field-notice-link"
                >
                    {linkText}
                    <Icon name="external-link" size={12} />
                </a>
            </span>
        </div>
    );
};

export default ProNotice;
