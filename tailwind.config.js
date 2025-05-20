const forms = require('@tailwindcss/forms');

module.exports = {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./resources/css/**/*.css",
  ],
  theme: {
    extend: {},
  },
  plugins: [forms],
};
