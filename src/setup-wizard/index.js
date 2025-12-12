import { render } from '@wordpress/element';
import SetupWizard from './SetupWizard';

// Render the wizard
const wizardRoot = document.getElementById('swift-rank-wizard-root');

if (wizardRoot) {
    render(<SetupWizard />, wizardRoot);
}
