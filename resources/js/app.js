import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';
import AppSidebar from './components/AppSidebar.vue';
import { SidebarProvider, SidebarInset } from './components/ui/sidebar/index.ts';
import SectionCards from './components/SectionCards.vue';
import ChartAreaInteractive from './components/ChartAreaInteractive.vue';
import SiteHeader from './components/SiteHeader.vue';
import DataTable from './components/DataTable.vue';
import { toast } from 'vue-sonner';
import Sonner from './components/ui/sonner/Sonner.vue';

const app = createApp({});

window.toast = toast;

// sidebar
app.component('Sonner', Sonner);
app.component('section-cards', SectionCards);
app.component('chart-area-interactive', ChartAreaInteractive);
app.component('site-header', SiteHeader);
app.component('app-sidebar', AppSidebar);
app.component('sidebar-provider', SidebarProvider);
app.component('sidebar-inset', SidebarInset);
app.component('data-table', DataTable);

app.mount('#app');
