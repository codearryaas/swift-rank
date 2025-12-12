/**
 * WordPress dependencies
 */
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { question, answer, isOpen } = attributes;
	const blockProps = useBlockProps.save( {
		className: `swift-rank-faq-item${ isOpen ? ' is-open' : '' }`,
	} );

	// Don't render if question or answer is empty
	if ( ! question || ! answer || ! question.trim() || ! answer.trim() ) {
		return null;
	}

	return (
		<div { ...blockProps }>
			<div className="faq-item-question-wrapper">
				<RichText.Content
					tagName="h4"
					className="faq-item-question"
					value={ question }
				/>
				<svg className="faq-item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
					<path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
				</svg>
			</div>

			<div className="faq-item-answer">
				<RichText.Content
					tagName="div"
					className="faq-item-answer-content"
					value={ answer }
				/>
			</div>
		</div>
	);
}
