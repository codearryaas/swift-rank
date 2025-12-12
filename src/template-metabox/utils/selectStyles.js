/**
 * Shared react-select custom styles for consistent WordPress admin UI
 */
export const getSelectStyles = (variant = 'list') => ({
	control: (provided, state) => ({
		...provided,
		borderColor: state.isFocused ? '#2271b1' : '#8c8f94',
		boxShadow: state.isFocused ? '0 0 0 1px #2271b1' : 'none',
		backgroundColor: '#fff',
		'&:hover': {
			borderColor: '#2271b1'
		},
		minHeight: '36px',
		fontSize: '13px',
		padding: '2px 4px'
	}),
	option: (provided, state) => {
		const baseStyles = {
			...provided,
			backgroundColor: state.isSelected
				? '#2271b1'
				: state.isFocused
					? '#f0f0f1'
					: 'white',
			color: state.isSelected ? 'white' : '#1e1e1e',
			cursor: state.isDisabled ? 'not-allowed' : 'pointer',
			opacity: state.isDisabled ? 0.5 : 1,
			padding: '10px 12px',
			':active': {
				backgroundColor: state.isDisabled ? 'white' : state.isSelected ? '#2271b1' : '#e0e0e0'
			}
		};

		if (variant === 'grid') {
			return {
				...baseStyles,
				border: '1px solid #ddd',
				borderRadius: '4px',
				display: 'flex',
				flexDirection: 'column',
				alignItems: 'flex-start', // Left-align content
				justifyContent: 'center', // Center content vertically
				textAlign: 'left', // Left-align text
				height: '100%',
				minHeight: '50px', // More compact height
				padding: '8px 10px', // More compact padding
				transition: 'all 0.2s ease',
				':hover': {
					...baseStyles[':hover'],
					borderColor: '#2271b1',
					transform: 'translateY(-2px)',
					boxShadow: '0 4px 8px rgba(0,0,0,0.12)'
				}
			};
		}

		return baseStyles;
	},
	singleValue: (provided) => ({
		...provided,
		color: '#1e1e1e',
		fontWeight: 500
	}),
	placeholder: (provided) => ({
		...provided,
		color: '#757575'
	}),
	input: (provided) => ({
		...provided,
		color: '#1e1e1e'
	}),
	menu: (provided) => ({
		...provided,
		zIndex: 9999,
		backgroundColor: '#fff',
		boxShadow: '0 4px 12px rgba(0, 0, 0, 0.15)',
		border: '1px solid #c3c4c7',
		width: '100%' // Match the select input width
	}),
	menuList: (provided) => {
		const baseStyles = {
			...provided,
			padding: 0
		};

		if (variant === 'grid') {
			return {
				...baseStyles,
				display: 'grid',
				gridTemplateColumns: 'repeat(5, 1fr)', // 5 columns for more compact layout
				gap: '6px', // Smaller gap
				padding: '6px', // Smaller padding
				'@media (max-width: 1400px)': {
					gridTemplateColumns: 'repeat(4, 1fr)'
				},
				'@media (max-width: 1200px)': {
					gridTemplateColumns: 'repeat(3, 1fr)'
				},
				'@media (max-width: 782px)': {
					gridTemplateColumns: 'repeat(2, 1fr)'
				},
				'@media (max-width: 600px)': {
					gridTemplateColumns: '1fr'
				}
			};
		}

		return baseStyles;
	}
});

/**
 * Format option label with description
 * The color inherits from the option's state (white when selected IN dropdown menu)
 */
export const formatOptionLabel = ({ label, description }, { context, selectValue }) => {
	// Check if this option is selected in the dropdown (menu context)
	const isSelected = context === 'menu' && selectValue && selectValue[0] && selectValue[0].label === label;

	return (
		<div>
			<div style={{ fontWeight: 500 }}>{label}</div>
			{description && (
				<div style={{
					fontSize: '12px',
					color: isSelected ? 'rgba(255, 255, 255, 0.8)' : '#646970',
					marginTop: '2px'
				}}>
					{description}
				</div>
			)}
		</div>
	);
};
