import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import FieldRenderer from '../../components/FieldRenderer';
import socialProfilesFields from '../../admin-settings/config/social-profiles-fields';
import WizardStep from '../components/WizardStep';

const SocialProfilesStep = ({ onNext, onBack, onSkip, currentStep, totalSteps, isSaving, initialData }) => {
    const isProActive = typeof window.swiftRankWizardSettings !== 'undefined' && window.swiftRankWizardSettings.isProActive;

    const [formData, setFormData] = useState({
        facebook: '',
        twitter: '',
        linkedin: '',
        instagram: '',
        youtube: '',
        custom_profiles: [],
    });

    useEffect(() => {
        if (initialData) {
            setFormData({
                facebook: initialData.facebook || '',
                twitter: initialData.twitter || '',
                linkedin: initialData.linkedin || '',
                instagram: initialData.instagram || '',
                youtube: initialData.youtube || '',
                custom_profiles: initialData.custom_profiles || [],
            });
        }
    }, [initialData]);

    const handleFieldChange = (fieldName, value) => {
        setFormData(prev => ({
            ...prev,
            [fieldName]: value
        }));
    };

    const handleNext = () => {
        onNext(formData);
    };

    return (
        <WizardStep
            title={__('Social Profiles', 'swift-rank')}
            description={__('Add your social media profile URLs to improve your Knowledge Graph visibility.', 'swift-rank')}
            onNext={handleNext}
            onBack={onBack}
            onSkip={() => onSkip(formData)}
            currentStep={currentStep}
            totalSteps={totalSteps}
            isSaving={isSaving}
        >
            <div className="schema-fields-container">
                {socialProfilesFields.map((fieldConfig) => {


                    return (
                        <FieldRenderer
                            key={fieldConfig.name}
                            fieldConfig={fieldConfig}
                            value={formData[fieldConfig.name]}
                            onChange={(value) => handleFieldChange(fieldConfig.name, value)}
                            fields={formData}
                            isOverridden={false}
                            onReset={null}
                            isPostMetabox={false}
                            allFieldConfigs={socialProfilesFields}
                        />
                    );
                })}
            </div>
        </WizardStep>
    );
};

export default SocialProfilesStep;
