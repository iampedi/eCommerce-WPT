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
    extend: {
      colors: {
        primary: 'var(--color-primary)',
        secondary: 'var(--color-secondary)',
        'primary-soft': 'var(--color-primary-soft)',
        'secondary-soft': 'var(--color-secondary-soft)',
        'primary-deep': 'var(--color-primary-deep)',
        'secondary-deep': 'var(--color-secondary-deep)',
      },
    },
  },
  plugins: [require('flowbite/plugin')],
};
