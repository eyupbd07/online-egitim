import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import DefaultLayout from './layouts/default.vue'

// 🔥 1. BOOTSTRAP DOSYASINI BURAYA ÇAĞIRIYORUZ (ÇOK ÖNEMLİ)
// Bu satır sayesinde Echo ve Reverb ayarları proje başlar başlamaz yüklenir.
import './bootstrap';

// --- STİLLER ---
// İkon CSS dosyasını en başa koyuyoruz
import '@mdi/font/css/materialdesignicons.css' 
import 'vuetify/styles'
import '@core-scss/template/index.scss'
import '@layouts/styles/index.scss'
import '../css/app.css';

// --- EKLENTİLER ---
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
// İkon setlerini doğru yerden çekiyoruz
import { aliases, mdi } from 'vuetify/iconsets/mdi' 
import VueApexCharts from 'vue3-apexcharts'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// --- VUETIFY AYARLARI ---
const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: '#9155FD',
                    secondary: '#8A8D93',
                    success: '#56CA00',
                    info: '#16B1FF',
                    warning: '#FFB400',
                    error: '#FF4C51',
                    background: '#ffffffff',
                    surface: '#ffffffff',
                },
            },
        },
    },
})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
        page.then((module) => {
            if (module.default.layout === undefined) {
                module.default.layout = DefaultLayout
            }
        })
        return page
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(VueApexCharts)

        app.mount(el)
    },
    progress: { color: '#9155FD' },
});