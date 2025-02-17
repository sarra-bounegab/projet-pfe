import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/css/**/*.css",
    ],
    theme: {
        extend: {},
    },
    plugins: [forms],  // Ne pas mettre `plugins: []` deux fois
};
