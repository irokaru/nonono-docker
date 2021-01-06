require('./bootstrap');

import Vue from 'vue';
import VueAuth from '@websanova/vue-auth';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import auth from './auth';
import router from './router';
import Store from './plugins/Store';

import { library }         from '@fortawesome/fontawesome-svg-core';
import { fas }             from '@fortawesome/free-solid-svg-icons';
import { far }             from '@fortawesome/free-regular-svg-icons';
import { fab }             from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import Main from './Main';

window.Vue = Vue;

// Set Vue router
Vue.router = router;
Vue.use(VueRouter);

// Set Vue authentication
Vue.use(VueAxios, axios);
Vue.use(VueAuth, auth);

// Set fontawesome
library.add(fas, far, fab);
Vue.component('v-fa', FontAwesomeIcon);

// Set Nonono Store
Vue.use(Store);

const app = new Vue({
  el: '#app',
  router,
  render: h => h(Main),
});
