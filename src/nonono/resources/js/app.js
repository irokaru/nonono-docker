require('./bootstrap');

import 'es6-promise/auto';
import Vue from 'vue';
import VueAuth from '@websanova/vue-auth';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import auth from './auth';
import router from './router';

// Set Vue router
Vue.router = router;
Vue.use(VueRouter);

// Set Vue authentication
Vue.use(VueAxios, axios);
axios.defaults.baseURL = `${process.env.MIX_APP_URL}`;
Vue.use(VueAuth, auth)

window.Vue = Vue;

const app = new Vue({
    el: '#app',
    router,
});
