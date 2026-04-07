/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.php",
    "./scripts.js",
    "./src/js/*.js",
    "./src/**/*.php",
    "./src/views/*.php",
    "./*.php",
    "*.php"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}