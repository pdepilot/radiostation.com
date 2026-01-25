import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './public/assets/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'system-ui', ...defaultTheme.fontFamily.sans],
                heading: ['Oxanium', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: '#fee2e2',
                    100: '#fecaca',
                    200: '#fca5a5',
                    300: '#f87171',
                    400: '#ef4444',
                    500: '#c8102e',
                    600: '#a00d25',
                    700: '#991b1b',
                    800: '#7f1d1d',
                    900: '#450a0a',
                    950: '#0f0f11',
                },
                gray: {
                    50: '#f9fafb',
                    100: '#f0f0f5',
                    200: '#e5e7eb',
                    300: '#b0b0c0',
                    400: '#9ca3af',
                    500: '#707080',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1e1e21',
                    900: '#161618',
                    950: '#0f0f11',
                },
                accent: '#c8102e',
                'accent-soft': '#d32f4f',
            },
        },
    },

    plugins: [forms],
};
