require('./bootstrap');

// Import modules...
import {createApp, h} from 'vue';
import {App as InertiaApp, plugin as InertiaPlugin} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';

// import { createApp } from 'vue'
import ElementPlus from 'element-plus';
import 'element-plus/lib/theme-chalk/index.css';
// import App from './App.vue';

// const app = createApp(App)
// app.use(ElementPlus)
// app.mount('#app')

const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .use(ElementPlus)
    .mixin({methods: {route}})
    .use(InertiaPlugin)
    .mount(el);

InertiaProgress.init({color: '#4B5563'});
