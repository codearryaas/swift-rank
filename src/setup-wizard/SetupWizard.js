import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import WizardStep from './components/WizardStep';
import WelcomeStep from './steps/WelcomeStep';
import SiteInfoStep from './steps/SiteInfoStep';
import ContentTypesStep from './steps/ContentTypesStep';
import EnhancementsStep from './steps/EnhancementsStep';
import SocialProfilesStep from './steps/SocialProfilesStep';
import UpgradeStep from './steps/UpgradeStep';
import './style.scss';

const SetupWizard = () => {
    const [currentStep, setCurrentStep] = useState(1);
    const [wizardState, setWizardState] = useState({
        completed_steps: [],
        current_step: 1,
        is_complete: false,
    });
    const [stepData, setStepData] = useState({});
    const [isLoading, setIsLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);

    // Check if Pro is active
    const isProActive = typeof window.swiftRankWizardSettings !== 'undefined' && window.swiftRankWizardSettings.isProActive;
    const totalSteps = isProActive ? 5 : 6; // 6 steps for free users (includes upgrade step)

    // Load wizard state on mount
    useEffect(() => {
        loadWizardState();
    }, []);

    const loadWizardState = async () => {
        try {
            // Load wizard state
            const state = await apiFetch({
                path: '/swift-rank/v1/wizard/get-state',
            });

            if (state) {
                setWizardState(state);
                setCurrentStep(state.current_step || 1);
            }

            // Load existing data for pre-population
            const existingData = await apiFetch({
                path: '/swift-rank/v1/wizard/get-data',
            });

            if (existingData) {
                setStepData(existingData);
            }
        } catch (error) {
            console.error('Error loading wizard state:', error);
        } finally {
            setIsLoading(false);
        }
    };

    const saveStepData = async (step, data) => {
        setIsSaving(true);

        try {
            await apiFetch({
                path: '/swift-rank/v1/wizard/save-step',
                method: 'POST',
                data: {
                    step,
                    data,
                },
            });

            // Update wizard state
            const updatedState = {
                ...wizardState,
                completed_steps: [...new Set([...wizardState.completed_steps, step])],
                current_step: step + 1 <= totalSteps ? step + 1 : step,
            };

            setWizardState(updatedState);
            setStepData({ ...stepData, [step]: data });

            return true;
        } catch (error) {
            console.error('Error saving step:', error);
            return false;
        } finally {
            setIsSaving(false);
        }
    };

    const completeWizard = async () => {
        setIsSaving(true);

        try {
            await apiFetch({
                path: '/swift-rank/v1/wizard/complete',
                method: 'POST',
            });

            // Redirect to settings page
            window.location.href = 'admin.php?page=swift-rank-settings';
        } catch (error) {
            console.error('Error completing wizard:', error);
            setIsSaving(false);
        }
    };

    const handleNext = async (data) => {
        const saved = await saveStepData(currentStep, data);

        if (saved) {
            if (currentStep < totalSteps) {
                setCurrentStep(currentStep + 1);
            } else {
                // Last step - complete wizard
                await completeWizard();
            }
        }
    };

    const handleBack = () => {
        if (currentStep > 1) {
            setCurrentStep(currentStep - 1);
        }
    };

    const handleSkip = async () => {
        // Save current progress and exit
        await apiFetch({
            path: '/swift-rank/v1/wizard/save-state',
            method: 'POST',
            data: {
                ...wizardState,
                current_step: currentStep,
            },
        });

        // Redirect to settings
        window.location.href = 'admin.php?page=swift-rank-settings';
    };

    const renderStep = () => {
        const stepProps = {
            onNext: handleNext,
            onBack: handleBack,
            onSkip: handleSkip,
            currentStep,
            totalSteps,
            isSaving,
            initialData: stepData[currentStep] || {},
        };

        switch (currentStep) {
            case 1:
                return <WelcomeStep {...stepProps} />;
            case 2:
                return <SiteInfoStep {...stepProps} />;
            case 3:
                return <SocialProfilesStep {...stepProps} />;
            case 4:
                return <ContentTypesStep {...stepProps} />;
            case 5:
                return <EnhancementsStep {...stepProps} />;
            case 6:
                // Only show upgrade step for non-Pro users
                return !isProActive ? <UpgradeStep {...stepProps} /> : null;
            default:
                return null;
        }
    };

    if (isLoading) {
        return (
            <div className="schema-wizard-loading">
                <div className="spinner is-active"></div>
                <p>{__('Loading setup wizard...', 'swift-rank')}</p>
            </div>
        );
    }

    return (
        <div className="schema-setup-wizard">
            <div className="wizard-header">
                <div className="wizard-logo">
                    {typeof window.swiftRankWizardSettings !== 'undefined' && window.swiftRankWizardSettings.pluginUrl && (
                        <img
                            src={window.swiftRankWizardSettings.pluginUrl + 'assets/images/swift-rank-logo.png'}
                            alt={__('Swift Rank', 'swift-rank')}
                            style={{ maxWidth: '100px', height: 'auto', marginBottom: '20px' }}
                        />
                    )}
                    <h1>{__('Swift Rank Setup', 'swift-rank')}</h1>
                </div>

                {/* Progress Indicator */}
                <div className="wizard-progress">
                    {[
                        { num: 1, label: __('Welcome', 'swift-rank') },
                        { num: 2, label: __('Website Info', 'swift-rank') },
                        { num: 3, label: __('Social', 'swift-rank') },
                        { num: 4, label: __('Content Types', 'swift-rank') },
                        { num: 5, label: __('Enhancements', 'swift-rank') },
                        ...(!isProActive ? [{ num: 6, label: __('Upgrade', 'swift-rank') }] : []),
                    ].map((step) => (
                        <div
                            key={step.num}
                            className={`progress-step ${step.num === currentStep ? 'active' : ''
                                } ${wizardState.completed_steps.includes(step.num) ? 'completed' : ''
                                }`}
                        >
                            <div className="step-circle">
                                {wizardState.completed_steps.includes(step.num) ? (
                                    <span className="dashicons dashicons-yes"></span>
                                ) : (
                                    <span>{step.num}</span>
                                )}
                            </div>
                            <div className="step-label">{step.label}</div>
                            {step.num < totalSteps && <div className="step-connector"></div>}
                        </div>
                    ))}
                </div>
            </div>

            <div className="wizard-content">
                {renderStep()}
            </div>
        </div>
    );
};

export default SetupWizard;
