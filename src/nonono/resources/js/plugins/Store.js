export default {
  install(Vue, options) {
    const storeDataList = [
      '$history', '$games', '$apps',
      '$posts', '$posts.categories',
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
      if (!Vue.$existsStore(key)) {
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
      if (!Vue.$existsStore(key)) {
        console.error(`${key} is not exist.`);
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

    /**
     * store内に該当するデータが存在するか
     * @param {string} key
     */
    Vue.$existsStore = function(key) {
      return key in Vue.prototype;
    }

    /**
     * store内の該当データを初期化する
     * @param {string} key
     */
    Vue.$resetStore = function(key) {
      if (!Vue.$existsStore(key)) {
        console.error(`${key} is not exist or empty.`);
        return false;
      }
      Vue.prototype[key] = [];
      return true;
    }
  },
};
