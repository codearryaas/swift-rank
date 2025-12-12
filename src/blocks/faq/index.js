/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import edit from './edit';
import save from './save';
import metadata from './block.json';
import './style.scss'; // Frontend styles

/**
 * Register FAQ Block
 */
registerBlockType( metadata.name, {
	...metadata,
	edit,
	save,
} );
