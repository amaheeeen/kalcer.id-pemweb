/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'sans-serif'],
        syne: ['Syne', 'sans-serif'],
      },
      keyframes: {
        'shockwave': {
          '0%': { transform: 'scale(0.5)', opacity: '1' },
          '100%': { transform: 'scale(2.5)', opacity: '0' },
        },
        'blob': {
          '0%, 100%': { borderRadius: '60% 40% 30% 70% / 60% 30% 70% 40%' },
          '50%': { borderRadius: '30% 60% 70% 40% / 50% 60% 30% 60%' },
        }
      },
      animation: {
        'shockwave': 'shockwave 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'blob': 'blob 10s infinite alternate',
      }
    },
  },
  plugins: [],
}