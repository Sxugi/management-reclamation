import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/css/app.css',
    ],

    theme: {
        extend: {
            colors: {
                whitesmoke: '#f5f5f5',
                darkslategray: '#27374d',
                darkgray: '#b3b3b3',
                gray: {
                    100: '#757575',
                    200: '#1d2939',
                },
                tomato: '#f04438',
                white: '#fff',
                cornflowerblue: '#9cb9ff',
                lightgray: '#d0d5dd',
                slategray: {
                    100: '#667085',
                    200: '#526d82',
                },
                mediumslateblue: '#465fff',
                gainsboro: '#dde6ed',
            },
            fontFamily: {
                outfit: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    corePlugins: {
        preflight: false
    },    
    plugins: [
        forms,
    ],
};