export default {
  /**
   * フォーマットに合わせた日付を返す
   * @param {string} format
   * @param {string} date
   * @returns {string}
   */
  formatDate(format, date='') {
    const weekdayArray = ['日', '月', '火', '水', '木', '金', '土'];
    const d            = date ? new Date(date) : new Date();
    const year         = d.getFullYear();
    const month        = d.getMonth() + 1;
    const day          = d.getDate();
    const weekday      = weekdayArray[d.getDay()];
    const hours        = d.getHours();
    const minutes      = d.getMinutes();
    const secounds     = d.getSeconds();

    const replaceArray = {
      'YYYY': year,
      'Y' :   year,
      'MM':   ('0' + month).slice(-2),
      'M':    month,
      'dd':   ('0' + day).slice(-2),
      'd':    day,
      'WW':   weekday,
      'hh':   ('0' + hours).slice(-2),
      'h':    hours,
      'mm':   ('0' + minutes).slice(-2),
      'm':    minutes,
      'ss':   ('0' + secounds).slice(-2),
      's':    secounds,
    };

    const replaceStr = '(' + Object.keys(replaceArray).join('|') + ')';
    const regex      = new RegExp(replaceStr, 'g');
    const ret        = format.replace(regex, str => replaceArray[str]);

    return ret;
  },
};
