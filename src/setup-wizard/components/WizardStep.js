import { __ } from '@wordpress/i18n';

const WizardStep = ({
    title,
    description,
    children,
    onNext,
    onBack,
    onSkip,
    currentStep,
    totalSteps,
    isSaving,
    showBack = true,
    showSkip = true,
    nextLabel = null,
}) => {
    return (
        <div className="wizard-step">
            <div className="step-header">
                <h2>{title}</h2>
                {description && <p className="step-description">{description}</p>}
            </div>

            <div className="step-body">
                {children}
            </div>

            <div className="step-footer">
                <div className="footer-left">
                    {showBack && currentStep > 1 && (
                        <button
                            type="button"
                            className="button button-secondary"
                            onClick={onBack}
                            disabled={isSaving}
                        >
                            {__('← Back', 'swift-rank')}
                        </button>
                    )}
                </div>

                <div className="footer-right">
                    {showSkip && currentStep < totalSteps && (
                        <button
                            type="button"
                            className="button button-link"
                            onClick={onSkip}
                            disabled={isSaving}
                        >
                            {__('Skip for Now', 'swift-rank')}
                        </button>
                    )}

                    <button
                        type="button"
                        className="button button-primary"
                        onClick={onNext}
                        disabled={isSaving}
                    >
                        {isSaving ? (
                            <>
                                <span className="spinner is-active" style={{ float: 'none', margin: '0 8px 0 0' }}></span>
                                {__('Saving...', 'swift-rank')}
                            </>
                        ) : (
                            nextLabel || (currentStep === totalSteps ? __('Finish Setup', 'swift-rank') : __('Next →', 'swift-rank'))
                        )}
                    </button>
                </div>
            </div>
        </div>
    );
};

export default WizardStep;
