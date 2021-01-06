<template>
<div>
  <h2>管理者用ブログ管理ページ</h2>

  <div class="content">

    <router-link to="/admin">管理者ページ</router-link> <v-fa :icon="['fas', 'chevron-right']"/> 管理者用ブログ管理ページ

    <h3>新規記事登録</h3>

    <form class="form" autocomplete="off" onsubmit="return false;" enctype="multipart/form-data">

      <input type="text" v-model="form.title" placeholder="記事のタイトル">
      <input type="text" v-model="form.date" placeholder="yyyy-mm-dd">

      <input type="checkbox" v-model="form.release_flag" id="release_flag" checked="checked">
      <label class="checkbox" for="release_flag">公開する</label>

      <textarea v-model="form.detail" placeholder="本文"></textarea>

      <input type="text" @input="form.categories = splitCategory($event.target.value)" placeholder="カテゴリ1,カテゴリ2">

      <button @click="sendStore(form)">追加</button>
    </form>

    <h3>既存記事一覧</h3>

    <p v-if="posts.length === 0">ブログ記事は無いです</p>

    <ul>
      <li class="post-data arrow" v-for="post in posts" :key="post.id" @click="setUpdateForm(game)">
        {{post.title}}
      </li>
    </ul>

    <h3>既存記事更新</h3>
    <form class="form" autocomplete="off" onsubmit="return false;">
      <!-- TODO: ここに更新用のフォームを作る -->
    </form>
  </div>

</div>

</template>

<script>
import ApiTools from '../../lib/ApiTools';
import DateUtil from '../../lib/DateUtil';
import Post     from '../../model/Post';
import PostApi  from '../../api/PostApi';

export default {
  data() {
    return {
      form: Post.model,
      posts:     [],
      isLoading: false,
      isSenging: false,
    };
  },
  methods: {
    /**
     * 記事一覧を取得する処理
     * @returns {array}
     */
    async getPosts() {
      const api = PostApi.getAll();

      return await api.then(posts => {
        return posts.data;
      }).catch(e => {
        console.log(e);
        return [];
      });
    },

    /**
     * 追加処理
     * @param {object} post
     * @return {boolean}
     */
    async sendStore(post) {
      if (this.isSending || this.isLoading) return;

      this.isSending = true;

      const formatData = ApiTools.makeFormData(post);
      const api        = PostApi.store(formatData);

      const storeResult = await api.then(res => {
        return true;
      }).catch(e => {
        console.log(e);
        return false;
      });

      if (storeResult) {
        this.posts = await this.getPosts();
        this.resetForm();
      }

      this.isSending = false;

      return storeResult;
    },

    /**
     * カテゴリをコンマで区切って配列にして返す
     * @param {string} str
     * @returns {array<string>}
     */
    splitCategory(str) {
      if (str === '') {
        return [];
      }

      return str.split(',').map((item) => item.trim());
    },

    /**
     * 入力フォームを初期化するやつ
     * @returns {void}
     */
    resetForm() {
      this.form = Post.model;
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
  async mounted() {
    this.isLoading = true;

    this.resetForm();

    this.posts = await this.getPosts();

    this.isLoading = false;
  },
}
</script>

<style>

</style>
