import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gray: require('tailwindcss/colors').gray,
                primary: '#174d38',
                secondary: '#6D071A',
                faded: '#f2f2f2',
                faded_dark: '#EDEDED',
                mute: '#A8CBB7',
                content: 'black'
            }
        },
    },

    plugins: [forms],
};
