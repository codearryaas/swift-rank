/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, useInnerBlocksProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './editor.scss';

const ALLOWED_BLOCKS = ['swift-rank/howto-step'];

const TEMPLATE = [
	['swift-rank/howto-step', {}],
];

export default function Edit({ attributes, setAttributes }) {
	const { title, description, totalTime, enableSchema } = attributes;

	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'swift-rank-howto-steps' },
		{
			allowedBlocks: ALLOWED_BLOCKS,
			template: TEMPLATE,
		}
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('HowTo Settings', 'swift-rank')}>
					<ToggleControl
						label={__('Enable HowTo Schema', 'swift-rank')}
						help={__(
							'When enabled, this block will output HowTo schema markup.',
							'swift-rank'
						)}
						checked={enableSchema}
						onChange={(value) => setAttributes({ enableSchema: value })}
					/>
					<TextControl
						label={__('Total Time', 'swift-rank')}
						help={__('e.g., "PT30M" for 30 minutes or "PT2H" for 2 hours', 'swift-rank')}
						value={totalTime}
						onChange={(value) => setAttributes({ totalTime: value })}
						placeholder="PT30M"
					/>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps({ className: 'swift-rank-howto-block' })}>
				<div className="swift-rank-howto-intro">
					<RichText
						tagName="h4"
						className="howto-title"
						placeholder={__('Enter How-To title...', 'swift-rank')}
						value={title}
						onChange={(value) => setAttributes({ title: value })}
						allowedFormats={['core/bold', 'core/italic']}
					/>

					<RichText
						tagName="p"
						className="howto-description"
						placeholder={__('Enter description...', 'swift-rank')}
						value={description}
						onChange={(value) => setAttributes({ description: value })}
						allowedFormats={['core/bold', 'core/italic']}
					/>
				</div>

				<div {...innerBlocksProps} />
			</div>
		</>
	);
}
