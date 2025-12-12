/**
 * WordPress dependencies
 */
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const { enableToggle, openFirst } = attributes;
	const blockProps = useBlockProps.save({
		className: 'wp-block-swift-rank-faq',
		'data-enable-toggle': enableToggle,
		'data-open-first': openFirst,
	});

	const innerBlocksProps = useInnerBlocksProps.save(blockProps);

	return <div {...innerBlocksProps} />;
}
