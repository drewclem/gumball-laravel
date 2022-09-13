const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    mode: "jit",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        fontFamily: {
            display: ["Inter", "Arial", "sans-serif"],
            body: ["Hind", "sans-serif"],
        },

        extend: {
            colors: {
                red: {
                    500: "#D94B4B",
                    600: "#BE5252",
                },
                blue: {
                    100: "#E7F6FF",
                    500: "#1E8AB9",
                },
                green: {
                    500: "#3F9E82",
                    600: "#3A8F76",
                },
                gray: {
                    50: "#f9f9f9",
                    100: "#f8f8f8",
                },
                black: "#1E1E1E",
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
