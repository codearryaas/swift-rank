import { useState, useEffect } from '@wordpress/element';
import { Button, CheckboxControl, Notice } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

const ImportExportTab = () => {
    const [templates, setTemplates] = useState([]);
    const [selectedTemplates, setSelectedTemplates] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [isExporting, setIsExporting] = useState(false);
    const [isImporting, setIsImporting] = useState(false);
    const [importFile, setImportFile] = useState(null);
    const [notice, setNotice] = useState(null);

    useEffect(() => {
        fetchTemplates();
    }, []);

    const fetchTemplates = () => {
        setIsLoading(true);
        apiFetch({ path: '/swift-rank/v1/templates' })
            .then((data) => {
                setTemplates(data);
                setIsLoading(false);
            })
            .catch((error) => {
                console.error('Error fetching templates:', error);
                setNotice({ type: 'error', message: __('Failed to load templates.', 'swift-rank') });
                setIsLoading(false);
            });
    };

    const handleSelectAll = (checked) => {
        if (checked) {
            setSelectedTemplates(templates.map(t => t.id));
        } else {
            setSelectedTemplates([]);
        }
    };

    const handleTemplateToggle = (templateId) => {
        if (selectedTemplates.includes(templateId)) {
            setSelectedTemplates(selectedTemplates.filter(id => id !== templateId));
        } else {
            setSelectedTemplates([...selectedTemplates, templateId]);
        }
    };

    const handleExport = () => {
        if (selectedTemplates.length === 0) {
            setNotice({ type: 'error', message: __('Please select at least one template to export.', 'swift-rank') });
            return;
        }

        setIsExporting(true);
        apiFetch({
            path: '/swift-rank/v1/export',
            method: 'POST',
            data: { template_ids: selectedTemplates },
        })
            .then((response) => {
                // Create download link
                const blob = new Blob([JSON.stringify(response, null, 2)], { type: 'application/json' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `schema-templates-${new Date().toISOString().slice(0, 10)}.json`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

                setNotice({ type: 'success', message: __('Templates exported successfully.', 'swift-rank') });
                setIsExporting(false);
            })
            .catch((error) => {
                setNotice({ type: 'error', message: error.message });
                setIsExporting(false);
            });
    };

    const handleFileChange = (e) => {
        setImportFile(e.target.files[0]);
    };

    const handleImport = () => {
        if (!importFile) {
            setNotice({ type: 'error', message: __('Please select a file to import.', 'swift-rank') });
            return;
        }

        setIsImporting(true);
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const importData = JSON.parse(e.target.result);

                apiFetch({
                    path: '/swift-rank/v1/import',
                    method: 'POST',
                    data: importData,
                })
                    .then((response) => {
                        setNotice({
                            type: 'success',
                            message: __(`Successfully imported ${response.imported_count} template(s).`, 'swift-rank')
                        });
                        setIsImporting(false);
                        setImportFile(null);
                        fetchTemplates();
                    })
                    .catch((error) => {
                        setNotice({ type: 'error', message: error.message });
                        setIsImporting(false);
                    });
            } catch (error) {
                setNotice({ type: 'error', message: __('Invalid JSON file.', 'swift-rank') });
                setIsImporting(false);
            }
        };
        reader.readAsText(importFile);
    };

    return (
        <div className="swift-rank-import-export">
            {notice && (
                <Notice status={notice.type} onRemove={() => setNotice(null)} style={{ marginBottom: '20px' }}>
                    {notice.message}
                </Notice>
            )}

            <div className="swift-rank-import-export-grid">
                {/* Export Box */}
                <div className="swift-rank-box">
                    <h2>{__('Export Schema Templates', 'swift-rank')}</h2>
                    <p className="description">{__('Select schema templates to export as JSON. You can then import them on another site.', 'swift-rank')}</p>

                    {isLoading ? (
                        <p>{__('Loading templates...', 'swift-rank')}</p>
                    ) : templates.length > 0 ? (
                        <>
                            <table className="wp-list-table widefat fixed striped" style={{ marginTop: '15px' }}>
                                <thead>
                                    <tr>
                                        <th style={{ width: '40px' }}>
                                            <CheckboxControl
                                                checked={selectedTemplates.length === templates.length && templates.length > 0}
                                                onChange={handleSelectAll}
                                            />
                                        </th>
                                        <th>{__('Template Name', 'swift-rank')}</th>
                                        <th>{__('Last Modified', 'swift-rank')}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {templates.map((template) => (
                                        <tr key={template.id}>
                                            <td>
                                                <CheckboxControl
                                                    checked={selectedTemplates.includes(template.id)}
                                                    onChange={() => handleTemplateToggle(template.id)}
                                                />
                                            </td>
                                            <td><strong>{template.title.rendered}</strong></td>
                                            <td>{new Date(template.modified).toLocaleDateString()}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                            <p style={{ marginTop: '15px' }}>
                                <Button isPrimary onClick={handleExport} isBusy={isExporting} disabled={selectedTemplates.length === 0}>
                                    {__('Export Selected Templates', 'swift-rank')}
                                </Button>
                            </p>
                        </>
                    ) : (
                        <p className="description">{__('No schema templates found.', 'swift-rank')}</p>
                    )}
                </div>

                {/* Import Box */}
                <div className="swift-rank-box">
                    <h2>{__('Import Schema Templates', 'swift-rank')}</h2>
                    <p className="description">{__('Upload a JSON file containing schema templates to import them into your site.', 'swift-rank')}</p>

                    <div style={{ marginTop: '15px' }}>
                        <label htmlFor="import_file" style={{ display: 'block', fontWeight: '600', marginBottom: '8px' }}>
                            {__('Select JSON File', 'swift-rank')}
                        </label>
                        <input
                            type="file"
                            id="import_file"
                            accept=".json"
                            onChange={handleFileChange}
                            style={{ marginBottom: '8px' }}
                        />
                        <p className="description">{__('Choose a JSON file exported from Swift Rank.', 'swift-rank')}</p>
                    </div>

                    <p style={{ marginTop: '15px' }}>
                        <Button isPrimary onClick={handleImport} isBusy={isImporting} disabled={!importFile}>
                            {__('Import Templates', 'swift-rank')}
                        </Button>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default ImportExportTab;
