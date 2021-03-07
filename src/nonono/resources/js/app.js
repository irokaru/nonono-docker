require('./bootstrap');

import { createApp } from 'vue';

import VueAxios from 'vue-axios';
import router   from './router';

import auth        from './plugins/Auth';
import Store       from './plugins/Store';
import WindowState from './plugins/WindowState';

import { library }         from '@fortawesome/fontawesome-svg-core';
import { fas }             from '@fortawesome/free-solid-svg-icons';
import { far }             from '@fortawesome/free-regular-svg-icons';
import { fab }             from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import Main from './Main';

const app = createApp(Main);

window.Vue = app;

// Set Vue router
app.router = router;
app.use(router);

// Set Vue authentication
app.use(VueAxios, axios);
app.use(auth);

// Set fontawesome
library.add(fas, far, fab);
app.component('v-fa', FontAwesomeIcon);

// Set Nonono Store
app.use(Store);

// Set window state
app.use(WindowState);

app.mount('#app');
