import { render } from '@wordpress/element';
import SettingsApp from './SettingsApp';

import './style.scss';

const root = document.getElementById('swift-rank-settings-root');

if (root) {
    render(<SettingsApp />, root);
}
