module.exports = {
    plugins: [
        require('daisyui'),
    ],
    purge: {
        mode: 'all',
        preserveHtmlElements: false,
        content: ['./templates/**/*.html.twig']
    },
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            colors: theme => ({
                'nord0': '#2E3440',
                'nord1': '#3B4252',
                'nord2': '#434C5E',
                'nord3': '#4C566A',
                'nord4': '#D8DEE9',
                'nord5': '#E5E9F0',
                'nord6': '#ECEFF4',
                'nord7': '#8FBCBB',
                'nord8': '#88C0D0',
                'nord9': '#81A1C1',
                'nord10': '#5E81AC',
                'nord11': '#BF616A',
                'nord12': '#D08770',
                'nord13': '#EBCB8B',
                'nord14': '#A3BE8C',
                'nord15': '#B48EAD',
            })
        }
    },
    variants: {
        extend: {},
    },
    daisyui: {
        themes: [
            {
                'mytheme': {
                    'primary': '#88C0D0',           // nord8
                    'primary-focus': '#8FBCBB',     // nord7
                    'primary-content': '#ffffff',
                    'secondary': '#f000b8',
                    'secondary-focus': '#bd0091',
                    'secondary-content': '#ffffff',
                    'accent': '#37cdbe',
                    'accent-focus': '#2aa79b',
                    'accent-content': '#ffffff',
                    'neutral': '#3d4451',
                    'neutral-focus': '#2a2e37',
                    'neutral-content': '#ffffff',
                    'base-100': '#2E3440',          // nord0
                    'base-200': '#3B4252',          // nord1
                    'base-300': '#434C5E',          // nord2
                    'base-content': '#1f2937',
                    'info': '#81A1C1',              // nord9
                    'success': '#A3BE8C',           // nord14
                    'warning': '#EBCB8B',           // nord13
                    'error': '#BF616A',             // nord11
                },
            },
        ],
    },
}
