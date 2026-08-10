import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{js,jsx,ts,tsx}',
    './vendor/livewire/livewire/resources/views/*.blade.php',
    './storage/framework/views/*.php',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Near-black identity scale — brand-900 is the hero color
        brand: {
          50: '#F7F7F7',
          100: '#EDEDED',
          200: '#E2E2E2',
          300: '#D4D4D4',
          400: '#A3A3A3',
          500: '#737373',
          600: '#525252',
          700: '#404040',
          800: '#262626',
          900: '#111111',
          950: '#0A0A0A',
        },
        // Single status color: unread badges / notification counts / online-status
        accent: {
          50: '#F0FDF4',
          100: '#DCFCE7',
          500: '#22C55E',
          600: '#16A34A',
          700: '#15803D',
          DEFAULT: '#22C55E',
        },
      },
      spacing: {
        // Shared density tokens (Phase 4a): 12-16px paddings, 1px hairlines
        hairline: '1px',
        card: '14px',
        panel: '16px',
        avatar: '12px',
      },
    },
  },
  plugins: [],
}