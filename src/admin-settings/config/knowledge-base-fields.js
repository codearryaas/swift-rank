import { __ } from '@wordpress/i18n';

/**
 * Field configurations for Knowledge Base Settings tab
 * Top-level toggle and type selector fields
 */

const knowledgeBaseFields = [
    {
        name: 'knowledge_base_enabled',
        label: __('Enable Knowledge Base Schema', 'swift-rank'),
        type: 'toggle',
        default: false,
        tooltip: __('Add Organization or Person schema to your homepage for Google Knowledge Graph.', 'swift-rank'),
    },
    {
        name: 'knowledge_base_type',
        label: __('Schema Type', 'swift-rank'),
        type: 'select',
        options: [
            { label: __('Organization', 'swift-rank'), value: 'Organization' },
            { label: __('Person', 'swift-rank'), value: 'Person' },
            { label: __('Local Business', 'swift-rank'), value: 'LocalBusiness' },
        ],
        default: 'Organization',
        tooltip: __('Choose Organization for companies, Person for individuals, or Local Business for physical business locations.', 'swift-rank'),
    },
];

export default knowledgeBaseFields;
