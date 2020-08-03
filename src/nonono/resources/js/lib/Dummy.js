export default {
  /**
   * ゲームのダミー配列を返す
   * @param {number} len
   * @returns {array}
   */
  game(len) {
    return Array(len).fill().map((_, i) => {
      return {
        id:             (i + 1) * -1,
        title:          '',
        release_date:   'XXXX-XX-XX',
        thumbnail_path: '/img/dummy/game.png',
        category:       '－－－',
        infomation:     '',
        url:            '',
      };
    });
  },
}
