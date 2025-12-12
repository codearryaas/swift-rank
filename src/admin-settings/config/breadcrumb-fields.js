import { __ } from '@wordpress/i18n';

/**
 * Field configurations for Breadcrumb Settings tab
 * These fields will be rendered using FieldsBuilder component
 */

const breadcrumbFields = [
    {
        name: 'breadcrumb_enabled',
        label: __('Enable Breadcrumb Schema', 'swift-rank'),
        type: 'toggle',
        default: false,
        tooltip: __('Add BreadcrumbList schema to pages for improved navigation in search results.', 'swift-rank'),
    },
    {
        name: 'breadcrumb_show_home',
        label: __('Show Home Link', 'swift-rank'),
        type: 'toggle',
        default: true,
        tooltip: __('Include a link to the homepage at the start of breadcrumbs.', 'swift-rank'),
        dependsOn: 'breadcrumb_enabled',
        showWhen: true,
    },
    {
        name: 'breadcrumb_home_text',
        label: __('Home Text', 'swift-rank'),
        type: 'text',
        default: __('Home', 'swift-rank'),
        placeholder: __('Home', 'swift-rank'),
        tooltip: __('The text to display for the home link in breadcrumbs. Default: "Home"', 'swift-rank'),
        dependsOn: 'breadcrumb_enabled',
        showWhen: true,
    },
    {
        name: 'breadcrumb_separator',
        label: __('Separator', 'swift-rank'),
        type: 'text',
        default: '›',
        tooltip: __('The character used to separate breadcrumb items (for display purposes). Examples: › / > / »', 'swift-rank'),
        dependsOn: 'breadcrumb_enabled',
        showWhen: true,
    },
];

export default breadcrumbFields;
