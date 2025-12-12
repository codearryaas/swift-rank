import { __ } from '@wordpress/i18n';
import FieldRenderer from '../../components/FieldRenderer';
import socialProfilesFields from '../config/social-profiles-fields';

const SocialProfilesTab = ({ settings, updateSetting }) => {
    const isProActive = typeof window.swiftRankSettings !== 'undefined' && window.swiftRankSettings.isProActive;

    const handleFieldChange = (fieldName, value) => {
        updateSetting(fieldName, value);
    };

    return (
        <div className="swift-rank-social-profiles-settings">
            <h2>{__('Social Profiles', 'swift-rank')}</h2>
            <p className="description">
                {__('Add your social media profile URLs to improve your Knowledge Graph visibility and help search engines understand your online presence.', 'swift-rank')}
            </p>

            <div className="schema-fields-container" style={{ marginTop: '20px' }}>
                {socialProfilesFields.map((fieldConfig) => {
                    return (
                        <FieldRenderer
                            key={fieldConfig.name}
                            fieldConfig={fieldConfig}
                            value={settings[fieldConfig.name]}
                            onChange={(value) => handleFieldChange(fieldConfig.name, value)}
                            fields={settings}
                            isOverridden={false}
                            onReset={null}
                            isPostMetabox={false}
                            allFieldConfigs={socialProfilesFields}
                        />
                    );
                })}
            </div>
        </div>
    );
};

export default SocialProfilesTab;
