<template>
<div class="blog-sidebar">
  <h3 class="head"><v-fa :icon="['far', 'clock']"/> しんちゃく</h3>

  <ul class="simple">
    <li v-for="(latest, index) in latests" :key="index">
      <router-link :to="'/blog/post/' + latest.id">{{latest.title}}</router-link>
      <div class="date">({{latest.date}})</div>
    </li>
  </ul>

  <div class="margin-tb-1">
    <router-link to="/blog"><v-fa :icon="['fas', 'angle-double-right']"/> にっき一覧へ</router-link>
  </div>

  <h3><v-fa :icon="['fas', 'tags']"/> かてごりー</h3>

  <ul class="simple">
    <li v-for="(category, index) in categories" :key="index">
      <router-link :to="'/blog/category/' + category.category">{{category.category}} ({{category.count}})</router-link>
    </li>
  </ul>

</div>
</template>

<script>
export default {
  props: {
    latests:    Array,
    categories: Array,
  },
}
</script>

<style>

</style>
