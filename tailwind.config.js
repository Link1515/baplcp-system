/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        container: {
            center: true,
            padding: "1rem",
        },
        extend: {
            colors: {
                primary: "#5768FF",
                disabled: "#B3B3B3",
            },
        },
    },
    plugins: [],
};
