import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ToggleControl } from '@wordpress/components';
import WizardStep from '../components/WizardStep';

const ContentTypesStep = ({ onNext, onBack, onSkip, currentStep, totalSteps, isSaving, initialData }) => {
    const isWooCommerceActive = typeof window.swiftRankWizardSettings !== 'undefined' && window.swiftRankWizardSettings.isWooCommerceActive;

    const [formData, setFormData] = useState({
        post_enabled: initialData.post_enabled !== undefined ? initialData.post_enabled : true,
        post_type: initialData.post_type || 'Article',
        page_enabled: initialData.page_enabled !== undefined ? initialData.page_enabled : true,
        search_enabled: initialData.search_enabled !== undefined ? initialData.search_enabled : true,
        woocommerce_enabled: initialData.woocommerce_enabled !== undefined ? initialData.woocommerce_enabled : true,
    });

    const handleChange = (field, value) => {
        setFormData({ ...formData, [field]: value });
    };

    const handleNext = () => {
        onNext(formData);
    };

    const postTypeOptions = [
        { value: 'Article', label: __('Article', 'swift-rank'), description: __('General article content', 'swift-rank') },
        { value: 'BlogPosting', label: __('BlogPosting', 'swift-rank'), description: __('Blog posts and informal articles', 'swift-rank') },
        { value: 'NewsArticle', label: __('NewsArticle', 'swift-rank'), description: __('News and current events', 'swift-rank') },
        { value: 'ScholarlyArticle', label: __('ScholarlyArticle', 'swift-rank'), description: __('Academic and research articles', 'swift-rank') },
        { value: 'TechArticle', label: __('TechArticle', 'swift-rank'), description: __('Technical and how-to articles', 'swift-rank') },
    ];

    return (
        <WizardStep
            title={__('Select Default Schema for Content Types', 'swift-rank')}
            description={__('Choose which schema types to automatically generate for your content.', 'swift-rank')}
            onNext={handleNext}
            onBack={onBack}
            onSkip={onSkip}
            currentStep={currentStep}
            totalSteps={totalSteps}
            isSaving={isSaving}
        >
            <div className="content-types-form">
                <table className="content-types-table">
                    <thead>
                        <tr>
                            <th>{__('Content Type', 'swift-rank')}</th>
                            <th>{__('Schema Type', 'swift-rank')}</th>
                            <th>{__('Enable', 'swift-rank')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {/* Posts */}
                        <tr>
                            <td>
                                <strong>{__('Posts', 'swift-rank')}</strong>
                                <p className="table-description">{__('Blog posts and articles', 'swift-rank')}</p>
                            </td>
                            <td>
                                <select
                                    className="schema-type-select"
                                    value={formData.post_type}
                                    onChange={(e) => handleChange('post_type', e.target.value)}
                                    disabled={!formData.post_enabled}
                                >
                                    {postTypeOptions.map(option => (
                                        <option key={option.value} value={option.value}>
                                            {option.label} - {option.description}
                                        </option>
                                    ))}
                                </select>
                            </td>
                            <td>
                                <ToggleControl
                                    checked={formData.post_enabled}
                                    onChange={(value) => handleChange('post_enabled', value)}
                                />
                            </td>
                        </tr>

                        {/* Pages */}
                        <tr>
                            <td>
                                <strong>{__('Pages', 'swift-rank')}</strong>
                                <p className="table-description">{__('Static pages', 'swift-rank')}</p>
                            </td>
                            <td>
                                <span className="schema-type-fixed">WebPage</span>
                            </td>
                            <td>
                                <ToggleControl
                                    checked={formData.page_enabled}
                                    onChange={(value) => handleChange('page_enabled', value)}
                                />
                            </td>
                        </tr>

                        {/* Search Results */}
                        <tr>
                            <td>
                                <strong>{__('Search Page', 'swift-rank')}</strong>
                                <p className="table-description">{__('Search results pages', 'swift-rank')}</p>
                            </td>
                            <td>
                                <span className="schema-type-fixed">SearchResultsPage</span>
                            </td>
                            <td>
                                <ToggleControl
                                    checked={formData.search_enabled}
                                    onChange={(value) => handleChange('search_enabled', value)}
                                />
                            </td>
                        </tr>

                        {/* WooCommerce Products */}
                        {isWooCommerceActive && (
                            <tr>
                                <td>
                                    <strong>{__('Products', 'swift-rank')}</strong>
                                    <p className="table-description">{__('WooCommerce products', 'swift-rank')}</p>
                                </td>
                                <td>
                                    <span className="schema-type-fixed">Product</span>
                                </td>
                                <td>
                                    <ToggleControl
                                        checked={formData.woocommerce_enabled}
                                        onChange={(value) => handleChange('woocommerce_enabled', value)}
                                    />
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>

                <div className="form-note">
                    <span className="dashicons dashicons-info"></span>
                    <p>{__('These settings will be applied automatically. You can override them for specific posts using schema templates.', 'swift-rank')}</p>
                </div>
            </div>
        </WizardStep>
    );
};

export default ContentTypesStep;
