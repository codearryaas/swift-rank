/**
 * Shared React Select component wrapper
 * Provides consistent styling across free and pro versions
 */

import ReactSelect from 'react-select';
import { getSelectStyles, formatOptionLabel } from '../template-metabox/utils/selectStyles';

const Select = ({
	value,
	onChange,
	options,
	isSearchable = true,
	isDisabled = false,
	placeholder,
	className,
	styles,
	formatOptionLabel: customFormatOptionLabel,
	isOptionDisabled,
	variant = 'list',
	...props
}) => {
	return (
		<ReactSelect
			value={value}
			onChange={onChange}
			options={options}
			styles={styles || getSelectStyles(variant)}
			formatOptionLabel={customFormatOptionLabel || formatOptionLabel}
			isSearchable={isSearchable}
			isDisabled={isDisabled}
			isOptionDisabled={isOptionDisabled}
			placeholder={placeholder}
			className={className}
			{...props}
		/>
	);
};

export default Select;
