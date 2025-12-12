/**
 * Repeater Field Component
 * Generic repeater field for adding multiple items with sub-fields
 * Used by FAQ, HowTo, and other schema types
 */

import { __ } from '@wordpress/i18n';
import { useState, useEffect, useRef } from '@wordpress/element';
import { Button } from '@wordpress/components';
import Tooltip from './Tooltip';
import Icon from './Icon';
import SchemaField from './SchemaField';
import ConfirmModal from './ConfirmModal';

const RepeaterField = ({ value, onChange, label, tooltip, isOverridden, onReset, fields = [] }) => {
    // Track if user has interacted with the field
    const hasUserInteracted = useRef(false);

    // Track item to remove for confirmation
    const [itemToRemove, setItemToRemove] = useState(null);

    // Initialize state with existing items or empty array
    const [items, setItems] = useState(() => {
        if (value && Array.isArray(value) && value.length > 0) {
            return value;
        }
        // Default with one empty item
        return [{}];
    });

    // Update parent when items change - only if user interacted
    useEffect(() => {
        if (hasUserInteracted.current) {
            onChange(items);
        }
    }, [items]);

    // Sync with external value changes (e.g., reset)
    useEffect(() => {
        if (value && Array.isArray(value) && value.length > 0) {
            // Only sync if not from user interaction
            if (!hasUserInteracted.current) {
                setItems(value);
            }
        }
    }, [value]);

    const addItem = () => {
        hasUserInteracted.current = true;
        setItems([...items, {}]);
    };

    const confirmRemoveItem = (index) => {
        // Check if item has any values
        const item = items[index];
        const hasValues = item && Object.keys(item).some(key => item[key] && item[key].trim() !== '');

        if (hasValues) {
            // Show confirmation if item has values
            setItemToRemove(index);
        } else {
            // Remove directly if item is empty
            removeItem(index);
        }
    };

    const removeItem = (index) => {
        hasUserInteracted.current = true;
        if (items.length === 1) {
            // Keep at least one empty item
            setItems([{}]);
        } else {
            const newItems = items.filter((_, i) => i !== index);
            setItems(newItems);
        }
        setItemToRemove(null);
    };

    const updateItem = (index, fieldName, fieldValue) => {
        hasUserInteracted.current = true;
        const newItems = [...items];
        newItems[index] = {
            ...newItems[index],
            [fieldName]: fieldValue
        };
        setItems(newItems);
    };

    return (
        <div className={`schema-field ${isOverridden ? 'has-override' : ''}`}>
            <ConfirmModal
                isOpen={itemToRemove !== null}
                onClose={() => setItemToRemove(null)}
                onConfirm={() => removeItem(itemToRemove)}
                title={__('Confirm Remove', 'swift-rank')}
                message={__('Are you sure you want to remove this item? This action cannot be undone.', 'swift-rank')}
                confirmText={__('Remove', 'swift-rank')}
                isDestructive={true}
            />
            <div className="field-header">
                <label className="field-label">
                    {label}
                    {tooltip && <Tooltip text={tooltip} />}
                </label>
                {isOverridden && onReset && (
                    <div className="field-actions">
                        <Button
                            variant="tertiary"
                            isDestructive
                            onClick={onReset}
                            className="reset-btn field-action-btn"
                            icon={<Icon name="refresh-cw" size={16} />}
                            label={__('Reset to default', 'swift-rank')}
                        />
                    </div>
                )}
            </div>
            <div className="swift-rank-repeater-container">
                <div className="swift-rank-repeater-items">
                    {items.map((item, index) => (
                        <div key={index} className="swift-rank-repeater-item">
                            <div className="swift-rank-repeater-item-header">
                                <span className="swift-rank-repeater-item-title">
                                    {label} #{index + 1}
                                </span>
                                <Button
                                    variant="link"
                                    isDestructive
                                    className="swift-rank-remove-repeater-item"
                                    onClick={(e) => {
                                        // Prevent default browser confirmation if attached to this event
                                        if (e && e.preventDefault) e.preventDefault();
                                        if (e && e.stopPropagation) e.stopPropagation();
                                        confirmRemoveItem(index);
                                    }}
                                    style={{
                                        display: 'inline-flex',
                                        alignItems: 'center',
                                        gap: '4px',
                                        textDecoration: 'none',
                                        fontSize: '12px'
                                    }}
                                >
                                    <Icon name="x" size={16} />
                                    {__('Remove', 'swift-rank')}
                                </Button>
                            </div>
                            <div className="swift-rank-repeater-item-content">
                                {fields.map((fieldConfig) => (
                                    <SchemaField
                                        key={fieldConfig.name}
                                        label={fieldConfig.label}
                                        fieldName={fieldConfig.name}
                                        value={item[fieldConfig.name] || ''}
                                        onChange={(newValue) => updateItem(index, fieldConfig.name, newValue)}
                                        type={fieldConfig.type}
                                        placeholder={fieldConfig.placeholder}
                                        tooltip={fieldConfig.tooltip}
                                        rows={fieldConfig.rows}
                                        required={fieldConfig.required}
                                    />
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
                <Button
                    variant="secondary"
                    onClick={addItem}
                    className="swift-rank-add-repeater-item"
                    icon={<Icon name="plus" size={16} />}
                >
                    {__('Add Item', 'swift-rank')}
                </Button>
            </div>
        </div>
    );
};

export default RepeaterField;
