import { createRouter, createWebHistory } from 'vue-router';

const loadView = (view) => {
  return () => import(`./pages/${view}.vue`);
};

// ------------------------------------------------

const DEFAULT_TITLE = 'ののの茶屋';

// ------------------------------------------------
// Routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: loadView('Home'),
    meta: {
      auth: undefined,
    }
  },
  {
    path: '/products',
    name: 'products',
    component: loadView('Products'),
    meta: {
      auth: undefined,
      title: 'ぷろだくと',
    }
  },
  {
    path: '/blog',
    name: 'blog',
    component: loadView('Blog'),
    meta: {
      auth: undefined,
      title: 'にっき',
    }
  },
  {
    path: '/blog/:mode',
    component: loadView('Blog'),
    meta: {
      auth: undefined,
      title: 'にっき',
    }
  },
  {
    path: '/blog/:mode/:key',
    name: 'blog-any',
    component: loadView('Blog'),
    meta: {
      auth: undefined,
      title: 'にっき',
    }
  },
  {
    path: '/blog/:mode/:key/:page',
    name: 'blog-any-page',
    component: loadView('Blog'),
    meta: {
      auth: undefined,
      title: 'にっき',
    }
  },
  {
    path: '/admin',
    name: 'admin',
    component: loadView('admin/Admin'),
    meta: {
      auth: true,
    },
  },
  {
    path: '/login',
    name: 'admin.login',
    component: loadView('admin/Login'),
    meta: {
      auth: false,
    }
  },
  {
    path: '/admin/history',
    name: 'admin.history',
    component: loadView('admin/History'),
    meta: {
      auth: true,
    }
  },
  {
    path: '/admin/game',
    name: 'admin.game',
    component: loadView('admin/Game'),
    meta: {
      auth: true,
    },
  },
  {
    path: '/admin/blog',
    name: 'admin.blog',
    component: loadView('admin/Blog'),
    meta: {
      auth: true,
    },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'error.404',
    component: () => import('./pages/error/404'),
    meta: {
      auth: undefined,
      title: '404 Not Found',
    },
  }
];

// ------------------------------------------------

const router = createRouter({
  history: createWebHistory(),
  routes:  routes,
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
