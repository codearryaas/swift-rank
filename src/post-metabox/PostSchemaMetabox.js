import { useState, useEffect, useCallback } from '@wordpress/element';
import {
	Card,
	CardBody,
	CardHeader,
	Notice,
	Button,
	Modal
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { addAction, removeAction } from '@wordpress/hooks';
import PostMetaboxProNotice from '../components/PostMetaboxProNotice';
import Icon from '../components/Icon';
import FieldsBuilder from '../components/FieldsBuilder';

/**
 * PostSchemaMetabox Component
 *
 * Displays matching schema templates and allows field overrides.
 * Templates are auto-enabled based on conditions - no manual selection needed.
 * Only stores field overrides, not full schema data.
 */
const PostSchemaMetabox = () => {
	const {
		postId,
		matchingTemplates = [],
		savedOverrides = {},
		nonce
	} = window.swiftRankPostMetabox || {};

	// Check if Pro is activated
	const isProActivated = window.swiftRankConfig?.isProActivated || false;

	// Use state to trigger re-render when Pro plugin registers its filters
	const [filtersVersion, setFiltersVersion] = useState(0);

	// Listen for Pro plugin registration
	useEffect(() => {
		const handleProRegistration = () => {
			setFiltersVersion(v => v + 1);
		};

		addAction(
			'swift_rank_pro_loaded',
			'swift-rank/post-metabox',
			handleProRegistration
		);

		// Also check after a short delay in case Pro already loaded
		const timer = setTimeout(() => {
			setFiltersVersion(v => v + 1);
		}, 100);

		return () => {
			clearTimeout(timer);
			removeAction('swift_rank_pro_loaded', 'swift-rank/post-metabox');
		};
	}, []);

	// Normalize savedOverrides keys to strings for consistent lookups.
	const normalizedSavedOverrides = Object.keys(savedOverrides).reduce((acc, key) => {
		acc[String(key)] = savedOverrides[key];
		return acc;
	}, {});

	// Field overrides state - only stores changed values
	// Structure: { templateId: { fieldName: value } }
	const [overrides, setOverrides] = useState(normalizedSavedOverrides);
	const [expandedTemplates, setExpandedTemplates] = useState({});
	const [confirmReset, setConfirmReset] = useState(null); // { type: 'field'|'template', templateId, fieldName? }

	// Save overrides to hidden input for form submission.
	useEffect(() => {
		// Use the hidden input created by PHP.
		const input = document.getElementById('swift-rank-overrides-input');
		if (input) {
			const newValue = JSON.stringify(overrides);
			const currentValue = input.value;

			// Avoid updating if values are effectively the same
			if (currentValue === newValue) {
				return;
			}

			// Handle empty array [] vs empty object {} mismatch
			// PHP sends [] for empty associative array, JS uses {}
			if ((currentValue === '[]' || currentValue === '') && newValue === '{}') {
				return;
			}

			input.value = newValue;
		}
	}, [overrides]);


	/**
	 * Update a field override.
	 */
	const updateFieldOverride = useCallback((templateId, fieldName, value) => {
		// Use string template ID for consistent key format.
		const templateKey = String(templateId);

		setOverrides(prev => {
			const newOverrides = { ...prev };

			// Initialize template overrides if needed.
			if (!newOverrides[templateKey]) {
				newOverrides[templateKey] = {};
			}

			// Store the value (even if empty string - user explicitly set it).
			newOverrides[templateKey][fieldName] = value;
			console.log(newOverrides);

			return newOverrides;
		});
	}, []);

	/**
	 * Toggle template card expanded state.
	 */
	const toggleExpanded = (templateId) => {
		setExpandedTemplates(prev => ({
			...prev,
			[templateId]: !prev[templateId]
		}));
	};

	/**
	 * Remove a single field override silently (no confirmation).
	 * Used when field value is set back to default.
	 */
	const removeOverride = useCallback((templateId, fieldName) => {
		const templateKey = String(templateId);
		setOverrides(prev => {
			const newOverrides = { ...prev };
			if (newOverrides[templateKey]) {
				delete newOverrides[templateKey][fieldName];
				if (Object.keys(newOverrides[templateKey]).length === 0) {
					delete newOverrides[templateKey];
				}
			}
			return newOverrides;
		});
	}, []);

	/**
	 * Reset a single field to template default (with confirmation modal).
	 */
	const resetField = (templateId, fieldName) => {
		removeOverride(templateId, fieldName);
		setConfirmReset(null);
	};

	/**
	 * Reset all fields for a template.
	 */
	const resetTemplate = (templateId) => {
		const templateKey = String(templateId);
		setOverrides(prev => {
			const newOverrides = { ...prev };
			delete newOverrides[templateKey];
			return newOverrides;
		});
		setConfirmReset(null);
	};

	/**
	 * Get override count for a template.
	 */
	const getOverrideCount = (templateId) => {
		const templateKey = String(templateId);
		if (overrides[templateKey]) {
			return Object.keys(overrides[templateKey]).length;
		}
		return 0;
	};

	/**
	 * Render confirmation modal.
	 */
	const renderConfirmModal = () => {
		if (!confirmReset) {
			return null;
		}

		const isTemplateReset = confirmReset.type === 'template';
		const template = matchingTemplates.find(t => t.id === confirmReset.templateId);

		return (
			<Modal
				title={__('Confirm Reset', 'swift-rank')}
				onRequestClose={() => setConfirmReset(null)}
				size="small"
			>
				<p>
					{isTemplateReset
						? __('Are you sure you want to reset all field overrides for this schema template? This will restore all fields to their template defaults.', 'swift-rank')
						: __('Are you sure you want to reset this field to its template default?', 'swift-rank')
					}
				</p>
				<div style={{ display: 'flex', justifyContent: 'flex-end', gap: '8px', marginTop: '16px' }}>
					<Button
						variant="secondary"
						onClick={() => setConfirmReset(null)}
					>
						{__('Cancel', 'swift-rank')}
					</Button>
					<Button
						variant="primary"
						isDestructive
						onClick={() => {
							if (isTemplateReset) {
								resetTemplate(confirmReset.templateId);
							} else {
								resetField(confirmReset.templateId, confirmReset.fieldName);
							}
						}}
					>
						{__('Reset', 'swift-rank')}
					</Button>
				</div>
			</Modal>
		);
	};

	/**
	 * Render fields based on schema type using the shared SchemaField component.
	 */
	const renderFields = (template) => {
		const { id: templateId, schemaType, fields = {} } = template;

		/**
		 * Handle field change for FieldsBuilder
		 */
		const handleFieldChange = (fieldName, value) => {
			updateFieldOverride(templateId, fieldName, value);
		};



		// Get template overrides for this template
		const templateOverrides = overrides[String(templateId)] || {};

		switch (schemaType) {
			case 'Article':
			case 'BlogPosting':
			case 'NewsArticle':
				// Get merged fields with overrides for FieldsBuilder
				const mergedArticleFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedArticleFields[key] = templateOverrides[key];
				});

				return (
					<FieldsBuilder
						schemaType="Article"
						fields={mergedArticleFields}
						onChange={(updatedFields) => {
							Object.keys(updatedFields).forEach((fieldName) => {
								if (updatedFields[fieldName] !== mergedArticleFields[fieldName]) {
									handleFieldChange(fieldName, updatedFields[fieldName]);
								}
							});
						}}
						overrides={templateOverrides}
						onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
						onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
						onResetAll={() => setConfirmReset({ type: 'template', templateId })}
						isPostMetabox={true}
					/>
				);

			case 'Organization':
				// Get merged fields with overrides
				const mergedOrgFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedOrgFields[key] = templateOverrides[key];
				});

				return (
					<FieldsBuilder
						schemaType="Organization"
						fields={mergedOrgFields}
						onChange={(updatedFields) => {
							Object.keys(updatedFields).forEach((fieldName) => {
								if (updatedFields[fieldName] !== mergedOrgFields[fieldName]) {
									handleFieldChange(fieldName, updatedFields[fieldName]);
								}
							});
						}}
						overrides={templateOverrides}
						onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
						onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
						onResetAll={() => setConfirmReset({ type: 'template', templateId })}
						isPostMetabox={true}
					/>
				);

			case 'Person':
				// Get merged fields with overrides
				const mergedPersonFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedPersonFields[key] = templateOverrides[key];
				});

				return (
					<FieldsBuilder
						schemaType="Person"
						fields={mergedPersonFields}
						onChange={(updatedFields) => {
							Object.keys(updatedFields).forEach((fieldName) => {
								if (updatedFields[fieldName] !== mergedPersonFields[fieldName]) {
									handleFieldChange(fieldName, updatedFields[fieldName]);
								}
							});
						}}
						overrides={templateOverrides}
						onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
						onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
						onResetAll={() => setConfirmReset({ type: 'template', templateId })}
						isPostMetabox={true}
					/>
				);

			case 'Product':
				// Get merged fields with overrides
				const mergedProductFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedProductFields[key] = templateOverrides[key];
				});

				return (
					<FieldsBuilder
						schemaType="Product"
						fields={mergedProductFields}
						onChange={(updatedFields) => {
							Object.keys(updatedFields).forEach((fieldName) => {
								if (updatedFields[fieldName] !== mergedProductFields[fieldName]) {
									handleFieldChange(fieldName, updatedFields[fieldName]);
								}
							});
						}}
						overrides={templateOverrides}
						onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
						onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
						onResetAll={() => setConfirmReset({ type: 'template', templateId })}
						isPostMetabox={true}
					/>
				);

			case 'Event':
				// Get merged fields with overrides
				const mergedEventFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedEventFields[key] = templateOverrides[key];
				});

				return (
					<FieldsBuilder
						schemaType="Event"
						fields={mergedEventFields}
						onChange={(updatedFields) => {
							Object.keys(updatedFields).forEach((fieldName) => {
								if (updatedFields[fieldName] !== mergedEventFields[fieldName]) {
									handleFieldChange(fieldName, updatedFields[fieldName]);
								}
							});
						}}
						overrides={templateOverrides}
						onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
						onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
						onResetAll={() => setConfirmReset({ type: 'template', templateId })}
						isPostMetabox={true}
					/>
				);

			case 'VideoObject':
				// Get merged fields with overrides
				const mergedVideoFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedVideoFields[key] = templateOverrides[key];
				});

				return (
					<FieldsBuilder
						schemaType="VideoObject"
						fields={mergedVideoFields}
						onChange={(updatedFields) => {
							Object.keys(updatedFields).forEach((fieldName) => {
								if (updatedFields[fieldName] !== mergedVideoFields[fieldName]) {
									handleFieldChange(fieldName, updatedFields[fieldName]);
								}
							});
						}}
						overrides={templateOverrides}
						onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
						onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
						onResetAll={() => setConfirmReset({ type: 'template', templateId })}
						isPostMetabox={true}
					/>
				);

			case 'JobPosting':
				// Get merged fields with overrides
				const mergedJobFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedJobFields[key] = templateOverrides[key];
				});

				return (
					<FieldsBuilder
						schemaType="JobPosting"
						fields={mergedJobFields}
						onChange={(updatedFields) => {
							Object.keys(updatedFields).forEach((fieldName) => {
								if (updatedFields[fieldName] !== mergedJobFields[fieldName]) {
									handleFieldChange(fieldName, updatedFields[fieldName]);
								}
							});
						}}
						overrides={templateOverrides}
						onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
						onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
						onResetAll={() => setConfirmReset({ type: 'template', templateId })}
						isPostMetabox={true}
					/>
				);

			case 'FAQPage':
				// Get merged fields with overrides
				const mergedFaqFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedFaqFields[key] = templateOverrides[key];
				});

				return (
					<FieldsBuilder
						schemaType="FAQPage"
						fields={mergedFaqFields}
						onChange={(updatedFields) => {
							Object.keys(updatedFields).forEach((fieldName) => {
								if (updatedFields[fieldName] !== mergedFaqFields[fieldName]) {
									handleFieldChange(fieldName, updatedFields[fieldName]);
								}
							});
						}}
						overrides={templateOverrides}
						onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
						onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
						onResetAll={() => setConfirmReset({ type: 'template', templateId })}
						isPostMetabox={true}
					/>
				);

			case 'HowTo':
				// Get merged fields with overrides
				const mergedHowToFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedHowToFields[key] = templateOverrides[key];
				});

				return (
					<FieldsBuilder
						schemaType="HowTo"
						fields={mergedHowToFields}
						onChange={(updatedFields) => {
							Object.keys(updatedFields).forEach((fieldName) => {
								if (updatedFields[fieldName] !== mergedHowToFields[fieldName]) {
									handleFieldChange(fieldName, updatedFields[fieldName]);
								}
							});
						}}
						overrides={templateOverrides}
						onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
						onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
						onResetAll={() => setConfirmReset({ type: 'template', templateId })}
						isPostMetabox={true}
					/>
				);

			default:
				// For custom or unknown types, try to use FieldsBuilder first
				// Get merged fields with overrides
				const mergedCustomFields = { ...fields };
				Object.keys(templateOverrides).forEach(key => {
					mergedCustomFields[key] = templateOverrides[key];
				});

				// Try FieldsBuilder for custom types (will work if PHP registered the type with fields)
				if (FieldsBuilder) {
					return (
						<FieldsBuilder
							schemaType={schemaType}
							fields={mergedCustomFields}
							onChange={(updatedFields) => {
								Object.keys(updatedFields).forEach((fieldName) => {
									if (updatedFields[fieldName] !== mergedCustomFields[fieldName]) {
										handleFieldChange(fieldName, updatedFields[fieldName]);
									}
								});
							}}
							overrides={templateOverrides}
							onResetField={(fieldName) => setConfirmReset({ type: 'field', templateId, fieldName })}
							onRemoveOverride={(fieldName) => removeOverride(templateId, fieldName)}
							onResetAll={() => setConfirmReset({ type: 'template', templateId })}
							isPostMetabox={true}
						/>
					);
				}

				// Absolute fallback: FieldsBuilder not available (shouldn't happen)
				return (
					<Notice status="warning" isDismissible={false}>
						{__('Unable to render fields for this schema type. Please refresh the page.', 'swift-rank')}
					</Notice>
				);
		}
	};

	// If no matching templates, show message.
	if (matchingTemplates.length === 0) {
		const isUserProfile = window.swiftRankPostMetabox?.context === 'user-profile';
		const message = isUserProfile
			? __('No schema templates match this user profile. Create a template with conditions that include author archives.', 'swift-rank')
			: __('No schema templates match this post. Create a template with conditions that include this post type.', 'swift-rank');

		return (
			<div className="swift-rank-post-metabox">
				{/* Show Pro upgrade notice when Pro is not activated */}
				{!isProActivated && <PostMetaboxProNotice />}

				<Notice status="info" isDismissible={false}>
					<div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '12px' }}>
						<span>{message}</span>
						<div style={{ display: 'flex', gap: '8px', flexShrink: 0 }}>
							<Button
								variant="secondary"
								size="small"
								href={swiftRankData.templatesUrl}
							>
								{__('Manage Templates', 'swift-rank')}
							</Button>
							<Button
								variant="primary"
								size="small"
								href={swiftRankData.newTemplateUrl}
							>
								{__('Add Template', 'swift-rank')}
							</Button>
						</div>
					</div>
				</Notice>
			</div>
		);
	}

	return (
		<div className="swift-rank-post-metabox">
			{/* Show Pro upgrade notice when Pro is not activated */}
			{!isProActivated && <PostMetaboxProNotice />}

			{renderConfirmModal()}

			<Notice status="info" isDismissible={false} className="auto-enabled-notice">
				{__('The following schema templates are automatically applied to this post based on template conditions. You can override individual fields below.', 'swift-rank')}
			</Notice>

			<div className="templates-list">
				{matchingTemplates.map((template) => {
					const overrideCount = getOverrideCount(template.id);

					// Get schema type info with icon
					const schemaTypeInfo = window.swiftRankPostMetabox?.schemaTypes?.find(
						type => type.value === template.schemaType
					);

					const schemaIcon = schemaTypeInfo?.icon || '';

					return (
						<Card key={template.id} className="template-card">
							<CardHeader className="template-header">
								<div
									className="header-content"
									onClick={() => toggleExpanded(template.id)}
									style={{ cursor: 'pointer', flex: 1 }}
								>
									<span className="schema-type-badge" style={{ display: 'inline-flex', alignItems: 'center', gap: '6px' }}>
										{schemaIcon && (
											<Icon name={schemaIcon} size={14} />
										)}
										{template.schemaType}
									</span>
									<span className="template-title">{template.title}</span>
								</div>
								<div className="header-actions">
									<a
										href={`post.php?post=${template.id}&action=edit`}
										target="_blank"
										rel="noopener noreferrer"
										className="edit-template-link"
										style={{ marginRight: '8px', textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: '4px' }}
										onClick={(e) => e.stopPropagation()}
									>
										<Icon name="pencil" size={16} />
										<span style={{ fontSize: '12px' }}>
											{__('Edit Template', 'swift-rank')}
										</span>
									</a>
									<span
										onClick={() => toggleExpanded(template.id)}
										style={{ cursor: 'pointer', display: 'inline-flex', alignItems: 'center' }}
									>
										<Icon
											name={expandedTemplates[template.id] ? 'chevron-up' : 'chevron-down'}
											size={16}
											color="currentColor"
										/>
									</span>
								</div>
							</CardHeader>
							{expandedTemplates[template.id] && (
								<CardBody className="template-fields">
									{renderFields(template)}
								</CardBody>
							)}
						</Card>
					);
				})}
			</div>
		</div>
	);
};

export default PostSchemaMetabox;
