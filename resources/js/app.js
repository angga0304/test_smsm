import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { createPinia } from 'pinia'
import { useDarkModeStore } from '@/Stores/darkMode.js'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import DataTablesLib from 'datatables.net'; 
import DataTable from 'datatables.net-vue3';
import axios from 'axios';
import VueAxios from 'vue-axios';
import vSelect  from "vue-select";
import { VueEditor } from "vue2-editor";

DataTable.use(DataTablesLib);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const pinia = createPinia();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueEditor)
            .use(ZiggyVue, Ziggy)
            .use(VueAxios, axios)
            .component('DataTable', DataTable)
            .component("v-select", vSelect) 
            .mount(el);
    },
    progress: {
        color: '#0076ff',
    },
});

const darkModeStore = useDarkModeStore(pinia)

if (
   (!localStorage['darkMode'] && window.matchMedia('(prefers-color-scheme: dark)').matches) ||
   localStorage['darkMode'] === '1'
 ) {
   darkModeStore.set(true)
}