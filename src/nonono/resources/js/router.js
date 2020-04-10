import VueRouter from 'vue-router';
// Pages
import Home from './pages/Home';
import Admin from './pages/admin/Admin';
import AdminLogin from './pages/admin/Login';
import AdminHistory from './pages/admin/History';

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
    name: 'admin.login',
    component: AdminLogin,
    meta: {
      auth: false,
    }
  },
  {
    path: '/admin/history',
    name: 'admin.history',
    component: AdminHistory,
    meta: {
      auth: true,
    }
  },
];

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
});

export default router;
