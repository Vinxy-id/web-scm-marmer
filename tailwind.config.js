module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./app/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        marble: {
          50: '#f8fafc',
          100: '#f1f5f9',
          500: '#64748b',
          800: '#1e293b',
          900: '#0f172a',
        },
        brand: {
          navy: '#1e3a8a',
          accent: '#3b82f6',
          gold: '#d97706',
          emerald: '#10b981',
        }
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
      }
    },
  },
  plugins: [],
}
