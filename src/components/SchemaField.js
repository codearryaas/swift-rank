import { useState, useEffect } from '@wordpress/element';
import {
	TextControl,
	TextareaControl,
	Button,
	DateTimePicker,
	DatePicker,
	TimePicker,
	Popover
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import Tooltip from './Tooltip';
import VariablesPopup from './VariablesPopup';
import Icon from './Icon';
import DurationPicker from './DurationPicker';

/**
 * Unified Schema Field component with variable insertion, image upload, and optional reset.
 *
 * This component is used by both the template metabox (for setting defaults)
 * and the post metabox (for overriding values).
 *
 * @param {Object}   props
 * @param {string}   props.label          - Field label
 * @param {string}   props.fieldName      - Field name/key
 * @param {string}   props.value          - Current field value
 * @param {Function} props.onChange       - Change handler (receives new value)
 * @param {string}   props.tooltip        - Tooltip text
 * @param {string}   props.placeholder    - Placeholder text
 * @param {string}   props.help           - Help text below field
 * @param {string}   props.type           - Field type: 'text', 'textarea', 'image', 'url', 'email', 'tel', 'datetime-local'
 * @param {number}   props.rows           - Number of rows for textarea (default: 4)
 * @param {string}   props.defaultValue   - Template default value (for post metabox)
 * @param {boolean}  props.isOverridden   - Whether the field has an override (for post metabox)
 * @param {Function} props.onReset        - Reset handler (for post metabox, shows reset button when provided)
 * @param {boolean}  props.showImageUpload - Deprecated: use type="image" instead
 */

const SchemaField = ({
	label,
	fieldName,
	value,
	onChange,
	tooltip,
	placeholder,
	help,
	type = 'text',
	rows = 4,
	defaultValue,
	isOverridden = false,
	onReset,
	showImageUpload = false, // Deprecated but kept for backwards compatibility
	required = false, // Whether this field is required by Google Schema
	returnObject = false, // Whether to return full object {id, url} for media fields
	noHeader = false, // Whether to hide the header (for embedding in other components)
}) => {
	const [inputRef, setInputRef] = useState(null);
	const [mediaFrame, setMediaFrame] = useState(null);

	// Determine if this is an image field (support both new type="image" and old showImageUpload prop)
	const isImageType = type === 'image' || showImageUpload;

	// Cleanup media frame on unmount
	useEffect(() => {
		return () => {
			if (mediaFrame) {
				mediaFrame.off('select');
			}
		};
	}, [mediaFrame]);

	// Insert variable at cursor position
	const insertVariable = (variable) => {
		// Ensure value is a string before string operations
		const currentValue = typeof value === 'string' ? value : '';

		if (inputRef) {
			const cursorPos = inputRef.selectionStart;
			const textBefore = currentValue.substring(0, cursorPos);
			const textAfter = currentValue.substring(cursorPos);
			onChange(textBefore + variable + textAfter);

			setTimeout(() => {
				inputRef.focus();
				inputRef.setSelectionRange(
					cursorPos + variable.length,
					cursorPos + variable.length
				);
			}, 0);
		} else {
			onChange(currentValue + variable);
		}
	};

	// Open media uploader
	const openMediaUploader = (e) => {
		e.preventDefault();
		e.stopPropagation();

		if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
			alert(__('Media library not available. Please refresh the page.', 'swift-rank'));
			return;
		}

		let frame = mediaFrame;
		if (!frame) {
			frame = wp.media({
				title: __('Select or Upload Image', 'swift-rank'),
				button: { text: __('Use this image', 'swift-rank') },
				library: { type: 'image' },
				multiple: false,
			});

			frame.on('select', () => {
				const attachment = frame.state().get('selection').first().toJSON();
				if (attachment && attachment.url) {
					if (returnObject) {
						onChange({
							id: attachment.id,
							url: attachment.url,
							alt: attachment.alt,
							width: attachment.width,
							height: attachment.height,
							mime: attachment.mime,
						});
					} else {
						onChange(attachment.url);
					}
				}
			});

			setMediaFrame(frame);
		}

		frame.open();
	};

	// Check if value is a variable or valid URL (for image preview)
	// Ensure value is a string before calling string methods (handles reference objects gracefully)
	const stringValue = typeof value === 'object' && value?.url ? value.url : (typeof value === 'string' ? value : '');
	const isVariable = stringValue && stringValue.startsWith('{') && stringValue.endsWith('}');
	const hasValidUrl = stringValue && !isVariable && (stringValue.startsWith('http') || stringValue.startsWith('//'));

	// Render the appropriate input control
	const renderInput = () => {
		// Ensure value is always a string (handle reference objects gracefully)
		const displayValue = typeof value === 'object' && value?.url ? value.url : (typeof value === 'string' ? value : '');

		const [isPickerOpen, setIsPickerOpen] = useState(false);

		if (type === 'textarea') {
			return (
				<>
					<div className="schema-field-input-wrapper" style={{ position: 'relative' }}>
						<TextareaControl
							value={displayValue}
							onChange={onChange}
							rows={rows}
							ref={setInputRef}
							className="schema-field-textarea"
							style={{ marginBottom: 0 }}
						/>
						<div
							className="schema-field-variable-picker"
							style={{
								position: 'absolute',
								right: '8px',
								top: '8px',
								zIndex: 1
							}}
						>
							<VariablesPopup
								onSelect={insertVariable}
								buttonProps={{
									isSmall: true,
									// Wrap Icon in span to prevent Button from overriding size
									icon: <span style={{ display: 'flex' }}><Icon name="code" size={12} style={{ width: '12px', height: '12px', display: 'block' }} /></span>,
									style: { minWidth: '20px', height: '20px', padding: 0 }
								}}
							/>
						</div>
					</div>
					{help && <p className="components-base-control__help" style={{ marginTop: '8px' }}>{help}</p>}
				</>
			);
		}

		// Handle Date/Time types with hybrid input (Text + Picker)
		const isDateType = type === 'date' || type === 'datetime' || type === 'datetime-local';
		const isTimeType = type === 'time';
		const isDurationType = type === 'duration';

		if (isDateType || isTimeType || isDurationType) {
			return (
				<>
					<div className="schema-field-input-wrapper" style={{ position: 'relative' }}>
						<TextControl
							value={displayValue}
							onChange={onChange}
							placeholder={placeholder}
							type="text" // Always use text to allow variables
							ref={setInputRef}
							className="schema-field-text-input"
							style={{ marginBottom: 0 }}
						/>
						<div
							className="schema-field-variable-picker"
							style={{
								position: 'absolute',
								right: '8px',
								top: '50%',
								transform: 'translateY(-50%)',
								zIndex: 1,
								display: 'flex',
								alignItems: 'center',
								gap: '4px'
							}}
						>
							{/* Date/Time/Duration Picker Trigger */}
							<Button
								variant="tertiary"
								onClick={() => setIsPickerOpen(!isPickerOpen)}
								className="field-action-btn is-small"
								label={(isTimeType || isDurationType) ? __('Select Time', 'swift-rank') : __('Select Date', 'swift-rank')}
								style={{ minWidth: '20px', height: '20px', padding: 0, justifyContent: 'center' }}
							>
								<span style={{ display: 'flex' }}>
									<Icon
										name={(isTimeType || isDurationType) ? 'clock' : 'calendar'}
										size={12}
										style={{ width: '12px', height: '12px', display: 'block' }}
									/>
								</span>
							</Button>

							{isPickerOpen && (
								<Popover
									position="bottom left"
									onClose={() => setIsPickerOpen(false)}
									className="schema-field-datetime-popover"
								>
									<div style={{ padding: '10px' }}>
										{isTimeType && (
											<TimePicker
												currentTime={displayValue}
												onChange={onChange}
												is12Hour={false}
											/>
										)}

										{isDurationType && (
											<div style={{ minWidth: '200px' }}>
												<DurationPicker
													value={displayValue}
													onChange={onChange}
												/>
											</div>
										)}

										{type === 'date' && (
											<DatePicker
												currentDate={displayValue}
												onChange={(newDate) => {
													// DatePicker returns ISO string YYYY-MM-DDT... usually need to strip time if date only?
													// Or it returns just date.
													// We'll trust onChange provides a usable string, but if it includes time for 'date' type, purely date is better.
													// Usually DatePicker returns ISO string.
													// We can take substring(0, 10) if we want strict date.
													// But let's just pass raw for now.
													if (newDate && newDate.indexOf('T') > -1) {
														onChange(newDate.split('T')[0]);
													} else {
														onChange(newDate);
													}
												}}
											/>
										)}

										{(type === 'datetime' || type === 'datetime-local') && (
											<DateTimePicker
												currentDate={displayValue}
												onChange={onChange}
												is12Hour={true}
											/>
										)}
									</div>
								</Popover>
							)}

							<VariablesPopup
								onSelect={insertVariable}
								buttonProps={{
									isSmall: true,
									// Wrap Icon in span to prevent Button from overriding size
									icon: <span style={{ display: 'flex' }}><Icon name="code" size={12} style={{ width: '12px', height: '12px', display: 'block' }} /></span>,
									style: { minWidth: '20px', height: '20px', padding: 0, justifyContent: 'center' }
								}}
							/>
						</div>
					</div>
					{help && <p className="components-base-control__help" style={{ marginTop: '8px' }}>{help}</p>}
				</>
			);
		}

		// Use text type for image/url fields to allow variables like {featured_image}
		// HTML5 url validation would reject variable placeholders
		const inputType = (isImageType || type === 'url') ? 'text' : type;

		return (
			<>
				<div className="schema-field-input-wrapper" style={{ position: 'relative' }}>
					<TextControl
						value={displayValue}
						onChange={onChange}
						placeholder={placeholder}
						type={inputType}
						ref={setInputRef}
						className="schema-field-text-input"
						style={{ marginBottom: 0 }}
					/>
					<div
						className="schema-field-variable-picker"
						style={{
							position: 'absolute',
							right: '8px',
							top: '50%',
							transform: 'translateY(-50%)',
							zIndex: 1,
							display: 'flex',
							alignItems: 'center',
							gap: '4px'
						}}
					>
						{isImageType && (
							<Button
								variant="tertiary"
								onClick={openMediaUploader}
								className="field-action-btn is-small"
								label={__('Upload Image', 'swift-rank')}
								style={{ minWidth: '20px', height: '20px', padding: 0, justifyContent: 'center' }}
							>
								<span style={{ display: 'flex' }}>
									<Icon name="image" size={12} style={{ width: '12px', height: '12px', display: 'block' }} />
								</span>
							</Button>
						)}

						<VariablesPopup
							onSelect={insertVariable}
							buttonProps={{
								isSmall: true,
								// Wrap Icon in span to prevent Button from overriding size
								icon: <span style={{ display: 'flex' }}><Icon name="code" size={12} style={{ width: '12px', height: '12px', display: 'block' }} /></span>,
								style: { minWidth: '20px', height: '20px', padding: 0, justifyContent: 'center' }
							}}
						/>
					</div>
				</div>
				{help && <p className="components-base-control__help" style={{ marginTop: '8px' }}>{help}</p>}
			</>
		);
	};

	return (
		<div className={`schema-field ${isOverridden ? 'has-override' : ''}`} data-field-name={fieldName}>
			{!noHeader && (
				<div className="field-header">
					<label className="field-label">
						{label}
						{required && <span style={{ color: '#d63638', marginLeft: '4px' }}>*</span>}
						{tooltip && <Tooltip text={tooltip} />}
					</label>
					<div className="field-actions">
						{/* Reset button for post metabox overrides */}
						{onReset && isOverridden && (
							<Button
								variant="tertiary"
								isDestructive
								onClick={onReset}
								className="field-action-btn reset-btn"
								label={__('Reset to Default', 'swift-rank')}
							>
								<Icon name="refresh-cw" size={16} />
							</Button>
						)}
					</div>
				</div>
			)}

			{renderInput()}

			{/* Template default value (shown in post metabox) */}
			{defaultValue && (
				<p className="field-default">
					<span className="label">{__('Template default:', 'swift-rank')}</span>
					<code>{defaultValue}</code>
				</p>
			)}

			{/* Image preview */}
			{isImageType && hasValidUrl && (
				<div className="media-preview" style={{ marginTop: '8px', marginBottom: '8px' }}>
					<img
						src={stringValue}
						alt={__('Preview', 'swift-rank')}
						onError={(e) => { e.target.style.display = 'none'; }}
						style={{ maxWidth: '200px', borderRadius: '4px', border: '1px solid #ddd' }}
					/>
				</div>
			)}
		</div>
	);
};

export default SchemaField;
