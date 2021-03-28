export default class {
  /**
   * 初期化処理
   * @param {object} links
   * @param {object} meta
   * @returns {this|null}
   */
  init(links, meta) {
    const linksKeys = [
      'first', 'last', 'next', 'prev',
    ];

    for (const key of linksKeys) {
      if (!Validator.hasKeyInObject(links, key) ||
         (!Validator.isString(links[key]) && links[key] !== null)) {
        return null;
      }
    }

    if (!Validator.hasKeyInObject(meta, 'current_page') || !Validator.isInteger(meta.current_page)) {
      return null;
    }

    this.current = meta.current_page;
    this.first   = this._getPageAsUrl(links.first);
    this.last    = this._getPageAsUrl(links.last);
    this.next    = this._getPageAsUrl(links.next);
    this.prev    = this._getPageAsUrl(links.prev);

    return this;
  };

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
  };

  /**
   * URLを基にページ番号を抜き出す
   * @param {string} url
   * @returns {string}
   */
  _getPageAsUrl(url) {
    url ||= '';

    const ret = url.match(/\?page=(\d+)$/);
    return ret !== null ? Number(ret[1]) : null;
  };
};
