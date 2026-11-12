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
        // Admin interface typeface (public site keeps Figtree)
        ui: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        'tpc-primary': 'rgb(0 128 0 / <alpha-value>)',
        'tpc-secondary': 'rgb(0 100 0 / <alpha-value>)',
        'tpc-ink': 'rgb(17 24 39 / <alpha-value>)',
        'tpc-accent': 'rgb(134 239 172 / <alpha-value>)',

        // ── Admin design system: white + green ─────────────────────
        brand: {
          DEFAULT: '#008000', // Primary green
          dark: '#006400',    // Hover / emphasis
          light: '#90EE90',   // Sparing highlights only (never text on white)
          50: '#F2F8F2',
          100: '#E3F1E3',
          200: '#C5E3C5',
        },
        ui: {
          bg: '#F8FAF8',      // Main background
          'bg-2': '#F1F5F1',  // Secondary background
          ink: '#1F2937',     // Primary text
          muted: '#6B7280',   // Secondary text
          line: '#E5E7EB',    // Borders
        },

        // Legacy neomorphism tokens (kept so nothing else breaks)
        'neo-bg': '#EEF2F6',
        'neo-surface': '#F4F7FB',
        'neo-surface-dim': '#E7ECF2',
        'neo-ink': '#2B3648',
      },
      boxShadow: {
        'ui-xs': '0 1px 2px rgba(16,24,40,0.05)',
        'ui-sm': '0 1px 3px rgba(16,24,40,0.08), 0 1px 2px rgba(16,24,40,0.04)',
        'ui-lg': '0 16px 40px -12px rgba(16,24,40,0.22), 0 4px 10px -4px rgba(16,24,40,0.08)',

        // Legacy neomorphism shadows
        'neo-sm': '4px 4px 10px rgba(163,177,198,0.50), -4px -4px 10px rgba(255,255,255,0.90)',
        'neo': '8px 8px 16px rgba(163,177,198,0.55), -8px -8px 16px rgba(255,255,255,0.90)',
        'neo-lg': '14px 14px 32px rgba(163,177,198,0.50), -14px -14px 32px rgba(255,255,255,0.95)',
        'neo-hover': '10px 10px 22px rgba(163,177,198,0.55), -10px -10px 22px rgba(255,255,255,0.95)',
        'neo-inset-sm': 'inset 2px 2px 6px rgba(163,177,198,0.50), inset -2px -2px 6px rgba(255,255,255,0.90)',
        'neo-inset': 'inset 4px 4px 10px rgba(163,177,198,0.55), inset -4px -4px 10px rgba(255,255,255,0.90)',
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
