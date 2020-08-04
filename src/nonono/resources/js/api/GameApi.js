export default {
  /**
   * ゲーム一覧を取得する
   * @returns {Promise<AxiosResponse<any>>}
   */
  get: () => {
    if (Vue.$hasStore('$games')) {
      return {data: Vue.$getStore('$games')};
    }
    return axios.get('/api/games');
  },

  /**
   * ゲーム一覧を取得する(管理者用)
   * @returns {Promise<AxiosResponse<any>>}
   */
  getAll: () => {
    return axios.get('/api/games/all');
  },

  /**
   * ゲーム追加
   * @param {object} data
   * @returns {Promise<AxiosResponse<any>>}
   */
  store: (data) => {
    return axios.post('/api/games', data);
  },

  /**
   * ゲーム更新
   * @param {object} data
   * @returns {Promise<AxiosResponse<any>>}
   */
  update: (data) => {
    return axios.post('/api/games', data, {
      headers: {
        'content-type': 'multipart/form-data',
        'X-HTTP-Method-Override': 'PUT',
      },
    });
  },
};
