/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./resources/**/*.{pug,scss}'],

  theme: {
    extend: {
      //
    },
  },

  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/line-clamp'),
    require('@tailwindcss/aspect-ratio'),
  ],
}
