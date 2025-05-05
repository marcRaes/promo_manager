/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  safelist: [
    'bg-green-100',
    'text-green-800',
    'border-green-300',
    'bg-red-100',
    'text-red-800',
    'border-red-300',
    'bg-blue-100',
    'text-blue-800',
    'border-blue-300',
    'bg-yellow-100',
    'text-yellow-800',
    'border-yellow-300',
    'bg-green-50',
    'bg-blue-50',
    'bg-yellow-50',
    'bg-red-200',
    'text-gray-500',
    'bg-green-200',
    'bg-yellow-200',
    'bg-red-500',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
