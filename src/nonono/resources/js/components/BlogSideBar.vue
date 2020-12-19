<template>
<div class="blog-sidebar">
  <h3 class="head"><i class="far fa-clock"></i> しんちゃく</h3>

  <ul class="simple">
    <li v-for="(latest, index) in latests" :key="index">
      <router-link :to="'/blog/post/' + latest.id">{{latest.title}}</router-link>
      <div class="date">({{latest.date}})</div>
    </li>
  </ul>

  <div class="margin-tb-1">
    <router-link to="/blog"><i class="fas fa-angle-double-right"></i>にっき一覧へ</router-link>
  </div>

  <h3><i class="fas fa-tags"></i> かてごりー</h3>

  <ul class="list-box">
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
