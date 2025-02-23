/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./views/**/*.clbr.html"],

    theme: {
        extend: {
            //
        },
    },

    plugins: [
        require("@tailwindcss/typography"),
        require("@tailwindcss/forms"),
        require("@tailwindcss/aspect-ratio"),
    ],
};
