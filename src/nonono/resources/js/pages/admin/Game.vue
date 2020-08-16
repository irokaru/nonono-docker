<template>
<div>
  <h2>管理者用ゲーム管理ページ</h2>

  <div class="content">

    <router-link to="/admin">管理者ページ</router-link> <i class="fas fa-chevron-right"></i> 管理者用履歴管理ページ

    <h3>新規ゲーム登録</h3>

    <form class="form" autocomplete="off" onsubmit="return false;" enctype="multipart/form-data">

      <input type="text" v-model="form.title" placeholder="ゲームのタイトル">
      <input type="text" v-model="form.delease_date" placeholder="yyyy-mm-dd">

      <input type="checkbox" v-model="form.release_flag" id="release_flag" checked="checked">
      <label class="checkbox" for="release_flag">公開する</label>

      <input type="file" accept="image/*" @change="setThumbnailImage">
      <img :src="previewThumbnail">

      <input type="text" v-model="form.thumbnail_name" placeholder="ファイル名(拡張子抜き)">
      <input type="text" v-model="form.category" placeholder="カテゴリ">
      <input type="text" v-model="form.infomation" placeholder="一言コメント">
      <input type="text" v-model="form.url" placeholder="リンク">
    </form>

  </div>


</div>
</template>

<script>
import FileInput from '../../lib/FileInput';

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
    setThumbnailImage(e) {
      const inputResult     = FileInput.input(e, 'image/');
      this.thumbnail        = inputResult.file;
      this.previewThumbnail = inputResult.blob;
    },
  },
  async mounted () {
    this.isLoading = true;

    this.isLoading = false;
  },
}
</script>

<style>

</style>
