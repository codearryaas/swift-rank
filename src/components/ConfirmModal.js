/**
 * Confirmation Modal Component
 * Reusable modal for confirming destructive actions
 */

import { __ } from '@wordpress/i18n';
import { Modal, Button } from '@wordpress/components';

/**
 * @param {Object} props
 * @param {boolean} props.isOpen - Whether the modal is open
 * @param {Function} props.onClose - Callback to close the modal
 * @param {Function} props.onConfirm - Callback when user confirms
 * @param {string} props.title - Modal title
 * @param {string} props.message - Confirmation message
 * @param {string} props.confirmText - Text for confirm button (default: 'Confirm')
 * @param {string} props.cancelText - Text for cancel button (default: 'Cancel')
 * @param {boolean} props.isDestructive - Whether the action is destructive (default: true)
 */
const ConfirmModal = ({
    isOpen,
    onClose,
    onConfirm,
    title = __('Confirm Action', 'swift-rank'),
    message = __('Are you sure you want to continue?', 'swift-rank'),
    confirmText = __('Confirm', 'swift-rank'),
    cancelText = __('Cancel', 'swift-rank'),
    isDestructive = true
}) => {
    if (!isOpen) return null;

    const handleConfirm = () => {
        onConfirm();
        onClose();
    };

    return (
        <Modal
            title={title}
            onRequestClose={onClose}
            size="small"
            className="swift-rank-confirm-modal"
        >
            <p>{message}</p>
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '8px', marginTop: '16px' }}>
                <Button
                    variant="secondary"
                    onClick={onClose}
                >
                    {cancelText}
                </Button>
                <Button
                    variant="primary"
                    isDestructive={isDestructive}
                    onClick={handleConfirm}
                >
                    {confirmText}
                </Button>
            </div>
        </Modal>
    );
};

export default ConfirmModal;
