export default {
  /**
   * ログイン処理
   * @param {Vue} app
   * @param {object} data
   */
  login(app, data) {
    app.$auth.login({
      data: data,
      success: (res) => {
        app.success = true;
      },
      error: (res) => {
        app.has_error = true;
        app.error = res.response.data.error;
      },
      rememberMe: false,
    });
  },

  /**
   * ログアウト処理
   * @param {Vue} app
   */
  logout(app) {
    app.$auth.logout({
      data: null,
      success: (res) => {
        app.success = true;
      },
      error: (res) => {
        app.has_error = true;
        app.error = res.response.data.error;
      },
    });
  },
}
