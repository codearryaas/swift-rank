/**
 * WordPress dependencies
 */
import { useBlockProps, RichText, useInnerBlocksProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const { title, description } = attributes;

	const innerBlocksProps = useInnerBlocksProps.save({
		className: 'swift-rank-howto-steps',
	});

	return (
		<div {...useBlockProps.save({ className: 'swift-rank-howto-block' })}>
			<div className="swift-rank-howto-intro">
				{title && (
					<RichText.Content
						tagName="h4"
						className="howto-title"
						value={title}
					/>
				)}

				{description && (
					<RichText.Content
						tagName="p"
						className="howto-description"
						value={description}
					/>
				)}
			</div>

			<div {...innerBlocksProps} />
		</div>
	);
}
