import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import plugin from 'tailwindcss/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Urbanist', ...defaultTheme.fontFamily.sans],
                urbanist: ['Urbanist', 'sans-serif'],
                poppins: ['Poppins', 'sans-serif'],
            },
            width: {
                66: '66%',
                88: '88%',
                70: '70%',
            },
            fontSize: {
                xs: '12px',
                sm: '14px',
                base: '16px',
                lg: '18px',
                xl: '20px',
                '2xl': '24px',
                '3xl': '28px',
                '4xl': '32px',
                '5xl': '48px',
            },
            colors: {
                // Dark mode colors
                darkblack: {
                    300: '#747681',
                    400: '#2A313C',
                    500: '#23262B',
                    600: '#1D1E24',
                    700: '#151515',
                },
                // Status colors - sesuai PRD
                success: {
                    50: '#D9FBE6',
                    100: '#B7FFD1',
                    200: '#4ADE80',
                    300: '#22C55E',
                    400: '#16A34A',
                },
                warning: {
                    100: '#FDE047',
                    200: '#FACC15',
                    300: '#EAB308',
                },
                error: {
                    50: '#FCDEDE',
                    100: '#FF7171',
                    200: '#FF4747',
                    300: '#DD3333',
                },
                // Gray shades
                bgray: {
                    50: '#FAFAFA',
                    100: '#F7FAFC',
                    200: '#EDF2F7',
                    300: '#E2E8F0',
                    400: '#CBD5E0',
                    500: '#A0AEC0',
                    600: '#718096',
                    700: '#4A5568',
                    800: '#2D3748',
                    900: '#1A202C',
                },
                // Brand colors KAS
                primary: {
                    50: '#D9FBE6',
                    100: '#B7FFD1',
                    200: '#4ADE80',
                    300: '#22C55E',
                    400: '#16A34A',
                    500: '#22C55E',
                    600: '#16A34A',
                    700: '#15803D',
                },
                orange: '#FF784B',
                bamber: {
                    50: '#FFFBEB',
                    100: '#FFC837',
                    500: '#F6A723',
                },
                purple: '#936DFF',
                portage: '#936DFF',
            },
            lineHeight: {
                'extra-loose': '44.8px',
                'big-loose': '140%',
                130: '130%',
                150: '150%',
                160: '160%',
                175: '175%',
                180: '180%',
                220: '220%',
            },
            letterSpacing: {
                tight: '-0.96px',
                40: '-0.4px',
            },
            borderRadius: {
                20: '20px',
            },
        },
    },
    plugins: [
        forms,
        typography,
        plugin(function ({ addVariant }) {
            addVariant('current', '&.active');
        }),
    ],
};
