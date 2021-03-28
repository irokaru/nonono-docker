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
   * @param {number} length
   * @param {array}
   */
  list(length) {
    if (!Validator.isInteger(length) || length < 0) {
      return [];
    }

    const pageLength = this.last - this.first + 1;

    if (pageLength < length) {
      return Array.from({length: pageLength}, (k, v) => v + this.first);
    }

    const half = parseInt(length / 2);

    if (this.current - half < this.first) {
      return Array.from({length: length}, (k, v) => v + this.first);
    }

    if (this.current + half > this.last) {
      return Array.from({length: length}, (k, v) => this.last - length + v + 1);
    }

    const start = this.current - half;

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
