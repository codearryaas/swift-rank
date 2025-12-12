/**
 * Tooltip Component
 *
 * A reusable tooltip component that matches the WordPress admin settings page style.
 * Uses CSS-only tooltip with no JavaScript dependencies.
 */
import { __ } from '@wordpress/i18n';
import Icon from './Icon';

/**
 * Tooltip component
 *
 * @param {Object}  props           Component props.
 * @param {string}  props.text      The tooltip text to display.
 * @param {string}  props.className Optional additional class name.
 * @param {Object}  props.children  Optional children to wrap (defaults to help icon).
 * @return {JSX.Element} Tooltip component.
 */
const Tooltip = ( { text, className = '', children } ) => {
	if ( ! text ) {
		return null;
	}

	return (
		<span className={ `swift-rank-tooltip ${ className }` }>
			{ children || <Icon name="help-circle" size={14} /> }
			<span className="swift-rank-tooltip-text">{ text }</span>
		</span>
	);
};

export default Tooltip;
