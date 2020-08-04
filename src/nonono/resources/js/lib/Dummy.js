export default {
  /**
   * 履歴のダミー配列を返す
   * @param {number} len
   * @returns {array}
   */
  history(len) {
    return this._makeDummyArray({
      id:          0,
      date:        'XXXX-XX-XX',
      discription: '－－－',
    }, len);
  },


  /**
   * ゲームのダミー配列を返す
   * @param {number} len
   * @returns {array}
   */
  game(len) {
    return this._makeDummyArray({
      id:             0,
      title:          '',
      release_date:   'XXXX-XX-XX',
      thumbnail_path: '/img/dummy/game.png',
      category:       '－－－',
      infomation:     '',
      url:            '',
    }, len);
  },

  /**
   * ダミー配列を良い感じに生成する
   * @param {object} data
   * @param {number} len
   * @returns {array}
   */
  _makeDummyArray(data, len = 1) {
    return Array(len).fill().map((_, i) => {
      data.id = (i + 1) * -1;
      return data;
    });
  }
}
