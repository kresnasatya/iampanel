const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './node_modules/tw-elements/dist/js/**/*.js'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                orange: {
                    '50': '#fff8f1',
                    '100': '#feecdc',
                    '200': '#fcd9bd',
                    '300': '#fdba8c',
                    '400': '#ff8a4c',
                    '500': '#ff5a1f',
                    '600': '#d03801',
                    '700': '#b43403',
                    '800': '#8a2c0d',
                    '900': '#771d1d',
                },
                teal: {
                    '50': '#edfafa',
                    '100': '#d5f5f6',
                    '200': '#afecef',
                    '300': '#7edce2',
                    '400': '#16bdca',
                    '500': '#0694a2',
                    '600': '#047481',
                    '700': '#036672',
                    '800': '#05505c',
                    '900': '#014451',
                },
                primary: {
                    100: '#cffafe',
                    200: '#a5f3fc',
                    300: '#67e8f9',
                    400: '#22d3ee',
                    500: '#06b6d4',
                    600: '#002465',
                    700: '#00399E',
                    800: '#155e75',
                    900: '#164e63',
                }
            },
            maxHeight: {
                '0': '0',
                xl: '36rem',
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('tw-elements/dist/plugin')
    ],
};
