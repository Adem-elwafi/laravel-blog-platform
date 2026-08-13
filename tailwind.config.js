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
      // Background presets (background-customization feature). Keys must stay
      // in sync with App\Support\BackgroundPresets::ALL in BOTH apps.
      // NOTE: keys must be FLAT (Tailwind v3 does not generate utilities from
      // nested backgroundImage objects) → `bg-backgrounds-obsidian` etc.
      backgroundImage: {
        'backgrounds-obsidian': 'linear-gradient(180deg, #161616 0%, #111111 100%)',
        'backgrounds-graphite': 'linear-gradient(160deg, #262626 0%, #111111 60%, #0a0a0a 100%)',
        'backgrounds-slate':    'linear-gradient(160deg, #1f2937 0%, #111827 55%, #0b1220 100%)',
        'backgrounds-midnight': 'linear-gradient(160deg, #1e3a8a 0%, #172554 55%, #0f1a3d 100%)',
        'backgrounds-ocean':    'linear-gradient(160deg, #164e63 0%, #0e3a4d 55%, #0a2b3a 100%)',
        'backgrounds-emerald':  'linear-gradient(160deg, #065f46 0%, #064e3b 55%, #06302c 100%)',
        'backgrounds-plum':     'linear-gradient(160deg, #4c1d95 0%, #3b1466 55%, #2a0f4d 100%)',
        'backgrounds-ember':    'linear-gradient(160deg, #78350f 0%, #5b2a0e 55%, #3d1d0a 100%)',
      },
    },
  },
  safelist: [
    // Guarantee the preset utilities exist even though the preset key is
    // applied dynamically (e.g. `bg-backgrounds-{{ $user->theme_background }}`).
    { pattern: /bg-backgrounds-(obsidian|graphite|slate|midnight|ocean|emerald|plum|ember)/ },
  ],
  plugins: [],
}