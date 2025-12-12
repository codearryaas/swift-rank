/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	useInnerBlocksProps,
	InnerBlocks,
	InspectorControls,
	BlockControls,
	store as blockEditorStore
} from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	ToolbarGroup,
	ToolbarButton
} from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { plus } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import './editor.scss';

const ALLOWED_BLOCKS = ['swift-rank/faq-item'];

const FAQ_TEMPLATE = [
	['swift-rank/faq-item', {}],
];

export default function Edit({ attributes, setAttributes, clientId }) {
	const { enableSchema, enableToggle, openFirst } = attributes;
	const blockProps = useBlockProps({
		className: 'swift-rank-faq-block',
	});

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		template: FAQ_TEMPLATE,
		renderAppender: InnerBlocks.ButtonBlockAppender,
	});

	// Get the count of inner blocks (FAQ items)
	const faqItemCount = useSelect(
		(select) => {
			const { getBlockCount } = select(blockEditorStore);
			return getBlockCount(clientId);
		},
		[clientId]
	);

	// Get dispatch function to insert blocks
	const { insertBlock } = useDispatch(blockEditorStore);
	const { createBlock } = wp.blocks;

	// Function to add a new FAQ item
	const addFaqItem = () => {
		const newBlock = createBlock('swift-rank/faq-item', {});
		insertBlock(newBlock, faqItemCount, clientId);
	};

	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						icon={plus}
						label={__('Add FAQ Item', 'swift-rank')}
						onClick={addFaqItem}
					/>
				</ToolbarGroup>
			</BlockControls>

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
					<ToggleControl
						label={__('Enable Accordion Toggle', 'swift-rank')}
						help={__(
							'Allow users to expand/collapse FAQ answers by clicking questions.',
							'swift-rank'
						)}
						checked={enableToggle}
						onChange={(value) => setAttributes({ enableToggle: value })}
					/>
					{enableToggle && (
						<ToggleControl
							label={__('Open First Item', 'swift-rank')}
							help={__(
								'Automatically open the first FAQ item when the page loads.',
								'swift-rank'
							)}
							checked={openFirst}
							onChange={(value) => setAttributes({ openFirst: value })}
						/>
					)}
					<p className="components-base-control__help" style={{ marginTop: '16px', paddingTop: '12px', borderTop: '1px solid #ddd' }}>
						{__('Total FAQ items: ', 'swift-rank')}
						<strong>{faqItemCount}</strong>
					</p>
				</PanelBody>
			</InspectorControls>

			<div {...innerBlocksProps} />
		</>
	);
}
