import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/eventGroup/create.js",
                "resources/js/eventGroup/edit.js",
                "resources/js/eventGroup/register.js",
                "resources/js/event/register.js",
            ],
            refresh: true,
        }),
    ],
});
