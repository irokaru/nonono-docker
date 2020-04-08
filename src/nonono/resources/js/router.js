import VueRouter from 'vue-router';
// Pages
import Home from './pages/Home';
import Login from './pages/admin/Login';
import Admin from './pages/admin/Admin';

// Routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: {
      auth: undefined,
    }
  },
  {
    path: '/admin',
    name: 'admin',
    component: Admin,
    meta: {
      auth: true,
    }
  },
  {
    path: '/admin/login',
    name: 'login',
    component: Login,
    meta: {
      auth: false,
    }
  },
];

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
});

export default router;
