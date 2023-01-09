import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default ({ mode }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };

    return defineConfig({
        server: {
            host: process.env.VITE_APP_SERVER,
        },
        plugins: [
            laravel({
                input: "resources/js/app.js",
                ssr: "resources/js/ssr.js",
                refresh: true,
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
        ssr: {
            noExternal: ["@inertiajs/server"],
        },
        build: {
            rollupOptions: {
                external: [
                    "vue-recaptcha",
                    "@vueup/vue-quill",
                    "@vueup/vue-quill/dist/vue-quill.snow.css",
                ],
            },
        },
    });
};
