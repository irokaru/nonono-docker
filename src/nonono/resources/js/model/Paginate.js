export default {
  model() {
    return {
      current: null,
      first:   null,
      last:    null,
      next:    null,
      prev:    null,
    };
  },

  /**
   * ページネーション用オブジェクトを返す
   * @param {object} links
   * @param {object} meta
   * @returns {object}
   */
  setup(links, meta) {
    let paginate = this.model();

    paginate.current = meta.current_page || null;
    paginate.first   = this._getPageAsUrl(links.first || '');
    paginate.last    = this._getPageAsUrl(links.last || '');
    paginate.next    = this._getPageAsUrl(links.next || '');
    paginate.prev    = this._getPageAsUrl(links.prev || '');

    return paginate;
  },

  /**
   * ページネーション用の数字配列
   * @param {{current: number, first: number, last: number, next: number, prev: number}} p
   * @param {number} length
   * @param {array}
   */
  list(p, length) {
    if (!Validator.isObject(p)) {
      return [];
    }

    if (!Validator.isInteger(p.current)) {
      return Array(length).fill('-');
    }

    const pageLength = p.last - p.first + 1;

    if (pageLength < length) {
      return Array.from({length: pageLength}, (k, v) => v + p.first);
    }

    const half   = parseInt(length / 2);
    const isEven = Boolean(length % 2);

    if (p.current - half < p.first) {
      return Array.from({length: length}, (k, v) => v + p.first);
    }

    if (p.current + half > p.last) {
      return Array.from({length: length}, (k, v) => p.last - length + v + 1);
    }

    const start = p.current - half;

    return Array.from({length: length}, (k, v) => v + start);
  },

  /**
   * URLを基にページ番号を抜き出す
   * @param {string} url
   * @returns {string}
   */
  _getPageAsUrl(url) {
    const ret = url.match(/\?page=(\d+)$/);
    return ret !== null ? Number(ret[1]) : null;
  },
};
