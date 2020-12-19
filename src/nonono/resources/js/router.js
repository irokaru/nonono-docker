import VueRouter from 'vue-router';

// ------------------------------------------------

const DEFAULT_TITLE = 'ののの茶屋';

// ------------------------------------------------
// Routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('./pages/Home'),
    meta: {
      auth: undefined,
    }
  },
  {
    path: '/products',
    name: 'products',
    component: () => import('./pages/Products'),
    meta: {
      auth: undefined,
      title: 'ぷろだくと',
    }
  },
  {
    path: '/blog',
    name: 'blog',
    component: () => import('./pages/Blog'),
    meta: {
      auth: undefined,
      title: 'ぶろぐ',
    }
  },
  {
    path: '/admin',
    name: 'admin',
    component: () => import('./pages/admin/Admin'),
    meta: {
      auth: true,
    }
  },
  {
    path: '/login',
    name: 'admin.login',
    component: () => import('./pages/admin/Login'),
    meta: {
      auth: false,
    }
  },
  {
    path: '/admin/history',
    name: 'admin.history',
    component: () => import('./pages/admin/History'),
    meta: {
      auth: true,
    }
  },
  {
    path: '/admin/game',
    name: 'admin.game',
    component: () => import('./pages/admin/Game'),
    meta: {
      auth: true,
    },
  },
  {
    path: '/admin/blog',
    name: 'admin.blog',
    component: () => import('./pages/admin/Blog'),
    meta: {
      auth: true,
    },
  },
  {
    path: '*',
    name: 'error.404',
    component: () => import('./pages/error/404'),
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
