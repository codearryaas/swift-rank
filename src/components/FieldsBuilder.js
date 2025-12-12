/**
 * Fields Builder Component
 * Builds a complete form from field configuration
 * Used by both template metabox and post metabox
 */

import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import FieldRenderer from './FieldRenderer';
import Icon from './Icon';
import ConfirmModal from './ConfirmModal';

/**
 * Get fields for a schema type from localized data
 * @param {string} schemaType - The schema type (Article, Organization, etc.)
 * @returns {Array} Array of field configurations
 */
const getFieldsForSchemaType = (schemaType) => {
	// Get schema types from localized data (template metabox, post metabox, settings page, or wizard)
	const schemaTypes = window.swiftRankMetabox?.schemaTypes ||
		window.swiftRankPostMetabox?.schemaTypes ||
		window.swiftRankSettings?.schemaTypes ||
		window.swiftRankWizardSettings?.schemaTypes ||
		[];

	const type = schemaTypes.find(t => t.value === schemaType);
	return type?.fields || [];
};

/**
 * @param {Object} props
 * @param {string} props.schemaType - The schema type (Article, Organization, etc.)
 * @param {Object} props.fields - Current field values
 * @param {Function} props.onChange - Callback when fields change
 * @param {Object} props.overrides - Field overrides (for post metabox)
 * @param {Function} props.onResetField - Callback to reset a field with confirmation (for post metabox)
 * @param {Function} props.onRemoveOverride - Callback to silently remove an override (for post metabox)
 * @param {Function} props.onResetAll - Callback to reset all fields (for post metabox)
 * @param {boolean} props.isPostMetabox - Whether this is the post metabox context
 */
const FieldsBuilder = ({ schemaType, fields, onChange, overrides = {}, onResetField, onRemoveOverride, onResetAll, isPostMetabox = false }) => {

	let fieldsConfig = getFieldsForSchemaType(schemaType);

	// Allow pro plugin to extend fields based on sub-type
	// For example, adding opening hours to Organization when organizationType is LocalBusiness
	if (wp.hooks) {
		fieldsConfig = wp.hooks.applyFilters(
			'swift_rank_extend_fields',
			fieldsConfig,
			schemaType,
			fields
		);
	}

	if (!fieldsConfig || fieldsConfig.length === 0) {
		return (
			<div className="schema-no-fields">
				<p>{__('No fields available for this schema type.', 'swift-rank')}</p>
			</div>
		);
	}

	const updateField = (fieldName, value) => {
		// Get the field config to check against default
		const fieldConfig = fieldsConfig.find(f => f.name === fieldName);
		const defaultValue = fieldConfig?.default;

		// In post metabox: if value equals the default, silently remove the override
		// This prevents false-positive overrides when value matches default
		if (isPostMetabox && value === defaultValue && onRemoveOverride) {
			onRemoveOverride(fieldName);
			return;
		}

		onChange({
			...fields,
			[fieldName]: value
		});
	};

	// Helper to check if a field is the subtype field (e.g., articleType, organizationType)
	const isSubtypeField = (fieldName) => {
		if (!schemaType) return false;
		const subtypeName = schemaType.charAt(0).toLowerCase() + schemaType.slice(1) + 'Type';
		return fieldName === subtypeName;
	};

	// Filter out conditional fields that shouldn't be shown
	const visibleFields = fieldsConfig.filter((fieldConfig) => {
		// Hide fields marked as hideInKnowledgeBase when not in post metabox (i.e., in Knowledge Base settings)
		if (!isPostMetabox && fieldConfig.hideInKnowledgeBase) {
			return false;
		}

		// If field has a condition, check if it should be displayed
		if (fieldConfig.condition) {
			const { field, value } = fieldConfig.condition;
			return fields[field] === value;
		}
		return true;
	});

	// Calculate changed fields count (excluding type/subtype fields)
	const changedFieldsCount = visibleFields.filter((field) => {
		// Don't count subtype fields as changes (e.g., articleType, organizationType)
		if (isSubtypeField(field.name)) {
			return false;
		}

		// In post metabox, count fields that have overrides
		if (isPostMetabox) {
			return overrides.hasOwnProperty(field.name);
		}

		// In template metabox, compare against defaults
		const value = fields[field.name];
		const defaultValue = field.default;

		// If value is undefined, it hasn't been set/changed in the state yet
		if (value === undefined) {
			return false;
		}

		return value !== defaultValue;
	}).length;

	const [showResetModal, setShowResetModal] = useState(false);

	const handleReset = () => {
		// In post metabox, use the onResetAll callback if provided
		if (isPostMetabox && onResetAll) {
			onResetAll();
			setShowResetModal(false);
			return;
		}

		// In template metabox, reset fields to defaults
		const newFields = {};
		fieldsConfig.forEach((field) => {
			// Preserve type/subtype field values, reset others to default
			if (isSubtypeField(field.name)) {
				// Keep the current value for type/subtype fields
				if (fields[field.name] !== undefined) {
					newFields[field.name] = fields[field.name];
				} else if (field.default !== undefined) {
					newFields[field.name] = field.default;
				}
			} else if (field.default !== undefined) {
				newFields[field.name] = field.default;
			}
		});
		onChange(newFields);
		setShowResetModal(false);
	};

	const hasSubtypeField = visibleFields.some(f => isSubtypeField(f.name));

	const renderResetBar = () => {
		// Only show reset bar in post metabox
		if (!isPostMetabox) return null;

		return (
			<div className="schema-reset-bar">
				<div className="reset-bar-content">
					<span className="changed-count">
						{changedFieldsCount} {changedFieldsCount === 1 ? __('field changed', 'swift-rank') : __('fields changed', 'swift-rank')}
					</span>
					<Button
						variant="tertiary"
						isDestructive
						onClick={() => setShowResetModal(true)}
						className="reset-all-btn"
					>
						<Icon name="refresh-cw" size={16} />
						{__('Reset All', 'swift-rank')}
					</Button>
				</div>
			</div>
		);
	};

	const renderResetModal = () => (
		<ConfirmModal
			isOpen={showResetModal}
			onClose={() => setShowResetModal(false)}
			onConfirm={handleReset}
			title={__('Confirm Reset', 'swift-rank')}
			message={__('Are you sure you want to reset all fields to their default values?', 'swift-rank')}
			confirmText={__('Reset', 'swift-rank')}
			isDestructive={true}
		/>
	);

	return (
		<div className="schema-fields-builder">
			{renderResetModal()}
			{!hasSubtypeField && changedFieldsCount > 0 && renderResetBar()}
			{visibleFields.map((fieldConfig) => {
				const isOverridden = overrides.hasOwnProperty(fieldConfig.name);

				return (
					<React.Fragment key={fieldConfig.name}>
						<FieldRenderer
							fieldConfig={fieldConfig}
							value={fields[fieldConfig.name]}
							onChange={(value) => updateField(fieldConfig.name, value)}
							fields={fields}
							isOverridden={isOverridden}
							onReset={onResetField ? () => onResetField(fieldConfig.name) : undefined}
							isPostMetabox={isPostMetabox}
						/>
						{isSubtypeField(fieldConfig.name) && changedFieldsCount > 0 && renderResetBar()}
					</React.Fragment>
				);
			})}
		</div>
	);
};

export default FieldsBuilder;

// Export the helper function for use in other components
export { getFieldsForSchemaType };
