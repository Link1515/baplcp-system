import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/season/create.js",
                "resources/js/season/edit.js",
                "resources/js/season/register.js",
                "resources/js/event/register.js",
            ],
            refresh: true,
        }),
    ],
});
