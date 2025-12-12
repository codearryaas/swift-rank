/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './editor.scss';
import './style.scss';
import edit from './edit';
import save from './save';
import metadata from './block.json';

/**
 * Register HowTo Block
 */
registerBlockType(metadata.name, {
	...metadata,
	edit,
	save,
});
