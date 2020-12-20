<template>
<div>
  <h2>管理者用ページ</h2>

  <div class="content">

    <router-link to="/admin">管理者ページ</router-link>

    <p @click="logout">logout</p>

    <ul>
      <li><router-link to="/admin/history">履歴管理ページ</router-link></li>
      <li><router-link to="/admin/game">ゲーム管理ページ</router-link></li>
      <li><router-link to="/admin/blog">ブログ管理ページ</router-link></li>
    </ul>

  </div>
</div>
</template>

<script>
import LoginApi from '../../api/LoginApi';

export default {
  methods: {
    logout () {
      LoginApi.logout(this);
    },
  },
}
</script>

<style>

</style>
