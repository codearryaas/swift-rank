/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './editor.scss';

export default function Edit({ attributes, setAttributes, context }) {
	const { question, answer } = attributes;
	const blockProps = useBlockProps({
		className: 'swift-rank-faq-item',
	});

	return (
		<div {...blockProps}>
			<div className="faq-item-question-wrapper">
				<RichText
					tagName="div"
					className="faq-item-question"
					placeholder={__('Enter question...', 'swift-rank')}
					value={question}
					onChange={(value) => setAttributes({ question: value })}
					allowedFormats={['core/bold', 'core/italic']}
				/>
				{context['swift-rank/enableToggle'] && (
					<svg className="faq-item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
						<path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
					</svg>
				)}
			</div>

			<div className="faq-item-answer-wrapper">
				<RichText
					tagName="div"
					className="faq-item-answer"
					placeholder={__('Enter answer...', 'swift-rank')}
					value={answer}
					onChange={(value) => setAttributes({ answer: value })}
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
	);
}
