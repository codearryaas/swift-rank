import { createRoot, render } from '@wordpress/element';
import PostSchemaMetabox from './PostSchemaMetabox';
import * as SharedComponents from '../components';
import '../components/style.scss';
import './style.scss';

// Expose all shared components globally for Pro plugin to use
window.swiftRankComponents = window.swiftRankComponents || {};
Object.assign(window.swiftRankComponents, SharedComponents);

const container = document.getElementById('swift-rank-post-metabox-root');

if (container) {
	// Use createRoot (React 18+) with fallback to legacy render
	if (createRoot) {
		const root = createRoot(container);
		root.render(<PostSchemaMetabox />);
	} else {
		render(<PostSchemaMetabox />, container);
	}
}
