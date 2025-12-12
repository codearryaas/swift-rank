import { __ } from '@wordpress/i18n';
import Icon from '../../components/Icon';

const FeatureCard = ({ icon, title, items, highlight }) => {
    return (
        <div className={`upgrade-feature-card ${highlight ? 'highlight' : ''}`}>
            <div className="feature-icon-wrapper">
                <Icon name={icon} size={24} />
            </div>
            <h3 className="upgrade-feature-title">
                {title}
            </h3>
            <ul className="upgrade-feature-list">
                {items.map((item, index) => (
                    <li key={index}>
                        <Icon name="check-circle-2" size={16} />
                        <span>{item}</span>
                    </li>
                ))}
            </ul>
        </div>
    );
};

const UpgradeTab = () => {
    const upgradeUrl = typeof window.swiftRankSettings !== 'undefined' ? window.swiftRankSettings.upgradeUrl : 'https://toolpress.net/swift-rank/pricing';

    return (
        <div className="swift-rank-upgrade-tab">
            <div className="upgrade-hero">
                <div className="upgrade-hero-badge">
                    <Icon name="zap" size={16} />
                    <span>{__('PRO VERSION', 'swift-rank')}</span>
                </div>

                <div className="upgrade-hero-icon">
                    <Icon name="sparkles" size={48} />
                </div>

                <h1 className="upgrade-hero-title">
                    {__('Unlock the Full Power of', 'swift-rank')}
                    <br />
                    {__('Swift Rank Pro', 'swift-rank')}
                </h1>

                <p className="upgrade-hero-description">
                    {__('Take your SEO to the next level with premium schema types, advanced targeting, powerful integrations, and professional support. Boost your search visibility and drive more organic traffic.', 'swift-rank')}
                </p>

                <div className="upgrade-stats">
                    <div className="upgrade-stat">
                        <Icon name="layers" size={24} />
                        <strong>15+</strong>
                        <span>{__('Schema Types', 'swift-rank')}</span>
                    </div>
                    <div className="upgrade-stat">
                        <Icon name="sparkles" size={24} />
                        <strong>50+</strong>
                        <span>{__('Pro Features', 'swift-rank')}</span>
                    </div>
                    <div className="upgrade-stat">
                        <Icon name="users" size={24} />
                        <strong>1000+</strong>
                        <span>{__('Happy Users', 'swift-rank')}</span>
                    </div>
                </div>

                <div className="upgrade-features-grid">
                    <FeatureCard
                        icon="utensils-crossed"
                        title={__('Premium Schema Types', 'swift-rank')}
                        highlight={true}
                        items={[
                            __('Recipe Schema with Ingredients', 'swift-rank'),
                            __('Event Schema with Locations', 'swift-rank'),
                            __('HowTo Schema with Steps', 'swift-rank'),
                            __('Podcast Episode Schema', 'swift-rank'),
                            __('Custom JSON-LD Builder', 'swift-rank'),
                            __('Enhanced Video Schema', 'swift-rank')
                        ]}
                    />

                    <FeatureCard
                        icon="link"
                        title={__('Schema Relationships', 'swift-rank')}
                        items={[
                            __('Link Schemas Together', 'swift-rank'),
                            __('Author-Article Connections', 'swift-rank'),
                            __('Organization References', 'swift-rank'),
                            __('Video-Article Relationships', 'swift-rank'),
                            __('Dynamic Reference Resolution', 'swift-rank')
                        ]}
                    />

                    <FeatureCard
                        icon="filter"
                        title={__('Advanced Targeting', 'swift-rank')}
                        items={[
                            __('Multiple Condition Groups', 'swift-rank'),
                            __('AND/OR Logic Operators', 'swift-rank'),
                            __('Category & Tag Conditions', 'swift-rank'),
                            __('Custom Field Matching', 'swift-rank'),
                            __('URL Pattern Matching', 'swift-rank')
                        ]}
                    />

                    <FeatureCard
                        icon="shopping-bag"
                        title={__('WooCommerce Pro', 'swift-rank')}
                        highlight={true}
                        items={[
                            __('Automatic Product Schema', 'swift-rank'),
                            __('Price & Availability Sync', 'swift-rank'),
                            __('Product Reviews & Ratings', 'swift-rank'),
                            __('Variable Product Support', 'swift-rank'),
                            __('SKU & Brand Data', 'swift-rank')
                        ]}
                    />

                    <FeatureCard
                        icon="braces"
                        title={__('Premium Variables', 'swift-rank')}
                        items={[
                            __('ACF Custom Fields', 'swift-rank'),
                            __('WooCommerce Data', 'swift-rank'),
                            __('Category & Tag Variables', 'swift-rank'),
                            __('Advanced Meta Fields', 'swift-rank'),
                            __('Dynamic Calculations', 'swift-rank')
                        ]}
                    />

                    <FeatureCard
                        icon="clock"
                        title={__('Business Features', 'swift-rank')}
                        items={[
                            __('Visual Opening Hours Editor', 'swift-rank'),
                            __('Timezone Support', 'swift-rank'),
                            __('Holiday/Special Hours', 'swift-rank'),
                            __('Multiple Locations', 'swift-rank')
                        ]}
                    />

                    <FeatureCard
                        icon="code-2"
                        title={__('Developer Tools', 'swift-rank')}
                        items={[
                            __('Custom Code Placement', 'swift-rank'),
                            __('Advanced Hooks & Filters', 'swift-rank'),
                            __('Schema Presets API', 'swift-rank'),
                            __('JSON-LD Validation', 'swift-rank')
                        ]}
                    />

                    <FeatureCard
                        icon="image"
                        title={__('Media Management', 'swift-rank')}
                        items={[
                            __('Default Image Fallbacks', 'swift-rank'),
                            __('Smart Image Selection', 'swift-rank'),
                            __('Video Metadata', 'swift-rank'),
                            __('Thumbnail Generation', 'swift-rank')
                        ]}
                    />

                    <FeatureCard
                        icon="headphones"
                        title={__('Priority Support', 'swift-rank')}
                        highlight={true}
                        items={[
                            __('Priority Email Support', 'swift-rank'),
                            __('Dedicated Support Portal', 'swift-rank'),
                            __('Feature Request Priority', 'swift-rank'),
                            __('Setup & Migration Help', 'swift-rank'),
                            __('Regular Plugin Updates', 'swift-rank')
                        ]}
                    />
                </div>

                <div className="upgrade-cta">
                    <a
                        href={upgradeUrl}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="upgrade-button"
                    >
                        <Icon name="rocket" size={20} />
                        <span>{__('Upgrade to Pro Now', 'swift-rank')}</span>
                        <Icon name="arrow-right" size={20} />
                    </a>

                    <div className="upgrade-benefits">
                        <div className="upgrade-benefit">
                            <Icon name="shield-check" size={18} />
                            <span>{__('14-Day Money-Back Guarantee', 'swift-rank')}</span>
                        </div>
                        <div className="upgrade-benefit">
                            <Icon name="refresh-cw" size={18} />
                            <span>{__('Regular Updates', 'swift-rank')}</span>
                        </div>
                        <div className="upgrade-benefit">
                            <Icon name="crown" size={18} />
                            <span>{__('Premium Support', 'swift-rank')}</span>
                        </div>
                    </div>
                </div>

                <div className="upgrade-testimonial">
                    <Icon name="quote" size={32} />
                    <p>{__('Swift Rank Pro transformed our search visibility. We\'ve seen a 300% increase in rich snippets and organic traffic. The support team is amazing!', 'swift-rank')}</p>
                    <div className="testimonial-author">
                        <strong>{__('Sarah J.', 'swift-rank')}</strong>
                        <span>{__('SEO Manager', 'swift-rank')}</span>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default UpgradeTab;
