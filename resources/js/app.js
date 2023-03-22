import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import Information from "./Components/Information.vue";

const app = createApp({});
app.component('Information', Information);
app.mount('#information');
