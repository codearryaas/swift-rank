import { __ } from '@wordpress/i18n';
import FieldRenderer from '../../components/FieldRenderer';
import generalFields from '../config/general-fields';

const GeneralTab = ({ settings, updateSetting }) => {
    const isProActive = typeof window.swiftRankSettings !== 'undefined' && window.swiftRankSettings.isProActive;

    // Handle individual field changes
    const handleFieldChange = (fieldName, value) => {
        updateSetting(fieldName, value);
    };

    return (
        <div className="swift-rank-general-settings">
            <h2>{__('General Settings', 'swift-rank')}</h2>
            <p className="description">
                {__('Configure general schema output settings for your website.', 'swift-rank')}
            </p>

            <div className="schema-fields-container" style={{ marginTop: '20px' }}>
                {generalFields.map((fieldConfig, index) => {
                    // Check if Pro field should be accessible
                    const isProField = fieldConfig.isPro;
                    const canAccess = !isProField || isProActive;

                    const fieldElement = (
                        <FieldRenderer
                            key={fieldConfig.name}
                            fieldConfig={fieldConfig}
                            value={settings[fieldConfig.name]}
                            onChange={(value) => handleFieldChange(fieldConfig.name, value)}
                            fields={settings}
                            isOverridden={false}
                            onReset={null}
                            isPostMetabox={false}
                            allFieldConfigs={generalFields}
                        />
                    );

                    // Wrap in group container if this is the start of a group
                    if (fieldConfig.groupStart) {
                        const groupFields = [fieldElement];
                        let i = index + 1;

                        // Collect all fields in this group
                        while (i < generalFields.length) {
                            const nextField = generalFields[i];

                            // Stop if we hit a field that's not in this group
                            if (!nextField.group || nextField.group !== fieldConfig.group) {
                                break;
                            }

                            // Check if field should be rendered (condition check)
                            let shouldRender = true;
                            if (nextField.condition && typeof nextField.condition === 'function') {
                                shouldRender = nextField.condition(settings);
                            }

                            // Check dependsOn (parent field)
                            if (shouldRender && nextField.parent) {
                                const parentValue = settings[nextField.parent];
                                shouldRender = !!parentValue;
                            }

                            // Only add field if it should be rendered
                            if (shouldRender) {
                                groupFields.push(
                                    <FieldRenderer
                                        key={nextField.name}
                                        fieldConfig={nextField}
                                        value={settings[nextField.name]}
                                        onChange={(value) => handleFieldChange(nextField.name, value)}
                                        fields={settings}
                                        isOverridden={false}
                                        onReset={null}
                                        isPostMetabox={false}
                                        allFieldConfigs={generalFields}
                                    />
                                );
                            }

                            // Break after processing the last field in group
                            if (nextField.groupEnd) break;
                            i++;
                        }

                        return (
                            <div key={`group-${fieldConfig.group}`} className={`schema-field-group schema-field-group-${fieldConfig.group}`}>
                                {groupFields}
                            </div>
                        );
                    }

                    // Skip fields that are part of a group (already rendered)
                    if (fieldConfig.group && !fieldConfig.groupStart) {
                        return null;
                    }

                    // Check if standalone field should be rendered (condition check)
                    if (fieldConfig.condition && typeof fieldConfig.condition === 'function') {
                        const shouldRender = fieldConfig.condition(settings);
                        if (!shouldRender) {
                            return null;
                        }
                    }

                    return fieldElement;
                })}
            </div>
        </div>
    );
};

export default GeneralTab;
