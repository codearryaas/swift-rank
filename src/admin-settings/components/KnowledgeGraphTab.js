import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';
import Icon from '../../components/Icon';
import FieldsBuilder from '../../components/FieldsBuilder';
import FieldRenderer from '../../components/FieldRenderer';
import knowledgeGraphFields from '../config/knowledge-graph-fields';

const KnowledgeGraphTab = ({ settings, updateSetting }) => {
    const knowledgeGraphEnabled = settings.knowledge_graph_enabled || false;
    const knowledgeGraphType = settings.knowledge_graph_type || 'Organization';
    const organizationFields = settings.organization_fields || {};
    const personFields = settings.person_fields || {};
    const localBusinessFields = settings.localbusiness_fields || {};

    // Get appropriate fields based on type
    let currentFields;
    if (knowledgeGraphType === 'Person') {
        currentFields = personFields;
    } else if (knowledgeGraphType === 'LocalBusiness') {
        currentFields = localBusinessFields;
    } else {
        currentFields = organizationFields;
    }

    // Initialize fields with defaults on first load
    useEffect(() => {
        // Get schema types from localized data
        const schemaTypes = window.swiftRankSettings?.schemaTypes || [];
        const schemaType = schemaTypes.find(t => t.value === knowledgeGraphType);

        if (schemaType?.fields) {
            // Build initial fields with defaults
            const initialFields = {};
            let needsInit = false;

            schemaType.fields.forEach(field => {
                // Check if field is missing or empty in current fields
                if (currentFields[field.name] === undefined || currentFields[field.name] === '') {
                    if (field.default !== undefined) {
                        initialFields[field.name] = field.default;
                        needsInit = true;
                    }
                } else {
                    // Keep existing value
                    initialFields[field.name] = currentFields[field.name];
                }
            });

            // Only update if we need to initialize missing fields
            if (needsInit && Object.keys(initialFields).length > 0) {
                if (knowledgeGraphType === 'Person') {
                    updateSetting('person_fields', initialFields);
                } else if (knowledgeGraphType === 'LocalBusiness') {
                    updateSetting('localbusiness_fields', initialFields);
                } else {
                    updateSetting('organization_fields', initialFields);
                }
            }
        }
    }, [knowledgeGraphType]);

    // Handle fields update from FieldsBuilder
    const handleFieldsChange = (newFields) => {
        if (knowledgeGraphType === 'Person') {
            updateSetting('person_fields', newFields);
        } else if (knowledgeGraphType === 'LocalBusiness') {
            updateSetting('localbusiness_fields', newFields);
        } else {
            updateSetting('organization_fields', newFields);
        }
    };

    // Handle individual field changes for top-level fields
    const handleFieldChange = (fieldName, value) => {
        updateSetting(fieldName, value);
    };

    return (
        <div className="swift-rank-knowledge-graph-settings">
            <h2>{__('Knowledge Graph Schema', 'swift-rank')}</h2>
            <p className="description">
                {__('Configure Organization or Person schema to establish your site\'s identity in Google Knowledge Graph.', 'swift-rank')}
            </p>

            <div className="schema-fields-container" style={{ marginTop: '20px' }}>
                {/* Render top-level toggle and select fields using FieldRenderer */}
                {knowledgeGraphFields.map((fieldConfig) => (
                    <FieldRenderer
                        key={fieldConfig.name}
                        fieldConfig={fieldConfig}
                        value={settings[fieldConfig.name]}
                        onChange={(value) => handleFieldChange(fieldConfig.name, value)}
                        fields={settings}
                        isOverridden={false}
                        onReset={null}
                        isPostMetabox={false}
                    />
                ))}

                {/* Render schema-specific fields using FieldsBuilder */}
                <div style={{ marginTop: '20px' }}>
                    <FieldsBuilder
                        key={knowledgeGraphType}
                        schemaType={knowledgeGraphType}
                        fields={currentFields}
                        onChange={handleFieldsChange}
                        isPostMetabox={false}
                    />
                </div>
            </div>

            <div className="swift-rank-info-box" style={{ marginTop: '20px', padding: '16px', background: '#f0f6fc', border: '1px solid #2271b1', borderRadius: '4px' }}>
                <div style={{ display: 'flex', alignItems: 'flex-start', gap: '12px' }}>
                    <Icon name="info" size={20} style={{ color: '#2271b1', flexShrink: 0, marginTop: '2px' }} />
                    <div>
                        <strong style={{ display: 'block', marginBottom: '8px', color: '#1d2327' }}>
                            {__('About Knowledge Graph', 'swift-rank')}
                        </strong>
                        <p style={{ margin: 0, color: '#3c434a', fontSize: '13px', lineHeight: '1.6' }}>
                            {__('Knowledge Graph schema helps Google understand your organization or personal brand and can display your information in search results, including your logo/photo, name, and social profiles.', 'swift-rank')}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default KnowledgeGraphTab;
