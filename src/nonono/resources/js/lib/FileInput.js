export default {
  /**
   * 入力されたファイルを返す
   * @param {Event} event
   * @param {string} typeStartWith
   * @returns {object}
   */
  input(event, typeStartWith='') {
    let ret = {
      file: null,
      blob: null,
    };

    if (!event.target.files[0]) {
      return ret;
    }

    const file = event.target.files[0];
    if (typeStartWith !== '' && !file.type.startsWith(typeStartWith)) {
      return ret;
    }

    ret.file = file;
    ret.blob = window.URL.createObjectURL(file);
    return ret;
  }
};
