import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'
/**
 * Authentication configuration, some of the options can be override in method calls
 */
const config = {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: 'nonono-jwt-auth',
  tokenStore: ['localStorage'],

  // API endpoints used in Vue Auth.
  loginData: {
    url: '/api/auth/login',
    method: 'POST',
    redirect: '/admin',
    fetchUser: true
  },
  logoutData: {
    url: '/api/auth/logout',
    method: 'POST',
    redirect: '/',
    makeRequest: true
  },
  refreshData: {
    url: '/api/auth/refresh',
    method: 'GET',
    enabled: true,
    interval: 30
  },
  fetchData: {enabled: false},
}
export default config
