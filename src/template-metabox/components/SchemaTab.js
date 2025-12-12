import { useState, useEffect, useMemo } from '@wordpress/element';
import { Notice, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import SchemaTypeSelector from './SchemaTypeSelector';
import FieldsBuilder from '../../components/FieldsBuilder';
import ProUpgradeNotice from '../../components/ProUpgradeNotice';
import Icon from '../../components/Icon';

/**
 * Get schema types from localized data
 * @returns {Array} Array of schema type configurations
 */
const getSchemaTypes = () => {
	return window.swiftRankMetabox?.schemaTypes || [];
};

const SchemaTab = ({ schemaType, schemaFields, onSchemaTypeChange, onSchemaFieldsChange, onConditionsChange }) => {
	// Check if Pro is activated
	const isProActivated = window.swiftRankConfig?.isProActivated || false;

	// State for preset modal
	const [isPresetModalOpen, setIsPresetModalOpen] = useState(false);

	// Get PresetModal component from Pro plugin
	const PresetModal = window.swiftRankProComponents?.SchemaPresetModal;

	// Check if the selected type is a Pro type and Pro is not activated
	const isProTypeWithoutLicense = useMemo(() => {
		if (!schemaType || isProActivated) return false;
		const schemaTypes = getSchemaTypes();
		const selectedType = schemaTypes.find(t => t.value === schemaType);
		return selectedType?.isPro && !isProActivated;
	}, [schemaType, isProActivated]);

	const handlePresetSelect = (preset) => {
		// Set schema type
		onSchemaTypeChange(preset.type);
		// Set schema fields
		onSchemaFieldsChange(preset.fields);

		// Set conditions if available and handler provided
		if (preset.conditions && onConditionsChange) {
			// Convert preset conditions (flat array) to conditions state structure (groups)
			const newConditions = {
				logic: 'or',
				groups: [
					{
						logic: 'and',
						rules: preset.conditions || []
					}
				]
			};
			onConditionsChange(newConditions);
		}
	};

	const renderSchemaFields = () => {
		// Show upgrade notice for Pro types when Pro is not activated
		if (isProTypeWithoutLicense) {
			return <ProUpgradeNotice schemaType={schemaType} />;
		}

		// Use FieldsBuilder directly - it reads fields from localized PHP data
		return (
			<div className={`schema-${schemaType.toLowerCase()}-fields`}>
				<FieldsBuilder
					schemaType={schemaType}
					fields={schemaFields}
					onChange={onSchemaFieldsChange}
					isPostMetabox={true}
				/>
			</div>
		);
	};

	return (
		<div className="schema-tab-content">
			{/* Schema Preset Button - Only show if Pro is active */}
			{isProActivated && PresetModal && (
				<div className="schema-preset-section" style={{
					marginBottom: '20px',
					display: 'flex',
					alignItems: 'center',
					justifyContent: 'space-between',
					gap: '16px',
					padding: '16px',
					background: 'linear-gradient(135deg, #f0f6fc 0%, #e8f2fa 100%)',
					borderRadius: '6px',
					border: '1px solid #d0e5f5'
				}}>
					<p className="description" style={{
						margin: 0,
						flex: 1,
						color: '#1d2327',
						fontSize: '13px'
					}}>
						{__('Choose from pre-configured schema templates to quickly set up your content.', 'swift-rank')}
					</p>
					<Button
						variant="primary"
						onClick={() => setIsPresetModalOpen(true)}
						className="schema-preset-button"
						style={{
							whiteSpace: 'nowrap',
							display: 'flex',
							alignItems: 'center',
							gap: '8px'
						}}
					>
						<Icon name="layout-template" size={16} />
						{__('Load from Preset', 'swift-rank')}
					</Button>

					<PresetModal
						isOpen={isPresetModalOpen}
						onClose={() => setIsPresetModalOpen(false)}
						onSelectPreset={handlePresetSelect}
					/>
				</div>
			)}

			<div className="schema-type-selector">
				<SchemaTypeSelector
					value={schemaType}
					onChange={onSchemaTypeChange}
				/>
			</div>

			{schemaType && (
				<div className="schema-fields-section">
					{renderSchemaFields()}
				</div>
			)}

			{!schemaType && (
				<Notice status="warning" isDismissible={false}>
					{__('Please select a schema type above to configure the fields.', 'swift-rank')}
				</Notice>
			)}
		</div>
	);
};

export default SchemaTab;
