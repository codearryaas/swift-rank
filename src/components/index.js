/**
 * Global exports for shared components
 * These are exposed via window.swiftRankComponents
 * for use by pro version and other plugins
 */

export { default as Tooltip } from './Tooltip';
export { default as Select } from './Select';
export { default as SchemaField } from './SchemaField';
export { default as FieldRenderer } from './FieldRenderer';
export { default as FieldsBuilder } from './FieldsBuilder';
export { default as ConfirmModal } from './ConfirmModal';

export { default as VariablesPopup, getVariableGroups } from './VariablesPopup';
export { default as ProUpgradeNotice } from './ProUpgradeNotice';
export { default as ProUpgradeSidebar } from './ProUpgradeSidebar';
export { default as PostMetaboxProNotice } from './PostMetaboxProNotice';
export { default as RepeaterField } from './RepeaterField';
export { default as SchemaReferenceField } from './SchemaReferenceField';

// Note: Schema field configurations are now localized from PHP
// See: class-swift-rank-cpt.php -> get_schema_types()
// Fields are available via window.swiftRankMetabox.schemaTypes
