import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import Select from 'react-select';
import Icon from '../../components/Icon';
import Tooltip from '../../components/Tooltip';
import ProNotice from '../../components/ProNotice';
import { getSelectStyles } from '../utils/selectStyles';

/**
 * Get schema types from localized data
 * @returns {Array} Array of schema type configurations
 */
const getSchemaTypes = () => {
	return window.swiftRankMetabox?.schemaTypes || [];
};

const SchemaTypeSelector = ({ value, onChange }) => {
	const schemaTypes = getSchemaTypes();

	// Check if pro is activated
	const isProActivated = window.swiftRankConfig?.isProActivated || false;

	// Get base grid styles and extend with Pro-specific styling
	const baseStyles = getSelectStyles('grid');

	const customStyles = {
		...baseStyles,
		control: (provided, state) => ({
			...baseStyles.control(provided, state),
			minHeight: '40px',
			borderRadius: '4px'
		}),
		option: (provided, state) => {
			const baseOptionStyles = baseStyles.option(provided, state);
			const isSelected = state.isSelected;
			const isFocused = state.isFocused;

			return {
				...baseOptionStyles,
				backgroundColor: isSelected
					? '#2271b1'
					: isFocused
						? state.data.isPro && !isProActivated ? '#fff8e5' : baseOptionStyles.backgroundColor
						: 'white',
				borderLeft: state.data.isPro && !isProActivated ? '3px solid #f0b849' : baseOptionStyles.border || 'none',
				color: isSelected ? '#ffffff' : baseOptionStyles.color
			};
		},
		menu: (provided) => ({
			...baseStyles.menu(provided),
			boxShadow: '0 2px 8px rgba(0, 0, 0, 0.1)',
			borderRadius: '4px'
		})
	};

	// Format option label to show Pro badge and icon
	const formatOptionLabel = (option, { context }) => {
		const { label, isPro, description, icon } = option;

		// Show Pro badge for pro types when not activated
		const showProBadge = isPro && !isProActivated;

		return (
			<div
				style={{ display: 'flex', flexDirection: 'column', width: '100%' }}
				title={description || ''} // Show description on hover
			>
				<div style={{
					fontWeight: 500,
					fontSize: '12px', // Smaller font for compact layout
					display: 'flex',
					alignItems: 'center',
					gap: '6px'
				}}>
					{icon && (
						<span style={{
							flexShrink: 0,
							display: 'inline-flex'
						}}>
							<Icon
								name={icon}
								size={14} // Smaller icon
								style={{ flexShrink: 0 }}
							/>
						</span>
					)}
					<span style={{ flex: 1 }}>{label}</span>
					{showProBadge && (
						<span style={{
							padding: '2px 5px',
							background: 'linear-gradient(135deg, #f0b849 0%, #d99c00 100%)',
							color: '#1e1e1e',
							borderRadius: '3px',
							fontSize: '8px',
							fontWeight: '700',
							textTransform: 'uppercase',
							letterSpacing: '0.5px',
							lineHeight: '1'
						}}>
							Pro
						</span>
					)}
				</div>
			</div>
		);
	};

	// Find selected option
	const selectedOption = schemaTypes.find(type => type.value === value) || null;

	// Handle custom schema type selection
	const handleSelectCustomType = () => {
		const customType = schemaTypes.find(type => type.value === 'Custom');
		if (customType) {
			onChange('Custom');
		}
	};

	// Custom menu component with notice bar
	const MenuList = (props) => {
		const { children, innerRef, innerProps } = props;

		return (
			<div
				ref={innerRef}
				{...innerProps}
				style={{
					...innerProps.style,
					display: 'grid',
					gridTemplateColumns: 'repeat(5, 1fr)',
					gap: '6px',
					padding: '6px'
				}}
			>
				{/* Notice Bar inside dropdown */}
				{isProActivated ? (
					// Pro Active: Show button to select Custom Type
					<div style={{
						background: '#e7f3ff',
						border: '1px solid #b3d9ff',
						borderRadius: '4px',
						padding: '10px',
						margin: '2px',
						fontSize: '12px',
						lineHeight: '1.4',
						gridColumn: '1 / -1', // Span all columns in grid
						display: 'flex',
						alignItems: 'center',
						justifyContent: 'space-between',
						gap: '12px'
					}}>
						<div style={{ flex: 1 }}>
							<div style={{ fontWeight: '600', marginBottom: '4px' }}>
								{__('Can\'t find your schema type?', 'swift-rank')}
							</div>
							<div style={{ color: '#3c434a', fontSize: '11px' }}>
								{__('Create a Custom Schema if the type you need is not listed below.', 'swift-rank')}
							</div>
						</div>
						<button
							type="button"
							onClick={handleSelectCustomType}
							style={{
								background: '#e7f3ff',
								color: '#2271b1',
								border: '1px solid #2271b1',
								borderRadius: '3px',
								padding: '6px 14px',
								fontSize: '11px',
								fontWeight: '500',
								cursor: 'pointer',
								whiteSpace: 'nowrap',
								flexShrink: 0
							}}
							onMouseOver={(e) => {
								e.target.style.background = '#2271b1';
								e.target.style.color = '#ffffff';
							}}
							onMouseOut={(e) => {
								e.target.style.background = '#e7f3ff';
								e.target.style.color = '#2271b1';
							}}
						>
							{__('Select Custom Type', 'swift-rank')}
						</button>
					</div>
				) : (
					// Pro NOT Active: Show upgrade notice using ProNotice component
					<div style={{ gridColumn: '1 / -1', margin: '2px' }}>
						<ProNotice
							message={__('Create custom schema types with Schema Builder', 'swift-rank')}
							linkText={__('Upgrade to Pro', 'swift-rank')}
							compact={true}
						/>
					</div>
				)}
				{children}
			</div>
		);
	};

	return (
		<div className="schema-field">
			<div className="field-header" style={{ marginBottom: '8px' }}>
				<label className="field-label">
					{__('Schema Type', 'swift-rank')}
					<Tooltip text={__('Choose the type of schema markup. This determines what fields are available and how your content appears in search results.', 'swift-rank')} />
				</label>
			</div>

			<Select
				value={selectedOption}
				onChange={(selectedOption) => onChange(selectedOption ? selectedOption.value : '')}
				options={schemaTypes}
				styles={customStyles}
				formatOptionLabel={formatOptionLabel}
				placeholder={__('Select Schema Type...', 'swift-rank')}
				isSearchable={true}
				className="schema-type-select"
				components={{ MenuList }}
			/>
		</div>
	);
};

export default SchemaTypeSelector;
