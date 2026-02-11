import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },

      // ✅ COLORS go here
      colors: {
        'tpc-primary': 'rgb(0 128 0 / <alpha-value>)',
        'tpc-secondary': 'rgb(0 100 0 / <alpha-value>)',
        'tpc-ink': 'rgb(17 24 39 / <alpha-value>)',
        'tpc-accent': 'rgb(134 239 172 / <alpha-value>)',
      },
    },
  },

  plugins: [forms],
};
