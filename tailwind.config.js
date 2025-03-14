import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                'sans': ['Poppins', 'sans-serif'],
                'heading': ['Montserrat', 'sans-serif'],
            },
            colors: {
                'primary': {
                    DEFAULT: '#a1cd2f', // atlantis
                    'dark': '#6c8c28',  // wasabi
                    'light': '#c5e96c',
                },
                'secondary': {
                    DEFAULT: '#4a9c3f', // apple
                    'dark': '#1f5020',  // everglade
                    'light': '#7fc275',
                },
                'accent': '#0f3708', // dark-fern (color del logo)
                'background': {
                    DEFAULT: '#f9faf5', // light background
                    'dark': '#042404'   // pine-tree for dark mode
                },
                'text': {
                    DEFAULT: '#243c07', // pine-tree
                    'light': '#f9faf5', // light text
                    'accent': '#44731d', // dell
                }
            },
            animation: {
                'pulse': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-slow': 'bounce 3s infinite',
                'wiggle': 'wiggle 1s ease-in-out infinite',
            },
            keyframes: {
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-3deg)' },
                    '50%': { transform: 'rotate(3deg)' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
