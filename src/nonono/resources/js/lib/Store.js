export default {
  install(Vue, options) {
    Vue.myGlobalMethod = function () {
      console.log('this is global');
    }

    const storeDataList = [
      '$history', '$games', '$apps',
    ];

    for (const key of storeDataList) {
      Vue.util.defineReactive(Vue.prototype, key, []);
    }

    /**
     * storeにデータを格納するやつ
     * @param {string} key
     * @param {array}  value
     * @returns {boolean}
     */
    Vue.$setStore = function(key, value) {
      if (!Vue.prototype[key]) {
        console.error(`${key} is not exist.`);
        return false;
      }
      Vue.prototype[key] = value;
      return true;
    };

    /**
     * storeからデータを取り出すやつ
     * @param {string} key
     * @returns {array}
     */
    Vue.$getStore = function(key) {
      if (!Vue.prototype[key]) {
        console.error(`${key} is not exist or empty.`);
        return [];
      }
      return Vue.prototype[key];
    };

    /**
     * storeにデータが有るか確認するやつ
     * @param {string} key
     * @return {boolean}
     */
    Vue.$hasStore = function(key) {
      return Vue.$getStore(key).length !== 0;
    };
  },
};
