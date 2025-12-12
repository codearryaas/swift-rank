/**
 * Schema Reference Field Component
 *
 * A searchable dropdown field that allows linking to other schema entities
 * (Users, Global schemas) to create connected graph relationships.
 *
 * Supports both reference mode (linking to entities) and custom text mode
 * for backward compatibility.
 */

import { useState, useEffect, useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import Select from './Select';
import Tooltip from './Tooltip';
import Icon from './Icon';

const SchemaReferenceField = ({
	label,
	value,
	onChange,
	tooltip,
	placeholder,
	targets = ['Person'], // Allowed schema types
	sources = ['users', 'knowledge_base', 'media'], // Where to look for entities
	allowCustom = false, // Allow custom text input
	customPlaceholder = '',
	isOverridden = false,
	onReset,
	required = false,
}) => {
	const [entities, setEntities] = useState({ global: [], users: [], schema_templates: [], knowledge_base: [], media: [] });
	const [loading, setLoading] = useState(true);
	const [mode, setMode] = useState('reference'); // 'reference' or 'custom'
	const [searchQuery, setSearchQuery] = useState('');
	const isInitialMount = useRef(true);

	// Determine initial mode based on value
	useEffect(() => {
		if (isInitialMount.current) {
			isInitialMount.current = false;

			// If value is a reference object, use reference mode
			if (value && isReferenceObject(value)) {
				setMode('reference');
			}
			// If value is a non-empty string, use custom mode
			else if (value && typeof value === 'string') {
				setMode('custom');
			}
			// Otherwise (null, empty, or variable placeholder), default to reference mode
			else {
				setMode('reference');
			}
		}
	}, [value]);

	// Fetch available entities from API
	useEffect(() => {
		fetchEntities();
	}, []);

	const fetchEntities = async (search = '') => {
		setLoading(true);
		try {
			const response = await apiFetch({
				path: `/swift-rank-pro/v1/entities?type=${targets.join(',')}&search=${search}`,
			});
			setEntities(response);
		} catch (error) {
			console.error('Failed to fetch entities:', error);
			setEntities({ global: [], users: [], schema_templates: [], knowledge_base: [] });
		} finally {
			setLoading(false);
		}
	};

	// Debounced search
	useEffect(() => {
		const timer = setTimeout(() => {
			if (searchQuery) {
				fetchEntities(searchQuery);
			}
		}, 300);

		return () => clearTimeout(timer);
	}, [searchQuery]);

	// Check if value is a reference object
	const isReferenceObject = (val) => {
		return val && typeof val === 'object' && val.type === 'reference';
	};

	// Convert entities to Select options
	const getOptions = () => {
		const options = [];

		// Add context options (dynamic variables)
		const contextOptions = [];
		if (sources.includes('users')) {
			contextOptions.push({
				value: JSON.stringify({ type: 'reference', source: 'user', id: '{post_author_id}' }),
				label: __('Current Author', 'swift-rank-pro'),
				icon: 'admin-users',
				description: __('Dynamic reference to the post author', 'swift-rank-pro')
			});
		}

		if (contextOptions.length > 0) {
			options.push({
				label: __('Context', 'swift-rank-pro'),
				options: contextOptions
			});
		}



		// Add users
		if (sources.includes('users') && entities.users && entities.users.length > 0) {
			options.push({
				label: __('Users', 'swift-rank-pro'),
				options: entities.users.map(entity => ({
					value: JSON.stringify({ type: 'reference', source: 'user', id: entity.id }),
					label: entity.label,
					icon: entity.icon,
					description: entity.description,
				})),
			});
		}

		// Add schema templates
		if (sources.includes('schema_templates') && entities.schema_templates && entities.schema_templates.length > 0) {
			options.push({
				label: __('Schema Templates', 'swift-rank-pro'),
				options: entities.schema_templates.map(entity => ({
					value: JSON.stringify({ type: 'reference', source: 'schema_template', id: entity.id }),
					label: entity.label,
					icon: entity.icon,
					description: entity.description,
				})),
			});
		}

		// Add knowledge base
		if (sources.includes('knowledge_base') && entities.knowledge_base && entities.knowledge_base.length > 0) {
			options.push({
				label: __('Knowledge Base', 'swift-rank-pro'),
				options: entities.knowledge_base.map(entity => ({
					value: JSON.stringify({ type: 'reference', source: 'knowledge_base', id: entity.id }),
					label: entity.label,
					icon: entity.icon,
					description: entity.description,
				})),
			});
		}

		// Add media
		if (sources.includes('media') && entities.media && entities.media.length > 0) {
			options.push({
				label: __('Media Library', 'swift-rank-pro'),
				options: entities.media.map(entity => ({
					value: JSON.stringify({ type: 'reference', source: 'media', id: entity.id }),
					label: entity.label,
					icon: entity.icon,
					description: entity.description,
					// Optional: we could use custom rendering to show thumbnail
				})),
			});
		}

		return options;
	};

	// Get currently selected option
	const getSelectedOption = () => {
		if (!value || !isReferenceObject(value)) {
			return null;
		}

		const options = getOptions();
		for (const group of options) {
			if (group.options) {
				const found = group.options.find(opt => {
					const optValue = JSON.parse(opt.value);
					return optValue.source === value.source && optValue.id === value.id;
				});
				if (found) return found;
			}
		}
		return null;
	};

	// Handle selection change
	const handleChange = (option) => {
		if (option) {
			const referenceValue = JSON.parse(option.value);
			onChange(referenceValue);
		} else {
			onChange(null);
		}
	};

	// Handle search input change
	const handleInputChange = (input, { action }) => {
		if (action === 'input-change') {
			setSearchQuery(input);
		}
	};

	// Toggle between reference and custom mode
	const toggleMode = () => {
		if (mode === 'reference') {
			setMode('custom');
			onChange(''); // Clear value when switching to custom
		} else {
			setMode('reference');
			onChange(null); // Clear value when switching to reference
		}
	};

	// Custom format for option labels with icons
	const formatOptionLabel = ({ label, icon, description }, { context, selectValue }) => {
		// Check if this option is currently selected in the dropdown menu
		const isSelectedInMenu = context === 'menu' && selectValue && selectValue[0] && selectValue[0].label === label;

		return (
			<div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
				{icon && <Icon name={icon} size={14} />}
				<div style={{ flex: 1 }}>
					<div style={{ fontWeight: 500 }}>{label}</div>
					{description && (
						<div style={{
							fontSize: '12px',
							color: isSelectedInMenu ? 'rgba(255, 255, 255, 0.8)' : '#646970',
							marginTop: '2px'
						}}>
							{description}
						</div>
					)}
				</div>
			</div>
		);
	};

	return (
		<div className={`schema-field schema-reference-field ${isOverridden ? 'has-override' : ''}`}>
			<div className="field-header">
				<label className="field-label">
					{label}
					{required && <span style={{ color: '#d63638', marginLeft: '4px' }}>*</span>}
					{tooltip && <Tooltip text={tooltip} />}
				</label>
				<div className="field-actions">
					{allowCustom && (
						<Button
							variant="tertiary"
							onClick={toggleMode}
							className="field-action-btn"
							label={mode === 'reference' ? __('Enter Custom Text', 'swift-rank-pro') : __('Select Reference', 'swift-rank-pro')}
						>
							<Icon name={mode === 'reference' ? 'type' : 'link'} size={16} />
						</Button>
					)}
					{isOverridden && onReset && (
						<Button
							variant="tertiary"
							isDestructive
							onClick={onReset}
							className="field-action-btn reset-btn"
							label={__('Reset to Default', 'swift-rank-pro')}
						>
							<Icon name="refresh-cw" size={16} />
						</Button>
					)}
				</div>
			</div>

			{mode === 'reference' ? (
				<div className="swift-rank-select-wrapper">
					<Select
						value={getSelectedOption()}
						onChange={handleChange}
						options={getOptions()}
						onInputChange={handleInputChange}
						isSearchable={true}
						isLoading={loading}
						isClearable={true}
						placeholder={placeholder || __('Select...', 'swift-rank-pro')}
						formatOptionLabel={formatOptionLabel}
						noOptionsMessage={() =>
							loading
								? __('Loading...', 'swift-rank-pro')
								: __('No entities found', 'swift-rank-pro')
						}
					/>
					<p className="description" style={{ marginTop: '8px', fontSize: '13px', color: '#666' }}>
						{__('Link to an existing entity to create a connected schema graph.', 'swift-rank-pro')}
					</p>
				</div>
			) : (
				<div className="custom-text-input">
					<input
						type="text"
						value={typeof value === 'string' ? value : ''}
						onChange={(e) => onChange(e.target.value)}
						placeholder={customPlaceholder || __('Enter custom text...', 'swift-rank-pro')}
						className="components-text-control__input"
						style={{ width: '100%' }}
					/>
					<p className="description" style={{ marginTop: '8px', fontSize: '13px', color: '#666' }}>
						{__('Enter custom text. Variables like {author_name} are supported.', 'swift-rank-pro')}
					</p>
				</div>
			)}
		</div>
	);
};

export default SchemaReferenceField;
