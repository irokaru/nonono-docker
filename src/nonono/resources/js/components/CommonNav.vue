<template>
<nav :class="{hidden: isAdminPage()}">
  <h1 title="トップへ"><router-link to="/"><img src="/img/logo.min.png" alt="ののの茶屋"></router-link></h1>

  <ul>
    <li><router-link to="/">とっぷ<span><v-fa :icon="['fas', 'desktop']"/>TOP</span></router-link></li>
    <li><router-link to="/products">ぷろだくと<span><v-fa :icon="['fas', 'box']"/>PRODUCTS</span></router-link></li>
    <li><router-link to="/material">そざい<span><v-fa :icon="['fas', 'folder-open']"/>MATERIAL</span></router-link></li>
    <li><router-link to="/blog">にっき<span><v-fa :icon="['fas', 'book']"/>BLOG</span></router-link></li>
    <li><router-link to="/link">りんく<span><v-fa :icon="['fas', 'link']"/>LINK</span></router-link></li>
    <li><router-link to="/contact">おといあわせ<span><v-fa :icon="['fas', 'envelope']"/>CONTACT</span></router-link></li>
  </ul>
</nav>
</template>

<script>
export default {
  methods: {
    isAdminPage() {
      return this.$route.path.match(/^\/admin/) !== null;
    }
  }
}
</script>

<style>
nav.hidden {
  display: none;
}
</style>
