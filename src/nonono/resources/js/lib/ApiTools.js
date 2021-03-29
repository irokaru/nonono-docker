const ApiTools = {
  /**
   * エラーオブジェクトからパラメータを作成する
   * @param {Object} e axios error object
   */
  genelateErrorParams (e) {
    if (!e || !e.response) return {status: 'error', message: ''};

    const status = e.response.status || -1;
    const errorMessage = this._getErrorMessageForAdmin(status);

    return {
      status: 'error',
      message: errorMessage,
    };
  },
  /**
   * ステータスのエラーコードから適切な文章を返す
   * @param {number} status ステータスコード
   * @returns {string} エラー文
   */
  _getErrorMessageForAdmin (status) {
    const errorArray = {
      401: 'セッションが切れました。再度ログインして試してください。',
      405: '通信方法が不適切です。作った人に報告してください。',
      422: '送信データが不正です。',
      429: 'データを短時間に送信しすぎです。時間を置いてから試してください。',
      500: '謎のエラーです。作った人に報告してください。',
    };

    if (!errorArray[status]) {
      return '';
    }

    return errorArray[status];
  },

  /**
   * 連想配列からFormDataを作成する
   * @param {object} obj target array
   * @returns {FormData}
   */
  makeFormData (obj) {
    const array = this._objectExpand(obj);
    let params = new FormData();

    for (let [key, value] of Object.entries(array)) {
      if (typeof value === 'boolean') {
        value = value ? 1 : 0;
      }
      params.append(key, value);
    }

    return params;
  },

  /**
   * 複数階層の連想配列から1層の連想配列を作成する
   * @param {object} obj object
   * @param {string} name array name
   */
  _objectExpand (obj, name) {
    let ret = {};

    name = name || '';

    for (let [key, value] of Object.entries(obj)) {
      const keyName = name ? `${name}[${key}]` : key;

      if (value === null) {
        ret[keyName] = value;
        continue;
      }

      if (typeof value === 'object' && !(value instanceof File)) {
        ret = {...ret, ...this._objectExpand(value, keyName)};
      } else {
        ret[keyName] = value;
      }
    }

    return ret;
  },
};

export default ApiTools;
