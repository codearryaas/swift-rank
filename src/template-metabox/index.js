import { createRoot, render } from '@wordpress/element';
import SchemaMetabox from './SchemaMetabox';
import ProSidebarNotice from '../components/ProSidebarNotice';
import * as SharedComponents from '../components';
import '../components/style.scss';
import './style.scss';

// Expose all shared components globally for Pro plugin to use
window.swiftRankComponents = window.swiftRankComponents || {};
Object.assign(window.swiftRankComponents, SharedComponents);

const container = document.getElementById('schema-template-metabox-root');

if (container) {
	// Use createRoot (React 18+) with fallback to legacy render
	if (createRoot) {
		const root = createRoot(container);
		root.render(<SchemaMetabox />);
	} else {
		render(<SchemaMetabox />, container);
	}
}

// Render Pro sidebar if container exists
const sidebarContainer = document.getElementById('schema-pro-sidebar-root');

if (sidebarContainer) {
	// Use default features from ProSidebarNotice component
	if (createRoot) {
		const sidebarRoot = createRoot(sidebarContainer);
		sidebarRoot.render(<ProSidebarNotice />);
	} else {
		render(<ProSidebarNotice />, sidebarContainer);
	}
}
