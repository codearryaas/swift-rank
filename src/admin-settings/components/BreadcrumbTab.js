import { __ } from '@wordpress/i18n';
import FieldRenderer from '../../components/FieldRenderer';
import breadcrumbFields from '../config/breadcrumb-fields';

const BreadcrumbTab = ({ settings, updateSetting }) => {
    // Handle individual field changes
    const handleFieldChange = (fieldName, value) => {
        updateSetting(fieldName, value);
    };

    return (
        <div className="swift-rank-breadcrumb-settings">
            <h2>{__('Breadcrumb Schema', 'swift-rank')}</h2>
            <p className="description">
                {__('Configure BreadcrumbList schema to help search engines understand your site hierarchy.', 'swift-rank')}
            </p>

            <div className="schema-fields-container" style={{ marginTop: '20px' }}>
                {breadcrumbFields.map((fieldConfig) => (
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
            </div>
        </div>
    );
};

export default BreadcrumbTab;
