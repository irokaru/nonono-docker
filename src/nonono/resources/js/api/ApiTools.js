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
  }
};

export default ApiTools;
