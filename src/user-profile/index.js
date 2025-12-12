import { createRoot, render } from '@wordpress/element';
import PostSchemaMetabox from '../post-metabox/PostSchemaMetabox';
import './style.scss';

// Initialize the app
const initUserProfileSchema = () => {
    const root = document.getElementById('swift-rank-user-profile-root');
    if (root) {
        // Use createRoot (React 18+) with fallback to legacy render
        if (createRoot) {
            const rootInstance = createRoot(root);
            rootInstance.render(<PostSchemaMetabox />);
        } else {
            render(
                <PostSchemaMetabox />,
                root
            );
        }
    }
};

// Run on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initUserProfileSchema);
} else {
    initUserProfileSchema();
}
