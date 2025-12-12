/**
 * Variables Popup Component
 *
 * A user-friendly popup for inserting dynamic variables into schema fields.
 * Extensible via WordPress hooks for Pro and other plugins.
 */

import { useState, useMemo } from '@wordpress/element';
import {
	Button,
	Popover,
	SearchControl,
	TabPanel
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import Icon from './Icon';

/**
 * Get base variables organized by category
 * Uses localized data from PHP
 */
const getVariableGroups = () => {
	// Get variable groups from localized PHP data
	// Check multiple possible global variable names:
	// - swiftRankMetabox (for template metabox - sr_template CPT)
	// - swiftRankPostMetabox (for post/page metabox)
	// - swiftRankSettings (for settings page)
	// - swiftRankWizardSettings (for setup wizard)
	const localizedGroups = window.swiftRankMetabox?.variableGroups ||
		window.swiftRankPostMetabox?.variableGroups ||
		window.swiftRankSettings?.variableGroups ||
		window.swiftRankWizardSettings?.variableGroups ||
		{};

	// Return localized groups (Pro plugin will have already extended them via PHP filter)
	return localizedGroups;
};

/**
 * Variables Popup Component
 */
const VariablesPopup = ({ onSelect, buttonProps = {} }) => {
	const [isOpen, setIsOpen] = useState(false);
	const [searchTerm, setSearchTerm] = useState('');

	const variableGroups = useMemo(() => getVariableGroups(), []);

	// Filter variables based on search
	const filteredGroups = useMemo(() => {
		if (!searchTerm) return variableGroups;

		const filtered = {};
		const term = searchTerm.toLowerCase();

		Object.entries(variableGroups).forEach(([key, group]) => {
			const matchingVars = group.variables.filter(v =>
				v.label.toLowerCase().includes(term) ||
				v.value.toLowerCase().includes(term) ||
				v.description.toLowerCase().includes(term)
			);

			if (matchingVars.length > 0) {
				filtered[key] = { ...group, variables: matchingVars };
			}
		});

		return filtered;
	}, [variableGroups, searchTerm]);

	const handleSelect = (variable) => {
		onSelect(variable);
		setIsOpen(false);
		setSearchTerm('');
	};

	// Create tabs for each group
	const tabs = Object.entries(variableGroups).map(([key, group]) => ({
		name: key,
		title: (
			<span className="variables-tab-title">
				<Icon name={group.icon} size={16} />
				<span className="tab-label">{group.label}</span>
			</span>
		)
	}));

	// Render variable list for a group
	const renderVariables = (variables) => (
		<div className="variables-list">
			{variables.map((variable) => (
				<button
					key={variable.value}
					type="button"
					className="variable-item"
					onClick={() => handleSelect(variable.value)}
				>
					<div className="variable-info">
						<code className="variable-code">{variable.value}</code>
						<span className="variable-label">{variable.label}</span>
					</div>
					<span className="variable-description">{variable.description}</span>
				</button>
			))}
		</div>
	);

	return (
		<>
			<Button
				variant="tertiary"
				onClick={() => setIsOpen(!isOpen)}
				className="field-action-btn variables-popup-trigger"
				icon={<Icon name="code" size={16} />}
				label={__('Insert Variable', 'swift-rank')}
				{...buttonProps}
			/>
			{isOpen && (
				<Popover
					className="swift-rank-variables-popover"
					onClose={() => {
						setIsOpen(false);
						setSearchTerm('');
					}}
					position="bottom left"
					focusOnMount="firstElement"
				>
					<div className="variables-popup-content">
						<div className="variables-popup-header">
							<h3>{__('Insert Variable', 'swift-rank')}</h3>
							<p className="description">
								{__('Variables are replaced with dynamic content when the schema is output.', 'swift-rank')}
							</p>
						</div>
						<div className="variables-search">
							<SearchControl
								value={searchTerm}
								onChange={setSearchTerm}
								placeholder={__('Search variables...', 'swift-rank')}
							/>
						</div>
						{searchTerm ? (
							<div className="variables-search-results">
								{Object.keys(filteredGroups).length === 0 ? (
									<p className="no-results">{__('No variables found', 'swift-rank')}</p>
								) : (
									Object.entries(filteredGroups).map(([key, group]) => (
										<div key={key} className="search-result-group">
											<h4 className="group-label">
												<Icon name={group.icon} size={16} />
												{group.label}
											</h4>
											{renderVariables(group.variables)}
										</div>
									))
								)}
							</div>
						) : (
							<TabPanel
								className="variables-tabs"
								tabs={tabs}
								initialTabName="post"
							>
								{(tab) => renderVariables(variableGroups[tab.name].variables)}
							</TabPanel>
						)}
					</div>
				</Popover>
			)}
		</>
	);
};

export default VariablesPopup;
export { getVariableGroups };
