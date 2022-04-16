const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
        space   : ['responsive', 'direction'],
        margin  : ['responsive', 'direction'],
        padding : ['responsive', 'direction'],
        divide  : ['responsive', 'direction'],
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('tailwindcss-dir')()
    ],
};
