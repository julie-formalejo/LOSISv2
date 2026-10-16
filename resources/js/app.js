import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

const pages = import.meta.glob('./pages/**/*.vue', {
    eager: true,
});

createInertiaApp({
    title: (title) => {
        return title ? `${title} - LOSISv2` : 'LOSISv2';
    },

    resolve: (name) => {
        const normalizedName = name.replace(/\\/g, '/').toLowerCase();

        const pagePath = Object.keys(pages).find((path) => {
            const normalizedPath = path
                .replace(/\\/g, '/')
                .replace(/^\.\/pages\//, '')
                .replace(/\.vue$/, '')
                .toLowerCase();

            return normalizedPath === normalizedName;
        });

        if (!pagePath) {
            throw new Error(`Vue page not found: ${name}`);
        }

        return pages[pagePath];
    },

    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(App, props),
        })
            .use(plugin)
            .mount(el);
    },
});
