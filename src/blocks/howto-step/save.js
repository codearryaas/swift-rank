/**
 * WordPress dependencies
 */
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save({ attributes }) {
    const { name, text, image } = attributes;
    const blockProps = useBlockProps.save({
        className: 'swift-rank-howto-step',
    });

    return (
        <div {...blockProps}>
            <div className="step-content">
                {name && (
                    <RichText.Content
                        tagName="h6"
                        className="step-name"
                        value={name}
                    />
                )}

                {text && (
                    <RichText.Content
                        tagName="div"
                        className="step-text"
                        value={text}
                    />
                )}

                {image && (
                    <div className="step-image">
                        <img src={image} alt={name} />
                    </div>
                )}
            </div>
        </div>
    );
}
