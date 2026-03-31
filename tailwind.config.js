/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './template-parts/**/*.php',
    './woocommerce/**/*.php',
    './assets/js/**/*.js',
    './node_modules/flowbite/**/*.js',
  ],
  safelist: ['inline-flex', 'items-center', '!h-12', 'w-auto'],
  theme: {
    extend: {},
  },
  plugins: [require('flowbite/plugin')],
};
