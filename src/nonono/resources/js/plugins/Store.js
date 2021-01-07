export default {
  install(app) {
    const storeDataList = [
      '$history', '$games', '$apps',
      '$posts.latests', '$posts.categories',
    ];

    for (const key of storeDataList) {
      app.config.globalProperties[key] = [];
    }

    /**
     * storeにデータを格納するやつ
     * @param {string} key
     * @param {array}  value
     * @returns {boolean}
     */
    app.$setStore = function(key, value) {
      if (!app.$existsStore(key)) {
        console.error(`${key} is not exist.`);
        return false;
      }
      app.config.globalProperties[key] = value;
      return true;
    };

    /**
     * storeからデータを取り出すやつ
     * @param {string} key
     * @returns {array}
     */
    app.$getStore = function(key) {
      if (!app.$existsStore(key)) {
        console.error(`${key} is not exist.`);
        return [];
      }
      return app.config.globalProperties[key];
    };

    /**
     * storeにデータが有るか確認するやつ
     * @param {string} key
     * @return {boolean}
     */
    app.$hasStore = function(key) {
      return app.$getStore(key).length !== 0;
    };

    /**
     * store内に該当するデータが存在するか
     * @param {string} key
     */
    app.$existsStore = function(key) {
      return key in app.config.globalProperties;
    }

    /**
     * store内の該当データを初期化する
     * @param {string} key
     */
    app.$resetStore = function(key) {
      if (!app.$existsStore(key)) {
        console.error(`${key} is not exist or empty.`);
        return false;
      }
      app.config.globalProperties[key] = [];
      return true;
    }

    /**
     * ページのタイトルをセットする
     * @param {string} title
     * @returns {void}
     */
    app.$setTitle = function(title) {
      if (!title) {
        title = 'ののの茶屋';
      }

      document.title = title;
    }
  },
};
