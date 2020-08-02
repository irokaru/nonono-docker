require('./bootstrap');

import 'es6-promise/auto';
import Vue from 'vue';
import VueAuth from '@websanova/vue-auth';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import auth from './auth';
import router from './router';

import Main from './Main';

window.Vue = Vue;

// Set Vue router
Vue.router = router;
Vue.use(VueRouter);

// Set Vue authentication
Vue.use(VueAxios, axios);
Vue.use(VueAuth, auth);

const app = new Vue({
  el: '#app',
  router,
  render: h => h(Main),
});
