import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.tsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['Space Grotesk', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                brand: {
                    lavender: 'var(--color-fst-lavender)',
                    purple: 'var(--color-fst-purple)',
                    deep: 'var(--color-purple-deep)',
                    graphite: 'var(--color-graphite)',
                    charcoal: 'var(--color-charcoal)',
                    silver: 'var(--color-silver)',
                    soft: 'var(--color-soft-white)',
                    red: 'var(--color-accent-red)',
                    yellow: 'var(--color-accent-yellow)',
                    cyan: 'var(--color-accent-cyan)',
                },
            },
        },
    },

    plugins: [forms],
};
