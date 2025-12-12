import { __ } from '@wordpress/i18n';
import { TextControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

/**
 * DurationPicker component for ISO 8601 durations (PT#H#M).
 * Allows picking hours and minutes without standard time picker limitations.
 * 
 * @param {Object} props
 * @param {string} props.value    - Current ISO duration string (e.g., PT1H30M)
 * @param {Function} props.onChange - Callback with new ISO string
 */
const DurationPicker = ({ value, onChange }) => {
    const [hours, setHours] = useState('');
    const [minutes, setMinutes] = useState('');

    // Parse incoming value on mount or when changed externally
    useEffect(() => {
        if (value && value.startsWith('PT')) {
            const match = value.match(/PT(?:(\d+)H)?(?:(\d+)M)?/);
            if (match) {
                setHours(match[1] || '0');
                setMinutes(match[2] || '0');
            }
        } else {
            setHours('');
            setMinutes('');
        }
    }, [value]);

    const updateDuration = (newHours, newMinutes) => {
        const h = parseInt(newHours, 10);
        const m = parseInt(newMinutes, 10);

        if (isNaN(h) && isNaN(m)) {
            // If both empty/invalid, clear
            onChange('');
            return;
        }

        let duration = 'PT';
        if (!isNaN(h) && h > 0) duration += `${h}H`;
        if (!isNaN(m) && m > 0) duration += `${m}M`;

        // If both 0, maybe output PT0M? Or empty? usually empty for optional field.
        // If input is "0" and "0", let's clear it or return PT0M if strictly needed.
        // Standard behavior: clear if empty.
        if (duration === 'PT') {
            onChange('');
        } else {
            onChange(duration);
        }
    };

    const handleHoursChange = (val) => {
        setHours(val);
        updateDuration(val, minutes);
    };

    const handleMinutesChange = (val) => {
        setMinutes(val);
        updateDuration(hours, val);
    };

    return (
        <div className="swift-rank-duration-picker" style={{ display: 'flex', gap: '10px', alignItems: 'flex-end' }}>
            <div style={{ flex: 1 }}>
                <TextControl
                    label={__('Hours', 'swift-rank')}
                    type="number"
                    value={hours}
                    onChange={handleHoursChange}
                    min={0}
                    step={1}
                />
            </div>
            <div style={{ flex: 1 }}>
                <TextControl
                    label={__('Minutes', 'swift-rank')}
                    type="number"
                    value={minutes}
                    onChange={handleMinutesChange}
                    min={0}
                    max={59} // Usually minutes wrap, but unlimited is ok? Standard duration usually keeps minutes < 60 but >60 is valid ISO. But let's suggest standard.
                    step={1}
                />
            </div>
        </div>
    );
};

export default DurationPicker;
