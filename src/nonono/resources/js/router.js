import VueRouter from 'vue-router'
// Pages
import Home from './pages/Home'
import Login from './pages/admin/Login'

// Routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: {
      auth: undefined
    }
  },
  {
    path: '/admin/login',
    name: 'login',
    component: Login,
    meta: {
      auth: false
    }
  },
];

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
});

export default router
