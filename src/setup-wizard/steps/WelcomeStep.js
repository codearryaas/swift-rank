import { __ } from '@wordpress/i18n';
import WizardStep from '../components/WizardStep';

const WelcomeStep = ({ onNext, onSkip, currentStep, totalSteps, isSaving }) => {
    const handleNext = () => {
        onNext({}); // Pass empty object, not the click event
    };

    return (
        <WizardStep
            title={__('Welcome to Swift Rank', 'swift-rank')}
            description={__('Enhance your website with automatically generated Schema.org structured data.', 'swift-rank')}
            onNext={handleNext}
            onSkip={onSkip}
            currentStep={currentStep}
            totalSteps={totalSteps}
            isSaving={isSaving}
            showBack={false}
            nextLabel={__('Start Setup', 'swift-rank')}
        >
            <div className="welcome-content">
                <div className="benefits-list">
                    <h3>{__('What Swift Rank Does for You', 'swift-rank')}</h3>

                    <div className="benefit-item">
                        <span className="dashicons dashicons-yes-alt"></span>
                        <div>
                            <strong>{__('Improve SEO Rankings', 'swift-rank')}</strong>
                            <p>{__('Help search engines better understand your content', 'swift-rank')}</p>
                        </div>
                    </div>

                    <div className="benefit-item">
                        <span className="dashicons dashicons-yes-alt"></span>
                        <div>
                            <strong>{__('Enable Rich Results', 'swift-rank')}</strong>
                            <p>{__('Stand out in search with enhanced listings', 'swift-rank')}</p>
                        </div>
                    </div>

                    <div className="benefit-item">
                        <span className="dashicons dashicons-yes-alt"></span>
                        <div>
                            <strong>{__('Increase Visibility', 'swift-rank')}</strong>
                            <p>{__('Get more clicks with eye-catching search results', 'swift-rank')}</p>
                        </div>
                    </div>

                    <div className="benefit-item">
                        <span className="dashicons dashicons-yes-alt"></span>
                        <div>
                            <strong>{__('No Coding Required', 'swift-rank')}</strong>
                            <p>{__('Easy setup wizard guides you through every step', 'swift-rank')}</p>
                        </div>
                    </div>
                </div>

                <div className="welcome-note">
                    <p>
                        <span className="dashicons dashicons-info"></span>
                        {__('This wizard will help you configure the essential settings. You can always change these later in the settings page.', 'swift-rank')}
                    </p>
                </div>
            </div>
        </WizardStep>
    );
};

export default WelcomeStep;
