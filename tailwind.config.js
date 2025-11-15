/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  safelist: [
    'bg-red-100','text-red-800','dark:bg-red-900','dark:text-red-300',
    'bg-green-100','text-green-800','dark:bg-green-900','dark:text-green-300',
    'bg-blue-100','text-blue-800','dark:bg-blue-900','dark:text-blue-300',
    'bg-yellow-100','text-yellow-800','dark:bg-yellow-900','dark:text-yellow-300',
    'bg-purple-100','text-purple-800','dark:bg-purple-900','dark:text-purple-300',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
