import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';
import AppSidebar from './components/AppSidebar.vue';
import { SidebarProvider, SidebarInset } from './components/ui/sidebar/index.ts';
import SectionCards from './components/SectionCards.vue';
import ChartAreaInteractive from './components/ChartAreaInteractive.vue';
import SiteHeader from './components/SiteHeader.vue';
import DataTable from './components/DataTable.vue';

const app = createApp({});

// sidebar
app.component('section-cards', SectionCards);
app.component('chart-area-interactive', ChartAreaInteractive);
app.component('site-header', SiteHeader);
app.component('app-sidebar', AppSidebar);
app.component('sidebar-provider', SidebarProvider);
app.component('sidebar-inset', SidebarInset);
app.component('data-table', DataTable);

app.mount('#app');
