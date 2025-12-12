import { __ } from '@wordpress/i18n';
import WizardStep from '../components/WizardStep';

const UpgradeStep = ({ onNext, onBack, currentStep, totalSteps, isSaving }) => {
    const handleNext = () => {
        onNext({});
    };

    return (
        <WizardStep
            title={__('Unlock the Full Power of Swift Rank', 'swift-rank')}
            description={__('Take your SEO to the next level with Swift Rank Pro', 'swift-rank')}
            onNext={handleNext}
            onBack={onBack}
            currentStep={currentStep}
            totalSteps={totalSteps}
            isSaving={isSaving}
            showSkip={false}
            nextLabel={__('Complete Setup', 'swift-rank')}
        >
            <div className="upgrade-step-content">
                {/* Hero Section */}
                <div className="upgrade-hero">
                    <div className="hero-icon">
                        <span className="dashicons dashicons-star-filled"></span>
                    </div>
                    <h2>{__('Ready to Maximize Your SEO Potential?', 'swift-rank')}</h2>
                    <p className="hero-description">
                        {__('Swift Rank Pro unlocks advanced features that help you stand out in search results and drive more traffic to your website.', 'swift-rank')}
                    </p>
                </div>

                {/* Features Grid */}
                <div className="upgrade-features-grid">
                    <div className="feature-card">
                        <div className="feature-icon">
                            <span className="dashicons dashicons-admin-page"></span>
                        </div>
                        <h3>{__('20+ Premium Schema Types', 'swift-rank')}</h3>
                        <p>{__('Access advanced schema types including Course, Event, Job Posting, Recipe, Video, and more.', 'swift-rank')}</p>
                    </div>

                    <div className="feature-card">
                        <div className="feature-icon">
                            <span className="dashicons dashicons-admin-settings"></span>
                        </div>
                        <h3>{__('Advanced Conditional Logic', 'swift-rank')}</h3>
                        <p>{__('Show different schema based on post type, category, tags, custom fields, and more.', 'swift-rank')}</p>
                    </div>

                    <div className="feature-card">
                        <div className="feature-icon">
                            <span className="dashicons dashicons-search"></span>
                        </div>
                        <h3>{__('Sitelinks Searchbox', 'swift-rank')}</h3>
                        <p>{__('Enable the Google sitelinks search box to let users search your site directly from Google.', 'swift-rank')}</p>
                    </div>

                    <div className="feature-card">
                        <div className="feature-icon">
                            <span className="dashicons dashicons-sos"></span>
                        </div>
                        <h3>{__('Priority Support', 'swift-rank')}</h3>
                        <p>{__('Get fast, expert help when you need it with priority email support.', 'swift-rank')}</p>
                    </div>

                    <div className="feature-card">
                        <div className="feature-icon">
                            <span className="dashicons dashicons-update"></span>
                        </div>
                        <h3>{__('Regular Updates', 'swift-rank')}</h3>
                        <p>{__('Stay ahead with new features, schema types, and Google algorithm updates.', 'swift-rank')}</p>
                    </div>

                    <div className="feature-card">
                        <div className="feature-icon">
                            <span className="dashicons dashicons-chart-line"></span>
                        </div>
                        <h3>{__('Advanced Analytics', 'swift-rank')}</h3>
                        <p>{__('Track schema performance and optimize for better search visibility.', 'swift-rank')}</p>
                    </div>
                </div>

                {/* CTA Section */}
                <div className="upgrade-cta">
                    <div className="cta-content">
                        <h3>{__('Special Offer: Get 20% Off Today!', 'swift-rank')}</h3>
                        <p>{__('Use code WIZARD20 at checkout', 'swift-rank')}</p>
                    </div>
                    <div className="cta-actions">
                        <a
                            href="https://schemaengine.io/pricing/?discount=WIZARD20"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="button button-primary button-hero upgrade-button"
                        >
                            <span className="dashicons dashicons-cart"></span>
                            {__('Upgrade to Pro Now', 'swift-rank')}
                        </a>
                        <p className="cta-note">
                            {__('30-day money-back guarantee • Instant access', 'swift-rank')}
                        </p>
                    </div>
                </div>

                {/* Skip Notice */}
                <div className="skip-notice">
                    <span className="dashicons dashicons-info"></span>
                    <p>{__('You can complete the setup now and upgrade anytime from the plugin settings.', 'swift-rank')}</p>
                </div>
            </div>
        </WizardStep>
    );
};

export default UpgradeStep;
