import { __ } from '@wordpress/i18n';
import Icon from '../../components/Icon';

const HelpTab = () => {
    const documentationLinks = [
        { title: __('Getting Started Guide', 'swift-rank'), url: '#getting-started' },
        { title: __('Creating Schema Templates', 'swift-rank'), url: '#schema-templates' },
        { title: __('Using Dynamic Variables', 'swift-rank'), url: '#dynamic-variables' },
        { title: __('Setting Display Conditions', 'swift-rank'), url: '#display-conditions' },
        { title: __('Testing Your Schema', 'swift-rank'), url: '#testing-schema' },
        { title: __('Article Schema Setup', 'swift-rank'), url: '#article-schema' },
        { title: __('Organization Schema Setup', 'swift-rank'), url: '#organization-schema' },
        { title: __('FAQ Schema Setup', 'swift-rank'), url: '#faq-schema' },
    ];

    return (
        <div className="swift-rank-help-tab">
            <h2 style={{ marginTop: 0 }}>{__('Help & Support', 'swift-rank')}</h2>
            <p className="description" style={{ marginBottom: '24px' }}>
                {__('Find documentation, guides, and support resources to help you get the most out of Swift Rank.', 'swift-rank')}
            </p>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(400px, 1fr))', gap: '20px' }}>
                {/* Documentation Card */}
                <div className="help-card" style={{
                    background: '#fff',
                    border: '1px solid #c3c4c7',
                    borderRadius: '4px',
                    padding: '24px',
                    boxShadow: '0 1px 1px rgba(0,0,0,.04)'
                }}>
                    <div style={{ display: 'flex', alignItems: 'center', marginBottom: '16px' }}>
                        <Icon name="book-open" size={24} style={{ marginRight: '12px' }} />
                        <h3 style={{ margin: 0, fontSize: '16px', fontWeight: 600 }}>
                            {__('Documentation', 'swift-rank')}
                        </h3>
                    </div>

                    <p style={{ marginBottom: '20px', color: '#646970', lineHeight: '1.6' }}>
                        {__('Browse our comprehensive documentation to learn how to configure and use Swift Rank effectively.', 'swift-rank')}
                    </p>

                    <div style={{ marginBottom: '20px' }}>
                        <h4 style={{ fontSize: '13px', fontWeight: 600, marginBottom: '12px', color: '#1d2327' }}>
                            {__('Popular Topics', 'swift-rank')}
                        </h4>
                        <ul style={{
                            margin: 0,
                            padding: 0,
                            listStyle: 'none'
                        }}>
                            {documentationLinks.map((link, index) => (
                                <li key={index} style={{ marginBottom: '8px' }}>
                                    <a
                                        href={link.url}
                                        style={{
                                            color: '#2271b1',
                                            textDecoration: 'none',
                                            fontSize: '13px',
                                            display: 'flex',
                                            alignItems: 'center'
                                        }}
                                        onMouseOver={(e) => e.target.style.textDecoration = 'underline'}
                                        onMouseOut={(e) => e.target.style.textDecoration = 'none'}
                                    >
                                        <Icon name="arrow-right" size={16} style={{ marginRight: '6px' }} />
                                        {link.title}
                                    </a>
                                </li>
                            ))}
                        </ul>
                    </div>

                    <a
                        href="https://toolpress.net/docs"
                        target="_blank"
                        rel="noopener noreferrer"
                        className="button button-primary"
                        style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            textDecoration: 'none',
                            gap: '6px'
                        }}
                    >
                        <Icon name="external-link" size={16} />
                        {__('Visit Documentation', 'swift-rank')}
                    </a>
                </div>

                {/* Support Card */}
                <div className="help-card" style={{
                    background: '#fff',
                    border: '1px solid #c3c4c7',
                    borderRadius: '4px',
                    padding: '24px',
                    boxShadow: '0 1px 1px rgba(0,0,0,.04)'
                }}>
                    <div style={{ display: 'flex', alignItems: 'center', marginBottom: '16px' }}>
                        <Icon name="help-circle" size={24} style={{ marginRight: '12px' }} />
                        <h3 style={{ margin: 0, fontSize: '16px', fontWeight: 600 }}>
                            {__('Support', 'swift-rank')}
                        </h3>
                    </div>

                    <p style={{ marginBottom: '20px', color: '#646970', lineHeight: '1.6' }}>
                        {__('Need help? Our support team is here to assist you with any questions or issues you may have.', 'swift-rank')}
                    </p>

                    <div style={{
                        background: '#f6f7f7',
                        border: '1px solid #dcdcde',
                        borderRadius: '4px',
                        padding: '16px',
                        marginBottom: '20px'
                    }}>
                        <h4 style={{ fontSize: '13px', fontWeight: 600, marginTop: 0, marginBottom: '8px', color: '#1d2327' }}>
                            {__('Response Time', 'swift-rank')}
                        </h4>
                        <p style={{ margin: 0, fontSize: '13px', color: '#646970', lineHeight: '1.6' }}>
                            {__('We typically respond to support requests within 24-48 hours during business days.', 'swift-rank')}
                        </p>
                    </div>

                    <div style={{
                        background: '#f6f7f7',
                        border: '1px solid #dcdcde',
                        borderRadius: '4px',
                        padding: '16px',
                        marginBottom: '20px'
                    }}>
                        <h4 style={{ fontSize: '13px', fontWeight: 600, marginTop: 0, marginBottom: '8px', color: '#1d2327' }}>
                            {__('Before You Contact Support', 'swift-rank')}
                        </h4>
                        <ul style={{ margin: 0, paddingLeft: '20px', fontSize: '13px', color: '#646970', lineHeight: '1.8' }}>
                            <li>{__('Check the documentation for answers', 'swift-rank')}</li>
                            <li>{__('Clear your browser cache and try again', 'swift-rank')}</li>
                            <li>{__('Disable other plugins to check for conflicts', 'swift-rank')}</li>
                        </ul>
                    </div>

                    <a
                        href="https://wordpress.org/support/plugin/swift-rank/"
                        target="_blank"
                        rel="noopener noreferrer"
                        className="button button-primary"
                        style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            textDecoration: 'none',
                            gap: '6px'
                        }}
                    >
                        <Icon name="message-circle" size={16} />
                        {__('Get Support', 'swift-rank')}
                    </a>
                </div>
            </div>
        </div>
    );
};

export default HelpTab;
