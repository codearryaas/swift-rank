/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { image as imageIcon } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import '../howto/editor.scss'; // Reuse styles from parent block

export default function Edit({ attributes, setAttributes }) {
    const { name, text, image } = attributes;
    const blockProps = useBlockProps({
        className: 'swift-rank-howto-step',
    });

    return (
        <div {...blockProps}>
            <div className="step-content">
                <RichText
                    tagName="h6"
                    className="step-name"
                    placeholder={__('Step name...', 'swift-rank')}
                    value={name}
                    onChange={(value) => setAttributes({ name: value })}
                    allowedFormats={['core/bold', 'core/italic']}
                />

                <RichText
                    tagName="div"
                    className="step-text"
                    placeholder={__('Step instructions...', 'swift-rank')}
                    value={text}
                    onChange={(value) => setAttributes({ text: value })}
                    allowedFormats={[
                        'core/bold',
                        'core/italic',
                        'core/link',
                        'core/strikethrough',
                    ]}
                    multiline="p"
                />

                <div className="step-image-section">
                    <label>{__('Step Image (Optional)', 'swift-rank')}</label>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media) => setAttributes({ image: media.url })}
                            allowedTypes={['image']}
                            value={image}
                            render={({ open }) => (
                                <div className="step-image-controls">
                                    {image ? (
                                        <div className="step-image-preview">
                                            <img src={image} alt={__('Step image', 'swift-rank')} />
                                            <div className="step-image-actions">
                                                <Button
                                                    onClick={open}
                                                    variant="secondary"
                                                    isSmall
                                                >
                                                    {__('Replace', 'swift-rank')}
                                                </Button>
                                                <Button
                                                    onClick={() => setAttributes({ image: '' })}
                                                    variant="secondary"
                                                    isDestructive
                                                    isSmall
                                                >
                                                    {__('Remove', 'swift-rank')}
                                                </Button>
                                            </div>
                                        </div>
                                    ) : (
                                        <Button
                                            onClick={open}
                                            icon={imageIcon}
                                            variant="secondary"
                                        >
                                            {__('Add Image', 'swift-rank')}
                                        </Button>
                                    )}
                                </div>
                            )}
                        />
                    </MediaUploadCheck>
                </div>
            </div>
        </div>
    );
}
