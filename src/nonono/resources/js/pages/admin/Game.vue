<template>
<div>
  <h2>管理者用ゲーム管理ページ</h2>

  <div class="content">

    <router-link to="/admin">管理者ページ</router-link> <i class="fas fa-chevron-right"></i> 管理者用履歴管理ページ

    <h3>新規ゲーム登録</h3>

    <form class="form" autocomplete="off" onsubmit="return false;" enctype="multipart/form-data">


    </form>

  </div>


</div>
</template>

<script>
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
      games: [],
      isLoading: false,
      isSending: false,
    };
  },
  methods: {

  },
  async mounted () {
    this.isLoading = true;

    this.isLoading = false;
  },
}
</script>

<style>

</style>
