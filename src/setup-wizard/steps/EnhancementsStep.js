import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ToggleControl } from '@wordpress/components';
import WizardStep from '../components/WizardStep';

const EnhancementsStep = ({ onNext, onBack, onSkip, currentStep, totalSteps, isSaving, initialData }) => {
    const isProActive = typeof window.swiftRankWizardSettings !== 'undefined' && window.swiftRankWizardSettings.isProActive;

    const [formData, setFormData] = useState({
        knowledge_base_enabled: initialData.knowledge_base_enabled !== undefined ? initialData.knowledge_base_enabled : true,
        breadcrumb_enabled: initialData.breadcrumb_enabled !== undefined ? initialData.breadcrumb_enabled : true,
        sitelinks_searchbox: initialData.sitelinks_searchbox !== undefined ? initialData.sitelinks_searchbox : false,
    });

    const handleChange = (field, value) => {
        setFormData({ ...formData, [field]: value });
    };

    const handleNext = () => {
        onNext(formData);
    };

    return (
        <WizardStep
            title={__('Global Enhancements', 'swift-rank')}
            description={__('Enable additional schema features to improve your search presence.', 'swift-rank')}
            onNext={handleNext}
            onBack={onBack}
            onSkip={onSkip}
            currentStep={currentStep}
            totalSteps={totalSteps}
            isSaving={isSaving}
        >
            <div className="enhancements-form">
                <div className="enhancement-item">
                    <div className="enhancement-header">
                        <ToggleControl
                            label={__('Enable Knowledge Base Schema', 'swift-rank')}
                            checked={formData.knowledge_base_enabled}
                            onChange={(value) => handleChange('knowledge_base_enabled', value)}
                        />
                    </div>
                    <p className="enhancement-description">
                        {__('Output Organization/Person/LocalBusiness schema on your homepage to help search engines understand your brand identity and display rich results.', 'swift-rank')}
                    </p>
                </div>

                <div className="enhancement-item">
                    <div className="enhancement-header">
                        <ToggleControl
                            label={__('Enable Breadcrumb Schema', 'swift-rank')}
                            checked={formData.breadcrumb_enabled}
                            onChange={(value) => handleChange('breadcrumb_enabled', value)}
                        />
                    </div>
                    <p className="enhancement-description">
                        {__('Add breadcrumb navigation to help search engines understand your site structure and display breadcrumb trails in search results.', 'swift-rank')}
                    </p>
                </div>

                <div className={`enhancement-item ${!isProActive ? 'pro-feature' : ''}`}>
                    <div className="enhancement-header">
                        <ToggleControl
                            label={__('Enable Sitelinks Searchbox', 'swift-rank')}
                            checked={formData.sitelinks_searchbox}
                            onChange={(value) => handleChange('sitelinks_searchbox', value)}
                            disabled={!isProActive}
                        />
                        {!isProActive && <span className="pro-badge">{__('PRO', 'swift-rank')}</span>}
                    </div>
                    <p className="enhancement-description">
                        {__('Show a search box directly in Google search results for your website, making it easier for users to search your content.', 'swift-rank')}
                    </p>
                    {!isProActive && (
                        <p className="pro-notice">
                            <span className="dashicons dashicons-lock"></span>
                            {__('This feature requires Swift Rank Pro.', 'swift-rank')}
                        </p>
                    )}
                </div>
            </div>
        </WizardStep>
    );
};

export default EnhancementsStep;
