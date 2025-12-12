/**
 * Social Profiles Field Configuration
 * Defines fields for the Social Profiles settings tab
 */

export const socialProfilesFields = [
    {
        name: 'facebook',
        label: 'Facebook',
        type: 'url',
        placeholder: 'https://facebook.com/yourpage',
        tooltip: 'Your Facebook page or profile URL',
    },
    {
        name: 'twitter',
        label: 'Twitter / X',
        type: 'url',
        placeholder: 'https://twitter.com/yourhandle',
        tooltip: 'Your Twitter (X) profile URL',
    },
    {
        name: 'linkedin',
        label: 'LinkedIn',
        type: 'url',
        placeholder: 'https://linkedin.com/in/yourprofile',
        tooltip: 'Your LinkedIn profile URL',
    },
    {
        name: 'instagram',
        label: 'Instagram',
        type: 'url',
        placeholder: 'https://instagram.com/yourusername',
        tooltip: 'Your Instagram profile URL',
    },
    {
        name: 'youtube',
        label: 'YouTube',
        type: 'url',
        placeholder: 'https://youtube.com/@yourchannel',
        tooltip: 'Your YouTube channel URL',
    },
    {
        name: 'custom_profiles',
        label: 'Custom Social Profiles',
        type: 'repeater',
        tooltip: 'Add any other social media profiles not listed above',
        isPro: true,
        fields: [
            {
                name: 'platform',
                label: 'Platform Name',
                type: 'text',
                placeholder: 'e.g., Pinterest, TikTok, etc.',
            },
            {
                name: 'url',
                label: 'Profile URL',
                type: 'url',
                placeholder: 'https://...',
            },
        ],
    },
];

export default socialProfilesFields;
