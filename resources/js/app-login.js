require('./bootstrap');

import Vue from 'vue';
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import axios from 'axios';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router'

import routes from './routes-login';

Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(VueAxios, axios);
Vue.use(VueRouter);

const router = new VueRouter({
	mode: 'history',
	routes,
});

//Main pages
import App from './views/app-login.vue'

const app = new Vue({
    router,
    el: '#app',
    components: { App }
});
