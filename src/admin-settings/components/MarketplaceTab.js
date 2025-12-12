import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

const MarketplaceTab = () => {
    const partners = [
        {
            name: 'Gutenify',
            description: __('Beautiful Gutenberg blocks and templates to build stunning websites with ease.', 'swift-rank'),
            logo: 'https://ps.w.org/gutenify/assets/icon-256x256.png',
            url: 'https://wordpress.org/plugins/gutenify/',
            buttonText: __('View Plugin', 'swift-rank')
        },
        {
            name: 'ToolPress',
            description: __('Essential WordPress development tools and utilities for developers and agencies.', 'swift-rank'),
            logo: 'https://ps.w.org/toolpress/assets/icon-256x256.png',
            url: 'https://wordpress.org/plugins/toolpress/',
            buttonText: __('View Plugin', 'swift-rank')
        },
        {
            name: 'WP Travel',
            description: __('Complete travel booking solution for tour operators and travel agencies.', 'swift-rank'),
            logo: 'https://ps.w.org/wp-travel/assets/icon-256x256.gif',
            url: 'https://wordpress.org/plugins/wp-travel/',
            buttonText: __('View Plugin', 'swift-rank')
        }
    ];

    return (
        <div className="marketplace-tab">
            <div className="marketplace-header">
                <h2>{__('Partner Plugins', 'swift-rank')}</h2>
                <p>{__('Discover our recommended plugins to enhance your WordPress experience.', 'swift-rank')}</p>
            </div>

            <div className="marketplace-grid">
                {partners.map((partner, index) => (
                    <div key={index} className="marketplace-card">
                        <div className="marketplace-card-logo">
                            <img src={partner.logo} alt={partner.name} />
                        </div>
                        <div className="marketplace-card-content">
                            <h3>{partner.name}</h3>
                            <p>{partner.description}</p>
                        </div>
                        <div className="marketplace-card-footer">
                            <Button
                                isSecondary
                                href={partner.url}
                                target="_blank"
                                className="marketplace-card-button"
                            >
                                {partner.buttonText}
                            </Button>
                        </div>
                    </div>
                ))}
            </div>

            <div className="marketplace-developer-section">
                <div className="marketplace-developer-box">
                    <h3>{__('Are You a Plugin Developer?', 'swift-rank')}</h3>
                    <p>{__('Want to showcase your plugin in our marketplace? We\'d love to feature quality WordPress plugins that complement Swift Rank.', 'swift-rank')}</p>
                    <Button
                        isSecondary
                        href="https://toolpress.net/partnership/"
                        className="marketplace-developer-button"
                        target='_blank'
                    >
                        {__('Submit Your Plugin', 'swift-rank')}
                    </Button>
                </div>
            </div>
        </div>
    );
};

export default MarketplaceTab;
