export default {
  /**
   * ブログ一覧を取得する
   * @returns {Promise<AxiosResponse<any>>}
   */
  get: (page = 1) => {
    return axios.get(`/api/posts?page=${page}`);
  },

  /**
   * ブログの新着一覧を取得する
   * @param {number} len
   * @returns {Promise<AxiosResponse<any>>}
   */
  getLatests: (len = 4) => {
    return axios.get(`/api/posts/latest/${len}`);
  },

  /**
   * カテゴリを基にブログ一覧を取得する
   * @returns {Promise<AxiosResponse<any>>}
   */
  getAsCategory: (category, page = 1) => {
    return axios.get(`/api/posts/category/${category}?page=${page}`);
  },

  /**
   * カテゴリ一覧を表示する
   * @returns {Promise<AxiosResponse<any>>}
   */
  getCategories: () => {
    if (Vue.$hasStore('$posts.categories')) {
      return {data: Vue.$getStore('$posts.categories')};
    }
    return axios.get('/api/posts/categories');
  },

  /**
   * [管理者向け]ブログ一覧を取得する
   * @returns {Promise<AxiosResponse<any>>}
   */
  getAll: () => {
    return axios.get('/api/posts/all');
  },

  /**
   * 記事を取得する
   * @param {number} id post id
   * @returns {Promise<AxiosResponse<any>>}
   */
  show: (id) => {
    return axios.get(`/api/post/show/${id}`);
  },

  /**
   *  記事を追加する
   * @param {object} post post object
   * @returns {Promise<AxiosResponse<any>>}
   */
  store: (post) => {
    return axios.post('/api/posts', post);
  },

  /**
   *  記事を更新する
   * @param {object} post post object
   * @returns {Promise<AxiosResponse<any>>}
   */
  update: (post) => {
    return axios.put('/api/posts', post);
  },
};
