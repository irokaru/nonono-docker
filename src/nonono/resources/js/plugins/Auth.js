import {createAuth} from '@websanova/vue-auth/src/v3.js';
import bearer       from '@websanova/vue-auth/src/drivers/auth/bearer'
import axios        from '@websanova/vue-auth/src/drivers/http/axios.1.x'
import router       from '@websanova/vue-auth/src/drivers/router/vue-router.2.x'

/**
 * Authentication configuration, some of the options can be override in method calls
 */
export default (app) => {
  app.use(createAuth({
    plugins: {
      http:   app.axios,
      router: app.router,
    },
    drivers: {
      auth:             bearer,
      http:             axios,
      router:           router,
      tokenDefaultKey: 'nonono-jwt-auth',
      stores:          ['localStorage'],
    },
    options: {
      notFoundRedirect: {name: 'admin'},
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
        enabled: false,
      },
      fetchData: {
        enabled: false
      },
    },
  }));
};
