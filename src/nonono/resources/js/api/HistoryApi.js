export default {
  /**
   * 更新履歴を取得する
   * @returns {Promise<AxiosResponse<any>>}
   */
  get: () => {
    if (Vue.$hasStore('$history')) {
      return {data: Vue.$getStore('$history')};
    }
    return axios.get('/api/history');
  },

  /**
   *  更新履歴を追加する
   * @param {object} history history object
   * @returns {Promise<AxiosResponse<any>>}
   */
  store: (history) => {
    return axios.post('/api/history', history);
  },

  /**
   *  更新履歴を更新する
   * @param {object} history history object
   * @returns {Promise<AxiosResponse<any>>}
   */
  update: (history) => {
    return axios.put('/api/history', history);
  },

  /**
   *  更新履歴を削除する
   * @param {number} id history id
   * @returns {Promise<AxiosResponse<any>>}
   */
  delete: (id) => {
    return axios.delete(`/api/history/${id}`);
  },
};
