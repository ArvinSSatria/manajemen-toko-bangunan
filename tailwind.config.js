import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    future: {
        hoverOnlyWhenSupported: true,
    },

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'sans-serif'],
                poppins: ['Poppins', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'sans-serif'],
            },
            spacing: {
                'sidebar': '260px',
            },
            colors: {
                primary: {
                    50:  '#f8f5f8',
                    100: '#efe9ef',
                    200: '#dfd2df',
                    300: '#c8b3c9',
                    400: '#ab8fab',
                    500: '#513252', // Main brand color
                    600: '#422842', // Hover state
                    700: '#321d33',
                    800: '#231524',
                    900: '#140c14',
                    950: '#0a060a',
                },
                sidebar: {
                    DEFAULT: '#f5f5f7',
                    hover: '#e5e5ea',
                    active: '#513252',
                    border: '#e5e5ea',
                },
                surface: '#ffffff',
                page: '#f5f5f7', // iOS Light Mode Background
            },
            borderRadius: {
                'card': '20px',
                'input': '12px',
                'menu': '10px',
            },
            borderColor: {
                DEFAULT: '#e5e7eb',
            },
            fontSize: {
                'page-title': ['2rem', { lineHeight: '2.5rem', fontWeight: '700' }],
                'section-title': ['1.5rem', { lineHeight: '2rem', fontWeight: '600' }],
                'card-title': ['1.125rem', { lineHeight: '1.75rem', fontWeight: '600' }],
                'meta': ['0.8125rem', { lineHeight: '1.25rem' }],
            },
        },
    },

    plugins: [forms, require('tailwindcss-animate')],
};
