/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	RichText
} from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	Button
} from '@wordpress/components';
import { plus, trash } from '@wordpress/icons';
import Icon from '../../components/Icon';

/**
 * Internal dependencies
 */
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { faqItems, enableSchema } = attributes;
	const blockProps = useBlockProps({
		className: 'swift-rank-faq-block',
	});

	// Check if we have empty FAQ items (for schema validation warning)
	const hasEmptyItems = faqItems.some(item => !item.question || !item.answer || !item.question.trim() || !item.answer.trim());

	const addFAQItem = () => {
		const newFaqItems = [...faqItems, { question: '', answer: '' }];
		setAttributes({ faqItems: newFaqItems });
	};

	const removeFAQItem = (index) => {
		const newFaqItems = faqItems.filter((item, i) => i !== index);
		setAttributes({ faqItems: newFaqItems });
	};

	const updateFAQItem = (index, field, value) => {
		const newFaqItems = faqItems.map((item, i) => {
			if (i === index) {
				return { ...item, [field]: value };
			}
			return item;
		});
		setAttributes({ faqItems: newFaqItems });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('FAQ Settings', 'swift-rank')} initialOpen={true}>
					<ToggleControl
						label={__('Enable FAQ Schema', 'swift-rank')}
						help={__(
							'When enabled, this block will output FAQ schema markup for better search engine visibility.',
							'swift-rank'
						)}
						checked={enableSchema}
						onChange={(value) => setAttributes({ enableSchema: value })}
					/>
					{enableSchema && hasEmptyItems && (
						<p style={{
							background: '#fcf0cd',
							border: '1px solid #dba617',
							borderRadius: '4px',
							padding: '8px 12px',
							fontSize: '12px',
							margin: '12px 0'
						}}>
							⚠️ {__('Please fill in all questions and answers for proper schema markup.', 'swift-rank')}
						</p>
					)}
					<p className="components-base-control__help" style={{ marginTop: '12px' }}>
						{__('Total FAQ items: ', 'swift-rank')}
						<strong>{faqItems.length}</strong>
					</p>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="swift-rank-faq-header">
					<h3>{__('FAQ', 'swift-rank')}</h3>
					{enableSchema && (
						<span className="schema-badge">
							<Icon name="code" size={16} />
							{__('Schema Enabled', 'swift-rank')}
						</span>
					)}
				</div>

				<div className="swift-rank-faq-items">
					{faqItems.map((item, index) => (
						<div key={index} className="swift-rank-faq-item">
							<div className="faq-item-header">
								<span className="faq-item-number">{index + 1}</span>
								{faqItems.length > 1 && (
									<Button
										icon={trash}
										label={__('Remove FAQ Item', 'swift-rank')}
										onClick={() => removeFAQItem(index)}
										className="faq-item-remove"
										isDestructive
									/>
								)}
							</div>

							<div className="faq-item-content">
								<RichText
									tagName="h4"
									className="faq-question"
									placeholder={__('Enter question...', 'swift-rank')}
									value={item.question}
									onChange={(value) => updateFAQItem(index, 'question', value)}
									allowedFormats={['core/bold', 'core/italic']}
								/>

								<RichText
									tagName="div"
									className="faq-answer"
									placeholder={__('Enter answer...', 'swift-rank')}
									value={item.answer}
									onChange={(value) => updateFAQItem(index, 'answer', value)}
									allowedFormats={[
										'core/bold',
										'core/italic',
										'core/link',
										'core/strikethrough',
									]}
									multiline="p"
								/>
							</div>
						</div>
					))}
				</div>

				<div className="swift-rank-faq-actions">
					<Button
						icon={plus}
						onClick={addFAQItem}
						variant="secondary"
					>
						{__('Add FAQ Item', 'swift-rank')}
					</Button>
				</div>
			</div>
		</>
	);
}
