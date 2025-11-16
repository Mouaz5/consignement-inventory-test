import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';
import '../css/app.css';
import './bootstrap';

const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });

createInertiaApp({
    resolve: name => pages[`./Pages/${name}.vue`],
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin);
        
        // Set up token from props on each page load
        if (props.initialPage.props.auth?.token) {
            const token = props.initialPage.props.auth.token;
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            localStorage.setItem('auth_token', token);
        }
        
        app.mount(el);
    },
});

InertiaProgress.init();
