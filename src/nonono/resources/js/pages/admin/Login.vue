<template>
<div>
  <h2>管理者用ログインページ</h2>

  <div class="content">

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
    login () {
      const redirect = this.$auth.redirect();
      const app = this;
      this.$auth.login({
        data: app.form,
        success: (res) => {
          app.success = true;
        },
        error: (res) => {
          app.has_error = true;
          app.error = res.response.data.error;
        },
        rememberMe: false,
      });
    },
  },
};
</script>

<style>

</style>
