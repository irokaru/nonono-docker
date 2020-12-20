export default {
  /**
   * パスのアクセスが正しいか判断する
   * @param {object} route
   * @returns {boolean}
   */
  validateRoute(route) {
    const mode = this.getMode(route);
    const key  = this.getKey(route);
    const page = this.getPage(route);

    if (mode === '' && key === '' && page === '') {
      return true;
    }

    // -------------------------------------------------------
    // 記事リスト
    if (mode && key === '' && page === '') {
      const modeNumber = this.toNumber(mode);

      if (Validator.isInteger(modeNumber) && Validator.minNumber(modeNumber, 1)) {
        return true;
      }
    }

    // -------------------------------------------------------
    // 記事詳細＆カテゴリリスト
    if (mode && key && page === '') {
      const keyNumber = this.toNumber(key);

      if (mode === 'post' && Validator.isInteger(keyNumber) && Validator.minNumber(keyNumber, 1)) {
        return true;
      }

      if (mode === 'category' && Validator.maxLength(key, 30)) {
        return true;
      }
    }

    // -------------------------------------------------------
    // カテゴリリスト
    if (mode && key && page) {
      const pageNumber = this.toNumber(page);

      if (mode === 'category' && Validator.maxLength(key, 30) && Validator.isInteger(pageNumber) && Validator.minNumber(pageNumber, 1)) {
        return true;
      }
    }

    return false;
  },

  /**
   * モードを基にコンポーネント名とページネーションの有無を返す
   * @param {object} route
   * @returns {[component, paginate]}
   */
  mainComponentName(route) {
    const mode = this.getMode(route);

    const modes = {
      post:     ['BlogPostDetail',   false],
      category: ['BlogCategoryList', true],
      _other:   ['BlogPostList',     true],
    };

    if (!Validator.hasKeyInObject(modes, mode)) {
      return modes._other;
    }

    return modes[mode];
  },

  /**
   * 現在表示中のページは記事一覧かどうかを返す
   * @param {string} view
   * @returns {boolean}
   */
  isPostList(view) {
    return view === 'BlogPostList';
  },

  /**
   * 現在表示中のページはカテゴリ記事一覧かどうかを返す
   * @param {string} view
   * @returns {boolean}
   */
  isCategoryList(view) {
    return view === 'BlogCategoryList';
  },

  /**
   * ルートを基に現在のページを返す
   * @param {object} route
   * @param {string} view
   * @returns {number}
   */
  getPageNumber(route, view) {
    if (this.isPostList(view)) {
      return this.getMode(route) ? parseInt(this.getMode(route)) : 1;
    }

    if (this.isCategoryList(view)) {
      return this.getPage(route) ? parseInt(this.getPage(route)) : 1;
    }

    return null;
  },

  /**
   * 現在のページを基にリンクを返す
   * @param {object} route
   * @param {string} view
   * @param {number} number
   * @returns {string}
   */
  getPageLink(route, view, number) {
    if (!number) {
      return '';
    }

    if (this.isPostList(view)) {
      return `/blog/${number}`;
    }

    if (this.isCategoryList(view)) {
      return `/blog/category/${this.getKey(route)}/${number}`;
    }

    return '/blog';
  },

  /**
   * ルートが変更されたのか確認するやつ
   * @param {object} route
   * @param {string} view
   * @param {object} paginate
   * @returns {boolean}
   */
  checkRouteChange(route, view, paginate) {
    return view === this.mainComponentName(route)[0] &&
           paginate.current === this.getPageNumber(route, view);
  },

  /**
   * 数値化する
   * @param {string} val
   * @returns {number}
   */
  toNumber(val) {
    return val.match(/^[0-9]+$/) ? Number(val) : -1;
  },

  /**
   * モードを返す
   * @param {object} route
   * @returns {string}
   */
  getMode(route) {
    return route.params.mode || '';
  },

  /**
   * キーを返す
   * @param {object} route
   * @returns {string}
   */
  getKey(route) {
    return route.params.key || '';
  },

  /**
   * ページを返す
   * @param {object} route
   * @returns {string}
   */
  getPage(route) {
    return route.params.page || '';
  },
};
