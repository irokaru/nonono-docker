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
   * @param {number} number
   * @param {array}
   */
  list(p, number) {
    if (!Validator.isInteger(p.current)) {
      return Array(number).fill('-');
    }

    const half = parseInt(number / 2);

    const start = () => {
      if (p.current - half < p.first) {
        return p.first;
      }

      if (p.last < p.current + half) {
        return p.current - number + 1;
      }

      return p.current - half;
    };

    const end = () => {
      if (start() + number - 1 > p.last) {
        return p.last;
      }

      return start() + number - 1;
    };

    return Array.from(
      {length: (end() - start() + 1)},
      (v, k) => k + start(),
    );
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
