import ApiTools from '@/api/ApiTools';

const HistoryApi = {
  /**
   * 更新履歴を取得する
   * @returns {array}
   */
  get: async () => {
    const api = axios.get('/api/history');

    return api.then(res => {
      return {
        status: 'success',
        history: res.data,
      };
    }).catch(e => {
      return ApiTools.genelateErrorParams(e);
    });
  },
  /**
   *  更新履歴を追加する
   * @param {object} history history object
   */
  store: async (history) => {
    const api = axios.post('/api/history', history);

    return api.then(res => {
      return res.data;
    }).catch(e => {
      return ApiTools.genelateErrorParams(e);
    });
  },
  /**
   *  更新履歴を更新する
   * @param {object} history history object
   */
  update: async (history) => {
    const api = axios.put('/api/history', history);

    return api.then(res => {
      return res.data;
    }).catch(e => {
      return ApiTools.genelateErrorParams(e);
    });
  },
  /**
   *  更新履歴を更新する
   * @param {int} id history id
   */
  delete: async (id) => {
    const api = axios.delete(`/api/history/${id}`);

    return api.then(res => {
      return res.data;
    }).catch(e => {
      return ApiTools.genelateErrorParams(e);
    });
  },
};

export default HistoryApi;
