import { __ } from '@wordpress/i18n';
import Icon from '../../components/Icon';

const ComparisonTable = () => {
    const features = [
        {
            category: __('Schema Types', 'swift-rank'),
            items: [
                { name: __('Article & Blog Posting', 'swift-rank'), free: true, pro: true },
                { name: __('WebPage Schema', 'swift-rank'), free: true, pro: true },
                { name: __('Organization Schema', 'swift-rank'), free: true, pro: true },
                { name: __('Person Schema', 'swift-rank'), free: true, pro: true },
                { name: __('Local Business Schema', 'swift-rank'), free: true, pro: true },
                { name: __('Product Schema', 'swift-rank'), free: true, pro: true },
                { name: __('Author Schema', 'swift-rank'), free: false, pro: true },
                { name: __('Recipe Schema', 'swift-rank'), free: false, pro: true },
                { name: __('Event Schema', 'swift-rank'), free: false, pro: true },
                { name: __('Video Schema', 'swift-rank'), free: false, pro: true },
                // { name: __('Course Schema', 'swift-rank'), free: false, pro: true },
                { name: __('Job Posting Schema', 'swift-rank'), free: false, pro: true },
                { name: __('Review & AggregateRating', 'swift-rank'), free: true, pro: true },
                { name: __('FAQ Page Schema', 'swift-rank'), free: true, pro: true },
                { name: __('HowTo Schema', 'swift-rank'), free: false, pro: true },
                // { name: __('Podcast Episode', 'swift-rank'), free: false, pro: true },
                // { name: __('Software Application', 'swift-rank'), free: false, pro: true },
            ]
        },
        // {
        //     category: __('WooCommerce SEO', 'swift-rank'),
        //     items: [
        //         { name: __('Basic Product Schema', 'swift-rank'), free: true, pro: true },
        //         { name: __('Automated Product Schema', 'swift-rank'), free: false, pro: true },
        //         { name: __('Price & Stock Sync', 'swift-rank'), free: false, pro: true },
        //         { name: __('Product Reviews & Ratings', 'swift-rank'), free: false, pro: true },
        //         { name: __('Brand & SKU Data', 'swift-rank'), free: false, pro: true },
        //         { name: __('Merchant Return Policy', 'swift-rank'), free: false, pro: true },
        //         { name: __('Shipping Details', 'swift-rank'), free: false, pro: true },
        //     ]
        // },
        {
            category: __('Advanced Features', 'swift-rank'),
            items: [
                { name: __('Custom Schema Builder', 'swift-rank'), free: false, pro: true },
                { name: __('BreadcrumbList Schema', 'swift-rank'), free: true, pro: true },
                { name: __('Sitelinks Searchbox', 'swift-rank'), free: true, pro: true },
                { name: __('Advanced Display Conditions', 'swift-rank'), free: false, pro: true },
                { name: __('Advanced Variables', 'swift-rank'), free: false, pro: true },
                { name: __('Custom Code Placement', 'swift-rank'), free: false, pro: true },
                { name: __('Prebuild Schema Templates', 'swift-rank'), free: false, pro: true },
                { name: __('Paywall Content Support', 'swift-rank'), free: false, pro: true },
                { name: __('Local Business Opening Hours', 'swift-rank'), free: false, pro: true },
                { name: __('Schema Linking', 'swift-rank'), free: false, pro: true },
                { name: __('Default Image Fallback', 'swift-rank'), free: false, pro: true },
                { name: __('Custom Social Profiles', 'swift-rank'), free: false, pro: true },
                { name: __('Priority Support', 'swift-rank'), free: false, pro: true },
            ]
        }
    ];

    return (
        <div className="comparison-table-wrapper">
            <table className="comparison-table">
                <thead>
                    <tr>
                        <th className="feature-col">{__('Features', 'swift-rank')}</th>
                        <th className="plan-col free-plan">
                            <span className="plan-name">{__('Free', 'swift-rank')}</span>
                        </th>
                        <th className="plan-col pro-plan">
                            <div className="pro-badge">
                                <Icon name="zap" size={14} />
                                <span>{__('PRO', 'swift-rank')}</span>
                            </div>
                            <span className="plan-name">{__('Swift Rank Pro', 'swift-rank')}</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {features.map((section, sIndex) => (
                        <>
                            <tr key={`header-${sIndex}`} className="section-header-row">
                                <td colSpan="3">{section.category}</td>
                            </tr>
                            {section.items.map((item, iIndex) => (
                                <tr key={`item-${sIndex}-${iIndex}`}>
                                    <td className="feature-name">
                                        {item.name}
                                        {item.new && <span className="new-badge">{__('NEW', 'swift-rank')}</span>}
                                    </td>
                                    <td className="plan-value free-value">
                                        {item.free ? (
                                            <Icon name="check" size={20} className="check-icon" />
                                        ) : (
                                            <Icon name="minus" size={20} className="dash-icon" />
                                        )}
                                    </td>
                                    <td className="plan-value pro-value">
                                        {item.pro ? (
                                            <div className="check-wrapper">
                                                <Icon name="check" size={20} className="check-icon" />
                                            </div>
                                        ) : (
                                            <Icon name="x" size={20} className="x-icon" />
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

const UpgradeTab = () => {
    const upgradeUrl = typeof window.swiftRankSettings !== 'undefined' ? window.swiftRankSettings.upgradeUrl : 'https://toolpress.net/swift-rank/pricing';

    return (
        <div className="swift-rank-upgrade-tab">
            <div className="upgrade-hero">
                <div className="upgrade-hero-content">
                    <div className="upgrade-badge">
                        <Icon name="crown" size={16} />
                        <span>{__('Unlock Full Potential', 'swift-rank')}</span>
                    </div>

                    <h1>
                        {__('Supercharge Your SEO with', 'swift-rank')} <span className="highlight-text">{__('Swift Rank Pro', 'swift-rank')}</span>
                    </h1>

                    <p className="hero-description">
                        {__('Get access to advanced schema types, paywall content support, powerful custom schema builder, and priority support to dominate search results.', 'swift-rank')}
                    </p>

                    <div className="hero-cta-group">
                        <a
                            href={upgradeUrl}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="button button-upgrade-hero"
                        >
                            <Icon name="rocket" size={20} />
                            <span>{__('Upgrade to Pro Now', 'swift-rank')}</span>
                        </a>
                        <p className="guarantee-text">
                            <Icon name="shield-check" size={14} />
                            {__('14-day money-back guarantee', 'swift-rank')}
                        </p>
                    </div>
                </div>
            </div>

            <div className="upgrade-comparison-section">
                <ComparisonTable />

                <div className="bottom-cta-container">
                    <div className="cta-content">
                        <h2>{__('Ready to rank higher?', 'swift-rank')}</h2>
                        <p>{__('Join thousands of smart website owners utilizing Swift Rank Pro\'s advanced schema features to dominate Google search results.', 'swift-rank')}</p>
                        <div className="cta-actions">
                            <a
                                href={upgradeUrl}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="button button-upgrade-large"
                            >
                                <Icon name="rocket" size={20} />
                                <span>{__('Get Swift Rank Pro Now', 'swift-rank')}</span>
                            </a>
                            <div className="guarantee-badge">
                                <Icon name="shield-check" size={16} />
                                <span>{__('14-Day Money-Back Guarantee', 'swift-rank')}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default UpgradeTab;
