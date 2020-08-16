<template>
<div>
  <h2>管理者用ログインページ</h2>

  <div class="content">

    <p>{{error}}</p>
    <form class="form" autocomplete="off" @submit.prevent="login" v-if="!success" method="post">
      <dt>name</dt>
      <dd><input type="text" v-model="form.name"></dd>

      <dt>password</dt>
      <dd><input type="password" v-model="form.password"></dd>

      <button type="sibmit">Login</button>

    </form>

  </div>

</div>
</template>

<script>
import LoginApi from '../../api/LoginApi';

export default {
  data() {
    return {
      form: {
        name: null,
        password: null,
      },
      success: false,
      has_error: false,
      error: ''
    }
  },
  methods: {
    /**
     * ログイン処理
     */
    login () {
      LoginApi.login(this, this.form);
    },
  },
};
</script>

<style>

</style>
