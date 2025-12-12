/**
 * WordPress dependencies
 */
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { faqItems, enableSchema } = attributes;
	const blockProps = useBlockProps.save();

	// Filter out empty FAQ items
	const validFaqItems = faqItems.filter(
		item => item.question && item.answer && item.question.trim() && item.answer.trim()
	);

	// Only generate schema if enabled and we have valid items
	const shouldRenderSchema = enableSchema && validFaqItems.length > 0;

	// Generate FAQ schema if enabled
	const schema = shouldRenderSchema ? {
		'@context': 'https://schema.org',
		'@type': 'FAQPage',
		'mainEntity': validFaqItems.map( ( item ) => {
			// Strip HTML tags from RichText content for schema
			const stripHtml = ( html ) => {
				const tmp = document.createElement( 'div' );
				tmp.innerHTML = html;
				return tmp.textContent || tmp.innerText || '';
			};

			return {
				'@type': 'Question',
				'name': typeof item.question === 'string' ? stripHtml( item.question ) : item.question,
				'acceptedAnswer': {
					'@type': 'Answer',
					'text': typeof item.answer === 'string' ? item.answer : ''
				}
			};
		} )
	} : null;

	return (
		<div { ...blockProps }>
			{ shouldRenderSchema && schema && (
				<script
					type="application/ld+json"
					dangerouslySetInnerHTML={ {
						__html: JSON.stringify( schema, null, 0 )
					} }
				/>
			) }

			<div className="swift-rank-faq-items">
				{ validFaqItems.map( ( item, index ) => {
					const itemProps = enableSchema ? {
						itemScope: true,
						itemProp: 'mainEntity',
						itemType: 'https://schema.org/Question'
					} : {};

					return (
						<div key={ index } className="swift-rank-faq-item" { ...itemProps }>
							<RichText.Content
								tagName="h4"
								className="faq-question"
								value={ item.question }
								{ ...( enableSchema && { itemProp: 'name' } ) }
							/>
							<div
								{ ...( enableSchema && {
									itemScope: true,
									itemProp: 'acceptedAnswer',
									itemType: 'https://schema.org/Answer'
								} ) }
							>
								<RichText.Content
									tagName="div"
									className="faq-answer"
									value={ item.answer }
									{ ...( enableSchema && { itemProp: 'text' } ) }
								/>
							</div>
						</div>
					);
				} ) }
			</div>
		</div>
	);
}
