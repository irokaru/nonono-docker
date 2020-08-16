<template>
<div>
  <h2>管理者用ゲーム管理ページ</h2>

  <div class="content">

    <router-link to="/admin">管理者ページ</router-link> <i class="fas fa-chevron-right"></i> 管理者用履歴管理ページ

    <h3>新規ゲーム登録</h3>

    <form class="form" autocomplete="off" onsubmit="return false;" enctype="multipart/form-data">

      <input type="text" v-model="form.title" placeholder="ゲームのタイトル">
      <input type="text" v-model="form.release_date" placeholder="yyyy-mm-dd">

      <input type="checkbox" v-model="form.release_flag" id="release_flag" checked="checked">
      <label class="checkbox" for="release_flag">公開する</label>

      <input type="file" accept="image/*" @change="setThumbnailImage">
      <img :src="previewThumbnail">

      <input type="text" v-model="form.thumbnail_name" placeholder="ファイル名(拡張子抜き)">
      <input type="text" v-model="form.category" placeholder="カテゴリ">
      <input type="text" v-model="form.infomation" placeholder="一言コメント">
      <input type="text" v-model="form.url" placeholder="リンク">

      <button @click="sendStore(form)">追加</button>
    </form>

    <h3>既存ゲーム一覧</h3>

    <p v-if="games.length === 0">ゲーム作品は無いです</p>

    <ul>
      <li class="game-data arrow" v-for="game in games" :key="game.id" @click="setUpdateForm(game)">
        {{game.title}}
      </li>
    </ul>

    <h3></h3>

    <h3>既存ゲーム更新</h3>
    <form class="form" autocomplete="off" onsubmit="return false;">
      <!-- TODO: ここに更新用のフォームを作る -->
    </form>
  </div>

</div>
</template>

<script>
import ApiTools  from '../../lib/ApiTools';
import DateUtil  from '../../lib/DateUtil';
import FileInput from '../../lib/FileInput';
import GameApi   from '../../api/GameApi';

/**
 * @typedef {boolean} NewType
 */

export default {
  data() {
    return {
      form: {
        title:          '',
        release_date:   '',
        release_flag:   false,
        thumbnail:      null,
        thumbnail_name: '',
        category:       '',
        infomation:     '',
        url:            '',
      },
      games:            [],
      previewThumbnail: null,
      isLoading:        false,
      isSending:        false,
    };
  },
  methods: {
    /**
     * ゲーム一覧を取得する処理
     * @returns {array}
     */
    async getGame() {
      const api = GameApi.getAll();

      return await api.then(games => {
        return games.data;
      }).catch(e => {
        console.log(e);
        return [];
      });
    },

    /**
     * 追加処理
     * @param {object} game
     * @return {boolean}
     */
    async sendStore(game) {
      if (this.isSending || this.isLoading) return;

      const formatData = ApiTools.makeFormData(game);

      this.isSending = true;
      const api = GameApi.store(formatData);

      const storeResult = await api.then(res => {
        return true;
      }).catch(e => {
        console.log(e);
        return false;
      });

      if (storeResult) {
        Vue.$resetStore('$games');
        this.games = await this.getGame();
        this.resetForm();
      }

      this.isSending = false;

      return storeResult;
    },

    /**
     * 更新処理
     * @param {object} game
     * @returns {boolean}
     */
    async sendUpdate(game) {
      if (this.isSending || this.isLoading) return;

      this.isSending = true;
      const api = GameApi.update(game);

      const updateResult = await api.then(res => {
        return true;
      }).catch(e => {
        console.log(e);
        return false;
      });

      if (storeResult) {
        Vue.$resetStore('$games');
        this.games = await this.getGame();
      }

      this.isSending = false;

      return updateResult;
    },

    setThumbnailImage(e) {
      const inputResult     = FileInput.input(e, 'image/');
      this.form.thumbnail        = inputResult.file;
      this.previewThumbnail = inputResult.blob;
    },

    /**
     * 入力フォームを初期化するやつ
     * @returns {void}
     */
    resetForm() {
      this.form = {
        title:          '',
        release_date:   DateUtil.formatDate('YYYY-MM-dd'),
        release_flag:   false,
        thumbnail:      null,
        thumbnail_name: '',
        category:       '',
        infomation:     '',
        url:            '',
      };
    },

    /**
     * 更新フォームに情報を入力するやつ
     * @returns {void}
     */
    setUpdateForm(game) {
      // TODO: オブジェクトを移す
      return {};
    },
  },
  async mounted () {
    this.isLoading = true;

    this.resetForm();

    this.games = await this.getGame();
    this.isLoading = false;
  },
}
</script>

<style>

</style>
