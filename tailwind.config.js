import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const colors = require('tailwindcss/colors')


/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    safelist: [
        "bg-sky-600",
        "bg-green-200",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                card: '4px 4px 15px 0 rgba(36, 37, 38, 0.08)',
                dialogue: '3px 4px 15px 0 rgba(36, 37, 38, 0.22)'
            },

            colors: {
                myyellow: "#ffc73c",
                myBlue: "#2879bd",
                purple: colors.purple,
                blue: colors.blue,
                sky: colors.sky,
                green: colors.green,
                "yellow-500": "#eab308"
            },

            spacing: {
                22: "5.5rem"      // 22 divided by 4
            },
            width: {
                "2full": "200%",
                "3full": "300%"
            }
        },
    },

    plugins: [forms],
};
