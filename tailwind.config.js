import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
      colors: {
        'tpc-primary': 'rgb(0 128 0 / <alpha-value>)',
        'tpc-secondary': 'rgb(0 100 0 / <alpha-value>)',
        'tpc-ink': 'rgb(17 24 39 / <alpha-value>)',
        'tpc-accent': 'rgb(134 239 172 / <alpha-value>)',

        // Neomorphism (admin panel) tokens
        'neo-bg': '#EEF2F6',
        'neo-surface': '#F4F7FB',
        'neo-surface-dim': '#E7ECF2',
        'neo-ink': '#2B3648',
      },
      boxShadow: {
        // Raised ("flat") soft-UI shadows
        'neo-sm': '4px 4px 10px rgba(163,177,198,0.50), -4px -4px 10px rgba(255,255,255,0.90)',
        'neo': '8px 8px 16px rgba(163,177,198,0.55), -8px -8px 16px rgba(255,255,255,0.90)',
        'neo-lg': '14px 14px 32px rgba(163,177,198,0.50), -14px -14px 32px rgba(255,255,255,0.95)',
        'neo-hover': '10px 10px 22px rgba(163,177,198,0.55), -10px -10px 22px rgba(255,255,255,0.95)',
        // Pressed / inset shadows
        'neo-inset-sm': 'inset 2px 2px 6px rgba(163,177,198,0.50), inset -2px -2px 6px rgba(255,255,255,0.90)',
        'neo-inset': 'inset 4px 4px 10px rgba(163,177,198,0.55), inset -4px -4px 10px rgba(255,255,255,0.90)',
        // Directional shadow for the sidebar edge: crisp hairline + soft glow so it reads as a distinct panel
        'neo-edge': '1px 0 0 rgba(15,23,42,0.06), 12px 0 28px -4px rgba(163,177,198,0.55)',
      },
      borderRadius: {
        '4xl': '1.75rem',
        '5xl': '2.25rem',
      },
    },
  },

  plugins: [forms, typography],
};
