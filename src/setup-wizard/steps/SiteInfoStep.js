import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import WizardStep from '../components/WizardStep';
import FieldRenderer from '../../components/FieldRenderer';
import FieldsBuilder from '../../components/FieldsBuilder';

const SiteInfoStep = ({ onNext, onBack, onSkip, currentStep, totalSteps, isSaving, initialData }) => {
    const [formData, setFormData] = useState({
        knowledge_graph_type: initialData.knowledge_graph_type || 'Organization',
        organization_fields: initialData.organization_fields || {},
        person_fields: initialData.person_fields || {},
        localbusiness_fields: initialData.localbusiness_fields || {},
    });

    const knowledgeGraphType = formData.knowledge_graph_type;

    // Get appropriate fields based on type
    let currentFields;
    if (knowledgeGraphType === 'Person') {
        currentFields = formData.person_fields;
    } else if (knowledgeGraphType === 'LocalBusiness') {
        currentFields = formData.localbusiness_fields;
    } else {
        currentFields = formData.organization_fields;
    }

    // Handle type selector change
    const handleTypeChange = (value) => {
        setFormData({
            ...formData,
            knowledge_graph_type: value,
        });
    };

    // Handle schema fields change from FieldsBuilder
    const handleFieldsChange = (newFields) => {
        if (knowledgeGraphType === 'Person') {
            setFormData({
                ...formData,
                person_fields: newFields,
            });
        } else if (knowledgeGraphType === 'LocalBusiness') {
            setFormData({
                ...formData,
                localbusiness_fields: newFields,
            });
        } else {
            setFormData({
                ...formData,
                organization_fields: newFields,
            });
        }
    };

    const handleNext = () => {
        // Extract only the values, not the entire field objects
        const dataToSave = {
            knowledge_graph_type: formData.knowledge_graph_type,
            organization_fields: formData.organization_fields,
            person_fields: formData.person_fields,
            localbusiness_fields: formData.localbusiness_fields,
        };
        onNext(dataToSave);
    };

    // Type selector field config
    const typeSelectorField = {
        name: 'knowledge_graph_type',
        label: __('Website Type', 'swift-rank'),
        type: 'select',
        options: [
            { label: __('Organization', 'swift-rank'), value: 'Organization' },
            { label: __('Person', 'swift-rank'), value: 'Person' },
            { label: __('Local Business', 'swift-rank'), value: 'LocalBusiness' },
        ],
        default: 'Organization',
        tooltip: __('Choose Organization for companies, Person for individuals, or Local Business for physical business locations.', 'swift-rank'),
    };

    return (
        <WizardStep
            title={__('Website Information', 'swift-rank')}
            description={__('Provide basic information about your website to establish your site\'s identity in search results.', 'swift-rank')}
            onNext={handleNext}
            onBack={onBack}
            onSkip={onSkip}
            currentStep={currentStep}
            totalSteps={totalSteps}
            isSaving={isSaving}
        >
            <div className="site-info-form">
                {/* Type Selector */}
                <FieldRenderer
                    fieldConfig={typeSelectorField}
                    value={knowledgeGraphType}
                    onChange={handleTypeChange}
                    fields={formData}
                    isPostMetabox={false}
                />

                {/* Schema-specific fields using FieldsBuilder */}
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
        </WizardStep>
    );
};

export default SiteInfoStep;
