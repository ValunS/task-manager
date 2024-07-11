import './bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';

import { createApp } from 'vue';
import App from './App.vue';
import axios from '@/axios';

import VueAxios from 'vue-axios'
import router from './routes'

// import ExampleComponent from './components/ExampleComponent.vue';
// app.component('example-component', ExampleComponent);

createApp(App).use(router).use(VueAxios, axios).mount('#app');