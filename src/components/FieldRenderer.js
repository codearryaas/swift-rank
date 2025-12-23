/**
 * Field Renderer Component
 * Dynamically renders fields based on configuration
 * Used by both template metabox and post metabox
 */

import React from 'react';
import SchemaField from './SchemaField';
import Select from './Select';
import Tooltip from './Tooltip';
import Icon from './Icon';
import RepeaterField from './RepeaterField';
import ProNotice from './ProNotice';
import ProUpgradeNotice from './ProUpgradeNotice';
import SchemaReferenceField from './SchemaReferenceField';
import VariablesPopup from './VariablesPopup';

const FieldRenderer = ({ fieldConfig, value, onChange, fields, isOverridden, onReset, isPostMetabox = false, allFieldConfigs = [] }) => {
	// Check if Pro is activated (support both admin settings and wizard context)
	const isProActivated = window.swiftRankConfig?.isProActivated || window.swiftRankWizardSettings?.isProActive || false;
	const { name, label, type, tooltip, options, placeholder, rows, default: defaultValue, isPro, dependsOn, showWhen, allowCustom, customType, parent, indent, indentLevel, condition } = fieldConfig;

	// Handle conditional field visibility (condition function)
	if (condition && typeof condition === 'function') {
		if (!condition()) {
			return null;
		}
	}

	// Handle parent field dependency (nested fields)
	if (parent && fields) {
		const parentValue = fields[parent];

		// If parent value is undefined, check the parent field's default value
		let isParentEnabled;
		if (parentValue === undefined) {
			// Find parent field config to get its default value
			const parentFieldConfig = allFieldConfigs.find(f => f.name === parent);
			const parentDefault = parentFieldConfig?.default;
			isParentEnabled = parentDefault === true || parentDefault === 'true' || parentDefault === '1' || parentDefault === 1;
		} else {
			// Check if parent is enabled (for toggle/checkbox fields)
			isParentEnabled = parentValue === true || parentValue === 'true' || parentValue === '1' || parentValue === 1;
		}

		if (!isParentEnabled) {
			return null;
		}
	}

	// Handle conditional field visibility (dependsOn/showWhen)
	if (dependsOn && fields) {
		const dependentValue = fields[dependsOn];
		const shouldShow = showWhen !== undefined ? (!!dependentValue === showWhen) : !!dependentValue;
		if (!shouldShow) {
			return null;
		}
	}

	// Flexible field toggle logic (allowCustom support)
	const predefinedOptions = options ? options.filter(opt => opt.value !== '__custom__') : [];
	const hasOptions = predefinedOptions.length > 0;
	const isValueInOptions = hasOptions && predefinedOptions.find(opt => opt.value === value);

	const [isCustomMode, setIsCustomMode] = React.useState(() => {
		// If no options, allowCustom just enables the toggle icon (always start in picker mode)
		if (!allowCustom) return false;
		if (!hasOptions) return false; // Start with picker for datetime/time

		// If has options, check if value is custom
		if (!value) return false;
		return !isValueInOptions && value !== '__custom__';
	});
	const [customInputRef, setCustomInputRef] = React.useState(null);
	const manualToggleRef = React.useRef(false);

	// Update custom mode when value changes (but not during manual toggle)
	React.useEffect(() => {
		if (!allowCustom) return;

		// Skip if this was a manual toggle
		if (manualToggleRef.current) {
			manualToggleRef.current = false;
			return;
		}

		// For fields without options (datetime, time), don't auto-switch
		if (!hasOptions) return;

		// For fields with options, auto-detect custom values
		const shouldBeCustom = value && value !== '__custom__' && !predefinedOptions.find(opt => opt.value === value);
		setIsCustomMode(shouldBeCustom);
	}, [value, allowCustom, hasOptions]);

	// Toggle between predefined and custom mode
	const toggleCustomMode = () => {
		manualToggleRef.current = true; // Mark as manual toggle
		const newMode = !isCustomMode;
		setIsCustomMode(newMode);

		if (newMode) {
			// Switching to custom mode - clear value
			onChange('');
		} else {
			// Switching back to predefined
			if (hasOptions) {
				// Set to first option or default
				onChange(predefinedOptions[0]?.value || defaultValue || '');
			} else {
				// For pickers without options, just clear
				onChange('');
			}
		}
	};

	// Render toggle icon (pencil/list)
	const renderToggleIcon = () => {
		if (!allowCustom) return null;

		return (
			<button
				type="button"
				onClick={toggleCustomMode}
				className="field-toggle-btn"
				aria-label={isCustomMode ? 'Use predefined options' : 'Use custom value'}
				title={isCustomMode ? 'Switch to predefined options' : 'Enter custom value'}
				style={{
					background: 'none',
					border: 'none',
					cursor: 'pointer',
					padding: '4px 8px',
					marginLeft: 'auto',
					color: '#2271b1',
					display: 'flex',
					alignItems: 'center',
				}}
			>
				<Icon name={isCustomMode ? 'list' : 'edit-3'} size={16} />
			</button>
		);
	};

	// Insert variable at cursor position in custom input
	const insertVariable = (variable) => {
		const currentValue = value || '';

		if (customInputRef) {
			const cursorPos = customInputRef.selectionStart || 0;
			const textBefore = currentValue.substring(0, cursorPos);
			const textAfter = currentValue.substring(cursorPos);
			const newValue = textBefore + variable + textAfter;
			onChange(newValue);

			// Set cursor position after variable
			setTimeout(() => {
				if (customInputRef) {
					customInputRef.focus();
					customInputRef.setSelectionRange(
						cursorPos + variable.length,
						cursorPos + variable.length
					);
				}
			}, 0);
		} else {
			onChange(currentValue + variable);
		}
	};

	// Apply indentation class if specified
	const wrapperClass = indent ? `nested-field-level-${indentLevel || 1}` : '';

	// Render heading field type
	if (type === 'heading') {
		return (
			<div className="schema-field">
				<h3>{label}</h3>
				{fieldConfig.description && (
					<p className="description">
						{fieldConfig.description}
					</p>
				)}
			</div>
		);
	}

	// Render button field type
	if (type === 'button') {
		return (
			<div className="schema-field">
				{label && (
					<div className="field-header">
						<label className="field-label">{label}</label>
					</div>
				)}
				<button
					type="button"
					className={`button ${fieldConfig.buttonClass || 'button-primary'}`}
					onClick={fieldConfig.onClick}
				>
					{fieldConfig.buttonLabel || label}
				</button>
				{fieldConfig.description && (
					<p className="description" style={{ marginTop: '8px' }}>
						{fieldConfig.description}
					</p>
				)}
			</div>
		);
	}

	// Render custom input (text, textarea, or image)
	const renderCustomInput = () => {
		const isTextarea = customType === 'textarea' || (type === 'textarea' && rows > 1);
		const isImage = customType === 'image';
		const isRequired = fieldConfig.required || false;

		return (
			<div className={`schema-field ${isOverridden ? 'has-override' : ''}`}>
				<div className="field-header">
					<label className="field-label">
						{label}
						{isRequired && <span style={{ color: '#d63638', marginLeft: '4px' }}>*</span>}
						{tooltip && <Tooltip text={tooltip} />}
					</label>
					{renderToggleIcon()}
					{isOverridden && onReset && (
						<div className="field-actions">
							<button
								type="button"
								className="components-button is-tertiary is-destructive field-action-btn reset-btn"
								onClick={onReset}
								aria-label="Reset to default"
							>
								<Icon name="refresh-cw" size={16} />
							</button>
						</div>
					)}
				</div>
				<div style={{ position: 'relative' }}>
					{isImage ? (
						<SchemaField
							type="image"
							value={value}
							onChange={onChange}
							placeholder={placeholder}
							noHeader={true}
							returnObject={fieldConfig.returnObject || true}
						/>
					) : isTextarea ? (
						<textarea
							value={value || ''}
							onChange={(e) => onChange(e.target.value)}
							placeholder={placeholder || 'Enter custom value or variable'}
							ref={setCustomInputRef}
							rows={rows || 4}
							className="components-textarea-control__input"
							style={{
								width: '100%',
								paddingRight: '40px',
							}}
						/>
					) : (
						<input
							type="text"
							value={value || ''}
							onChange={(e) => onChange(e.target.value)}
							placeholder={placeholder || 'Enter custom value or variable'}
							ref={setCustomInputRef}
							className="components-text-control__input"
							style={{
								width: '100%',
								paddingRight: '40px',
							}}
						/>
					)}
					{!isImage && (
						<div style={{
							position: 'absolute',
							right: '8px',
							top: isTextarea ? '8px' : '50%',
							transform: isTextarea ? 'none' : 'translateY(-50%)',
							zIndex: 1
						}}>
							<VariablesPopup
								onSelect={insertVariable}
								buttonProps={{
									isSmall: true,
									// Wrap Icon in span to prevent Button from overriding size
									icon: <span style={{ display: 'flex' }}><Icon name="code" size={12} style={{ width: '12px', height: '12px', display: 'block' }} /></span>,
									style: { minWidth: '20px', height: '20px', padding: 0, justifyContent: 'center' }
								}}
							/>
						</div>
					)}
				</div>
			</div>
		);
	};

	// In post metabox, hide sub-type selects (organizationType, articleType, etc.)
	if (isPostMetabox && type === 'select' && (name === 'organizationType' || name === 'articleType')) {
		return null;
	}

	// Check if this is a Pro field and Pro is not activated
	// (except for opening_hours which has its own handling)
	if (isPro && !isProActivated && type !== 'opening_hours' && type !== 'notice') {
		// Create field-specific message with description if available
		const fieldMessage = fieldConfig.description
			? fieldConfig.description
			: `${label} is a Pro feature.`;

		return (
			<div className="schema-field schema-field-pro-locked">
				<div className="field-header">
					<label className="field-label">
						{label}
						<span className="pro-badge-inline">Pro</span>
						{tooltip && <Tooltip text={tooltip} />}
					</label>
				</div>
				<ProNotice
					message={fieldMessage}
					linkText="Upgrade to unlock"
				/>
			</div>
		);
	}

	// Handle checkbox fields
	if (type === 'checkbox') {
		const effectiveValue = value !== undefined ? value : defaultValue;
		const isChecked = effectiveValue === true || effectiveValue === 'true' || effectiveValue === '1' || effectiveValue === 1;

		return (
			<div className={`schema-field schema-field-checkbox ${isOverridden ? 'has-override' : ''}`}>
				<div className="field-header">
					<label className="field-label checkbox-label" style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer' }}>
						<input
							type="checkbox"
							checked={isChecked}
							onChange={(e) => onChange(e.target.checked)}
							style={{ margin: 0 }}
						/>
						<span>{label}</span>
						{tooltip && <Tooltip text={tooltip} />}
					</label>
					{isOverridden && onReset && (
						<div className="field-actions">
							<button
								type="button"
								className="components-button is-tertiary is-destructive field-action-btn reset-btn"
								onClick={onReset}
								aria-label="Reset to default"
							>
								<Icon name="refresh-cw" size={16} />
							</button>
						</div>
					)}
				</div>
			</div>
		);
	}

	// Handle toggle fields (similar to checkbox but uses WordPress ToggleControl style)
	if (type === 'toggle') {
		const effectiveValue = value !== undefined ? value : defaultValue;
		const isChecked = effectiveValue === true || effectiveValue === 'true' || effectiveValue === '1' || effectiveValue === 1;

		// Determine indent class
		const level = indentLevel || (indent ? 1 : 0);
		const indentClass = level > 0 ? `nested-field-level-${level}` : '';

		return (
			<div className={`schema-field schema-field-toggle ${isOverridden ? 'has-override' : ''} ${indentClass}`}>
				<div className="field-header">
					<label className="field-label">
						{label}
						{tooltip && <Tooltip text={tooltip} />}
					</label>
					{isOverridden && onReset && (
						<div className="field-actions">
							<button
								type="button"
								className="components-button is-tertiary is-destructive field-action-btn reset-btn"
								onClick={onReset}
								aria-label="Reset to default"
							>
								<Icon name="refresh-cw" size={16} />
							</button>
						</div>
					)}
				</div>
				<div className="toggle-wrapper" style={{ marginTop: '8px' }}>
					<label className="toggle-switch" style={{ display: 'inline-flex', alignItems: 'center', cursor: 'pointer' }}>
						<input
							type="checkbox"
							checked={isChecked}
							onChange={(e) => onChange(e.target.checked)}
							className="toggle-input"
							style={{ position: 'absolute', opacity: 0 }}
						/>
						<span className="toggle-slider" style={{
							position: 'relative',
							display: 'inline-block',
							width: '36px',
							height: '20px',
							backgroundColor: isChecked ? '#2271b1' : '#8c8f94',
							borderRadius: '10px',
							transition: 'background-color 0.2s',
						}}>
							<span style={{
								position: 'absolute',
								content: '',
								height: '16px',
								width: '16px',
								left: isChecked ? '18px' : '2px',
								bottom: '2px',
								backgroundColor: 'white',
								borderRadius: '50%',
								transition: 'left 0.2s',
							}} />
						</span>
					</label>
				</div>
			</div>
		);
	}

	// Handle select fields
	if (type === 'select' && options) {
		// If allowCustom is enabled and in custom mode, show custom input
		if (allowCustom && isCustomMode) {
			return renderCustomInput();
		}

		const selectedOption = predefinedOptions.find(opt => opt.value === (value || defaultValue));
		const isRequired = fieldConfig.required || false;

		// Determine indent class
		const level = indentLevel || (indent ? 1 : 0);
		const indentClass = level > 0 ? `nested-field-level-${level}` : '';

		return (
			<div className={`schema-field ${isOverridden ? 'has-override' : ''} ${indentClass}`}>
				<div className="field-header">
					<label className="field-label">
						{label}
						{isRequired && <span style={{ color: '#d63638', marginLeft: '4px' }}>*</span>}
						{tooltip && <Tooltip text={tooltip} />}
					</label>
					{renderToggleIcon()}
					{isOverridden && onReset && (
						<div className="field-actions">
							<button
								type="button"
								className="components-button is-tertiary is-destructive field-action-btn reset-btn"
								onClick={onReset}
								aria-label="Reset to default"
							>
								<Icon name="refresh-cw" size={16} />
							</button>
						</div>
					)}
				</div>
				<div className="swift-rank-select-wrapper">
					<Select
						value={selectedOption}
						onChange={(option) => onChange(option ? option.value : (defaultValue || ''))}
						options={predefinedOptions}
						isSearchable={true}
					/>
				</div>
			</div>
		);
	}

	// Handle select_or_custom fields (dropdown with custom input option)
	if (type === 'select_or_custom' && options) {
		const customOption = options.find(opt => opt.value === '__custom__');
		const predefinedOptions = options.filter(opt => opt.value !== '__custom__');
		const selectedOption = predefinedOptions.find(opt => opt.value === value);
		const isRequired = fieldConfig.required || false;

		// Check if current value is custom (not in predefined options and not empty)
		const isCustomValue = value && value !== '__custom__' && !predefinedOptions.find(opt => opt.value === value);

		// Initialize state based on whether we have a custom value
		const [showCustomInput, setShowCustomInput] = React.useState(isCustomValue);
		const [customInputRef, setCustomInputRef] = React.useState(null);

		// Update state when value changes
		React.useEffect(() => {
			// Show custom input if value is not in predefined options (including empty string after selecting custom)
			const shouldShowCustom = value !== '__custom__' && !predefinedOptions.find(opt => opt.value === value);
			setShowCustomInput(shouldShowCustom);
		}, [value]);

		const handleSelectChange = (option) => {
			if (option && option.value === '__custom__') {
				// Immediately show custom input and clear value
				setShowCustomInput(true);
				// Use setTimeout to ensure state updates before onChange
				setTimeout(() => {
					onChange(''); // Clear value to show placeholder
				}, 0);
			} else {
				setShowCustomInput(false);
				onChange(option ? option.value : (defaultValue || ''));
			}
		};

		const handleCustomInputChange = (newValue) => {
			onChange(newValue);
		};

		// Insert variable at cursor position in custom input
		const insertVariable = (variable) => {
			const currentValue = value || '';

			if (customInputRef) {
				const cursorPos = customInputRef.selectionStart || 0;
				const textBefore = currentValue.substring(0, cursorPos);
				const textAfter = currentValue.substring(cursorPos);
				const newValue = textBefore + variable + textAfter;
				onChange(newValue);

				// Set cursor position after variable
				setTimeout(() => {
					if (customInputRef) {
						customInputRef.focus();
						customInputRef.setSelectionRange(
							cursorPos + variable.length,
							cursorPos + variable.length
						);
					}
				}, 0);
			} else {
				onChange(currentValue + variable);
			}
		};

		return (
			<div className={`schema-field ${isOverridden ? 'has-override' : ''}`}>
				<div className="field-header">
					<label className="field-label">
						{label}
						{isRequired && <span style={{ color: '#d63638', marginLeft: '4px' }}>*</span>}
						{tooltip && <Tooltip text={tooltip} />}
					</label>
					{isOverridden && onReset && (
						<div className="field-actions">
							<button
								type="button"
								className="components-button is-tertiary is-destructive field-action-btn reset-btn"
								onClick={onReset}
								aria-label="Reset to default"
							>
								<Icon name="refresh-cw" size={16} />
							</button>
						</div>
					)}
				</div>
				{!showCustomInput ? (
					<div className="swift-rank-select-wrapper">
						<Select
							value={selectedOption || (value === '__custom__' ? customOption : null)}
							onChange={handleSelectChange}
							options={options}
							isSearchable={true}
						/>
					</div>
				) : (
					<div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
						<div style={{ position: 'relative' }}>
							<input
								type="text"
								value={value || ''}
								onChange={(e) => handleCustomInputChange(e.target.value)}
								placeholder={placeholder || 'Enter custom value or variable'}
								className="components-text-control__input"
								ref={setCustomInputRef}
								style={{
									width: '100%',
									padding: '6px 8px',
									paddingRight: '40px',
									fontSize: '13px',
									lineHeight: '2',
									border: '1px solid #8c8f94',
									borderRadius: '2px',
								}}
							/>
							<div style={{ position: 'absolute', right: '4px', top: '50%', transform: 'translateY(-50%)' }}>
								<VariablesPopup onSelect={insertVariable} />
							</div>
						</div>
						<button
							type="button"
							onClick={() => {
								setShowCustomInput(false);
								onChange(defaultValue || predefinedOptions[0]?.value || '');
							}}
							className="components-button is-link"
							style={{ alignSelf: 'flex-start', padding: 0, height: 'auto', fontSize: '12px' }}
						>
							← Back to dropdown
						</button>
					</div>
				)}
			</div>
		);
	}

	// Handle opening_hours field (from Pro plugin)
	if (type === 'opening_hours') {
		// Show Pro upgrade notice if Pro is not activated
		if (!isProActivated) {
			return (
				<div className="schema-field">
					<div className="field-header">
						<label className="field-label">
							{label}
							{tooltip && <Tooltip text={tooltip} />}
						</label>
					</div>
					<ProUpgradeNotice featureType="opening_hours" schemaType="LocalBusiness" />
				</div>
			);
		}

		const OpeningHoursField = window.swiftRankProComponents?.OpeningHoursField;
		if (OpeningHoursField) {
			return (
				<OpeningHoursField
					label={label}
					value={value}
					onChange={onChange}
					tooltip={tooltip}
					isOverridden={isOverridden}
					onReset={onReset}
				/>
			);
		}
		// Fallback if Pro plugin component not available but Pro is activated
		return null;
	}

	// Handle custom_builder field (from Pro plugin)
	if (type === 'custom_builder') {
		const CustomSchemaBuilder = window.swiftRankProComponents?.CustomSchemaBuilder;
		if (CustomSchemaBuilder) {
			return (
				<div className={`schema-field ${isOverridden ? 'has-override' : ''}`}>
					<div className="field-header">
						<label className="field-label">
							{label}
							{tooltip && <Tooltip text={tooltip} />}
						</label>
						{isOverridden && onReset && (
							<div className="field-actions">
								<button
									type="button"
									className="components-button is-tertiary is-destructive field-action-btn reset-btn"
									onClick={onReset}
									aria-label="Reset to default"
								>
									<Icon name="refresh-cw" size={16} />
								</button>
							</div>
						)}
					</div>
					<CustomSchemaBuilder
						value={value}
						onChange={onChange}
					/>
				</div>
			);
		}
		return null;
	}

	// Handle schema_reference field (Pro feature for relationships)
	if (type === 'schema_reference') {
		// Show Pro upgrade notice if Pro is not activated
		if (!isProActivated) {
			return (
				<div className="schema-field schema-field-pro-locked">
					<div className="field-header">
						<label className="field-label">
							{label}
							<span className="pro-badge-inline">Pro</span>
							{tooltip && <Tooltip text={tooltip} />}
						</label>
					</div>
					<ProNotice
						message={__('Upgrade to Pro to unlock schema relationships', 'swift-rank')}
						linkText={__('Upgrade to Pro', 'swift-rank')}
						compact={true}
						icon="lock"
					/>
				</div>
			);
		}

		return (
			<SchemaReferenceField
				label={label}
				value={value}
				onChange={onChange}
				tooltip={tooltip}
				placeholder={fieldConfig.placeholder}
				targets={fieldConfig.targets || ['Person']}
				sources={fieldConfig.sources || ['users', 'knowledge_graph']}
				allowCustom={fieldConfig.allowCustom !== false}
				customPlaceholder={fieldConfig.customPlaceholder}
				isOverridden={isOverridden}
				onReset={onReset}
				required={fieldConfig.required || false}
			/>
		);
	}

	// Handle generic notice field
	if (type === 'notice') {
		const showLabel = label && fieldConfig.showLabel !== false;

		return (
			<div className={`schema-field schema-field-notice ${fieldConfig.isPro ? 'schema-field-pro-locked' : ''}`}>
				{showLabel && (
					<div className="field-header">
						<label className="field-label">
							{label}
							{tooltip && <Tooltip text={tooltip} />}
						</label>
					</div>
				)}
				<ProNotice
					message={fieldConfig.message || fieldConfig.description}
					linkText={fieldConfig.linkText || __('Learn More', 'swift-rank')}
					linkUrl={fieldConfig.linkUrl}
					compact={fieldConfig.compact !== false}
					icon={fieldConfig.icon || (fieldConfig.isPro ? 'lock' : 'info')}
					allowHtml={fieldConfig.allowHtml || false}
				/>
			</div>
		);
	}



	// Handle repeater field
	if (type === 'repeater') {
		// Import RepeaterField dynamically or assume it's imported at top
		// For now, let's assume we need to import it at the top of this file
		// But since we are editing this file, I will add the import in a separate edit if needed
		// or just use window.swiftRankComponents?.RepeaterField if we want to be safe
		// However, since I just created the file in src/components, I should import it properly.

		return (
			<RepeaterField
				label={label}
				value={value}
				onChange={onChange}
				tooltip={tooltip}
				isOverridden={isOverridden}
				onReset={onReset}
				fields={fieldConfig.fields || []}
			/>
		);
	}

	// Handle media field (same as image type in SchemaField)
	if (type === 'media') {
		return (
			<SchemaField
				label={label}
				fieldName={name}
				value={value !== undefined ? value : (defaultValue || '')}
				onChange={onChange}
				tooltip={tooltip}
				placeholder={placeholder || defaultValue}
				type="image"
				isOverridden={isOverridden}
				onReset={onReset}
				defaultValue={defaultValue}
				required={fieldConfig.required || false}
				returnObject={fieldConfig.returnObject || false}
			/>
		);
	}

	// Auto-populate default value ONLY if value is undefined (not set yet)
	// If value is empty string, it means user actively cleared it, so we respect that.
	const fieldValue = value !== undefined ? value : (defaultValue || '');

	// Handle text/textarea fields with allowCustom and options
	if ((type === 'text' || type === 'textarea' || type === 'url') && allowCustom && options) {
		// If in custom mode, show custom input
		if (isCustomMode) {
			return renderCustomInput();
		}

		// Otherwise show dropdown with predefined options
		const selectedOption = predefinedOptions.find(opt => opt.value === value);
		const isRequired = fieldConfig.required || false;

		return (
			<div className={`schema-field ${isOverridden ? 'has-override' : ''}`}>
				<div className="field-header">
					<label className="field-label">
						{label}
						{isRequired && <span style={{ color: '#d63638', marginLeft: '4px' }}>*</span>}
						{tooltip && <Tooltip text={tooltip} />}
					</label>
					{renderToggleIcon()}
					{isOverridden && onReset && (
						<div className="field-actions">
							<button
								type="button"
								className="components-button is-tertiary is-destructive field-action-btn reset-btn"
								onClick={onReset}
								aria-label="Reset to default"
							>
								<Icon name="refresh-cw" size={16} />
							</button>
						</div>
					)}
				</div>
				<div className="swift-rank-select-wrapper">
					<Select
						value={selectedOption}
						onChange={(option) => onChange(option ? option.value : (defaultValue || ''))}
						options={predefinedOptions}
						isSearchable={true}
					/>
				</div>
			</div >
		);
	}


	// Handle all other field types using SchemaField
	return (
		<SchemaField
			label={label}
			fieldName={name}
			value={fieldValue}
			onChange={onChange}
			tooltip={tooltip}
			placeholder={placeholder || defaultValue}
			type={type}
			rows={rows}
			isOverridden={isOverridden}
			onReset={onReset}
			defaultValue={defaultValue}
			required={fieldConfig.required || false}
		/>
	);
};

export default FieldRenderer;
