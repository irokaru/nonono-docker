import VueRouter from 'vue-router';

// ------------------------------------------------
// Pages
import Home  from './pages/Home';
import Products from './pages/Products';
import Error404 from './pages/error/404';

import Admin        from './pages/admin/Admin';
import AdminLogin   from './pages/admin/Login';
import AdminHistory from './pages/admin/History';

// ------------------------------------------------

const DEFAULT_TITLE = 'ののの茶屋';

// ------------------------------------------------
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
    path: '/products',
    name: 'products',
    component: Products,
    meta: {
      auth: undefined,
      title: 'ぷろだくと',
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
    path: '/login',
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
  {
    path: '*',
    name: 'error.404',
    component: Error404,
    meta: {
      auth: undefined,
      title: '404 Not Found',
    },
  }
];

// ------------------------------------------------

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
});

router.beforeEach((to, from, next) => {
  if (!to.meta) return next();

  // set title
  const meta = to.meta;
  if (meta.title) {
    document.title = `${meta.title} - ${DEFAULT_TITLE}`;
  } else {
    document.title = DEFAULT_TITLE;
  }

  next();
});

export default router;
