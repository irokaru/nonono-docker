export default {
  /**
   * パスのアクセスが正しいか判断する
   * @param {string} mode
   * @param {string} key
   * @param {string} page
   * @returns {boolean}
   */
  validateRoute(mode, key, page) {
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
   * @param {string} mode
   * @returns {[component, paginate]}
   */
  mainComponentName(mode) {
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
   * 数値化する
   * @param {string} val
   * @returns {number}
   */
  toNumber(val) {
    return val.match(/^[0-9]+$/) ? Number(val) : -1;
  },
};
