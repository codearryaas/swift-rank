import { useState, useEffect } from '@wordpress/element';
import { Button, Spinner, SnackbarList } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { applyFilters } from '@wordpress/hooks';

import GeneralTab from './components/GeneralTab';
import KnowledgeGraphTab from './components/KnowledgeGraphTab';
import SocialProfilesTab from './components/SocialProfilesTab';
import BreadcrumbTab from './components/BreadcrumbTab';
import ImportExportTab from './components/ImportExportTab';
import UpgradeTab from './components/UpgradeTab';
import MarketplaceTab from './components/MarketplaceTab';
import Icon from '../components/Icon';
import ProSidebarNotice from '../components/ProSidebarNotice';

const SettingsApp = () => {
    const [settings, setSettings] = useState({});
    const [isLoading, setIsLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);
    const [notices, setNotices] = useState([]);

    // Get initial tab from URL hash or default to 'general'
    const getTabFromHash = () => {
        const hash = window.location.hash.replace('#', '');
        return hash || 'general';
    };

    const [activeTab, setActiveTab] = useState(getTabFromHash());

    const isProActive = typeof window.swiftRankSettings !== 'undefined' && window.swiftRankSettings.isProActive;

    useEffect(() => {
        apiFetch({ path: '/wp/v2/settings' }).then((response) => {
            setSettings(response.swift_rank_settings || {});
            setIsLoading(false);
        });

        // Listen for hash changes (browser back/forward)
        const handleHashChange = () => {
            const newTab = getTabFromHash();
            setActiveTab(newTab);
        };

        window.addEventListener('hashchange', handleHashChange);

        return () => {
            window.removeEventListener('hashchange', handleHashChange);
        };
    }, []);

    // Update URL hash when tab changes
    const handleTabChange = (tabName) => {
        setActiveTab(tabName);
        window.location.hash = tabName;
    };

    const updateSetting = (key, value) => {
        setSettings((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    const saveSettings = () => {
        setIsSaving(true);

        apiFetch({
            path: '/wp/v2/settings',
            method: 'POST',
            data: {
                swift_rank_settings: settings
            },
        })
            .then(() => {
                setNotices([
                    ...notices,
                    {
                        id: 'save-success',
                        content: __('Settings saved.', 'swift-rank'),
                        status: 'success',
                    },
                ]);
                setIsSaving(false);
                // Auto-remove success notice after 3 seconds
                setTimeout(() => {
                    removeNotice('save-success');
                }, 3000);
            })
            .catch((error) => {
                console.error('Save error:', error);
                setNotices([
                    ...notices,
                    {
                        id: 'save-error',
                        content: error.message || __('Failed to save settings.', 'swift-rank'),
                        status: 'error',
                    },
                ]);
                setIsSaving(false);
            });
    };

    const removeNotice = (id) => {
        setNotices(notices.filter((notice) => notice.id !== id));
    };

    if (isLoading) {
        return (
            <div className="swift-rank-settings-loading">
                <Spinner />
            </div>
        );
    }

    const defaultTabs = [
        {
            name: 'general',
            title: __('General', 'swift-rank'),
            component: GeneralTab,
        },
        {
            name: 'knowledge_graph',
            title: __('Knowledge Graph', 'swift-rank'),
            component: KnowledgeGraphTab,
        },
        {
            name: 'social_profiles',
            title: __('Social Profiles', 'swift-rank'),
            component: SocialProfilesTab,
        },
        {
            name: 'breadcrumb',
            title: __('Breadcrumb', 'swift-rank'),
            component: BreadcrumbTab,
        },
        {
            name: 'import_export',
            title: __('Import/Export', 'swift-rank'),
            component: ImportExportTab,
        },
        {
            name: 'marketplace',
            title: __('Marketplace', 'swift-rank'),
            component: MarketplaceTab,
        },
    ];

    // Add upgrade tab only if Pro is not active
    if (!isProActive) {
        defaultTabs.push({
            name: 'upgrade',
            title: __('Upgrade to Pro', 'swift-rank'),
            component: UpgradeTab,
            isUpgrade: true,
        });
    }

    const tabs = applyFilters('swift_rank_settings_tabs', defaultTabs);

    const CurrentTabComponent = tabs.find(tab => tab.name === activeTab)?.component;

    // Check if current tab needs save button (not upgrade or import_export or marketplace or social_profiles)
    const showSaveButton = activeTab !== 'upgrade' && activeTab !== 'import_export' && activeTab !== 'marketplace';

    return (
        <div className="wrap swift-rank-admin">
            <h1>{__('Swift Rank', 'swift-rank')}</h1>

            <div className="swift-rank-settings-header">
                <p>{__('Configure Schema.org structured data settings for your WordPress site.', 'swift-rank')}</p>
            </div>

            <h2 className="nav-tab-wrapper">
                {tabs.map((tab) => (
                    <a
                        key={tab.name}
                        href={`#${tab.name}`}
                        className={`nav-tab ${activeTab === tab.name ? 'nav-tab-active' : ''} ${tab.isUpgrade ? 'nav-tab-upgrade' : ''}`}
                        onClick={(e) => {
                            e.preventDefault();
                            handleTabChange(tab.name);
                        }}
                    >
                        {tab.isUpgrade && (
                            <Icon name="star" size={14} style={{ marginRight: '4px' }} />
                        )}
                        {tab.title}
                    </a>
                ))}
            </h2>

            <div className="swift-rank-settings-container">
                <div className="swift-rank-settings-main">
                    <div className="swift-rank-tab-content">
                        {CurrentTabComponent && (
                            <CurrentTabComponent
                                settings={settings}
                                updateSetting={updateSetting}
                            />
                        )}

                        {showSaveButton && (
                            <p className="submit">
                                <Button
                                    isPrimary
                                    isBusy={isSaving}
                                    onClick={saveSettings}
                                    className="button button-primary"
                                >
                                    {__('Save Changes', 'swift-rank')}
                                </Button>
                            </p>
                        )}
                    </div>
                </div>

                <div className="swift-rank-settings-sidebar">
                    {!isProActive && (
                        <ProSidebarNotice />
                    )}

                    <div className="swift-rank-sidebar-box">
                        <div className="box-icon">
                            <Icon name="book-open" size={24} />
                        </div>
                        <h3>{__('Documentation', 'swift-rank')}</h3>
                        <p>{__('Learn how to get the most out of Swift Rank.', 'swift-rank')}</p>
                        <a
                            href="https://toolpress.net/docs-category/swift-rank/"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="button"
                        >
                            <Icon name="external-link" size={16} />
                            {__('View Docs', 'swift-rank')}
                        </a>
                    </div>

                    <div className="swift-rank-sidebar-box">
                        <div className="box-icon">
                            <Icon name="help-circle" size={24} />
                        </div>
                        <h3>{__('Support', 'swift-rank')}</h3>
                        <p>{__('Need help? Get support from our team.', 'swift-rank')}</p>
                        <a
                            href="https://toolpress.net/support"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="button"
                        >
                            <Icon name="external-link" size={16} />
                            {__('Get Support', 'swift-rank')}
                        </a>
                    </div>
                </div>
            </div>

            <SnackbarList notices={notices} onRemove={removeNotice} />
        </div>
    );
};

export default SettingsApp;
